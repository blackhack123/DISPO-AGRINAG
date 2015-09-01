/**
 * 
 */

	$(document).ready(function () {
		InputData_TrimAll();
	/*	InputData_initNumberAndDecimal();*/
		InputData_initNumberNoDecimal();
		
		$('table').tablesorter(); //Columna de Sueldo
		$("table").stickyTableHeaders();
	
		
	});