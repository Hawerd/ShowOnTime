<!DOCTYPE html>
<!--
    Name:   Portal-V.php
    Autor:  Hawerd Gonzalez
    Date:   22-May-2016
    Desc:   Modulo principal del Portal.

    Autor:  Luis castaño
    Date:   22-May-2016  
    Desc:   Se ajusta  el codigo del portal, se agrega la seccion 
            INICIALIZACION.
    
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
  
    //Routes
    icons           = "../resource/icons/"; //rutas de iconos
    
    //Cells
    dashboardCell   = "a";
    tabbarCell      = "b";
    sidebarCell     = "a";
    
    //setup size
    siderbarWidth   = 200;
    
/* END INICIALITATION */   
    
/* INSTANTIATION  */

    portalLayout = new dhtmlXLayoutObject(portal,"2U");

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

    portalSidebar.attachEvent("onSelect",function(id){
        centerTabs.tabs(id).setActive();
    });
    
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
<div id="portal" style="position: fixed; height: 95%; width: 90%;"></div>
</body>
</html>