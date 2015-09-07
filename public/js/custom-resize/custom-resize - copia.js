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