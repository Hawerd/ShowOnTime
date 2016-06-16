<!DOCTYPE html>
<!--
    Name:   Clients-V.php
    Autor:  Victor Gutierrez
    Date:   13-Jun-2016
    Desc:   Modulo encargado de la gestión de clientes

***************************************************************************************

    Autor:  Victor Gutierrez
    Date:   15-Jun-2016
    Desc:   Se Implementaron el tab bar y la grid del modulo de clientes.
    
    Autor:  Victor Gutierrez
    Date:   16-Jun-2016
    Desc:   Se Implementó el formulario del modulo de clientes.

-->

<html>
<head>
<title>Clientes</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">
    
//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
    clientsInit();
});//end function dhtmlxEvent

function clientsInit() {
    
/* INITIALIZATION */

    /* Static XML */
    clientsGridXML          = "Clients-Grid.xml";
    clientsMenuXML          = "Clients-Menu.xml";
    clientsFormXML          = "Clients-Form.xml";
    
    /* CM XML */
    clientsGridLoad          = "ClientsData-Grid.xml";
    
    /* Cells */
    gridCell                = "a";
    formCell                = "b";
    
    /* Routes Img */
    gridImg                 = "../../../codebase/imgs/";
    menuImg                 = "../../../codebase/skyblue/imgs";
    
    /* Variables Generales */
    selectedRow             = "";   // contiene el id a seleccionar en la grilla
    
    /* Flags Generales */ 
    firstTime               = true;
    
/* END INITIALIZATION */

/* INSTANTIATION  */

    //Layout Main 
    pattern                 = "2E";
    clientsLayout           = new dhtmlXLayoutObject("clientsLayoutDiv", pattern);
    
    //Grid Container 
    clientsGridContainer    = clientsLayout.cells(gridCell);
    clientsGridContainer.hideHeader();
    
    //Form Container
    clientsFormContainer    = clientsLayout.cells(formCell);
    
    //Menu
    clientsMenu             = clientsGridContainer.attachMenu();
    clientsMenu.setIconsPath(menuImg);
    clientsMenu.setSkin("dhx_skyblue");
    
    //Grid
    clientsGrid             = clientsGridContainer.attachGrid();
    clientsGrid.setImagePath(gridImg);
    clientsGrid.init();
    
    //Form
    clientsForm             = clientsFormContainer.attachForm();
    
    // Bind to Grid 	
    clientsForm.bind(clientsGrid);

/* END INSTANTIATION */ 

/* EVENTS */

    /* Evento onClick del Menu */
    clientsMenu.attachEvent("onClick", function(id) {
        switch (id) {
            case "addEvent":
                clientsForm.clear();
                clientsForm.unlock();
                clientsForm.setItemFocus("NameClient");
                break;
            case "editEvent":
                clientsForm.unlock();
                break;
        }//fin del switch
    });//fin del evento onClick
    
    /* Evento onXLS de la Grilla */
    clientsGrid.attachEvent("onXLS", function(grid){
        clientsGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    clientsGrid.attachEvent("onXLE", function(grid){
        clientsGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento onRowSelect de la Grilla */
    clientsGrid.attachEvent("onRowSelect", function(id){
        clientsFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onButtonClik del formulario de Eventos */
    clientsForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                break;
            case "clear":
                clientsForm.restoreBackup(clientsFormBackup);
                break;
            case "cancel":
                clientsForm.restoreBackup(clientsFormBackup);
                clientsForm.lock();
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick
    
/* END EVENTS */     

/* LOADS  */

    //load struct menu
    clientsMenu.loadStruct(clientsMenuXML, clientsMenuCallback);
    
    //load struct grid
    clientsGrid.load(clientsGridXML, clientsGridCallback);
    
    //load struct form
    clientsForm.loadStruct(clientsFormXML, clientsFormCallback);
    
/* END LOADS */

/* FUNCTIONS */

    /* Función callback de la estructura del menu encargada de obtener el valor de los userdata 
     * correspondientes a los "headers" */
    function clientsMenuCallback(){
        var headerForm          = clientsMenu.getUserData("sp3","headerForm");
        var headerFormCollapse  = clientsMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        clientsFormContainer.setText(headerForm);
        clientsLayout.cells(formCell).setCollapsedText(headerFormCollapse);
    }//fin de la funcion clientsMenuCallback
    
    /* Función callback de la estructura de la grilla que se encarga de cargar los datos  */
    function clientsGridCallback(){
    /*  datos cargados por primera vez(cuando se inicia el modulo)
        caso contrario se limpiara la estructura y se cargara datos */
        if( firstTime ){
            clientsGrid.load(clientsGridLoad,clientsGridDataCallback);
        }else{
            clientsGrid.clearAndLoad(clientsGridLoad,clientsGridDataCallback);
        }
    }//fin de la funcion clientsGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function clientsGridDataCallback(){
        //Obtiene el id de la primera fila de la grilla
        selectedRow = clientsGrid.getRowId(0);
       
        //Selecciona la Fila (Dispara Evento onRowSelect). 
        clientsGrid.selectRowById(selectedRow,false,true,true);
	clientsGrid.showRow( selectedRow );	
    }//fin de la funcion clientsGridDataCallback
    
    /* funcion callback de la estructura del formulario */
    function clientsFormCallback(){
        //clientsForm.getInput("DateOfMounting").style.textAlign = "right";
        //clientsForm.getInput("DateOfStart").style.textAlign = "right";
        //clientsForm.getInput("DateFinal").style.textAlign = "right";
    }//fin de la funcion clientsFormCallback
    
    /* funcion que configura los inputs de la forma */
    function clientsFormSetup(){
        //Se bloquea el formulario
        clientsForm.lock();
        
        //backups de la fila seleccionada
        clientsFormBackup          = clientsForm.saveBackup();
        clientsFormOriginalValues  = clientsForm.getFormData();
   
        //Se configuran botones del Menu
        clientsMenuButtonSetup();
    }//fin de la funcion clientsFormSetup
    
    /* funcion que configura los botones del menu */
    function clientsMenuButtonSetup(){
        //deshabilitado todos los botones
        clientsMenu.setItemEnabled("addClient");
        clientsMenu.setItemEnabled("editClient");
        clientsMenu.setItemEnabled("removeClient"); 
    }//fin de la funcion clientsMenuButtonSetup
    
}
    
</script>
</head>
<body>
<div id="clientsLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>