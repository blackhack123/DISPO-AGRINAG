/**
 * 
 */


$(document).ready(function () {
	
	$("#frm_usuario_listado #btn_consultar_usuario").on('click', function(event){ 
		$('#grid_usuario_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});
	
	$("#frm_usuario_listado #btn_nuevo_usuario").on('click', function(event){
		usuario_nuevo(); 
		return false;
	});


	$("#frm_nuevo_usuario #btn_grabar_usuario").on('click', function(event){ 
		usuario_grabar();
		return false;
	});     

/*
	$("#frm_usuario_listado #btn_consultar_usuario").on('click', function(event){    
		listar_usuario(false);
	});

	$(".btn_editar_usuario").on('click', function(event){ 
		var tr 						= $(this).closest('tr');
		var id						= tr.attr('id');

		consultar_usuario(id);
		//$("#dialog_mantenimiento").modal('show') 
	});

	*/

	/*---------------------------------------------------------------*/
	/*-----------------Se configura los JQGRID's USUARIOS------------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_usuario_listado").jqGrid({
		url:'../../seguridad/usuario/listadodata',
		postData: {
			cliente_id: function()   { return $("#frm_informacion_general #cliente_id").val(); }, 
			nombre: function()		 { return $("#frm_usuario_listado #busqueda_nombre").val(); },
			estado: function() 		 { return $("#frm_usuario_listado #busqueda_estado").val(); }				
		},
		datatype: "json",
		loadonce: true,		
		gridview:false,	
		/*height:'400',*/
		colNames:['Código','Nombre','Usuario','Email','Estado', ''],
		colModel:[
			//{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'id',index:'marcacion_sec', width:50, align:"center", sorttype:"int"},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			{name:'username',index:'username', width:150, sorttype:"string"},	
			{name:'email',index:'email', width:150, sorttype:"string"},	
			//{name:'sincronizado',index:'sincronizado', width:30, align:"center", sorttype:"number", formatter: ListadoUsuario_FormatterSincronizado},
			//{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
			{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
			{name:'btn_editar_usuario',index:'', width:30, align:"center", formatter:ListadoUsuario_FormatterEdit,
			   cellattr: function () { return ' title=" Modificar"'; }
			},
		],
		rowNum:999999999,
		pager: '#pager_usuario_listado',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		rownumbers: true,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight,  
		jsonReader: {
			repeatitems : false,
		},		
		/*caption:"Grilla de Prueba",*/
		afterInsertRow : function(rowid, rowdata){
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, false, {color:'red'});
			}//end if
		},
		ondblClickRow: function (rowid,iRow,iCol,e) {
				var data = $('#grid_usuario_listado').getRowData(rowid);				
				usuario_consultar(data.id)
			//	return false;
		},
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	}).navGrid();

/*	$('#grid_usuario_listado').setGroupHeaders(
	{
		useColSpanStyle: true,
		groupHeaders: [{ "numberOfColumns": 2, "titleText": "Sincronizacion", "startColumnName": "sincronizado" }]
	});		*/
	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_usuario_listado").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_usuario_listado').getRowData(rowid);
				usuario_consultar("+data.id+");
		   }
	});


	function ListadoUsuario_FormatterEdit(cellvalue, options, rowObject){
		var id = rowObject.id;	
		//new_format_value = '<a href="javascript:void(0)" onclick="consultar_listado(\''+marcacion_sec+'\')"><img src="<?php echo($this->basePath()); ?>/images/edit.png" border="0" /></a> ';
		new_format_value = '<a href="javascript:void(0)" onclick="usuario_consultar(\''+id+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange" id="btn_editar_usuario" ></i></a>'; 
		return new_format_value
	}//end function ListadoMarcacion_FormatterSincronizado

	
	function ListadoUsuario_FormatterSincronizado(cellvalue, options, rowObject){	
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
	jQuery("#grid_usuario_listado").jqGrid('navGrid','#pager_usuario_listado',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
});



/**
 * ---------------------------------------------------------------------------------------------
 *			FUNCIONES USUARIO
 *---------------------------------------------------------------------------------------------
 */

	function usuario_nuevo()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../seguridad/usuario/nuevoclientedata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									ValidateControlsInit();
									$("#accion").val("I");
									$("#dialog_nuevo_usuario_nombre").html("NUEVO REGISTRO");

									$(" #usuario_id").val('');
									$("#usuario_id").prop('readonly',false);
									$("#frm_nuevo_usuario #nombre").val('');
									$("#frm_nuevo_usuario #username").val('');
									$("#frm_nuevo_usuario #password").val('');
									$("#frm_nuevo_usuario #email").val('');
									//$("#frm_nuevo_usuario #perfil_id").html(response.cbo_perfil_id);
									$("#frm_nuevo_usuario #grupo_dispo_cab_id").html(response.cbo_grupo_dispo);
									$("#frm_nuevo_usuario #grupo_precio_cab_id").html(response.cbo_grupo_precio);
									$("#frm_nuevo_usuario #inventario_id").html(response.cbo_inventario_id);
									$("#frm_nuevo_usuario #estado").html(response.cbo_estado);
									$("#frm_nuevo_usuario #lbl_usuario_ing").html('');
									$("#frm_nuevo_usuario #lbl_fec_ingreso").html('');
									$("#frm_nuevo_usuario #lbl_usuario_mod").html('');
									$("#frm_nuevo_usuario #lbl_fec_modifica").html('');									
									$("#dialog_nuevo_usuario").modal('show');
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function nuevo



	function ValidateControlAdicional()
	{
		//Validacion Customizada
		var valor1 = $("#frm_nuevo_usuario #password").val();
		var valor2 = $("#frm_nuevo_usuario #password2").val();
		var accion = $("#accion").val(); 

		if (accion == 'I'){
			if ($("#frm_nuevo_usuario #password").val()==''){
				mensaje = 'Debe ingresar una clave';
				return mensaje;
			}
			if ($("#frm_nuevo_usuario #password2").val()==''){
				mensaje = 'Debe ingresar la confirmacion de la clave';
				return mensaje;
			}//end if
		}//end if

		if ($("#frm_nuevo_usuario #password").val()!=''){
			if ($("#frm_nuevo_usuario #password").val() != $("#frm_nuevo_usuario #password2").val()){
				mensaje = 'La contraseña no coincide';
				return mensaje;
			}//end if
		}//end if
		if ($("#frm_nuevo_usuario #password2").val()!=''){
			if ($("#frm_nuevo_usuario #password").val() != $("#frm_nuevo_usuario #password2").val()){
				mensaje = 'La contraseña no coincide';
				return mensaje;
			}//end if
		}//end if

		return 'OK';
	}//end function ValidateControlAdicional


	function usuario_grabar(){

		if (!ValidateControls('frm_nuevo_usuario')) {
			return false;
		}//end FuncionGrabar

		var mensaje = ValidateControlAdicional();

		if (mensaje!='OK')
		{
			swal({  title: mensaje,   
				//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
				//html:true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "OK",
				cancelButtonText: "",
				closeOnConfirm: false,
				closeOnCancel: false,
				/*timer: 2000*/
			});			
			return false;
		}//end if
		
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{	accion: $("#accion").val(),
					 	id: $("#frm_nuevo_usuario #usuario_id").val(),
					 	cliente_id: $("#frm_informacion_general #cliente_id").val(),
					 	nombre: $("#frm_nuevo_usuario #nombre").val(),
					 	username: $("#frm_nuevo_usuario #username").val(),					 	
					 	password: $("#frm_nuevo_usuario #password").val(),
					 	password2: $("#frm_nuevo_usuario #password2").val(),
					 	email: $("#frm_nuevo_usuario #email").val(),
					 	//perfil_id: $("#frm_nuevo_usuario #perfil_id").val(),
					 	grupo_dispo_cab_id: $("#frm_nuevo_usuario #grupo_dispo_cab_id").val(),
					 	estado: $("#frm_nuevo_usuario #estado").val(),
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../seguridad/usuario/grabarclientedata',
							'control_process':true,
							//usuario_listar: (true),
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										mostrar_registro_usuario(response)
										usuario_listar (false);
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


	function mostrar_registro_usuario(response)
	{
		var row = response.row;
		if (row!==null)
		{
			ValidateControlsInit();
			$("#accion").val("M");			
			$("#dialog_nuevo_usuario_nombre").html(row.nombre);
			$("#frm_nuevo_usuario #usuario_id").val(row.id);
			$("#frm_nuevo_usuario #usuario_id").prop('readonly',true);
			$("#frm_nuevo_usuario #nombre").val(row.nombre);
			$("#frm_nuevo_usuario #username").val(row.username);
			$("#frm_nuevo_usuario #password").val('');
			$("#frm_nuevo_usuario #password2").val('');
			$("#frm_nuevo_usuario #email").val(row.email);
			//$("#frm_nuevo_usuario #perfil_id").html(response.cbo_perfil_id);
			$("#frm_nuevo_usuario #grupo_dispo_cab_id").html(response.cbo_grupo_dispo);
			$("#frm_nuevo_usuario #grupo_precio_cab_id").html(response.cbo_grupo_precio);
			$("#frm_nuevo_usuario #inventario_id").html(response.cbo_inventario_id);
			$("#frm_nuevo_usuario #estado").html(response.cbo_estado);
			$("#frm_nuevo_usuario #lbl_usuario_ing").html(row.usuario_ing_user_name);
			$("#frm_nuevo_usuario #lbl_fec_ingreso").html(row.fec_ingreso);
			$("#frm_nuevo_usuario #lbl_usuario_mod").html(row.usuario_mod_user_name);
			$("#frm_nuevo_usuario #lbl_fec_modifica").html(row.fec_modifica);

		}//end if
	}//end function mostrar_registro


	function usuario_consultar(id)
	{
	
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{id:id}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../seguridad/usuario/consultardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
								mostrar_registro_usuario(response);
									//usuario_listar(true);
									cargador_visibility('hide');
									
									$("#dialog_nuevo_usuario").modal('show');
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;
	}//end function usuario_consultar
	


	
	function usuario_listar(limpiar_filtros)
	{
		$('#frm_usuario_listado #grid_usuario_listado').jqGrid("clearGridData");
		
		if (limpiar_filtros==true)
		{
			$("#frm_usuario_listado #busqueda_nombre").val("");
			$("#frm_usuario_listado #busqueda_estado").val("");
		}//end if
		
		$('#frm_usuario_listado #grid_usuario_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function listar_agenciacarga
