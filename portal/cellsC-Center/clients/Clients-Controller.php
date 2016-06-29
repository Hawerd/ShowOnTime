<?php

/*
    Name:   Clients-Controller.php
    Autor:  Victor Gutiérrez
    Date:   28-Jun-2016
    Desc:   Controlador para el Modulo Clientes.
  
***************************************************************************************
  
    Autor:  
    Date:   
    Desc:   

*/

/* PRE-PROCESSING FOR VALIDATION OF METHOD AND FORMAT.
   Se encarga de Validar los campos format y method provenientes 
   de la Vista Si esto valores no se encuentran o no estan 
   definidos el controlador finalizara el script y retornara su 
   respectivo mensaje de Error en xml.  */ 

try {
    
    //Variables Globales del Modulo
    $unexpError = "This is an Unexpected Error";
    $rootORM    = "/../../../orm/";
    $respXml    = "";
    $respMsg    = "";
    
    //Variables que contiene clases.
    $eventsClass;
    $clientsClass;
    $empleoyesClass;

    //Variables Globales para el Metodo
    $retXml     = "";
    $retMsg     = "";
    
    //Variables Globales del formulario
    $ids        = "";
    $tid        = "";
    $methodVal  = "";
    $methodFld  = "";
    $retAction 	= "";	
    $actualFlds = "";
    $operation  = "";
    
    //Evalua Existencia de la variable Format
    if(!isset($_REQUEST['format'])){
       
       $respMsg = "Format not defined";
       throw new Exception($message = "Oops");
       
    }else{
       
        //Evalua el valor de la variable format.
        switch($_REQUEST['format']){
            case "grid": 
            case "form":
                break;
            case "":
                $respMsg = "Format is blank";
                throw new Exception($message = "Oops");
                break;
            default:
                $respMsg = "Format is invalid";
                throw new Exception($message = "Oops");
                break;
        }//fin del switch
        
    }//fin de la condicion  
    
    //Evalua Existencia de la variable Method
    if(isset($_REQUEST['method'])){
        
        $methodVal = $_REQUEST['method']; 
        
    }else{
        
        if(!is_array($_POST)){
           $respMsg = "No Form";
           throw new Exception($message = "Oops");
        }//fin de la condicion

        if(!isset($_POST['ids'])){
           $respMsg = "Ids is blank or not defined";
           throw new Exception($message = "Oops");
        }//fin de la condicion

        $ids  = $_POST['ids'];

        if(!isset($_POST[$ids.'_method'])){
           $respMsg = "Method not defined";
           throw new Exception($message = "Oops");
        }//fin de la condicion
        
        $methodVal  = $_POST[$ids.'_method']; 
 
    }//fin de la condicion
    
    //Evaluamos el Valor del Method Obtenido 
    switch($methodVal){
        case "loadDataGrid":
            //Adjunta archivos ORM necesarios para el CM.
            require_once $rootORM."view/viewEvents.php";
            
            //Se instancias clases necesarias 
            $vEventsClass   = new viewEvents();   //Instancia la clase viewEvents de la ORM.
            break;
        case "submitDataForm":
            //Obtengo el numero total de campos del formulario  
            $actualFlds  = count($_POST);                 
            
            //Adjunta archivos ORM necesarios para el CM.
            require_once $rootORM."sist/SistEvents.php";  

            //Se instancias clases necesarias 
            $eventsClass    = new sistEvents();   //Instancia la clase sitsEvents de la ORM.
            break;
        case "":
            $respMsg = "Method is blank";
            throw new Exception($message = "Oops");
            break;
        default:
            $respMsg = "Method is invalid";
            throw new Exception($message = "Oops");
            break;
    }//fin del switch

   //Se Ejecuta Funcion proveniente del Method
   eval('$respXml='.$methodVal.'();');  
   print($respXml);
    
}catch(Exception $e){
    
    if($e->getMessage()!= "Oops"){
        $respMsg = $unexpError." : ".$e->getMessage();
    }
    
    //Se crea xml Error segun el formato de la peticion
    switch($_REQUEST['format']){
        case "grid": 
            $respXml = gridError($respMsg);
            break;
        case "form":
        default:
            $respXml = formError($respMsg);
            break;
    }//fin del switch
    
    //Finaliza el script y devuelve el Error
    exit($respXml);
    
}//fin del catch Pre-Processing

/* END PRE-PROCESSING FOR VALIDATION OF METHOD AND FORMAT */