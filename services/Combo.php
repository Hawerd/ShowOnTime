<?php

/*
    Name:   Combo.php
    Autor:  Luis F Castaño
    Date:   25-Jun-2016
    Desc:   Se crea puente para el instanciamiento de las funciones      
            getCombo del archivo FunctionUtility.php.
  
    Autor:  
    Date:   
    Desc:   

*/

try{
    
    //type xml return
    header('Content-Type: application/xml');
    
    //variables generales del modulo combo.
    $unexpError     = "This is an Unexpected Error";
    $respMsg        = "";
    $respXml        = "";
    $methodVal      = "";
    $codeVal        = "";   

    if(!isset($_REQUEST['method'])){
        $respMsg = "Method not defined";
        throw new Exception($message="Oops");
    }
    
    if($_REQUEST['method'] == "" ){
        $respMsg = "Method is blank";
        throw new Exception($message="Oops");
    }
    
    $methodVal = $_REQUEST['method'];
    
    //Adjunta archivo functionUtility e instancia la clase funcion Utility
    require_once "FunctionUtility.php";  
    $funcUtlClass = new functionUtility();
    
    //Se evalua que exista el segundo parametro no requerido
    if(isset($_REQUEST['code'])){
        
        if($_REQUEST['code'] == ""){
            $respMsg = "Code is blank";
            throw new Exception($message="Oops");
        }
        
        $codeVal  = $_REQUEST['code'];    
        
        eval('$respXml = $funcUtlClass->$methodVal($codeVal);');
        
    }else{
        
        eval('$respXml = $funcUtlClass->$methodVal();');
        
    }//fin de la condicion
    
    print($respXml);

}catch(Exception $e){
    
    if($e->getMessage()!= "Oops"){
        $respMsg = $unexpError." : ".$e->getMessage();
    }
    
    //formato typo combo
    $respXml  = "<complete><option value='error'>$respMsg</option></complete>";
    
    //Finaliza el script y devuelve el Error
    exit ($respXml);
    
}//fin del catch

