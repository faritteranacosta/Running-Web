<?php
    session_start();
    require_once(__DIR__ . "/../../model/dao/Usuario.php");
    require_once (__DIR__.'/../mdb/mdbUsuario.php');
    
    $corredor = obtenerUsuarioPorId($_SESSION['ID_USUARIO']);
   
    $respuesta = [
        'id' => $usuario->getIdUsuario(),
        'nombre' => $usuario->getNombre(),
        'apellido' => $usuario->getApellido(),
        'correo' => $usuario-> getCorreo()  ];

    echo json_encode($respuesta);  



    