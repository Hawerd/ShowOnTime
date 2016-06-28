<?php
/*
    Name:   SisElement.php
    Autor:  Hawerd GOnzalez
    Date:   19-Jun-2016
    Desc:   se crea ORM para la tabla sistelements.
  
    Autor: Luis F Castaño
    Date:  21-Jun-2016 
    Desc:  Se corrige nombre de la clase de la ORM. 
  
    Autor:  Luis F Castaño
    Date:   25-Jun-2016
    Desc:   Se corrige nombre de la Tabla.
  
    Autor:  Luis F Castaño
    Date:   26-Jun-2016
    Desc:   Se agrega funcion init. 
  
    Autor:  Luis F Castaño
    Date:   27-Jun-2016
    Desc:   Se agrega el valor null a la estructura principal de la orm. 
  
    Autor:  Hawerd Gonzalez
    Date:   27-Jun-2016
    Desc:   Agrego campo faltantes a la orm
  
    Autor:  Luis F Castaño
    Date:   28-Jun-2016
    Desc:   Se ajusta Orden de campos de la Orm.
 
*/

require_once '/../MethodsORM.php';

class sistElements extends methods{

    protected  $entityObj;
    protected  $nameTable;

    /* funcion constructora para definir los campos de la tabla de Base de Datos */
    public function __construct(){

        $this->entityObj = Array();         //Array por Defecto
        $this->nameTable = "sistElements";  //nombre de la tabla
        
        //Definir aqui los campos de la Tabla Requerida.
        $this->entityObj['elementUUID']         = null;
        $this->entityObj['elementCode']         = null;
        $this->entityObj['elementName']         = null;
        $this->entityObj['elementBrand']        = null;
        $this->entityObj['elementReferences']   = null;
        $this->entityObj['elementDesc']         = null;
        $this->entityObj['elementScheduled']    = null;
        $this->entityObj['FK_elementTypeCode']  = null;
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
        $this->entityObj['elementUUID']         = uniqid(mt_rand(),true);
    }//fin de la funcion init

}//fin de la clase sistElements