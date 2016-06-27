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
  
    Autor:  Luis F Castaño
    Date:   27-Jun-2016
    Desc:   Se agrega el valor null a la estructura principal de la orm. 
 
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
        $this->entityObj['empleoyeUUID']         = null;
        $this->entityObj['empleoyeFirstName']    = null;
        $this->entityObj['empleoyeLastName']     = null;
        $this->entityObj['empleoyeNumberID']     = null;
        $this->entityObj['empleoyeNIT']          = null;
        $this->entityObj['empleoyeAdress1']      = null;
        $this->entityObj['empleoyeAdress2']      = null;
        $this->entityObj['empleoyePhoneR']       = null;
        $this->entityObj['empleoyePhoneM']       = null;
        $this->entityObj['empleoyeEmail1']       = null;
        $this->entityObj['empleoyeEmail2']       = null;
        $this->entityObj['FK_empleoyeTypeCode']  = null;
        $this->entityObj['CreatedDT']            = null;
        $this->entityObj['CreatedBy']            = null;
        $this->entityObj['UpdatedDT']            = null;
        $this->entityObj['UpdatedBy']            = null;
        $this->entityObj['Active']               = null;
        $this->entityObj['ActiveDT']             = null;
        $this->entityObj['ActiveBy']             = null;
        
        //Contructor para la conexion de los metodos
        parent::__construct();  

    }//fin del constructor 
    
    /* funcion que se encarga de inicializar datos en la estructura */
    public function init(){
        $this->entityObj['empleoyeUUID']        = uniqid(mt_rand(),true);
    }//fin de la funcion init

}//fin de la clase sistEmpleoyes