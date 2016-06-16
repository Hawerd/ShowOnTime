<!DOCTYPE html>
<!--
    Name:   Clients-V.php
    Autor:  Victor Gutierrez
    Date:   13-Jun-2016
    Desc:   Modulo encargado de la gestión de clientes

***************************************************************************************

    Autor: 
    Date:
    Desc:
    
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
    
    /* CM XML */
    eventsGridLoad          = "ClientsData-Grid.xml";
    
    /* Cells */
    gridCell                = "a";
    formCell                = "b";
    
    /* Routes Img */
    gridImg                 = "../../../codebase/imgs/";
    menuImg                 = "../../../codebase/skyblue/imgs";
    
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

/* END INSTANTIATION */ 

/* EVENTS */

    /* Evento onClick del Menu */
    clientsMenu.attachEvent("onClick", function(id) {
        switch (id) {
            case "addEvent":
                /*
                eventsForm.clear();
                eventsForm.unlock();
                eventsForm.setItemFocus("NameOfClient");
                */
                break;
            case "editEvent":
                //eventsForm.unlock();
                break;
        }//fin del switch
    });//fin del evento onClick

/* END EVENTS */     

/* LOADS  */

    //load struct menu
    clientsMenu.loadStruct(clientsMenuXML, clientsMenuCallback);
    
/* END LOADS */

/* FUNCTIONS */

    /* Función callback de la estructura del menu encargada de obtener el valor de los userdata 
     * correspondiente a los header
     */
    function clientsMenuCallback(){
        /*
        var headerForm          = eventsMenu.getUserData("sp3","headerForm");
        var headerFormCollapse  = eventsMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        eventsFormContainer.setText(headerForm);
        eventsLayout.cells(formCell).setCollapsedText(headerFormCollapse);
        */
    }//fin de la funcion eventsMenuCallback

}
    
</script>
</head>
<body>
<div id="clientsLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>