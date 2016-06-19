<?php

/*
    Name:   MethodsORM.php
    Autor:  Luis F Castaño
    Date:   18-Jun-2016
    Desc:   Contiene los metodos que podran utilizar las ORMs
 
*/

require_once 'ConexionPDO.php';

class methods{
    
    protected   $retConnObj;
    protected   $connObj;

    public function __construct(){

        $conexObj         = new conexion();        //Se instancia la clase conexion
        $this->retConnObj = $conexObj->connect();  //Objeto con la conexion a la base de datos
        
    }//fin de la funcion constructor_ObjORM

    //funcion que carga los datos de la tabla requerida
    public function entityLoad($sqlfilter=null,$sqlUnique=false){

        $retStruct = Array();
        $fieldName = "";
        
        try{
            
            if($this->retConnObj['error']){
                throw new PDOException($message = $this->retConnObj['msg']);      
            }
            
            //creamos la listas de los campos para completar la sentencia de Base de Datos
            foreach($this->entityObj as $key => $val ) {
                $fieldName .= $key.",";
            }//fin del ciclo
            
            $fieldName = substr ($fieldName, 0, strlen($fieldName) - 1); 

            $this->connObj = $this->retConnObj['conn'];
            $sql   = 'SELECT '.$fieldName.' FROM '.$this->nameTable.' order by UpdatedDT';
          
            if(!is_null($sqlfilter)){
                $sql = 'SELECT '.$fieldName.' FROM '.$this->nameTable.' WHERE '.$sqlfilter;      
            }
            
            //Se executa el query y se obtiene los datos relacionales
            $dataQueryObj  = $this->connObj->prepare($sql);
            $dataQueryObj->execute();  
            $dataObj       = $dataQueryObj->fetchAll();
            
            if($sqlUnique){
               $dataObj = $dataObj[0]; 
            }

            //finish
            $retStruct['msg']    = "successQuery";
            $retStruct['error']  = false;
            $retStruct['data']   = $dataObj;
            
        }catch(PDOException $e){
            
            $retStruct['msg']    = "Error entiyLoad: ".$e->getMessage();
            $retStruct['error']  = true;
            
        }//fin del catch
       
        return $retStruct;
    }//fin de la funcion entityLoad
    
    //funcion que se encarga de crear una entidad(Array)vacia de la tabla
    public function entityNew(){

        $retStruct = Array();
        
        try{
            
            if($this->retConnObj['error']){
                throw new PDOException($message = $this->retConnObj['msg']);      
            }
            
            //Se establece zona horaria
            date_default_timezone_set("America/Bogota" ) ; 
            $today = date("Y-m-d H:i:s");
            
            $this->entityObj['CreatedDT']           = $today;
            $this->entityObj['CreatedBy']           = "Luis";
            $this->entityObj['UpdatedDT']           = $today;
            $this->entityObj['UpdatedBy']           = "Luis";
            $this->entityObj['Active']              = false;
            $this->entityObj['ActiveDT']            = $today;
            $this->entityObj['ActiveBy']            = "Luis";
            $this->entityObj['New']                 = "NewRecord";

            //finish
            $retStruct['msg']    = "successQuery";
            $retStruct['error']  = false;
            $retStruct['data']   = $this->entityObj;
            
        }catch(PDOException $e){
            
            $retStruct['msg']    = "Error entiyNew: ".$e->getMessage();
            $retStruct['error']  = true;
        }
        
        return $retStruct; 
    }//fin de la funcion entityNew
    
    //funcion que se encarga de crear una entidad(Array)vacia de la tabla
    public function entitySave($obj=null){
        
        $retStruct = Array();
        $fieldName = "";
        $fieldBind = "";
        $binParam  = "";
        $binField  = "";
        
        try{
            
            if($this->retConnObj['error']){
                throw new PDOException($message = $this->retConnObj['msg']);      
            }
            
            if(is_null($obj)){
                
               throw new PDOException($message = "It requires a parameter as an argument."); 
               
            }else{
               
               $this->connObj = $this->retConnObj['conn'];
               
               //creamos la listas de los campos para completar la sentencia de Base de Datos
               foreach($this->entityObj as $key => $val ) {
                   if($key != "New"){
                      $fieldName .= $key.",";
                      $fieldBind .= ":".$key.",";
                   }
               }//fin del ciclo

               $fieldName = substr ($fieldName, 0, strlen($fieldName) - 1); 
               $fieldBind = substr ($fieldBind, 0, strlen($fieldBind) - 1);
               $binParam  = explode(",",$fieldBind);
               $binField  = explode(",",$fieldName);
    
               if(isset($obj['New'])){

                   $insertSql = $this->connObj->prepare("INSERT INTO ".$this->nameTable." ($fieldName) VALUES ($fieldBind)");

                   //Se adjunta los compos de la sentencia con el valor obtenido
                   for($i=0;$i<count($binField);$i++){
                       $insertSql->bindParam($binParam[$i],$obj[$binField[$i]]);  
                   }
                   
                   $insertSql->execute();

                   $retStruct['msg']    = "success";
                   $retStruct['error']  = false;
 
                }else{
  
                   $updateSql = $this->connObj->prepare("UPDATE ".$this->nameTable." SET Active = false WHERE ".$binField[0]." = '".$obj[$binField[0]]."'");
                   $insertSql = $this->connObj->prepare("INSERT INTO ".$this->nameTable." ($fieldName) VALUES ($fieldBind)");
                   
                   //Se adjunta los compos de la sentencia con el valor obtenido
                   for($i=0;$i<count($binField);$i++){
                       $insertSql->bindParam($binParam[$i],$obj[$binField[$i]]);  
                   }
                   
                   $updateSql->execute();
                   $insertSql->execute();
                   
                   $retStruct['msg']    = "successQuery";
                   $retStruct['error']  = false;
                   
                }    
            }
   
        }catch(PDOException $e){
            
            $retStruct['msg']    = "Error entitySave: ".$e->getMessage();
            $retStruct['error']  = true;
            
        }//fin del catch
      
        return $retStruct;
    }//fin de la funcion entityNew

}//fin de la clase methods