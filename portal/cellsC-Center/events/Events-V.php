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
    
    /* Variables Generales */
    unexpMsg            = "<i>Unexpected Error</i>"; //Mensaje para los Erroes inexperados en el formulario
    selectedRow         = "";       // contiene el id a seleccionar en la grilla
    returnAction        = "";       // contiene la accion que retorna el CM ej: success y fail.
    returnTid           = "";       // contiene el UUID que retorna el CM para la Seleccion en la Grilla
    badReturn           = "";       // contiene el mensaje de ERROR que devuelve la data de la grilla

    /* Flags Generales */ 
    firstTime           = true;     // flag que verifica si al modulo se esat accediendo por priemra vez
    canChangeForm	= false;    // flag que indica si el formulario esta desbloqueado (true)
    isSavingForm	= false;    // flag que me indica que la forma de detalle esta guardando
    isReSelecting	= false;    // flag que indica la reSeleccion de una fila en la treeGrid 
    
    /* userdata Form header */
    headerForm          = "";
    headerFormCollapse  = "";
    
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
                eventsForm.clear();
                eventsForm.unlock();
                eventsForm.setItemFocus("NameOfClient");
                eventsForm.setItemValue("method", "submitDataForm");
                eventsForm.setItemValue("op", "add");
                break;
            case "editEvent":
                eventsForm.unlock();
                break;
            case "removeEvent":
                eventsForm.setItemValue("op", "remove");
                eventsForm.save();
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
    
    /* Evento onRowSelect de la Grilla */
    eventsGrid.attachEvent("onRowSelect", function(id){
        eventsFormSetup();
    });//fin del evento onRowSelect
    
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
                eventsForm.restoreBackup(eventsFormBackup);
                eventsForm.lock();
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
                        isSavingForm 	= false; 
                        canChangeForm 	= false; 
                        eventsGridCallback();                   //transfiere el control a la funcion
                        break;
                    case "fail":
                        isSavingForm = false;                   //la forma no esta en proceso de guardado
                        eventsFormContainer.showView("def");    //transfiere el control al usuario
                        eventsForm.reset();
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
    eventsMsgForm.loadStruct(eventsMsgFormXML);
   
/* END LOADS */

/* FUNCTIONS */
    
    /* funcion callback de la estructura del menu que se 
     * encarga de obtener los userdata con los header */
    function eventsMenuCallback(){
        
        headerForm          = eventsMenu.getUserData("sp3","headerForm");
        headerFormCollapse  = eventsMenu.getUserData("sp3","headerFormCollapse");
        
        //Se configura header del formualrio
        eventsFormContainer.setText(headerForm);
        eventsLayout.cells(formCell).setCollapsedText(headerFormCollapse);
        
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
            
            //Obtiene el id de la primera fila de la grilla
            selectedRow = eventsGrid.getRowId(0);

            //Selecciona la Fila (Dispara Evento onRowSelect). 
            eventsGrid.selectRowById(selectedRow,false,true,true);
            eventsGrid.showRow( selectedRow );	
         
        }//fin de la condicion que evalua el Error de Carga
        
        eventsLoadDependent();
        
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
        
    }//fin de la funcion eventsFormCallback
    
    /* funcion que manipula los datos que dependen de la carga de otro componente */
    function eventsLoadDependent(){
        
        if( returnAction == "error" ){
            eventsShowUnexpError();
        }else{
            /* Aqui se Situa componentes que requiere datos 
              de la carga de otro componente a la vez */
        }
        
    }//fin de la funcion eventsLoadDependent
    
    /* funcion que configura los inputs de la forma */
    function eventsFormSetup(){
        
        //Se verifica que la vista no sea la de mensajes de usuario
        var activeView = eventsFormContainer.getViewName();
        if( activeView != "def" ){
            eventsFormContainer.showView("def");
        }
        
        //Se bloquea la forma
        eventsForm.lock();
        
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
    
    /* funcion que configura los botones del menu */
    function eventsMenuButtonSetup(){
        //deshabilitado todos los botones
        eventsMenu.setItemEnabled("addEvent");
        eventsMenu.setItemEnabled("editEvent");
        eventsMenu.setItemEnabled("removeEvent"); 
    }//fin de la funcion eventsMenuButtonSetup

/* END FUNCTIONS */
    
}//fin de la funcion eventsInit
</script>
</head>
<body>
<div id="eventsLayoutDiv" style="position: fixed; height: 99%; width: 99%;"></div>
</body>
</html>

