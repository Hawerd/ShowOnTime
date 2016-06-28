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
    
    generalMenuXML      = "General-Menu.xml";
    generalGridXML      = "General-Grid.xml?etc=";
    generalFormXML      = "General-Form.xml";
    generalMsgFormXML   = "GeneralMsg-Form.xml";
    
    generalGridData     = "General-CM.php?format=grid&method=loadDataGrid&etc=";
    
    menuImg             = "../../../../codebase/skyblue/imgs";
    
    // dataProcesor
    generalFormdp       = "General-CM.php?format=form";
    
    /*  Cells   */
    gridCell    = "a";
    formCell    = "b"; 
    
    /*  flags   */
    isAdding        = false;
    isEditing       = false
    firstTime       = true;

    newRowId        = "add"; 
    hasNewRow       = "";
    returnAction    = "";
/* INICIALITATION  */    
    pattern     = "2E";
    generalLayout = new dhtmlXLayoutObject("generalLayoutDiv",pattern);
/* END INICIALITATION */   

/* INSTANTIATION  */
    generalGridContainer  = generalLayout.cells(gridCell);
    generalGridContainer.hideHeader();
    // Menu
    generalMenu =  generalGridContainer.attachMenu();
    generalFormContainer  = generalLayout.cells(formCell);
   //Form
    generalFormContainer.showView("msg");
    generalMsgForm      = generalFormContainer.attachForm();
    //Form Msg
    generalFormContainer.showView("def");
    generalForm   = generalFormContainer.attachForm();
    //generalMenu.setIconsPath(menuImg);
    generalMenu.setSkin("dhx_skyblue");
    // Agrego la grilla    
    generalGrid   = generalGridContainer.attachGrid(); 
    generalGrid.init();
/* END INSTANTIATION */       
/* EVENTS */    
    generalMenu.attachEvent("onClick", function(id){
        switch(id){
            case "add":
                addNewRow();
                break;
            case "edit":
                isEditing = true;
                setupForm();
                break; 
        }// fin del switch   
        
    });
    //Eventos del formulario
    generalForm.attachEvent("onButtonClick", function(id){
        switch(id){
            case "update":
                generalForm.save();       
                break;
            case "clear":
                generalForm.restoreBackup(generalFormBackup);
                break;
            case "cancel":
                if (isAdding){
                    isAdding    = false;
                    generalGrid.deleteSelectedRows(newRowId);                //remover nueva fila
                    selectedRow = generalGrid.getRowId(0);                   //Obtiene el id de la primera fila de la grilla
                    generalGrid.selectRowById(selectedRow,false,true,true);  //se selecciona la primera fila de la grilla
                } else {
                    generalForm.restoreBackup(generalFormBackup);
                }
                generalForm.lock();
                break;
                
        }// fin del switch
    });
    
    generalMsgForm.attachEvent("onButtonClick", function(id){
        switch(returnAction){
            case "success":
                break;
            case "fail":
                generalFormContainer.showView("def");
                break;
        }// fin del switch
        
    });
    //Final Eventos del formulario

/* END EVENTS */     

/* LOADS  */
    generalMenu.loadStruct(generalMenuXML);
    generalGrid.load(generalGridXML  + new Date().getTime(),generalGridCallback);
    //load struct form
    generalForm.loadStruct(generalFormXML, generalFormCallback);
    generalMsgForm.loadStruct(generalMsgFormXML);
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
        newRowData = generalGrid.getUserData("","NewRow");
        if(firstTime){
            var rowNum = generalGrid.getRowsNum();
            if(rowNum) {
                selectedRowId   = generalGrid.getRowId(0);
                generalGrid.selectRowById(selectedRowId,false,true,true);    
            } else {
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
                returnAction    = node.getAttribute("type");
                returnDetail    = node.firstChild.data;
                returnTid       = node.getAttribute("tid");
                switch(returnAction){
                    case "success":
                    case "fail":
                        //Se limpia el cache del dataProccess
                        generalDataProcessor._in_progress   = [0]; 
                        generalDataProcessor.updatedRows    = [0];
                        firstTime   = false;
                        generalMsgForm.setItemLabel("textMsg",returnDetail);
                        generalFormContainer.showView("msg");
                        break;
                    default:
                        /* Este Formato es para un mensaje de error adicional */
                        returnAction = "error";
                        badReturn    = "*** Unhandled returnAction:" + returnAction + " *** " + returnDetail;
                        break;
            }
        }
        generalForm.lock();
        generalDataProcessor = new dataProcessor(generalFormdp);
        generalDataProcessor.init(generalForm)
        generalDataProcessor.defineAction("success",generalFormReturn);
        generalDataProcessor.defineAction("fail",generalFormReturn);
        generalDataProcessor.defineAction("error",generalFormReturn);
        generalDataProcessor.defineAction("inserted",generalFormReturn);
    }// end function callback
    
    function addNewRow(){
        isAdding    = true;
        generalForm.unlock();
        selectedRow = newRowId;						
        generalGrid.addRow(newRowId, newRowData);			
        generalGrid.selectRowById(selectedRow, false, true, true);
        generalFormBackup = generalForm.saveBackup();
        setupForm();
    }//end addNewRow
    
    function setupForm(){
        if( isEditing ){
            generalFormBackup = generalForm.saveBackup();
            generalForm.unlock();
            generalForm.hideItem("quantity");
            generalForm.showItem("elementScheduled");
        }
        if ( isAdding ){
            generalForm.showItem("quantity");
            generalForm.hideItem("elementScheduled");
        }
    }// end setupForm

/* END FUNCTIONS */

});//end function main dhtmlxEvent

</script>
</head>
<body>
<div id="generalLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

