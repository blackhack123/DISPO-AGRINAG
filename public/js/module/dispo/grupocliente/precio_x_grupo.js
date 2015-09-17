

$(document).ready(function () {

	/*----------------------Se cargan los controles -----------------*/
	
	$("#frm_grupo_precio #grupo_precio_cab_id").on('change', function(event){
		$('#grid_grupoprecio_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		$('#grid_grupoprecio_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		PrecioGrupo_ConsultarInfoDispoGrupoCab($("#frm_grupo_precio #grupo_precio_cab_id").val());
		return false;		
	});
	
	
	
	$("#frm_grupo_precio #btn_nuevo_grupo_precio").on('click', function(event){ 
		nuevo_grupo_precio(); 
		return false;
	});		
	
	$("#frm_grupo_precio #btn_modificar_grupo_precio").on('click', function(event){ 
		$("#frm_precio_grupo_mantenimiento #accion").val('M');
		PrecioGrupo_Consultar($("#frm_grupo_precio #grupo_precio_cab_id").val())	
	});
	
	
	$("#frm_grabar_precio_grupo #btn_grabar_grupo_precio").on('click', function(event){ 
		grabar_grupo_precio();
		return false;
	});	
	
	

	$("#frm_grupo_precio #btn_asignar_grupo").on('click', function(event){ 
		PrecioGrupo_asignarGrupo();
		return false;
	});	

	
	$("#frm_grupo_precio #btn_eliminar_grupo").on('click', function(event){ 
		PrecioGrupo_eliminarGrupo();
		return false;
	});	
	
	
	
	
	/*---------------------------------------------------------------*/	
	
	/*---------------------------------------------------------------*/
	/*------Se configura los JQGRID's GRUPO PRECIO NOASIGNADOS ------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_grupoprecio_noasignados").jqGrid({
		url:'../../dispo/grupoprecio/listadogrupoprecionoasignadosdata',
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['ID','CLIENTE'],
		colModel:[
			//{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'id',index:'id', width:230, sorttype:"integer", hidden:true},
			{name:'nombre',index:'nombre', width:150, sorttype:"string"}
			//{name:'usuario_nombre',index:'usuario_nombre', width:230, sorttype:"string"}
			//{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
			//{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			// cellattr: function () { return ' title=" Modificar"'; }
			//},
		],
		rowNum:999999,
		pager: '#pager_grupoprecio_noasignados',
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

		
		
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
		
	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_grupoprecio_noasignados").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_grupoprecio_noasignados').getRowData(rowid);
				consultar_listado("+data.id+");
		   }
	});


	jQuery("#grid_grupoprecio_noasignados").jqGrid('navGrid','#pager_grupoprecio_noasignados',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/	
	
	/*---------------------------------------------------------------*/
	/*----- Se configura los JQGRID's GRUPO PRECIO ASIGNADOS ---------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_grupoprecio_asignados").jqGrid({
		url:'../../dispo/grupoprecio/listadogrupoprecioasignadosdata',
		postData: {
			grupo_precio_cab_id: 	function() {return $("#frm_grupo_precio #grupo_precio_cab_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['ID','CLIENTE','ESTADO'],
		colModel:[
			{name:'id',index:'id', width:150, sorttype:"integer", hidden:true},
			{name:'nombre',index:'nombre', width:150, sorttype:"string"},
			{name:'estado',index:'estado', width:60, sorttype:"string", hidden:true},
		],
		rowNum:999999,
		pager: '#pager_grupoprecio_asignados',
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
	jQuery("#grid_grupoprecio_asignados").jqGrid('navGrid','#pager_grupoprecio_asignados',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/		
	
	
});
	


	function PrecioGrupo_ConsultarInfoDispoGrupoCab(grupo_precio_cab_id)
	{
		var data = 	{
						grupo_precio_cab_id:		grupo_precio_cab_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/consultarcabecera',
							'control_process':true,
							'show_cargando':false,
							'async':true,
							'finish':function(response){
								if (response.respuesta_code == 'OK')
								{
									$("#frm_grupo_precio #info_grupo_precio_cab").html(response.row.inventario_id+' - '+response.row.calidad_nombre+' - '+response.row.calidad_clasifica_fox );
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function PrecioGrupo_ConsultarInfoDispoGrupoCab
	

	function nuevo_grupo_precio()
	{
		var data = 	{
					
				inventario_opciones:	'&lt;SELECCIONE&gt;',
				calidad_opciones:		'&lt;SELECCIONE&gt;'
				
					}		
		
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/nuevodata',
							'control_process':true,
							'show_cargando':true,
							
							'finish':function(response){	
								$("#frm_precio_grupo_mantenimiento #accion").val("I");
								$("#dialog_precio_grupo_mantenimiento_titulo").html("NUEVO REGISTRO");
								$("#frm_precio_grupo_mantenimiento #id").val('');
								$("#frm_precio_grupo_mantenimiento #nombre").val('');
								$("#frm_precio_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
								$("#frm_precio_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
								
								$('#dialog_precio_grupo_mantenimiento').modal('show');								
							 }							
				           }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function nuevo


	function grabar_grupo_precio()
	{
		if (!ValidateControls('frm_precio_grupo_mantenimiento')) 
		{
			return false;
		}//end if
				
		var accion  		= $("#frm_precio_grupo_mantenimiento #accion").val();
		var id  			= $("#frm_precio_grupo_mantenimiento #id").val();
		var nombre  		= $("#frm_precio_grupo_mantenimiento #nombre").val();
		var inventario_id 	= $("#frm_precio_grupo_mantenimiento #inventario_id").val();
		var calidad_id  	= $("#frm_precio_grupo_mantenimiento #calidad_id").val();

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
							'url':'../../dispo/grupoprecio/grabardata',
							'control_process':true,
							'show_cargando':false,
							'async':true, 
							'finish':function(response){
									if ($("#frm_precio_grupo_mantenimiento #accion").val()=='I'){
										dispoGrupo_init();
									}//end if
									PrecioMostrarRegistro(response);
									$("#frm_grupo_precio #grupo_precio_cab_id").html(response.grupo_precio_opciones);
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
	}//end function grabar_grupo_precio



	function PrecioMostrarRegistro(response)
	{
		var row = response.row;
		
		if (row==null) return false;
		
		$("#dialog_precio_grupo_mantenimiento_titulo").html(row.nombre);
		$("#dialog_precio_grupo_mantenimiento #accion").val("M");
		$("#dialog_precio_grupo_mantenimiento #id").val(row.id);
		$("#dialog_precio_grupo_mantenimiento #nombre").val(row.nombre);
		$("#dialog_precio_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
		$("#dialog_precio_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
	}//end function PrecioMostrarRegistro
	
	
	function PrecioGrupo_Consultar(id)
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{grupo_precio_cab_id:id}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/consultarregistrodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
								PrecioMostrarRegistro(response);
									cargador_visibility('hide');

									$('#dialog_precio_grupo_mantenimiento').modal('show');
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function PrecioGrupo_Consultar
	


	
	function PrecioGrupo_asignarGrupo()
	{
		var grid = $("#frm_grupo_precio #grid_grupoprecio_noasignados");
        var rowKey = grid.getGridParam("selrow");

        if (!rowKey)
        	{
        		alert("SELECCIONE UN CLIENTE PARA VINCULARLO");
        		return false;
        	}
        
        var selectedIDs = grid.getGridParam("selarrrow");
		
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) {
			var element				= {};
			element.id 		= selectedIDs[i];
			arr_data.push(element);
		}//end for
		
		var data = {
				formData: {
							'grupo_precio_cab_id': $("#frm_grupo_precio #grupo_precio_cab_id").val(),
						  },			
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../dispo/cliente/vinculargrupoprecio',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
							$('#frm_grupo_precio #grid_grupoprecio_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							$('#frm_grupo_precio #grid_grupoprecio_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);
	}//end function DispoGrupo_asignarGrupo	
	
	
	
	
	function PrecioGrupo_eliminarGrupo()
	{
		var grid = $("#frm_grupo_precio #grid_grupoprecio_asignados");
        var rowKey = grid.getGridParam("selrow");

        if (!rowKey)
        	{
        		alert("SELECCIONE UN CLIENTE PARA DESVINCULARLO");
        		return false;
        	}
        
        var selectedIDs = grid.getGridParam("selarrrow");
		
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) {
			var element				= {};
			element.id 		= selectedIDs[i];
			arr_data.push(element);
		}//end for
		
		var data = {
				formData: {
							'grupo_precio_cab_id': $("#frm_grupo_precio #grupo_precio_cab_id").val(),
						  },			
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../dispo/cliente/desvinculargrupoprecio',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
							$('#frm_grupo_precio #grid_grupoprecio_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							$('#frm_grupo_precio #grid_grupoprecio_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);
	}//end function DispoGrupo_eliminarGrupo
	