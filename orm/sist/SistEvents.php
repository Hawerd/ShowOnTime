<?php
/*
    Name:   SistEvents.php
    Autor:  Luis F Castaño
    Date:   18-Jun-2016
    Desc:   se crea ORM para la tabla sistEvents.
  
    Autor:  Luis F Castaño
    Date:   25-Jun-2016
    Desc:   Se corrige nombre de la Tabla.
  
    Autor:  Luis F Castaño
    Date:   26-Jun-2016
    Desc:   Se agrega funcion init.
  
    Autor:  Luis F Castaño
    Date:   27-Jun-2016
    Desc:   Se agrega el valor null a la estructura principal de la orm. 
 
*/

require_once '/../MethodsORM.php';

class sistEvents extends methods{

    protected  $entityObj;
    protected  $nameTable;

    /* funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "sistEvents";    //Definir nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['eventUUID']           = null;
        $this->entityObj['eventName']           = null;
        $this->entityObj['eventCity']           = null;
        $this->entityObj['eventAddress']        = null;
        $this->entityObj['eventMountingDate']   = null;
        $this->entityObj['eventInitDate']       = null;
        $this->entityObj['eventFinishDate']     = null;
        $this->entityObj['FK_EventTypeCode']    = null;
        $this->entityObj['FK_employeUUID']      = null;
        $this->entityObj['FK_ClientUUID']       = null;
        $this->entityObj['CreatedDT']           = null;
        $this->entityObj['CreatedBy']           = null;
        $this->entityObj['UpdatedDT']           = null;
        $this->entityObj['UpdatedBy']           = null;
        $this->entityObj['Active']              = null;
        $this->entityObj['ActiveDT']            = null;
        $this->entityObj['ActiveBy']            = null;
        
        //Contructor para la conexion de los metodos
        parent::__construct();  

    }//fin del constructor 
    
    /* funcion que se encarga de inicializar datos en la estructura */
    public function init(){
        $this->entityObj['eventUUID']           = uniqid(mt_rand(),true);
    }//fin de la funcion init

}//fin de la clase sistEvents