/**
 * 
 */


$(document).ready(function () {
	
	//RECARGA LA GRILLA 
	$("#frm_busqueda_agencia").submit(function( event ) {
		  event.preventDefault();
		  $('#grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}); 
	
	$("#frm_busqueda_agencia #btn_consultar").on('click', function(event){ 
		$('#grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});
	
	
	//EVENTOS NUEVO - GRABAR - CONSULTAR
	 $("#frm_nueva_agencia #btn_nuevo").on('click', function(event){
			nuevo(); 
			return false;
     }); 
	 
	 
	 $("#frm_busqueda_agencia #btn_edit").on('click', function(event){ 
			var tr 			= $(this).closest('tr');
			var id 			= tr.attr('id');
	
			consultar_agencia_carga(id);
			//$("#dialog_mantenimiento").modal('show') 
    });
	   
     $("#frm #btn_grabar").on('click', function(event){ 
     	grabar();
			return false;
     });  
     
     
     $("#frm_busqueda_agencia #btn_excel").on('click', function(event){ 
     	AgenciaCarga_ExportarExcel();
 		return false;
 	});	
     
  
	
	/*---------------------------------------------------------------*/
	/*--------Se configura los JQGRID's AGENCIA_CARGA----------------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_agenciacarga_listado").jqGrid({
		url:'../../dispo/agenciacarga/listadodata',
		postData: {
			nombre:  			function() { return $("#frm_busqueda_agencia #criterio_busqueda").val(); }, 
			estado: 			function() { return $("#frm_busqueda_agencia #busqueda_estado").val(); }				
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Código','Nombre','Telefono','Tipo','','Fec. Sincronizado', 'Estado',''],
		colModel:[
			{name:'id',index:'id', width:50, align:"center", sorttype:"int"},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			{name:'telefono',index:'telefono', width:100, sorttype:"string"},	
			{name:'tipo',index:'tipo', width:80, sorttype:"string", align: 'center',formatter:gridAgenciacargaListado_FormatterTipo },	
			{name:'sincronizado',index:'sincronizado', width:30, align:"center", sorttype:"number", formatter: gridAgenciacargaListado_FormatterSincronizado},
			{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
			{name:'estado',index:'estado', width:60, sorttype:"string", align:"center", hidden:true},
			{name:'btn_editar_agenciacarga',index:'', width:30, align:"center", formatter: gridAgenciacargaListado_FormatterEdit,
			cellattr: function () { return ' title=" Modificar"'; }
			},
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
		
		afterInsertRow : function(rowid, rowdata){
			if (rowdata.estado == "I"){
				$(this).jqGrid('setRowData', rowid, false, {color:'red'});
			}//end if
		},
		
		ondblClickRow: function (rowid,iRow,iCol,e) {
				var data = $('#grid_agenciacarga_listado').getRowData(rowid);
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
	

	
	//Se configura el grid para que pueda navegar procesar la fila con el ENTER
	jQuery("#grid_agenciacarga_listado").jqGrid('bindKeys', {
		   "onEnter" : function( rowid ) { 
				//consultar_listado(rowid);
				var data = $('#grid_agenciacarga_listado').getRowData(rowid);
				consultar_listado("+data.id+");
		   }
	});


	function gridAgenciacargaListado_FormatterTipo(cellvalue, options, rowObject)
	{	
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
		
	}//end function gridAgenciacargaListado_FormatterTipo	
	
	
	
	function gridAgenciacargaListado_FormatterSincronizado(cellvalue, options, rowObject)
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
	}//end function gridAgenciacargaListado_FormatterSincronizado
	
	
	function gridAgenciacargaListado_FormatterEdit(cellvalue, options, rowObject)
	{
		
		var id = rowObject.id;	
		//new_format_value = '<a href="javascript:void(0)" onclick="consultar_listado(\''+marcacion_sec+'\')"><img src="<?php echo($this->basePath()); ?>/images/edit.png" border="0" /></a> ';
		new_format_value = '<a href="javascript:void(0)" onclick="consultar(\''+id+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange"></i></a>'; 
		return new_format_value
		
	}//end function gridAgenciacargaListado_FormatterEdit
	
	
	$('#grid_agenciacarga_listado').setGroupHeaders(
			{
				useColSpanStyle: true,
				groupHeaders: [{ "numberOfColumns": 2, "titleText": "Sincronizacion", "startColumnName": "sincronizado" }]
	});
	
	jQuery("#grid_agenciacarga_listado").jqGrid('navGrid','#pager_agenciacarga_listado',{edit:false,add:false,del:false});

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
							//'url':'<?php echo($this->basePath()); ?>/dispo/agenciacarga/nuevodata',
							'url':'../../dispo/agenciacarga/nuevodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									ValidateControlsInit();
									$("#accion").val("I");
									$("#dialog_mantenimiento_agenciacarga_nombre").html("NUEVO REGISTRO");
	
									$("#id").val('');
									$("#id").prop('readonly',false);
									$("#nombre").val('');
									$("#direccion").val('');
									$("#telefono").val('');
									$("#tipo").html(response.cbo_tipo);
									$("#estado").html(response.cbo_estado);
									$("#lbl_fec_sincronizado").html('');
									$("#lbl_usuario_ing").html('');
									$("#lbl_fec_ingreso").html('');
									$("#lbl_usuario_mod").html('');
									$("#lbl_fec_modifica").html('');
									$("#sincronizado_pendiente").show();
									$("#sincronizado_ok").hide();
									
									$("#dialog_mantenimiento").modal('show')
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function nuevo





	

	function grabar(){
		if (!ValidateControls('frm')) {
			return false;
		}//end FuncionGrabar
		
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{	accion: $("#accion").val(),
					 	id: $("#id").val(),
					 	nombre: $("#nombre").val(),
					 	direccion: $("#direccion").val(),
					 	telefono: $("#telefono").val(),
					 	tipo: $("#tipo").val(),
					 	estado: $("#estado").val(),
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/dispo/agenciacarga/grabardata',
							'url':'../../dispo/agenciacarga/grabardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										mostrar(response)
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
										$('#grid_agenciacarga_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
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

	
	function consultar(id)
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{id_agencia_carga: id}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/dispo/agenciacarga/consultardata',
							'url':'../../dispo/agenciacarga/consultardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									mostrar(response)
									cargador_visibility('hide');
									$("#dialog_mantenimiento").modal('show')
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function consultar_agencia_carga

	function mostrar(response)
	{
		var row = response.row;
		if (row!==null)
		{
			ValidateControlsInit();
			$("#accion").val("M");			
			$("#dialog_mantenimiento_agenciacarga_nombre").html(row.nombre);
			$("#id").val(row.id);
			$("#id").prop('readonly',true);
			$("#nombre").val(row.nombre);
			$("#direccion").val(row.direccion);
			$("#telefono").val(row.telefono);
			$("#tipo").html(response.cbo_tipo);
			$("#estado").html(response.cbo_estado);
			$("#lbl_fec_sincronizado").html(row.fec_sincronizado);
			$("#lbl_usuario_ing").html(row.usuario_ing_user_name);
			$("#lbl_fec_ingreso").html(row.fec_ingreso);
			$("#lbl_usuario_mod").html(row.usuario_mod_user_name);
			$("#lbl_fec_modifica").html(row.fec_modifica);
			
			if (row.sincronizado==1)
			{
				$("#sincronizado_pendiente").hide();
				$("#sincronizado_ok").show();
			}else{
				$("#sincronizado_pendiente").show();
				$("#sincronizado_ok").hide();
			}//end if
		}//end if
	}//end function mostrar



	function AgenciaCarga_ExportarExcel()
	{
		cargador_visibility('show');

		var url = '../../dispo/agenciacarga/exportarexcel';
		var params = '?criterio_busqueda='+$("#frm_busqueda_agencia #criterio_busqueda").val()+
					 '&estado='+$("#frm_busqueda_agencia #busqueda_estado").val()+
				 	 '&sincronizado='+$("#frm_busqueda_agencia #busqueda_sincronizado").val();
		url = url + params;
		var win = window.open(url);
		
		cargador_visibility('hide');
	}//end function DispoGeneral_ExportarExcel
	
	
	