/*
 * Este metodo es utilizado para las mascaras contables que necesitan solo numeros y el caracter punto
 * Clase Utilizada:  inputdata-mascara-contable
 */
function InputData_initMascaraContable(){

	$(document).find('.inputdata-mascara-contable').keydown(function(e) {
		var key = e.charCode || e.keyCode || 0;
		// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
		// home, end, period, and numpad decimal
		if (
				(key == 8) || 
				(key == 9) ||
				(key == 46) ||
				(key == 110) ||
				(key == 190) ||
				(key == 57)  ||
				(key == 105)  
		    )
		{
			//Se valida el punto
			if ((key == 110) ||(key == 190)){
				//console.log($(this).val());
				
				//Valida que no sea el punto el primer caracter
				if ($(this).val().length==0){
					return false;
				}//end if
				
				//Valida que no sea el punto el primer caracter
				var pos_ultimo_caracter = $(this).val().length - 1;
				if ($(this).val().substring(pos_ultimo_caracter, pos_ultimo_caracter+1)=='.'){
					return false;
				}//end if				
			}//end if
			return true;
		}//end if
		return false;
	});	
	
	$(document).find('.inputdata-mascara-contable').blur(function(e) {
		//Se pregunta si hay un punto al final del contenido
		var pos_ultimo_caracter = $(this).val().length - 1;

		if ($(this).val().substring(pos_ultimo_caracter, pos_ultimo_caracter+1)=='.'){
			$(this).val($(this).val().substring(0, pos_ultimo_caracter));
			return false;
		}//end if				
		return false;
	});

}//end function InputData_initMascaraContable