var div_cargador=".loading-state";
var div_cargador_insert_afert_element = "#dialog-message";

$(function() {

	var cargador_html;
	
	cargador_html = '<div class="loading-state"><div class="loader centered">Loading...</div></div>';
	$(div_cargador_insert_afert_element).after(cargador_html);	
	$(div_cargador).hide();
});	


function cargador_visibility(visible){
	if (visible=="show"){
		$(div_cargador).show();
	}//end if
	if (visible=="hide"){
		$(div_cargador).hide();
	}//end if	   		
}

