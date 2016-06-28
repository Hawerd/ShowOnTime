<?php
/*
    Name:   Events-CM.php
    Autor:  Luis F Castaño
    Date:   17-Jun-2016
    Desc:   Controlador para el Modulo Eventos.
  
    Autor:  Luis F Castaño
    Date:   19-Jun-2016
    Desc:   Se realiza la carga de datos en la funcion loadDataGrid utilizando
            las ORM
  
    Autor:  Luis F Castaño
    Date:   21-Jun-2016
    Desc:   Se actualiza logica de prueba en las funciones internas addForm, updateForm
            removeForm, y en la funcion loadDataGrid se agregan nuevas columnas hidden a
            la estructura xml que se retorna.
  
    Autor:  Luis F Castaño
    Date:   25-Jun-2016
    Desc:   Se ajusta la funcion interna addform para agregar registros.
    
    Autor:  Luis F Castaño
    Date:   27-Jun-2016
    Desc:   Se corrige logica de la funcion loadDataGrid para cargar los datos 
            en la estructura xml, se retiran validaciones y se agregan otras.     
   
*/

/* PRE-PROCESSING FOR VALIDATION OF METHOD AND FORMAT.
   Se encarga de Validar los campos format y method provenientes 
   de la Vista Si esto valores no se encuentran o no estan 
   definidos el controlador finalizara el script y retornara su 
   respectivo mensaje de Error en xml.  */ 

try{
    
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

/* SECTION FOR FUNCTIONS OF METHOD. 
   funciones de Primer Orden, ejem: loadDataGrid, loadDataForm o
   SubmitModuleForm  */

    /* Funcion que se encarga de cargar los datos que poblaran
    el xml de la Grilla en el Modulo general. */
    function loadDataGrid(){ 

        //Variables locales de la funcion
        global $retXml;
        global $vEventsClass;
        global $vEventsDataObj;
        
        //Variables locales de la funcion
        $gridErrMsg     = "";
        $employeeName   = "";
        $employeeUUID   = "";
        $mountingDate   = "";
        $initDate       = "";
        $finishDate     = "";

        try{

            /* Cargo los datos de la tabla de base de datos */
            $vEventsObj   = $vEventsClass->entityLoad("Active = true");
            if($vEventsObj['error']){
                throw new Exception($message=$vEventsObj['msg']);
            }
            
            //Obtengo los datos del objeto cargado $eventsObj
            $eventDataObj  = $vEventsObj['data'];

            header('Content-Type: application/xml');
            $retXml = "<rows>";
            
            for($i=0; $i<count($eventDataObj); $i++){

                $recActual = $eventDataObj[$i];
                
                //si esiste empleado obtener nombre completo.
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
    
    /* funcion que se encarga de dirigir las peticiones 
    provenientes del formulario, dirige las validaciones y posteriormente
    la operacion a realizar con los datos. */
    function submitDataForm(){
        
        //Variables Globales del modulo
        global $retXml;
        global $retMsg;
        global $retAction;
        global $tid;
        global $actualFlds;
        global $operation;
        global $ids;
        global $tid;
        
        //Variables locales de la funcion
        $submitStage    = "";
        $expectedFlds   = "";
        $doValidation   = "";
        $operationResp  = "";
        
        try{
            
            $submitStage = "initialization";
            
            //Numero de Campos esperados por parte del Fomrulario
            $expectedFlds = 19;
            
            //Chequea el numero de campos del formulario
            if($expectedFlds != $actualFlds){
                $retMsg = "Incorrect number of fields: ".$actualFlds." vs ".$expectedFlds;
                throw new Exception($message = "Oops");
            }
            
            //se obtiene la operation
            $operation = $_POST[$ids.'_op'];
            
           //operaciones que requieren de validaciones.(remove, no requiere).
            switch($operation){
                case "update":
                case "add":
                    //Se valida los campos del formulario
                    $submitStage = $operation."Validation";

                    $doValidation = validateForm();
                    if($doValidation['error']){
                        throw new Exception($message = $doValidation['msg']);//retorna un error inesperado de la funcion de validacion
                    }
                    if(!$doValidation['valid']){
                        $retMsg = $doValidation['msg'];
                        throw new Exception($message = "Oops"); //retorna los mensajes de los campos no validados
                    }
                    break;
            }//fin del switch
            
            //Se invoca la funcion de segunda orden
            $submitStage = $operation;
            eval('$operationResp ='.$operation.'Form();'); 
            if($operationResp['error']){
                throw new Exception($message = $operationResp['msg']);//retorna un error inesperado de las funciones internas add,update,remove
            }
            
            $retMsg     = $operationResp['msg']; //retorna el mensaje de exito
            $retAction  = "success";             //retorna la accion exitosamente
            
        }catch(Exception $e){
            
            if($e->getMessage()!= "Oops"){
                global $unexpError;
                $retMsg = $unexpError." : ".$submitStage.": ".$e->getMessage();
            }
            
            $retAction = "fail";
        }//fin del catch
        
        header('Content-Type: application/xml');
        $retXml = "<data>";
            $retXml .= "<action type='$retAction' tid='$tid'><![CDATA[$retMsg]]></action>";
        $retXml .= "</data>";
        
        return $retXml;
    }//fin de la funcion submitGeneralForm
  
/* END SECTION FOR FUNCTIONS OF METHOD */
    
/* FUNCTIONS INTERNAL FOR THE OPERATIONS. 
   funciones de segundo orden, ejem: addForm, updateForm, RemoveForm
   y operaciones encargadas de persistir en la base de datos o que sirven 
   de apoyo para el proceso requerido  */ 
   
   /* funcion que se encarga de insertar nuevos datos */
   function addForm(){
       
       $retStruct   = Array();
       $funcStage   = "";
       global $eventsClass;
       global $eventsDataObj;
       global $ids;
       global $tid;
       
       try{
           
           $funcStage   = "LoadEntityNew";
           
           //cargamos nueva entidad de la tabla eventos     
           $eventsObj   = $eventsClass->entityNew();
           if($eventsObj['error']){
               throw new Exception($message = $eventsObj['msg']);
           }
           
           //Obtengo mi entidad vacia de la tabla para poblarla
           $eventsDataObj = $eventsObj['data'];

           $funcStage   = "SetupEventsDataObj";
           
           //Campos Requeridos en la Base de Datos.
           $eventsDataObj['eventName']          = $_POST[$ids.'_NameOfEvent'];
           $eventsDataObj['eventCity']          = $_POST[$ids.'_CityOfEvent'];
           $eventsDataObj['eventAddress']       = $_POST[$ids.'_AddrOfEvent'];
           $eventsDataObj['FK_EventTypeCode']   = $_POST[$ids.'_FK_EventTypeCode'];
           $eventsDataObj['FK_ClientUUID']      = $_POST[$ids.'_FK_ClientUUID'];
           $eventsDataObj['Active']             = true; 
           
           //Campos No Requeridos en la Base de Datos.
           if($_POST[$ids.'_DateOfMounting'] != "" ){
               $eventsDataObj['eventMountingDate']  = $_POST[$ids.'_DateOfMounting']; 
           }
           if($_POST[$ids.'_DateOfStart'] != "" ){
               $eventsDataObj['eventInitDate']      = $_POST[$ids.'_DateOfStart'];
           }
           if($_POST[$ids.'_DateFinal'] != "" ){
               $eventsDataObj['eventFinishDate']    = $_POST[$ids.'_DateFinal'];
           }
           if($_POST[$ids.'_FK_employeUUID'] != "?" ){
               $eventsDataObj['FK_employeUUID']     = $_POST[$ids.'_FK_employeUUID'];
           }
           
           $funcStage   = "EntitySaveOfDataObj";
           
           //Guardar el registro
           $eventsSaveObj   = $eventsClass->entitySave($eventsDataObj);
           if($eventsSaveObj['error']){
               throw new Exception($message = $eventsSaveObj['msg']);
           }

           $funcStage           = "Finish";
           $tid                 = $eventsDataObj['eventUUID'];
           $retStruct['msg']    = "El registro se ha Agregado satisfactoriamente.";
           $retStruct['error']  = false;
           
       }catch(Exception $e){
           
           $retStruct['msg']    = $funcStage." : ".$e->getMessage();
           $retStruct['error']  = true;      
       }
       
       return $retStruct;
   }//fin de la funcion addForm
   
   /* funcion que se encarga de actualizar los datos */
   function updateForm(){
       
       $retStruct   = Array();
       $funcStage   = "";
       global $eventsClass;
       global $eventsDataObj;
       global $ids;
       global $tid;

       try{
           
           $funcStage   = "EntityLoadOfDataObj";
           
           //Cargo los datos de las tablas de base de Datos.
           $eventsObj   = $eventsClass->entityLoad("eventUUID = '".$_POST[$ids.'_eventUUID']."' and Active = true",true);
           if($eventsObj['error']){
               throw new Exception($message=$eventsObj['msg']);
           }
           
           //Obtengo la entidad con los datos consultados
           $eventsDataObj = $eventsObj['data'];
           
           $funcStage   = "SetupEventsDataObj";
           
           //Campos Requeridos en la Base de Datos.
           $eventsDataObj['eventName']          = $_POST[$ids.'_NameOfEvent'];
           $eventsDataObj['eventCity']          = $_POST[$ids.'_CityOfEvent'];
           $eventsDataObj['eventAddress']       = $_POST[$ids.'_AddrOfEvent'];
           $eventsDataObj['FK_EventTypeCode']   = $_POST[$ids.'_FK_EventTypeCode']; 
           $eventsDataObj['FK_ClientUUID']      = $_POST[$ids.'_FK_ClientUUID'];

           //Campos No Requeridos en la Base de Datos.
           if($_POST[$ids.'_DateOfMounting'] != "" ){
               $eventsDataObj['eventMountingDate']  = $_POST[$ids.'_DateOfMounting']; 
           }
           if($_POST[$ids.'_DateOfStart'] != "" ){
               $eventsDataObj['eventInitDate']      = $_POST[$ids.'_DateOfStart'];
           }
           if($_POST[$ids.'_DateFinal'] != "" ){
               $eventsDataObj['eventFinishDate']    = $_POST[$ids.'_DateFinal'];
           }
           if($_POST[$ids.'_FK_employeUUID'] != "?" ){
               $eventsDataObj['FK_employeUUID']     = $_POST[$ids.'_FK_employeUUID'];
           }

           $funcStage   = "EntitySaveOfDataObj";
           
           //Guarda el registro
           $eventsSaveObj   = $eventsClass->entitySave($eventsDataObj);
           if($eventsSaveObj['error']){
               throw new Exception($message = $eventsSaveObj['msg']);
           }

           $funcStage           = "Finish";
           $tid                 = $eventsDataObj['eventUUID'];
           $retStruct['msg']    = "El registro se ha Actualizado satisfactoriamente.";
           $retStruct['error']  = false; 
           
       }catch(Exception $e){
           
           $retStruct['msg']    = $funcStage." : ".$e->getMessage();
           $retStruct['error']  = true;      
       }
       
       return $retStruct;
   }//fin de la funcion updateForm
   
   /* funcion que se encarga de desactivar los datos */
   function removeForm(){
       
       $retStruct   = Array();
       $funcStage   = "";
       global $eventsClass;
       global $eventsDataObj;
       global $ids;
       global $tid;
       
       try{
           
           $funcStage   = "EntityLoadOfDataObj";
           
           //Cargo los datos de las tablas de base de Datos.
           $eventsObj   = $eventsClass->entityLoad("eventUUID = '".$_POST[$ids.'_eventUUID']."' and Active = true",true);
           if($eventsObj['error']){
               throw new Exception($message=$eventsObj['msg']);
           }
           
           //Obtengo la entidad con los datos consultados
           $eventsDataObj = $eventsObj['data'];

           $funcStage   = "EntitySaveOfDataObj";
           
           /* Activo el segundo parametro de la funcion entitySave, que le indica a la funcion
            que quiero desactivar de la base de datos el objeto que le paso como parametro */
           $eventsSaveObj   = $eventsClass->entitySave($eventsDataObj,true); 
           if($eventsSaveObj['error']){
               throw new Exception($message = $eventsSaveObj['msg']);
           }

           $funcStage           = "Finish";
           $tid                 = $eventsDataObj['eventUUID'];
           $retStruct['msg']    = "El registro se ha Eliminado satisfactoriamente.";
           $retStruct['error']  = false;
           
       }catch(Exception $e){
           
           $retStruct['msg']    = $funcStage." : ".$e->getMessage();
           $retStruct['error']  = true;      
       }
       
       return $retStruct;
   }//fin de la funcion removeForm
   
   /* funcion que se encarga de validar los campos del formulario */
   function validateForm(){
       
       $funcStage  = "";
       $retStruct  = Array();
       $validMsg   = Array();
       $validIndex = 1; 
       global $ids;
       
       try{
           
           //Se validan los campos del formulario
           $funcStage = "ValidationFields";
           
           if($_POST[$ids.'_FK_EventTypeCode'] == "?"){
              $validMsg[$validIndex] = "El campo Tipo de Evento es requerido."; 
           }
           if($_POST[$ids.'_FK_ClientUUID'] == "?"){
              $validIndex += 1;
              $validMsg[$validIndex] = "El campo Nombre de Cliente es requerido."; 
           }
           if($_POST[$ids.'_NameOfEvent'] == ""){
              $validIndex += 1; 
              $validMsg[$validIndex] = "El campo Nombre de Evento es requerido."; 
           }
           if($_POST[$ids.'_CityOfEvent'] == ""){
              $validIndex += 1; 
              $validMsg[$validIndex] = "El campo Ciudad de Evento es requerido."; 
           }
           if(is_numeric($_POST[$ids.'_CityOfEvent'])){
              $validIndex += 1; 
              $validMsg[$validIndex] = "El campo Ciudad de Evento No puede contener valores Numericos."; 
           }
           if($_POST[$ids.'_AddrOfEvent'] == ""){
              $validIndex += 1; 
              $validMsg[$validIndex] = "El campo Direccion de Evento es requerido."; 
           }

           //Se evalua la validacion de los campos 
           if(count($validMsg) == 0){
              $retStruct['valid'] = true;
           }else{
              $retStruct['msg']   = implode("<br>",$validMsg);   
              $retStruct['valid'] = false;  
           }
           
           $funcStage           = "Finish";
           $retStruct['error']  = false;

       }catch(Exception $e){
           
          if($e->getMessage()!= "Oops"){
             $retStruct['msg']   = $funcStage." : ".$e->getMessage();
             $retStruct['error'] = true;    
          }
          $retStruct['valid'] = false;
       }
       
       return $retStruct;	
   }//fin de la funcion validateForm
    
/* END FUNCTIONS INTERNAL FOR THE OPERATIONS */  
    
/* SECTION FOR FUNCTIONS OF ERRORS. 
   Funciones de Segundo Orden, se encargan de estructurar 
   los datos que son causados por errores en la carga de datos
   de la Grilla o el Formulario  */
   
    /* funcion que retorna el xml para el Manejo de Errores
       que pertenecen a la carga de datos de la grilla */   
    function gridError($errMsg){
        $errMsgXML = "";
        
        header('Content-Type: application/xml');
        $errMsgXML = "<rows>";
            $errMsgXML .= "<userdata name='hasError'>true</userdata>";
            $errMsgXML .= "<userdata name='errorMsg'><![CDATA[$errMsg]]></userdata>";
            $errMsgXML .= "<row id='1'>";
                $errMsgXML .= "<cell>ERROR</cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell><![CDATA[$errMsg]]></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
                $errMsgXML .= "<cell></cell>";
            $errMsgXML .= "</row>";
        $errMsgXML .= "</rows>";
            
        return $errMsgXML; 
    }//fin de la funcion gridError
    
    /* funcion que retorna el xml para el Manejo de Errores
       que pertenecen a la carga de datos del formulario */    
    function formError($errMsg){
        $errMsgXML = "";
        
        header('Content-Type: application/xml');
        $errMsgXML = "<data>";
            $errMsgXML .= "<action type='fail' tid=''><![CDATA[$errMsg]]></action>";
        $errMsgXML .= "</data>";
        
        return $errMsgXML; 
    }//fin de la funcion formError
    
/* END SECTION FOR FUNCTIONS OF ERRORS */ 

