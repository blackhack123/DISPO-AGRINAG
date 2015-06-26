function number_val(valor, decimales){
	var valor2  = new String(valor);
	valor2 = valor2.replace(/,/g,"");

	valor2 = parseFloat(valor2);
	if (isNaN(valor2)){
		valor2 = 0;
	}//end if
	
	if (typeof decimales === "undefined"){
	}else{
		valor2 = number_round(valor2, decimales);
	}//end if
	
	return valor2;
}//end function iframe_load


function number_round(numero, decimales){
	var multiplo = Math.pow(10,decimales);
	
	calculado = Math.round(numero*multiplo)/multiplo;
	return calculado;
}