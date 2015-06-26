
function InputData_initNumberNoDecimal(){
	$(document).find('.inputdata-number-no-decimal').keydown(function(e) {
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
				(key == 57)  ||
				(key == 105) ||
				(key >= 96 && key <=105)|| //Teclado NumPad
				(key >= 48 && key <=57)    //Teclado Normal
		    )
		{
			return true;
		}//end if
		return false;
	});	
	
	$(document).find('.inputdata-number-no-decimal').blur(function(e) {
		//Si el dato viene sin valor se le incializa con CERO
		if ($(this).val()==''){
			data = 0;
		}else{
			data = $(this).val();
		}
		var valor = parseFloat(data);
		valor = valor.toFixed(0);
		$(this).val(valor);
		
		return false;
	});
}//end function InputData_initNumberNoDecimal