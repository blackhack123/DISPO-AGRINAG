/*
	//Para llamar un combo
	var data = "opcion=todos&tipo_nomina_id=1&texto_1er_elemento_anio=&texto_1er_elemento_calendario=Todos";
	var parameters = {	'async':true,
			'cancel_process_previous':false,			
						'type': 'POST',
						'url':'<?php echo($this->basePath()); ?>/talentohumano/periodocalendario/getAllCombosPorTipoNomina',
						'show_cargando':true,
						'finish':function(msg){
							alert('se activo la funcion');
							console.log(msg);
							//console.log(parameters);
							//console.log(obj_data);
						}
					 }
	console.log(parameters);
	obj_result = ajax_call(parameters, data)

*/



var ajax_main_process = null;

function ajax_call(parameters, obj_data){
	if (typeof(parameters.cache) == 'undefined'){parameters.cache = false;}		
	if (typeof(parameters.async) == 'undefined'){parameters.async = false;}	
	if (typeof(parameters.cancel_process_previous) == 'undefined'){parameters.cancel_process_previous = true;}			
	if (typeof(parameters.show_cargando) == 'undefined'){parameters.show_cargando = false;}			

	if (typeof(obj_data) == 'undefined') {obj_data = '';}
	
	if (parameters.show_cargando==true){		
		cargador_visibility('show');
//		$(this).loading = true;
	}//end if

	if (typeof(parameters.contentType) == 'undefined'){
		parameters.contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
	}//end if

/*	if (parameters.async)
	{
*/		$.ajax({
			type: parameters.type,
			contentType: parameters.contentType,
			url: parameters.url,
			data: obj_data,
			cache: parameters.cache,
			async: parameters.async,
		}).done(function(msg) {
			if ((msg.respuesta_code=='OK')||(msg.respuesta_code=='NOOK')){
				parameters.finish(msg)
			}else{
				message_error('ERROR1.', msg, true);
			}	
			if (parameters.show_cargando==true){
				cargador_visibility('hide');
			}//end if
		}).error(function(request, status, error) {		
			//console.log('paso01:*',error,"*");
			if (error.length != 0)
			{	
				//console.log('paso02');
				message_error('ERROR2', request.responseText, true);
				console.log('request:',request);
				console.log('status:',status);								
				console.log('error:',error);
			}//end if
			if (parameters.show_cargando==true){
				cargador_visibility('hide');
			}//end if
		});		
/*	}else{
		ajax_main_process = $.ajax({
									type: parameters.type,
									contentType: parameters.contentType,
									url: parameters.url,
									data: obj_data,
									cache: parameters.cache,
									async: parameters.async,
									beforeSend : function(){ 
										if (parameters.cancel_process_previous==true){
											if (ajax_main_process) {
												ajax_main_process.abort();
											}	
										}
									},
								}).done(function(msg) {
									if ((msg.respuesta_code=='OK')||(msg.respuesta_code=='NOOK')){
										parameters.finish(msg)
									}else{
										message_error('ERROR1.', msg, true);
									}	
									if (parameters.show_cargando==true){
										cargador_visibility('hide');
									}//end if
								}).error(function(request, status, error) {
									message_error('ERROR2', request.responseText, true);
									console.log(request);
									console.log(status);								
									console.log(error);
									if (parameters.show_cargando==true){
										cargador_visibility('hide');
									}//end if
								});
	}//end if
*/	
	
	return true;
}//end function ajax_call