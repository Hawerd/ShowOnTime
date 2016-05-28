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
    stockBarXML     = "StockBar.xml";
    
    //Array of tabs main 
    stockTabs       = [{tab:"audio",route:"../../../portal/cellsC-Center/stock/audio/Audio-V.php"}];

    //Cells
    stockGridCell   = "a";
    stockFormCell   = "b";
    
/* END INICIALITATION */   
      
/* INSTANTIATION  */
    
    stockLayout = new dhtmlXLayoutObject("stockLayoutDiv","1C");
    
    stockGridContainer = stockLayout.cells(stockGridCell);
    stockGridContainer.hideHeader();
    
    stockTabbar    = stockGridContainer.attachTabbar();

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
    stockTabbar.loadStruct(stockBarXML);
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

