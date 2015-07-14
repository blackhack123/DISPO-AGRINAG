
	/**
	* Para poder utilizar esta función, deberá de realizar lo siguiente:
	* 1) EN BODY, debe declararlo con el siguiente formato:
	*        <div id="tips_diasnolaborados_27" titulo="TIPS">
	*              Aqui viene el texto de guia que debe de aparecer
	*		 </div>
	*
	*	  Parametros:	
	*         1) titulo      -->  Es el titulo del TIPS
	*         3) imagen_size -->  (Opcional) Es el tamaño
	*
	* 2) EN el javascript:
	* 			$(function() {
	*					   tips_create($("#tips_diasnolaborados_27"));
	*			});
	*
	*  Con los pasos antes mencionados es suficiente para generar el contenido del TIPS
	*
	*/
	function tips_create(div_control){
		var titulo 		= div_control.attr('titulo');
		var imagen_size = div_control.attr('imagen-size');
		var texto 		= div_control.html();		
		
		if (imagen_size == undefined  || imagen_size == ''){
			size_witdh 		= 60;
			size_height 	= 60;
		}else{
			size_witdh		= imagen_size;
			size_height		= imagen_size;
		}//end if
		
		//console.log('size_witdh:'+size_witdh+'*size_height:'+size_height);
		html = '<table border="0" cellpadding="1" cellspacing="1">'
			+'  <tr>'
			+'    <td valign="top"><img src="js/custom-tips/images/tips.png" width="'+size_witdh+'" height="'+size_height+'" /></td>'
			+'    <td valign="top" class="tips_fondo">'
			+'		<table width="100%" border="0" cellpadding="1" cellspacing="1" >'
			+'		  <tr>'
			+'			<td class="tips_titulo">'+titulo+'</td>'
			+'		  </tr>'
			+'		  <tr>'
			+'			<td class="tips_texto">'+texto
			+'				<br />'
			+'				<div style="height:15px"></div>'
			+'			</td>'
			+'		  </tr>'
			+'		</table>'
			+'	</td>'
			+'  </tr>'
			+'</table>';
	
		div_control.removeAttr('titulo');
		div_control.removeAttr('imagen-size');
		div_control.removeAttr('texto');		
		div_control.html(html);
		return true;
	}//end function tips_create

