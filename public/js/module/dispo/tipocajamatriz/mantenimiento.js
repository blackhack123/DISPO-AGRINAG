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
	nueva_caja_matriz_init();
	
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
	
	$("#frm_tipo_caja_busqueda #btn_nueva_caja_matriz").on('click', function(event){ 
		NuevaCajaMatriz();
		return false;
	});

	$("#form_mantenimiento_caja_matriz #btn_grabar_caja_matriz").on('click', function(event){ 
		ValidarTamanoBunchs();		//RegistrarCajaMatriz();
		return false;
	});
	
	$("#frm_tipo_caja_busqueda #btn_eliminar_caja_matriz").on('click', function(event){ 
		EliminarajaMatriz();
		return false;
	});
	
	//OnChange Combos 
	
	/*
	$("#form_mantenimiento_caja_matriz #inventario_id").on('change', function(event){ 
		var inventario_id =  $("#form_mantenimiento_caja_matriz #inventario_id").val();
		recargarGrilla(inventario_id);
	});
	*/
	$("#form_mantenimiento_caja_matriz #tipo_caja_id").on('change', function(event){ 
		recargarGrilla();
	});
	
	$("#form_mantenimiento_caja_matriz #tamano_bunch_id").on('change', function(event){ 
		recargarGrilla();
	});
	
	$("#form_mantenimiento_caja_matriz #tallos_x_bunch").on('change', function(event){ 
		recargarGrilla();
	});
	
	
	
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Disponibilidad General---------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_tipo_caja_matriz").jqGrid({
		url:'../../dispo/tipocajamatriz/listadodata',
		postData: {
			//tipo_caja_id: 	function() {return $("#frm_tipo_caja_busqueda #tipo_caja_id").val();},
			inventario_id: 	function() {return $("#frm_tipo_caja_busqueda #inventario_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['ID','ID INVENT','ID T CAJA','ID T BUNCH','Tipo Caja','Tallos X Bunch','Tamaño Bunch','40','50','60','70','80','90', '100', '110', ''],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'id_caja_matriz',index:'id_caja_matriz', width:110, sorttype:"int", align:"center", hidden:true },
			{name:'inventario_id',index:'inventario_id', width:110, sorttype:"string", align:"center", hidden:true},
			{name:'tipo_caja_id',index:'tipo_caja_id', width:110, sorttype:"string", align:"center", hidden:true},
			{name:'tamano_bunch_id',index:'tamano_bunch_id', width:110, sorttype:"string", align:"center", hidden:true},
			{name:'tipo_caja_nombre',index:'tipo_caja_nombre', width:110, sorttype:"string", align:"center"},
			{name:'tallos_x_bunch',index:'tallos_x_bunch', width:110, sorttype:"int", align:"center"},
			{name:'tamano_bunch_nombre',index:'tamano_bunch_nombre', width:110, sorttype:"string", align:"center"},
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", editable:false, formatter: gridTipoCaja_GradosFormatter, unformat:gridTipoCaja_GradosUnFormatter,
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
			},
			{name:'btn_editar_caja_matriz',index:'', width:30, align:"center", formatter:ListadoCajaMatriz_FormatterEdit,
				   cellattr: function () { return ' title=" Modificar"'; }
			},
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
		cellEdit: false,
		cellsubmit: 'clientArray',
		editurl: 'clientArray',	
		multiselect: true,
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
		ondblClickRow: function (rowid,iRow,iCol,e) {
			
			var data = $('#grid_tipo_caja_matriz').getRowData(rowid);
			
			//parametros para consultar
			var inventario_id 		= data.inventario_id;
			var tipo_caja_id 		= data.tipo_caja_id;
			var tamano_bunch_id 	= data.tamano_bunch_id;
			var tallos_x_bunch 		= data.tallos_x_bunch;
			
			consultar_caja_matriz_por_clave_alterna(inventario_id, tipo_caja_id, tamano_bunch_id, tallos_x_bunch )
			
			//console.log('invent:',data.inventario_id, 't_caja:',data.tipo_caja_id,'t_bunch',data.tamano_bunch_id,'t_tallos',data.tallos_x_bunch)
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
		
	function ListadoCajaMatriz_FormatterEdit(cellvalue, options, rowObject){
		
		//console.log('rowObject:',rowObject)
		var inventario_id 		= rowObject.inventario_id;
		var tipo_caja_id 		= rowObject.tipo_caja_id;
		var tamano_bunch_id		= rowObject.tamano_bunch_id;
		var tallos_x_bunch 		= rowObject.tallos_x_bunch;
		new_format_value = '<a href="javascript:void(0)" onclick="consultar_caja_matriz_por_clave_alterna(\''+inventario_id+'\',\''+tipo_caja_id+'\',\''+tamano_bunch_id+'\','+tallos_x_bunch+')"><i class="glyphicon glyphicon-pencil" style="color:orange"></i></a>'; 
		return new_format_value
	
	}//end function ListadoCajaMatriz_FormatterEdit
	
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de NUEVA CAJA MATRIZ--------------*/
	/*---------------------------------------------------------------*/	
		jQuery("#grid_mantenimiento_caja_matriz").jqGrid({
			url:'../../dispo/tipocajamatriz/consultarPorClaveAlternaListado',
			postData: {
				tipo_caja_id: 	function() {return $("#form_mantenimiento_caja_matriz #tipo_caja_id").val();},
				inventario_id: 	function() {return $("#form_mantenimiento_caja_matriz #inventario_id").val();},
				tallos_x_bunch: function() {return $("#form_mantenimiento_caja_matriz #tallos_x_bunch").val();},
				tamano_bunch_id: function() {return $("#form_mantenimiento_caja_matriz #tamano_bunch_id").val();},
			},
			colNames:['40','50','60','70','80','90', '100', '110'],
			colModel:[
				//{name:'seleccion',index:'', width:50, align: 'center',editable: false, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
				{name:'40',index:'40', width:50, sorttype:"int", align:"center", editable:true,
					editoptions: {
											dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
								 },
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
			datatype: "json",
			loadonce: true,
			rowNum:999999999,
			pager: '#pager_mantenimiento_caja_matriz',
			toppager:false,
			pgbuttons:false,
			pginput:false,
			rowList:false,
			gridview:false,	
			shrinkToFit: false,
			jsonReader: {
				repeatitems : false,
				userdata: "userData",
			},	
			cellEdit: true,
			cellsubmit: 'clientArray',
			editurl: 'clientArray',				
			loadComplete: function(data)
			{
				userdata = $("#grid_mantenimiento_caja_matriz").jqGrid('getGridParam','userData');
				
				if (userdata.respuesta == 'NO-DATA')
				{
				    var newData = [{"40": "0", "50": "0", "60": "0", "70": "0", "80": "0", "90": "0", "100": "0", "110": "0"}];
			        $("#grid_mantenimiento_caja_matriz").addRowData('',newData);
				}//end if
			},
			loadBeforeSend: function (xhr, settings) {
				this.p.loadBeforeSend = null; //remove event handler
				return false; // dont send load data request
			},	
			loadError: function (jqXHR, textStatus, errorThrown) {
				message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
			//	console.log('ERROR:',jqXHR.responseText);
			},
		
					
		});

		jQuery("#grid_mantenimiento_caja_matriz").jqGrid('navGrid','#pager_mantenimiento_caja_matriz',{edit:false,add:false,del:false});
			
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
							//$("body #frm_tipo_caja_busqueda #tipo_caja_id").html(response.tipocaja_opciones);
							$("body #frm_tipo_caja_busqueda #inventario_id").html(response.inventario_opciones);
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
}//end function TipoCajaMatriz_Init


/*
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
*/

/*
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
							//}else{
							//	message_error('ERROR', response);
							//}//end if									
						//}							
					// }
	//response = ajax_call(parameters, data);		
	//return false;	
	
//}//end function TipoCaja_Grabar

/*

function TipoCaja_OpenModalActualizacionMasiva()
{
		$("#frm_tipo_caja_matenimiento #tipo_caja_id").val($("#frm_tipo_caja_busqueda #tipo_caja_id").val());
		$("#frm_tipo_caja_matenimiento #inventario_id").val($("#frm_tipo_caja_busqueda #inventario_id").val());

		$("#frm_tipo_caja_matenimiento #tipo_caja_nombre").html($("#frm_tipo_caja_busqueda #tipo_caja_id").val());
		$("#frm_tipo_caja_matenimiento #inventario_nombre").html($("#frm_tipo_caja_busqueda #inventario_id :selected").text());
		
		actualizacion_masiva_init();
		$('#dialog_tipo_caja_actualizacion').modal('show')
}//end function TipoCaja_OpenModalActualizacionMasiva

*/
/*
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
*/



/****************************************************/
/**********CREACION DE NUEVA CAJA MATRIZ*************/
/****************************************************/

	function nueva_caja_matriz_init()
	{
		
		var data = 	{
						opcion: 'caja-matriz',
						tipo_caja_id:		null,
						tamano_bunch_id:	null,
						inventario_id:		null
							
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/tipocajamatriz/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response)
							{	
									if (response.respuesta_code == 'OK')
									{
										$("#form_mantenimiento_caja_matriz #tipo_caja_id").html(response.tipocaja_opciones);
										$("#form_mantenimiento_caja_matriz #inventario_id").html(response.inventario_opciones);
										$("#form_mantenimiento_caja_matriz #tamano_bunch_id").html(response.tamano_bunch_opciones);
									}
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function nueva_caja_matriz_init
	
	
	function NuevaCajaMatriz()
	{
		//REINICIAR LOS COMBOS
		var inventario_id			= $("#frm_tipo_caja_busqueda #inventario_id").val();
		var tipo_caja_id			= null;
		var tamano_bunch_id			= null;
		var valor_tallos_x_bunch 	= '25';
		
		//MOSTRAR TITULO EN DIALOG
		var titulo 					= "<b><em style='color:blue'>MERCADO:</em></b> "+ $("#frm_tipo_caja_busqueda #inventario_id option:selected").text();
		$("#modal_matenimiento_caja_matriz_titulo").html(titulo);
		
		
		$("#form_mantenimiento_caja_matriz #inventario_id").val(inventario_id);
		$("#form_mantenimiento_caja_matriz #tipo_caja_id").val(tipo_caja_id);
		$("#form_mantenimiento_caja_matriz #tamano_bunch_id").val(tamano_bunch_id);
		$("#form_mantenimiento_caja_matriz #tallos_x_bunch").val(valor_tallos_x_bunch);
		
		
		//ESTABLECE MI LA GRILLA grid_mantenimiento_caja_matriz A MODO LOCAL SOLO EN EL EVENTO NUEVA CAJA MATRIZ
		/*
		var dataGrid_mantenimiento_caja_matriz = [{ '40': 0, '50': 0, '60': 0, '70': 0, '80': 0, '90': 0, 	'100': 0, '110': 0 }];
		$("#grid_mantenimiento_caja_matriz").jqGrid('setGridParam',{datatype:'local', data:dataGrid_mantenimiento_caja_matriz}).trigger('reloadGrid');
		$('#grid_mantenimiento_caja_matriz').jqGrid("setGridParam",{datatype:"local"}).trigger("reloadGrid");
		*/
		
		$('#grid_mantenimiento_caja_matriz').jqGrid('clearGridData');
	    var newData = [{"40": "0", "50": "0", "60": "0", "70": "0", "80": "0", "90": "0", "100": "0", "110": "0"}];
        $("#grid_mantenimiento_caja_matriz").addRowData('',newData);
      
        
		$('#modal_matenimiento_caja_matriz').modal('show');
		
	}//end NuevaCajaMatriz
	
	
	//FUNCION VALIDA LOS SELECT E INPUT
	function ValidarTamanoBunchs()
	{
		
		if (!ValidateControls('form_mantenimiento_caja_matriz')) 
		{
			return false;
		}else
		{
			
			var tallos_x_bunch 		= $("#form_mantenimiento_caja_matriz #tallos_x_bunch").val();
			
			if (tallos_x_bunch < 1)
				{
				
					alert('LA CANTIDAD DE TALLOS NO PUEDE SER CERO ..!!!');
					return false;
				
				}else
					
				{
					
					RegistrarCajaMatriz();
					
				}//end valida input tallos_x_bunch
			
		}//end ValidateControls
		
	}//end ValidarTamanoBunchs
	
	
	function RegistrarCajaMatriz()
	{
		$('#grid_mantenimiento_caja_matriz').jqGrid('editCell', 1, 0, false);

		//Guarda los datos de la grilla #grid_mantenimiento_caja_matriz
		var grid = $("#grid_mantenimiento_caja_matriz");
		var ids = grid.jqGrid('getDataIDs');
		var arr_data 			= new	Array();
		
		var tipo_caja_id		= $("#form_mantenimiento_caja_matriz #tipo_caja_id").val();
		var inventario_id		= $("#form_mantenimiento_caja_matriz #tipo_caja_id").val();
		var variedad_id			= $("#form_mantenimiento_caja_matriz #variedad_id").val();
		var tallos_x_bunch 		= $("#form_mantenimiento_caja_matriz #tallos_x_bunch").val();
		
		//guarda la grilla local en un arr_data
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
				tipo_caja_id:		$("#form_mantenimiento_caja_matriz #tipo_caja_id").val(),
				inventario_id:		$("#form_mantenimiento_caja_matriz #inventario_id").val(),
				tallos_x_bunch:		$("#form_mantenimiento_caja_matriz #tallos_x_bunch").val(),
				tamano_bunch_id:	$("#form_mantenimiento_caja_matriz #tamano_bunch_id").val(),
				grid_data: 			arr_data
			}
		
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
				'contentType': 'application/json',
				'url':'../../dispo/tipocajamatriz/registrarcajamatriz',
				'control_process':false,
				'show_cargando':true,
				'async':true, 
				'finish':function(response){
					if (response.respuesta_code == 'OK')
					{
						
						$('#modal_matenimiento_caja_matriz').modal('hide')
						cargador_visibility('hide');
						/*	swal({  title: "Registros procesados correctamente!!",   
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
					*/ //se oculta mensaje de registro grabado exitosamente 
						$('#grid_tipo_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
					}else{
						message_error('ERROR', response);
					}//end if									
				}							
			 }
		response = ajax_call(parameters, data);		
		return false;			
				
	}//end GrabarCajaMatriz
	
	
	function mostrar_dialog_mantenimiento_caja_matriz(inventario_id, tipo_caja_id, tamano_bunch_id, tallos_x_bunch)
	{

		
		var inventario_id 		= inventario_id;
		var tipo_caja_id 		= tipo_caja_id;
		var tamano_bunch_id 	= tamano_bunch_id;
		var tallos_x_bunch 		= tallos_x_bunch;
		
		var titulo 					= "<b><em style='color:blue'>MERCADO:</em></b> "+ $("#frm_tipo_caja_busqueda #inventario_id option:selected").text();
		$("#modal_matenimiento_caja_matriz_titulo").html(titulo);
		
		$("#form_mantenimiento_caja_matriz #inventario_id").val(inventario_id);
		$("#form_mantenimiento_caja_matriz #tipo_caja_id").val(tipo_caja_id);
		$("#form_mantenimiento_caja_matriz #tamano_bunch_id").val(tamano_bunch_id);
		$("#form_mantenimiento_caja_matriz #tallos_x_bunch").val(tallos_x_bunch);
		$('#modal_matenimiento_caja_matriz').modal('show');
	}
	
	
	function consultar_caja_matriz_por_clave_alterna(inventario_id, tipo_caja_id, tamano_bunch_id, tallos_x_bunch)
	{
		//muetra dialog y envia pamatros de seleccion 
		var data = 	{
						inventario_id:		inventario_id,
						tipo_caja_id:		tipo_caja_id,
						tamano_bunch_id:	tamano_bunch_id,
						tallos_x_bunch:		tallos_x_bunch
					}
		
		
		mostrar_dialog_mantenimiento_caja_matriz(inventario_id, tipo_caja_id, tamano_bunch_id, tallos_x_bunch);
		$('#grid_mantenimiento_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function consultar_caja_matriz_por_clave_alterna
	

	
	
	function recargarGrilla()
	{
		
		var inventario_id 		=  $("#form_mantenimiento_caja_matriz #inventario_id").val();
		var tipo_caja_id	 	=  $("#form_mantenimiento_caja_matriz #tipo_caja_id").val();
		var tamano_bunch_id 	=  $("#form_mantenimiento_caja_matriz #tamano_bunch_id").val();
		var tallos_x_bunch 		=  $("#form_mantenimiento_caja_matriz #tallos_x_bunch").val();
	
		if ((tipo_caja_id && tamano_bunch_id && tallos_x_bunch)=='')
		{
			return false;			
		}//end if

		//RECARGA LA GRILLA
		$('#grid_mantenimiento_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
	}//end function recargarGrilla
	
	
	
	
	function EliminarajaMatriz()
	{
		
		var grid 					= $("#grid_tipo_caja_matriz");
		var col_inventario_id	 	= jqgrid_get_columnIndexByName(grid, "inventario_id");
		var col_tipo_caja_id 		= jqgrid_get_columnIndexByName(grid, "tipo_caja_id");
		var col_tamano_bunch_id 	= jqgrid_get_columnIndexByName(grid, "tamano_bunch_id");
		var col_tallos_x_bunch 		= jqgrid_get_columnIndexByName(grid, "tallos_x_bunch");
		
        var rowKey 					= grid.getGridParam("selrow");

        if (!rowKey)
		{
			alert("SELECCIONE UN REGISTRO PARA ELIMINAR...");
			return false;
		}//end if
		
		var r = confirm("Esta seguro de eliminar los registros seleccionados...?");
		
		if (r == false)
		{ 
			return false; 
		}//end if
		
		var selectedIDs = grid.getGridParam("selarrrow");
		var arr_data 	= new Array();
		for (var i = 0; i < selectedIDs.length; i++) 
		{
			inventario_id 	= grid.jqGrid('getCell',selectedIDs[i], col_inventario_id);
			tipo_caja_id 	= grid.jqGrid('getCell',selectedIDs[i], col_tipo_caja_id);
			tamano_bunch_id	= grid.jqGrid('getCell',selectedIDs[i], col_tamano_bunch_id);
			tallos_x_bunch 	= grid.jqGrid('getCell',selectedIDs[i], col_tallos_x_bunch);
			
			
			var element				= {};
			element.inventario_id		= inventario_id;
			element.tipo_caja_id  		= tipo_caja_id;
			element.tamano_bunch_id  	= tamano_bunch_id;
			element.tallos_x_bunch  	= tallos_x_bunch;
			
			arr_data.push(element);
		}//end for
		
		
		var data = 	{
				grid_data: 		arr_data,
			}

		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
				'contentType': 'application/json',
				'url':'../../dispo/tipocajamatriz/eliminarcajamatrizmasiva',
				'control_process':false,
				'show_cargando':true,
				'async':true, 
				'finish':function(response)
				{
					
					if (response.respuesta_code == 'OK')
					{
						
						cargador_visibility('hide');
						swal({  title: "Registros eliminados correctamente...",   
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
						$('#grid_tipo_caja_matriz').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
					}else{
						message_error('ERROR', response);
					}//end if	
					
				}//end function response							
			 }
		response = ajax_call(parameters, data);		
		return false;	
		
		console.log(arr_data);
		
	}//end EliminarCajaMatriz
	
	
/****************************************************/
/****************************************************/
/****************************************************/