<?php

class conexion {
    
    private $servidor;
    private $baseDatos;
    private $user;
    private $pass;
    private $conexion;

    function conexion() {
        $this->servidor = "localhost";
        $this->baseDatos = "sistShowOnTime_Dev";
        $this->user = "root";
        $this->pass = "sol12345";
        $this->conectar();
    }
    

        //para conectarnos a traves de PDO a la base de datos
        private function conectar(){
            $this->conexion = new PDO('mysql:host='.$this->servidor.';dbname='.$this->baseDatos, $this->user,$this->pass);   				
        }	
       
       //este metodo se encarga de recibir una consulta sql y ejecutarla
       public function consultar($sql){
            $datos = $this->conexion->query($sql);				  
            return $datos;
       }

       //ESTE METODO SE ECARGA DE REVCIBIR UNA CONSULTA EN SQL Y INSERTAR UN NUEVO REGISTRO EN LA BD	
       public function insertar($sql){
            if($datos = $this->conexion->query($sql)){
                return true;
            }else{
            return false;
            }  				  
        }	
	  
  
}

?>