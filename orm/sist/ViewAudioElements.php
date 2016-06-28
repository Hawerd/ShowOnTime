<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewSistElements
 *
 * @author Hawerd
 *  Name:   viewSistElements.php
    Autor:  Hawerd GOnzalez
    Date:   27-Jun-2016
    Desc:   se crea ORM para la vista viewSistElements
 */

require_once '/../MethodsORM.php';
class viewAudioElements extends methods {
    //put your code here
    protected  $entityObj;
    protected  $nameTable;
    
    public function __construct(){
        
        $this->entityObj = array();
        $this->nameTable = "viewAudioElements";
        
        //Definir aqui los campos de la vista.
        $this->entityObj['elementUUID']         = null;
        $this->entityObj['elementCode']         = null;
        $this->entityObj['elementBrand']        = null;
        $this->entityObj['elementReferences']   = null;
        $this->entityObj['elementDesc']         = null;
        $this->entityObj['FK_elementTypeCode']  = null;
        
        parent::__construct();
    }
}
