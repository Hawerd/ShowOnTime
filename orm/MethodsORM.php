<?php

/*
    Name:   MethodsORM.php
    Autor:  Luis F Castaño
    Date:   18-Jun-2016
    Desc:   Contiene los metodos que podran utilizar las ORMs.
            Se agrega en la funcion entitySave() la cofiguracion $obj['UpdatedDT'].
            Se agregan comentarios para las funciones de la clase, ademas se agrega mas
            logica a la funcion entitySave.
  
    Autor:  Luis F Castaño
    Date:   21-Jun-2016
    Desc:   Se realiza ajuste en la funcion entitySave, para que actualize el campo
            updatedDT cuando realiza una actualizacion al registro que queda false.
  
    Autor:  Luis F Castaño
    Date:   26-Jun-2016
    Desc:   se agrega llamado de la funcion init en la funcion entityNew
  
    Autor:  Luis F Castaño
    Date:   28-Jun-2016
    Desc:   Se ajusta error en la funcion entityLoad cuando se crea la lista de campos, se agrega
            en el ciclo la condicion key != New.
  
*/

require_once 'ConexionPDO.php';

class methods{
    
    protected   $retConnObj;
    protected   $connObj;

    public function __construct(){

        $conexObj         = new conexion();        //Se instancia la clase conexion
        $this->retConnObj = $conexObj->connect();  //Objeto con la conexion a la base de datos
        
    }//fin de la funcion constructor_ObjORM

    /* funcion que carga los datos de la tabla requerida. 
       param: sqlFilter: filtro para la realizacion de la sentencia de consulta.
              sqlUnique: true, si se quiere obtener un registro unico. 
                         false por defecto,si se quiere obtener un conjunto de varios registros. */
    public function entityLoad($sqlfilter=null,$sqlUnique=false){

        $retStruct = Array();
        $fieldName = "";
        
        try{
            
            if($this->retConnObj['error']){
                throw new PDOException($message = $this->retConnObj['msg']);      
            }
            
            //creamos la listas de los campos para completar la sentencia de Base de Datos
            foreach($this->entityObj as $key => $val ) {
                if($key != "New"){
                    $fieldName .= $key.",";
                }
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
    
    /* funcion que se encarga de crear una entidad(Array)vacia de la tabla requerida. */
    public function entityNew(){

        $retStruct = Array();
        
        try{
            
            $this->init(); //Inicializa datos predeterminado de la ORM.
            
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
    
    /* funcion que se encarga de crear una entidad(Array)vacia de la tabla.
       Param: $obj: Objeto(Array) de la entidad que se quiere guardar en la base de datos.
              $rmv: true, si se quiere desactivar el registro u objeto que se provee por parametro.
                    false por defecto, Se hara una nueva inserccion o actualizacion sobre el registro 
                    u bojeto que se provee por parametro. */
    public function entitySave($obj=null,$rmv=false){
        
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
                    
                   //Se establece zona horaria
                   date_default_timezone_set("America/Bogota" ) ; 
                   $today = date("Y-m-d H:i:s");

                   if($rmv){
                       
                       $updateSql = $this->connObj->prepare("UPDATE ".$this->nameTable." SET Active = false, UpdatedDT = '".$today."' WHERE ".$binField[0]." = '".$obj[$binField[0]]."'");
                       
                       $updateSql->execute();
                       
                   }else{
                       
                       //Se establece la fecha de actualizacion del registro
                       $obj['UpdatedDT'] = $today;
                       
                       $updateSql = $this->connObj->prepare("UPDATE ".$this->nameTable." SET Active = false, UpdatedDT = '".$today."' WHERE ".$binField[0]." = '".$obj[$binField[0]]."' and Active = true");
                       $insertSql = $this->connObj->prepare("INSERT INTO ".$this->nameTable." ($fieldName) VALUES ($fieldBind)");
                       
                       //Se adjunta los compos de la sentencia con el valor obtenido
                       for($i=0;$i<count($binField);$i++){
                           $insertSql->bindParam($binParam[$i],$obj[$binField[$i]]);  
                       }

                       $updateSql->execute();
                       $insertSql->execute();
                       
                   }//fin de la condicion $rmv

                   $retStruct['msg']    = "successQuery";
                   $retStruct['error']  = false;
                   
                }//fin de la condicion $obj['New'] esta definido.
                
            }//fin de la condicion que evalua si ha objeto como parametro obligatorio
   
        }catch(PDOException $e){
            
            $retStruct['msg']    = "Error entitySave: ".$e->getMessage();
            $retStruct['error']  = true;
            
        }//fin del catch
      
        return $retStruct;
    }//fin de la funcion entityNew

}//fin de la clase methods