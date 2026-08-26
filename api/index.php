<?php
require_once __DIR__ . '/../controller/AuthController.php';
require_once __DIR__ . '/../controller/UserController.php';
require_once __DIR__ . '/../controller/EventController.php';
require_once __DIR__ . '/../controller/RaceController.php';
require_once __DIR__ . '/../controller/TypeRaceController.php';
require_once __DIR__ . '/../controller/CategoryController.php';
require_once __DIR__ . '/../controller/ProductController.php';
require_once __DIR__ . '/../controller/ParticipationController.php';
require_once __DIR__ . '/../controller/RouteController.php';
require_once __DIR__ . '/../model/entidad/Evento.php';
require_once __DIR__ . '/../model/entidad/Ruta.php';

use PHPMailer\PHPMailer\PHPMailer;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function jsonResponse($payload, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function readJsonBody()
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function requireAuthUserId()
{
    if (!isset($_SESSION['ID_USUARIO'])) {
        jsonResponse(['success' => false, 'error' => 'Usuario no autenticado']);
    }

    return $_SESSION['ID_USUARIO'];
}

function serializeProductCard($producto)
{
    $stock = $producto->getStock();
    return [
        'id' => $producto->getIdProducto(),
        'name' => $producto->getNombre(),
        'description' => $producto->getDescripcion(),
        'price' => (float) $producto->getPrecio(),
        'fecha_publicacion' => $producto->getFechaPublicacion(),
        'vendedor_id' => $producto->getVendedorId(),
        'categoria' => $producto->getCategoria(),
        'stock' => (int) $stock,
        'image' => $producto->getImagenUrl(),
        'status' => $stock === 0 ? 'out-of-stock' : ($stock < 10 ? 'low-stock' : 'active')
    ];
}

$routes = require __DIR__ . '/../routes/api.php';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$path = preg_replace('#/index\.php$#', '', $path);
$routeKey = ($_SERVER['REQUEST_METHOD'] ?? 'GET') . ' ' . $path;

if (!isset($routes[$routeKey])) {
    jsonResponse(['success' => false, 'error' => 'Ruta API no encontrada'], 404);
}

try {
    switch ($routeKey) {
        case 'POST /api/login':
            $data = readJsonBody();
            $username = $data['user'] ?? '';
            $password = $data['pass'] ?? '';
            $authController = new AuthController();
            jsonResponse($authController->login($username, $password));
            break;

        case 'GET /api/logout':
            session_destroy();
            header('Location: ../view/iniciar_sesion.html');
            exit;

        case 'POST /api/register':
            $userController = new UserController();
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS);
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_SPECIAL_CHARS);
            $fechaNacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_SPECIAL_CHARS);
            $fechaRegistro = date('Y-m-d H:i:s');
            $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_SPECIAL_CHARS);
            $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
            $contrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_SPECIAL_CHARS);
            $nombreTienda = $rol === 'vendedor'
                ? filter_input(INPUT_POST, 'nombre_tienda', FILTER_SANITIZE_SPECIAL_CHARS)
                : null;

            if (!$nombre || !$apellido || !$correo || !$contrasena || !$sexo || !$fechaNacimiento || !$fechaRegistro || !$rol) {
                jsonResponse([
                    'message' => 'All fields are required.',
                    'ruta' => null,
                    'user' => null
                ]);
            }

            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
            $user = $userController->agregar(
                $rol,
                $nombre,
                $apellido,
                $correo,
                $hashedPassword,
                $sexo,
                $fechaNacimiento,
                $fechaRegistro,
                null,
                null,
                $nombreTienda
            );

            jsonResponse([
                'message' => $user ? 'User registered successfully' : 'User no registered',
                'ruta' => $user ? './iniciar_sesion.html' : 'registrarse.html',
                'user' => $user
            ]);
            break;

        case 'POST /api/password/recover':
            require_once __DIR__ . '/../vendor/autoload.php';
            $email = $_POST['email'] ?? null;
            $userController = new UserController();

            $usuario = $email ? $userController->buscarPorCorreo($email) : null;
            if (!$usuario) {
                jsonResponse(['success' => false, 'message' => 'Correo no encontrado.']);
            }

            $token = bin2hex(random_bytes(50));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $guardado = $userController->guardarToken($email, $token, $expira);

            if (!$guardado) {
                jsonResponse([
                    'success' => false,
                    'message' => 'Error al guardar el token de recuperación. Intenta nuevamente.'
                ]);
            }

            $link = "http://localhost/running-web/view/restablecer_contrasena.php?token=$token";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'webrunning008@gmail.com';
            $mail->Password = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('webrunning008@gmail.com', 'Running Web');
            $mail->addAddress($email);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recupera tu contraseña en Running Web';
            $mail->Body = "
                <h2>Hola</h2>
                <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <b>Running Web</b>.</p>
                <p>Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                <p><a href='$link' style='color:#2563eb;font-weight:bold;'>Restablecer mi contraseña</a></p>
                <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                <br>
                <p style='color:#888;'>Gracias por confiar en nosotros.<br>El equipo de Running Web</p>
            ";
            $mail->isHTML(true);

            if ($mail->send()) {
                jsonResponse(['success' => true, 'message' => 'Correo enviado. Revisa tu bandeja.']);
            }

            jsonResponse(['success' => false, 'message' => 'Error al enviar el correo.']);
            break;

        case 'POST /api/password/reset':
            $userController = new UserController();
            $data = readJsonBody();
            $token = $data['token'] ?? '';
            $nueva = $data['password'] ?? '';
            $nuevaConfirm = $data['confirm_password'] ?? '';

            if ($nueva !== $nuevaConfirm) {
                jsonResponse(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            }

            $usuario = $userController->verificarToken($token);
            if (!$usuario) {
                jsonResponse(['success' => false, 'message' => 'Token inválido.']);
            }

            $nuevaHashed = password_hash($nueva, PASSWORD_DEFAULT);
            $userController->actualizarContrasena($usuario['id_usuario'], $nuevaHashed);
            jsonResponse(['success' => true, 'message' => 'Contraseña actualizada con éxito.']);
            break;

        case 'GET /api/eventos':
            $eventController = new EventController();
            $eventos = $eventController->index();

            if (isset($_GET['pagina']) || isset($_GET['porPagina'])) {
                $pagina = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
                $porPagina = isset($_GET['porPagina']) ? max(1, (int) $_GET['porPagina']) : 20;
                $offset = ($pagina - 1) * $porPagina;
                $slice = array_slice($eventos, $offset, $porPagina);
                jsonResponse([
                    'eventos' => $slice,
                    'total' => count($eventos)
                ]);
            }

            jsonResponse($eventos);
            break;

        case 'GET /api/eventos/admin':
            if (($_GET['action'] ?? null) !== 'obtenerPorId' || !isset($_GET['id'])) {
                jsonResponse(['success' => false, 'error' => 'Solicitud no válida']);
            }

            $eventController = new EventController();
            $evento = $eventController->obtenerPorId($_GET['id']);
            if (!$evento) {
                jsonResponse(['success' => false, 'error' => 'Evento no encontrado']);
            }

            jsonResponse([
                'success' => true,
                'evento' => [
                    'id' => $evento->getIdEvento(),
                    'nombre' => $evento->getNombreEvento(),
                    'tipo' => $evento->getTipoEvento(),
                    'fecha' => $evento->getFechaEvento(),
                    'hora' => $evento->getHoraEvento(),
                    'descripcion' => $evento->getDescripcionEvento(),
                    'ubicacion_id' => $evento->getUbicacionId(),
                    'id_patrocinador' => $evento->getIdPatrocinador()
                ]
            ]);
            break;

        case 'POST /api/eventos/admin':
            $eventController = new EventController();
            $input = readJsonBody();
            if (empty($input)) {
                $input = $_POST;
            }

            $action = $input['action'] ?? null;
            if ($action === 'agregar') {
                $nombre = $input['nombre'] ?? null;
                $tipo = $input['tipo'] ?? null;
                $fecha = $input['fecha'] ?? null;
                $hora = $input['hora'] ?? null;
                $descripcion = $input['descripcion'] ?? null;
                $idPatrocinador = $input['id_patrocinador'] ?? null;
                $ubicacionId = $input['ubicacion_id'] ?? null;

                $missingFields = [];
                if (empty($nombre)) $missingFields[] = 'nombre';
                if (empty($tipo)) $missingFields[] = 'tipo';
                if (empty($fecha)) $missingFields[] = 'fecha';
                if (empty($hora)) $missingFields[] = 'hora';
                if (empty($descripcion)) $missingFields[] = 'descripcion';
                if (!isset($idPatrocinador) || $idPatrocinador === null || $idPatrocinador === '') $missingFields[] = 'id_patrocinador';
                if (!isset($ubicacionId) || $ubicacionId === null || $ubicacionId === '') $missingFields[] = 'ubicacion_id';

                if (!empty($missingFields)) {
                    jsonResponse(['success' => false, 'error' => 'Faltan datos obligatorios: ' . implode(', ', $missingFields)]);
                }

                $ok = $eventController->agregar($nombre, $tipo, $fecha, $hora, $descripcion, $idPatrocinador, $ubicacionId);
                jsonResponse($ok ? ['success' => true] : ['success' => false, 'error' => 'Error al agregar evento']);
            }

            if ($action === 'actualizar') {
                $idEvento = $input['id_evento'] ?? null;
                $nombre = $input['nombre'] ?? null;
                $tipo = $input['tipo'] ?? null;
                $fecha = $input['fecha'] ?? null;
                $hora = $input['hora'] ?? null;
                $descripcion = $input['descripcion'] ?? null;
                $patrocinador = $input['patrocinador'] ?? null;
                $ubicacionId = $input['ubicacion_id'] ?? null;

                if (!$idEvento || !$nombre || !$tipo || !$fecha || !$descripcion || !$patrocinador || !$ubicacionId) {
                    jsonResponse(['success' => false, 'error' => 'Faltan datos para actualizar el evento']);
                }

                $evento = new Evento($nombre, $tipo, $fecha, $hora, $descripcion, $patrocinador);
                $evento->setIdEvento($idEvento);
                $evento->setUbicacionId((int) $ubicacionId);

                $ok = $eventController->actualizar($evento);
                jsonResponse($ok ? ['success' => true] : ['success' => false, 'error' => 'Error al actualizar el evento']);
            }

            if ($action === 'eliminar') {
                $idEvento = $input['id_evento'] ?? null;
                if (!$idEvento) {
                    jsonResponse(['success' => false, 'error' => 'ID de evento no recibido']);
                }

                $ok = $eventController->eliminar($idEvento);
                jsonResponse($ok ? ['success' => true] : ['success' => false, 'error' => 'Error al eliminar el evento']);
            }

            jsonResponse(['success' => false, 'error' => 'Acción no reconocida']);
            break;

        case 'GET /api/carreras':
            $raceController = new RaceController();
            if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idCarrera'])) {
                jsonResponse($raceController->serializar($_GET['idCarrera']));
            }

            jsonResponse($raceController->listar());
            break;

        case 'POST /api/carreras':
            $raceController = new RaceController();
            $typeRaceController = new TypeRaceController();
            $eventController = new EventController();
            $categoryController = new CategoryController();
            $routeController = new RouteController();
            $action = $_POST['action'] ?? null;

            if ($action !== 'crear') {
                jsonResponse(['error' => 'Acción no soportada', 'success' => false], 400);
            }

            $distancia = $_POST['distancia'] ?? null;
            $idTipoCarrera = $_POST['idTipoCarrera'] ?? null;
            $idCategoria = $_POST['idCategoria'] ?? null;
            $idRuta = $_POST['idRuta'] ?? null;

            if (!$distancia || !$idTipoCarrera || !$idCategoria || !$idRuta) {
                throw new Exception('Faltan datos obligatorios para crear la carrera');
            }

            $tipoCarrera = $typeRaceController->obtenerPorId($idTipoCarrera);
            $ruta = $routeController->obtener($idRuta);
            $categoria = $categoryController->obtenerPorId($idCategoria);

            $idEvento = $eventController->agregar(
                $_POST['nombre'] ?? '',
                'carrera',
                $_POST['fecha'] ?? '',
                $_POST['hora'] ?? '',
                $_POST['descripcion'] ?? '',
                null,
                $_POST['direccion'] ?? ''
            );

            $evento = $eventController->obtenerPorId($idEvento);
            if (!$evento) {
                throw new Exception('Evento no encontrado');
            }
            if (!$tipoCarrera) {
                throw new Exception('Tipo de carrera no encontrado');
            }

            $resultado = $raceController->insertar($distancia, $evento, $tipoCarrera, $categoria, $ruta);
            jsonResponse([
                'success' => true,
                'mensaje' => 'Carrera creada exitosamente',
                'id' => $resultado
            ]);
            break;

        case 'GET /api/carreras/metodos':
            $raceController = new RaceController();
            if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idCarrera'])) {
                jsonResponse($raceController->serializar($_GET['idCarrera']));
            }

            jsonResponse($raceController->listar());
            break;

        case 'POST /api/carreras/metodos':
            $raceController = new RaceController();
            $eventController = new EventController();
            $typeRaceController = new TypeRaceController();
            $categoryController = new CategoryController();
            $routeController = new RouteController();
            $input = readJsonBody();

            if (!$input || !isset($input['distancia'], $input['id_evento'], $input['tipo_carrera_id'], $input['id_categoria'], $input['id_ruta'])) {
                jsonResponse([
                    'error' => 'Datos incompletos para crear una carrera',
                    'success' => false
                ], 400);
            }

            $distancia = (float) $input['distancia'];
            $evento = $eventController->obtenerPorId((int) $input['id_evento']);
            $tipoCarrera = $typeRaceController->obtenerPorId((int) $input['tipo_carrera_id']);
            $categoria = $categoryController->obtenerPorId((int) $input['id_categoria']);
            $ruta = $routeController->obtener((int) $input['id_ruta']);

            if (!$evento || !$tipoCarrera || !$categoria || !$ruta) {
                throw new Exception('No se pudo resolver evento, tipo, categoría o ruta para crear la carrera');
            }

            $resultado = $raceController->insertar($distancia, $evento, $tipoCarrera, $categoria, $ruta);
            if ($resultado) {
                jsonResponse(['message' => 'Carrera insertada exitosamente', 'success' => true]);
            }

            throw new Exception('No se pudo insertar la carrera');

        case 'GET /api/productos':
            $productController = new ProductController();
            $vendedorId = $_GET['vendedor_id'] ?? null;
            $productos = $vendedorId
                ? $productController->obtenerPorVendedor($vendedorId)
                : $productController->listar();

            $respuesta = array_map('serializeProductCard', $productos);
            jsonResponse($respuesta);
            break;

        case 'POST /api/productos':
            $productController = new ProductController();
            $data = readJsonBody();
            $required = ['name', 'description', 'price', 'vendedor_id', 'categoria', 'stock', 'image'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido.");
                }
            }

            $result = $productController->agregar(
                $data['name'],
                $data['description'],
                (float) $data['price'],
                date('Y-m-d'),
                $data['vendedor_id'],
                $data['categoria'],
                (int) $data['stock'],
                $data['image']
            );

            jsonResponse([
                'success' => (bool) $result,
                'msg' => $result ? 'Producto creado correctamente.' : 'No se pudo crear el producto.'
            ]);
            break;

        case 'PUT /api/productos':
            $productController = new ProductController();
            $data = readJsonBody();
            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Se requiere el ID del producto para actualizar.');
            }

            $required = ['name', 'description', 'price', 'vendedor_id', 'categoria', 'stock', 'image'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido.");
                }
            }

            $result = $productController->actualizar(
                $id,
                $data['name'],
                $data['description'],
                (float) $data['price'],
                date('Y-m-d'),
                $data['vendedor_id'],
                $data['categoria'],
                (int) $data['stock'],
                $data['image']
            );

            jsonResponse([
                'success' => (bool) $result,
                'msg' => $result ? 'Producto actualizado correctamente.' : 'No se pudo actualizar el producto.'
            ]);
            break;

        case 'DELETE /api/productos':
            $productController = new ProductController();
            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Se requiere el ID del producto para eliminar.');
            }

            $result = $productController->eliminar($id);
            jsonResponse([
                'success' => (bool) $result,
                'msg' => $result ? 'Producto eliminado correctamente.' : 'No se pudo eliminar el producto.'
            ]);
            break;

        case 'GET /api/productos/paginados':
            $productController = new ProductController();
            if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idProducto'])) {
                jsonResponse($productController->obtenerPorId($_GET['idProducto']));
            }

            $pagina = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
            $porPagina = isset($_GET['porPagina']) ? max(1, (int) $_GET['porPagina']) : 20;
            $offset = ($pagina - 1) * $porPagina;

            $productos = $productController->obtenerPaginados($offset, $porPagina);
            $total = $productController->contar();
            if ($productos === null || $productos === false) {
                throw new Exception('Error al obtener productos de la base de datos');
            }

            $respuesta = array_map(function ($producto) {
                return [
                    'id' => $producto->getIdProducto(),
                    'nombre' => $producto->getNombre(),
                    'descripcion' => $producto->getDescripcion(),
                    'precio' => $producto->getPrecio(),
                    'stock' => $producto->getStock(),
                    'categoria' => $producto->getCategoria()
                ];
            }, $productos);

            jsonResponse(['productos' => $respuesta, 'total' => $total]);
            break;

        case 'DELETE /api/productos/paginados':
            $productController = new ProductController();
            $data = readJsonBody();
            $idProducto = $data['idProducto'] ?? null;
            if (!$idProducto) {
                throw new Exception('Se requiere el ID del producto para eliminar.');
            }

            $result = $productController->eliminar($idProducto);
            jsonResponse([
                'success' => (bool) $result,
                'msg' => $result ? 'Producto eliminado correctamente.' : 'No se pudo eliminar el producto.'
            ]);
            break;

        case 'GET /api/usuarios':
            $userController = new UserController();
            if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idUsuario'])) {
                jsonResponse($userController->obtenerPorId($_GET['idUsuario']));
            }

            $pagina = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
            $porPagina = isset($_GET['porPagina']) ? max(1, (int) $_GET['porPagina']) : 20;
            $offset = ($pagina - 1) * $porPagina;
            $usuarios = $userController->obtenerPaginados($offset, $porPagina);
            $total = $userController->contar();

            if ($usuarios === null || $usuarios === false) {
                throw new Exception('Error al obtener usuarios de la base de datos');
            }

            $respuesta = array_map(function ($usuario) {
                return [
                    'id' => $usuario->getIdUsuario(),
                    'nombre' => $usuario->getNombre(),
                    'apellido' => $usuario->getApellido(),
                    'email' => $usuario->getCorreo(),
                    'sexo' => $usuario->getSexo(),
                    'fechaNacimiento' => $usuario->getFechaNacimiento(),
                    'rol' => $usuario->getRol()
                ];
            }, $usuarios);

            jsonResponse(['usuarios' => $respuesta, 'total' => $total]);
            break;

        case 'POST /api/usuarios':
            $userController = new UserController();
            $data = readJsonBody();
            $required = ['nombre', 'apellido', 'email', 'sexo', 'fechaNacimiento', 'rol'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido.");
                }
            }

            $result = $userController->agregar(
                $data['rol'],
                $data['nombre'],
                $data['apellido'],
                $data['email'],
                $data['contrasena'] ?? '',
                $data['sexo'],
                $data['fechaNacimiento'],
                date('Y-m-d H:i:s')
            );

            if ($result === false) {
                throw new Exception('Error al agregar el usuario');
            }

            jsonResponse(['success' => true, 'msg' => 'Usuario agregado correctamente']);
            break;

        case 'DELETE /api/usuarios':
            $userController = new UserController();
            $data = readJsonBody();
            if (empty($data['idUsuario'])) {
                throw new Exception('El campo idUsuario es requerido.');
            }

            $result = $userController->eliminar($data['idUsuario']);
            if ($result === false) {
                throw new Exception('Error al eliminar el usuario');
            }

            jsonResponse(['success' => true, 'msg' => 'Usuario eliminado correctamente']);
            break;

        case 'GET /api/profile':
            requireAuthUserId();
            jsonResponse(['id_usuario' => $_SESSION['ID_USUARIO']]);
            break;

        case 'GET /api/participar':
            $participationController = new ParticipationController();
            if (isset($_GET['check']) && (int) $_GET['check'] === 1) {
                if (!isset($_GET['id_evento']) || !isset($_SESSION['ID_USUARIO'])) {
                    jsonResponse(['exists' => false]);
                }

                $exists = $participationController->existe($_SESSION['ID_USUARIO'], $_GET['id_evento']);
                jsonResponse(['exists' => $exists]);
            }

            jsonResponse(['success' => false, 'message' => 'Método no permitido']);
            break;

        case 'POST /api/participar':
            $participationController = new ParticipationController();
            $idEvento = $_POST['id_evento'] ?? null;
            if (!isset($_SESSION['ID_USUARIO'])) {
                jsonResponse(['success' => false, 'message' => 'Usuario no autenticado']);
            }

            $resultado = $participationController->agregar($_SESSION['ID_USUARIO'], $idEvento);
            if ($resultado) {
                jsonResponse(['success' => true, 'message' => 'Participación registrada']);
            }

            jsonResponse(['success' => false, 'message' => 'Error al registrar la participación']);
            break;

        case 'GET /api/participaciones':
            $idUsuario = requireAuthUserId();
            $participationController = new ParticipationController();
            $participaciones = $participationController->obtenerPorUsuario($idUsuario);

            $result = [];
            foreach ($participaciones as $participacion) {
                $evento = $participacion->getEvento();
                $idCarrera = property_exists($participacion, 'id_carrera') ? $participacion->id_carrera : null;
                $distancia = property_exists($participacion, 'distancia') ? $participacion->distancia : null;
                $result[] = [
                    'id_evento' => $evento->getIdEvento(),
                    'nombre_evento' => $evento->getNombreEvento(),
                    'fecha_evento' => $evento->getFechaEvento(),
                    'hora_evento' => $evento->getHoraEvento(),
                    'descripcion_evento' => $evento->getDescripcionEvento(),
                    'id_carrera' => $idCarrera,
                    'distancia' => $distancia
                ];
            }

            jsonResponse(['success' => true, 'participaciones' => $result]);
            break;

        case 'POST /api/participaciones/eliminar':
            $idUsuario = requireAuthUserId();
            $idEvento = $_POST['id_evento'] ?? null;
            if (!$idEvento) {
                jsonResponse(['success' => false, 'error' => 'ID de evento no recibido']);
            }

            $participationController = new ParticipationController();
            $resultado = $participationController->eliminar($idUsuario, $idEvento);
            if ($resultado) {
                jsonResponse(['success' => true]);
            }

            jsonResponse(['success' => false, 'error' => 'No se pudo eliminar la participación']);
            break;

        case 'GET /api/rutas':
            $routeController = new RouteController();
            $routeId = $_GET['id'] ?? null;
            if (!$routeId) {
                throw new Exception('ID de ruta no proporcionado', 400);
            }

            $ruta = $routeController->obtener($routeId);
            if (!$ruta) {
                throw new Exception('Ruta no encontrada', 404);
            }

            jsonResponse([
                'success' => true,
                'data' => [
                    'id' => $ruta->getIdRuta(),
                    'nombre' => $ruta->getNombreRuta(),
                    'puntos' => $ruta->getPuntosRuta(),
                    'distancia' => json_encode($ruta->getDistanciaRuta(), true),
                    'fecha_creacion' => $ruta->getFechaCreacionRuta()
                ]
            ]);
            break;

        case 'POST /api/rutas':
            $routeController = new RouteController();
            $input = readJsonBody();
            if (empty($input)) {
                throw new Exception('Formato JSON inválido', 400);
            }

            if (!isset($input['usuario_id'], $input['nombre'], $input['puntos'])) {
                throw new Exception('Datos incompletos. Se requieren usuario_id, nombre y puntos', 400);
            }
            if (!is_numeric($input['usuario_id']) || $input['usuario_id'] <= 0) {
                throw new Exception('ID de usuario inválido', 400);
            }
            if (strlen(trim($input['nombre'])) < 3) {
                throw new Exception('El nombre de la ruta debe tener al menos 3 caracteres', 400);
            }
            if (!is_array($input['puntos']) || count($input['puntos']) < 2) {
                throw new Exception('La ruta debe tener al menos 2 puntos geográficos', 400);
            }

            foreach ($input['puntos'] as $punto) {
                if (!isset($punto[0], $punto[1]) || !is_numeric($punto[0]) || !is_numeric($punto[1])) {
                    throw new Exception('Estructura de puntos inválida. Se espera [[lat,lng],...]', 400);
                }
            }

            $distancia = $routeController->calcularDistancia($input['puntos']);
            $ruta = new Ruta();
            $ruta->setUsuarioId($input['usuario_id']);
            $ruta->setNombreRuta(htmlspecialchars(trim($input['nombre'])));
            $ruta->setPuntosRuta($input['puntos']);
            $ruta->setDistanciaRuta($distancia);
            $ruta->setFechaCreacionRuta(date('Y-m-d H:i:s'));

            $rutaGuardada = $routeController->guardar($ruta);
            if (!$rutaGuardada) {
                throw new Exception('Error al persistir la ruta en la base de datos', 500);
            }

            jsonResponse([
                'success' => true,
                'message' => 'Ruta guardada correctamente',
                'data' => [
                    'id' => $rutaGuardada->getIdRuta(),
                    'nombre' => htmlspecialchars(trim($input['nombre'])),
                    'distancia' => $rutaGuardada->getDistanciaRuta(),
                    'puntos_count' => count($rutaGuardada->getPuntosRuta())
                ]
            ]);
            break;

        case 'GET /api/categorias-tipos':
            $categoryController = new CategoryController();
            $typeRaceController = new TypeRaceController();
            $categorias = $categoryController->listar();
            $tiposCarrera = $typeRaceController->listar();

            if ($categorias === null || $categorias === false) {
                throw new Exception('Error al obtener categorías de la base de datos');
            }
            if ($tiposCarrera === null || $tiposCarrera === false) {
                throw new Exception('Error al obtener tipos de carrera de la base de datos');
            }

            $respuesta = [
                'success' => true,
                'categorias' => [],
                'tiposCarrera' => []
            ];

            foreach ($categorias as $categoria) {
                $respuesta['categorias'][] = [
                    'id' => $categoria->getIdCategoria(),
                    'nombre' => $categoria->getNombre()
                ];
            }

            foreach ($tiposCarrera as $tipo) {
                $respuesta['tiposCarrera'][] = [
                    'id' => $tipo->getIdTipoCarrera(),
                    'nombre' => $tipo->getNombre()
                ];
            }

            jsonResponse($respuesta);
            break;

        default:
            jsonResponse(['success' => false, 'error' => 'Ruta API no manejada'], 404);
            break;
    }
} catch (PDOException $e) {
    error_log('[api] ' . $e->getMessage());
    jsonResponse([
        'success' => false,
        'error' => 'Ocurrió un error al procesar la solicitud. Inténtalo de nuevo más tarde.'
    ], 500);
} catch (Exception $e) {
    $code = (int) $e->getCode();
    if ($code < 400 || $code > 599) {
        $code = 500;
    }

    jsonResponse([
        'success' => false,
        'error' => $e->getMessage()
    ], $code);
}
