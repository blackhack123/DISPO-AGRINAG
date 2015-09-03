
/*La columna de la grilla que tiene el checkbox debe de llamarse siempre seleccion*/
function jqgrid_fila_seleccionada(id_grid){
	var data = $("#"+id_grid).getRowData();
	for (var i = 0; i < data.length; i++) {
		var seleccion = data[i].seleccion;

		if (seleccion==1) return true;
	}
	return false;
}//end function	jqgrid_fila_seleccionada


/*Obtiene los Ids que han sido seleccionado, mendiante el checkbox llamado seleccion*/
function jqgrid_get_ids_seleccionado(id_grid, col_id){
	//var data = $("#grid_usuario_1").jqGrid('getGridParam', 'data');
	var data = $("#"+id_grid).getRowData();
	var ids = new Array();
	var ind = 0;
	for (var i = 0; i < data.length; i++) {
		var seleccion = data[i].seleccion;
		
		if (seleccion==1){
			
			ids[ind] = eval("data[i]."+col_id);
			ind++;
		}//end if
	}//end for			
	return ids;
}//end function	jqgrid_get_ids_seleccionado


//funcion para obtener el id requerido en jqgrid option multiselect
function jqgrid_get_ids_seleccionado_multiselect(id_grid, col_id){
	 var seleccionado	=$("#"+id_grid).jqGrid('getGridParam','selarrrow');
	 var ids = new Array();
	 var ind = 0;
	 
	 for (var i = 0; i < seleccionado.length; i++) {
		 var data= new Array();
		  data =$("#"+id_grid).jqGrid('getRowData',seleccionado[i]);
	   
		  ids[ind] = eval("data."+col_id);
			ind++
		 }//end for

	return ids;
}//end function	jqgrid_get_ids_seleccionado_multiselect	



/**
  * Permite identificar si una fila a sido seleccionada o no, en caso tener alguna fila seleccionada devolverá el valor de TRUE
  */
function jqgrid_fila_seleccionada_id_v2(id_grid){
		var seleccionado	=$("#"+id_grid).jqGrid('getGridParam','selarrrow');
		if (seleccionado.length) return true;
	
	return false;
}//end function	jqgrid_fila_seleccionada



/**
  * Funcion que permite obtener la fila completa de las Filas Seleccionadas (NO POR COLUMNAS como el procedimiento jqgrid_get_ids_seleccionado_multiselect)
  * esta funcion permite optimizar el acceso y la aceleración para la obtención de los rows
  */
function jqgrid_get_data_seleccionado_v2(id_grid){
	
	var seleccionado	=$("#"+id_grid).jqGrid('getGridParam','selarrrow');
	var arr_data = new Array();
	var ind = 0;
	 
	for (var i = 0; i < seleccionado.length; i++) {
		 var data = new Array();

		var isDisabled = $("#"+id_grid+"_"+seleccionado[i]).is(':disabled');  //MORONITOR REVISAR ESTO
		//console.log('isDisabled:',isDisabled);
		if (!isDisabled) {			 
			data =$("#"+id_grid).jqGrid('getRowData',seleccionado[i]);

			arr_data.push(data);
			ind++
		}//end if
	}//end for

	if (ind==0){
		return false;
	}else{
		return arr_data;
	}
}//end function	jqgrid_get_data_seleccionado


var jqgrid_get_columnIndexByName = function(grid,columnName) {
	//var cm = $("#"+id_grid).jqGrid('getGridParam','colModel');
	var cm = grid.jqGrid('getGridParam','colModel');
	for (var i=0,l=cm.length; i<l; i++) {
		if (cm[i].name===columnName) {
			return i;
		}
	}
	return -1;
};




/*
 * Esta funcion permite realizar un PRCHE para controlar el evento CLIK del CHECKBOX en el modo de edición, logrando poder funcionar
 * correctamente debido al error que se produce cuando se activa el celledit:true y el formatoptions: {disabled:false}.
 * 
 * Los parámetros que se deben de enviar son:
 * 	1) Objeto checkbox
 *  2) Id del Grid
 *  3) rowId
 *  4) Nombre de la columna que contiene el CheckBox.
 *  5) Nombre de la columna Accion (opcional), en caso de envir vacio este parametro simplemente omitirá la columna acción dentro de esta funcion.
 *  
 *  Se debe de tener en cuente que el formater debe del checkbox debe de ser invocado como se muestra en el siguiente ejemplo:
 *  	{name:'est_discapacitado',index:'est_discapacitado', width:100, editable:true,  edittype:"checkbox", formatoptions: {disabled : false},  align: 'center', editoptions:{value:"1:0"},
 * 			formatter:function (cellvalue, options, rowObject)  
 *			{
 *				control = '<input type="checkbox" '+ (cellvalue==1 ? 'checked' : '') + ' onclick=javascript:jqgrid_eventCheck(this,"grid_carga_familiar_3","'+options.rowId+'","est_discapacitado","accion") />';
 *				return  control;		
 *			}
 *		}
 */
function jqgrid_eventCheck(objCheck, grid, rowid, col_checkbox, col_accion){
	var valor = objCheck.checked;	
	if (valor){
		$('#'+grid).jqGrid('setCell',rowid, col_checkbox,'1');
	}else{
		$('#'+grid).jqGrid('setCell',rowid, col_checkbox,'0');
	}//end if
	if (col_accion!=''){
		var accion =  $('#'+grid).jqGrid('getCell',rowid, col_accion);
		if (accion=='C'){
		    $('#'+grid).jqGrid('setCell',rowid, col_accion,'M');
		}//end if
	}//end if
}//end function jqgrid_eventCheck




/**
 * Integración Jqgrid + DatePicker
 * 
 * La variable jqgrid_formatoptions_date, permite configurar el formato de la fecha que recibe del servidor y como lo de interpretar en el frontend.
 * La funcion jqgrid_init_date, permite integrarlo al jqgrid mediante el datainit del editoptions.
 * 
 * Ejemplo de como se lo debe de implementar en el colModel:
 * 
 *        jQuery("#grid_prestamo_empleado_amortizacion_32").jqGrid({
 *			.
 *			.		
 *         	colNames:['','','Cuota','Fec.Pago','valor Cuota ','Capital','Interes','Saldo','Estado',''],
 *         	colModel:[
 *             .
 *             .
 *             .
 *             {name:'fecha_cuota',index:'fecha_cuota', width:100, sorttype:"date",editable:true, formatter:'date', editrules: { date: true },
 *              	    formatoptions:jqgrid_formatoptions_date,
 *          			editoptions: {dataInit:	jqgrid_init_date},
 *             }, 
 *          ]
 *        });  
 * 
 */
var jqgrid_formatoptions_date = $.parseJSON('{"srcformat": "Y-m-d", "newformat": "d-m-Y"}');

function jqgrid_init_date(elem) {               										
		$(elem).datepicker({ autoSize: true, 
							 showOn: "both",  
							 numberOfMonths: 3,
							 showButtonPanel: true}
		).next().hide();
}//end function jqgrid_init_date



var ajax_process_jqrid_Buscador = null;
function jqrid_Buscador(params)
{
	ajax_process_jqrid_Buscador = $.ajax({
											url: basePath + '/application/jqgridbuscador/dialog',
											type: "GET",
											data: params,
											dataType: "html",
											beforeSend : function(){
												if (ajax_process_jqrid_Buscador) {
													ajax_process_jqrid_Buscador.abort();
												}
											},
										}).done(function(msg) {
											$("#dialog-buscador-JqGrid").html( msg );
										
											//Pasa el foco al siguiente control para evitar conflicto en el ENTER del DIALOG
											$("#txt_buscar_DialogBuscador_JqGrid").focus();
										
											//Abre la ventana de Dialogo
											$("#dialog-buscador-JqGrid").dialog( "open" );
											cargador_visibility('hide');
										
										}).error(function(request, status, error) {
											message_error('ERROR', request.responseText);
											cargador_visibility('hide');
										});	
}//jqrid_Buscador