var message_respuesta = '';
var message_control_focus = null;

	function message_confirm(titulo, mensaje, funcion_call_back, parametros){
		if ($("#dialog-message").html()==''){
			bodyMessage();
		}//end if
	
///		$("#dialog-message").attr('title', titulo);
	//	$("#dialog-message").find("span.ui-dialog-title").text(titulo);
		$("span.dialog-message-ico").hide();
		$(".dialog-message-question").show();				
		$("#dialog-message-text").html(mensaje);

		var funcion = eval(funcion_call_back);
		
		message_respuesta = '';
		$("#dialog-message").dialog({
			width: 'auto',
			minHeight: 'auto',		
			modal: true,	
			closeOnEscape: true,
			open: function(event, ui) {
			  $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').show();
			},			
			buttons: {
				Yes: function() {
					message_respuesta = true;
					$( this ).dialog( "close" );
					funcion(true, parametros);
				},
				No: function() {
					message_respuesta = false;
					$( this ).dialog( "close" );
					funcion(false, parametros);
				}
			}
		});
		
		$("#dialog-message").dialog("option", "position", "center");
		$("#dialog-message").dialog('option', 'title', titulo);	
		$("#dialog-message").dialog( "option", "closeOnEscape", false);		
		return message_respuesta;
	}

	function message_alert_callback(titulo, mensaje, funcion_call_back, parametros){
		if ($("#dialog-message").html()==''){
			bodyMessage();
		}//end if
	
///		$("#dialog-message").attr('title', titulo);
	//	$("#dialog-message").find("span.ui-dialog-title").text(titulo);
		$("span.dialog-message-ico").hide();
		$(".dialog-message-question").show();				
		$("#dialog-message-text").html(mensaje);
		
		var funcion = eval(funcion_call_back);
		
		message_respuesta = '';
		$("#dialog-message").dialog({
			width: 'auto',
			minHeight: 'auto',
			modal: true,
			closeOnEscape: false,
			open: function(event, ui) {
			  $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').hide();
			},
			buttons: {
				Aceptar: function() {
					message_respuesta = true;
					$( this ).dialog( "close" );
					funcion(true, parametros);
				},
			}
		});
		
//		$("#dialog-message").dialog({ dialogClass: 'no-close' });
		$("#dialog-message").dialog("option", "position", "center");
		$("#dialog-message").dialog('option', 'title', titulo);		
		$("#dialog-message").dialog( "option", "closeOnEscape", false);
		return message_respuesta;
	}//end function message_alert_callback


	function bodyMessage(){
		html='	<table>'
			+'		<tr>'
			+'			<td width="50" valign="top">'
			+'				<span class="dialog-message-ico dialog-message-alert"></span>'
			+'				<span class="dialog-message-ico dialog-message-info"></span>'
			+'				<span class="dialog-message-ico dialog-message-error"></span>'
			+'				<span class="dialog-message-ico dialog-message-question"></span>'
			+'			</td>'
			+'			<td>'
			+'				<span id="dialog-message-text"></span>'
			+'			</td>'
			+'		</tr>'
			+'	</table>'
		$("#dialog-message").html(html);
		return 	false;
	}

	function message(tipo, titulo, mensaje){
		return false; //Desactivado MORONITOR	
		if ($("#dialog-message").html()==''){
			bodyMessage();
		}//end if

/*		$("#dialog-message").attr('title', titulo);
		$("#dialog-message").find("span.ui-dialog-title").text(titulo);*/
		//$("span.ui-dialog-title").text(titulo); 
		
		//$("#dialog-message").attr('title', titulo);
		$("span.dialog-message-ico").hide();
		$(".dialog-message-"+tipo).show();		
		$("#dialog-message-text").html(mensaje);
		
		$("#dialog-message").dialog({
			width: 'auto',
			minHeight: 'auto',		
			modal: true,
			show: 'fade',
			hide: 'fade',			
			closeOnEscape: true,			
			open: function(event, ui) {
			  $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').show();
			},
			close: function( event, ui ) {
			  if (message_control_focus != null){
				  $("#"+message_control_focus).focus();
			  }//end if
			},
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});		
		$("#dialog-message").dialog('option', 'title', titulo);	
		$("#dialog-message").dialog("option", "position", "center");
		$("#dialog-message").dialog( "option", "closeOnEscape", true );

		return false
	}
		
	function message_alert(titulo, mensaje, control_focus){
		if (typeof control_focus === "undefined") {
			message_control_focus = null;
		}else{
			message_control_focus = control_focus;
	    }//end if
		message('alert', titulo, mensaje);
		return false;
	}

	function message_info(titulo, mensaje){
		message('info', titulo, mensaje);
		return false;
	}

	function message_error(titulo, mensaje){
		message('error', titulo, mensaje);
		return false;
	}
