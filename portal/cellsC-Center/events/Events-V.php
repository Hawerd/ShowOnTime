<!DOCTYPE html>
<!--
    Name:   Events-V.php
    Autor:  Luis F Castaño
    Date:   22-May-2016
    Desc:   Modulos principal de Eventos.

    Autor:  Luis castaño
    Date:   22-May-2016  
    Desc:   Se realiza logica del modulo.

    Autor:  Luis castaño
    Date:   23-May-2016  
    Desc:   Se realiza la logica de la funcion callback eventsGridCallback.
            Se Realiza la carga de datos "Quemados" con xml.
            Se agregan Eventos onXLE y onXLS.

    Autor:  Luis castaño
    Date:   24-May-2016  
    Desc:   Se realizan los callback de los componentes existentes
            Se crean las funciones de apoyo como form y menu setup
            Se agregan eventos como el onClik y onRowSelect.

    Autor:  Luis castaño
    Date:   12-Jun-2016  
    Desc:   Se ajusta el id del setItemFocus en el evento onClik
            del menu.

    Autor:  Luis castaño
    Date:   14-Jun-2016  
    Desc:   Se ajusta el style.textAlign del input dateOfStart 
            en la funcion eventsFormCallback 
   
-->
<html>
<head>
<title>Eventos</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
    eventsInit();
});//end function dhtmlxEvent

function eventsInit(){
   
/* INICIALITATION  */  
    
    /* Static XML */
    eventsGridXML   = "Events-Grid.xml";
    eventsMenuXML   = "Events-Menu.xml";
    eventsFormXML   = "Events-Form.xml";
    
    /* CM XML */
    eventsGridLoad  = "EventsData-Grid.xml";
    
    /* Cells */
    gridCell        = "a";
    formCell        = "b";
    
    /* Routes Img */
    gridImg         = "../../../codebase/imgs/";
    menuImg         = "../../../codebase/skyblue/imgs";
    
    /* Variables Generales */
    selectedRow     = "";   // contiene el id a seleccionar en la grilla
    
    /* Flags Generales */ 
    firstTime       = true; 	
    
/* END INICIALITATION */   

/* INSTANTIATION  */
    
    //Layout Main 
    pattern         = "2E";
    eventsLayout    = new dhtmlXLayoutObject("eventsLayoutDiv",pattern);
    
    //Grid Container 
    eventsGridContainer    = eventsLayout.cells(gridCell);
    eventsGridContainer.hideHeader();
    
    //Form Container
    eventsFormContainer    = eventsLayout.cells(formCell);
    
    //Menu
    eventsMenu  = eventsGridContainer.attachMenu();
    eventsMenu.setIconsPath(menuImg);
    eventsMenu.setSkin("dhx_skyblue");
    
    //Grid
    eventsGrid  = eventsGridContainer.attachGrid();
    eventsGrid.setImagePath(gridImg);
    eventsGrid.init();
  
    //Form
    eventsForm = eventsFormContainer.attachForm();
    
    // Bind to Grid 	
    eventsForm.bind(eventsGrid);
  
/* END INSTANTIATION */       

/* EVENTS */
    
    /* Evento onClick del Menu */
    eventsMenu.attachEvent("onClick", function(id){
        switch (id) {
            case "addEvent":
                eventsForm.clear();
                eventsForm.unlock();
                eventsForm.setItemFocus("NameOfClient");
                break;
            case "editEvent":
                eventsForm.unlock();
                break;
        }//fin del switch
    });//fin del evento onClick

    /* Evento onXLS de la Grilla */
    eventsGrid.attachEvent("onXLS", function(grid){
        eventsGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    eventsGrid.attachEvent("onXLE", function(grid){
        eventsGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento onRowSelect de la Grilla */
    eventsGrid.attachEvent("onRowSelect", function(id){
        eventsFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onButtonClik del formulario de Eventos */
    eventsForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                break;
            case "clear":
                eventsForm.restoreBackup(eventsFormBackup);
                break;
            case "cancel":
                eventsForm.restoreBackup(eventsFormBackup);
                eventsForm.lock();
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick
    
/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    eventsMenu.loadStruct(eventsMenuXML,eventsMenuCallback);
    
    //load struct grid
    eventsGrid.load(eventsGridXML,eventsGridCallback);
    
    //load struct form
    eventsForm.loadStruct(eventsFormXML,eventsFormCallback);
   
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function eventsMenuCallback(){
        var headerForm          = eventsMenu.getUserData("sp3","headerForm");
        var headerFormCollapse  = eventsMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        eventsFormContainer.setText(headerForm);
        eventsLayout.cells(formCell).setCollapsedText(headerFormCollapse);
    }//fin de la funcion eventsMenuCallback
    
    /* funcion callback de la estructura de la grilla 
     * que se encarga de cargar los datos  */
    function eventsGridCallback(){
    /*  datos cargados por primera vez(cuando se inicia el modulo)
        caso contrario se limpiara la estructura y se cargara datos */
        if( firstTime ){
            eventsGrid.load(eventsGridLoad,eventsGridDataCallback);
        }else{
            eventsGrid.clearAndLoad(eventsGridLoad,eventsGridDataCallback);
        }
    }//fin de la funcion eventsGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function eventsGridDataCallback(){
        //Obtiene el id de la primera fila de la grilla
        selectedRow = eventsGrid.getRowId(0);
       
        //Selecciona la Fila (Dispara Evento onRowSelect). 
        eventsGrid.selectRowById(selectedRow,false,true,true);
	eventsGrid.showRow( selectedRow );	
    }//fin de la funcion eventsGridDataCallback
    
    /* funcion callback de la estructura del formulario */
    function eventsFormCallback(){
        eventsForm.getInput("DateOfMounting").style.textAlign = "right";
        eventsForm.getInput("DateOfStart").style.textAlign = "right";
        eventsForm.getInput("DateFinal").style.textAlign = "right";
    }//fin de la funcion eventsFormCallback
    
    /* funcion que configura los inputs de la forma */
    function eventsFormSetup(){
        //Se bloquea la forma
        eventsForm.lock();
        
        //backups de la fila seleccionada
        eventsFormBackup          = eventsForm.saveBackup();
        eventsFormOriginalValues  = eventsForm.getFormData();
   
        //Se configuran botones del Menu
        eventsMenuButtonSetup();
    }//fin de la funcion eventsFormSetup
    
    /* funcion que configura los botones del menu */
    function eventsMenuButtonSetup(){
        //deshabilitado todos los botones
        eventsMenu.setItemEnabled("addEvent");
        eventsMenu.setItemEnabled("editEvent");
        eventsMenu.setItemEnabled("removeEvent"); 
    }//fin de la funcion eventsMenuButtonSetup

/* END FUNCTIONS */
    
}//fin de la funcion eventsInit
</script>
</head>
<body>
<div id="eventsLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>

