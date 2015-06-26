

function iframe_load(iframe, url, div_listado, div_iframe){
		iframe.contents().find("body").html('');
		iframe.attr("src",url);		
	
		cargador_visibility('show');		
		iframe.unbind('load');
		iframe.on('load', function(){
			cargador_visibility('hide');
			div_listado.hide();
			div_iframe.show( "slow" );
			iframe.unbind('load');			
		});
		
}//end function iframe_load


/*
Example: 	parametros = 'empresa_id=4&rol_cab_sec=21&sucursal_id=39';
			iframe_openAppTab(16, parametros);


*/
function iframe_openAppTab(opcion, parametros){
	parent.parent.openAppTab(opcion, parametros);
	return false;
}//end function iframe_openAppTab


function iframe_resize(iframe){
	   $(window).resize(function () {
		    waitForFinalEvent(function(){
		    	  var newheight =  window.frameElement.scrollHeight - 20;
				  iframe.attr('height', newheight);
				  resize_JqGrid_Refresh();
		    }, 500, "some unique string");
		});		
	   $(window).resize();
}//end function iframe_resize
