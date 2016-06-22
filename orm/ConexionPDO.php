<?php
/*  
    Name:   Conexion.php
    Autor:  Luis F Castaño
    Date:   18-Jun-2016
    Desc:   Clase Principal para la conexion de la base de datos
            atravez de PDO.
  
    Autor:  Luis F Castaño
    Date:   18-Jun-2016
    Desc:   Se agrega control de Error al Objeto conexion: setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    
    Autor:  Luis F Castaño
    Date:   22-Jun-2016
    Desc:   Se crea coenxion externa a base de datos de prueba y desarrollo.
  
 */

/* Define la clase conexion */
class conexion{
    
    private $server;
    private $dbname;
    private $username;
    private $pass;
    private $retStruct;
    
    /* funcion constructora de variables generales */
    public function __construct(){
        
       $this->server    = "sql3.freesqldatabase.com";
       $this->dbname    = "sql3125008";
       $this->username  = "sql3125008";
       $this->pass      = "uHmTAQyb1U";
       $this->retStruct = Array();

    }//fin del contructor
    
    /* funcion que se encarga de conectarse a la base de datos especificada a traves de PDO */
    public function connect(){

       try{
           
           //Se realiza conexion a la base de datos a traves de PDO
           $conn = new PDO('mysql:host='.$this->server.';dbname='.$this->dbname,$this->username,$this->pass);
           $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
           //Se retorna Array con los valores Obtenido si no hay Error
           $this->retStruct['msg']    = "success";
           $this->retStruct['conn']   = $conn;
           $this->retStruct["error"]  = false;
           
       }catch(PDOException $e){
           
           //Se retorna Array con los valores obtenidos si hubo Error en la conexion
           $this->retStruct["msg"]    = "Connection Error DataBase: ".$e->getMessage();
           $this->retStruct["error"]  = true;
           
       }//fin del catch
      
       return $this->retStruct;
    }//fin de la funcion connect
    
}//fin de la clase conexion

