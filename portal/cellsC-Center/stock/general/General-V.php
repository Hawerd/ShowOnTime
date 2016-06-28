<!DOCTYPE html>
<!--
    Name:   General-V.php
    Autor:  Hawerd Gonzalez
    Date:   24-May-2016
    Desc:   Modulos principal de General

    Autor:  Hawerd GOnzalez
    Date:   22-Jun-2016
    Desc:   Implementando la carga de la grilla

    Autor:  Luis F Castaño
    Date:   28-Jun-2016
    Desc:   Se implemente actualizacion en la vista.
   
-->
<html>
<head>
<title>Show On Time</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
    generalInit();
});//end function dhtmlxEvent
  
function generalInit(){
   
/* INICIALITATION  */  
    
    /* Static XML */
    generalGridXML       = "General-Grid.xml";
    generalMenuXML       = "General-Menu.xml";
    generalFormXML       = "General-Form.xml";
    generalMsgFormXML    = "GeneralMsg-Form.xml";
    
    /* CM XML */
    generalGridLoad      = "General-CM.php?format=grid&method=loadDataGrid";
    generalDP            = "General-CM.php?format=form";
    
    /* combo XML */
    connCombos          = 1;    //Numero de combos con un conector en el formulario.
    connCombosLoaded    = 0;    //Se utiliza para establecer un indicador de carga para los combos
    
    /* Cells */
    gridCell            = "a";
    formCell            = "b";
    
    /* Routes Img */
    gridImg             = "../../../codebase/imgs/";
    menuImg             = "../../../codebase/skyblue/imgs";
    
    /* Referencia para las columnas de la grilla */
    elementUUIDCol        = 7;
    
    /* Variables Generales */
    newRowData          = "Nuevo Elemento,,,,,,?,,submitDataForm,add";// contiene la data de la nueva fila en la grilla para el nuevo registro.
    unexpMsg            = "<i>Unexpected Error</i>";            // Mensaje para los Erroes inexperados en el formulario
    newRowId            = "add";                                // contiene el id de la nueva fila para realizar un nuevo registro.
    selectedRow         = "";                                   // contiene el id a seleccionar en la grilla. 
    returnAction        = "";                                   // contiene la accion que retorna el CM ej: success y fail.
    returnTid           = "";                                   // contiene el UUID que retorna el CM para la Seleccion en la Grilla.
    badReturn           = "";                                   // contiene el mensaje de ERROR que devuelve la data de la grilla
    checkFlagsTimer     = 0;                                    // contiene el tiempo de ejecucion de la funcion setInterval (xx/ms).
    rowNum              = 0;                                    // contiene el numero de filas de la grilla.
    
    /* Flags Generales */ 
    firstTime           = true;                                 // flag que verifica si al modulo se esat accediendo por priemra vez
    canChangeForm       = false;                                // flag que indica si el formulario esta desbloqueado (true)
    isAdding            = false;                                // flag que indica que la grilla esta agregando una fila nueva.
    isSavingForm        = false;                                // flag que me indica que la forma de detalle esta guardando
    isReSelecting       = false;                                // flag que indica la reSeleccion de una fila en la treeGrid
    
    //Puntos de control de estructuras y datos
    generalMenuLoaded    = false;                                // flag para chequear la carga del menu 
    generalGridLoaded    = false;                                // flag para chequear la carga de la Grilla 
    generalFormLoaded    = false;                                // flag para chequear la carga del Formulario 
    generalMsgFormLoaded = false;                                // flag para chequear la carga del Formulario de Msg
    
    /* userdata Form header */
    headerForm          = "";
    headerFormCollapse  = "";
    noChangeForm        = "";                                  
    rmvQuestTitle       = "";
    rmvQuestText        = "";
    rmvQuestCancel 	= "";
    rmvQuestOk          = "";
    
/* END INICIALITATION */   

/* INSTANTIATION  */
    
    //Layout Main 
    pattern         = "2E";
    generalLayout    = new dhtmlXLayoutObject("generalLayoutDiv",pattern);
    
    //Grid Container 
    generalGridContainer    = generalLayout.cells(gridCell);
    generalGridContainer.hideHeader();
    
    //Form Container
    generalFormContainer    = generalLayout.cells(formCell);
    
    //Menu
    generalMenu  = generalGridContainer.attachMenu();
    generalMenu.setIconsPath(menuImg);
    generalMenu.setSkin("dhx_skyblue");
    
    //Grid
    generalGrid  = generalGridContainer.attachGrid();
    generalGrid.setImagePath(gridImg);
    generalGrid.init();
  
    //Form Msg
    generalFormContainer.showView("msg");
    generalMsgForm   = generalFormContainer.attachForm();
    
    //Form
    generalFormContainer.showView("def");
    generalForm      = generalFormContainer.attachForm();
    
    // Bind to Grid 	
    generalForm.bind(generalGrid);

/* END INSTANTIATION */       

/* EVENTS */
    
    /* Evento onClick del Menu */
    generalMenu.attachEvent("onClick", function(id){
        switch (id) {
            case "addElement":
                generalAddNewRow();
                break;
            case "editElement":
                canChangeForm   = true;
                generalForm.unlock();
                generalMenuButtonSetup();
                break;
            case "removeElement":
                generalRemoveDialog();
                break;   
        }//fin del switch
    });//fin del evento onClick

    /* Evento onXLS de la Grilla */
    generalGrid.attachEvent("onXLS", function(grid){
        generalGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    generalGrid.attachEvent("onXLE", function(grid){
        generalGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento que controla, Si o No, una fila se selecciona dependiendo
       de la condicion que se que se presenta en el formulario, si esta
       guardando o esta presente el formulario de mensajes, no se podra
       seleccionar una fila de la grilla. */
    generalGrid.attachEvent("onBeforeSelect", function(id, oldId){
        
        var doSelect = false;
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = generalFormContainer.getViewName();
        
        /* Permite seleccionar una fila si:
        1. activeView    != msg
        2. isSavingForm  == false.
        3. canChangeForm == false.
        4. isAdding      == false  */
        if( activeView != "msg" && !canChangeForm && !isSavingForm && !isAdding ){
            doSelect = true;
        }
        
        /* Permite seleccionar una fila si isReSelecting es true
        isReSelecting es una bandera que sobrepone su efecto a la accion de las otras banderas.
        Hay 2 condiciones cuando se establece a true.

        1. xxxAddNewRow(). Como tenemos a isAdding = true, necesitamos isReselecting = true
        de manera que podamos seleccionar la fila que hemos agregado recientemente.
        De lo contrario, no seriamos capaces de seleccionar la fila ya que isAdding = true 

        2. Evento "onButtonClick" del xxxMsgForm. Si el returnAction == 'success',
        necesitamos isReselecting = true de manera que podamos reseleccionar la fila que hemos
        agregado o actualizado. De lo contrario, no seriamos capaces de selecionar la fila debido a
        que activeView = 'msg' */
        if ( isReSelecting ) {
            isReSelecting  = false;
            doSelect       = true;
        }
        
        return doSelect;
    });//fin del evento onBeforeSelect
    
    /* Evento onRowSelect de la Grilla */
    generalGrid.attachEvent("onRowSelect", function(id){
       //configurar formulario
        generalFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onBeforeSave para el formulario principal del Modulo */
    generalForm.attachEvent("onBeforeSave", function(id, values){

        /* Chequeo para Cambios */
        var currentValues = generalForm.getFormData();
        var changeCtr = 0;
        
        generalForm.forEachItem(function(name){
            if (typeof currentValues[name] != "undefined") {
                var currVal = currentValues[name];
                var origVal = generalFormOriginalValues[name];
                switch (name){
                    case "DateOfMounting":
                    case "DateOfStart":
                    case "DateFinal":    
                        if( currVal != null && origVal!= null ){
                            currVal = currVal.toString();
                            origVal = origVal.toString();
                        }
                        break;
                }//fin del switch
                if (currVal === origVal) {
                    //values are the same
                } else {
                    //values are different
                    changeCtr += 1;
                }
            } else {
                    //fieldset and other calculated form field ids
            }
        });//fin de la funcion forEachItem
        
        if (changeCtr) {
            isSavingForm = true;                //El formulario esta en proceso de guardado
            generalFormContainer.progressOn(); 	//Empieza el progreso de la forma
            generalMenuButtonSetup();
            return true;
        } else {
            // message no changes to save 
            returnAction = "fail";
            generalMsgForm.showItem("ok");
            generalMsgForm.setItemLabel("textMsg", noChangeForm );   //txt not change for the form Msg. 
            generalFormContainer.showView("msg");
            generalMenuButtonSetup();
            return false;
        }//fin de la condicion changeCtr			

    });//fin de del eventon onBeforeSave del formulario
    
    /* Evento onButtonClik del formulario de Eventos */
    generalForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                generalForm.save();
                break;
            case "clear":
                generalForm.restoreBackup(generalFormBackup);
                break;
            case "cancel":
                canChangeForm   = false;
                /* si se esta agregando, eliminar fila y reseleccionar la primera 
                 * caso contrario restaurar backup */
                if(isAdding){
                    isAdding    = false;
                    selectedRow = generalGrid.getRowId(0);                   //Obtiene el id de la primera fila de la grilla
                    generalGrid.deleteSelectedRows(newRowId);                //remover nueva fila
                    generalGrid.selectRowById(selectedRow,false,true,true);  //se selecciona la primera fila de la grilla
                    generalGrid.showRow( selectedRow );                      //mostrar fila seleccionada 
                }else{
                    generalForm.restoreBackup(generalFormBackup);
                    generalForm.lock();
                    generalMenuButtonSetup();
                }  
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick
    
    /* Evento onButtonClik del formulario de Mensajes de Usuario (view.Msg) */
    generalMsgForm.attachEvent("onButtonClick", function(name){
        switch (name) {
            case "ok":
                switch( returnAction ){
                    case "success":
                        selectedRow 	= returnTid;
                        isReSelecting 	= true;
                        isAdding        = false;
                        isSavingForm 	= false; 
                        canChangeForm 	= false; 
                        generalGridCallback();                   //transfiere el control a la funcion
                        break;
                    case "fail":
                        isSavingForm = false;                   //la forma no esta en proceso de guardado
                        generalFormContainer.showView("def");    //transfiere el control al usuario
                        generalMenuButtonSetup();
                        break;
                }//fin del switch
                break;
        }// fin del switch		
    }); //Fin del Evento onButtonClick
    
/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    generalMenu.loadStruct(generalMenuXML,generalMenuCallback);
    
    //load struct grid
    generalGrid.load(generalGridXML,generalGridCallback);
    
    //load struct form
    generalForm.loadStruct(generalFormXML,generalFormCallback);
    
    //load struct form msg
    generalMsgForm.loadStruct(generalMsgFormXML, function(){
                                                    //flag,se configura a true: estructura y tratamiento de datos propia lista
                                                    generalMsgFormLoaded = true;
                                               }//fin function callBack
    );
   
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function generalMenuCallback(){
        
        headerForm          = generalMenu.getUserData("sp3","headerForm");
        headerFormCollapse  = generalMenu.getUserData("sp3","headerFormCollapse");
        noChangeForm        = generalMenu.getUserData("sp3","noChangeForm");
        rmvQuestTitle       = generalMenu.getUserData("sp3","rmvQuestTitle");
        rmvQuestText        = generalMenu.getUserData("sp3","rmvQuestText");
        rmvQuestCancel      = generalMenu.getUserData("sp3","rmvQuestCancel");
        rmvQuestOk          = generalMenu.getUserData("sp3","rmvQuestOk");
        
        //Se configura header del formualrio
        generalFormContainer.setText(headerForm);
        generalLayout.cells(formCell).setCollapsedText(headerFormCollapse);
        
        //flag,se configura a true: estructura y tratamiento de datos propia lista
        generalMenuLoaded = true;
    }//fin de la funcion generalMenuCallback
    
    /* funcion callback de la estructura de la grilla 
     * que se encarga de cargar los datos  */
    function generalGridCallback(){
    /*  datos cargados por primera vez(cuando se inicia el modulo)
        caso contrario se limpiara la estructura y se cargara datos */
        if( firstTime ){
            generalGrid.load(generalGridLoad,generalGridDataCallback);
        }else{
            generalGrid.clearAndLoad(generalGridLoad,generalGridDataCallback);
        }
        
    }//fin de la funcion generalGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function generalGridDataCallback(){
        
        if(firstTime){
           firstTime    = false; 
        }
        
        //Manejar respuesta de error de la carga de datos 
        var hasError    = generalGrid.getUserData("","hasError");
        
        if ( hasError == 'true' ) {
            
            returnAction 	= "error";
            badReturn 		= generalGrid.getUserData("","errorMsg");
            badReturn 		= "***" + unexpMsg + "*** " + badReturn;
            
        }else{
            
           rowNum   = generalGrid.getRowsNum();              // numero total de filas en la grilla 
           
           if(rowNum){
               
               var oldSelectedRow  = selectedRow;            //fila que vamos a volver a re-seleccionar, si es que existe.
               selectedRow         = generalGrid.getRowId(0); //Obtiene el id de la primera fila de la grilla

                if (oldSelectedRow != "") {
                    var searchResult = generalGrid.findCell( oldSelectedRow,elementUUIDCol,true );
                    if (searchResult.length) {
                        var searchArray = searchResult[0];
                        selectedRow = searchArray[0];
                    }
                }

                 //Selecciona la Fila (Dispara Evento onRowSelect). 
                generalGrid.selectRowById(selectedRow,false,true,true);
                generalGrid.showRow( selectedRow );
                 
           }else{
                generalAddNewRow();   
           }
         
        }//fin de la condicion que evalua el Error de Carga
        
        /*  llamada de función para cargar los elementos dependientes, con o sin la función de temporizador, 
        en función de si o no ya cargado */
        if ( generalGridLoaded ){
            generalLoadDependent();
        } else {
            //flag,se configura a true: estructura y tratamiento de data propia lista
            generalGridLoaded 	= true;
            checkFlagsTimer 	= setInterval(function() { generalCheckFlags(); },3); // 3ms interval	
        }
        
    }//fin de la funcion generalGridDataCallback
    
    /* funcion callback de la estructura del formulario */
    function generalFormCallback(){

        function generalFormReturn(node){
            
            returnAction = node.getAttribute("type");
            returnDetail = node.firstChild.data;
            returnTid	 = node.getAttribute("tid");
            
            switch(returnAction){
                case "success":
                case "fail":
                    //Se limpia el cache del dataProccess
                    generalFormDP._in_progress   = [0]; 
                    generalFormDP.updatedRows    = [0];
                    generalMsgForm.setItemLabel("textMsg", returnDetail); // Nombre del campo xml para el texto
                    generalMsgForm.showItem("ok");
                    break;
                case 'invalid':
                case 'error':
                case 'insert':
                case 'update':
                default:
                    /* Este Formato es para un mensaje de error adicional */
                    returnAction = "error";
                    badReturn    = "*** Unhandled returnAction:" + returnAction + " *** " + returnDetail;
                    break;
            }//fin del switch
            
            //Fin del progreso de la forma
            generalFormContainer.progressOff();
            
            if ( returnAction == "error" ) {
                generalShowUnexpError();
            } else {
                /*ir a la Vista MsgForm - transfiere el control al usuario */
                generalFormContainer.showView("msg");
            }
 
        }//fin funcion generalFormReturn
        
        /* DataProcess para la forma general */
        generalFormDP  = new dataProcessor(generalDP);
        generalFormDP.init(generalForm);
        generalFormDP.defineAction("success", generalFormReturn);	// updated or inserted
        generalFormDP.defineAction("fail",    generalFormReturn);	// problem on update or insert
        generalFormDP.defineAction("invalid", generalFormReturn);	// invalid form data
        generalFormDP.defineAction("error",   generalFormReturn);	// error on form operation
        generalFormDP.defineAction("insert",  generalFormReturn);	// should not occur
        generalFormDP.defineAction("update",  generalFormReturn);	// should not occur
        
        /* El formulario no está completamente cargado hasta que todos los combos esten listos */
        generalForm.attachEvent("onOptionsLoaded", function(name){
            if ( ! generalFormLoaded ) {
                /* for combos with connectors */
                switch (name) {
                    case "FK_elementTypeCode":
                        connCombosLoaded += 1;
                        break;
                }
                if (connCombosLoaded == connCombos) {
                    //flag,se configura a true: estructura y tratamiento de data propia lista
                    generalFormLoaded = true
                }
            }
        });

    }//fin de la funcion generalFormCallback
    
    /* funcion que chequea los flags de cada componente del modulo (componentes)*/
    function generalCheckFlags(){

        /* Estatodo instanciado , carga de estructuras ,
        y cualquier dependencia de estructura de carga (como un Combo )?
        ¿YA TERMINAMOS? */
        if ( generalMenuLoaded && generalGridLoaded && generalFormLoaded && generalMsgFormLoaded ) {		
            clearInterval(checkFlagsTimer); //clear interval so no longer executes
            generalLoadDependent();          //go to function that deals with any lane
        }//fin de la condicion

    }//fin de la funcion generalCheckFlags
    
    /* funcion que manipula los datos que dependen de la carga de otro componente */
    function generalLoadDependent(){
        
        if( returnAction == "error" ){
            generalShowUnexpError();
        }else{
            /* Aqui se Situa la configuracion de los componentes 
               que requieren datos de la carga de otro */
        }
        
    }//fin de la funcion generalLoadDependent
    
    /* funcion que configura los inputs de la forma */
    function generalFormSetup(){
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = generalFormContainer.getViewName();
        if( activeView != "def" ){
            generalFormContainer.showView("def");
        }
        
        //si esta agregando el formulario
        if(isAdding){
            if(!rowNum){
                generalForm.disableItem("cancel");
            }
            //se desbloquea la forma
            generalForm.unlock();
            generalForm.setItemFocus("NameOfClient");
        }else{
            //Se bloquea la forma
            generalForm.lock();
        }
        
        //backups de la fila seleccionada
        generalFormBackup          = generalForm.saveBackup();
        generalFormOriginalValues  = generalForm.getFormData();

        //Se configuran botones del Menu
        generalMenuButtonSetup();
    }//fin de la funcion generalFormSetup
    
    /* funcion que se encarga de configurar la forma Msg para los Errores */
    function generalShowUnexpError(){
        
        if ( badReturn == "" ) {
            badReturn = "Error";
        }
        
        generalMsgForm.hideItem("ok");
        generalFormContainer.setText(headerForm + " - " + unexpMsg);
        generalLayout.cells(formCell).setCollapsedText(headerFormCollapse + " - " + unexpMsg);
        generalMsgForm.setItemLabel("textMsg", badReturn);
        generalFormContainer.showView("msg");
        generalMenuButtonSetup();
	
    }//fin funcion generalShowUnexpError
    
     /* funcion que se encarga de agregar una nueva fila en la grilla */
    function generalAddNewRow() {

        isAdding        = true;
        isReSelecting	= true;
        selectedRow     = newRowId;
        generalGrid.addRow( newRowId, newRowData );
        generalGrid.selectRowById( selectedRow,false,true,true );
        generalGrid.showRow( selectedRow );	

    }// fin de la funcion generalAddNewRow
    
    /* funcion que configura los botones del menu */
    function generalMenuButtonSetup(){
        
        //deshabilitado todos los botones
        generalMenu.setItemDisabled("addElement");
        generalMenu.setItemDisabled("editElement");
        generalMenu.setItemDisabled("removeElement");
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = generalFormContainer.getViewName();
        
        if(activeView == "msg" || canChangeForm || isSavingForm || isAdding){
            return true;
        }
        
        //Se habilita todos los botones.
        generalMenu.setItemEnabled("addElement");
        generalMenu.setItemEnabled("editElement");
        generalMenu.setItemEnabled("removeElement");
        
    }//fin de la funcion generalMenuButtonSetup
    
    /* funcion que se encarga de mostrar una ventana modal que advierte
     * al usuario si desea eliminar el registro el registro actual */
    function generalRemoveDialog(){
        dhtmlx.message({
            title: rmvQuestTitle,
            type:  "confirm-warning",
            text:  rmvQuestText,
            cancel:rmvQuestCancel,
            ok:    rmvQuestOk,
            callback: function(res) {
                if (res) {
                    generalForm.setItemValue("op", "remove");
                    generalForm.save();
                } 
            }
        });
    }//fin de la funcion generalRemoveDialog

/* END FUNCTIONS */
    
}//fin de la funcion generalInit

</script>
</head>
<body>
<div id="generalLayoutDiv" style="position: fixed; height: 100%; width: 100%;"></div>
</body>
</html>

