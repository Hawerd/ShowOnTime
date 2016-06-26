<?php

/*
    Name:   FunctionUtility.php
    Autor:  Luis F Castaño
    Date:   25-Jun-2016
    Desc:   Se crea clase para contener diversas funciones que apoyaran el 
            proceso de trabajo de cada uno de los modulos del portal por ejem:
            Funciones que poblan Combos(getCombo).
  
    Autor:  
    Date:   
    Desc:   

*/

class functionUtility {
    
    private $respMsg;
    private $respXml;
    private $unexpError;
    private $noneOption;
    private $rootORM;
    
    function __construct() {
        
        $this->unexpError  = "This is an Unexpected Error";
        $this->noneOption  = "Seleccione el Tipo";  //for Combos
        $this->rootORM     = "/../orm/";            //for ORMs
        $this->respMsg     = "";
        $this->respXml     = "";
        
    }//fin del constructor
    
   /* funcion que se encarga de realizar una estructura xml para
      poblar los combos que requieran los datos tipo de cliente */
    function getClientTypeCombo(){
        
        try{
            
            //Adjunta archivo ORM e Instancia clase codClientType
            require_once $this->rootORM."cod/CodClientType.php";    
            $clientTypeClass  = new codClientType();                 
            
            //type xml return
            header('Content-Type: application/xml');
            $this->respXml  = "<complete>";
            
            //Se Carga los datos de la tabla codClientType.
            $clientTypeObj  = $clientTypeClass->entityLoad("Active = true");
            
            //Se Valida que no haya Error en la Carga de datos.
            if($clientTypeObj['error']){
                throw new Exception($message=$clientTypeObj['msg']);
            }
            
            //Se obtiene los datos del objeto clientType.
            $clientTypeDataObj  = $clientTypeObj['data'];
            
            $this->respXml.= "<option value='?'>$this->noneOption</option>";
            
            for($i=0; $i<count($clientTypeDataObj); $i++){
                
               $code        = $clientTypeDataObj[$i]['clientTypeCode'];
               $description = $clientTypeDataObj[$i]['clientTypeDesc'];
               
               $this->respXml.= "<option value='".$code."'><![CDATA[$description]]></option>";
                       
            }//fin del ciclo
            
            $this->respXml.= "</complete>";

        }catch(Exception $e){
            
            //msg error unexpected
            $this->respMsg = $this->unexpError." : ".$e->getMessage();
            
            //formato typo combo
            $this->respXml  = "<complete><option value='error'>$this->respMsg</option></complete>";
            
        }//fin del catch
        
        return $this->respXml;
    }//fin de la funcion getClientTypeCombo
    
    /* funcion que se encarga de realizar una estructura xml para
       poblar los combos que requieran los datos tipo elemento */
    function getElementTypeCombo(){
        
        try{
            
            //Adjunta archivo ORM e Instancia clase codClientType
            require_once $this->rootORM."cod/CodElementType.php";    
            $elementTypeClass  = new codElementType();                 
            
            //type xml return
            header('Content-Type: application/xml');
            $this->respXml  = "<complete>";
            
            //Se Carga los datos de la tabla codElementType.
            $elementTypeObj  = $elementTypeClass->entityLoad("Active = true");
            
            //Se Valida que no haya Error en la Carga de datos.
            if($elementTypeObj['error']){
                throw new Exception($message=$elementTypeObj['msg']);
            }
            
            //Se obtiene los datos del objeto elementType.
            $elementTypeDataObj  = $elementTypeObj['data'];
            
            $this->respXml.= "<option value='?'>$this->noneOption</option>";
            
            for($i=0; $i<count($elementTypeDataObj); $i++){
                
               $code        = $elementTypeDataObj[$i]['elementTypeCode'];
               $description = $elementTypeDataObj[$i]['elementTypeDesc'];
               
               $this->respXml.= "<option value='".$code."'><![CDATA[$description]]></option>";
                       
            }//fin del ciclo
            
            $this->respXml.= "</complete>";

        }catch(Exception $e){
            
            //msg error unexpected
            $this->respMsg = $this->unexpError." : ".$e->getMessage();
            
            //formato typo combo
            $this->respXml  = "<complete><option value='error'>$this->respMsg</option></complete>";
            
        }//fin del catch
        
        return $this->respXml;
    }//fin de la funcion getElementTypeCombo
    
    /* funcion que se encarga de realizar una estructura xml para
       poblar los combos que requieran los datos tipo empleado */
    function getEmpleoyesTypeCombo(){
        
        try{
            
            //Adjunta archivo ORM e Instancia clase codClientType
            require_once $this->rootORM."cod/CodEmpleoyesType.php";    
            $empleoyesTypeClass  = new codEmpleoyesType();                 
            
            //type xml return
            header('Content-Type: application/xml');
            $this->respXml  = "<complete>";
            
            //Se Carga los datos de la tabla codEmpleoyesType.
            $empleoyesTypeObj  = $empleoyesTypeClass->entityLoad("Active = true");
            
            //Se Valida que no haya Error en la Carga de datos.
            if($empleoyesTypeObj['error']){
                throw new Exception($message=$empleoyesTypeObj['msg']);
            }
            
            //Se obtiene los datos del objeto empleoyesType.
            $empleoyesTypeDataObj  = $empleoyesTypeObj['data'];
            
            $this->respXml.= "<option value='?'>$this->noneOption</option>";
            
            for($i=0; $i<count($empleoyesTypeDataObj); $i++){
                
               $code        = $empleoyesTypeDataObj[$i]['empleoyeTypeCode'];
               $description = $empleoyesTypeDataObj[$i]['empleoyeTypeDesc'];
               
               $this->respXml.= "<option value='".$code."'><![CDATA[$description]]></option>";
                       
            }//fin del ciclo
            
            $this->respXml.= "</complete>";

        }catch(Exception $e){
            
            //msg error unexpected
            $this->respMsg = $this->unexpError." : ".$e->getMessage();
            
            //formato typo combo
            $this->respXml  = "<complete><option value='error'>$this->respMsg</option></complete>";
            
        }//fin del catch
        
        return $this->respXml;
    }//fin de la funcion getEmpleoyesTypeCombo
    
    /* funcion que se encarga de realizar una estructura xml para
       poblar los combos que requieran los datos tipo evento */
    function getEventTypeCombo(){
        
        try{
            
            //Adjunta archivo ORM e Instancia clase codEventType
            require_once $this->rootORM."cod/CodEventType.php";    
            $eventTypeClass  = new codEventType();                 
            
            //type xml return
            header('Content-Type: application/xml');
            $this->respXml  = "<complete>";
            
            //Se Carga los datos de la tabla codEventType.
            $eventTypeObj  = $eventTypeClass->entityLoad("Active = true");
            
            //Se Valida que no haya Error en la Carga de datos.
            if($eventTypeObj['error']){
                throw new Exception($message=$eventTypeObj['msg']);
            }
            
            //Se obtiene los datos del objeto eventTypeObj.
            $eventTypeDataObj  = $eventTypeObj['data'];
            
            $this->respXml.= "<option value='?'>$this->noneOption</option>";
            
            for($i=0; $i<count($eventTypeDataObj); $i++){
                
               $code        = $eventTypeDataObj[$i]['eventTypeCode'];
               $description = $eventTypeDataObj[$i]['eventTypeDesc'];
               
               $this->respXml.= "<option value='".$code."'><![CDATA[$description]]></option>";
                       
            }//fin del ciclo
            
            $this->respXml.= "</complete>";

        }catch(Exception $e){
            
            //msg error unexpected
            $this->respMsg = $this->unexpError." : ".$e->getMessage();
            
            //formato typo combo
            $this->respXml  = "<complete><option value='error'>$this->respMsg</option></complete>";
            
        }//fin del catch
        
        return $this->respXml;
    }//fin de la funcion getEmpleoyesTypeCombo
    
    /* funcion que se encarga de realizar una estructura xml para
       poblar los combos que requieran los nombres de los clientes. */
    function getClientsCombo(){
        
        try{
            
            //Adjunta archivo ORM e Instancia clase sistClients
            require_once $this->rootORM."sist/SistClients.php";    
            $clientsClass  = new sistClients();
            
            $this->noneOption = "Seleccione Cliente";
            
            //type xml return
            header('Content-Type: application/xml');
            $this->respXml  = "<complete>";
            
            //Se Carga los datos de la tabla sistClients.
            $clientsObj  = $clientsClass->entityLoad("Active = true");
            
            //Se Valida que no haya Error en la Carga de datos.
            if($clientsObj['error']){
                throw new Exception($message=$clientsObj['msg']);
            }
            
            //Se obtiene los datos del objeto sistClients.
            $clientsDataObj  = $clientsObj['data'];
            
            $this->respXml.= "<option value='?'>$this->noneOption</option>";
            
            for($i=0; $i<count($clientsDataObj); $i++){
                
               $code        = $clientsDataObj[$i]['clientUUID'];
               $description = $clientsDataObj[$i]['clientFirstName']." ".$clientsDataObj[$i]['clientLastName'];
               
               $this->respXml.= "<option value='".$code."'><![CDATA[$description]]></option>";
                       
            }//fin del ciclo
            
            $this->respXml.= "</complete>";

        }catch(Exception $e){
            
            //msg error unexpected
            $this->respMsg = $this->unexpError." : ".$e->getMessage();
            
            //formato typo combo
            $this->respXml  = "<complete><option value='error'>$this->respMsg</option></complete>";
            
        }//fin del catch
        
        return $this->respXml;
    }//fin de la funcion getClientsCombo
    
    /* funcion que se encarga de realizar una estructura xml para
       poblar los combos que requieran los nombres de los empleados. */
    function getEmpleoyesCombo(){
        
        try{
            
            //Adjunta archivo ORM e Instancia clase sistEmpleoyes
            require_once $this->rootORM."sist/SistEmpleoyes.php";    
            $empleoyesClass  = new sistEmpleoyes();
            
            $this->noneOption = "Seleccione Empleado";
            
            //type xml return
            header('Content-Type: application/xml');
            $this->respXml  = "<complete>";
            
            //Se Carga los datos de la tabla sistEmpleoyes.
            $empleoyesObj  = $empleoyesClass->entityLoad("Active = true");
            
            //Se Valida que no haya Error en la Carga de datos.
            if($empleoyesObj['error']){
                throw new Exception($message=$empleoyesObj['msg']);
            }
            
            //Se obtiene los datos del objeto sistEmpleoyes.
            $empleoyesDataObj  = $empleoyesObj['data'];
            
            $this->respXml.= "<option value='?'>$this->noneOption</option>";
            
            for($i=0; $i<count($empleoyesDataObj); $i++){
                
               $code        = $empleoyesDataObj[$i]['empleoyeUUID'];
               $description = $empleoyesDataObj[$i]['empleoyeFirstName']." ".$empleoyesDataObj[$i]['empleoyeLastName'];
               
               $this->respXml.= "<option value='".$code."'><![CDATA[$description]]></option>";
                       
            }//fin del ciclo
            
            $this->respXml.= "</complete>";

        }catch(Exception $e){
            
            //msg error unexpected
            $this->respMsg = $this->unexpError." : ".$e->getMessage();
            
            //formato typo combo
            $this->respXml  = "<complete><option value='error'>$this->respMsg</option></complete>";
            
        }//fin del catch
        
        return $this->respXml;
    }//fin de la funcion getEmpleoyesCombo
    
}//fin de la clase functionUtility

