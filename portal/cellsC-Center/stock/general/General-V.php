<!DOCTYPE html>
<!--
    Name:   General-V.php
    Autor:  Hawerd Gonzalez
    Date:   24-May-2016
    Desc:   Modulos principal de General

    Autor:  Hawerd GOnzalez
    Date:   22-Jun-2016
    Desc:   Implementando la carga de la grilla
   
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
    
    generalMenuXML  = "General-Menu.xml";
    generalGridXML  = "General-Grid.xml?etc=";
    generalFormXML  = "General-Form.xml";
    
    generalGridData = "General-CM.php?format=grid&method=loadDataGrid&etc=";
    
    menuImg         = "../../../../codebase/skyblue/imgs";
    
    // dataProcesor
    generalFormdp   = "General-CM.php?format=form";
    
    /*  Cells   */
    gridCell    = "a";
    formCell    = "b"; 
    
    /*  flags   */
    firstTime   = true;
    hasNewRow   = "";
/* INICIALITATION  */    
    pattern     = "2E";
    generalLayout = new dhtmlXLayoutObject("generalLayoutDiv",pattern);
/* END INICIALITATION */   

/* INSTANTIATION  */
    generalGridContainer  = generalLayout.cells(gridCell);
    generalGridContainer.hideHeader();
    
    generalFormContainer  = generalLayout.cells(formCell);
    
    // Menu
    generalMenu =  generalGridContainer.attachMenu();
    //Form
    generalForm = generalFormContainer.attachForm();
    
    //generalMenu.setIconsPath(menuImg);
    generalMenu.setSkin("dhx_skyblue");
    
    // Agrego la grilla    
    generalGrid   = generalGridContainer.attachGrid(); 
    generalGrid.init();
/* END INSTANTIATION */       
    
    generalMenu.attachEvent("onClick", function(id){
        switch(id){
            case "add":
                generalForm.unlock();
                break;
        }// fin del switch        
    });
    //Eventos del formulario
    generalForm.attachEvent("onButtonClick", function(id){
        switch(id){
            case "update":
                generalForm.setItemValue("method", "submitGeneralForm");
                generalForm.save();       
                break;
        }// fin del switch
    });
/* EVENTS */

/* END EVENTS */     

/* LOADS  */
    generalMenu.loadStruct(generalMenuXML);
    generalGrid.load(generalGridXML  + new Date().getTime(),generalGridCallback);
    //load struct form
    generalForm.loadStruct(generalFormXML, generalFormCallback);
/* END LOADS */

/* FUNCTIONS */
    // Carga de los datos para la grilla.
    function generalGridCallback(){
        if(firstTime){
            generalGrid.load(generalGridData + new Date().getTime(),generalGridDataCallback);
        } else {
            generalGrid.clearAndLoad(generalGridData + new Date().getTime(),generalGridDataCallback);
        }
    }
    
    function generalGridDataCallback(){
        // Obtengo el numero de filas o registros en la grilla.
        if(firstTime){
            var rowNum = generalGrid.getRowsNum();
            if(rowNum) {
                selectedRowId   = generalGrid.getRowId(0);
                generalGrid.selectRowById(selectedRowId,false,true,true);    
            } else {
                newRowData = generalGrid.getUserData("","NewRow");
                addNewRow();
            }
        } else {
            generalGrid.selectRowById(selectedRowId,false,true,true);
        }
        firstTime = false;
    }    // end generalGridDataCallback
    
    function generalFormCallback(){
        generalForm.bind(generalGrid);
            function generalFormReturn(node){
                resp        = node.action;
                returnTid   = node.tid;
                switch(resp){
                    case "success":
                        firstTime   = false;
                        selectedRowId = returnTid;
                        generalGridCallback();  
                        break;
            }
        }
        generalForm.lock();
        dp = new dataProcessor(generalFormdp);
        dp.init(generalForm)
        dp.defineAction("success",generalFormReturn);
        dp.defineAction("fail",generalFormReturn);
        dp.defineAction("error",generalFormReturn);
        dp.defineAction("inserted",generalFormReturn);
    }// end function callback
    
    function addNewRow(){
        if (hasNewRow) {
        } else {
            hasNewRow = "add1"						
            generalGrid.addRow(hasNewRow, newRowData);			
            generalGrid.selectRowById(hasNewRow, false, true, true);	
            isAdding = true;
        }
    }//end addNewRow

/* END FUNCTIONS */

});//end function main dhtmlxEvent

</script>
</head>
<body>
<div id="generalLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

