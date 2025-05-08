<?php
    session_start();
    require_once (__DIR__.'/../mdb/mdbUsuario.php');
    
    $corredor = buscarCorredorPorId($_SESSION['ID_USUARIO']);
   
    $respuesta = [
        'id' => $corredor->getIdCorredor(),
        'nombre' => $corredor->getNombreUsuario(),
        'apellido' => $corredor->getApellidoUsuario(),
        'password' => $corredor->getContrasena()    ];

    echo json_encode($respuesta);  


    