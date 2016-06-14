<?php

class conexion {
    private $servidor;
    private $baseDatos;
    private $user;
    private $pass;
    private $conexion;

    function conexion() {
        $this->servidor = "localhost";
//        $this->baseDatos = "u2818434_Tmichuleta";
//        $this->user = "u2818434";
//        $this->pass = "jmoo?K1cH6";
        $this->baseDatos = "sistShowOnTime_Dev";
        $this->user = "root";
        $this->pass = "";
        $this->conectar();
    }
    
    private function conectar() {
        try{
            $this->conexion = new PDO('mysql:host=' . $this->servidor . ';dbname=' . $this->baseDatos, $this->user, $this->pass); 
          
        } catch (PDOException $e) {
            echo 'Fallo de conexion'.$ex->getMessage();
        }
        
    }

    public function consultar($sql) {
        try{
            $datos = $this->conexion->query($sql);
        } catch (Exception $ex) {
            echo 'Fallo el insert'.$ex;
        }
        
        return $datos;
    }

    public function insertar($sql) {
        try{
            if ($datos = $this->conexion->query($sql)) {
                return true;
            } 
        } catch (Exception $ex) {
            return false; 
        }
       
    }

}

?>