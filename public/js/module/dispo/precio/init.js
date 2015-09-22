

$(document).ready(function () 
{
	//Se indica que todos los controles se le aplique el trim
	InputData_TrimAll();

	//Se consulta los combos los valores que deben de cargar
	var data = 	{
					opcion: 'panel-precio',
					grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/precio/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){		
							$("body #frm_precio_grupo #grupo_precio_cab_id").html(response.grupo_precio_opciones);
							$("body #frm_precio_grupo #tipo_precio").html(response.tipo_precio_opciones);							
							$("body #frm_grupo_usuario #grupo_precio_cab_id").html(response.grupo_precio_opciones);
							
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
	
});
	