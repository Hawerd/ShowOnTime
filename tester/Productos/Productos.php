<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Productos
 *
 * @author Hawerd
 */

require_once '../conexion/MC_conexion.php';


class Productos {
    
    private $ProductID;
    private $ProductCodeUUID;
    private $ProductName;
    private $ProductDesc;
    private $RawCost;
    private $SalePrice;
    private $FK_IVACode;
    private $PublicPrice;
    private $Active;
    private $sortSeq;
    
    public function registrarProductos(){
        
        $ProductID          = NULL;
        $ProductCodeUUID    = $this->getProductCodeUUID();
        $ProductName        = $this->getProductName();
    /*
        $sortSeq            = 1;
        $ProductDesc        = $this->getProductDesc();
        $RawCost            = $this->getRawCost();
        $SalePrice          = $this->getSalePrice();
        $FK_IVACode         = $this->getFK_IVACode();
        $PublicPrice        = $this->getPublicPrice();
     * 
     */
        $Active             = true;
       
        $con = new conexion();
        try{
            
//            $res = $con->insertar(" INSERT INTO ufproduct (ProductID,ProductCodeUUID,ProductName,ProductDesc,RawCost,SalePrice,FK_IVACode,PublicPrice,Active,sortSeq)"
//                . "VALUES ('$ProductID','$ProductCodeUUID','$ProductName','$ProductDesc','$RawCost','$SalePrice','$FK_IVACode','$PublicPrice','$Active','$sortSeq')");
            $res = $con->insertar(" INSERT INTO viewproducts (ProductID,ProductCodeUUID,ProductName,Active)"
                . "VALUES ('$ProductID','$ProductCodeUUID','$ProductName','$Active')");
        } catch (Exception $ex) {
            echo 'Fallo Insert'.$ex->getMessage();    
        }
      
    }
    
    public function actualizarProducto(){
        
        $ProductID          = NULL;
        $ProductCodeUUID    = $this->getProductCodeUUID();
        $ProductName        = $this->getProductName();
        $sortSeq            = $this->getSortSeq();
        $ProductDesc        = $this->getProductDesc();
        $RawCost            = $this->getRawCost();
        $SalePrice          = $this->getSalePrice();
        $FK_IVACode         = $this->getFK_IVACode();
        $PublicPrice        = $this->getPublicPrice();
        $Active             = true;
        
        $con = new conexion();
        
        try{
            
            $sortSeq = $sortSeq +1;
//            Desactivo el anterior registro
            $con->insertar("UPDATE ufproduct set active = false WHERE ProductCodeUUID = '$ProductCodeUUID' and active = true ")
            or die (mysql_error());
            
//            Creo un nuevo registro con el mismo UUID
            $con->insertar(" INSERT INTO ufproduct (ProductID,ProductCodeUUID,ProductName,ProductDesc,RawCost,SalePrice,FK_IVACode,PublicPrice,Active,sortSeq)"
                . "VALUES ('$ProductID','$ProductCodeUUID','$ProductName','$ProductDesc','$RawCost','$SalePrice','$FK_IVACode','$PublicPrice','$Active','$sortSeq')")
            or die (mysql_error());
            
        } catch (Exception $ex) {
            echo 'Fallo Insert'.$ex->getMessage();    
        }
    }
    
    // funcion para remover el producto.
    public function remove(){
        
        $ProductCodeUUID    = $this->getProductCodeUUID();
        $con = new conexion();
        $con->insertar("UPDATE ufproduct set active = false WHERE ProductCodeUUID = '$ProductCodeUUID' and active = true ");
        
    }
    
    public function getError(){
        return $this->getError();
    }
    
    public function getProductID() {
        return $this->ProductID;
    }

    public function getProductCodeUUID() {
        return $this->ProductCodeUUID;
    }

    public function getProductName() {
        return $this->ProductName;
    }

    public function getProductDesc() {
        return $this->ProductDesc;
    }

    public function getRawCost() {
        return $this->RawCost;
    }

    public function getSalePrice() {
        return $this->SalePrice;
    }

    public function getFK_IVACode() {
        return $this->FK_IVACode;
    }

    public function getPublicPrice() {
        return $this->PublicPrice;
    }

    public function getActive() {
        return $this->Active;
    }
    
    public function getSortSeq() {
        return $this->sortSeq;
    }

    public function setSortSeq($sortSeq) {
        $this->sortSeq = $sortSeq;
    }

    public function setProductID($ProductID) {
        $this->ProductID = $ProductID;
    }

    public function setProductCodeUUID($ProductCodeUUID) {
        $this->ProductCodeUUID = $ProductCodeUUID;
    }

    public function setProductName($ProductName) {
        $this->ProductName = $ProductName;
    }

    public function setProductDesc($ProductDesc) {
        $this->ProductDesc = $ProductDesc;
    }

    public function setRawCost($RawCost) {
        $this->RawCost = $RawCost;
    }

    public function setSalePrice($SalePrice) {
        $this->SalePrice = $SalePrice;
    }

    public function setFK_IVACode($FK_IVACode) {
        $this->FK_IVACode = $FK_IVACode;
    }

    public function setPublicPrice($PublicPrice) {
        $this->PublicPrice = $PublicPrice;
    }

    public function setActive($Active) {
        $this->Active = $Active;
    }

}
