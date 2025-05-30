<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ManejadorDB
 *
 * @author Admin
 */
class DataSource {
    //put your code here
    
    private $cadenaConexion;
    private $conexion;
    
    
    public function __construct(){
        try{
            $config=fopen(__DIR__."/../../config/config.json","r");
            if(!$config){
                die("Cannot open configuration file!");
            }
            
            $content="";
            
            while(!feof($config)){
                $content.= fgets($config);
            }
            
            $json =json_decode($content, true);
            
            $this->cadenaConexion="mysql:host=".$json['host'].";dbname=".$json['database'].';charset=utf8';
            $this->conexion = new PDO($this->cadenaConexion,$json['username'],$json['password']);
        } catch (PDOException $ex) {
             echo $ex->getMessage();
        }
    }
    
    public function ejecutarConsulta($sql="", $values=array()){
        if($sql != ""){
            $consulta=$this->conexion->prepare($sql);
            $consulta->execute($values);
            $tabla_datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            return $tabla_datos;
        }else{
            return $tabla_datos = array();
        }
    }
    
    
    public function ejecutarActualizacion($sql="", $values=array()){
        if($sql != ""){
            $consulta=$this->conexion->prepare($sql);
            $consulta->execute($values);
            $numero_tablas_afectadas = $consulta->rowCount();
            return $numero_tablas_afectadas;
        }else{
            return 0;
        }
    }
    

    public function getLastInsertId(){
        return $this->conexion->lastInsertId();
    } 
    
    public function getConnection() {
        return $this->conexion;
    }

    public function cerrarConexion(){
        $this->conexion = null;
    }

}

?>
