<!DOCTYPE html>
<!--
  
    Name:   Portal-V.php
    Autor:  Hawerd Gonzalez
    Desc:   Archivo inicial que contiene los taps del programa
    
-->
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
    <script src="../codebase/dhtmlx.js" type="text/javascript"></script>
    <script type="text/javascript">

        /*  Variables de layout */
        
        // Celda del dashboard
        cellDashboard   = "a";
        tabCell         = "b";
        
        // Celdas del dashboard
        sidebarCell     = "a";
        

        dhtmlxEvent(window,"load",function(){

/*  INSTANTIATION   */
            portalLayout = new dhtmlXLayoutObject(portal,"2U");
            
            dashBoardLayoutContainer = portalLayout.cells(cellDashboard);
            dashBoardLayoutContainer.setWidth(200);
            
            dashBoardLayout = dashBoardLayoutContainer.attachLayout('2E');

            /*  Celda izquierda en la que esta el arbol */
            sidebarContainer = dashBoardLayout.cells(sidebarCell);
            sidebarContainer.setText("Gestion");
            sidebarContainer.setWidth(200);

            /*  Agrego el sidebar a la celda  */
            portalSidebar = sidebarContainer.attachSidebar({
                icons_path:"../resource/icons/"
            });

            /*  Celda central en la que estaran las pestañas    */  
            centerContainer = portalLayout.cells(tabCell);
            centerContainer.hideHeader();

            centerTabs  = centerContainer.attachTabbar();

/*  END INSTANTIATION   */       

/*  EVENTS  */
            portalSidebar.attachEvent("onSelect",function(id){
                centerTabs.tabs(id).setActive();
            });

/*  END EVENTS  */     

/*  LOADS   */
            portalSidebar.loadStruct("Sidebar.xml");
            centerTabs.loadStruct("Tabs.xml");

/*  END LOADS   */                
            });
    </script>
</head>
<body>
   <div id="portal"  style="position: fixed; height: 95%; width: 90%;"></div>
</body>
</html>