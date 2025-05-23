<?php 
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON


    try {
    require_once (__DIR__."/../mdb/mdbUsuario.php");
    $ruta = "#";
    $message = "User no registered";
    $user = null;
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS);
    $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_SPECIAL_CHARS);
    $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_SPECIAL_CHARS);
    $fecha_registro = date("Y-m-d H:i:s");
    error_log($fecha_registro);
    $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_SPECIAL_CHARS);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_SPECIAL_CHARS);
    $nombre_tienda = null;
    if($rol == "vendedor"){
        $nombre_tienda = filter_input(INPUT_POST, 'nombre_tienda', FILTER_SANITIZE_SPECIAL_CHARS);
    }


    if (!$nombre || !$apellido || !$correo || !$contrasena || !$sexo || !$fecha_nacimiento || !$fecha_registro || !$rol) {
        echo json_encode([
            "message" => "All fields are required.",
            "ruta" => null,
            "user" => null
        ]);
        exit;
    }

   
    error_log("Datos recibidos: Name=$nombre, Surname=$apellido, Email=$correo, Password=$contrasena");

    $user = agregarUsuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro, $ciudad = null, $equipo = null, $nombre_tienda);
    if ($user != null) {
        $ruta = "./iniciar_sesion.php";
        $message = "User registered successfully";
    } else {
        $ruta = "./registrarse.php";
    }

    $result = [
        "message" => $message,
        "ruta" => $ruta,
        "user" => $user
    ];

    echo json_encode($result); // Convertir el array a JSON
} catch (Exception $e) {
    echo json_encode([
        "message" => "An error occurred: " . $e->getMessage(),
        "ruta" => null,
        "user" => null
    ]);
}
?>