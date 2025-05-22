<?php
session_start();
header('Content-Type: application/json');

// Validar autenticación
if (!isset($_SESSION['ID_USUARIO'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

require_once __DIR__ . '/../mdb/mdbParticipacionEvento.php';
require_once __DIR__ . '/../../model/dao/DataSource.php'; 

$id_usuario = $_SESSION['ID_USUARIO'];

// Determinar la acción a realizar
$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'obtener':
        // Obtener participaciones del usuario
        obtenerParticipaciones($id_usuario);
        break;
        
    case 'participar':
        // Agregar nueva participación
        agregarParticipacion($id_usuario);
        break;
        
    case 'eliminar':
        // Eliminar participación
        eliminarParticipacionUsuario($id_usuario);
        break;
        
    case 'verificar':
        // Verificar si ya existe participación
        verificarParticipacion($id_usuario);
        break;
        
    default:
        // Si no se especifica acción, mantener compatibilidad con método original
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check']) && $_GET['check'] == 1) {
            verificarParticipacion($id_usuario);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_evento'])) {
            agregarParticipacion($id_usuario);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            obtenerParticipaciones($id_usuario);
        } else {
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
        break;
}

/**
 * Obtener todas las participaciones de un usuario
 */
function obtenerParticipaciones($id_usuario) {
    try {
        $participaciones = obtenerParticipacionesPorUsuarioMDB($id_usuario);
        
        $result = [];
        foreach ($participaciones as $participacion) {
            $evento = $participacion->getEvento();
            $id_carrera = property_exists($participacion, 'id_carrera') ? $participacion->id_carrera : null;
            $result[] = [
                'id_carrera' => $id_carrera,
                'id_evento' => $evento->getIdEvento(),
                'nombre_evento' => $evento->getNombreEvento(),
                'fecha_evento' => $evento->getFechaEvento(),
                'hora_evento' => $evento->getHoraEvento(),
                'descripcion_evento' => $evento->getDescripcionEvento()
            ];
        }
        
        echo json_encode(['success' => true, 'participaciones' => $result]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error al obtener participaciones: ' . $e->getMessage()]);
    }
}

/**
 * Agregar nueva participación
 */
function agregarParticipacion($id_usuario) {
    $id_evento = $_POST['id_evento'] ?? null;
    
    if (!$id_evento) {
        echo json_encode(['success' => false, 'message' => 'ID de evento no recibido']);
        return;
    }
    
    try {
        // Verificar si ya existe la participación
        if (existeParticipacionMDB($id_usuario, $id_evento)) {
            echo json_encode(['success' => false, 'message' => 'Ya estás participando en este evento']);
            return;
        }
        
        $resultado = agregarParticipacionMDB($id_usuario, $id_evento);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Participación registrada']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la participación']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al procesar participación: ' . $e->getMessage()]);
    }
}

/**
 * Eliminar participación
 */
function eliminarParticipacionUsuario($id_usuario) {
    $id_evento = $_POST['id_evento'] ?? null;
    
    if (!$id_evento) {
        echo json_encode(['success' => false, 'error' => 'ID de evento no recibido']);
        return;
    }
    
    try {
        $resultado = eliminarParticipacion($id_usuario, $id_evento);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Participación eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo eliminar la participación']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar participación: ' . $e->getMessage()]);
    }
}

/**
 * Verificar si existe participación
 */
function verificarParticipacion($id_usuario) {
    $id_evento = $_GET['id_evento'] ?? null;
    
    if (!$id_evento) {
        echo json_encode(['exists' => false, 'error' => 'ID de evento no recibido']);
        return;
    }
    
    try {
        $exists = existeParticipacionMDB($id_usuario, $id_evento);
        echo json_encode(['exists' => $exists]);
    } catch (Exception $e) {
        echo json_encode(['exists' => false, 'error' => 'Error al verificar participación: ' . $e->getMessage()]);
    }
}
?>