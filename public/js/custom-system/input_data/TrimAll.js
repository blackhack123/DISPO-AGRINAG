/**
 * Quita los espacios iniciales y finales de los controles tipo INPUT
 *
 */
function InputData_TrimAll(){
	$(':input').on('change', function(event) {
        var dato =  $.trim($(this).val());
		 $(this).val(dato);
	});	
}//end function InputData_TrimAll