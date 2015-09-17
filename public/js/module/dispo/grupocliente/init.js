

$(document).ready(function () 
{
	dispoGrupo_init();
	
});
	

function dispoGrupo_init()
{
	$("#frm_dispo_grupo #info_grupo_dispo_cab").html('');
	
	var data = 	{
					opcion: 'panel-grupo-clientes',
					grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
					grupo_precio_1er_elemento:	'&lt;SELECCIONE&gt;',
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/grupocliente/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){	
							//grupodispo_listar();
							$("#frm_dispo_grupo #grupo_dispo_cab_id").html(response.grupo_dispo_opciones);
							$("#frm_grupo_precio #grupo_precio_cab_id").html(response.grupo_precio_opciones);
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
}//end function dispoGrupo_init
