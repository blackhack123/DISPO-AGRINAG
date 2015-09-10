/**
 * 
 */


$(document).ready(function () {
	
	$("#frm_agenciacarga_listado #btn_consultar_agencia_carga").on('click', function(event){ 
		$('#grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});
	
	$("#frm_agenciacarga_listado #btn_nueva_agencia_carga").on('click', function(event){
		agenciacarga_nueva(); 
		return false;
	});

	/*$("#frm_agenciacarga_listado #btn_editar_agenciacarga").on('click', function(event){ 
		var tr 						= $(this).closest('tr');
		var id						= tr.attr('id');
		agenciacarga_consultar(id);
		//$("#dialog_mantenimiento").modal('show') 
	});	*/
	
	$("#frm_agencia_carga #btn_grabar_agencia_carga").on('click', function(event){ 
		agenciacarga_grabar();
		return false;
	});     
	/*
	$("#frm_agenciacarga_listado #btn_consultar_agencia").on('click', function(event){    
		agenciacarga_listar(false);
	}); */
	/*---------------------------------------------------------------*/
	/*-----------------Se configura los JQGRID's AG. CARGA-----------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_agenciacarga_listado").jqGrid({
		url:'../../dispo/agenciacarga/listadodata',
		postData: {
			nombre: function() { return $("#frm_agenciacarga_listado #busqueda_nombre").val(); },
			estado: function() { return $("#frm_agenciacarga_listado #busqueda_estado").val(); }				
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['','Código','Nombre','Telefono','Tipo','','Fec. Ultima Vez','Estado', ''],
		colModel:[
			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'id',index:'id', width:50, align:"center", sorttype:"int"},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			{name:'telefono',index:'telefono', width:100, sorttype:"string"},	
			{name:'tipo',index:'tipo', width:80, sorttype:"string", align: 'center',formatter:gridAgenciacargaListado_FormatterTipo },	
			{name:'sincronizado',index:'sincronizado', width:30, align:"center", sorttype:"number", formatter: gridAgenciacargaListado_FormatterSincronizado},
			{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
			{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
			{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			   cellattr: function () { return ' title=" Modificar"'; }
			},
		],
		rowNum:999999,
		pager: '#pager_agenciacarga_listado',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
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
		ondblClickRow: function (rowid,iRow,iCol,e) {
				var data = $('#grid_agenciacarga_listado').getRowData(rowid);				
				agenciacarga_consultar(data.id)
				//return false;
		},
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
		

	$('#grid_agenciacarga_listado').setGroupHeaders(
	{
		useColSpanStyle: true,
		groupHeaders: [{ "numberOfColumns": 2, "titleText": "Sincronizacion", "startColumnName": "sincronizado" }]
	});		
	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_agenciacarga_listado").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_agencia_listado').getRowData(rowid);
				consultar_listado("+data.id+");
		   }
	});


	function gridAgenciacargaListado_FormatterEdit(cellvalue, options, rowObject){
		var id = rowObject.id;	
		//new_format_value = '<a href="javascript:void(0)" onclick="consultar_listado(\''+agenciacarga_sec+'\')"><img src="<?php echo($this->basePath()); ?>/images/edit.png" border="0" /></a> ';
		new_format_value = '<a href="javascript:void(0)" onclick="agenciacarga_consultar(\''+id+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange" id="btn_editar_agenciacarga" ></i></a>'; 
		return new_format_value
	}//end function ListadoMarcacion_FormatterSincronizado

	
	function gridAgenciacargaListado_FormatterSincronizado(cellvalue, options, rowObject){	
		switch (rowObject.sincronizado)
		{
		case '0':
			new_format_value = '<span class="glyphicon glyphicon-time icon-white" style="color:red">'; 
			break;
		case '1':
			new_format_value = '<i class="glyphicon glyphicon-ok icon-white" style="color:green">';
			break;
		default :
			new_format_value = '<span class="glyphicon glyphicon-time icon-white" style="color:red">'; 
			break;
		}//end switch
		return new_format_value;
	}//end function ListadoMarcacion_FormatterSincronizado	


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
	
});





/**
 * ---------------------------------------------------------------------------------------------
 *			FUNCIONES AGENCIA CARGA
 *---------------------------------------------------------------------------------------------
 */

 function agenciacarga_nueva()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/agenciacarga/nuevodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									ValidateControlsInit();
									$("#frm_agencia_carga #accion").val("I");
									$("#dialog_nueva_agenciacarga_nombre").html("NUEVO REGISTRO");

									$("#agencia_carga_id").val('');
									$("#agencia_carga_id").prop('readonly',false);
									$("#nombre").val('');
									$("#direccion").val('');
									$("#telefono").val('');
									$("#frm_agencia_carga #tipo").html(response.cbo_tipo);
									$("#frm_agencia_carga #estado").html(response.cbo_estado);
									$("#lbl_fec_sincronizado").html('');
									$("#lbl_usuario_ing").html('');
									$("#lbl_fec_ingreso").html('');
									$("#lbl_usuario_mod").html('');
									$("#lbl_fec_modifica").html('');
									$("#sincronizado_pendiente").show();
									$("#sincronizado_ok").hide();
									
									$("#dialog_nueva_agenciacarga").modal('show');
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function nuevo



 function agenciacarga_grabar(){
		if (!ValidateControls('frm_agencia_carga')) {
			return false;
		}//end FuncionGrabar
		
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{	accion: $("#frm_agencia_carga #accion").val(),
					 	id: $("#frm_agencia_carga #agencia_carga_id").val(),
					 	nombre: $("#frm_agencia_carga #nombre").val(),
					 	direccion: $("#frm_agencia_carga #direccion").val(),
					 	telefono: $("#frm_agencia_carga #telefono").val(),
					 	tipo: $("#frm_agencia_carga #tipo").val(),
					 	estado: $("#frm_agencia_carga #estado").val(),
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/agenciacarga/grabardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										agenciacarga_mostrar_registro(response)
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
											/*timer: 2000*/
										});
									}else{
										swal({title: response.respuesta_mensaje,   
											//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
											//html:true,
											type: "error",
											showCancelButton: false,
											confirmButtonColor: "#DD6B55",
											confirmButtonText: "OK",
											cancelButtonText: "",
											closeOnConfirm: false,
											closeOnCancel: false,
											/*timer: 2000*/
										});
									
									}									
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function grabar


	function agenciacarga_consultar(id)
	{
	
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{id:id}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/agenciacarga/consultardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
								agenciacarga_mostrar_registro(response);
								//	listar_marcacion(true);
									cargador_visibility('hide');
									
									$("#dialog_nueva_agenciacarga").modal('show');
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;
	}//end function agenciacarga_consultar

	

	function agenciacarga_mostrar_registro(response)
	{
		var row = response.row;
		if (row!==null)
		{
		//	ValidateControlsInit();
			$("#frm_agencia_carga #accion").val("M");			
			$("#dialog_nueva_agenciacarga_nombre").html(row.nombre);
			$("#frm_agencia_carga #agencia_carga_id").val(row.id);
			$("#frm_agencia_carga #agencia_carga_id").prop('readonly',true);
			$("#frm_agencia_carga #nombre").val(row.nombre);
			$("#frm_agencia_carga #direccion").val(row.direccion);
			$("#frm_agencia_carga #telefono").val(row.telefono);
			$("#frm_agencia_carga #tipo").html(response.cbo_tipo);
			$("#frm_agencia_carga #estado").html(response.cbo_estado);
			$("#frm_agencia_carga #lbl_fec_ingreso").html(row.fec_ingreso);
			$("#frm_agencia_carga #lbl_fec_modifica").html(row.fec_modifica);
			$("#frm_agencia_carga #lbl_usuario_ing").html(row.usuario_ing_user_name);
			$("#frm_agencia_carga #lbl_usuario_mod").html(row.usuario_mod_user_name);
			$("#frm_agencia_carga #lbl_fec_sincronizado").html(row.fec_sincronizado);
			
			
			
			
			if (row.sincronizado==1)
			{
				$("#frm_agencia_carga #sincronizado_pendiente").hide();
				$("#frm_agencia_carga #sincronizado_ok").show();
			}else{
				$("#frm_agencia_carga #sincronizado_pendiente").show();
				$("#frm_agencia_carga #sincronizado_ok").hide();
			}//end if
		}//end if
	}//end function agenciacarga_mostrar_registro
	
	function agenciacarga_listar(limpiar_filtros)
	{
		$('#frm_agenciacarga_listado #grid_agenciacarga_listado').jqGrid("clearGridData");
		
		if (limpiar_filtros==true)
		{
			$("#frm_agenciacarga_listado #busqueda_nombre").val("");
			$("#frm_agenciacarga_listado #busqueda_estado").val("");
		}//end if
		
		$('#frm_agenciacarga_listado #grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function listar_agenciacarga


