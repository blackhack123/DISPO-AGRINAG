/**
 * 
 */
var selRowId_PrecioGrupoGrid 	= 0;	
var selColName_PrecioGrupoGrid 	= 0;
var seliRow_PrecioGrupoGrid 	= 0;		
var seliCol_PrecioGrupoGrid 	= 0;	
var valAnt_PrecioGrupoGrid		= null;

var primera_vez_gridPrecioGrupo = true;

$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	$("#frm_precio_grupo #btn_nuevo_grupo_precio").on('click', function(event){ 
		GrupoPrecio_Nuevo(); 
		return false;
	});		
	
	$("#frm_precio_grupo #btn_modificar_grupo_precio").on('click', function(event){ 
		$("#frm_precio_grupo_mantenimiento #accion").val('M');
		PrecioGrupo_Consultar($("#frm_precio_grupo #grupo_precio_cab_id").val())	
	});
	
	
	$("#frm_grabar_precio_grupo #btn_grabar_grupo_precio").on('click', function(event){ 
		GrupoPrecio_GrabarMantenimiento();
		return false;
	});	
	
	
	$("#frm_precio_grupo #grupo_precio_cab_id").on('change', function(event){
		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		
		GrupoPrecio_ConsultarInfoPrecioGrupoCab($("#frm_precio_grupo #grupo_precio_cab_id").val());
		return false;		
	});
	
	
	$("#frm_precio_grupo #tipo_precio").on('change', function(event){
		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});	
	
	
	$("#frm_precio_grupo #color_ventas_id").on('change', function(event){
		if ($('#frm_precio_grupo #grupo_precio_cab_id').val()=='') 
		{
			$("#grid_precio_grupo").jqGrid('clearGridData');
			return false;
		}//end if

		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});	
	
	$("#frm_precio_grupo #btn_consultar").on('click', function(event){ 
		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	
	
	
	$("body #frm_precio_grupo #btn_row_new").on('click', function(event){ 
		Oferta_Nuevo();
	});	
	
	
	
	$("#frm_precio_grupo #btn_row_eliminar").on('click', function(event){ 	
		Oferta_Eliminar();
	});	
	
	
	$("#frm_oferta #btn_grabar_grupo_precio").on('click', function(event){ 	
		Oferta_Grabar();
	});		
			
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID del PRECIO X GRUPO----------------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_precio_grupo").jqGrid({
		url:'../../dispo/grupoprecio/listadodata',
		postData: {
			grupo_precio_cab_id: 	function() {return $("#frm_precio_grupo #grupo_precio_cab_id").val();},
			tipo_precio:			function() {return $("#frm_precio_grupo #tipo_precio").val();},
			color_ventas_id:		function() {return $("#frm_precio_grupo #color_ventas_id").val();}
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['producto_id','Id','Variedad','Color','ofer40','ofer50','ofer60','ofer70','ofer80','ofer90','ofer100','ofer110', '40','50','60','70','80','90','100','110'],
		colModel:[
			{name:'producto_id',index:'producto_id', width:50, sorttype:"string", hidden:true},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string"/*, formatter: gridDispoGrupo_VariedadNombreFormatter*/},
			{name:'color_ventas_nombre',index:'color_ventas_nombre', width:80, sorttype:"string"},			
			{name:'ofer40',index:'ofer40', width:50, hidden:true},
			{name:'ofer50',index:'ofer50', width:50, hidden:true},
			{name:'ofer60',index:'ofer60', width:50, hidden:true},
			{name:'ofer70',index:'ofer70', width:50, hidden:true},
			{name:'ofer80',index:'ofer80', width:50, hidden:true},
			{name:'ofer90',index:'ofer90', width:50, hidden:true},
			{name:'ofer100',index:'ofer100', width:50, hidden:true},
			{name:'ofer110',index:'ofer110', width:50, hidden:true},																					
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && e.shiftKey)   // tab
																{
																	if ((seliRow_PrecioGrupoGrid - 1) <= 0) return false;
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "110") + ", true);", 10);														
																}																
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]													
									 },														 
			},
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }											
			},
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }											
			},
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }											
			},
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }											
			},
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },		
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																			
									 }											
			},
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", editable:true, formatter: gridGrupoPrecio_GradosFormatter, unformat:gridGrupoPrecio_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
											{ 
												type: 'keydown', 
												fn: function(e) { 
													var key = e.charCode || e.keyCode;
													if(key == 9 && !e.shiftKey)   // tab
													{
														setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "40") + ", true);", 10);														
													}
													if ((key == 13)||(key == 40))//enter, abajo
													{
														setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " + 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);
													}
													else if ((key == 38))//arriba
													{
														setTimeout("jQuery('#grid_precio_grupo').editCell(" + seliRow_PrecioGrupoGrid + " - 1, " + seliCol_PrecioGrupoGrid + ", true);", 10);													
													}//end if													
												}
											}
										]																		
									 }											
			},
		],
		rowNum:999999,
		pager: '#pager_precio_grupo',
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
		/*multiselect: true, */
		jsonReader: {
			repeatitems : false,
		},
//		loadComplete: grid_setAutoHeight,
		loadComplete: function (data) {
			autoHeight_JqGrid_Refresh("grid_precio_grupo");
			primera_vez_gridPrecioGrupo = false;
		},
		loadBeforeSend: function (xhr, settings) {
			
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},
		beforeProcessing: function(data, status, xhr){
			primera_vez_gridPrecioGrupo = true;
		},
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
		beforeEditCell : function(rowid, cellname, value, iRow, iCol)
		{
			seliCol_PrecioGrupoGrid  = iCol;
			seliRow_PrecioGrupoGrid  = iRow;
			valAnt_PrecioGrupoGrid   = value;
			
			PrecioGrupo_CargarOferta(rowid, value, iRow, iCol);
		},			
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			//Evita se llame la funcion grabar sin que se haya modificado el valor
			var precio  = number_val(jQuery("#grid_precio_grupo").jqGrid('getCell',rowid, iCol), 2);
			if (number_val(precio)==number_val(valAnt_PrecioGrupoGrid))
			{
				return false;
			}//end if

			//Asigna las variables para poderlo pasar en la funcion grabar
			var grupo_precio_cab_id  = $("#frm_precio_grupo #grupo_precio_cab_id").val();
			var col_producto_id 	= jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "producto_id");
			var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "variedad_id");
			var producto_id			= jQuery("#grid_precio_grupo").jqGrid('getCell',rowid, col_producto_id);
			var variedad_id			= jQuery("#grid_precio_grupo").jqGrid('getCell',rowid, col_variedad_id);

			var grado_id			= jqgrid_get_columnNameByIndex($("#grid_precio_grupo"), iCol);
			var tipo_precio			= $("#frm_precio_grupo #tipo_precio").val();

			GrupoPrecio_Grabar(producto_id, grupo_precio_cab_id, variedad_id, grado_id, tipo_precio, precio);
		},
	});
	
	//Filtro
//	$("#grid_precio_grupo").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	
	function gridGrupoPrecio_GradosFormatter(cellvalue, options, rowObject){
		if (primera_vez_gridPrecioGrupo == true)
		{
			col_grado_name = options.colModel.name;
			col_oferta_name = 'ofer'+col_grado_name;
			
			//valor = eval('rowObject.'+col_oferta_name);
			
			precio_oferta	= number_val(eval('rowObject.'+col_oferta_name));			
		}else{
			pos_col_grado 	= options.pos;
			pos_col_oferta  = options.pos - 8;
			precio_oferta = number_val(rowObject[pos_col_oferta]);
			
		}//end if

		cellvalue = number_val(cellvalue, 2);		
		var color = "Black";
		if (cellvalue==0)
		{
			color = "LightGray";
		}
		
		if (precio_oferta>0)
		{
			color = "red";
		}
		
		cellvalue = $.number( cellvalue, 2, '.',','); 		
		new_format_value = '<span style="color:'+color+'">'+cellvalue+ '</a>';
		return new_format_value;
	}
		
	function gridGrupoPrecio_GradosUnFormatter(cellvalue, options, cell){
		return number_val($('span', cell).html());
	}	
		
		
	jQuery("#grid_precio_grupo").jqGrid('navGrid','#pager_precio_grupo',{edit:false,add:false,del:false});


	function gridDispoGrupo_VariedadNombreFormatter(cellvalue, options, rowObject)
	{
		if (rowObject.tallos_x_bunch==25)
		{
			new_format_value = rowObject.variedad;
		}else{
			new_format_value = rowObject.variedad + ' <em><b style="color:orange; font-style: italic;">('+rowObject.tallos_x_bunch+')</b></em>';
		}//end if
		return new_format_value;
	}//end function gridDispoGeneral_VariedadNombreFormatter

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de COMBO- ------------------------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_oferta").jqGrid({
		url:'../../dispo/grupoprecio/listadoofertadata',
		postData: {
			grupo_precio_cab_id: 	function() {return $("#frm_precio_grupo #grupo_precio_cab_id").val();},
			producto_id: 			function() {return $("#frm_precio_grupo #producto_seleccionado_id").val();},						
			variedad_id:			function() {return $("#frm_precio_grupo #variedad_seleccionada_id").val();},
			grado_id:				function() {return $("#frm_precio_grupo #grado_seleccionado_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['grupo_precio_cab_id','producto_id','variedad_id','grado_id','producto_combo_id','Id','Variedad','Color','Grado','Factor',''],
		colModel:[
			{name:'grupo_precio_cab_id',index:'grupo_precio_cab_id', width:50, align:"center", sorttype:"int", hidden:true},		
			{name:'producto_id',index:'producto_id', width:50, align:"center", sorttype:"int", hidden:true},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int", hidden:true},
			{name:'grado_id',index:'grado_id', width:50, sorttype:"string", hidden:true},
			{name:'producto_combo_id',index:'producto_combo_id', width:50, align:"center", sorttype:"int", hidden:true},
			{name:'variedad_combo_id',index:'variedad_combo_id', width:30, sorttype:"string"},
			{name:'variedad_combo_nombre',index:'variedad_combo_nombre', width:130, sorttype:"string"/*, formatter: gridOferta_VariedadComboNombreFormatter*/},
			{name:'color_ventas_combo_nombre',index:'color_ventas_combo_nombre', width:70, sorttype:"string"},
			{name:'grado_combo_id',index:'grado_combo_id', width:40, align:"center", sorttype:"string"},
			{name:'factor_combo',index:'factor_combo', width:50, sorttype:"number", align:"center", },
			{name:'btn_editar_cliente',index:'', width:30, align:"center", formatter:GridOferta_FormatterEdit,
			   cellattr: function () { return ' title=" Modificar"'; }
			},					
		],
		rowNum:999999,
		pager: '#pager_oferta',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		gridview:false,	
		multiselect: true,
		jsonReader: {
			repeatitems : false,
		},
//		loadComplete: grid_setAutoHeight,
		loadComplete: function (data) {
			autoHeight_JqGrid_Refresh("grid_oferta");
		},
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});	
	
	jQuery("#grid_oferta").jqGrid('navGrid','#pager_oferta',{edit:false,add:false,del:false});	
	
	
	
	function gridOferta_VariedadComboNombreFormatter(cellvalue, options, rowObject)
	{
		if (rowObject.tallos_x_bunch==25)
		{
			new_format_value = rowObject.variedad_combo_nombre;
		}else{
			new_format_value = rowObject.variedad_combo_nombre + ' <em><b style="color:orange; font-style: italic;">('+rowObject.tallos_x_bunch_combo+')</b></em>';
		}//end if
		return new_format_value;
	}//end function gridOferta_VariedadNombreFormatter
	
});
	


function GridOferta_FormatterEdit(cellvalue, options, rowObject){
	var grupo_precio_cab_id = rowObject.grupo_precio_cab_id;
	var producto_id 		= rowObject.producto_id;
	var variedad_id 		= rowObject.variedad_id;
	var grado_id 			= rowObject.grado_id;
	var producto_combo_id	= rowObject.producto_combo_id;
	var variedad_combo_id	= rowObject.variedad_combo_id;
	var grado_combo_id		= rowObject.grado_combo_id;
	var factor_combo		= rowObject.factor_combo;	
	//new_format_value = '<a href="javascript:void(0)" onclick="consultar_listado(\''+marcacion_sec+'\')"><img src="<?php echo($this->basePath()); ?>/images/edit.png" border="0" /></a> ';
	new_format_value = '<a href="javascript:void(0)" onclick="Oferta_Consultar(\''+grupo_precio_cab_id+'\',\''+producto_id+'\',\''+variedad_id+'\',\''+grado_id+'\',\''+producto_combo_id+'\',\''+variedad_combo_id+'\',\''+grado_combo_id+'\',\''+factor_combo+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange"></i></a>'; 
	return new_format_value
}//end function ListadoCliente_FormatterEdit
	
	

function GrupoPrecio_Grabar(producto_id, grupo_precio_cab_id, variedad_id, grado_id, tipo_precio, precio)
{
	var data = 	{
					grupo_precio_cab_id:		grupo_precio_cab_id,
					producto_id:				producto_id,
					variedad_id:				variedad_id,
					grado_id:					grado_id,
					precio:						precio,
					tipo_precio:			    tipo_precio
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/grupoprecio/grabar',
						'control_process':true,
						'show_cargando':false,
						'async':true,
						'finish':function(response){
							if (response.validacion_code == 'OK')
							{
								//hace algo
							}else{
								message_error('ERROR', response);
							}//end if									
						}							
					 }
	response = ajax_call(parameters, data);		
	return false;			

}//end function GrupoPrecio_Grabar


function GrupoPrecio_ConsultarInfoPrecioGrupoCab(grupo_precio_cab_id)
{
	var data = 	{
					grupo_precio_cab_id:		grupo_precio_cab_id,
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/grupoprecio/consultarcabecera',
						'control_process':true,
						'show_cargando':false,
						'async':true,
						'finish':function(response){
							if (response.respuesta_code == 'OK')
							{
								$("#frm_precio_grupo #inventario_id").val(response.row.inventario_id);
								$("#frm_precio_grupo #calidad_id").val(response.row.calidad_id);
								$("#frm_precio_grupo #info_grupo_precio_cab").html('');								
/*								$("#frm_precio_grupo #info_grupo_precio_cab").html(response.row.inventario_id+' - '+response.row.calidad_nombre+' - '+response.row.calidad_clasifica_fox);*/
							}else{
								message_error('ERROR', response);
							}//end if									
						}							
					 }
	response = ajax_call(parameters, data);		
	return false;				
}//end function GrupoPrecio_ConsultarInfoPrecioGrupoCab




	function GrupoPrecio_Nuevo()
	{
		var data = 	{
					
				inventario_opciones:	'&lt;SELECCIONE&gt;',
				calidad_opciones:		'&lt;SELECCIONE&gt;'
				
					}		
		
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/nuevodata',
							'control_process':true,
							'show_cargando':true,
							
							'finish':function(response){	
								$("#frm_precio_grupo_mantenimiento #accion").val("I");
								$("#dialog_precio_grupo_mantenimiento_titulo").html("NUEVO REGISTRO");
								$("#frm_precio_grupo_mantenimiento #id").val('');
								$("#frm_precio_grupo_mantenimiento #nombre").val('');
								$("#frm_precio_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
								$("#frm_precio_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
								
								$('#dialog_precio_grupo_mantenimiento').modal('show');								
							 }							
				           }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function nuevo


	function GrupoPrecio_GrabarMantenimiento()
	{
		if (!ValidateControls('frm_precio_grupo_mantenimiento')) 
		{
			return false;
		}//end if
				
		var accion  		= $("#frm_precio_grupo_mantenimiento #accion").val();
		var id  			= $("#frm_precio_grupo_mantenimiento #id").val();
		var nombre  		= $("#frm_precio_grupo_mantenimiento #nombre").val();
		var inventario_id 	= $("#frm_precio_grupo_mantenimiento #inventario_id").val();
		var calidad_id  	= $("#frm_precio_grupo_mantenimiento #calidad_id").val();

		var data = 	{
						accion:			accion,
						id:				id,
						nombre:			nombre,
						inventario_id:	inventario_id,
						calidad_id:		calidad_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/grabardata',
							'control_process':true,
							'show_cargando':false,
							'async':true, 
							'finish':function(response){
									if ($("#frm_precio_grupo_mantenimiento #accion").val()=='I'){
										//dispoGrupo_init();
									}//end if
									PrecioGrupo_ComboGrupoRefresh();
									PrecioMostrarRegistro(response);									
									$("#frm_precio_grupo #grupo_precio_cab_id").html(response.grupo_precio_opciones);
									$('#dialog_precio_grupo_mantenimiento').modal('hide')
									cargador_visibility('hide');
									swal({  title: "Informacion grabada con exito!!",   
										//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
										//html:true,
										type: "success",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "OK",
										cancelButtonText: "",
										closeOnConfirm: false,
										closeOnCancel: false,
									});
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;			
	}//end function GrupoPrecio_Grabar



	function PrecioMostrarRegistro(response)
	{
		var row = response.row;
		
		if (row==null) return false;
		
		$("#dialog_precio_grupo_mantenimiento_titulo").html(row.nombre);
		$("#dialog_precio_grupo_mantenimiento #accion").val("M");
		$("#dialog_precio_grupo_mantenimiento #id").val(row.id);
		$("#dialog_precio_grupo_mantenimiento #nombre").val(row.nombre);
		$("#dialog_precio_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
		$("#dialog_precio_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
	}//end function PrecioMostrarRegistro
	
	
	function PrecioGrupo_Consultar(id)
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{grupo_precio_cab_id:id}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/consultarregistrodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
								PrecioMostrarRegistro(response);
									cargador_visibility('hide');

									$('#dialog_precio_grupo_mantenimiento').modal('show');
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function PrecioGrupo_Consultar


	function PrecioGrupo_ComboGrupoRefresh()
	{
		$("#frm_grupo_usuario #info_grupo_precio_cab").html('');
		
		var data = 	{
						texto_primer_elemento:	'&lt;SELECCIONE&gt;',
						grupo_precio_cab_id:	$("#frm_precio_grupo #grupo_precio_cab_id").val(),
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/getcombo',
							'show_cargando':false,
							'async':true,
							'finish':function(response){	
								//grupodispo_listar();
								$("#frm_grupo_usuario #grupo_precio_cab_id").html(response.opciones);
								$("#frm_precio_grupo #grupo_precio_cab_id").html(response.opciones);
								
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;			
	}//end function dispoGrupo_ComboGrupoRefresh
	
	
	
	function PrecioGrupo_CargarOferta(rowid, value, iRow, iCol)
	{
		var col_producto_id 	= jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "producto_id");		
		var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "variedad_id");
		var col_variedad_nombre = jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "variedad");	
		var grado_id			= jqgrid_get_columnNameByIndex($("#grid_precio_grupo"), iCol);
		var producto_id			= $("#grid_precio_grupo").jqGrid('getCell',rowid, col_producto_id);				
		var variedad_id			= $("#grid_precio_grupo").jqGrid('getCell',rowid, col_variedad_id);		
		var variedad_nombre		= $("#grid_precio_grupo").jqGrid('getCell',rowid, col_variedad_nombre);

		//Muestra la Etiqueta de acuerdo a lo seleccionado
		$("#frm_precio_grupo #lbl_titulo").html(variedad_nombre + ' - '+grado_id)
		
		//Establece los valores seleccionados de la GRILLA para realizar la carga del GRID DE OFERTAS
		$("#frm_precio_grupo #producto_seleccionado_id").val(producto_id);
		$("#frm_precio_grupo #variedad_seleccionada_id").val(variedad_id);
		$("#frm_precio_grupo #variedad_seleccionada_nombre").val(variedad_nombre);
		$("#frm_precio_grupo #grado_seleccionado_id").val(grado_id);

		$('#grid_oferta').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function PrecioGrupo_CargarOferta
	



	function Oferta_Nuevo()
	{
		if ($('#frm_precio_grupo #grupo_precio_cab_id').val() == "")
		{
			alert('Debe de seleccionar un GRUPO PRECIO');
			$('#frm_precio_grupo #grupo_precio_cab_id').focus();
			return false;
		}//end if
		
		if ($('#frm_precio_grupo #variedad_seleccionada_id').val() == "")
		{
			alert('Debe de seleccionar una Variedad que tenga Precio de Oferta');
			return false;
		}//end if

		if ($('#frm_precio_grupo #grado_seleccionado_id').val() == "")
		{
			alert('Debe de seleccionar un Grado que tenga Precio de Oferta');
			return false;
		}//end if
		
		Oferta_GetCombos($("#frm_precio_grupo #grupo_precio_cab_id").val());		
		
		$('#frm_oferta #accion').val('I');
		$('#frm_oferta #variedad_combo_id').attr('disabled',false);	
		$('#frm_oferta #grado_combo_id').attr('disabled',false);
		$('#frm_oferta #factor_combo').val('');
		
		var etiqueta = 'OFERTA '+$('#frm_precio_grupo #variedad_seleccionada_nombre').val()+ ' - GRADO '+$('#frm_precio_grupo #grado_seleccionado_id').val();
		$('#frm_oferta #dialog_oferta_titulo').html(etiqueta);
		
		$('#dialog_oferta').modal('show')
		
	}//end Oferta_Nuevo




	function Oferta_Grabar()
	{
		if (!ValidateControls('frm_oferta')) {
			return false;
		}//end if

		var data = 	{	accion: 			$("#frm_oferta #accion").val(),
						grupo_precio_cab_id:$("#frm_precio_grupo #grupo_precio_cab_id").val(),
						producto_id:		$("#frm_precio_grupo #producto_seleccionado_id").val(),
						variedad_id:		$("#frm_precio_grupo #variedad_seleccionada_id").val(),
						grado_id:			$("#frm_precio_grupo #grado_seleccionado_id").val(),
					 	producto_combo_id: 	$("#frm_oferta #producto_combo_id").val(),
					 	variedad_combo_id: 	$("#frm_oferta #variedad_combo_id").val(),						
					 	grado_combo_id: 	$("#frm_oferta #grado_combo_id").val(),
					 	factor_combo: 		$("#frm_oferta #factor_combo").val(),
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/grabarofertadata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										//Se oculta el modal
										$('#dialog_oferta').modal('hide')
										//Se recarga la grilla de promocion
										$('#grid_oferta').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
										
										
										cargador_visibility('hide');
										swal({  title: "Informacion grabada con exito!!",   
											//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
											//html:true,
											type: "success",
											showCancelButton: false,
											confirmButtonColor: "#DD6B55",
											confirmButtonText: "OK",
											cancelButtonText: "",
											closeOnConfirm: false,
											closeOnCancel: false,
											/*timer: 2000*/
										});
									}else{
										swal({title: response.respuesta_mensaje,   
											//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
											//html:true,
											type: "error",
											showCancelButton: false,
											confirmButtonColor: "#DD6B55",
											confirmButtonText: "OK",
											cancelButtonText: "",
											closeOnConfirm: false,
											closeOnCancel: false,
											/*timer: 2000*/
										});
									
									}									
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function Oferta_Grabar
	
	
	function Oferta_Consultar(grupo_precio_cab_id, producto_id, variedad_id, grado_id, producto_combo_id, variedad_combo_id, grado_combo_id, factor_combo)
	{
		Oferta_GetCombos(grupo_precio_cab_id);		
		
		$('#frm_oferta #accion').val('M');
		var etiqueta = 'OFERTA '+$('#frm_precio_grupo #variedad_seleccionada_nombre').val()+ ' - GRADO '+$('#frm_precio_grupo #grado_seleccionado_id').val();
		$('#frm_oferta #dialog_oferta_titulo').html(etiqueta);

		$('#frm_oferta #producto_combo_id').val(producto_combo_id);
		$('#frm_oferta #variedad_combo_id').val(variedad_combo_id);
		$('#frm_oferta #grado_combo_id').val(grado_combo_id);
		$('#frm_oferta #factor_combo').val(factor_combo);	

		$('#frm_oferta #producto_combo_id').attr('disabled',true);			
		$('#frm_oferta #variedad_combo_id').attr('disabled',true);	
		$('#frm_oferta #grado_combo_id').attr('disabled',true);

		$('#dialog_oferta').modal('show')
		
		return false;				
	}//end function Oferta_Consultar
	
	
	
	function Oferta_Eliminar()
	{
		var r = confirm("Esta seguro de eliminar?");
		if (r == false) 
		{
			return false;
		}//end if
				
		var col_grupo_precio_cab_id 	= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "grupo_precio_cab_id");
		var col_producto_id				= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "producto_id");		
		var col_variedad_id 			= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "variedad_id");
		var col_grado_id			 	= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "grado_id");
		var col_producto_combo_id		= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "producto_combo_id");		
		var col_variedad_combo_id 		= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "variedad_combo_id");
		var col_grado_combo_id			= jqgrid_get_columnIndexByName($("#frm_precio_grupo #grid_oferta"), "grado_combo_id");
								
		var grid = $("#frm_precio_grupo #grid_oferta");
		var rowKey = grid.getGridParam("selrow");
		
		if (!rowKey)
		{
			alert("SELECCIONE UN REGISTRO PARA ELIMINAR");
			return false;
		}
		
       	var selectedIDs = grid.getGridParam("selarrrow");
		var usuario_id  = null;
		
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) {
			grupo_precio_cab_id = jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_grupo_precio_cab_id);
			producto_id 		= jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_producto_id);
			variedad_id 		= jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_variedad_id);
			grado_id 			= jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_grado_id);
			producto_combo_id 	= jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_producto_combo_id);			
			variedad_combo_id 	= jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_variedad_combo_id);
			grado_combo_id 		= jQuery("#frm_precio_grupo #grid_oferta").jqGrid('getCell',selectedIDs[i], col_grado_combo_id);
		
			var element					= {};
			element.grupo_precio_cab_id = grupo_precio_cab_id;
			element.producto_id 		= producto_id;
			element.variedad_id 		= variedad_id;
			element.grado_id 			= grado_id;
			element.producto_combo_id	= producto_combo_id;
			element.variedad_combo_id	= variedad_combo_id;
			element.grado_combo_id 		= grado_combo_id;

			arr_data.push(element);
		}//end for
		
		var data = {						
				grid_data: 	arr_data,
			};			
			data = JSON.stringify(data);
		

		var parameters = {	'type': 'post',
				'contentType': 'application/json',
				'url':'../../dispo/grupoprecio/eliminarofertas',
				'show_cargando':true,
				'finish':function(response){
						if (response.respuesta_code=='OK'){
							$('#grid_oferta').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
						}else{
							message_error('ERROR', response);
						}//end if
				}
		};
		
		ajax_call(parameters, data);		
	}//end function Oferta_Eliminar
	
	
	
	function Oferta_GetCombos(grupo_precio_cab_id)
	{
		var data = 	{	
						//grupo_precio_cab_id:$("#frm_precio_grupo #grupo_precio_cab_id").val(),
						grupo_precio_cab_id: grupo_precio_cab_id,
						producto_combo_id:	 'ROS',
						producto_combo_1er_elemento: '&lt;Seleccione&gt;'
					}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupoprecio/getcombosofertas',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									if (response.validacion_code == 'OK')
									{
										$("#frm_oferta #producto_combo_id").html(response.producto_opciones);
										$("#frm_oferta #variedad_combo_id").html(response.variedad_opciones);
										$("#frm_oferta #grado_combo_id").html(response.grado_opciones);
									}else{
										swal({title: response.respuesta_mensaje,   
											//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
											//html:true,
											type: "error",
											showCancelButton: false,
											confirmButtonColor: "#DD6B55",
											confirmButtonText: "OK",
											cancelButtonText: "",
											closeOnConfirm: false,
											closeOnCancel: false,
											/*timer: 2000*/
										});
									
									}									
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;						
	}//end getcombosofertasAction