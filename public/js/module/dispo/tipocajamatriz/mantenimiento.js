/**
 * 
 */
var selRowId_TipoCajaMatrizGrid 	= 0;	
var selColName_TipoCajaMatrizGrid 	= 0;	
var seliRow_TipoCajaMatrizGrid 		= 0;		
var seliCol_TipoCajaMatrizGrid 		= 0;	
var valAnt_TipoCajaMatrizGrid		= null;



$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	TipoCajaMatriz_Init();
	
	$("#frm_tipo_caja_busqueda #inventario_id, #frm_tipo_caja_busqueda #tipo_caja_id").on('change', function(event){
//		$("#grid_tipo_caja_matriz").jqGrid('clearGridData');
		$('#grid_tipo_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});


	$("#frm_tipo_caja_busqueda #btn_consultar").on('click', function(event){ 
		$('#grid_tipo_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
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
	
	$("#frm_tipo_caja_busqueda #btn_nueva_variedad").on('click', function(event){ 
		NuevaCajaMatriz();
		return false;
	});

	$("#form_nueva_caja_matriz #btn_grabar_caja_matriz").on('click', function(event){ 
		GrabarCajaMatriz();
		return false;
	});
	
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Disponibilidad General---------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_tipo_caja_matriz").jqGrid({
		url:'../../dispo/tipocajamatriz/listadodata',
		postData: {
			tipo_caja_id: 	function() {return $("#frm_tipo_caja_busqueda #tipo_caja_id").val();},
			inventario_id: 	function() {return $("#frm_tipo_caja_busqueda #inventario_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Id','Variedad','Tallos X Bunch','40','50','60','70','80','90', '100', '110'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string"},
			{name:'tallos_x_bunch',index:'tallos_x_bunch', width:110, sorttype:"int", align:"center"},
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
																	if ((seliRow_TipoCajaMatrizGrid - 1) <= 0) return false;
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + jqgrid_get_columnIndexByName($("#grid_tipo_caja_matriz"), "110") + ", true);", 10);														
																}																
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
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
														setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + jqgrid_get_columnIndexByName($("#grid_tipo_caja_matriz"), "40") + ", true);", 10);														
													}
													if ((key == 13)||(key == 40))//enter, abajo
													{
														setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " + 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);
													}
													else if ((key == 38))//arriba
													{
														setTimeout("jQuery('#grid_tipo_caja_matriz').editCell(" + seliRow_TipoCajaMatrizGrid + " - 1, " + seliCol_TipoCajaMatrizGrid + ", true);", 10);													
													}//end if													
												}
											}
										]																		
									 }					
			}
		],
		rowNum:999999,
		pager: '#pager_tipo_caja_matriz',
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
			seliCol_TipoCajaMatrizGrid  = iCol;
			seliRow_TipoCajaMatrizGrid  = iRow;
			valAnt_TipoCajaMatrizGrid   = value;
		},	
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			//Evita se llame la funcion grabar sin que se haya modificado el valor
			var nro_bunches  = number_val(jQuery("#grid_tipo_caja_matriz").jqGrid('getCell',rowid, iCol), 2);
			if (number_val(nro_bunches)==number_val(valAnt_TipoCajaMatrizGrid))
			{
				return false;
			}//end if

			//Asigna las variables para poderlo pasar en la funcion grabar
			var tipo_caja_id  	= $("#frm_tipo_caja_busqueda #tipo_caja_id").val();						
			var inventario_id  	= $("#frm_tipo_caja_busqueda #inventario_id").val();
			var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_tipo_caja_matriz"), "variedad_id");
			var variedad_id			= jQuery("#grid_tipo_caja_matriz").jqGrid('getCell',rowid, col_variedad_id);

			var grado_id			= jqgrid_get_columnNameByIndex($("#grid_tipo_caja_matriz"), iCol);
			
			TipoCaja_Grabar(tipo_caja_id, inventario_id, variedad_id, grado_id, nro_bunches);
		},
	});
	//$("#grid_tipo_caja_matriz").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	jQuery("#grid_tipo_caja_matriz").jqGrid('navGrid','#pager_tipo_caja_matriz',{edit:false,add:false,del:false});
		
		
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
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de NUEVA CAJA MATRIZ--------------*/
	/*---------------------------------------------------------------*/	
			
			var dataGridNuevaCajaMatriz = [{
				'40': 0,
				'50': 0,
				'60': 0,
				'70': 0,
				'80': 0,
				'90': 0,
				'100': 0,
				'110': 0
			}];
			
			jQuery("#grid_nueva_caja_matriz").jqGrid({
				data: dataGridNuevaCajaMatriz,       
				datatype: "local",
				//datatype: "json",
				loadonce: true,			
				/*height:'400',*/
				colNames:['40','50','60','70','80','90', '100', '110'],
				colModel:[
					//{name:'seleccion',index:'', width:50, align: 'center',editable: false, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
					{name:'40',index:'40', width:50, sorttype:"int", align:"center", editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }
					},
					{name:'50',index:'50', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }			
					},	
					{name:'60',index:'60', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
						}
					},	
					{name:'70',index:'70', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }
					},	
					{name:'80',index:'80', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }
					},	
					{name:'90',index:'90', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }			
					},	
					{name:'100',index:'100', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }			
					},
					{name:'110',index:'110', width:50, sorttype:"int", align:"center",editable:true,
						editoptions: {
												dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
									 }
					},
				],
				rowNum:10,
				pager: '#pager_nueva_caja_matriz',
				toppager:false,
				pgbuttons:false,
				pginput:false,
				rowList:false,
				gridview:false,	
				shrinkToFit: false,
				jsonReader: {
					repeatitems : false,
				},	
				cellEdit: true,
				cellsubmit: 'clientArray',
				editurl: 'clientArray',					
				loadError: function (jqXHR, textStatus, errorThrown) {
					message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
				},
				//ondblClickRow
	/*			
				onSelectRow: function(){
					var row_id = $("#grid_nueva_caja_matriz").getGridParam('selrow');
					jQuery('#grid_nueva_caja_matriz').editRow(row_id, true);
					startEdit();
				}
*/			
			});
/*			
			function startEdit() {
	            var grid = $("#grid_nueva_caja_matriz");
	            var ids = grid.jqGrid('getDataIDs');

	            for (var i = 0; i < ids.length; i++) {
	                grid.jqGrid('editRow',ids[i]);
	            }
	        };
*/        
	/*        function saveRows() {
	            var grid = $("#grid_nueva_caja_matriz");
	            var ids = grid.jqGrid('getDataIDs');

	            for (var i = 0; i < ids.length; i++) {
	                grid.jqGrid('saveRow', ids[i]);
	            }
	        }
	*/
			jQuery("#form_nueva_caja_matriz #grid_nueva_caja_matriz").jqGrid('navGrid','#pager_nueva_caja_matriz',{edit:false,add:false,del:false});
			
			
			
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
});



function TipoCajaMatriz_Init()
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
}//end function TipoCajaMatriz_Init


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
	var unds_bunch 		= $("#frm_tipo_caja_matenimiento #unds_bunch").val();
	
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
								$('#dialog_tipo_caja_actualizacion').modal('hide')
								$('#grid_tipo_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
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


/****************************************************/
/**********CREACION DE NUEVA CAJA MATRIZ*************/
/****************************************************/

	function nueva_caja_matriz_init()
	{
		var data = 	{
						opcion: 'caja-matriz',
						tipo_caja_id:		$("#frm_tipo_caja_busqueda #tipo_caja_id").val(),
						inventario_id:		$("#frm_tipo_caja_busqueda #inventario_id").val(),
						variedad_id:		'&lt;TODAS&gt;',
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/tipocajamatriz/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response){		
								$("#form_nueva_caja_matriz #tipo_caja_id").html(response.tipocaja_opciones);
								$("#form_nueva_caja_matriz #inventario_id").html(response.inventario_opciones);
								$("#form_nueva_caja_matriz #variedad_id").html(response.variedad_opciones);
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function nueva_caja_matriz_init
	
	
	function NuevaCajaMatriz()
	{
		nueva_caja_matriz_init();
		$('#modal_nueva_variedad').modal('show');
		
	}//end NuevaCajaMatriz
	
	function GrabarCajaMatriz()
	{
		if (!ValidateControls('form_nueva_caja_matriz')) 
		{
			return false;
		}
		
		$('#grid_nueva_caja_matriz').jqGrid('editCell', 1, 0, false);

		//Guarda los datos de la grilla #grid_nueva_caja_matriz
		var grid = $("#grid_nueva_caja_matriz");
		var ids = grid.jqGrid('getDataIDs');
		var arr_data 			= new	Array();
		
		for (var i = 0; i < ids.length; i++) {
			id = ids[i];
			row =  grid.jqGrid('getRowData', id);
			
			var element		= {};
			element['40'] 	= row['40'];
			element['50'] 	= row['50'];
			element['60'] 	= row['60'];
			element['70'] 	= row['70'];
			element['80'] 	= row['80'];
			element['90'] 	= row['90'];
			element['100'] 	= row['100'];
			element['110'] 	= row['110'];
			arr_data.push(element);
		}//end for
		
		var data = 	{
				tipo_caja_id:		$("#form_nueva_caja_matriz #tipo_caja_id").val(),
				inventario_id:		$("#form_nueva_caja_matriz #inventario_id").val(),
				variedad_id:		$("#form_nueva_caja_matriz #variedad_id").val(),
				tallos_x_bunch:		$("#form_nueva_caja_matriz #tallos_x_bunch").val(),
				grid_data: 			arr_data
			}
		//alert("entra al evento data");
		data = JSON.stringify(data);
		console.log(data);
		
	}//end GrabarCajaMatriz

/****************************************************/
/****************************************************/
/****************************************************/