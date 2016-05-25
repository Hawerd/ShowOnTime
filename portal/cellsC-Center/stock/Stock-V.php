<!DOCTYPE html>
<!--
    Name:   Stock-V.php
    Autor:  Hawerd Gonzalez
    Date:   22-May-2016
    Desc:   Modulos principal de Stock

    Autor:  Luis F Castaño
    Date:   25-May-2016
    Desc:   Se corrige ruta del audio xml.
   
-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../codebase/dhtmlx.js" type="text/javascript"></script>

<script type="text/javascript">

    stockBarXML     = "StockBar.xml";
    
    audioXML        = "../../../portal/cellsC-Center/stock/audio/Audio-V.php";
/*  Variables de layout */
    stockGridCell   = "a";
    stockFormCell   = "b";
//Function main dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    
/* INICIALITATION  */    
    stockLayout = new dhtmlXLayoutObject(stock,"1C");
/* END INICIALITATION */   
    stockGridContainer = stockLayout.cells(stockGridCell);
    stockGridContainer.hideHeader();
    
    audioTab    = stockGridContainer.attachTabbar();
            
/* INSTANTIATION  */

/* END INSTANTIATION */       
    
/* EVENTS */
    audioTab.attachEvent("onTabClick", function(id) {
        switch(id){
            case id:
            audioTab.tabs(id).attachURL(audioXML);   
            break;
       }//fin del switch 
    });//fin del evento onTabClick    
        
/* END EVENTS */     

/* LOADS  */
    audioTab.loadStruct(stockBarXML);
/* END LOADS */

/* FUNCTIONS */

/* END FUNCTIONS */

});//end function main dhtmlxEvent

</script>
</head>
<body>
<div id="stock" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

