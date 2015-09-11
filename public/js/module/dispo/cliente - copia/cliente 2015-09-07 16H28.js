/**
 * 
 */

$(document).ready(function () {
	
	
	$(".btn_edit").on('click', function(event){ 
		var tr 						= $(this).closest('tr');
		var cliente_id 				= tr.attr('cliente_id');

		consultar_cliente(cliente_id);
		//$("#dialog_mantenimiento").modal('show') 
	});
	
	$("#btn_consultar").on('click', function(event){ 
		$("#frm").submit();
		return false;
	});

	$(" #btn_nuevo_cliente").on('click', function(event){
		nuevo(); 
		return false;
	});    

	$("#btn_grabar_informacion_general").on('click', function(event){ 
		grabar_informacion_general();
		return false;
	});   
	
	$(" #tbl_registros tbody tr").on('dblclick', function(event){ 
		var tr 						= $(this).closest('tr');
		var cliente_id 				= tr.attr('cliente_id');

		consultar_cliente(cliente_id);
		//$("#dialog_mantenimiento").modal('show') 
	});	

	
});


/*
 *************************************************************************** 
 *		FUNCION DE CONTROL DE PROGRAMACION DIARIA-SEMANAL-INMEDIATA
 ***************************************************************************
 */

 function tipoProgramacion(getTp){
	 
		var tP = getTp;
		
		if(tP == "S"){
			if($("#frm_informacion_general #calendario").css("display") != "none"){
				$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
				$('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else if($("#frm_informacion_general #p_inmediato").css("display") != "none"){
				$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
				$('#frm_informacion_general #p_inmediato').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else if($("#frm_informacion_general #semanal").css("display") != "none"){
				//$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else{
				$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
			}
		}

		if(tP == "C"){
			if($("#frm_informacion_general #semanal").css("display") != "none"){
				$('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
				$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else if($("#frm_informacion_general #p_inmediato").css("display") != "none"){
				$('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
				$('#frm_informacion_general #p_inmediato').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else if($("#frm_informacion_general #calendario").css("display") != "none"){
				//$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else{
				$('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
			}
		}

		if(tP == "I"){
			if($("#frm_informacion_general #semanal").css("display") != "none"){
				$('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
				$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else if($("#frm_informacion_general #calendario").css("display") != "none"){
				$('#frm_informacion_general #p_inmediato').animate({height: "toggle", opacity: "toggle"}, "slow");
				$('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else if($("#frm_informacion_general #inmediato").css("display") != "none"){
				//$('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
			}else{
				$('#frm_informacion_general #p_inmediato').animate({height: "toggle", opacity: "toggle"}, "slow");
			}
		}
		
		

	}

 
	

/**
 * ----------------------------------------------------------------------
 * 		FUNCIONES DEL CLIENTE (INFORMACION GENERAL)
 *-----------------------------------------------------------------------
 */
    
	function nuevo()
	{
		//Deshabilita los tabs excepto el primer TAB
		$("#tabs_mantenimiento_cliente ul li:gt(0)").addClass('disabled').addClass('disabledTab');
	
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{}
		
		var est_credito_suspendido 	= ($("#frm_informacion_general #est_credito_suspendido").is(':checked') ? 1 : 0);
		var estado				 	= ($("#frm_informacion_general #estado").is(':checked') ? 1 : 0);
		var incobrable				= ($("#frm_informacion_general #incobrable").is(':checked') ? 1 : 0);
		var cliente_especial 		= ($("#frm_informacion_general #cliente_especial").is(':checked') ? 1 : 0);
		var envia_estadocta 		= ($("#frm_informacion_general #envia_estadocta").is(':checked') ? 1 : 0);
		//var tipo_envio_estcta		= ($("#frm_informacion_general #tipo_envio_estcta").is(':checked') ? 1 : 0);
		//var dia_semana 				= ($("#frm_informacion_general #dia_semana").is(':checked') ? 1 : 0);
		//var inmediato				= ($("#frm_informacion_general #inmediato").is(':checked') ? 1 : 0);
		
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/cliente/nuevodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){		
									//ValidateControlsInit();
								
								$("#accion").val("I");
								$("#dialog_mantenimiento_cliente_nombre").html("NUEVO REGISTRO");

								$('#myTabs a[href="#tab_informacion_general"]').tab('show') // Select tab by nombre
								$("#frm_informacion_general #cliente_id").val('');
								$("#frm_informacion_general #cliente_id").prop('readonly',false);
								$("#frm_informacion_general #nombre").val('');
								$("#frm_informacion_general #abreviatura").val('');
								$("#frm_informacion_general #grupo_precio_cab_id").html(response.cbo_grupo_dispo);
								$("#frm_informacion_general #direccion").val('');
								$("#frm_informacion_general #ciudad").val('');
								$("#frm_informacion_general #estados_id").html(response.cbo_estado_id);
								$("#frm_informacion_general #estado_nombre").val('');
								$("#frm_informacion_general #pais_id").html(response.cbo_pais_id);
								$("#frm_informacion_general #codigo_postal").val('');
								$("#frm_informacion_general #comprador").val('');
								$("#frm_informacion_general #telefono1").val('');
								$("#frm_informacion_general #telefono1_ext").val('');
								$("#frm_informacion_general #telefono2").val('');
								$("#frm_informacion_general #telefono2_ext").val('');
								$("#frm_informacion_general #fax1").val('');
								$("#frm_informacion_general #fax1_ext").val('');
								$("#frm_informacion_general #fax2").val('');
								$("#frm_informacion_general #fax2_ext").val('');
								$("#frm_informacion_general #email").val('');
								$("#frm_informacion_general #usuario_vendedor_id").html(response.cbo_usuario_vendedor_id);
								$("#frm_informacion_general #tc_limite_credito").val('');
								$("#frm_informacion_general #tc_interes").val('');
								$("#frm_informacion_general #est_credito_suspendido").val('');
								$("#frm_informacion_general #credito_suspendido_razon").val('');
								$("#frm_informacion_general #contacto").val('');
								$("#frm_informacion_general #cliente_factura_id").val('');
								$("#frm_informacion_general #telefono_fact1").val('');
								$("#frm_informacion_general #telefono_fact1_ext").val('');
								$("#frm_informacion_general #telefono_fact2").val('');
								$("#frm_informacion_general #telefono_fact2_ext").val('');
								$("#frm_informacion_general #fax_fact1").val('');
								$("#frm_informacion_general #fax_fact1_ext").val('');
								$("#frm_informacion_general #fax_fact2").val('');
								$("#frm_informacion_general #fax_fact2_ext").val('');
								$("#frm_informacion_general #email_factura").val('');
								$("#frm_informacion_general #comercializadora").val('');
								$("#frm_informacion_general #pais_fue").val('');
								$("#frm_informacion_general #facturacion_sri").val('');
								$("#frm_informacion_general #porc_iva").val('');
								$("#frm_informacion_general #estado").val('');
								$("#frm_informacion_general #incobrable").val('');
								$("#frm_informacion_general #cliente_especial").val('');
								$("#frm_informacion_general #porc_iva").val('');
								$("#frm_informacion_general #formato_estado_cta").html(response.cbo_formato_estado_cta);
								$("#frm_informacion_general #tipo_envio_estcta").val('');
								$("#frm_informacion_general #dia_semana").val('');
								$("#frm_informacion_general #inmediato").val('');
								$('#frm_informacion_general #p_inmediato').animate({height: "toggle", opacity: "toggle"}, "slow");
								$("#frm_informacion_general #diacal_fecha2").val('');
								$("#frm_informacion_general #diacal_fecha1").val('');
								
								$("#frm_informacion_general #lbl_fec_sincronizado").html('');
								$("#frm_informacion_general #lbl_usuario_ing").html('');
								$("#frm_informacion_general #lbl_fec_ingreso").html('');
								$("#frm_informacion_general #lbl_usuario_mod").html('');
								$("#frm_informacion_general #lbl_fec_modifica").html('');
								$("#frm_informacion_general #sincronizado_pendiente").show();
								$("#frm_informacion_general #sincronizado_ok").hide();
								
								$("#dialog_mantenimiento").modal('show');
							 }							
				           }
		response = ajax_call(parameters, data);		
		return false;	
										
	}//end function nuevo

	
	
	function listar_marcacion(limpiar_filtros)
	{
		$('#frm_marcacion_listado #grid_marcacion_listado').jqGrid("clearGridData");
		
		if (limpiar_filtros==true)
		{
			$("#frm_marcacion_listado #busqueda_nombre").val("");
			$("#frm_marcacion_listado #busqueda_estado").val("");
		}//end if
		
		$('#frm_marcacion_listado #grid_marcacion_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function listar_marcacion

	

	 function grabar_informacion_general()
 	{
		if (!ValidateControls('frm_informacion_general')) 
		{
			return false;
		}

		//Define el metodo de envio estado de cuenta
		var tipo_envio_estcta	=$('input[name="tipo_envio_estcta"]:checked', '#frm_informacion_general').val();
    	var inmediato			="0";
    	var diacal_fecha1		="  ";
		var diacal_fecha2		="  ";
		var dia_semana			=" ";
		
		//console.log('tipo_envio_estcta:',tipo_envio_estcta);
		
		switch (tipo_envio_estcta)
		{
	        case 'S':
	        	//dia_semana			= ($("#frm_informacion_general #dia_semana").is(':checked') ? 1 : 0);
	        	dia_semana			= $('input[name="dia_semana"]:checked', '#frm_informacion_general').val();
	        	break;
	        case 'C':
	        	diacal_fecha2		= $("#frm_informacion_general #diacal_fecha2").val();
				diacal_fecha1		= $("#frm_informacion_general #diacal_fecha1").val();
	        	break;
	        case 'I':
	        	inmediato			= ($("#frm_informacion_general #inmediato").is(':checked') ? 1 : 0);
	        	break;
		}	
		
		//Asignacion de variables
		var est_credito_suspendido 	= ($("#frm_informacion_general #est_credito_suspendido").is(':checked') ? 1 : 0);
		var estado				 	= ($("#frm_informacion_general #estado").is(':checked') ? 1 : 0);
		var incobrable				= ($("#frm_informacion_general #incobrable").is(':checked') ? 1 : 0);
		var cliente_especial 		= ($("#frm_informacion_general #cliente_especial").is(':checked') ? 1 : 0);
		var envia_estadocta 		= ($("#frm_informacion_general #envia_estadocta").is(':checked') ? 1 : 0);

		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{	accion: 					$("#accion").val(),
					 	cliente_id: 				$("#frm_informacion_general #cliente_id").val(),
					 	nombre: 					$("#frm_informacion_general #nombre").val(),
					 	abreviatura: 				$("#frm_informacion_general #abreviatura").val(),
					 	grupo_precio_cab_id:		$("#frm_informacion_general #grupo_precio_cab_id").val(),
					 	direccion: 					$("#frm_informacion_general #direccion").val(),
					 	ciudad: 					$("#frm_informacion_general #ciudad").val(),
					 	estados_id:					$("#frm_informacion_general #estados_id").val(),
					 	estado_nombre:				$("#frm_informacion_general #estado_nombre").val(),
					 	pais_id: 					$("#frm_informacion_general #pais_id").val(),
					 	codigo_postal: 				$("#frm_informacion_general #codigo_postal").val(),
					 	comprador: 					$("#frm_informacion_general #comprador").val(),
					 	telefono1: 					$("#frm_informacion_general #telefono1").val(),
					 	telefono1_ext:				$("#frm_informacion_general #telefono1_ext").val(),
						telefono2: 					$("#frm_informacion_general #telefono2").val(),
						telefono2_ext:				$("#frm_informacion_general #telefono2_ext").val(),
						fax1: 						$("#frm_informacion_general #fax1").val(),
						fax1_ext:					$("#frm_informacion_general #fax1_ext").val(),
						fax2:						$("#frm_informacion_general #fax2").val(),
						fax2_ext:					$("#frm_informacion_general #fax2_ext").val(),
						email:						$("#frm_informacion_general #email").val(),
						usuario_vendedor_id:		$("#frm_informacion_general #usuario_vendedor_id").val(),
						tc_limite_credito:			$("#frm_informacion_general #tc_limite_credito").val(),
						tc_interes:					$("#frm_informacion_general #tc_interes").val(),
						est_credito_suspendido:		est_credito_suspendido,
						credito_suspendido_razon:	$("#frm_informacion_general #credito_suspendido_razon").val(),
						contacto:					$("#frm_informacion_general #contacto").val(),
						cliente_factura_id:			$("#frm_informacion_general #cliente_factura_id").val(),
						telefono_fact1:				$("#frm_informacion_general #telefono_fact1").val(),
						telefono_fact1_ext:			$("#frm_informacion_general #telefono_fact1_ext").val(),
						telefono_fact2:				$("#frm_informacion_general #telefono_fact2").val(),
						telefono_fact2_ext:			$("#frm_informacion_general #telefono_fact2_ext").val(),
						fax_fact1:					$("#frm_informacion_general #fax_fact1").val(),
						fax_fact1_ext:				$("#frm_informacion_general #fax_fact1_ext").val(),
						fax_fact2:					$("#frm_informacion_general #fax_fact2").val(),
						fax_fact2_ext:				$("#frm_informacion_general #fax_fact2_ext").val(),
						email_factura:				$("#frm_informacion_general #email_factura").val(),
						pais_fue:					$("#frm_informacion_general #pais_fue").val(),
						facturacion_sri:			$("#frm_informacion_general #facturacion_sri").val(),
						porc_iva:					$("#frm_informacion_general #porc_iva").val(),
						estado:								estado,
						incobrable:							incobrable,
						cliente_especial:					cliente_especial,
						envia_estadocta:					envia_estadocta,
						formato_estado_cta:					$("#frm_informacion_general #formato_estado_cta").val(),
						tipo_envio_estcta:					tipo_envio_estcta,
						dia_semana:							dia_semana,
						diacal_fecha2:						diacal_fecha2,
						diacal_fecha1:						diacal_fecha1,
						inmediato:							inmediato
						
					}
		//console.log('tipo_envio_estcta:',tipo_envio_estcta);
		//console.log('inmediato:',inmediato);
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/cliente/grabardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										$("#tabs_mantenimiento_cliente ul li").removeClass('disabled',false).removeClass('disabledTab',false);
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
									
									}//end if									
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function grabar



		function mostrar_registro(response)
		{
			var row = response.row;
			
			var est_credito_suspendido 	= ($("#frm_informacion_general #est_credito_suspendido").is(':checked') ? 1 : 0);
			var estado				 	= ($("#frm_informacion_general #estado").is(':checked') ? 1 : 0);
			var incobrable				= ($("#frm_informacion_general #incobrable").is(':checked') ? 1 : 0);
			var cliente_especial 		= ($("#frm_informacion_general #cliente_especial").is(':checked') ? 1 : 0);
			var envia_estadocta 		= ($("#frm_informacion_general #envia_estadocta").is(':checked') ? 1 : 0);
			//var tipo_envio_estcta		= ($("#frm_informacion_general #tipo_envio_estcta").is(':checked') ? 1 : 0);
			//var dia_semana				= ($("#frm_informacion_general #dia_semana").is(':checked') ? 1 : 0);
			//var inmediato				= ($("#frm_informacion_general #inmediato").is(':checked') ? 1 : 0);
			if (row!==null)
			{
			//	ValidateControlsInit();
				$("#accion").val("M");			
				$("#dialog_mantenimiento_cliente_nombre").html(row.nombre);
				$("#frm_informacion_general #cliente_id").val(row.id);
				$("#frm_informacion_general #cliente_id").prop('readonly',true);

				$("#frm_informacion_general #nombre").val(row.nombre);
				$("#frm_informacion_general #abreviatura").val(row.abreviatura);
				$("#frm_informacion_general #grupo_precio_cab_id").html(response.cbo_grupo_dispo);
				$("#frm_informacion_general #direccion").val(row.direccion);
				$("#frm_informacion_general #ciudad").val(row.ciudad);
				$("#frm_informacion_general #estados_id").html(response.cbo_estado_id);
				$("#frm_informacion_general #estado_nombre").val(row.estado_nombre);
				$("#frm_informacion_general #pais_id").html(response.cbo_pais_id);
				$("#frm_informacion_general #codigo_postal").val(row.codigo_postal);
				$("#frm_informacion_general #comprador").val(row.comprador);
				$("#frm_informacion_general #telefono1").val(row.telefono1);
				$("#frm_informacion_general #telefono1_ext").val(row.telefono1_ext);
				$("#frm_informacion_general #telefono2").val(row.telefono2);
				$("#frm_informacion_general #telefono2_ext").val(row.telefono2_ext);
				$("#frm_informacion_general #fax1").val(row.fax1);
				$("#frm_informacion_general #fax1_ext").val(row.fax1_ext);
				$("#frm_informacion_general #fax2").val(row.fax2);
				$("#frm_informacion_general #fax2_ext").val(row.fax2_ext);
				$("#frm_informacion_general #email").val(row.email);
				$("#frm_informacion_general #usuario_vendedor_id").html(response.cbo_usuario_vendedor_id);
				$("#frm_informacion_general #tc_limite_credito").val(row.tc_limite_credito);
				$("#frm_informacion_general #tc_interes").val(row.tc_interes);
				if (row.est_credito_suspendido=='1')
				{
				    $("#frm_informacion_general #est_credito_suspendido").prop('checked', true);
				}
				else
				{
				    $("#frm_informacion_general #est_credito_suspendido").prop('checked', false);
				}
				
				$("#frm_informacion_general #credito_suspendido_razon").val(row.credito_suspendido_razon);
				$("#frm_informacion_general #contacto").val(row.contacto);
				$("#frm_informacion_general #cliente_factura_id").val(row.cliente_factura_id);
				$("#frm_informacion_general #telefono_fact1").val(row.telefono_fact1);
				$("#frm_informacion_general #telefono_fact1_ext").val(row.telefono_fact1_ext);
				$("#frm_informacion_general #telefono_fact2").val(row.telefono_fact2);
				$("#frm_informacion_general #telefono_fact2_ext").val(row.telefono_fact2_ext);
				$("#frm_informacion_general #fax_fact1").val(row.fax_fact1);
				$("#frm_informacion_general #fax_fact1_ext").val(row.fax_fact1_ext);
				$("#frm_informacion_general #fax_fact2").val(row.fax_fact2);
				$("#frm_informacion_general #fax_fact2_ext").val(row.fax_fact2_ext);
				$("#frm_informacion_general #email_factura").val(row.email_factura);
				$("#frm_informacion_general #comercializadora").val(row.comercializadora);
				$("#frm_informacion_general #pais_fue").val(row.pais_fue);
				$("#frm_informacion_general #facturacion_SRI").val(row.facturacion_SRI);
				$("#frm_informacion_general #porc_iva").val(row.porc_iva);
				if (row.estado=='1')
				{
				    $("#frm_informacion_general #estado").prop('checked', true);
				}
				else
				{
				    $("#frm_informacion_general #estado").prop('checked', false);
				}
				
				if (row.incobrable=='1')
				{
				    $("#frm_informacion_general #incobrable").prop('checked', true);
				}
				else
				{
				    $("#frm_informacion_general #incobrable").prop('checked', false);
				}
				
				if (row.cliente_especial=='1')
				{
				    $("#frm_informacion_general #cliente_especial").prop('checked', true);
				}
				else
				{
				    $("#frm_informacion_general #cliente_especial").prop('checked', false);
				}
				
				if (row.envia_estadocta=='1')
				{
				    $("#frm_informacion_general #envia_estadocta").prop('checked', true);
				}
				else
				{
				    $("#frm_informacion_general #envia_estadocta").prop('checked', false);
				}
				$("#frm_informacion_general #porc_iva").val(row.porc_iva);
				$("#frm_informacion_general #formato_estado_cta").html(response.cbo_formato_estado_cta);

				switch (row.tipo_envio_estcta) {
				        case 'S':
				         $("#frm_informacion_general #programacion_semana").prop('checked', true);
				         $('#frm_informacion_general #semanal').animate({height: "toggle", opacity: "toggle"}, "slow");
				        break;
				 
				        case 'C':
				         $("#frm_informacion_general #programacion_calendario").prop('checked', true);
				         $('#frm_informacion_general #calendario').animate({height: "toggle", opacity: "toggle"}, "slow");
				        break;

				        case 'I':
				          $("#frm_informacion_general #programacion_inmediato").prop('checked', true);
				          $('#frm_informacion_general #p_inmediato').animate({height: "toggle", opacity: "toggle"}, "slow");
				        break;
				 }
				
				
				switch (row.dia_semana) {
				        case 'L':
				        	$("#L").prop('checked', true);
				        break;
				        case 'M':
				        	$("#M").prop('checked', true);
				        break;
				        case 'I':
					         $("#I").prop('checked', true);
					        break;
				        case 'J':
				        	$("#J").prop('checked', true);
					        break;
				        case 'V':
					         $("#V").prop('checked', true);
					        break;
				        case 'S':
					         $("#S").prop('checked', true);
					        break;
				        case 'D':
					         $("#D").prop('checked', true);
					        break;
				}	

				if (row.inmediato=='1')
				{
				    $("#frm_informacion_general #inmediato").prop('checked', true);
				}
				else
				{
				    $("#frm_informacion_general #inmediato").prop('checked', false);
				}
				
				$("#frm_informacion_general #diacal_fecha2").val(row.diacal_fecha2);
				$("#frm_informacion_general #diacal_fecha1").val(row.diacal_fecha1);
				
				$("#frm_informacion_general #lbl_fec_ingreso").val(row.fec_ingreso);
				$("#frm_informacion_general #lbl_fec_modifica").html(row.fec_modifica);
				$("#frm_informacion_general #lbl_usuario_ing").html(row.usuario_ing_user_name);
				$("#frm_informacion_general #lbl_usuario_mod").html(row.usuario_mod_user_name);
				$("#frm_informacion_general #lbl_fec_sincronizado").html(row.fec_sincronizado);
				
				if (row.sincronizado==1)
				{
					$("#frm_informacion_general #sincronizado_pendiente").hide();
					$("#frm_informacion_general #sincronizado_ok").show();
				}else{
					$("#frm_informacion_general #sincronizado_pendiente").show();
					$("#frm_informacion_general #sincronizado_ok").hide();
				}//end if
			}//end if
		}//end function mostrar_registro
			




		function consultar_cliente(id)
		{
			//Habilita todos los TABS
			$("#frm_informacion_general #tabs_mantenimiento_cliente ul li").removeClass('disabled').removeClass('disabledTab');
		
			//Se llama mediante AJAX para adicionar al carrito de compras
			var data = 	{cliente_id:id}
			data = JSON.stringify(data);
			
			var parameters = {	'type': 'POST',//'POST',
								'contentType': 'application/json',
								'url':'../../dispo/cliente/consultardata',
								'control_process':true,
								'show_cargando':true,
								'finish':function(response){
										mostrar_registro(response);
										listar_marcacion(true);
										cargador_visibility('hide');

										$("#dialog_mantenimiento").modal('show')
								}							
			                 }
			response = ajax_call(parameters, data);		
			return false;		
		}//end function consultar_cliente
