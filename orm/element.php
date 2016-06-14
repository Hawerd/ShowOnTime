<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of element
 *
 * @author Hawerd
 */

require_once '/../conexion/conexion.php';
class element {
    private $elementID;
    private $elementUUID;
    private $elementCode;
    private $elementName;
    private $elementBrand;
    private $elementReferences;
    private $elementQuantity;
    private $FK_ProducTypeCode;
    private $CreatedDT;
    private $CreatedBy;
    private $UpdatedDT;
    private $UpdatedBy;
    private $Active;
    private $ActiveDT;
    private $ActiveBy;
    
    
    public function entityLoad(){
        header('Content-Type: application/xml');
        $res = $con->insertar("select * from codClientType where active = true order by elementName");
        echo $res;
    }
    
    function getElementID() {
        return $this->elementID;
    }

    function getElementUUID() {
        return $this->elementUUID;
    }

    function getElementCode() {
        return $this->elementCode;
    }

    function getElementName() {
        return $this->elementName;
    }

    function getElementBrand() {
        return $this->elementBrand;
    }

    function getElementReferences() {
        return $this->elementReferences;
    }

    function getElementQuantity() {
        return $this->elementQuantity;
    }

    function getFK_ProducTypeCode() {
        return $this->FK_ProducTypeCode;
    }

    function getCreatedDT() {
        return $this->CreatedDT;
    }

    function getCreatedBy() {
        return $this->CreatedBy;
    }

    function getUpdatedDT() {
        return $this->UpdatedDT;
    }

    function getUpdatedBy() {
        return $this->UpdatedBy;
    }

    function getActive() {
        return $this->Active;
    }

    function getActiveDT() {
        return $this->ActiveDT;
    }

    function getActiveBy() {
        return $this->ActiveBy;
    }

    function setElementID($elementID) {
        $this->elementID = $elementID;
    }

    function setElementUUID($elementUUID) {
        $this->elementUUID = $elementUUID;
    }

    function setElementCode($elementCode) {
        $this->elementCode = $elementCode;
    }

    function setElementName($elementName) {
        $this->elementName = $elementName;
    }

    function setElementBrand($elementBrand) {
        $this->elementBrand = $elementBrand;
    }

    function setElementReferences($elementReferences) {
        $this->elementReferences = $elementReferences;
    }

    function setElementQuantity($elementQuantity) {
        $this->elementQuantity = $elementQuantity;
    }

    function setFK_ProducTypeCode($FK_ProducTypeCode) {
        $this->FK_ProducTypeCode = $FK_ProducTypeCode;
    }

    function setCreatedDT($CreatedDT) {
        $this->CreatedDT = $CreatedDT;
    }

    function setCreatedBy($CreatedBy) {
        $this->CreatedBy = $CreatedBy;
    }

    function setUpdatedDT($UpdatedDT) {
        $this->UpdatedDT = $UpdatedDT;
    }

    function setUpdatedBy($UpdatedBy) {
        $this->UpdatedBy = $UpdatedBy;
    }

    function setActive($Active) {
        $this->Active = $Active;
    }

    function setActiveDT($ActiveDT) {
        $this->ActiveDT = $ActiveDT;
    }

    function setActiveBy($ActiveBy) {
        $this->ActiveBy = $ActiveBy;
    }


}
