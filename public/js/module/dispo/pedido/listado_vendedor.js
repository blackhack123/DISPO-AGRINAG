
$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	$("#frm_buscar #btn_consultar").on('click', function(event){ 
		var data = 	{
						cliente_nombre:		$("#frm_buscar #cliente_nombre").val(),
						fecha_ini:			$("#frm_buscar #fecha_ini").val(),
						fecha_fin:			$("#frm_buscar #fecha_fin").val(),
						desglosar_fincas:	$("#frm_buscar #desglosar_fincas").val(),
					}
		data = JSON.stringify(data);
			
		var parameters = {	'type': 'post',
							'contentType': 'application/json',
							'url':'../../dispo/pedido/listadovendedordata',
							'show_cargando':true,
							'finish':function(response){
									if (response.respuesta_code=='OK'){
										listado_vendedor_llenar_tabla(response.result);	
									}else{
										message_error('ERROR', response);
									}//end if
							}
						 }
		ajax_call(parameters, data);	
		return false;
	});
	
	/*---------------------------------------------------------------*/	
	
	function listado_vendedor_llenar_tabla(result)
	{
		
	}//end function listado_vendedor_llenar_tabla
	
	
});

