/**
 * 
 */


$(document).ready(function () {
	
	
	$("#frm_agenciacarga_listado #btn_asignar_cliente_agencia_carga").on('click', function(event){ 
		grabar_cliente_agencia_carga();
		return false;
	}); 
	
	$("#frm_agenciacarga_listado #btn_eliminar_cliente_agencia_carga").on('click', function(event){ 
		eliminar_cliente_agencia_carga();
		return false;
	}); 
	
	/*---------------------------------------------------------------*/
	/*-----------------Se configura los JQGRID's AG. CARGA-----------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_agenciacarga_listado").jqGrid({
		url:'../../dispo/agenciacarga/listadodata',
		//postData: {
		//	nombre: function() { return $("#frm_agenciacarga_listado #busqueda_nombre").val(); },
		//	estado: function() { return $("#frm_agenciacarga_listado #busqueda_estado").val(); }				
		//},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Código','Nombre','Tipo'],
		colModel:[
			//{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'id',index:'id', width:50, align:"center", sorttype:"int"},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			//{name:'telefono',index:'telefono', width:100, sorttype:"string"},	
			{name:'tipo',index:'tipo', width:80, sorttype:"string", align: 'center',formatter:gridAgenciacargaListado_FormatterTipo }	
			//{name:'sincronizado',index:'sincronizado', width:30, align:"center", sorttype:"number", formatter: gridAgenciacargaListado_FormatterSincronizado},
			//{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
			//{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
			//{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			// cellattr: function () { return ' title=" Modificar"'; }
			//},
		],
		rowNum:999999,
		pager: '#pager_agenciacarga_listado',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		gridview:false,	
		multiselect: true,
		caption: "AGENCIAS DISPONIBLES",
		jsonReader: {
			repeatitems : false,
		},		
		/*caption:"Grilla de Prueba",*/
		afterInsertRow : function(rowid, rowdata){
			//console.log('rowdata',rowdata);
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, true, {color:'red'});
			}//end if
		},
		
		//ondblClickRow: function (rowid,iRow,iCol,e) {
		//		var data = $('#grid_agenciacarga_listado').getRowData(rowid);				
		//		agenciacarga_consultar(data.id)
		//		//return false;
		//},
		
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
		


	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_agenciacarga_listado").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_agencia_listado').getRowData(rowid);
				consultar_listado("+data.id+");
		   }
	});



	function gridAgenciacargaListado_FormatterTipo(cellvalue, options, rowObject){	
		switch (rowObject.tipo)
		{
		case "A":
			new_format_value = 'AGENCIA'; 
			break;
		case "B":
			new_format_value = 'AMBAS';
			break;
		case "C":
			new_format_value = 'CUARTO FRIO';
			break;
		default :
			new_format_value = 'AGENCIA'; 
			break;
		}//end switch
		return new_format_value;
	}//end function ListadoMarcacion_FormatterSincronizado	
	

	jQuery("#grid_agenciacarga_listado").jqGrid('navGrid','#pager_agenciacarga_listado',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	/*---------------------------------------------------------------*/
	/*--------Se configura los JQGRID's CLIENTE_AGENCIA_CARGA--------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_cliente_agenciacarga_listado").jqGrid({
		url:'../../dispo/clienteagenciacarga/listadodata',
		postData: {
			cliente_id: function()   { return $("#frm_informacion_general #cliente_id").val(); }, 
		//	estado: function() { return $("#frm_agenciacarga_listado #busqueda_estado").val(); }				
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Código','Nombre','Tipo'],
		colModel:[
			//{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'id',index:'id', width:50, align:"center", sorttype:"int"},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			//{name:'telefono',index:'telefono', width:100, sorttype:"string"},	
			{name:'tipo',index:'tipo', width:80, sorttype:"string", align: 'center',formatter:gridAgenciacargaListado_FormatterTipo }	
			//{name:'sincronizado',index:'sincronizado', width:30, align:"center", sorttype:"number", formatter: gridAgenciacargaListado_FormatterSincronizado},
			//{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
			//{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
			//{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			// cellattr: function () { return ' title=" Modificar"'; }
			//},
		],
		rowNum:999999,
		pager: '#pager_cliente_agenciacarga_listado',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		gridview:false,	
		multiselect: true,
		caption: "AGENCIAS ASIGNADAS",
		jsonReader: {
			repeatitems : false,
		},		
		/*caption:"Grilla de Prueba",*/
		afterInsertRow : function(rowid, rowdata){
			//console.log('rowdata',rowdata);
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, true, {color:'red'});
			}//end if
		},
		
		//ondblClickRow: function (rowid,iRow,iCol,e) {
		//		var data = $('#grid_agenciacarga_listado').getRowData(rowid);				
		//		agenciacarga_consultar(data.id)
		//		//return false;
		//},
		
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
	

	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_cliente_agenciacarga_listado").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_cliente_agenciacarga_listado').getRowData(rowid);
				consultar_listado("+data.id+");
		   }
	});


	function gridAgenciacargaListado_FormatterTipo(cellvalue, options, rowObject){	
		switch (rowObject.tipo)
		{
		case "A":
			new_format_value = 'AGENCIA'; 
			break;
		case "B":
			new_format_value = 'AMBAS';
			break;
		case "C":
			new_format_value = 'CUARTO FRIO';
			break;
		default :
			new_format_value = 'AGENCIA'; 
			break;
		}//end switch
		return new_format_value;
	}//end function ListadoMarcacion_FormatterSincronizado	
	

	jQuery("#grid_cliente_agenciacarga_listado").jqGrid('navGrid','#pager_cliente_agenciacarga_listado',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	
	
});





/**
 * ---------------------------------------------------------------------------------------------
 *			FUNCIONES AGENCIA CARGA
 *---------------------------------------------------------------------------------------------
 */

	
	function agenciacarga_listar(limpiar_filtros)
	{
		$('#frm_agenciacarga_listado #grid_agenciacarga_listado').jqGrid("clearGridData");		
		$('#frm_agenciacarga_listado #grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function agenciacarga_listar

	
	function cliente_agenciacarga_listar(limpiar_filtros)
	{
		$('#frm_agenciacarga_listado #grid_cliente_agenciacarga_listado').jqGrid("clearGridData");
		$('#frm_agenciacarga_listado #grid_cliente_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function cliente_agenciacarga_listar


	/**
	 * ---------------------------------------------------------------------------------------------
	 *			FUNCIONES CLIENTE AGENCIA CARGA
	 *---------------------------------------------------------------------------------------------
	 */
	
	function grabar_cliente_agencia_carga(respuesta)
	{
	
		var grid = $("#frm_agenciacarga_listado #grid_agenciacarga_listado");
		var rowKey = grid.getGridParam("selrow");
		
		if (!rowKey)
		{
			alert("SELECCIONE UNA AGENCIA PARA ASIGNARLA");
			return false;
		}
		
		
		var selectedIDs = grid.getGridParam("selarrrow");
		console.log(selectedIDs);
		
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) {
			var element	= {};
			element.agencia_carga_id 		= selectedIDs[i];
			arr_data.push(element);
		}//end for
		
		var data = {
			formData: {
						'cliente_id': $("#frm_informacion_general #cliente_id").val(),
					  },			
			grid_data: 	arr_data,
		};			
	
	
		var parameters = {	'type': 'post',
							'contentType': 'application/json',
							'url':'../../dispo/clienteagenciacarga/vincular',
							'show_cargando':true,
							'finish':function(response){
									if (response.respuesta_code=='OK'){
										//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
										$('#frm_agenciacarga_listado #grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
										$('#frm_agenciacarga_listado #grid_cliente_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
									}else{
										message_error('ERROR', response);
									}//end if
							}
						 }
		ajax_call(parameters, data);		
	
	}//end function grabar_cliente_agencia_carga
	
	
	
	function eliminar_cliente_agencia_carga()
	{
  
		var grid = $("#grid_cliente_agenciacarga_listado");
        var rowKey = grid.getGridParam("selrow");

        if (!rowKey)
            alert("SELECCIONE UNA AGENCIA PARA ELIMINARLA");
        else {
            var selectedIDs = grid.getGridParam("selarrrow");
            var result = "";
            for (var i = 0; i < selectedIDs.length; i++) {
                result += selectedIDs[i] + ",";
               
            }

            alert(result);
        } 
		
	}//end function grabar_cliente_agencia_carga


	
	
	