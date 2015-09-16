

$(document).ready(function () {

	/*----------------------Se cargan los controles -----------------*/
	dispoGrupo_init();
	
	$("#frm_dispo_grupo #grupo_dispo_cab_id").on('change', function(event){
		$('#grid_grupodispo_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		$('#grid_grupodispo_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		DispoGrupo_ConsultarInfoDispoGrupoCab($("#frm_dispo_grupo #grupo_dispo_cab_id").val());
		return false;		
	});
	
	
	$("#frm_dispo_grupo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	

	$("#frm_dispo_grupo #btn_nuevo").on('click', function(event){ 
		nuevo(); 
		return false;
	});		
	
	$("#frm_dispo_grupo #editar_dispo_grupo").on('click', function(event){ 	
		$("#frm_dispo_grupo_mantenimiento #accion").val('M');
		DispoGrupo_Consultar($("#frm_dispo_grupo #grupo_dispo_cab_id").val())	
	});
	
	
	$("#frm_dispo_grupo #btn_asignar_grupo").on('click', function(event){ 
		DispoGrupo_asignarGrupo();
		return false;
	});	

	
	$("#frm_dispo_grupo #btn_eliminar_grupo").on('click', function(event){ 
		DispoGrupo_eliminarGrupo();
		return false;
	});	
	
	
	$("#frm_dispo_grupo_mantenimiento #btn_grabar").on('click', function(event){ 
		DispoGrupo_GrabarRegistro();
		return false;
	});	
	
	
	/*---------------------------------------------------------------*/	
	
	/*---------------------------------------------------------------*/
	/*------Se configura los JQGRID's GRUPO DISPO ASIGNADOS ---------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_grupodispo_noasignados").jqGrid({
		url:'../../dispo/grupodispo/listadogrupodisponoasignadosdata',
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['CLIENTE','USUARIO'],
		colModel:[
			//{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'cliente_nombre',index:'cliente_nombre', width:150, sorttype:"string"},
			{name:'usuario_nombre',index:'usuario_nombre', width:230, sorttype:"string"}
			//{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
			//{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			// cellattr: function () { return ' title=" Modificar"'; }
			//},
		],
		rowNum:999999,
		pager: '#pager_grupodispo_noasignados',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		gridview:false,	
		multiselect: true,
		caption: "DISPONIBLES",
		jsonReader: {
			repeatitems : false,
		},		
		/*caption:"Grilla de Prueba",*/
	/*	afterInsertRow : function(rowid, rowdata){
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, true, {color:'red'});
			}//end if
		},
	*/
		
		
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
		
	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_grupodispo_noasignados").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_grupodispo_noasignados').getRowData(rowid);
				consultar_listado("+data.id+");
		   }
	});


	jQuery("#grid_grupodispo_noasignados").jqGrid('navGrid','#pager_grupodispo_noasignados',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	
	
	/*---------------------------------------------------------------*/
	/*----- Se configura los JQGRID's GRUPO DISPO ASIGNADOS ---------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_grupodispo_asignados").jqGrid({
		url:'../../dispo/grupodispo/listadogrupodispoasignadosdata',
		postData: {
			grupo_dispo_cab_id: 	function() {return $("#frm_dispo_grupo #grupo_dispo_cab_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['CLIENTE','USUARIO','ESTADO'],
		colModel:[
			//{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'cliente_nombre',index:'cliente_nombre', width:150, sorttype:"string"},
			{name:'usuario_nombre',index:'usuario_nombre', width:230, sorttype:"string"},
			{name:'estado',index:'estado', width:60, sorttype:"string", hidden:true},
			//{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			// cellattr: function () { return ' title=" Modificar"'; }
			//},
		],
		rowNum:999999,
		pager: '#pager_grupodispo_asignados',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		gridview:false,	
		multiselect: true,
		caption: "ASIGNADOS",
		jsonReader: {
			repeatitems : false,
		},		
		afterInsertRow : function(rowid, rowdata){
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, true, {color:'red'});
			}//end if
		},
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
	jQuery("#grid_grupodispo_asignados").jqGrid('navGrid','#pager_grupodispo_asignados',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/	
	
	});
	

	function dispoGrupo_init()
	{
		$("#frm_dispo_grupo #info_grupo_dispo_cab").html('');
		
		var data = 	{
						opcion: 'panel-grupo-clientes',
						grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
						//grupo_dispo_cab_id:			grupo_dispo_cab_id;
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response){	
								//grupodispo_listar();
								$("#frm_dispo_grupo #grupo_dispo_cab_id").html(response.grupo_dispo_opciones);
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function dispoGrupo_init
	
	

	function DispoGrupo_ConsultarInfoDispoGrupoCab(grupo_dispo_cab_id)
	{
		var data = 	{
						grupo_dispo_cab_id:		grupo_dispo_cab_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/consultarcabecera',
							'control_process':true,
							'show_cargando':false,
							'async':true,
							'finish':function(response){
								if (response.respuesta_code == 'OK')
								{
									$("#frm_dispo_grupo #info_grupo_dispo_cab").html(response.row.inventario_id+' - '+response.row.calidad_nombre+' - '+response.row.calidad_clasifica_fox );
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function DispoGrupo_ConsultarInfoDispoGrupoCab
	
	
	function nuevo()
	{
		var data = 	{
					
				inventario_opciones:	'&lt;SELECCIONE&gt;',
				calidad_opciones:		'&lt;SELECCIONE&gt;'
				
					}		
		
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/nuevodata',
							'control_process':true,
							'show_cargando':true,
							
							'finish':function(response){	
								$("#accion").val("I");
								$("#dialog_dispo_grupo_mantenimiento_titulo").html("NUEVO REGISTRO");
								$("#frm_dispo_grupo_mantenimiento #id").val('');
								$("#frm_dispo_grupo_mantenimiento #nombre").val('');
								$("#frm_dispo_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
								$("#frm_dispo_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
								
								$('#dialog_dispo_grupo_mantenimiento').modal('show')								
							 }							
				           }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function nuevo
	
	
	function DispoGrupo_GrabarRegistro()
	{
		if (!ValidateControls('frm_dispo_grupo_mantenimiento')) 
		{
			return false;
		}//end if
				
		var accion  		= $("#frm_dispo_grupo_mantenimiento #accion").val();
		var id  			= $("#frm_dispo_grupo_mantenimiento #id").val();
		var nombre  		= $("#frm_dispo_grupo_mantenimiento #nombre").val();
		var inventario_id 	= $("#frm_dispo_grupo_mantenimiento #inventario_id").val();
		var calidad_id  	= $("#frm_dispo_grupo_mantenimiento #calidad_id").val();

		var data = 	{
						accion:			accion,
						id:				id,
						nombre:			nombre,
						inventario_id:	inventario_id,
						calidad_id:		calidad_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/grabardata',
							'control_process':true,
							'show_cargando':false,
							'async':true, 
							'finish':function(response){
									if ($("#frm_dispo_grupo_mantenimiento #accion").val()=='I'){
										dispoGrupo_init();
									}//end if
									MostrarRegistro(response);
									
									cargador_visibility('hide');
									swal({  title: "Informacion grabada con exito!!",   
										//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
										//html:true,
										type: "success",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "OK",
										cancelButtonText: "",
										closeOnConfirm: false,
										closeOnCancel: false,
									});
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;			
	}//end function DispoGrupo_GrabarRegistro
	


	function MostrarRegistro(response)
	{
		var row = response.row;
		
		if (row==null) return false;
		
		$("#frm_dispo_grupo_mantenimiento #accion").val("M");
		$("#frm_dispo_grupo_mantenimiento #id").val(row.id);
		$("#frm_dispo_grupo_mantenimiento #nombre").val(row.nombre);
		$("#frm_dispo_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
		$("#frm_dispo_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
	}//end function DispoGrupo_MostrarRegistro
	
	
	function DispoGrupo_Consultar(id)
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{grupo_dispo_cab_id:id}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/consultarregistrodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									DispoGrupo_MostrarRegistro(response);
									cargador_visibility('hide');

									$('#dialog_dispo_grupo_mantenimiento').modal('show')
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function DispoGrupo_Consultar
	
	function grupodispo_listar(limpiar_filtros)
	{
		$('#frm_dispo_grupo #grid_grupodispo_noasignados').jqGrid("clearGridData");		
		$('#frm_dispo_grupo #grid_grupodispo_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function agenciacarga_listar
	
	
	
	
	function DispoGrupo_asignarGrupo()
	{
		var grid = $("#frm_dispo_grupo #grid_grupodispo_noasignados");
        var rowKey = grid.getGridParam("selrow");

        if (!rowKey)
        	{
        		alert("SELECCIONE UN USUARIO PARA VINCULARLO");
        		return false;
        	}
        
        var selectedIDs = grid.getGridParam("selarrrow");
		
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) {
			var element				= {};
			element.usuario_id 		= selectedIDs[i];
			arr_data.push(element);
		}//end for
		
		var data = {
				formData: {
							'grupo_dispo_cab_id': $("#frm_dispo_grupo #grupo_dispo_cab_id").val(),
						  },			
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../seguridad/usuario/vinculargrupodispo',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
							$('#frm_dispo_grupo #grid_grupodispo_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							$('#frm_dispo_grupo #grid_grupodispo_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);
	}//end function DispoGrupo_asignarGrupo	
	
	
	
	
	function DispoGrupo_eliminarGrupo()
	{
		var grid = $("#frm_dispo_grupo #grid_grupodispo_asignados");
        var rowKey = grid.getGridParam("selrow");

        if (!rowKey)
        	{
        		alert("SELECCIONE UN USUARIO PARA DESVINCULARLO");
        		return false;
        	}
        
        var selectedIDs = grid.getGridParam("selarrrow");
		
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) {
			var element				= {};
			element.usuario_id 		= selectedIDs[i];
			arr_data.push(element);
		}//end for
		
		var data = {
				formData: {
							'grupo_dispo_cab_id': $("#frm_dispo_grupo #grupo_dispo_cab_id").val(),
						  },			
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../seguridad/usuario/desvinculargrupodispo',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
							$('#frm_dispo_grupo #grid_grupodispo_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							$('#frm_dispo_grupo #grid_grupodispo_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);
	}//end function DispoGrupo_eliminarGrupo