<?php

/* 
    Name:   viewClients.php
    Autor:  Victor Gutiérrez
    Date:   09-Jul-2016
    Desc:   ORM correspondiente a la vista viewClients.

***************************************************************************************

    Autor:  
    Date:   
    Desc:   

*/

require_once '/../MethodsORM.php';

class viewClients extends methods{
    
    protected  $entityObj;
    protected  $nameTable;
    
    /* Funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "viewEvents";    //Definir nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['clientUUID']          = null;
        $this->entityObj['clientFirstName']     = null;
        $this->entityObj['clientLastName']      = null;
        $this->entityObj['clientNumberID']      = null;
        $this->entityObj['clientNIT']           = null;
        $this->entityObj['clientAdress1']       = null;
        $this->entityObj['clientPhoneR']        = null;
        $this->entityObj['clientPhoneM']        = null;
        $this->entityObj['FK_ClientTypeCode']   = null;
        $this->entityObj['FK_DocumentTypeCode'] = null;
        $this->entityObj['CreatedDT']           = null;
        $this->entityObj['CreatedBy']           = null;
        $this->entityObj['UpdatedDT']           = null;
        $this->entityObj['UpdatedBy']           = null;
        $this->entityObj['Active']              = null;
        $this->entityObj['ActiveDT']            = null;
        $this->entityObj['ActiveBy']            = null;
        
        //Contructor para la conexion de los metodos
        parent::__construct();  

    }// Fin del constructor 
    
}// Fin de la clase viewClients
