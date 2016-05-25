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
    
    /* CM XML */
    eventsGridLoad  = "EventsData-Grid.xml";
        
    /* Cells */
    gridCell        = "a";
    formCell        = "b";
    
    /* Routes Img */
    gridImg         = "../../../codebase/imgs/";
    menuImg         = "../../../codebase/skyblue/imgs";
    
    /* flags Generales */ 
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
    eventsFormContainer.hideHeader();
    
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
  
/* END INSTANTIATION */       

/* EVENTS */

    /* Evento onXLS de la Grilla */
    eventsGrid.attachEvent("onXLS", function(grid){
        eventsGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    eventsGrid.attachEvent("onXLE", function(grid){
        eventsGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    eventsMenu.loadStruct(eventsMenuXML);
    
    //load struct grid
    eventsGrid.load(eventsGridXML,eventsGridCallback);
   
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura que encargara 
     * de cargar los datos de la de la grilla */
    function eventsGridCallback(){
    /*  datos cargados por primera vez(cuando se inicia el modulo)
        caso contrario se limpiara la estructura y se cargara datos */
        if( firstTime ){
            eventsGrid.load(eventsGridLoad);
        }else{
            eventsGrid.clearAndLoad(eventsGridLoad);
        }
    }//fin de la funcion eventsGridCallback

/* END FUNCTIONS */
    
}//fin de la funcion eventsInit
</script>
</head>
<body>
<div id="eventsLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

