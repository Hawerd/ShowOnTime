<?php

/*
    Name:   Clients-CM.php
    Autor:  Victor Gutiérrez
    Date:   28-Jun-2016
    Desc:   Controlador para el Modulo Clientes.
  
***************************************************************************************

    Autor:  Victor Gutiérrez
    Date:   04-Jun-2016
    Desc:   Avances en la implementación de la funcion loadDataGrid.

*/

/* PRE-PROCESSING FOR VALIDATION OF METHOD AND FORMAT.
   Se encarga de Validar los campos format y method provenientes 
   de la Vista, si estos valores no se encuentran o no estan 
   definidos el controlador finalizara el script y retornara su 
   respectivo mensaje de Error en xml.  */ 

try {
    
    // Variables Globales del Modulo
    $unexpError = "This is an Unexpected Error";
    $rootORM    = "/../../../orm/";
    $respXml    = "";
    $respMsg    = "";
    
    // Variables correspondientes a clases.
    $eventsClass;
    $clientsClass;
    $empleoyesClass;

    // Variables Globales para el Metodo
    $retXml     = "";
    $retMsg     = "";
    
    // Variables Globales del formulario
    $ids        = "";
    $tid        = "";
    $methodVal  = "";
    $methodFld  = "";
    $retAction 	= "";	
    $actualFlds = "";
    $operation  = "";
    
    // Evalua la existencia de la variable Format
    if (!isset($_REQUEST['format'])) {
       
       $respMsg = "Format not defined";
       throw new Exception($message = "Oops");
       
    } else {
       
        //Evalua el valor de la variable format.
        switch($_REQUEST['format']) {
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
        }// Fin del switch
        
    }//fin de la condicion  
    
    // Evalua la existencia de la variable Method
    if (isset($_REQUEST['method'])) {
        
        $methodVal = $_REQUEST['method']; 
        
    } else {
        
        if (!is_array($_POST)) {
           $respMsg = "No Form";
           throw new Exception($message = "Oops");
        }// Fin de la condicion

        if (!isset($_POST['ids'])) {
           $respMsg = "Ids is blank or not defined";
           throw new Exception($message = "Oops");
        }// Fin de la condicion

        $ids  = $_POST['ids'];

        if (!isset($_POST[$ids.'_method'])) {
           $respMsg = "Method not defined";
           throw new Exception($message = "Oops");
        }// Fin de la condicion
        
        $methodVal  = $_POST[$ids.'_method']; 
 
    }// Fin de la condicion
    
    //Evaluamos el Valor del Method Obtenido 
    switch($methodVal) {
        case "loadDataGrid":
            // Adjunta archivos ORM necesarios para el CM.
            require_once $rootORM."sist/SistClients.php";
            
            // Se instancian clases necesarias 
            $clientsClass   = new sistClients();   // Instancia la clase sistClients de la ORM.
            break;
        case "submitDataForm":
            // Obtengo el numero total de campos del formulario  
            $actualFlds  = count($_POST);                 
            
            // Adjunta archivos ORM necesarios para el CM.
            require_once $rootORM."sist/SistClients.php";  

            // Se instancian clases necesarias 
            $clientsClass    = new sistClients();   // Instancia la clase sistClients de la ORM.
            break;
        case "":
            $respMsg = "Method is blank";
            throw new Exception($message = "Oops");
            break;
        default:
            $respMsg = "Method is invalid";
            throw new Exception($message = "Oops");
            break;
    }// Fin del switch

   //Se Ejecuta Funcion proveniente del Method
   eval('$respXml='.$methodVal.'();');  
   print($respXml);
    
} catch(Exception $e) {
    
    if ($e->getMessage()!= "Oops") {
        $respMsg = $unexpError." : ".$e->getMessage();
    }
    
    // Se crea xml Error segun el formato de la peticion
    switch($_REQUEST['format']) {
        case "grid": 
            $respXml = gridError($respMsg);
            break;
        case "form":
        default:
            $respXml = formError($respMsg);
            break;
    }// Fin del switch
    
    // Finaliza el script y devuelve el Error
    exit($respXml);
    
}// Fin del catch Pre-Processing

/* END PRE-PROCESSING FOR VALIDATION OF METHOD AND FORMAT */

/* SECTION FOR FUNCTIONS OF METHOD. 
   funciones de Primer Orden, ejem: loadDataGrid, loadDataForm o
   SubmitModuleForm  */

    /* Funcion que se encarga de cargar los datos que poblaran
    el xml de la Grilla en el Modulo general. */
    function loadDataGrid() { 

        // Variables locales de la funcion
        global $retXml;
        global $clientsClass;
        
        //Variables locales de la funcion
        $gridErrMsg     = "";
        $employeeName   = "";
        $employeeUUID   = "";
        $mountingDate   = "";
        $initDate       = "";
        $finishDate     = "";

        try {

            /* Cargo los datos de la tabla de base de datos */
            $clientsObj   = $clientsClass->entityLoad("Active = true");
            if($clientsObj['error']) {
                throw new Exception($message=$clientsObj['msg']);
            }
            
            //Obtengo los datos del objeto cargado $clientsObj
            $clientsDataObj  = $clientsObj['data'];

            header('Content-Type: application/xml');
            $retXml = "<rows>";
            
            for($clientIndex=0; $clientIndex<count($clientsDataObj); $clientIndex++){

                $recActual = $clientsDataObj[$clientIndex];
                
                // Si existe empleado obtener nombre completo.
                if($recActual['FK_employeUUID'] != null){
                    $employeeUUID = $recActual['FK_employeUUID'];
                    $employeeName = $recActual['empleoyeFirstName']." ".$recActual['empleoyeLastName'];
                }else{
                    $employeeUUID = "?";   
                    $employeeName = "";   
                }
                //si existe fecha de montaje, obtenerla.
                if($recActual['eventMountingDate']!= null){
                    $mountingDate = $recActual['eventMountingDate'];
                }else{
                    $mountingDate = "";
                }
                //si existe fecha de inicio, obtenerla.
                if($recActual['eventInitDate'] != null){
                    $initDate = $recActual['eventInitDate'];
                }else{
                    $initDate = "";
                }
                //si existe fecha final, obtenerla.
                if($recActual['eventFinishDate'] != null){
                    $finishDate = $recActual['eventFinishDate'];
                }else{
                    $finishDate = "";
                }
                
                //xml
                $retXml .= "<row id='$i'>";
                    $retXml .= '<cell>'.$recActual['eventTypeDesc'].'</cell>';
                    $retXml .= '<cell>'.$recActual['clientFirstName']." ".$recActual['clientLastName'].'</cell>';
                    $retXml .= '<cell>'.$employeeName.'</cell>';
                    $retXml .= '<cell>'.$recActual['eventName'].'</cell>';
                    $retXml .= '<cell>'.$recActual['eventCity'].'</cell>';
                    $retXml .= '<cell>'.$recActual['eventAddress'].'</cell>';
                    $retXml .= '<cell>'.$mountingDate.'</cell>';
                    $retXml .= '<cell>'.$initDate.'</cell>';
                    $retXml .= '<cell>'.$finishDate.'</cell>';
                    $retXml .= '<cell>'.'</cell>';
                    $retXml .= '<cell>'.$recActual['eventUUID'].'</cell>';
                    $retXml .= '<cell>'.$recActual['FK_EventTypeCode'].'</cell>';
                    $retXml .= '<cell>'.$recActual['FK_ClientUUID'].'</cell>';
                    $retXml .= '<cell>'.$employeeUUID.'</cell>';
                    $retXml .= "<cell>submitDataForm</cell>";
                    $retXml .= "<cell>update</cell>";
                $retXml .= "</row>";
                
            }//fin del ciclo
            
            $retXml .= "</rows>";
       
        }catch(Exception $e){
            
            global $unexpError;
            $gridErrMsg = $unexpError." : ".$e->getMessage();
            $retXml = gridError($gridErrMsg);
            
        }//fin del catch
       
        return $retXml;
    }//fin de la funcion loadDataGrid