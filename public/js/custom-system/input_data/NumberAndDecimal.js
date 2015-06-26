/**
 * Permite ingresar numeros con decimales
 * Clase utilizada: inputdata-number-and-decimal
 * Por defecto se utiliza 2 decimales, en caso de requerir decimales personalizados se puede utilizar las siguiente clases:
 *     - inputdata-1decimal   (UN DECIMAL)
 *     - inputdata-2decimal	  (DOS DECIMALES)
 *     - inputdata-3decimal   (TRES DECIMALES)       
 */

function InputData_initNumberAndDecimal(){
	$(document).find('.inputdata-number-and-decimal').keydown(function(e) {
		var key = e.charCode || e.keyCode || 0;
		// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
		// home, end, period, and numpad decimal
		//console.log(key);
		if (
				(key == 8) ||  //BackSpace
				(key == 9) ||  //Tab 
				(key == 35) || //End
				(key == 36) || //Home
				(key == 37) || //Direccion Izquierda
				(key == 39) || //Direccion Derecha				
				(key == 46) || //Delete
				(key == 110) || //Punto NumPad
				(key == 190) || //Punto Teclado
				(key == 57)  ||
				(key == 105) ||
				(key >= 96 && key <=105)|| //Teclado NumPad
				(key >= 48 && key <=57)    //Teclado Normal
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
	
	$(document).find('.inputdata-number-and-decimal').blur(function(e) {
		//Se pregunta si hay un punto al final del contenido
		var pos_ultimo_caracter = $(this).val().length - 1;

		if ($(this).val().substring(pos_ultimo_caracter, pos_ultimo_caracter+1)=='.'){
			$(this).val($(this).val().substring(0, pos_ultimo_caracter));
		}//end if

		//Se obtiene con cuantos decimales va a trabajar el dato
		default_nro_decimales = 2;
		nro_decimales = 0;
		if ($(this).hasClass('inputdata-1decimal')){
			nro_decimales = 1;		
		}//end if
		if ($(this).hasClass('inputdata-2decimal')){
			nro_decimales = 2;		
		}//end if
		if ($(this).hasClass('inputdata-3decimal')){
			nro_decimales = 3;		
		}//end if
		if (nro_decimales==0){
			nro_decimales = default_nro_decimales; 
		}
		
		//Si el dato viene sin valor se le incializa con CERO
		if ($(this).val()==''){
			data = 0;
		}else{
			data = $(this).val();
		}
		var valor = parseFloat(data);
		valor = valor.toFixed(nro_decimales);
		$(this).val(valor);
		
		return false;
	});
	
}//end function InputData_initNumberAndDecimal