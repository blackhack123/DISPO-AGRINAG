
function InputData_UpperCaseAll(){
	$(':input').on('change', function(event) {
        var dato =  $(this).val().toUpperCase();
		 $(this).val(dato);
	});	
}//end function InputData_initNumberNoDecimal