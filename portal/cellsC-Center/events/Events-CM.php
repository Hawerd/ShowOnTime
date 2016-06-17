<?php
/*
    Name:   Events-CM.php
    Autor:  Luis F Castaño
    Date:   17-Jun-2016
    Desc:   Controlador para el Modulo Eventos.
   
*/

/* PRE-PROCESSING FOR VALIDATION OF METHOD AND FORMAT.
   Se encarga de Validar los campos format y method provenientes 
   de la Vista Si esto valores no se encuentran o no estan 
   definidos el controlador finalizara el script y retornara su 
   respectivo mensaje de Error en xml.  */ 

try{
    
    //Variables Globales del Modulo
    $unexpError = "This is an Unexpected Error";
    $respXml    = "";
    $respMsg    = "";

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
        case "submitDataForm":
            $actualFlds = count($_POST);
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
        
        $gridErrMsg = "";
        global $retXml;
       
        try{
            
            header('Content-Type: application/xml');
            $retXml = "<rows>";
                $retXml .= "<row id='1'>";
                    $retXml .= "<cell>EV000001</cell>";
                    $retXml .= "<cell>Luis Fernando Castaño Rodriguez</cell>";
                    $retXml .= "<cell>Juan Pablo Ramirez Alvarado</cell>";
                    $retXml .= "<cell>Cumpl.Sr Osorio</cell>";
                    $retXml .= "<cell>Cali</cell>";
                    $retXml .= "<cell>Cll 20 No 22-23</cell>";
                    $retXml .= "<cell>28-May-2016</cell>";
                    $retXml .= "<cell>29-May-2016</cell>";
                    $retXml .= "<cell>31-May-2016</cell>";
                    $retXml .= "<cell></cell>";
                    $retXml .= "<cell>submitDataForm</cell>";
                    $retXml .= "<cell>update</cell>";
                $retXml .= "</row>";
                $retXml .= "<row id='2'>";
                    $retXml .= "<cell>EV000002</cell>";
                    $retXml .= "<cell>Victor Gutierrez Garcia</cell>";
                    $retXml .= "<cell>Maria Veronica Pulido Castillo</cell>";
                    $retXml .= "<cell>Concierto Sinf. Kraken</cell>";
                    $retXml .= "<cell>Palmira</cell>";
                    $retXml .= "<cell>Cll 100 No 50-55</cell>";
                    $retXml .= "<cell>31-May-2016</cell>";
                    $retXml .= "<cell>01-Jun-2016</cell>";
                    $retXml .= "<cell>02-Jun-2016</cell>";
                    $retXml .= "<cell></cell>";
                    $retXml .= "<cell>submitDataForm</cell>";
                    $retXml .= "<cell>update</cell>";
                $retXml .= "</row>";
                $retXml .= "<row id='3'>";
                    $retXml .= "<cell>EV000003</cell>";
                    $retXml .= "<cell>Hawerd Gonzales</cell>";
                    $retXml .= "<cell>Andres Alejandro Nieto</cell>";
                    $retXml .= "<cell>Concierto Mana</cell>";
                    $retXml .= "<cell>Florida</cell>";
                    $retXml .= "<cell>Cra 1 No 12-22A</cell>";
                    $retXml .= "<cell>10-Jun-2016</cell>";
                    $retXml .= "<cell>11-Jun-2016</cell>";
                    $retXml .= "<cell>12-Jun-2016</cell>";
                    $retXml .= "<cell></cell>";
                    $retXml .= "<cell>submitDataForm</cell>";
                    $retXml .= "<cell>update</cell>";
                $retXml .= "</row>";
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
        
        //Variables locales de la funcion
        $submitStage    = "";
        $expectedFlds   = "";
        $doValidation   = "";
        $operationResp  = "";
        
        try{
            
            $submitStage = "initialization";
            
            //Numero de Campos esperados por parte del Fomrulario
            $expectedFlds = 15;
            
            //Chequea el numero de campos del formulario
            if($expectedFlds != $actualFlds){
                $retMsg = "Incorrect number of fields: ".$actualFlds." vs ".$expectedFlds;
                throw new Exception($message = "Oops");
            }
            
            //se obtiene la operation
            $operation = $_POST[$ids.'_op'];
            
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
       
       try{
           
           $funcStage   = "EntityNew";
           
           
           $funcStage           = "Finish";
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
       
       try{
           
           $funcStage   = "LoadEntity";
           
           $funcStage           = "Finish";
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
       
       try{
           
           $funcStage   = "LoadEntity";
           
           $funcStage           = "Finish";
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
           
           if($_POST[$ids.'_NameOfClient'] == ""){
              $validMsg[$validIndex] = "El campo Nombre de Cliente es Requerido."; 
           }
           if($_POST[$ids.'_NameOfEmployee'] == ""){
              $validIndex += 1;
              $validMsg[$validIndex] = "El campo Nombre de Empleado es Requerido."; 
           }
           if($_POST[$ids.'_NameOfEvent'] == ""){
              $validIndex += 1; 
              $validMsg[$validIndex] = "El campo Nombre de Evento es Requerido."; 
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

