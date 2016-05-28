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
    
    Autor:  Luis castaño
    Date:   23-May-2016  
    Desc:   Se ajusta los tamaños del div principal portalLayoutDiv.

    Autor:  Luis castaño
    Date:   27-May-2016  
    Desc:   Se ajusta el evento onclik del objeto centerTabs para
            determinar si es necesario o No cargar el tab clickeado.
    
-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
    portalInit();
});//end function dhtmlxEvent

//Function Portal Init
function portalInit(){
    
/* INICIALITATION  */    
   
    //Static XML
    tabbarXML       = "Tabbar.xml";       //tabs principales
    sidebarXML      = "Sidebar.xml";      //Sidebar
    
    //Array of tabs main 
    portalTabs      = [{tab:"stock",route:"../portal/cellsC-Center/stock/Stock-V.php"},
                       {tab:"clients",route:"../portal/cellsC-Center/clients/Clients-V.php"},
                       {tab:"events",route:"../portal/cellsC-Center/events/Events-V.php"}];
                   
    //icons route
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
        var tabActive = centerTabs.getActiveTab(); 
        if(tabActive != id){
            for(var i=0;i<portalTabs.length;i++){
                if( portalTabs[i].tab == id ){
                    centerTabs.tabs(id).attachURL(portalTabs[i].route);
                    break;
                }
            }//fin del ciclo
        }//fin de la condicion tabActive
    });//fin del evento onTabClick

/* END EVENTS */     

/* LOADS  */

    portalSidebar.loadStruct(sidebarXML);
    centerTabs.loadStruct(tabbarXML);
    
/* END LOADS */

/* FUNCTIONS */
/* END FUNCTIONS */

};//end function Init
</script>
</head>
<body>
<div id="portalLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>