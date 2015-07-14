/*
Creado por: 	Moroni Salazar
Fecha Creación:	16-Abr-2012
Motivo:			Minimizar la programación para las validaciones básicas.

Depende de: Jquery 		>= 1.9.1
			Jquery-ui 	>= 1.10.2
			custom-validate.css (Hoja de Estilo)
			
Forma de utilizarlo:
	- Se debe de invocar a la funcion ValidateControls(), el cual retorna TRUE si pasa todo el control de validacion
	  y FALSE en caso que no pase todas las validaciones

Parámetros:
	Debe ser utilizado como atributos del control lo siguiente:
	
	Attributo				Valor						Explicaciòn
	-----------------------------------------------------------------------------------
	validate 				required					Utilizado para campos necesarios
							validateLinkConditional		Solo se validará, siempre y cuando se cumpla la Validacion Condicional
							
	validatedate			true						Se utiliza para validar los controles que son de tipo fecha							
				
	validateFocus			id Control					(Opcional) Si el control no cumple la validaciòn envìa el foco al control
														que se indique, esto es util para aquellos controles que utilizan
														la ventana de dialogo para seleccionar un registro
													
	validateMessage			Texto						Se indica el texto que quiere que se muestre en el Tooltips, al momento
														que la validaciòn se active.
													
	validateLinkConditional	Condición					Depende si el atributo validate='validateLinkConditional', la condición
														que se indique se evalua internamente por un EVAL, por lo que la condición
														puede ser ilimitada al combinarse con varios controles.
	  
Ejemplos:
	1) CAMPO OBLIGATORIO.- El caso más común el VERDADERO si tiene un valor asignado el control
			<select name="perfil_2" id="perfil_2" name="select"  validate="required" validateMessage="Seleccione un Perfil">

	2) VALIDAR CONTROL Y PASAR EL FOCO A OTRO CONTROL.- en caso de ser FALSO pasa el foco a otro control,
	   esto es útil para aquellos controles que necesita de un botón para invocar a la pantalla de diálogo.
			<input name="nombre_2" id="nombre_2" type="text"  value="" readonly  
			  validate="required" validateFocus="buscar_persona_2" validateMessage="Seleccione una Persona" />

	3) VALIDAR CONTROL DEPENDIENDO DE OTRO CONTROL.- Esto es útil cuando un control necesita ser validado, 
	   de acuerdo a que otro control tenga asignado un valor determinado o cumpla una condición.
			<input name="permiso_mobil_2" id="permiso_mobil_2" type="checkbox" value="1" validate="validateLinkConditional" 
				validateMessage="Condicional" validateLinkConditional="($('#cambiar_clave_2').prop('checked')==true)" />
		  
*/

	
	function ValidateToolTips(control, mensaje){
		control.attr('title',mensaje);
		if (/MSIE\s([\d.]+)/.test(navigator.userAgent)) {
			//No hace nada por IE
		}else{
			control.tooltip({ position: { my: "left+15 center", at: "right center" } });	
		}//end if
	}
	
	
	function ValidateControlsInit()
	{
		$( ".validate_no_valido" ).tooltip( "destroy" );
		$(".validate_no_valido").removeClass("validate_no_valido");
	}//end function 
	
	
	function ValidateControls(frm){
		//var controles = $("input[validate], select[validate]");
		var controles = $("#"+frm+" *[validate]");		
		var flag = true;
		var PrimerControl_noValido = new Object();
		var valida = false;
		
		$( ".validate_no_valido" ).tooltip( "destroy" );
		//Se validan los controles de acuerdo a las validaciones
		controles.each(function(){		
			$(this).removeClass('validate_no_valido');
			$(this).removeAttr('title');

			valida = true;
			if ($(this).attr('validate')=='required'){
//				valida = true;
				switch ($(this).attr('type')){
					case 'checkbox':
						if ($(this).prop('checked')==false){
							valida = false;
						}//end if
						break;
					default:
						if ($(this).val()==''){
							valida = false;
						}//end if
						break;
				}//end switch
			}//end if
			
			if ($(this).attr('validate')=='validateLinkConditional'){
				//alert('condicional:'+$(this).attr('validateLinkConditional')+'**'+eval($(this).attr('validateLinkConditional')));
				if (eval($(this).attr('validateLinkConditional'))){
//					valida = true;
					switch ($(this).attr('type')){
						case 'checkbox':
							if ($(this).prop('checked')==false){
								valida = false;
							}//end if
							break;
						default:
							if ($(this).val()==''){
								valida = false;
							}//end if
							break;
					}//end switch				
				}//end if
			}//validate Conditional
			

			/*--------------------------Validaciones adicionales----------------------------*/
						
			if ((valida==true) && ($(this).val()!='') ){
				
				//Valida que la fecha sea valida
				if ($(this).attr('validatedate')=='true'){
					valida = ValidateFecha($(this).val());
					$(this).attr('validateMessage','Fecha No Valida, solo se acepta dd/mm/yyyy');		
				}//end if
				
				//Valida que la cédula sea valida
				if ($(this).attr('validatedocumento')=='cedula'){
					valida = ValidateCedula($(this).val());
					$(this).attr('validateMessage','Cédula no válida');						
				}//end if				
			}//end if
			
			/*------------------------------------------------------------------------------*/

			if (!valida){
				if($(this).hasClass("validate_no_valido")){
					$("this").removeClass("validate_no_valido");
				}
				//$(this).attr('class', 'validate_no_valido');
				$(this).addClass("validate_no_valido");
				if (jQuery.isEmptyObject(PrimerControl_noValido)){
					PrimerControl_noValido = $(this);
				}//end if
				flag = false;
				if ($(this).is('[validateMessage]')){
					ValidateToolTips($(this), $(this).attr('validateMessage'));
				}//end if
			}//end if
		});
				

		if (!jQuery.isEmptyObject(PrimerControl_noValido)){
			if (PrimerControl_noValido.is('[validateFocus]')){
				control = PrimerControl_noValido.attr('validateFocus');
				$("#"+control).focus();
			}else{						
				PrimerControl_noValido.focus();
			}//end if
			message_alert('Mensaje del Sistema', 'Ingrese la información requerida');	
			//alert('Ingrese la información requerida');
		}//end if		
			
		return flag;
	}//end function ValidateControls
	
	
	function validateNumber (event){
		var keyCode = event.which; // Capture the event

		//190 is the key code of decimal if you dont want decimals remove this condition keyCode != 190
		if (keyCode != 8 && keyCode != 9 && keyCode != 13 && keyCode != 37 && keyCode != 38 && keyCode != 39 && keyCode != 40 && keyCode != 46 && keyCode != 110 && keyCode != 190) {
			if (keyCode < 48) {
				return false;
				//event.preventDefault();
			} else if (keyCode > 57 && keyCode < 96) {
				return false;				
				//event.preventDefault();
			} else if (keyCode > 105) {
				return false;
				//event.preventDefault();
			}
		}
	}//end function validateNumber
	
	function validateNumberTruncateDecimal(){
        var val = parseFloat( this.value ).toFixed(2);
		if ($.isNumeric(val)){
	        $(this).val( val );
		}else{
			$(this).val(0);
		}
	}//end function 