<!DOCTYPE html>
<!--
    Name:   Lights-V.php
    Autor:  Luis F castaño
    Date:   13-Jun-2016
    Desc:   Vista JS del Modulo Lights.

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
    lightsInit();
});//end function dhtmlxEvent

//Function main dhtmlxEvent
function lightsInit(){

/* INICIALITATION  */          
    
    //Static XML 
    lightsMenuXML = "lights-Menu.xml";
    lightsGridXML = "lights-Grid.xml";
    lightsFormXML = "lights-Form.xml";
    
    //CM XML 
    lightsDataXML = "lightsDataTest-Grid.xml";
    
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
    lightsLayout = new dhtmlXLayoutObject("lightsLayoutDiv",pattern);
    
    //container Grid
    lightsGridContainer  = lightsLayout.cells(gridCell);
    lightsGridContainer.hideHeader();
    
    //container Form
    lightsFormContainer  = lightsLayout.cells(formCell);
    
    //Menu
    lightsMenu   =  lightsGridContainer.attachMenu();
    lightsMenu.setSkin("dhx_skyblue");
    
    //Grid
    lightsGrid   = lightsGridContainer.attachGrid(); 
    lightsGrid.init();
    
    //Form
    lightsForm = lightsFormContainer.attachForm();
   
    // Bind to Grid 	
    lightsForm.bind(lightsGrid);
    
/* END INSTANTIATION */       
    
/* EVENTS */

    /* Evento onClick del Menu */
    lightsMenu.attachEvent("onClick", function(id){
        switch (id) {
            case "addElement":
                lightsForm.clear();
                lightsForm.unlock();
                lightsForm.setItemFocus("ElementName");
                break;
            case "editElement":
                lightsForm.unlock();
                break;
        }//fin del switch
    });//fin del evento onClick

    /* Evento onXLS de la Grilla */
    lightsGrid.attachEvent("onXLS", function(grid){
        lightsGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    lightsGrid.attachEvent("onXLE", function(grid){
        lightsGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento onRowSelect de la Grilla */
    lightsGrid.attachEvent("onRowSelect", function(id){
        lightsFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onButtonClik del formulario de Eventos */
    lightsForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                break;
            case "clear":
                lightsForm.restoreBackup(lightsFormBackup);
                break;
            case "cancel":
                lightsForm.restoreBackup(lightsFormBackup);
                lightsForm.lock();
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick

/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    lightsMenu.loadStruct(lightsMenuXML, lightsMenuCallback);
    
    //load struct grid
    lightsGrid.load(lightsGridXML, lightsGridCallback);
    
    //load struct form
    lightsForm.loadStruct(lightsFormXML);
    
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function lightsMenuCallback(){
        var headerForm          = lightsMenu.getUserData("sp3","headerForm");
        var headerFormCollapse  = lightsMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        lightsFormContainer.setText(headerForm);
        lightsLayout.cells(formCell).setCollapsedText(headerFormCollapse);
    }//fin de la funcion lightsMenuCallback

    /* funcion callback de la estructura de la grilla 
     * que se encarga de cargar los datos */
    function lightsGridCallback(){
    /* datos cargados por primera vez(cuando se inicia el modulo)
       caso contrario se limpiara la estructura y se cargara datos */
       if ( firstTime ){
           lightsGrid.load(lightsDataXML,lightsGridDataCallback);
       } else {
           lightsGrid.clearAndLoad(lightsDataXML,lightsGridDataCallback);
       }
    }//fin de la funcion lightsGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function lightsGridDataCallback(){
        //Obtiene el id de la primera fila de la grilla
        selectedRow = lightsGrid.getRowId(0);
       
        //Selecciona la Fila (Dispara Evento onRowSelect). 
        lightsGrid.selectRowById(selectedRow,false,true,true);
	lightsGrid.showRow( selectedRow );	
    }//fin de la funcion eventsGridDataCallback
    
     /* funcion que configura los inputs de la forma */
    function lightsFormSetup(){
        //Se bloquea la forma
        lightsForm.lock();
        
        //backups de la fila seleccionada
        lightsFormBackup          = lightsForm.saveBackup();
        lightsFormOriginalValues  = lightsForm.getFormData();
   
        //Se configuran botones del Menu
        lightsMenuButtonSetup();
    }//fin de la funcion lightsFormSetup
    
    /* funcion que configura los botones del menu */
    function lightsMenuButtonSetup(){
        //deshabilitado todos los botones
        lightsMenu.setItemEnabled("addElement");
        lightsMenu.setItemEnabled("editElement");
        lightsMenu.setItemEnabled("removeElement"); 
    }//fin de la funcion lightsMenuButtonSetup
    
/* END FUNCTIONS */

};//fin de la funcion lightsInit
</script>
</head>
<body>
<div id="lightsLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>

