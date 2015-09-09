/**
 * 
 */
var selRowId_PrecioGrupoGrid 	= 0;	
var selColName_PrecioGrupoGrid 	= 0;
var seliRow_PrecioGrupoGrid 	= 0;		
var seliCol_PrecioGrupoGrid 	= 0;	
var valAnt_PrecioGrupoGrid		= null;

$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	precioGrupo_init();
	
	$("#frm_precio_grupo #grupo_precio_cab_id").on('change', function(event){
		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		
		GrupoPrecio_ConsultarInfoPrecioGrupoCab($("#frm_precio_grupo #grupo_precio_cab_id").val());
		return false;		
	});
	
	
	$("#frm_precio_grupo #tipo_precio").on('change', function(event){
		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});	
	
	
	$("#frm_precio_grupo #btn_consultar").on('click', function(event){ 
		$('#grid_precio_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	/*
	$("#frm_precio_grupo #btn_nuevo").on('click', function(event){ 
		$("#dialog_dispo_variedad_titulo").html('Variedad - Grado: 30');
		$('#dialog_dispo_variedad').modal('show')
		return false;
	});		
	
	$("#frm_precio_grupo_stock #btn_grabar").on('click', function(event){ 
		grabar_dispoGrupo_stock();
		return false;
	});	
	*/

	
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Dispobilidad General-----------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_precio_grupo").jqGrid({
		url:'../../dispo/grupoprecio/listadodata',
		postData: {
			grupo_precio_cab_id: 	function() {return $("#frm_precio_grupo #grupo_precio_cab_id").val();},
			tipo_precio:			function() {return $("#frm_precio_grupo #tipo_precio").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Id','Variedad','40','50','60','70','80','90','100','110'],
		colModel:[
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string"},
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
													}else{
														return false;
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
			seliCol_PrecioGrupoGrid  = iCol;
			seliRow_PrecioGrupoGrid  = iRow;
			valAnt_PrecioGrupoGrid   = value;
			
			//console.log('beforeEditCell iCol:', iCol,'*iRow:',iRow,'*val_PrecioGrupoGrid:',val_PrecioGrupoGrid);			
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
			var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_precio_grupo"), "variedad_id");
			var variedad_id			= jQuery("#grid_precio_grupo").jqGrid('getCell',rowid, col_variedad_id);

			var grado_id			= jqgrid_get_columnNameByIndex($("#grid_precio_grupo"), iCol);
			var tipo_precio			= $("#frm_precio_grupo #tipo_precio").val();
			
			GrupoPrecio_Grabar(grupo_precio_cab_id, variedad_id, grado_id, tipo_precio, precio);
		},
	});
	
	//Filtro
//	$("#grid_precio_grupo").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	
	function gridGrupoPrecio_GradosFormatter(cellvalue, options, rowObject){
		cellvalue = number_val(cellvalue, 2);		
		var color = "Black";
		if (cellvalue==0)
		{
			color = "LightGray";
		}
		cellvalue = $.number( cellvalue, 2, '.',','); 		
		new_format_value = '<span style="color:'+color+'">'+cellvalue+ '</a>';
		return new_format_value;
	}
		
	function gridGrupoPrecio_GradosUnFormatter(cellvalue, options, cell){
		return number_val($('span', cell).html());
	}	
		
		
	jQuery("#grid_precio_grupo").jqGrid('navGrid','#pager_precio_grupo',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
});
	
	
function precioGrupo_init()
{
	var data = 	{
					opcion: 'panel-control-disponibilidad',
					grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
				}
	data = JSON.stringify(data);
	var parameters = {	'type': 'POST',//'POST',
						'contentType': 'application/json',
						'url':'../../dispo/grupoprecio/initcontrols',
						'show_cargando':false,
						'async':true,
						'finish':function(response){		
							$("body #frm_precio_grupo #grupo_precio_cab_id").html(response.grupo_precio_opciones);
							$("body #frm_precio_grupo #tipo_precio").html(response.tipo_precio_opciones);
							
						 }							
					 }
	response = ajax_call(parameters, data);		
	return false;	
}//end function precioGrupo_init


function GrupoPrecio_Grabar(grupo_precio_cab_id, variedad_id, grado_id, tipo_precio, precio)
{
	var data = 	{
					grupo_precio_cab_id:		grupo_precio_cab_id,
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
								$("#frm_precio_grupo #info_grupo_precio_cab").html(response.row.inventario_id+' - '+response.row.calidad_nombre+' - '+response.row.calidad_clasifica_fox );
							}else{
								message_error('ERROR', response);
							}//end if									
						}							
					 }
	response = ajax_call(parameters, data);		
	return false;				
}//end function GrupoPrecio_ConsultarInfoPrecioGrupoCab