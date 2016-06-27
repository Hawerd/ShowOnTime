<?php
/*
    Name:   viewEvents.php
    Autor:  Luis F Castaño
    Date:   27-Jun-2016
    Desc:   se crea ORM para la vista viewEvents, no Requieren funcion Init().
  
    Autor:  
    Date:   
    Desc:   

*/

require_once '/../MethodsORM.php';

class viewEvents extends methods{

    protected  $entityObj;
    protected  $nameTable;

    /* funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "viewEvents";    //Definir nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['eventUUID']           = null;
        $this->entityObj['eventName']           = null;
        $this->entityObj['eventCity']           = null;
        $this->entityObj['eventAddress']        = null;
        $this->entityObj['clientFirstName']     = null;
        $this->entityObj['clientLastName']      = null;
        $this->entityObj['empleoyeFirstName']   = null;
        $this->entityObj['empleoyeLastName']    = null;
        $this->entityObj['eventMountingDate']   = null;
        $this->entityObj['eventInitDate']       = null;
        $this->entityObj['eventFinishDate']     = null;
        $this->entityObj['eventTypeDesc']       = null;
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

}//fin de la clase viewEvents

