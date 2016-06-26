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
        $this->entityObj['eventUUID']           = "";
        $this->entityObj['eventName']           = "";
        $this->entityObj['eventCity']           = "";
        $this->entityObj['eventAddress']        = "";
        $this->entityObj['eventMountingDate']   = "";
        $this->entityObj['eventInitDate']       = "";
        $this->entityObj['eventFinishDate']     = "";
        $this->entityObj['FK_EventTypeCode']    = "";
        $this->entityObj['FK_employeUUID']      = "";
        $this->entityObj['FK_ClientUUID']       = "";
        $this->entityObj['CreatedDT']           = "";
        $this->entityObj['CreatedBy']           = "";
        $this->entityObj['UpdatedDT']           = "";
        $this->entityObj['UpdatedBy']           = "";
        $this->entityObj['Active']              = "";
        $this->entityObj['ActiveDT']            = "";
        $this->entityObj['ActiveBy']            = "";
        
        //Contructor para la conexion de los metodos
        parent::__construct();  

    }//fin del constructor 
    
    /* funcion que se encarga de inicializar datos en la estructura */
    public function init(){
        $this->entityObj['eventUUID']           = uniqid(mt_rand(),true);
    }//fin de la funcion init

}//fin de la clase sistEvents