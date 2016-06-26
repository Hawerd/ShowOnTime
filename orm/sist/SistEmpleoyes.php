<?php
/*
    Name:   SistEmpleoyes.php
    Autor:  Luis F Castaño
    Date:   19-Jun-2016
    Desc:   se crea ORM para la tabla sistEmpleoyes.
  
    Autor:  Luis F Castaño
    Date:   21-Jun-2016
    Desc:   Se corrgie comentario de la ORM.
 
    Autor:  Luis F Castaño
    Date:   25-Jun-2016
    Desc:   Se corrige nombre de la Tabla.
  
    Autor:  Luis F Castaño
    Date:   26-Jun-2016
    Desc:   Se agrega funcion init.
 
*/

require_once '/../MethodsORM.php';

class sistEmpleoyes extends methods{

    protected  $entityObj;
    protected  $nameTable;

    /* funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "sistEmpleoyes"; //Definir nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['empleoyeUUID']         = "";
        $this->entityObj['empleoyeFirstName']    = "";
        $this->entityObj['empleoyeLastName']     = "";
        $this->entityObj['empleoyeNumberID']     = "";
        $this->entityObj['empleoyeNIT']          = "";
        $this->entityObj['empleoyeAdress1']      = "";
        $this->entityObj['empleoyeAdress2']      = "";
        $this->entityObj['empleoyePhoneR']       = "";
        $this->entityObj['empleoyePhoneM']       = "";
        $this->entityObj['empleoyeEmail1']       = "";
        $this->entityObj['empleoyeEmail2']       = "";
        $this->entityObj['FK_empleoyeTypeCode']  = "";
        $this->entityObj['CreatedDT']            = "";
        $this->entityObj['CreatedBy']            = "";
        $this->entityObj['UpdatedDT']            = "";
        $this->entityObj['UpdatedBy']            = "";
        $this->entityObj['Active']               = "";
        $this->entityObj['ActiveDT']             = "";
        $this->entityObj['ActiveBy']             = "";
        
        //Contructor para la conexion de los metodos
        parent::__construct();  

    }//fin del constructor 
    
    /* funcion que se encarga de inicializar datos en la estructura */
    public function init(){
        $this->entityObj['empleoyeUUID']        = uniqid(mt_rand(),true);
    }//fin de la funcion init

}//fin de la clase sistEmpleoyes