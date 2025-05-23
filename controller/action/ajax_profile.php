<?php
    session_start();
    require_once(__DIR__ . "/../../model/dao/Usuario.php");
    require_once (__DIR__.'/../mdb/mdbUsuario.php');
    
    $corredor = obtenerUsuarioPorId($_SESSION['ID_USUARIO']);
           
    echo json_encode(["id_usuario" => $_SESSION['ID_USUARIO']]);  



    