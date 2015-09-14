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
	mantenimiento_init();
	
	$("#frm_tipo_caja_busqueda #inventario_id, #frm_tipo_caja_busqueda #tipo_caja_id").on('change', function(event){
//		$("#grid_tipo_caja").jqGrid('clearGridData');
		$('#grid_tipo_caja').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});


	$("#frm_tipo_caja_busqueda #btn_consultar").on('click', function(event){ 
		$('#grid_tipo_caja').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	
	$("#frm_tipo_caja_busqueda #btn_actualizacion_masiva").on('click', function(event){ 
		TipoCaja_OpenModalActualizacionMasiva();
		return false;
	});		

	$("#frm_tipo_caja_matenimiento #btn_grabar").on('click', function(event){ 
		TipoCaja_TipoCaja_GrabarMasivo();
		return false;
	});		
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Disponibilidad General---------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_tipo_caja").jqGrid({
		url:'../../dispo/tipocajamatriz/listadodata',
		postData: {
			tipo_caja_id: 	function() {return $("#frm_tipo_caja_busqueda #tipo_caja_id").val();},
			inventario_id: 	function() {return $("#frm_tipo_caja_busqueda #inventario_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Id','Variedad','40','50','60','70','80','90', '100', '110'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string"},
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", editable:true, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
		loadComplete: grid_setAutoHeight,
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
		beforeEditCell : function(rowid, cellname, value, iRow, iCol)
		{
			seliCol_TipoCajaGrid  = iCol;
			seliRow_TipoCajaGrid  = iRow;
			valAnt_TipoCajaGrid   = value;
		},	
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			//Evita se llame la funcion grabar sin que se haya modificado el valor
			var nro_bunches  = number_val(jQuery("#grid_tipo_caja").jqGrid('getCell',rowid, iCol), 2);
			if (number_val(nro_bunches)==number_val(valAnt_TipoCajaGrid))
			{
				return false;
			}//end if

			//Asigna las variables para poderlo pasar en la funcion grabar
			var tipo_caja_id  	= $("#frm_tipo_caja_busqueda #tipo_caja_id").val();						
			var inventario_id  	= $("#frm_tipo_caja_busqueda #inventario_id").val();
			var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja"), "variedad_id");
			var variedad_id			= jQuery("#grid_tipo_caja").jqGrid('getCell',rowid, col_variedad_id);

			var grado_id			= jqgrid_get_columnNameByIndex($("#grid_tipo_caja"), iCol);
			
			TipoCaja_Grabar(tipo_caja_id, inventario_id, variedad_id, grado_id, nro_bunches);
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
	var data = 	{
					opcion: 'mantenimiento',
					inventario_1er_elemento:	'',
					tipo_caja_1er_elemento:		'',
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamatriz/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){		
							$("body #frm_tipo_caja_busqueda #tipo_caja_id").html(response.tipocaja_opciones);
							$("body #frm_tipo_caja_busqueda #inventario_id").html(response.inventario_opciones);
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
}//end function mantenimiento_init


function actualizacion_masiva_init()
{
	var data = 	{
					opcion: 'actualizacion-masiva',
					variedad_1er_elemento:	'&lt;TODAS&gt;',
					grado_1er_elemento:		'&lt;TODOS&gt;',
					inventario_id:			$("#frm_tipo_caja_matenimiento #inventario_id").val(),
					tipo_caja_id:			$("#frm_tipo_caja_matenimiento #tipo_caja_id").val(),
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamatriz/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){		
							$("body #frm_tipo_caja_matenimiento #variedad_id").html(response.variedad_opciones);
							$("body #frm_tipo_caja_matenimiento #grado_id").html(response.grado_opciones);
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
}//end function actualizacion_masiva_init


function TipoCaja_Grabar(tipo_caja_id, inventario_id, variedad_id, grado_id, nro_bunches)
{
	var data = 	{
					tipo_caja_id:		tipo_caja_id,
					inventario_id:		inventario_id,
					variedad_id:		variedad_id,
					grado_id:			grado_id,
					nro_bunches:		nro_bunches
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/tipocajamatriz/grabar',
						'control_process':true,
						'show_cargando':false,
						'async':true,
						'finish':function(response){
							if (response.respuesta_code == 'OK')
							{
								//mostrar_registro(response)
								/*cargador_visibility('hide');
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
								});*/
							}else{
								message_error('ERROR', response);
							}//end if									
						}							
					 }
	response = ajax_call(parameters, data);		
	return false;			
}//end function TipoCaja_Grabar



function TipoCaja_OpenModalActualizacionMasiva()
{
		$("#frm_tipo_caja_matenimiento #tipo_caja_id").val($("#frm_tipo_caja_busqueda #tipo_caja_id").val());
		$("#frm_tipo_caja_matenimiento #inventario_id").val($("#frm_tipo_caja_busqueda #inventario_id").val());

		$("#frm_tipo_caja_matenimiento #tipo_caja_nombre").html($("#frm_tipo_caja_busqueda #tipo_caja_id").val());
		$("#frm_tipo_caja_matenimiento #inventario_nombre").html($("#frm_tipo_caja_busqueda #inventario_id :selected").text());
		
		actualizacion_masiva_init();
		$('#dialog_tipo_caja_actualizacion').modal('show')
}//end function TipoCaja_OpenModalActualizacionMasiva



function TipoCaja_TipoCaja_GrabarMasivo()
{
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
						'url':'../../dispo/tipocajamatriz/actualizarmasivo',
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
}//end function TipoCaja_TipoCaja_GrabarMasivo