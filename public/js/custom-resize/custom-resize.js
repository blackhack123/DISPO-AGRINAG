/*---------------------------------------------------------*/
/*---------Se configura la redimension de los JqGRID-------*/
/*---------------------------------------------------------*/

/*---------------------------------------------------------*/			
/*---------------------------------------------------------*/

/**
 * Nota: Se utiliza la variable global_layout la misma que es establecida en el Layout de la aplicacion,
 * 		 el objetivo de esta variable poder redimensionar los JqGrid que estan en los contenedores
 */

/*
 * Array de Jqgrid's para la redimension
 */
var resize_array_JqGrid = [];


/**
 * Registra el Grid que se va a redimensionar con la pantalla
 * 
 * @param JqGrid_id
 * @returns {Boolean}
 */
function resize_JqGrid_Add(JqGrid_id)
{
	//Se pregunta si existe el Id en el array
	var indice = resize_array_JqGrid.indexOf(JqGrid_id);
	
	if (indice==-1)
	{
		resize_array_JqGrid.push(JqGrid_id);
	}//end if

	resize_JqGrid_Refresh();
	return true;
}//end function resize_JqGrid_Add



/**
 * Permite optimizar el array del JqGrid para evitar llamados innecesarios
 */
function resize_Array_JqGrid_Depurar()
{
	size = resize_array_JqGrid.length;
	i = 0;
	while (i < size)
	{
		id_jqGrid = resize_array_JqGrid[i];
		if ($(id_jqGrid).length==0)
		{
			resize_array_JqGrid.splice(i,1);
			size = resize_array_JqGrid.length;
		}else{
			i++;
		}//end if
	}//en while
}//end function resize_Array_JqGrid_Depurar


/**
 * Permite Refrescar el redimensionamiento de todos los JqGrid's
 * Esta funcion es llamada desde la funcion center__onresize del Layout
 */
function resize_JqGrid_Refresh()
{
/*	if (typeof(global_layout)=== 'undefined'){
		return false;	
	}
*/	
	resize_Array_JqGrid_Depurar();
	
	var $Pane = global_layout.panes.center;
	//var outerWidth = $Pane.outerWidth();
	var innerWidth = $Pane.innerWidth()-30;  
	for(var i=0; i< resize_array_JqGrid.length; i++)
	{
		id_jqGrid = resize_array_JqGrid[i];
		if ($(id_jqGrid).length)
		{
			$(id_jqGrid).setGridWidth(innerWidth);
		}//end if
	}//end for
	
}//end function function resize_JQGrid


/*
 * LAS SIGUIENTES LINEAS SOLO SON LINEAS DE CONSULTA PARA EL REDIMENSIONAMIENTO MEDIANTE EL EVENTO WINDOW 
 */
/*
$(window).bind('resize', function() {
    var $Pane = global_layout.panes.center;
    //var outerWidth = $Pane.outerWidth();
    var innerWidth = $Pane.innerWidth()-30;  
   //console.log('resize');
    $(JqGrid).setGridWidth(innerWidth);
}).trigger('resize');
*/



/**
 * Implementado solo para el Layout Iframe, en el otro Layout no ha sido probado y se 
 * se desconoce su comportamiento
 *  
 * Permite hacer el ancho del grid cambie dinamicamente de acuerdo a la suma del ancho
 * de las columnas
 * 
 * Implementaci�n, en el JqGrid debe de estar parametrizado de la siguiente forma:
 * 		shrinkToFit: false,
 *		loadComplete: grid_setAutoWidth,
 *		resizeStop: grid_setAutoWidth,
 *
 * Incoporar la clase grid-autowidth, como en el siguiente ejemplo:
 *  	<table id="equipo_grid" class="grid grid-autowidth"></table> 
 */
/*var grid_setAutoWidth = function() {
	$.each($('.grid-autowidth'), function() {
		autoWidth_JqGrid(this.id)
	});	
};


function executeAutoWidth_JqGrid()
{
	$.each($('.grid-autowidth'), function() {
		autoWidth_JqGrid(this.id)
	});	
}
*/

/**
 * M�todo invocado de forma automatica por medio del redimensionamiento de la ventana 
 */
/*function autoWidth_JqGrid(id)   //Expand Width
{
	grid = eval('$("#'+id+'")');
	var columnas = grid.jqGrid('getGridParam', 'colModel');
	console.log('columnas:',columnas);

	//Se averigua si tiene activo el grid-colhidden
	colMarginHidden = grid.hasClass('grid-marginhidden');

	var anchoTotal = 0;
	//var columnasVisibles = 0;
	for (var i = 0; columnas[i]; i++) {
		if (colMarginHidden!=0){
			if ((columnas[i].hidden == false) || (typeof columnas[i].hidden === "undefined")){
				anchoTotal += columnas[i].width + 9;
			//columnasVisibles++;
			}//end if
		}else{
			anchoTotal += columnas[i].width;
		}
	}//end for

	//anchoTotal += colhidden;

	//anchoextra = (columnasVisibles - 1) * 10;
	//anchoTotal += 50;
	grid.jqGrid('setGridWidth', anchoTotal);	
}
*/


var grid_setAutoWidthContainer = function() {
	$.each($('.grid-autowidthcontainer'), function() {
		autoWidthContainer_JqGrid(this.id)
	});	
};

function autoWidthContainer_JqGrid(id)
{
	if (typeof id === "undefined") return false;
	grid = eval('$("#'+id+'")');
	
	newWidth = grid.closest(".ui-jqgrid").parent().width();
	grid.jqGrid("setGridWidth", newWidth, true);	
}//end function autoWidthContainer_JqGrid



var grid_setAutoHeight = function() {
/*	var grid = this;
	console.log('paso01', this.id);
	console.log('paso02', $(this).attr('id'));	
	autoHeight_JqGrid_Refresh(grid.id);
*/	
	$.each($('.grid-autoheight'), function() {
		autoHeight_JqGrid_Refresh(this.id)
	});	
};





/**
 * 
 * Implementado solo para el Layout Iframe, en el otro Layout no ha sido probado y se 
 * se desconoce su comportamiento
 * 
 * Permite que el alto del Grid cambie de manera din�mica de acuerdo al alto de la ventana
 * incluso cuando se redimensiona
 * 
 * Implementaci�n en el HTML, se debe de especificar la clase "grid-autoheight":
 * 
 * 			<table id="presupuesto_grid" class="grid grid-autoheight"></table>   
 * 
 */
function autoHeight_JqGrid_Refresh(id)
{	
	if (typeof id === "undefined") return false;
	grid = eval('$("#'+id+'")');

	//$(this).trigger("resize");

	var viewportHeight = window.innerHeight + window.scrollMaxY;	
	var pageheight = getPageHeight();
//	var offset =  grid.offset();
	//var position =  grid.offsetParent().offset();	 //IFRAME
//	var position =  grid.offset();	//NORMAL

	var offset = grid.parents('div.ui-jqgrid-bdiv').offset()

	calculo = grid.offset().top - $("body").offset().top;
/*	console.log('viewportHeight',viewportHeight);
	console.log('grid offset top',offset.top);
	console.log('grid position top',position.top);		
	console.log('pageheight',pageheight);
	console.log('calculo',calculo);
*/	
	var alto = pageheight - offset.top - 50;
	grid.jqGrid('setGridHeight', alto); 
	
	/* 
	 * --------------------------------------------------------
	 * Acople para que no se vea el scroll bar horizontal
	 * -------------------------------------------------------- 
	 */
	var dataFirstAutoHeight =  grid.data('firstAutoHeight');
	
	if (typeof dataFirstAutoHeight == 'undefined')
	{
		grid.data('firstAutoHeight', 1);
		
		var columnas = grid.jqGrid('getGridParam', 'colModel');

	    var anchoTotal = 0;
	    for (var i = 0; columnas[i]; i++) {
			if (columnas[i].hidden==false)
			{
		        anchoTotal += columnas[i].width;
			}//end if
	    }

	    anchoTotal += 50;
	    grid.jqGrid('setGridWidth', anchoTotal);		
	}//end if

}


/**
 * Permite obtener el verdadero alto de la P�gina que se visualiza en la pantalla,
 * conocido como ViewPort (Area de Visualizacion)
 */
function getPageHeight() {
	var windowHeight = 0;
	if (self.innerHeight) { // all except Explorer
	  windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
	  windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
	  windowHeight = document.body.clientHeight;
	}
	return windowHeight;
}