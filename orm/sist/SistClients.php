<?php
/*
    Name:   SistClients.php
    Autor:  Luis F Castaño
    Date:   19-Jun-2016
    Desc:   se crea ORM para la tabla sistClients.
 
*/

require_once '/../MethodsORM.php';

class sistClients extends methods{

    protected  $entityObj;
    protected  $nameTable;

    /* funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "sistclients";    //Definir nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['clientUUID']         = "";
        $this->entityObj['clientFirstName']    = "";
        $this->entityObj['clientLastName']     = "";
        $this->entityObj['clientNumberID']     = "";
        $this->entityObj['clientNIT']          = "";
        $this->entityObj['clientAdress1']      = "";
        $this->entityObj['clientAdress2']      = "";
        $this->entityObj['clientPhoneR']       = "";
        $this->entityObj['clientPhoneM']       = "";
        $this->entityObj['clientEmail1']       = "";
        $this->entityObj['clientEmail2']       = "";
        $this->entityObj['FK_ClientTypeCode']  = "";
        $this->entityObj['CreatedDT']          = "";
        $this->entityObj['CreatedBy']          = "";
        $this->entityObj['UpdatedDT']          = "";
        $this->entityObj['UpdatedBy']          = "";
        $this->entityObj['Active']             = "";
        $this->entityObj['ActiveDT']           = "";
        $this->entityObj['ActiveBy']           = "";
        
        //Contructor para la conexion de los metodos
        parent::__construct();  

    }//fin del constructor 

}//fin de la clase sistEvents