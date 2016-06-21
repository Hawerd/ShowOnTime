<?php
/*
    Name:   SisElement.php
    Autor:  Hawerd GOnzalez
    Date:   19-Jun-2016
    Desc:   se crea ORM para la tabla sistelements.
 
*/

require_once '/../MethodsORM.php';

class sistEmpleoyes extends methods{

    protected  $entityObj;
    protected  $nameTable;

    /* funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "sistempleoyes"; //nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['elementUUID']         = uniqid(mt_rand(),true);
        $this->entityObj['elementCode']         = "";
        $this->entityObj['elementName']         = "";
        $this->entityObj['elementBrand']        = "";
        $this->entityObj['elementReferences']   = "";
        $this->entityObj['elementQuantity']     = "";
        $this->entityObj['FK_elementTypeCode']  = "";
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

}//fin de la clase sistEvents