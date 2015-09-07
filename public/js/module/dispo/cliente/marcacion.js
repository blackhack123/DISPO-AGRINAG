/**
 * 
 */


$(document).ready(function () {

		
		$("#frm_marcacion_listado #btn_nueva_marcacion").on('click', function(event){
			nuevamarcacion(); 
			return false;
		});
	
		$(".btn_editar_marcacion").on('click', function(event){ 
			var tr 						= $(this).closest('tr');
			var marcacion_sec 			= tr.attr('marcacion_sec');
	
			consultar_marcacion(marcacion_sec);
			//$("#dialog_mantenimiento").modal('show') 
		});
	
		$("#frm_marcacion #btn_grabar_marcacion").on('click', function(event){ 
			grabar_marcacion();
			return false;
		});    
		
		$("#frm_marcacion_listado #btn_consultar").on('click', function(event){    
			listar_marcacion(false);
		});
		
		/*---------------------------------------------------------------*/
		/*-----------------Se configura los JQGRID's---------------------*/
		/*---------------------------------------------------------------*/		
		jQuery("#grid_marcacion_listado").jqGrid({
			url:'<?php echo(./../dispo/marcacion/listadodata',
			postData: {
				cliente_id: function()   { return $("#frm_informacion_general #cliente_id").val(); }, 
				nombre: function()		 { return $("#frm_marcacion_listado #busqueda_nombre").val(); },
				estado: function() 		 { return $("#frm_marcacion_listado #busqueda_estado").val(); }				
			},
			datatype: "json",
			loadonce: true,		
			gridview:false,	
			/*height:'400',*/
			colNames:['','Código','Nombre','Pais','','Fec. Ultima Vez','Estado', ''],
			colModel:[
				{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
				{name:'marcacion_sec',index:'marcacion_sec', width:50, align:"center", sorttype:"int"},
				{name:'nombre',index:'nombre', width:230, sorttype:"string"},
				{name:'pais_nombre',index:'pais_nombre', width:150, sorttype:"string"},	
				{name:'sincronizado',index:'sincronizado', width:30, align:"center", sorttype:"number", formatter: ListadoMarcacion_FormatterSincronizado},
				{name:'fec_sincronizado',index:'fec_sincronizado', width:130, sorttype:"string", align:"center"},
				{name:'estado',index:'estado', width:60, sorttype:"string", align:"center"},
				{name:'btn_editar_marcacion',index:'', width:30, align:"center", formatter:ListadoMarcacion_FormatterEdit,
				   cellattr: function () { return ' title=" Modificar"'; }
				},
			],
			rowNum:999999999,
			pager: '#pager_marcacion_listado',
			toppager:false,
			pgbuttons:false,
			pginput:false,
			rowList:false,
			jsonReader: {
				repeatitems : false,
			},		
			
			/*caption:"Grilla de Prueba",*/
			afterInsertRow : function(rowid, rowdata){
				//console.log('rowdata:',rowdata);
				if (rowdata.estado == "I"){
					$(this).jqGrid('setRowData', rowid, false, {color:'red'});
				}//end if
			},
			ondblClickRow: function (rowid,iRow,iCol,e) {
					var data = $('#grid_marcacion_listado').getRowData(rowid);				
					consultar_marcacion(data.marcacion_sec)
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
			
		$('#grid_marcacion_listado').setGroupHeaders(
		{
			useColSpanStyle: true,
			groupHeaders: [{ "numberOfColumns": 2, "titleText": "Sincronizacion", "startColumnName": "sincronizado" }]
		});		
		
		//Se configura el grid para que pueda navegar procesar la fila con el ENTER
		jQuery("#grid_marcacion_listado").jqGrid('bindKeys', {
			   "onEnter" : function( rowid ) { 
					//consultar_listado(rowid);
					var data = $('#grid_marcacion_listado').getRowData(rowid);
					consultar_listado("+data.marcacion_sec+");
			   }
		});
	
	
		function ListadoMarcacion_FormatterEdit(cellvalue, options, rowObject){
			var marcacion_sec = rowObject.marcacion_sec;	
			//new_format_value = '<a href="javascript:void(0)" onclick="consultar_listado(\''+marcacion_sec+'\')"><img src="<?php echo($this->basePath()); ?>/images/edit.png" border="0" /></a> ';
			new_format_value = '<a href="javascript:void(0)" onclick="consultar_marcacion(\''+marcacion_sec+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange" id="btn_editar_marcacion" ></i></a>'; 
			return new_format_value
		}//end function ListadoMarcacion_FormatterSincronizado

		
		function ListadoMarcacion_FormatterSincronizado(cellvalue, options, rowObject){	
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
		jQuery("#grid_marcacion_listado").jqGrid('navGrid','#pager_marcacion_listado',{edit:false,add:false,del:false});

		/*---------------------------------------------------------------*/	
		/*---------------------------------------------------------------*/

});





/**
 * ---------------------------------------------------------------------------------------------
 *			FUNCIONES MARCACION
 *---------------------------------------------------------------------------------------------
 */

	function nuevamarcacion()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{}
		data = JSON.stringify(data);
		
		var parameters={	'type': 'POST',//'POST',
				'contentType': 'application/json',
				'url':'<?php echo(./../dispo/marcacion/nuevodata',
				'control_process':true,
				'show_cargando':true,
				'finish':function(response){
						//ValidateControlsInit();
						$("#frm_marcacion #accion").val("I");
						$("#dialog_nueva_marcacion_nombre").html("NUEVO REGISTRO");

						$("#marcacion_sec").val('');
						$("#frm_marcacion #cliente_id").val($("#frm_informacion_general #cliente_id").val());
						$("#frm_marcacion #cliente_id").prop('readonly',true);
						$("#frm_marcacion #nombre").val('');
						$("#frm_marcacion #direccion").val('');
						$("#frm_marcacion #pais_id").html(response.cbo_pais_id);
						$("#frm_marcacion #ciudad").val('');
						$("#frm_marcacion #contacto").val('');
						$("#frm_marcacion #telefono").val('');
						$("#frm_marcacion #zip").val('');
						$("#frm_marcacion #estado").html(response.cbo_estado);
						$("#frm_marcacion #lbl_fec_sincronizado").html('');
						$("#frm_marcacion #lbl_usuario_ing").html('');
						$("#frm_marcacion #lbl_fec_ingreso").html('');
						$("#frm_marcacion #lbl_usuario_mod").html('');
						$("#frm_marcacion #lbl_fec_modifica").html('');
						$("#frm_marcacion #sincronizado_pendiente").show();
						$("#frm_marcacion #sincronizado_ok").hide();
						
						$("#dialog_nueva_marcacion").modal('show');
						}							
	                 }
		response = ajax_call(parameters, data);		
		return false;	
									
	}//end function nuevo




	function grabar_marcacion()
	 	{
			if (!ValidateControls('frm_marcacion')) {
				return false;
			}

			//Se llama mediante AJAX para adicionar al carrito de compras
			var data = 	{	accion: $("#frm_marcacion #accion").val(),
						 	marcacion_sec: $("#frm_marcacion #marcacion_sec").val(),
						 	cliente_id: $("#frm_marcacion #cliente_id").val(),
						 	nombre: $(" #frm_marcacion #nombre").val(),
						 	direccion: $(" #frm_marcacion #direccion").val(),
						 	ciudad: $("#frm_marcacion #ciudad").val(),
						 	pais_id: $("#frm_marcacion #pais_id").val(),
							contacto: $("#frm_marcacion #contacto").val(),
							telefono: $("#frm_marcacion #telefono").val(),
							zip:$("#frm_marcacion #zip").val(),
							estado: $("#frm_marcacion #estado").val(),
						}
			data = JSON.stringify(data);
			
			var parameters = {	'type': 'POST',//'POST',
								'contentType': 'application/json',
								'url':'<?php echo(./../dispo/marcacion/grabardata',
								'control_process':true,
								'show_cargando':true,
								'finish':function(response){
										if (response.validacion_code == 'OK')
										{
											mostrar_registro_marcacion(response)
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
		



		function mostrar_registro_marcacion(response)
		{
			var row = response.row;
			if (row!==null)
			{
			//	ValidateControlsInit();
				$("#frm_marcacion #accion").val("M");			
				$("#dialog_nueva_marcacion_nombre").html(row.nombre);
				$("#frm_marcacion #marcacion_sec").val(row.marcacion_sec);
				$("#frm_marcacion #cliente_id").val(row.cliente_id);
				$("#frm_marcacion #cliente_id").prop('readonly',true);
				$("#frm_marcacion #nombre").val(row.nombre);
				$("#frm_marcacion #direccion").val(row.direccion);
				$("#frm_marcacion #pais_id").html(response.cbo_pais_id);
				$("#frm_marcacion #ciudad").val(row.ciudad);
				$("#frm_marcacion #contacto").val(row.contacto);
				$("#frm_marcacion #telefono").val(row.telefono);
				$("#frm_marcacion #zip").val(row.zip);
				$("#frm_marcacion #estado").html(response.cbo_estado);
				$("#frm_marcacion #lbl_fec_ingreso").html(row.fec_ingreso);
				$("#frm_marcacion #lbl_fec_modifica").html(row.fec_modifica);
				$("#frm_marcacion #lbl_usuario_ing").html(row.usuario_ing_user_name);
				$("#frm_marcacion #lbl_usuario_mod").html(row.usuario_mod_user_name);
				$("#frm_marcacion #lbl_fec_sincronizado").html(row.fec_sincronizado);
				
				if (row.sincronizado==1)
				{
					$("#frm_marcacion #sincronizado_pendiente").hide();
					$("#frm_marcacion #sincronizado_ok").show();
				}else{
					$("#frm_marcacion #sincronizado_pendiente").show();
					$("#frm_marcacion #sincronizado_ok").hide();
				}//end if
			}//end if
		}//end function mostrar_registro




		function consultar_marcacion(marcacion_sec)
		{
		
			//Se llama mediante AJAX para adicionar al carrito de compras
			var data = 	{marcacion_sec:marcacion_sec}
			data = JSON.stringify(data);
			
			var parameters = {	'type': 'POST',//'POST',
								'contentType': 'application/json',
								'url':'<?php echo(./../dispo/marcacion/consultardata',
								'control_process':true,
								'show_cargando':true,
								'finish':function(response){
									mostrar_registro_marcacion(response);
									//	listar_marcacion(true);
										cargador_visibility('hide');
										
										$("#dialog_nueva_marcacion").modal('show');
								}							
			                 }
			response = ajax_call(parameters, data);		
			return false;
		}//end function consultar_marcacion
