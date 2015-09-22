/**
 * 
 */
var selRowId_TipoCajaMarcacionGrid 		= 0;	
var selColName_TipoCajaMarcacionGrid 	= 0;	
var seliRow_TipoCajaMarcacionGrid 		= 0;		
var seliCol_TipoCajaMarcacionGrid 		= 0;	
var valAnt_TipoCajaMarcacionGrid		= null;
var secMax_TipoCajaMarcacionGrid		= 0;

var arr_eliminados_tipocajamarcacion	= [];

$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
/*	mantenimiento_init();
	
	$("#frm_tipo_caja_marcacion #inventario_id, #frm_tipo_caja_marcacion #tipo_caja_id").on('change', function(event){
		$('#grid_tipo_caja_marcacion').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});
*/

	$("#frm_tipo_caja_marcacion #btn_consultar").on('click', function(event){ 
		$('#grid_tipo_caja_marcacion').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});
	
	
	$("#frm_tipo_caja_marcacion #btn_row_new").on('click', function(event){ 
		TipoCajaMarcacion_AddRow();
	});	
	
	
	
	$("#frm_tipo_caja_marcacion #btn_row_eliminar").on('click', function(event){ 	
		TipoCajaMarcacion_DelRow();
	});		
		
/*	
	$("#frm_tipo_caja_marcacion #btn_actualizacion_masiva").on('click', function(event){ 
		TipoCajaMarcacion_OpenModalActualizacionMasiva();
		return false;
	});		
*/
	$("#frm_tipo_caja_marcacion #btn_grabar").on('click', function(event){ 
		TipoCajaMarcacion_Grabar();
		return false;
	});		
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID ----------------------------------*/
	/*---------------------------------------------------------------*/
	var cbo_tipo_caja 	= '';
	var cbo_inventario 	= '';
	var cbo_grado 		= ''
	$.ajax({
		url: '../../dispo/tipocajamarcacion/getCombosDataGrid',
		async: false, 
		success: function(data, result) {
			if (!result) message_error('ERROR', 'Error al cargar combos del GRID');
			cbo_tipo_caja 	= data.opciones_tipo_caja;
			cbo_inventario 	= data.opciones_inventario;
			cbo_grado 		= data.opciones_grado;
		}
	}).responseText;

			

	jQuery("#grid_tipo_caja_marcacion").jqGrid({
		url:'../../dispo/tipocajamarcacion/listadodata',
		postData: {
			cliente_nombre: 	function() {return $("#frm_tipo_caja_marcacion #cliente_nombre").val();},
			marcacion_nombre: 	function() {return $("#frm_tipo_caja_marcacion #marcacion_nombre").val();},
		},
		datatype: "json",
		loadonce: true,		
		rowNum:999999,
		pager: '#pager_tipo_caja_marcacion',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		shrinkToFit: false,
		resizeStop: grid_setAutoHeight, 
		rownumbers: true,
		cellEdit: true,
		cellsubmit: 'clientArray',
		editurl: 'clientArray',	
		jsonReader: {
			repeatitems : false,
		},				
		/*height:'400',*/
		colNames:['Accion','Id','cliente_id','marcacion_sec','variedad_id','Cliente','Marcacion','Tipo Caja','Inventario','Variedad','Grado','Bunches'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'accion',index:'accion', width:60, align:"center", hidden: true},
			{name:'id',index:'id', width:50, align:"center", sorttype:"int", hidden: true},
			{name:'cliente_id',index:'cliente_id', width:50, align:"center", sorttype:"string", hidden: true},
			{name:'marcacion_sec',index:'marcacion_sec', width:50, align:"center", sorttype:"int", hidden: true},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int", hidden: true},
			{name:'cliente_nombre',index:'cliente_nombre', width:150, sorttype:"string", editable:true, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && e.shiftKey)   // tab
																{
																	if ((seliRow_TipoCajaMarcacionGrid - 1) <= 0) return false;
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " - 1, " + jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "unds_bunch") + ", true);", 10);														
																}																
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " + 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " - 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
																}													
															}
														} 
													]													
									 },
			},
			{name:'marcacion_nombre',index:'marcacion_nombre', width:150, sorttype:"string", editable:true, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " + 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " - 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }
			},	
			{name:'tipo_caja_id',index:'tipo_caja_id', width:100, align:"center", sorttype:"string", editable:true, edittype:"select", formatter:'select', editoptions:{value: cbo_tipo_caja}},	
			{name:'inventario_id',index:'inventario_id', width:70, align:"center", sorttype:"string", editable:true, edittype:"select", formatter:'select', editoptions:{value: cbo_inventario}},	
			{name:'variedad_nombre',index:'variedad_nombre', width:150, sorttype:"string", editable:true, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " + 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " - 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }
			},	
			{name:'grado_id',index:'grado_id', width:50, align:"center", sorttype:"string", editable:true,edittype:"select", formatter:'select', editoptions:{value: cbo_grado}},	
			{name:'unds_bunch',index:'unds_bunch', width:70, align:"center", sorttype:"int", editable:true, formatter: gridTipoCajaMarcacion_GradosFormatter, unformatter: gridTipoCajaMarcacion_GradosFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
											{ 
												type: 'keydown', 
												fn: function(e) { 
													var key = e.charCode || e.keyCode;
													if(key == 9 && !e.shiftKey)   // tab
													{
														setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " + 1, " + jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "cliente_nombre") + ", true);", 10);														
													}
													if ((key == 13)||(key == 40))//enter, abajo
													{
														setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " + 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);
													}
													else if ((key == 38))//arriba
													{
														setTimeout("jQuery('#grid_tipo_caja_marcacion').editCell(" + seliRow_TipoCajaMarcacionGrid + " - 1, " + seliCol_TipoCajaMarcacionGrid + ", true);", 10);													
													}//end if													
												}
											}
										]																		
									 }					
			}
		],	
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
		loadComplete: function()
		{			
			var ids = jQuery("#grid_tipo_caja_marcacion").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) 
			{
				var rowId = ids[i];
				var rowData = jQuery('#grid_tipo_caja_marcacion').jqGrid ('getRowData', rowId);
				rowData.accion = 'C';
				$("#grid_tipo_caja_marcacion").jqGrid('setRowData', rowId, rowData);
			}		
			
			userdata = $("#grid_tipo_caja_marcacion").jqGrid('getGridParam', 'userData');

			secMax_TipoCajaMarcacionGrid = userdata.sec_maximo;	
			
			autoHeight_JqGrid_Refresh("grid_tipo_caja_marcacion");	
			
			arr_eliminados_tipocajamarcacion =  []; //Vacia el array de elementos eliminados
		},		
		beforeEditCell : function(rowid, cellname, value, iRow, iCol)
		{
			selRowId_TipoCajaMarcacionGrid = rowid;
			seliCol_TipoCajaMarcacionGrid  = iCol;
			seliRow_TipoCajaMarcacionGrid  = iRow;
			valAnt_TipoCajaMarcacionGrid   = value;
		},	
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			var col_accion 		 		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "accion");
			var col_cliente_id		 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "cliente_id");			
			var col_cliente_nombre	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "cliente_nombre");
			var col_marcacion_sec		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "marcacion_sec");			
			var col_marcacion_nombre	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "marcacion_nombre");
			var col_inventario_id	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "inventario_id");
			var col_variedad_id	 		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "variedad_id");			
			var col_variedad_nombre	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "variedad_nombre");
		
			switch(iCol)
			{
				case col_cliente_nombre:					
					var cliente_nombre = jQuery("#grid_tipo_caja_marcacion").jqGrid('getCell',rowid, col_cliente_nombre);
					/*var empresa_id =  $("#empresa_id_53").val();*/
					var params = { 'title':'BUSCADOR DE CLIENTES',
								   'grid_url': 				basePath+'/dispo/cliente/listadodialogdata',
								   'term':				    cliente_nombre,
								   'grid_source_id': 		'grid_tipo_caja_marcacion',
								   'grid_source_rowid':		rowid,
								   'grid_dialog_columns': 	[
																{'title':'id','name':'id', 'index':'id','sorttype':'int', 'hidden':'true'},
																{'title':'Nombre','name':'nombre', 'index':'nombre', 'width':'450', 'sorttype':'string'},		
															],
								   'link_columns_grid_to_dialog': 
															[
																{'col_source':'cliente_nombre', 'col_dialog':'nombre'},	
																{'col_source':'cliente_id', 'col_dialog':'id'},
															],	
								  'filters': {'estado':'A'},
								 };					
					jqrid_Buscador(params); //MORONITOR
					
					$("#grid_tipo_caja_marcacion").jqGrid('setCell', iRow, col_marcacion_sec, null);
					$("#grid_tipo_caja_marcacion").jqGrid('setCell', iRow, col_marcacion_nombre, null);
					break;

				case col_marcacion_nombre:
					var marcacion_nombre = jQuery("#grid_tipo_caja_marcacion").jqGrid('getCell',rowid, col_marcacion_nombre);
					var cliente_id 		 = jQuery("#grid_tipo_caja_marcacion").jqGrid('getCell',rowid, col_cliente_id);
					
					if (cliente_id=='')
					{
						$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_marcacion_sec, null);
						$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_marcacion_nombre, null);
						return false;
					}//end if

					var params = { 'title':'BUSCADOR DE MARCACION',
								   'grid_url': 				basePath+'/dispo/marcacion/listadodialogdata',
								   'term':				    marcacion_nombre,
								   'grid_source_id': 		'grid_tipo_caja_marcacion',
								   'grid_source_rowid':		rowid,
								   'grid_dialog_columns': 	[
																{'title':'id','name':'marcacion_sec', 'index':'marcacion_sec','sorttype':'int', 'hidden':'false'},
																{'title':'Nombre','name':'nombre', 'index':'nombre', 'width':'450', 'sorttype':'string'},		
															],
								   'link_columns_grid_to_dialog': 
															[
																{'col_source':'marcacion_sec', 'col_dialog':'marcacion_sec'},	
																{'col_source':'marcacion_nombre', 'col_dialog':'nombre'},
															],	
								  'filters': {
									  			'cliente_id':cliente_id,
									  			'estado':'A',
								  			 },
								 };					
					jqrid_Buscador(params); //MORONITOR
					break;


				case col_inventario_id:
					$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_variedad_id, null);
					$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_variedad_nombre, null);
					break;

				case col_variedad_nombre:
					var inventario_id 	= jQuery("#grid_tipo_caja_marcacion").jqGrid('getCell',rowid, col_inventario_id);
					var variedad_nombre = jQuery("#grid_tipo_caja_marcacion").jqGrid('getCell',rowid, col_variedad_nombre);
					
					if (inventario_id=='')
					{
						$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_variedad_id, null);
						$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_variedad_nombre, null);
						return false;
					}//end if
					
					var params = { 'title':'BUSCADOR DE VARIEDADES',
								   'grid_url': 				basePath+'/dispo/disponibilidad/listadovariedaddialogdata',
								   'term':				    variedad_nombre,
								   'grid_source_id': 		'grid_tipo_caja_marcacion',
								   'grid_source_rowid':		rowid,
								   'grid_dialog_columns': 	[
																{'title':'id','name':'variedad_id', 'index':'variedad_id','sorttype':'string', 'hidden':'true'},
																{'title':'Nombre','name':'variedad_nombre', 'index':'variedad_nombre', 'width':'450', 'sorttype':'string'},		
															],
								   'link_columns_grid_to_dialog':
															[
																{'col_source':'variedad_nombre', 'col_dialog':'variedad_nombre'},	
																{'col_source':'variedad_id', 'col_dialog':'variedad_id'},
															],
								  'filters': {
									  			'inventario_id': inventario_id,
									  			'estado':'A'
											 },
								 };					
					jqrid_Buscador(params); //MORONITOR

					break;

			}//end switch
		
			var accion = jQuery("#grid_tipo_caja_marcacion").jqGrid('getCell',rowid, col_accion);
			if (accion!='I')
			{
				$("#grid_tipo_caja_marcacion").jqGrid('setCell', rowid, col_accion, 'M');
			}//end if				
		},
	});
	//$("#grid_tipo_caja_marcacion").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	jQuery("#grid_tipo_caja_marcacion").jqGrid('navGrid','#pager_tipo_caja_marcacion',{edit:false,add:false,del:false});
		
		
	function gridTipoCajaMarcacion_GradosFormatter(cellvalue, options, rowObject){
		cellvalue = $.number( cellvalue, 0, '.',','); 		
		if ((cellvalue==0 )|| (cellvalue === undefined)){
				cellvalue = '';		
		}//end if
		return cellvalue;
	}
		
	function gridTipoCajaMarcacion_GradosUnFormatter(cellvalue, options, cell){
		return number_val(cellvalue);
	}
		

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
});


function TipoCajaMarcacion_AddRow()
{
	secMax_TipoCajaMarcacionGrid++;
	rowData = {accion:"I", id:null};
	$("#grid_tipo_caja_marcacion").addRowData(secMax_TipoCajaMarcacionGrid, rowData);
	
	number_rows = $("#grid_tipo_caja_marcacion").getGridParam("reccount");
	$('#grid_tipo_caja_marcacion').editCell(number_rows, jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "cliente_nombre"), true);
	return false;
}//end function TipoCajaMarcacion_AddRow


function TipoCajaMarcacion_DelRow()
{
	if (!seliRow_TipoCajaMarcacionGrid){
		message_alert('Mensaje del Sistema','Debe de seleccionar una fila para eliminarla');
		return false;	
	}//end if
	
	$('#grid_tipo_caja_marcacion').jqGrid('editCell', seliRow_TipoCajaMarcacionGrid, seliCol_TipoCajaMarcacionGrid, false);	
	
	var rowData = $('#grid_tipo_caja_marcacion').getRowData(selRowId_TipoCajaMarcacionGrid);
	
	if (rowData.accion!='I'){
		//Se elimina el elemento que se invoka
		var reg_eliminado = {id:rowData.id};
		arr_eliminados_tipocajamarcacion.push(reg_eliminado);
	}//end if
	$('#grid_tipo_caja_marcacion').delRowData(selRowId_TipoCajaMarcacionGrid);


	//Se establece la posicion del puntero de edicion
	var dataIDs = $('#grid_tipo_caja_marcacion').getDataIDs();
	var len = dataIDs.length;
	if (seliRow_TipoCajaMarcacionGrid <= len){
		$('#grid_tipo_caja_marcacion').jqGrid('editCell', seliRow_TipoCajaMarcacionGrid, seliCol_TipoCajaMarcacionGrid, true);
	}else if (len > 0){
		$('#grid_tipo_caja_marcacion').jqGrid('editCell', seliRow_TipoCajaMarcacionGrid - 1, seliCol_TipoCajaMarcacionGrid, true);
	}else{
		seliRow_TipoCajaMarcacionGrid 	= 0;
		selRowId_TipoCajaMarcacionGrid 	= null;				
	}//end if		
}//end function TipoCajaMarcacion_DelRow



function TipoCajaMarcacion_validaGrid(){
	var col_accion		 		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "accion");
	var col_cliente_id		 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "cliente_id");			
	var col_cliente_nombre	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "cliente_nombre");
	var col_marcacion_sec		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "marcacion_sec");			
	var col_marcacion_nombre	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "marcacion_nombre");
	var col_inventario_id	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "inventario_id");
	var col_variedad_nombre	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "variedad_nombre");
	var col_tipo_caja_id	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "tipo_caja_id");	
	var col_grado_id	 		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "grado_id");
	var col_unds_bunch	 		= jqgrid_get_columnIndexByName($("#grid_tipo_caja_marcacion"), "unds_bunch");

	var ids = jQuery("#grid_tipo_caja_marcacion").jqGrid('getDataIDs');
	
	for (var i = 0; i < ids.length; i++) 
	{
		$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_accion, false);	

		var rowId = ids[i];
		var rowData = jQuery('#grid_tipo_caja_marcacion').jqGrid ('getRowData', rowId);

		if (rowData.cliente_nombre == ''){
			alert('Ingrese el cliente');
			$('#grid_tipo_caja_marcacion').focus();
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_cliente_nombre, true);
			return false;
		}//end if
		if (rowData.marcacion_nombre == ''){
			alert('Ingrese Marcacion');
			$('#grid_tipo_caja_marcacion').focus();
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_marcacion_nombre, true);
			return false;
		}//end if
		if (rowData.tipo_caja_id == ''){
			alert('Ingrese Tipo Caja');
			$('#grid_tipo_caja_marcacion').focus();
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_tipo_caja_id, true);	
			return false;				
		}//end if
		if (rowData.inventario_id == ''){
			alert('Ingrese Inventario');											
			$('#grid_tipo_caja_marcacion').focus();				
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_inventario_id, true);
			return false;
		}//end if
		if (rowData.variedad_id == ''){
			alert('Ingrese la variedad');
			$('#grid_tipo_caja_marcacion').focus();
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_variedad_nombre, true);
			return false;
		}//end if
		if (rowData.grado_id == ''){
			alert('Ingrese el grado');
			$('#grid_tipo_caja_marcacion').focus();
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_grado_id, true);
			return false;
		}//end if
		if (rowData.unds_bunch == ''){
			alert('Ingrese las Unidades de Bunch');
			$('#grid_tipo_caja_marcacion').focus();
			$('#grid_tipo_caja_marcacion').jqGrid('editCell', i+1, col_unds_bunch, true);
			return false;
		}//end if
	}//end for

	return true;
}//end function TipoCajaMarcacion_validaGrid



function TipoCajaMarcacion_Grabar()
{
	if (!TipoCajaMarcacion_validaGrid()){
		return false;
	}
	
	$('#grid_tipo_caja_marcacion').jqGrid('editCell', seliRow_TipoCajaMarcacionGrid , seliCol_TipoCajaMarcacionGrid , false);
	
	//Se prepara el array
	TipoCajaMarcacion_arr_data = $('#grid_tipo_caja_marcacion').jqGrid('getGridParam','data');

	//Buffer Equipo
	var TipoCajaMarcacion_arr_data_ingresar 	= new Array();
	var TipoCajaMarcacion_arr_data_modificar = new Array();
	var TipoCajaMarcacion_arr_data_eliminar 	= new Array();		

	//Se arma el buffer para el ingreso y la modificacion
	var ids = jQuery("#grid_tipo_caja_marcacion").jqGrid('getDataIDs');
	
	for (var i = 0; i < ids.length; i++) 
	{	
		var rowId = ids[i];
		var rowData = jQuery('#grid_tipo_caja_marcacion').jqGrid ('getRowData', rowId);
		
		if ((rowData.accion == 'I')||(rowData.accion == 'M')){
			var element	= {};
			element.id				= rowData.id;
			element.marcacion_sec	= rowData.marcacion_sec;
			element.variedad_id		= rowData.variedad_id;
			element.tipo_caja_id	= rowData.tipo_caja_id;
			element.inventario_id 	= rowData.inventario_id;
			element.grado_id		= rowData.grado_id;
			element.unds_bunch		= number_val(rowData.unds_bunch, 0);
		}//end if
		
		switch (rowData.accion)
		{
			case 'I':
				TipoCajaMarcacion_arr_data_ingresar.push(element);
				break;							
			case 'M':
				TipoCajaMarcacion_arr_data_modificar.push(element);
				break;
		}//end if
	}//end for

	//Se arma el buffer de eliminados
	for (var i = 0; i < arr_eliminados_tipocajamarcacion.length; i++) {
		var element	= {};
		element.id 								= arr_eliminados_tipocajamarcacion[i].id;
		TipoCajaMarcacion_arr_data_eliminar.push(element);
	}//end for

	//---------------------------------------------------------------------	
	//---------------------------------------------------------------------		
	//Se arma el buffer para grabar 
	//---------------------------------------------------------------------
	var data = {			
		gridTipoCaja_ingresar: 	TipoCajaMarcacion_arr_data_ingresar,
		gridTipoCaja_modificar: TipoCajaMarcacion_arr_data_modificar,
		gridTipoCaja_eliminar: 	TipoCajaMarcacion_arr_data_eliminar,
	};
	data = JSON.stringify(data);	


	//---------------------------------------------------------------------	
	//---------------------------------------------------------------------		
	//Se invoca la ejecucion por AJAX para procesar los cambios del APU	
	//---------------------------------------------------------------------
	var parameters = {	'type': 'post',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamarcacion/grabar',
						'show_cargando':true,
						'finish':function(response){
								if (response.respuesta_code=='OK'){
									message_info('Mensaje del Sistema',"Datos Grabados con éxito");
									//$('#presupuesto_grid').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
									$('#grid_tipo_caja_marcacion').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");

								}else{
									message_error('ERROR', response);
								}//end if
						}
					 }
	ajax_call(parameters, data);		

	return false;		
}//end function TipoCajaMarcacion_TipoCajaMarcacion_GrabarMasivo