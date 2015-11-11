

$(document).ready(function () {

	/*----------------------Se cargan los controles -----------------*/
	
	$("#frm_grupo_usuario #grupo_precio_cab_id").on('change', function(event){
		$('#grid_grupoprecio_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		$('#grid_grupoprecio_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		PrecioGrupo_ConsultarInfoDispoGrupoCab($("#frm_grupo_usuario #grupo_precio_cab_id").val());
		return false;		
	});
	
	
	

	$("#frm_grupo_usuario #btn_asignar_grupo").on('click', function(event){ 
		PrecioGrupo_asignarGrupo();
		return false;
	});	

	
	$("#frm_grupo_usuario #btn_eliminar_grupo").on('click', function(event){ 
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
		colNames:['ID','CLIENTE','USUARIO'],
		colModel:[
			{name:'id',index:'id', width:70, sorttype:"integer", hidden:true},
			{name:'nombre',index:'nombre', width:150, sorttype:"string"},
			{name:'usuario_nombre',index:'usuario_nombre', width:150, sorttype:"string"}
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
			grupo_precio_cab_id: 	function() {return $("#frm_grupo_usuario #grupo_precio_cab_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['ID','CLIENTE','USUARIO','GRUPO DISPO','ESTADO'],
		colModel:[
			{name:'id',index:'id', width:150, sorttype:"integer", hidden:true},
			{name:'nombre',index:'nombre', width:150, sorttype:"string"},
			{name:'usuario_nombre',index:'usuario_nombre', width:150, sorttype:"string"},						
			{name:'grupo_dispo_nombre',index:'grupo_dispo_nombre', width:150, sorttype:"string"},
			{name:'estado',index:'estado', width:60, sorttype:"string", hidden:true}
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
									$("#frm_grupo_usuario #info_grupo_precio_cab").html(response.row.inventario_id+' - '+response.row.calidad_nombre+' - '+response.row.calidad_clasifica_fox );
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function PrecioGrupo_ConsultarInfoDispoGrupoCab
	
	
	function PrecioGrupo_asignarGrupo()
	{
		var grid = $("#frm_grupo_usuario #grid_grupoprecio_noasignados");
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
			element.id 		= selectedIDs[i];
			arr_data.push(element);
		}//end for
		
		var data = {
				formData: {
							'grupo_precio_cab_id': $("#frm_grupo_usuario #grupo_precio_cab_id").val(),
						  },			
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../seguridad/usuario/vinculargrupoprecio',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
							$('#frm_grupo_usuario #grid_grupoprecio_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							$('#frm_grupo_usuario #grid_grupoprecio_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);
	}//end function DispoGrupo_asignarGrupo	
	
	
	
	
	function PrecioGrupo_eliminarGrupo()
	{
		var grid = $("#frm_grupo_usuario #grid_grupoprecio_asignados");
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
							'grupo_precio_cab_id': $("#frm_grupo_usuario #grupo_precio_cab_id").val(),
						  },			
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../seguridad/usuario/desvinculargrupoprecio',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							//message_info('Mensaje del Sistema',"Datos Grabados con éxito");
							$('#frm_grupo_usuario #grid_grupoprecio_noasignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							$('#frm_grupo_usuario #grid_grupoprecio_asignados').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);
	}//end function DispoGrupo_eliminarGrupo
	
	
