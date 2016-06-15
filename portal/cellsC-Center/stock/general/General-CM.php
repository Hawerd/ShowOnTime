<?php
   
    require_once '../../../../orm/element.php';
  
    // METHOD RECIBIDO: si es del formulario o grilla. ejecuta la funcion requerida.
    if(isset($_REQUEST['format'])){

        $format = $_REQUEST['format'];
        //echo $format;
        $format();
    }    
        
        //echo json_encode($format);
    
    function grid(){
        
        //require_once('../../../../orm/Element.php');
        
        entityLoad();
       
        //$res = $con->consultar("select * from ufproduct where active = true order by ProductName");
    /*          
        header('Content-Type: application/xml');
        $retXml = "<rows>";
            $retXml .= "<row id='1'>";
            $retXml .= "<cell>EV000001</cell>";
            $retXml .= "<cell>Luis Fernando Castaño Rodriguez</cell>";
            $retXml .= "</row>";
        $retXml .= "</rows>";
        
        print ($retXml);
    */    
    }
    
    function entityLoad(){
        $c = new element();
        //header('Content-Type: application/xml');
        $c->entityLoad();
        echo $c;
//        $con = new conexion();
//        $res = $con->consultar("select * from ufproduct where active = true order by ProductName");
    }
        
    function form(){

        $ids    = $_REQUEST['ids']; 
        $method = $_REQUEST[$ids.'_method']; 

        echo json_encode($method);
/*           
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
*/
    }// end function form
    

