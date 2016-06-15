
<?php
    require_once '../orm/Productos.php';
    // METHOD RECIBIDO: si es del formulario o grilla. ejecuta la funcion requerida.
    if(isset($_REQUEST['format'])){

        $nombreFuncion = $_REQUEST['format'];
        $nombreFuncion();

    }    

    // Carga de los datos para la grilla
    function grid(){
        
        require("../codebase/connector/grid_connector.php");
        
        
        $res    = mysql_connect("localhost","root","");
        mysql_select_db("ufqye");

        $conn = new GridConnector($res,Mysql);
        //$conn->render_table("viewproducts","ProductCodeUUID","ProductID,ProductCodeUUID,ProductName,sortSeq,ProductDesc,RawCost,SalePrice,FK_IVACode,PublicPrice,Active");
        $conn->render_table("viewproducts","ProductCodeUUID","ProductID,ProductCodeUUID,ProductName,Active");
    }
       
    // Evalua si se esta insertando, eliminado o actulizando.
    function form(){
        
        $ids    = $_REQUEST['ids'];    
        $method = $_REQUEST[$ids.'_method'];
        switch($method){
            case 'submitProductsForm':
                addProducto();
                break;
            case 'updateProductosForm':
                updateProduct();
                break;
            case 'removeProductosForm':
                removeProduct();
                break;            
            
        }
    }
    // agregar producto
    function addProducto(){
        
        $error      = false;
        $tid        = "";
        try{
            $info   = array();
           
            $p      = new Productos();
            $ids    = $_REQUEST['ids']; 
            $ProductCodeUUID    = uniqid(mt_rand(), true);
            $p->setProductCodeUUID($ProductCodeUUID);
            $p->setProductName($_REQUEST[$ids.'_ProductName']);
       /*
            $p->setProductDesc($_REQUEST[$ids.'_ProductDesc']);
            $p->setRawCost($_REQUEST[$ids.'_RawCost']);
            $p->setSalePrice($_REQUEST[$ids.'_SalePrice']);
            $p->setFK_IVACode($_REQUEST[$ids.'_FK_IVACode']);
            $p->setPublicPrice($_REQUEST[$ids.'_PublicPrice']);
         */   
            if (!$p->registrarProductos()){
                $action = "success";
                $tid    =  $ProductCodeUUID;
            } else {
                throw new Exception('Erro al insertar.');
            }
        } catch (Exception $e) {
            
            $action = "fail";
            $error  = $e; 
        }
       
	$info['action'] = $action;
	$info['tid']    = $tid;
        $info['error']  = $error;
	echo json_encode($info);
    }
    
    function updateProduct(){
        
        $error      = false;
        $tid        = "";
        $info   = array();
        
        try{
            
            $p      = new Productos();
            $ids    = $_REQUEST['ids']; 
           
            $ProductCodeUUID    = $_REQUEST[$ids.'_ProductCodeUUID'];
            $p->setProductCodeUUID($ProductCodeUUID);
            $p->setProductName($_REQUEST[$ids.'_ProductName']);
            $p->setSortSeq($_REQUEST[$ids.'_sortSeq']);
            $p->setProductDesc($_REQUEST[$ids.'_ProductDesc']);
            $p->setRawCost($_REQUEST[$ids.'_RawCost']);
            $p->setSalePrice($_REQUEST[$ids.'_SalePrice']);
            $p->setFK_IVACode($_REQUEST[$ids.'_FK_IVACode']);
            $p->setPublicPrice($_REQUEST[$ids.'_PublicPrice']);
            
            if (!$p->actualizarProducto()){
                $action = "success";
                $tid    =  $ProductCodeUUID;
            } else {
                throw new Exception('Erro al insertar.');
            }
        } catch (Exception $ex) {
            
            $action = "fail";
            $error  = $ex->getMessage(); 
        }
       
	$info['action'] = $action;
	$info['tid']    = $tid;
        $info['error']  = $error;
	echo json_encode($info);    
    }
    
    function removeProduct(){
        $error      = false;
        $tid        = "";
        $info   = array();
      
        try{
            
            $p      = new Productos();
            $ids    = $_REQUEST['ids'];  
            $ProductCodeUUID    = $_REQUEST[$ids.'_ProductCodeUUID'];
            $p->setProductCodeUUID($ProductCodeUUID);
         
            if (!$p->remove()){
                $action = "success";
                $tid    =  0;
            } else {
                throw new Exception('Erro al insertar.');
            }
        } catch (Exception $ex) {
            
            $action = "fail";
            $error  = $ex->getMessage(); 
        }
       
	$info['action'] = $action;
	$info['tid']    = $tid;
        $info['error']  = $error;
	echo json_encode($info);   
    }