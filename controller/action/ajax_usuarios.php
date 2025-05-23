<?php 
require_once __DIR__ . '/../mdb/mdbUsuario.php';
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idUsuario'])) {
    // Endpoint para obtener detalles de un usuario específico
    $result = obtenerUsuarioPorId($_GET['idUsuario']);
    if(ob_get_length()) ob_clean();
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

try {
    if($method === "GET"){
        // --- PAGINACIÓN ---
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $porPagina = isset($_GET['porPagina']) ? max(1, intval($_GET['porPagina'])) : 20;
        $offset = ($pagina - 1) * $porPagina;

        // Debes implementar estas funciones en tu mdbUsuario.php:
        // obtenerUsuariosPaginados($offset, $porPagina) y contarUsuarios()
        $usuarios = obtenerUsuariosPaginados($offset, $porPagina);
        $total = contarUsuarios();

        if ($usuarios === null || $usuarios === false) {
            throw new Exception("Error al obtener usuarios de la base de datos");
        }

        $respuesta = array_map(function($usuario) {
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

        echo json_encode([
            'usuarios' => $respuesta,
            'total' => $total
        ]);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    error_log("Payload recibido: " . json_encode($data));
    switch ($method) {
        case 'POST':
            // Validar datos requeridos
            $required = ['nombre', 'apellido', 'email', 'sexo', 'fechaNacimiento', 'rol'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido.");
                }
            }

    
            if ($result === false) {
                throw new Exception("Error al agregar el usuario");
            }

            echo json_encode(['success' => true, 'msg' => 'Usuario agregado correctamente']);
            break;

        case 'DELETE':
            // Eliminar usuario
            error_log("Entrando en el case DELETE...");

            if (empty($data['idUsuario'])) {
                throw new Exception("El campo idUsuario es requerido.");
            }

            $result = eliminarUsuario($data['idUsuario']);

            if ($result === false) {
                throw new Exception("Error al eliminar el gato");
            }

            echo json_encode(['success' => true, 'msg' => 'Usuario eliminado correctamente']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}
?>