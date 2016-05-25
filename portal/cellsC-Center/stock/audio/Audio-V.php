<!DOCTYPE html>
<!--
    Name:   Stock-V.php
    Autor:  Hawerd Gonzalez
    Date:   24-May-2016
    Desc:   Modulos principal de Audio
   
-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../../codebase/dhtmlx.js" type="text/javascript"></script>

<script type="text/javascript">

/*  Variables de layout */
    
    
//Function main dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    
    audioMenuXML    = "Audio-Menu.xml";
    audioGridXML    = "Audio-Grid.xml";
    
    audioDataXML    = "AudioDataTest-Grid.xml";
    
    menuImg         = "../../../../codebase/skyblue/imgs";
    
    /*  Cells   */
    gridCell    = "a";
    formCell    = "b"; 
    
    /*  flags   */
    firstTime   = true;
/* INICIALITATION  */    
    pattern     = "2E";
    audioLayout = new dhtmlXLayoutObject("audioLayoutDiv",pattern);
/* END INICIALITATION */   

/* INSTANTIATION  */
    audioGridContainer  = audioLayout.cells(gridCell);
    audioGridContainer.hideHeader();
    
    audioFormContainer  = audioLayout.cells(formCell);
    
    // Menu
    audioMenu   =  audioGridContainer.attachMenu();
    //audioMenu.setIconsPath(menuImg);
    audioMenu.setSkin("dhx_skyblue");
    
    // Agrego la grilla    
    audioGrid   = audioGridContainer.attachGrid(); 
    audioGrid.init();
/* END INSTANTIATION */       
    
/* EVENTS */

/* END EVENTS */     

/* LOADS  */
    audioMenu.loadStruct(audioMenuXML);
    audioGrid.load(audioGridXML, audioGridCallback);
/* END LOADS */

/* FUNCTIONS */
    function audioGridCallback(){
       
       if ( firstTime ){
           audioGrid.load(audioDataXML);
       } else {
           audioGrid.clearAndLoad(audioDataXML);
       }
    }
/* END FUNCTIONS */

});//end function main dhtmlxEvent

</script>
</head>
<body>
<div id="audioLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

