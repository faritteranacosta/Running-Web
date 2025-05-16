<?php
require_once __DIR__ . '/../mdb/mdbCarrera.php';

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : null);

switch ($action) {
    case 'insertar':
        $distancia = $_POST['distancia'];
        $evento = $_POST['evento'];
        $tipoCarrera = $_POST['tipoCarrera'];
        $categoria = $_POST['categoria'];
        $ruta = $_POST['ruta'];
        $result = insertarCarreraMDB($distancia, $evento, $tipoCarrera, $categoria, $ruta);
        echo json_encode($result);
        break;
    case 'actualizar':
        $idCarrera = $_POST['idCarrera'];
        $distancia = $_POST['distancia'];
        $evento = $_POST['evento'];
        $tipoCarrera = $_POST['tipoCarrera'];
        $categoria = $_POST['categoria'];
        $ruta = $_POST['ruta'];
        $result = actualizarCarreraMDB($idCarrera, $distancia, $evento, $tipoCarrera, $categoria, $ruta);
        echo json_encode($result);
        break;
    case 'eliminar':
        $idCarrera = $_POST['idCarrera'];
        $result = eliminarCarreraMDB($idCarrera);
        echo json_encode($result);
        break;
    case 'obtener':
        $idCarrera = $_GET['idCarrera'];
        $result = obtenerCarreraPorIdMDB($idCarrera);
        echo json_encode($result);
        break;
    case 'listar':
        $result = obtenerTodasLasCarrerasMDB();
        echo json_encode($result);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}
