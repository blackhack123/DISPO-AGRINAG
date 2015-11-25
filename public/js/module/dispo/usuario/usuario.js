


$(document).ready(function () {
	
	//RECARGA LA GRILLA 
	$("#frm_busqueda_usuario").submit(function( event ) {
		  event.preventDefault();
		  $('#grid_usuario_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}); 
	
	$("#frm_busqueda_usuario #btn_consultar").on('click', function(event){ 
		$('#grid_usuario_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});
	
	
	//EVENTOS NUEVO - GRABAR - CONSULTAR
	 $("#frm_nuevo_usuario #btn_nuevo").on('click', function(event){
			nuevo(); 
			return false;
     }); 
	 
	 
	 $("#frm_busqueda_agencia #btn_edit").on('click', function(event){ 
			var tr 			= $(this).closest('tr');
			var id 			= tr.attr('id');
	
			consultar_agencia_carga(id);
			//$("#dialog_mantenimiento").modal('show') 
    });
	   
     $("#frm_mantenimiento_usuario #btn_grabar").on('click', function(event){ 
     	grabar();
			return false;
     });  
     
     
     $("#frm_busqueda_usuario #btn_excel").on('click', function(event){ 
     	Usuario_ExportarExcel();
 		return false;
 	});	
     
  
	
	/*---------------------------------------------------------------*/
	/*--------Se configura los JQGRID's AGENCIA_CARGA----------------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_usuario_listado").jqGrid({
		url:'../../seguridad/usuario/listadodata',
		postData: {
			nombre:  			function() { return $("#frm_busqueda_usuario #criterio_busqueda").val(); }, 
			estado: 			function() { return $("#frm_busqueda_usuario #busqueda_estado").val(); },
			perfil: 			function() { return $("#frm_busqueda_usuario #busqueda_perfil_id").val(); }
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['CODIGO','NOMBRE','USUARIO','LOGIN FOX','EMAIL','PERFIL', 'ESTADO',''],
		colModel:[
			{name:'id',index:'id', width:60, align:"left", sorttype:"int", hidden:true},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			{name:'username',index:'username', width:100, sorttype:"string"},	
			{name:'login_fox',index:'login_fox', width:80, sorttype:"string", align: 'left' },	
			{name:'email',index:'email', width:130, sorttype:"string", align:"left"},
			{name:'perfil_nombre',index:'perfil_nombre', width:80, sorttype:"string", align:"left"},
			{name:'estado',index:'estado', width:60, sorttype:"string", align:"center", hidden:true},
			{name:'btn_editar',index:'', width:30, align:"center", formatter: gridUsuarioListado_FormatterEdit,
			cellattr: function () { return ' title=" Modificar"'; }
			},
		],
		rowNum:999999,
		pager: '#pager_usuario_listado',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		gridview:false,	
		multiselect: false,
		jsonReader: {
			repeatitems : false,
		},		
		/*caption:"Grilla de Prueba",*/
		afterInsertRow : function(rowid, rowdata){
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, true, {color:'red'});
			}//end if
		},
		
		ondblClickRow: function (rowid,iRow,iCol,e) {
				var data = $('#grid_usuario_listado').getRowData(rowid);
				console.log('ID:',data.id);
				consultar(data.id);
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
	

	
	function gridUsuarioListado_FormatterSincronizado(cellvalue, options, rowObject)
	{	
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
	}//end function gridUsuarioListado_FormatterSincronizado
	
	
	function gridUsuarioListado_FormatterEdit(cellvalue, options, rowObject)
	{
		var id = rowObject.id;	
		new_format_value = '<a href="javascript:void(0)" onclick="consultar(\''+id+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange"></i></a>'; 
		return new_format_value
		
	}//end function gridUsuarioListado_FormatterEdit
	
	
	jQuery("#grid_usuario_listado").jqGrid('navGrid','#pager_usuario_listado',{edit:false,add:false,del:false});

	
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
});

	
	function nuevo()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/seguridad/usuario/nuevodata',
							'url':'../../seguridad/usuario/nuevodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									ValidateControlsInit();
									$("#accion").val("I");
									$("#dialog_mantenimiento_usuario_nombre").html("NUEVO REGISTRO");
	
									$("#id").val('');
									$("#id").prop('readonly',false);
									$("#nombre").val('');
									$("#username").val('');
									$("#password").val('');
									$("#email").val('');
									$("#login_fox").val('');
									$("#perfil_id").html(response.cbo_perfil_id);
									$("#estado").html(response.cbo_estado);
									$("#lbl_usuario_ing").html('');
									$("#lbl_fec_ingreso").html('');
									$("#lbl_usuario_mod").html('');
									$("#lbl_fec_modifica").html('');									
									$("#dialog_mantenimiento").modal('show')
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function nuevo
	
	function consultar(id)
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{id: id}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/seguridad/usuario/consultardata',
							'url':'../../seguridad/usuario/consultardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									mostrar_registro(response)
									cargador_visibility('hide');
	
									$("#dialog_mantenimiento").modal('show')
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function consultar_usuario
	
	
	
	function ValidateControlAdicional()
	{
		//Validacion Customizada
		var valor1 = $("#password").val();
		var valor2 = $("#password2").val();
		var accion = $("#accion").val(); 
	
		if (accion == 'I'){
			if ($("#password").val()==''){
				mensaje = 'Debe ingresar una clave';
				return mensaje;
			}
			if ($("#password2").val()==''){
				mensaje = 'Debe ingresar la confirmacion de la clave';
				return mensaje;
			}//end if
		}//end if
	
		if ($("#password").val()!=''){
			if ($("#password").val() != $("#password2").val()){
				mensaje = 'La contraseña no coincide';
				return mensaje;
			}//end if
		}//end if
		if ($("#password2").val()!=''){
			if ($("#password").val() != $("#password2").val()){
				mensaje = 'La contraseña no coincide';
				return mensaje;
			}//end if
		}//end if
	
		return 'OK';
	}//end function ValidateControlAdicional
	
	
	
	function grabar(){
	
		if (!ValidateControls('frm_mantenimiento_usuario')) {
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
					 	id: $("#id").val(),
					 	nombre: $("#nombre").val(),
					 	username: $("#username").val(),					 	
					 	password: $("#password").val(),
					 	password2: $("#password2").val(),
					 	login_fox: $("#login_fox").val(),
					 	email: $("#email").val(),
					 	perfil_id: $("#perfil_id").val(),
					 	estado: $("#estado").val(),
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/seguridad/usuario/grabardata',
							'url':'../../seguridad/usuario/grabardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										mostrar_registro(response)
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
										$("#dialog_mantenimiento").modal('hide');
										 $('#grid_usuario_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
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
		$("#dialog_mantenimiento").modal('hide');		
		return false;		
	}//end function grabar
	
	
	function mostrar_registro(response)
	{
		var row = response.row;
		if (row!==null)
		{
			ValidateControlsInit();
			$("#accion").val("M");			
			$("#dialog_mantenimiento_usuario_nombre").html(row.nombre);
			$("#frm_mantenimiento_usuario #id").val(row.id);
			$("#frm_mantenimiento_usuario #id").prop('readonly',true);
			$("#frm_mantenimiento_usuario #nombre").val(row.nombre);
			$("#frm_mantenimiento_usuario #username").val(row.username);
			$("#frm_mantenimiento_usuario #password").val('');
			$("#frm_mantenimiento_usuario #password2").val('');
			$("#frm_mantenimiento_usuario #login_fox").val(row.login_fox);
			$("#frm_mantenimiento_usuario #email").val(row.email);
			$("#frm_mantenimiento_usuario #perfil_id").html(response.cbo_perfil_id);
			$("#frm_mantenimiento_usuario #estado").html(response.cbo_estado);
			$("#frm_mantenimiento_usuario #lbl_usuario_ing").html(row.usuario_ing_user_name);
			$("#frm_mantenimiento_usuario #lbl_fec_ingreso").html(row.fec_ingreso);
			$("#frm_mantenimiento_usuario #lbl_usuario_mod").html(row.usuario_mod_user_name);
			$("#frm_mantenimiento_usuario #lbl_fec_modifica").html(row.fec_modifica);
	
		}//end if
	}//end function mostrar_registro


	function Usuario_ExportarExcel()
	{
		cargador_visibility('show');

		var url = '../../seguridad/usuario/exportarexcel';
		var params = '?criterio_busqueda='+$("#frm_busqueda_usuario #criterio_busqueda").val()+
					 '&estado='+$("#frm_busqueda_usuario #busqueda_estado").val()+
				 	 '&perfil='+$("#frm_busqueda_usuario #busqueda_perfil_id").val();
		url = url + params;
		var win = window.open(url);
		
		cargador_visibility('hide');
	}//end function DispoGeneral_ExportarExcel
	
	
	