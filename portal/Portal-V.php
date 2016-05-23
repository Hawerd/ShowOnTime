<!DOCTYPE html>
<!--
    Name:   Portal-V.php
    Autor:  Hawerd Gonzalez
    Date:   22-May-2016
    Desc:   Modulo principal del Portal.

    Autor:  Luis castaño
    Date:   22-May-2016  
    Desc:   * Se ajusta  el codigo del portal, se agrega la seccion 
              INICIALIZACION.
            * Se agrega evento onTabClick.
            * Se agrega switch en el Evento onClickTab para cargar o recargar cada tab
              de manera independiente.
    
-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function main dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    
/* INICIALITATION  */    
   
    //Static XML
    tabbarXML       = "Tabbar.xml";       //tabs principales
    sidebarXML      = "Sidebar.xml";      //Sidebar
    
    //Tabs-V load php
    tabStockLoad    = "../portal/cellsC-Center/Events/Stock-V.php";
    tabClientsLoad  = "../portal/cellsC-Center/Events/Clients-V.php";
    tabEventsLoad   = "../portal/cellsC-Center/Events/Events-V.php";
    
    //General Routes
    icons           = "../resource/icons/"; //rutas de iconos
    
    //Cells
    dashboardCell   = "a";
    tabbarCell      = "b";
    sidebarCell     = "a";
    
    //setup size
    siderbarWidth   = 200;
    
/* END INICIALITATION */   
    
/* INSTANTIATION  */
    
    pattern      = "2U";
    portalLayout = new dhtmlXLayoutObject("portalLayoutDiv",pattern);

    dashBoardLayoutContainer = portalLayout.cells(dashboardCell);
    dashBoardLayoutContainer.setWidth(siderbarWidth);

    dashBoardLayout = dashBoardLayoutContainer.attachLayout('2E');

    /*  Celda izquierda en la que esta el arbol */
    sidebarContainer = dashBoardLayout.cells(sidebarCell);
    sidebarContainer.setText("Panel de Control");
    sidebarContainer.setWidth(siderbarWidth);

    /*  Agrego el sidebar a la celda  */
    portalSidebar = sidebarContainer.attachSidebar({
        icons_path: icons
    });

    /*  Celda central en la que estaran las pestaÃ±as    */  
    centerContainer = portalLayout.cells(tabbarCell);
    centerContainer.hideHeader();

    centerTabs  = centerContainer.attachTabbar();

/* END INSTANTIATION */       

/* EVENTS */
    
    /* Evento onSelect del portal Sidebar */
    portalSidebar.attachEvent("onSelect",function(id){
        centerTabs.tabs(id).setActive();
    });//fin del evento onSelect
   
   /* Evento onTabClick del CenterTab*/
    centerTabs.attachEvent("onTabClick", function(id) {
       switch(id){
           case "stock":
               centerTabs.tabs(id).attachURL(tabStockLoad);
               break;
           case "clients":
               centerTabs.tabs(id).attachURL(tabClientsLoad);
               break;
           case "events":
               centerTabs.tabs(id).attachURL(tabEventsLoad);
               break;    
       }//fin del switch 
    });//fin del evento onTabClick

/* END EVENTS */     

/* LOADS  */

    portalSidebar.loadStruct(sidebarXML);
    centerTabs.loadStruct(tabbarXML);
    
/* END LOADS */

/* FUNCTIONS */
/* END FUNCTIONS */

});//end function main dhtmlxEvent

</script>
</head>
<body>
<div id="portalLayoutDiv" style="position: fixed; height: 95%; width: 90%;"></div>
</body>
</html>