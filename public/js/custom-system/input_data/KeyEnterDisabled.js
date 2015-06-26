/**
 * Deshabilita la tecla Enter en los controles INPUT, con la finalidad que los botones en el JQUERY UI
 * no se haga el SUBMIT de manera automática por el ENTER sino más bien nosotros controlar esa acción. 
 */
function InputData_KeyEnterDisabled(){
	$(document).find('input').keypress(function(e) {
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
			return false;
		}
	});	
}//end function InputData_KeyEnterDisabled