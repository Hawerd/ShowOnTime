<!DOCTYPE html>
<!--
    Name:   Stock-V.php
    Autor:  Hawerd Gonzalez
    Date:   22-May-2016
    Desc:   Modulos principal de Stock

    Autor:  Luis F Castaño
    Date:   25-May-2016
    Desc:   Se corrige ruta del audio xml.

    Autor:  Luis F Castaño
    Date:   27-May-2016
    Desc:   Se agrega  logica para activar los tabs

    Autor:  Luis F Castaño
    Date:   12-Jun-2016
    Desc:   Se renombrar variables de los componentes, no
            se usa grilla ni forma.

    Autor:  Luis F Castaño
    Date:   13-Jun-2016
    Desc:   Se agrega al Array stockTabs la ruta del Modulo
            lights para funcionamiento.

    Autor:  Luis F Castaño
    Date:   14-Jun-2016
    Desc:   Se agrega al Array stockTabs la ruta del Modulo
            structure para su funcionamiento.

    Autor:  Luis F Castaño
    Date:   16-Jun-2016
    Desc:   Se agrega al Array stockTabs la ruta del Modulo
            general para su funcionamiento.
   
-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
   stockInit();
});//end function dhtmlxEvent

//Function Stock Init
function stockInit(){
    
/* INICIALITATION  */ 
    
    //Static XML 
    stockTabbarXML  = "StockTabbar.xml";
    
    //Array of tabs main 
    stockTabs       = [{tab:"general",route:"../../../portal/cellsC-Center/stock/general/General-V.php"},
                       {tab:"audio",route:"../../../portal/cellsC-Center/stock/audio/Audio-V.php"},
                       {tab:"lights",route:"../../../portal/cellsC-Center/stock/lights/Lights-V.php"},
                       {tab:"structures",route:"../../../portal/cellsC-Center/stock/structure/Structure-V.php"}];

    //Cells
    tabbarCell      = "a";
   
/* END INICIALITATION */   
      
/* INSTANTIATION  */
    
    /* main layout */
    stockLayout = new dhtmlXLayoutObject("stockLayoutDiv","1C");
    
    /* Container Tabbar */
    stockTabbarContainer = stockLayout.cells(tabbarCell);
    stockTabbarContainer.hideHeader();
    
    /* Tabbar */
    stockTabbar    = stockTabbarContainer.attachTabbar();

/* END INSTANTIATION */       
    
/* EVENTS */

    stockTabbar.attachEvent("onTabClick", function(id) {
       var tabActive = stockTabbar.getActiveTab(); 
        if(tabActive != id){
            for(var i=0;i<stockTabs.length;i++){
                if( stockTabs[i].tab == id ){
                    stockTabbar.tabs(id).attachURL(stockTabs[i].route);
                    break;
                }
            }//fin del ciclo
        }//fin de la condicion tabActive
    });//fin del evento onTabClick    
        
/* END EVENTS */     

/* LOADS  */
    stockTabbar.loadStruct(stockTabbarXML);
/* END LOADS */

/* FUNCTIONS */

/* END FUNCTIONS */

};//end function Init
</script>
</head>
<body>
<div id="stockLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

