<!DOCTYPE html>
<!--
    Name:   Structure-V.php
    Autor:  Luis F castaño
    Date:   14-Jun-2016
    Desc:   Vista JS del Modulo Estructuras.

-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
    structureInit();
});//end function dhtmlxEvent

//Function main dhtmlxEvent
function structureInit(){

/* INICIALITATION  */          
    
    //Static XML 
    structureMenuXML = "structure-Menu.xml";
    structureGridXML = "structure-Grid.xml";
    structureFormXML = "structure-Form.xml";
    
    //CM XML 
    structureDataXML = "StructureDataTest-Grid.xml";
    
    //Routes Img 
    menuImg       = "../../../../codebase/skyblue/imgs";
    
    //Cells   
    gridCell     = "a";
    formCell     = "b"; 
    
    /* Variables Generales */
    selectedRow  = "";   // contiene el id a seleccionar en la grilla
    
    //flags   
    firstTime    = true;

/* END INICIALITATION */   

/* INSTANTIATION  */
    
    //main Layout
    pattern     = "2E";
    structureLayout = new dhtmlXLayoutObject("structureLayoutDiv",pattern);
    
    //container Grid
    structureGridContainer  = structureLayout.cells(gridCell);
    structureGridContainer.hideHeader();
    
    //container Form
    structureFormContainer  = structureLayout.cells(formCell);
    
    //Menu
    structureMenu   =  structureGridContainer.attachMenu();
    structureMenu.setSkin("dhx_skyblue");
    
    //Grid
    structureGrid   = structureGridContainer.attachGrid(); 
    structureGrid.init();
    
    //Form
    structureForm = structureFormContainer.attachForm();
   
    // Bind to Grid 	
    structureForm.bind(structureGrid);
    
/* END INSTANTIATION */       
    
/* EVENTS */

    /* Evento onClick del Menu */
    structureMenu.attachEvent("onClick", function(id){
        switch (id) {
            case "addElement":
                structureForm.clear();
                structureForm.unlock();
                structureForm.setItemFocus("ElementName");
                break;
            case "editElement":
                structureForm.unlock();
                break;
        }//fin del switch
    });//fin del evento onClick

    /* Evento onXLS de la Grilla */
    structureGrid.attachEvent("onXLS", function(grid){
        structureGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    structureGrid.attachEvent("onXLE", function(grid){
        structureGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento onRowSelect de la Grilla */
    structureGrid.attachEvent("onRowSelect", function(id){
        structureFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onButtonClik del formulario de Eventos */
    structureForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                break;
            case "clear":
                structureForm.restoreBackup(structureFormBackup);
                break;
            case "cancel":
                structureForm.restoreBackup(structureFormBackup);
                structureForm.lock();
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick

/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    structureMenu.loadStruct(structureMenuXML, structureMenuCallback);
    
    //load struct grid
    structureGrid.load(structureGridXML, structureGridCallback);
    
    //load struct form
    structureForm.loadStruct(structureFormXML);
    
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function structureMenuCallback(){
        var headerForm          = structureMenu.getUserData("sp3","headerForm");
        var headerFormCollapse  = structureMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        structureFormContainer.setText(headerForm);
        structureLayout.cells(formCell).setCollapsedText(headerFormCollapse);
    }//fin de la funcion structureMenuCallback

    /* funcion callback de la estructura de la grilla 
     * que se encarga de cargar los datos */
    function structureGridCallback(){
    /* datos cargados por primera vez(cuando se inicia el modulo)
       caso contrario se limpiara la estructura y se cargara datos */
       if ( firstTime ){
           structureGrid.load(structureDataXML,structureGridDataCallback);
       } else {
           structureGrid.clearAndLoad(structureDataXML,structureGridDataCallback);
       }
    }//fin de la funcion lightsGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function structureGridDataCallback(){
        //Obtiene el id de la primera fila de la grilla
        selectedRow = structureGrid.getRowId(0);
       
        //Selecciona la Fila (Dispara Evento onRowSelect). 
        structureGrid.selectRowById(selectedRow,false,true,true);
	structureGrid.showRow( selectedRow );	
    }//fin de la funcion eventsGridDataCallback
    
     /* funcion que configura los inputs de la forma */
    function structureFormSetup(){
        //Se bloquea la forma
        structureForm.lock();
        
        //backups de la fila seleccionada
        structureFormBackup          = structureForm.saveBackup();
        structureFormOriginalValues  = structureForm.getFormData();
   
        //Se configuran botones del Menu
        structureMenuButtonSetup();
    }//fin de la funcion structureFormSetup
    
    /* funcion que configura los botones del menu */
    function structureMenuButtonSetup(){
        //deshabilitado todos los botones
        structureMenu.setItemEnabled("addElement");
        structureMenu.setItemEnabled("editElement");
        structureMenu.setItemEnabled("removeElement"); 
    }//fin de la funcion structureMenuButtonSetup
    
/* END FUNCTIONS */

};//fin de la funcion structureInit
</script>
</head>
<body>
<div id="structureLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>

