<!DOCTYPE html>
<!--
    Name:   Events-V.php
    Autor:  Luis F Castaño
    Date:   22-May-2016
    Desc:   Modulos principal de Eventos.

    Autor:  Luis castaño
    Date:   22-May-2016  
    Desc:   Se realiza logica del modulo.

    Autor:  Luis castaño
    Date:   23-May-2016  
    Desc:   Se realiza la logica de la funcion callback eventsGridCallback.
            Se Realiza la carga de datos "Quemados" con xml.
            Se agregan Eventos onXLE y onXLS.

    Autor:  Luis castaño
    Date:   24-May-2016  
    Desc:   Se realizan los callback de los componentes existentes
            Se crean las funciones de apoyo como form y menu setup
            Se agregan eventos como el onClik y onRowSelect.

    Autor:  Luis castaño
    Date:   12-Jun-2016  
    Desc:   Se ajusta el id del setItemFocus en el evento onClik
            del menu.

    Autor:  Luis castaño
    Date:   14-Jun-2016  
    Desc:   Se ajusta el style.textAlign del input dateOfStart 
            en la funcion eventsFormCallback 

    Autor:  Luis castaño
    Date:   17-Jun-2016  
    Desc:   * Se realiza el dataProccess del formulario eventsForm.
            * Se realiza el control de Errores por parte de la carga de datos de la grilla.
            * El modulo sigue en prototipo, logica de Errores y control de asincronismo aun por
            Realizar.

    Autor:  Luis castaño
    Date:   21-Jun-2016  
    Desc:   * se agregan nuevos eventos, onBeforeSelect y onBeforeSave.
            * Se agregan nuevas funciones addNewRow y eventRemoveDialog.
            * Se agrega como variable global rowNum.
   
-->
<html>
<head>
<title>Eventos</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link href="../../../codebase/skyblue/dhtmlx.css" rel="stylesheet" type="text/css"/>
<script src="../../../codebase/dhtmlx.js" type="text/javascript"></script>
<script type="text/javascript">

//Function dhtmlxEvent
dhtmlxEvent(window,"load",function(){
    //execute dhtmlx init
    eventsInit();
});//end function dhtmlxEvent

function eventsInit(){
   
/* INICIALITATION  */  
    
    /* Static XML */
    eventsGridXML       = "Events-Grid.xml";
    eventsMenuXML       = "Events-Menu.xml";
    eventsFormXML       = "Events-Form.xml";
    eventsMsgFormXML    = "EventsMsg-Form.xml";
    
    /* CM XML */
    eventsGridLoad      = "Events-CM.php?format=grid&method=loadDataGrid";
    eventsDP            = "Events-CM.php?format=form";
    
    /* Cells */
    gridCell            = "a";
    formCell            = "b";
    
    /* Routes Img */
    gridImg             = "../../../codebase/imgs/";
    menuImg             = "../../../codebase/skyblue/imgs";
    
    /* Referencia para las columnas de la grilla */
    eventUUIDCol        = 9;
    
    /* Variables Generales */
    newRowData          = "Nuevo Evento,,,,,,,,,,?,?,?,submitDataForm,add,";// contiene la data de la nueva fila en la grilla para el nuevo registro.
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
    eventsMenuLoaded    = false;                                // flag para chequear la carga del menu 
    eventsGridLoaded    = false;                                // flag para chequear la carga de la Grilla 
    eventsFormLoaded    = false;                                // flag para chequear la carga del Formulario 
    eventsMsgFormLoaded = false;                                // flag para chequear la carga del Formulario de Msg
    
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
    eventsLayout    = new dhtmlXLayoutObject("eventsLayoutDiv",pattern);
    
    //Grid Container 
    eventsGridContainer    = eventsLayout.cells(gridCell);
    eventsGridContainer.hideHeader();
    
    //Form Container
    eventsFormContainer    = eventsLayout.cells(formCell);
    
    //Menu
    eventsMenu  = eventsGridContainer.attachMenu();
    eventsMenu.setIconsPath(menuImg);
    eventsMenu.setSkin("dhx_skyblue");
    
    //Grid
    eventsGrid  = eventsGridContainer.attachGrid();
    eventsGrid.setImagePath(gridImg);
    eventsGrid.init();
  
    //Form Msg
    eventsFormContainer.showView("msg");
    eventsMsgForm   = eventsFormContainer.attachForm();
    
    //Form
    eventsFormContainer.showView("def");
    eventsForm      = eventsFormContainer.attachForm();
    
    // Bind to Grid 	
    eventsForm.bind(eventsGrid);

/* END INSTANTIATION */       

/* EVENTS */
    
    /* Evento onClick del Menu */
    eventsMenu.attachEvent("onClick", function(id){
        switch (id) {
            case "addEvent":
                eventsAddNewRow();
                break;
            case "editEvent":
                canChangeForm   = true;
                eventsForm.unlock();
                eventsMenuButtonSetup();
                break;
            case "removeEvent":
                eventsRemoveDialog();
                break;   
        }//fin del switch
    });//fin del evento onClick

    /* Evento onXLS de la Grilla */
    eventsGrid.attachEvent("onXLS", function(grid){
        eventsGridContainer.progressOn();
    });
    /* Evento onXLE de la Grilla */
    eventsGrid.attachEvent("onXLE", function(grid){
        eventsGridContainer.progressOff();
    });//fin del evento onXLS y onXLE
    
    /* Evento que controla, Si o No, una fila se selecciona dependiendo
       de la condicion que se que se presenta en el formulario, si esta
       guardando o esta presente el formulario de mensajes, no se podra
       seleccionar una fila de la grilla. */
    eventsGrid.attachEvent("onBeforeSelect", function(id, oldId){
        
        var doSelect = false;
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = eventsFormContainer.getViewName();
        
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
    eventsGrid.attachEvent("onRowSelect", function(id){
       //configurar formulario
        eventsFormSetup();
    });//fin del evento onRowSelect
    
    /* Evento onBeforeSave para el formulario principal del Modulo */
    eventsForm.attachEvent("onBeforeSave", function(id, values){

        /* Chequeo para Cambios */
        var currentValues = eventsForm.getFormData();
        var changeCtr = 0;
        
        eventsForm.forEachItem(function(name){
            if (typeof currentValues[name] != "undefined") {
                var currVal = currentValues[name];
                var origVal = eventsFormOriginalValues[name];
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
            eventsFormContainer.progressOn(); 	//Empieza el progreso de la forma
            eventsMenuButtonSetup();
            return true;
        } else {
            // message no changes to save 
            returnAction = "fail";
            eventsMsgForm.showItem("ok");
            eventsMsgForm.setItemLabel("textMsg", noChangeForm );   //txt not change for the form Msg. 
            eventsFormContainer.showView("msg");
            eventsMenuButtonSetup();
            return false;
        }//fin de la condicion changeCtr			

    });//fin de del eventon onBeforeSave del formulario
    
    /* Evento onButtonClik del formulario de Eventos */
    eventsForm.attachEvent("onButtonClick", function(id) {
        switch(id){
            case "update":
                eventsForm.save();
                break;
            case "clear":
                eventsForm.restoreBackup(eventsFormBackup);
                break;
            case "cancel":
                canChangeForm   = false;
                /* si se esta agregando, eliminar fila y reseleccionar la primera 
                 * caso contrario restaurar backup */
                if(isAdding){
                    isAdding    = false;
                    selectedRow = eventsGrid.getRowId(0);                   //Obtiene el id de la primera fila de la grilla
                    eventsGrid.deleteSelectedRows(newRowId);                //remover nueva fila
                    eventsGrid.selectRowById(selectedRow,false,true,true);  //se selecciona la primera fila de la grilla
                    eventsGrid.showRow( selectedRow );                      //mostrar fila seleccionada 
                }else{
                    eventsForm.restoreBackup(eventsFormBackup);
                    eventsForm.lock();
                    eventsMenuButtonSetup();
                }  
                break;
        } // fin del switch	
    });//fin del evento onbuttonClick
    
    /* Evento onButtonClik del formulario de Mensajes de Usuario (view.Msg) */
    eventsMsgForm.attachEvent("onButtonClick", function(name){
        switch (name) {
            case "ok":
                switch( returnAction ){
                    case "success":
                        selectedRow 	= returnTid;
                        isReSelecting 	= true;
                        isAdding        = false;
                        isSavingForm 	= false; 
                        canChangeForm 	= false; 
                        eventsGridCallback();                   //transfiere el control a la funcion
                        break;
                    case "fail":
                        isSavingForm = false;                   //la forma no esta en proceso de guardado
                        eventsFormContainer.showView("def");    //transfiere el control al usuario
                        eventsMenuButtonSetup();
                        break;
                }//fin del switch
                break;
        }// fin del switch		
    }); //Fin del Evento onButtonClick
    
/* END EVENTS */     

/* LOADS  */
    
    //load struct menu
    eventsMenu.loadStruct(eventsMenuXML,eventsMenuCallback);
    
    //load struct grid
    eventsGrid.load(eventsGridXML,eventsGridCallback);
    
    //load struct form
    eventsForm.loadStruct(eventsFormXML,eventsFormCallback);
    
    //load struct form msg
    eventsMsgForm.loadStruct(eventsMsgFormXML, function(){
                                                    //flag,se configura a true: estructura y tratamiento de datos propia lista
                                                    eventsMsgFormLoaded = true;
                                               }//fin function callBack
    );
   
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function eventsMenuCallback(){
        
        headerForm          = eventsMenu.getUserData("sp3","headerForm");
        headerFormCollapse  = eventsMenu.getUserData("sp3","headerFormCollapse");
        noChangeForm        = eventsMenu.getUserData("sp3","noChangeForm");
        rmvQuestTitle       = eventsMenu.getUserData("sp3","rmvQuestTitle");
        rmvQuestText        = eventsMenu.getUserData("sp3","rmvQuestText");
        rmvQuestCancel      = eventsMenu.getUserData("sp3","rmvQuestCancel");
        rmvQuestOk          = eventsMenu.getUserData("sp3","rmvQuestOk");
        
        //Se configura header del formualrio
        eventsFormContainer.setText(headerForm);
        eventsLayout.cells(formCell).setCollapsedText(headerFormCollapse);
        
        //flag,se configura a true: estructura y tratamiento de datos propia lista
        eventsMenuLoaded = true;
    }//fin de la funcion eventsMenuCallback
    
    /* funcion callback de la estructura de la grilla 
     * que se encarga de cargar los datos  */
    function eventsGridCallback(){
    /*  datos cargados por primera vez(cuando se inicia el modulo)
        caso contrario se limpiara la estructura y se cargara datos */
        if( firstTime ){
            eventsGrid.load(eventsGridLoad,eventsGridDataCallback);
        }else{
            eventsGrid.clearAndLoad(eventsGridLoad,eventsGridDataCallback);
        }
        
    }//fin de la funcion eventsGridCallback
    
    /* funcion que manipula la Data de la Grilla*/
    function eventsGridDataCallback(){
        
        if(firstTime){
           firstTime    = false; 
        }
        
        //Manejar respuesta de error de la carga de datos 
        var hasError    = eventsGrid.getUserData("","hasError");
        
        if ( hasError == 'true' ) {
            
            returnAction 	= "error";
            badReturn 		= eventsGrid.getUserData("","errorMsg");
            badReturn 		= "***" + unexpMsg + "*** " + badReturn;
            
        }else{
            
           rowNum   = eventsGrid.getRowsNum();              // numero total de filas en la grilla 
           
           if(rowNum){
               
               var oldSelectedRow  = selectedRow;            //fila que vamos a volver a re-seleccionar, si es que existe.
               selectedRow         = eventsGrid.getRowId(0); //Obtiene el id de la primera fila de la grilla

                if (oldSelectedRow != "") {
                    var searchResult = eventsGrid.findCell( oldSelectedRow,eventUUIDCol,true );
                    if (searchResult.length) {
                        var searchArray = searchResult[0];
                        selectedRow = searchArray[0];
                    }
                }

                 //Selecciona la Fila (Dispara Evento onRowSelect). 
                eventsGrid.selectRowById(selectedRow,false,true,true);
                eventsGrid.showRow( selectedRow );
                 
           }else{
                eventsAddNewRow();   
           }
         
        }//fin de la condicion que evalua el Error de Carga
        
        /*  llamada de función para cargar los elementos dependientes, con o sin la función de temporizador, 
        en función de si o no ya cargado */
        if ( eventsGridLoaded ){
            eventsLoadDependent();
        } else {
            //flag,se configura a true: estructura y tratamiento de data propia lista
            eventsGridLoaded 	= true;
            checkFlagsTimer 	= setInterval(function() { eventsCheckFlags(); },3); // 3ms interval	
        }
        
    }//fin de la funcion eventsGridDataCallback
    
    /* funcion callback de la estructura del formulario */
    function eventsFormCallback(){
        
        //Se configuran los campos fechas para que se ubiquen a la derecha del campo
        eventsForm.getInput("DateOfMounting").style.textAlign = "right";
        eventsForm.getInput("DateOfStart").style.textAlign = "right";
        eventsForm.getInput("DateFinal").style.textAlign = "right";
        
        function eventsFormReturn(node){
            
            returnAction = node.getAttribute("type");
            returnDetail = node.firstChild.data;
            returnTid	 = node.getAttribute("tid");
            
            switch(returnAction){
                case "success":
                case "fail":
                    eventsFormDP._in_progress   = {} //limpiamos el cache del dataProccess
                    eventsFormDP.updatedRows    = [] //limpiamos el cache del dataProccess
                    eventsMsgForm.setItemLabel("textMsg", returnDetail); // Nombre del campo xml para el texto
                    eventsMsgForm.showItem("ok");
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
            eventsFormContainer.progressOff();
            
            if ( returnAction == "error" ) {
                eventsShowUnexpError();
            } else {
                /*ir a la Vista MsgForm - transfiere el control al usuario */
                eventsFormContainer.showView("msg");
            }
 
        }//fin funcion eventsFormReturn
        
        /* DataProcess para la forma Events */
        eventsFormDP  = new dataProcessor(eventsDP);
        eventsFormDP.init(eventsForm);
        eventsFormDP.defineAction("success", eventsFormReturn);	// updated or inserted
        eventsFormDP.defineAction("fail",    eventsFormReturn);	// problem on update or insert
        eventsFormDP.defineAction("invalid", eventsFormReturn);	// invalid form data
        eventsFormDP.defineAction("error",   eventsFormReturn);	// error on form operation
        eventsFormDP.defineAction("insert",  eventsFormReturn);	// should not occur
        eventsFormDP.defineAction("update",  eventsFormReturn);	// should not occur
        
        //flag,se configura a true: estructura y tratamiento de datos propia lista
        eventsFormLoaded = true;
    }//fin de la funcion eventsFormCallback
    
    /* funcion que chequea los flags de cada componente del modulo (componentes)*/
    function eventsCheckFlags(){

        /* Estatodo instanciado , carga de estructuras ,
        y cualquier dependencia de estructura de carga (como un Combo )?
        ¿YA TERMINAMOS? */
        if ( eventsMenuLoaded && eventsGridLoaded && eventsFormLoaded && eventsMsgFormLoaded ) {		
            clearInterval(checkFlagsTimer); //clear interval so no longer executes
            eventsLoadDependent();          //go to function that deals with any lane
        }//fin de la condicion

    }//fin de la funcion eventsCheckFlags
    
    /* funcion que manipula los datos que dependen de la carga de otro componente */
    function eventsLoadDependent(){
        
        if( returnAction == "error" ){
            eventsShowUnexpError();
        }else{
            /* Aqui se Situa la configuracion de los componentes 
               que requieren datos de la carga de otro */
        }
        
    }//fin de la funcion eventsLoadDependent
    
    /* funcion que configura los inputs de la forma */
    function eventsFormSetup(){
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = eventsFormContainer.getViewName();
        if( activeView != "def" ){
            eventsFormContainer.showView("def");
        }
        
        //si esta agregando el formulario
        if(isAdding){
            if(!rowNum){
                eventsForm.disableItem("cancel");
            }
            //se desbloquea la forma
            eventsForm.unlock();
            eventsForm.setItemFocus("NameOfClient");
        }else{
            //Se bloquea la forma
            eventsForm.lock();
        }
        
        //backups de la fila seleccionada
        eventsFormBackup          = eventsForm.saveBackup();
        eventsFormOriginalValues  = eventsForm.getFormData();

        //Se configuran botones del Menu
        eventsMenuButtonSetup();
    }//fin de la funcion eventsFormSetup
    
    /* funcion que se encarga de configurar la forma Msg para los Errores */
    function eventsShowUnexpError(){
        
        if ( badReturn == "" ) {
            badReturn = "Error";
        }
        
        eventsMsgForm.hideItem("ok");
        eventsFormContainer.setText(headerForm + " - " + unexpMsg);
        eventsLayout.cells(formCell).setCollapsedText(headerFormCollapse + " - " + unexpMsg);
        eventsMsgForm.setItemLabel("textMsg", badReturn);
        eventsFormContainer.showView("msg");
        eventsMenuButtonSetup();
	
    }//fin funcion eventsShowUnexpError
    
     /* funcion que se encarga de agregar una nueva fila en la grilla */
    function eventsAddNewRow() {

        isAdding        = true;
        isReSelecting	= true;
        selectedRow     = newRowId;
        eventsGrid.addRow( newRowId, newRowData );
        eventsGrid.selectRowById( selectedRow,false,true,true );
        eventsGrid.showRow( selectedRow );	

    }// fin de la funcion eventsAddNewRow
    
    /* funcion que configura los botones del menu */
    function eventsMenuButtonSetup(){
        
        //deshabilitado todos los botones
        eventsMenu.setItemDisabled("addEvent");
        eventsMenu.setItemDisabled("editEvent");
        eventsMenu.setItemDisabled("removeEvent");
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = eventsFormContainer.getViewName();
        
        if(activeView == "msg" || canChangeForm || isSavingForm || isAdding){
            return true;
        }
        
        //Se habilita todos los botones.
        eventsMenu.setItemEnabled("addEvent");
        eventsMenu.setItemEnabled("editEvent");
        eventsMenu.setItemEnabled("removeEvent");
        
    }//fin de la funcion eventsMenuButtonSetup
    
    /* funcion que se encarga de mostrar una ventana modal que advierte
     * al usuario si desea eliminar el registro el registro actual */
    function eventsRemoveDialog(){
        dhtmlx.message({
            title: rmvQuestTitle,
            type:  "confirm-warning",
            text:  rmvQuestText,
            cancel:rmvQuestCancel,
            ok:    rmvQuestOk,
            callback: function(res) {
                if (res) {
                    eventsForm.setItemValue("op", "remove");
                    eventsForm.save();
                } 
            }
        });
    }//fin de la funcion eventsRemoveDialog

/* END FUNCTIONS */
    
}//fin de la funcion eventsInit
</script>
</head>
<body>
<div id="eventsLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>

