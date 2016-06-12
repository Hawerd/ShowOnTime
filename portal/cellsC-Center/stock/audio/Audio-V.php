<!DOCTYPE html>
<!--
    Name:   Audio-V.php
    Autor:  Hawerd Gonzalez
    Date:   24-May-2016
    Desc:   Modulos principal de Audio

    Autor:  Luis F Castaño
    Date:   12-Jun-2016
    Desc:   Se realizan Eventos para el menu, formulario y la grilla.
            Se realizan los callback del menu y la grilla.
   
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
    audioInit();
});//end function dhtmlxEvent

//Function main dhtmlxEvent
function audioInit(){

/* INICIALITATION  */          
    
    //Static XML 
    audioMenuXML = "Audio-Menu.xml";
    audioGridXML = "Audio-Grid.xml";
    audioFormXML = "Audio-Form.xml";
    
    //CM XML 
    audioDataXML = "AudioDataTest-Grid.xml";
    
    //Routes Img 
    menuImg      = "../../../../codebase/skyblue/imgs";
    
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
    audioLayout = new dhtmlXLayoutObject("audioLayoutDiv",pattern);
    
    //container Grid
    audioGridContainer  = audioLayout.cells(gridCell);
    audioGridContainer.hideHeader();
    
    //container Form
    audioFormContainer  = audioLayout.cells(formCell);
    
    //Menu
    audioMenu   =  audioGridContainer.attachMenu();
    audioMenu.setSkin("dhx_skyblue");
    
    //Grid
    audioGrid   = audioGridContainer.attachGrid(); 
    audioGrid.init();
    
    //Form
    audioForm = audioFormContainer.attachForm();
   
    // Bind to Grid 	
    audioForm.bind(audioGrid);
    
/* END INSTANTIATION */       
    
/* EVENTS */

     /* Evento onClick del Menu */
    audioMenu.attachEvent("onClick", function(id){
        switch (id) {
            case "addElement":
                audioForm.clear();
                audioForm.unlock();
                audioForm.setItemFocus("ElementName");
                break;
            case "editElement":
                audioForm.unlock();
                break;
        }//fin del switch
    });//fin del evento onClick

    /* Evento onXLS de la Grilla */
    audioGrid.attachEvent("onXLS", function(grid){
        audioGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    audioGrid.attachEvent("onXLE", function(grid){
        audioGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento onRowSelect de la Grilla */
    audioGrid.attachEvent("onRowSelect", function(id){
        audioFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onButtonClik del formulario de Eventos */
    audioForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                break;
            case "clear":
                audioForm.restoreBackup(audioFormBackup);
                break;
            case "cancel":
                audioForm.restoreBackup(audioFormBackup);
                audioForm.lock();
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick

/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    audioMenu.loadStruct(audioMenuXML, audioMenuCallback);
    
    //load struct grid
    audioGrid.load(audioGridXML, audioGridCallback);
    
    //load struct form
    audioForm.loadStruct(audioFormXML);
    
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function audioMenuCallback(){
        var headerForm          = audioMenu.getUserData("sp3","headerForm");
        var headerFormCollapse  = audioMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        audioFormContainer.setText(headerForm);
        audioLayout.cells(formCell).setCollapsedText(headerFormCollapse);
    }//fin de la funcion audioMenuCallback

    /* funcion callback de la estructura de la grilla 
     * que se encarga de cargar los datos */
    function audioGridCallback(){
    /* datos cargados por primera vez(cuando se inicia el modulo)
       caso contrario se limpiara la estructura y se cargara datos */
       if ( firstTime ){
           audioGrid.load(audioDataXML,audioGridDataCallback);
       } else {
           audioGrid.clearAndLoad(audioDataXML,audioGridDataCallback);
       }
    }//fin de la funcion audioGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function audioGridDataCallback(){
        //Obtiene el id de la primera fila de la grilla
        selectedRow = audioGrid.getRowId(0);
       
        //Selecciona la Fila (Dispara Evento onRowSelect). 
        audioGrid.selectRowById(selectedRow,false,true,true);
	audioGrid.showRow( selectedRow );	
    }//fin de la funcion eventsGridDataCallback
    
     /* funcion que configura los inputs de la forma */
    function audioFormSetup(){
        //Se bloquea la forma
        audioForm.lock();
        
        //backups de la fila seleccionada
        audioFormBackup          = audioForm.saveBackup();
        audioFormOriginalValues  = audioForm.getFormData();
   
        //Se configuran botones del Menu
        audioMenuButtonSetup();
    }//fin de la funcion audioFormSetup
    
    /* funcion que configura los botones del menu */
    function audioMenuButtonSetup(){
        //deshabilitado todos los botones
        audioMenu.setItemEnabled("addElement");
        audioMenu.setItemEnabled("editElement");
        audioMenu.setItemEnabled("removeElement"); 
    }//fin de la funcion audioMenuButtonSetup
    
/* END FUNCTIONS */

};//fin de la funcion audioInit
</script>
</head>
<body>
<div id="audioLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>

