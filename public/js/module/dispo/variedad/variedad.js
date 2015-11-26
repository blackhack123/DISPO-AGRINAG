


$(document).ready(function () {
	
	//RECARGA LA GRILLA 
	$("#frm_busqueda").submit(function( event ) {
		  event.preventDefault();
		  $('#grid_variedad_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}); 
	
	$("#frm_busqueda #btn_consultar").on('click', function(event){ 
		$('#grid_variedad_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});
	
	
	//EVENTOS NUEVO - GRABAR - CONSULTAR
	 $("#frm_nuevo #btn_nuevo").on('click', function(event){
			nuevo(); 
			return false;
     }); 
	 
	 
     $("#frm_mantenimiento_variedad #btn_grabar").on('click', function(event){ 
     	grabar();
			return false;
     });  
     
     
     $("#frm_busqueda #btn_excel").on('click', function(event){ 
    	 Variedad_ExportarExcel();
 		return false;
 	});	
     
  
	
	/*---------------------------------------------------------------*/
	/*-----------Se configura los JQGRID's VARIEDADES----------------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_variedad_listado").jqGrid({
		url:'../../dispo/variedad/listadodata',
		postData: {
			criterio_busqueda: 	function() { return $("#frm_busqueda #criterio_busqueda").val(); }, 
			estado: 			function() { return $("#frm_busqueda #busqueda_estado").val(); },
			color_ventas_id: 	function() { return $("#frm_busqueda #busqueda_color").val(); }
		},
		datatype: "json",
		loadonce: true,			
		colNames:['Codigo','Nombre','','Color Base','Color Venta','Solido', 'Real','','Fec. Ultima Vez','Estado',''],
		colModel:[
			{name:'id',index:'id', width:60, align:"left", sorttype:"int", hidden:true},
			{name:'nombre',index:'nombre', width:230, sorttype:"string"},
			{name:'url_ficha',index:'url_ficha', width:30, sorttype:"string", formatter: gridVariedadListado_FormatterLink},	
			{name:'color_base_nombre',index:'color_base_nombre', width:120, sorttype:"string", align: 'left' },	
			{name:'color_venta',index:'color_venta', width:100, sorttype:"string", align:"left"},
			{name:'solido',index:'solido', width:60, sorttype:"string", align:"left", formatter: gridVariedadListado_FormatterSolido},
			{name:'es_real',index:'es_real', width:40, sorttype:"string", align:"center", formatter: gridVariedadListado_FormatterEsReal},
			{name:'sincronizado',index:'sincronizado', width:60, sorttype:"string", align:"center", formatter: gridVariedadListado_FormatterSincronizado},
			{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
			{name:'estado',index:'estado', width:60, sorttype:"string", align:"center", hidden:true},
			{name:'btn_editar',index:'', width:30, align:"center", formatter: gridVariedadListado_FormatterEdit,
			cellattr: function () { return ' title=" Modificar"'; }
			},
		],
		rowNum:999999,
		pager: '#pager_variedad_listado',
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
			var data = $('#grid_variedad_listado').getRowData(rowid);
			consultar(data.id);
		},
		
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
		
	});
	
	
	$('#grid_variedad_listado').setGroupHeaders({
			useColSpanStyle: true,
			groupHeaders: [{ "numberOfColumns": 2, "titleText": "Sincronizacion", "startColumnName": "sincronizado" }]
	});
	
	
	function gridVariedadListado_FormatterLink(cellvalue, options, rowObject){
		var id = rowObject.url_ficha;	
		var new_format_value = '';
		if (rowObject.url_ficha === undefined || rowObject.url_ficha === null || rowObject.url_ficha=='')
		{
			new_format_value = ' ';
		}else{
			new_format_value = '<a href="javascript:void(0)" onclick="window.open(\''+rowObject.url_ficha+'\',this.target,\'scrollbars=yes,resizable=yes,height=600,width=1000,left=100,top=100\')"><i class="glyphicon glyphicon-camera" style="color:green"></i></a>'; 
		}//end if
		
		return new_format_value;
	}//end function ListadoCliente_FormatterEdit
	
	
	function gridVariedadListado_FormatterSolido(cellvalue, options, rowObject)
	{	
		switch (rowObject.solido)
		{
		case 'S':
			new_format_value = '<span class="glyphicon glyphicon-ok icon-white" style="color:green"></span>'; 
			break;
		case 'N':
			new_format_value = '<span class="glyphicon glyphicon-remove icon-white" style="color:red"></span>';
			break;
		default :
			new_format_value = ' '; 
			break;
		}//end switch
		return new_format_value;
		
	}//end function gridVariedadListado_FormatterSolido
	
	function gridVariedadListado_FormatterEsReal(cellvalue, options, rowObject)
	{	
		switch (rowObject.es_real)
		{
		case 'S':
			new_format_value = '<span class="glyphicon glyphicon-ok icon-white" style="color:green"></span>'; 
			break;
		case 'N':
			new_format_value = '<span class="glyphicon glyphicon-remove icon-white" style="color:red"></span>';
			break;
		default :
			new_format_value = ' '; 
			break;
		}//end switch
		return new_format_value;
		
	}//end function gridVariedadListado_FormatterEsReal
	
	
	function gridVariedadListado_FormatterSincronizado(cellvalue, options, rowObject)
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
	
	
	function gridVariedadListado_FormatterEdit(cellvalue, options, rowObject)
	{
		var id = rowObject.id;	
		new_format_value = '<a href="javascript:void(0)" onclick="consultar(\''+id+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange"></i></a>'; 
		return new_format_value
		
	}//end function gridVariedadListado_FormatterEdit
	
	
	jQuery("#grid_variedad_listado").jqGrid('navGrid','#pager_variedad_listado',{edit:false,add:false,del:false});

	
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
});


	function nuevo()
	{
		var data = 	{}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/dispo/variedad/nuevodata',
							'url':'../../dispo/variedad/nuevodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									ValidateControlsInit();
									$("#accion").val("I");
									$("#dialog_mantenimiento_variedad_nombre").html("NUEVO REGISTRO");
	
									$("#variedad_id").val('');
									$("#variedad_id").prop('readonly',false);
									$("#nombre").val('');
									$("#nombre_tecnico").val('');
									$("#calidad_variedad_id").html(response.cbo_calidad_variedad_id);
									$("#color").html(response.cbo_color);
									$("#color2").html(response.cbo_color2);
									$("#grupo_color_id").html(response.cbo_grupo_color_id);
									$("#colorbase").html(response.cbo_color_base);
									$("#solido").html(response.cbo_solido);
									$("#es_real").html(response.cbo_es_real);
									$("#est_producto_especial").val('');
									$("#mensaje").val('');
									$("#cultivada").html(response.cbo_cultivada);
									$("#ciclo_prod").val('');
									$("#obtentor_id").html(response.cbo_obtentor_id);
									$("#tamano_bunch_id").html(response.cbo_tamano_bunch);
									$("#color_ventas_id").html(response.cbo_color_ventas);
									$("#url_ficha").val('');
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
		if (!ValidateControls('frm_mantenimiento_variedad')) {
			return false;
		}
	
		var est_producto_especial = ($("#est_producto_especial").is(':checked') ? 1 : 0);
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{	accion: $("#accion").val(),
					 	id: $("#variedad_id").val(),
					 	nombre: $("#nombre").val(),
					 	nombre_tecnico: $("#nombre_tecnico").val(),
					 	calidad_variedad_id: $("#calidad_variedad_id").val(),
					 	color: $("#color").val(),
					 	color2: $("#color2").val(),
					 	grupo_color_id: $("#grupo_color_id").val(),
					 	colorbase: $("#colorbase").val(),
					 	solido:$("#solido").val(),
					 	es_real:$("#es_real").val(),
					 	est_producto_especial: est_producto_especial,
					 	tamano_bunch_id:$("#tamano_bunch_id").val(),
					 	url_ficha:$("#url_ficha").val(),
					 	color_ventas_id:$("#color_ventas_id").val(),
					 	mensaje:$("#mensaje").val(),
					 	cultivada:$("#cultivada").val(),
					 	ciclo_prod: $("#ciclo_prod").val(),
					 	obtentor_id: $("#obtentor_id").val(),
					 	//producto_id: $("#producto_id").val(),
					 	estado: $("#estado").val(),
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/dispo/variedad/grabardata',
							'url':'../../dispo/variedad/grabardata',
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
										$('#grid_variedad_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
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
		var data = 	{variedad_id: id}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							//'url':'<?php echo($this->basePath()); ?>/dispo/variedad/consultardata',
							'url':'../../dispo/variedad/consultardata',
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
	}//end function consultar
	
	function mostrar_registro(response)
	{
		var row = response.row;
		if (row!==null)
		{
			ValidateControlsInit();
			$("#accion").val("M");			
			$("#dialog_mantenimiento_variedad_nombre").html(row.nombre);
			$("#variedad_id").val(row.id);
			$("#variedad_id").prop('readonly',true);
			$("#nombre").val(row.nombre);
			$("#nombre_tecnico").val(row.nombre_tecnico);
			$("#calidad_variedad_id").html(response.cbo_calidad_variedad_id);
			$("#color").html(response.cbo_color);
			$("#color2").html(response.cbo_color2);
			$("#grupo_color_id").html(response.cbo_grupo_color_id);
			$("#tamano_bunch_id").html(response.cbo_tamano_bunch);
			$("#colorbase").html(response.cbo_color_base);
			$("#solido").html(response.cbo_solido);
			$("#es_real").html(response.cbo_es_real);
			$("#mensaje").val(row.mensaje);
			$("#cultivada").html(response.cbo_cultivada);
			$("#ciclo_prod").val(row.ciclo_prod);
			$("#obtentor_id").html(response.cbo_obtentor_id);
			$("#color_ventas_id").html(response.cbo_color_ventas);
			$("#url_ficha").val(row.url_ficha);
			if (row.est_producto_especial=='1')
			{
			    $("#est_producto_especial").prop('checked', true);
			}
			else
			{
			    $("#est_producto_especial").prop('checked', false);
			}
			$("#estado").html(response.cbo_estado);
			$("#lbl_fec_ingreso").html(row.fec_ingreso);
			$("#lbl_fec_modifica").html(row.fec_modifica);
			$("#lbl_usuario_ing").html(row.usuario_ing_user_name);
			$("#lbl_usuario_mod").html(row.usuario_mod_user_name);
			$("#lbl_fec_sincronizado").html(row.fec_sincronizado);
			
	
			if (row.sincronizado==1)
			{
				$("#sincronizado_pendiente").hide();
				$("#sincronizado_ok").show();
			}else{
				$("#sincronizado_pendiente").show();
				$("#sincronizado_ok").hide();
			}//end if
		}//end if
	}//end function mostrar_registro
	
	
	function Variedad_ExportarExcel()
	{
		cargador_visibility('show');
	
		var url = '../../dispo/variedad/exportarexcel';
		var params = '?criterio_busqueda='+$("#frm_busqueda #criterio_busqueda").val()+
					 '&busqueda_estado='+$("#frm_busqueda #busqueda_estado").val()+
				 	 '&busqueda_color='+$("#frm_busqueda #busqueda_color").val();
		url = url + params;
		var win = window.open(url);
		
		cargador_visibility('hide');
	}//end function Variedad_ExportarExcel
	
	
	