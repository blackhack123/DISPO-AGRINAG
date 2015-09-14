/**
 * 
 */
var selRowId_TipoCajaGrid 	= 0;	
var selColName_TipoCajaGrid = 0;	
var seliRow_TipoCajaGrid 	= 0;		
var seliCol_TipoCajaGrid 	= 0;	
var valAnt_TipoCajaGrid		= null;



$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
/*	mantenimiento_init();
	
	$("#frm_tipo_caja #inventario_id, #frm_tipo_caja #tipo_caja_id").on('change', function(event){
		$('#grid_tipo_caja').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});
*/

	$("#frm_tipo_caja #btn_consultar").on('click', function(event){ 
		$('#grid_tipo_caja').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
/*	
	$("#frm_tipo_caja #btn_actualizacion_masiva").on('click', function(event){ 
		TipoCaja_OpenModalActualizacionMasiva();
		return false;
	});		

	$("#frm_tipo_caja_matenimiento #btn_grabar").on('click', function(event){ 
		TipoCaja_TipoCaja_GrabarMasivo();
		return false;
	});		
*/	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID ----------------------------------*/
	/*---------------------------------------------------------------*/
	var cbo_tipo_caja = $.ajax({
								url: '../../dispo/tipocaja/getComboDataGrid',
								async: false, 
								success: function(data, result) {
									if (!result) message_error('ERROR', 'Error al cargar combo de Nivel');
								}
							}).responseText;

	var cbo_inventario = $.ajax({
								url: '../../dispo/inventario/getComboDataGrid',
								async: false, 
								success: function(data, result) {
									if (!result) message_error('ERROR', 'Error al cargar combo de Nivel');
								}
							}).responseText;
			
	jQuery("#grid_tipo_caja").jqGrid({
		url:'../../dispo/tipocajamarcacion/listadodata',
		postData: {
			tipo_caja_id: 	function() {return $("#frm_tipo_caja #tipo_caja_id").val();},
			inventario_id: 	function() {return $("#frm_tipo_caja #inventario_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Id','cliente_id','marcacion_sec','variedad_id','Cliente','Marcacion','Tipo Caja','Inventario','Variedad','Grado','Bunches'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'accion',index:'accion', width:60, align:"center", hidden: false},
			{name:'id',index:'id', width:50, align:"center", sorttype:"int"},
			{name:'cliente_id',index:'cliente_id', width:50, align:"center", sorttype:"string"},
			{name:'marcacion_sec',index:'marcacion_sec', width:50, align:"center", sorttype:"int"},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'cliente_nombre',index:'cliente_nombre', width:150, align:"center", sorttype:"string", editable:true, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && e.shiftKey)   // tab
																{
																	if ((seliRow_TipoCajaGrid - 1) <= 0) return false;
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " - 1, " + jqgrid_get_columnIndexByName($("#grid_tipo_caja"), "110") + ", true);", 10);														
																}																
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " + 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " - 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}													
															}
														} 
													]													
									 },
			},	
			{name:'marcacion_nombre',index:'marcacion_nombre', width:150, align:"center", sorttype:"string", 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " + 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " - 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }
			},	
			{name:'tipo_caja_id',index:'tipo_caja_id', width:100, align:"center", sorttype:"string", editable:true, edittype:"select", formatter:'select', editoptions:{value: cbo_tipo_caja}},	
			{name:'inventario_id',index:'inventario_id', width:70, align:"center", sorttype:"string", editable:true, edittype:"select", formatter:'select', editoptions:{value: cbo_inventario}},	
			{name:'variedad_nombre',index:'variedad_nombre', width:150, align:"center", sorttype:"string", editable:true, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " + 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " - 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }
			},	
			{name:'grado_id',index:'grado_id', width:50, align:"center", sorttype:"string", editable:true,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " + 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " - 1, " + seliCol_TipoCajaGrid + ", true);", 10);
																}													
															}
														} 
													]																				
									 }
			},	
			{name:'unds_bunch',index:'unds_bunch', width:70, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
											{ 
												type: 'keydown', 
												fn: function(e) { 
													var key = e.charCode || e.keyCode;
													if(key == 9 && !e.shiftKey)   // tab
													{
														setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " + 1, " + jqgrid_get_columnIndexByName($("#grid_tipo_caja"), "40") + ", true);", 10);														
													}
													if ((key == 13)||(key == 40))//enter, abajo
													{
														setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " + 1, " + seliCol_TipoCajaGrid + ", true);", 10);
													}
													else if ((key == 38))//arriba
													{
														setTimeout("jQuery('#grid_tipo_caja').editCell(" + seliRow_TipoCajaGrid + " - 1, " + seliCol_TipoCajaGrid + ", true);", 10);													
													}//end if													
												}
											}
										]																		
									 }					
			}
		],
		rowNum:999999,
		pager: '#pager_tipo_caja',
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
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
		loadComplete: function()
		{
			
		},		
		beforeEditCell : function(rowid, cellname, value, iRow, iCol)
		{
			seliCol_TipoCajaGrid  = iCol;
			seliRow_TipoCajaGrid  = iRow;
			valAnt_TipoCajaGrid   = value;
		},	
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			var col_accion 		 		= jqgrid_get_columnIndexByName($("#grid_tipo_caja"), "accion");
			var col_cliente_nombre	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja"), "cliente_nombre");
			var col_variedad_nombre	 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja"), "variedad_nombre");
		
			switch(iCol)
			{
				case col_cliente_nombre:					
					var cliente_nombre = jQuery("#grid_tipo_caja").jqGrid('getCell',rowid, col_cliente_nombre);
					/*var empresa_id =  $("#empresa_id_53").val();*/
					var params = { 'title':'BUSCADOR DE CLIENTES',
								   'grid_url': 				'../cliente/listadodialogdata',
								   'term':				    cliente_nombre,
								   'grid_source_id': 		'grid_tipo_caja',
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

					break;

			}//end switch
		
			var accion = jQuery("#grid_tipo_caja").jqGrid('getCell',rowid, col_accion);
			if (accion!='I')
			{
				$("#grid_tipo_caja").jqGrid('setCell', rowid, col_accion, 'M');
			}//end if				
		},
	});
	//$("#grid_tipo_caja").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	jQuery("#grid_tipo_caja").jqGrid('navGrid','#pager_tipo_caja',{edit:false,add:false,del:false});
		
		
	function gridTipoCaja_GradosFormatter(cellvalue, options, rowObject){
		cellvalue = number_val(cellvalue, 0);		
		var color = "Black";
		if (cellvalue==0)
		{
			color = "LightGray";
		}
		cellvalue = $.number( cellvalue, 0, '.',','); 		
		new_format_value = '<span style="color:'+color+'">'+cellvalue+ '</a>';
		return new_format_value;
	}
		
	function gridTipoCaja_GradosUnFormatter(cellvalue, options, cell){
		return number_val($('span', cell).html());
	}
		

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
});



function mantenimiento_init()
{
/*	var data = 	{
					opcion: 'mantenimiento',
					inventario_1er_elemento:	'',
					tipo_caja_1er_elemento:		'',
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamarcacion/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){		
							$("body #frm_tipo_caja #tipo_caja_id").html(response.tipocaja_opciones);
							$("body #frm_tipo_caja #inventario_id").html(response.inventario_opciones);
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
*/
}//end function mantenimiento_init


function actualizacion_masiva_init()
{
/*	var data = 	{
					opcion: 'actualizacion-masiva',
					variedad_1er_elemento:	'&lt;TODAS&gt;',
					grado_1er_elemento:		'&lt;TODOS&gt;',
					inventario_id:			$("#frm_tipo_caja_matenimiento #inventario_id").val(),
					tipo_caja_id:			$("#frm_tipo_caja_matenimiento #tipo_caja_id").val(),
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamarcacion/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){		
							$("body #frm_tipo_caja_matenimiento #variedad_id").html(response.variedad_opciones);
							$("body #frm_tipo_caja_matenimiento #grado_id").html(response.grado_opciones);
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
*/
}//end function actualizacion_masiva_init


function TipoCaja_Grabar(tipo_caja_id, inventario_id, variedad_id, grado_id, nro_bunches)
{
/*	var data = 	{
					tipo_caja_id:		tipo_caja_id,
					inventario_id:		inventario_id,
					variedad_id:		variedad_id,
					grado_id:			grado_id,
					nro_bunches:		nro_bunches
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamarcacion/grabar',
						'control_process':true,
						'show_cargando':false,
						'async':true,
						'finish':function(response){
							if (response.respuesta_code == 'OK')
							{
								//mostrar_registro(response)								
							}else{
								message_error('ERROR', response);
							}//end if									
						}							
					 }
	response = ajax_call(parameters, data);		
	return false;			
*/
}//end function TipoCaja_Grabar



function TipoCaja_OpenModalActualizacionMasiva()
{
/*		$("#frm_tipo_caja_matenimiento #tipo_caja_id").val($("#frm_tipo_caja #tipo_caja_id").val());
		$("#frm_tipo_caja_matenimiento #inventario_id").val($("#frm_tipo_caja #inventario_id").val());

		$("#frm_tipo_caja_matenimiento #tipo_caja_nombre").html($("#frm_tipo_caja #tipo_caja_id").val());
		$("#frm_tipo_caja_matenimiento #inventario_nombre").html($("#frm_tipo_caja #inventario_id :selected").text());
		
		actualizacion_masiva_init();
		$('#dialog_tipo_caja_actualizacion').modal('show')
*/		
}//end function TipoCaja_OpenModalActualizacionMasiva



function TipoCaja_TipoCaja_GrabarMasivo()
{
/*
	if (!ValidateControls('frm_tipo_caja_matenimiento')) 
	{
		return false;
	}
	
	
	var tipo_caja_id 	= $("#frm_tipo_caja_matenimiento #tipo_caja_id").val();
	var inventario_id 	= $("#frm_tipo_caja_matenimiento #inventario_id").val();
	var variedad_id 	= $("#frm_tipo_caja_matenimiento #variedad_id").val();
	var grado_id 		= $("#frm_tipo_caja_matenimiento #grado_id").val();
	var unds_bunch 	= $("#frm_tipo_caja_matenimiento #unds_bunch").val();
	
	var data = 	{
					tipo_caja_id:		tipo_caja_id,
					inventario_id:		inventario_id,
					variedad_id:		variedad_id,
					grado_id:			grado_id,
					unds_bunch:			unds_bunch
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamarcacion/actualizarmasivo',
						'control_process':true,
						'show_cargando':true,
						'async':true,
						'finish':function(response){
							if (response.respuesta_code == 'OK')
							{
								//mostrar_registro(response)
								cargador_visibility('hide');
								swal({  title: "Informacion procesada con exito!!",   
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
							}else{
								message_error('ERROR', response);
							}//end if									
						}							
					 }
	response = ajax_call(parameters, data);		
	return false;
*/			
}//end function TipoCaja_TipoCaja_GrabarMasivo