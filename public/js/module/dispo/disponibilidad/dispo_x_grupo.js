/**
 * 
 */
var selRowId_DispoGrupoGrid 	= 0;	
var selColName_DispoGrupoGrid 	= 0;
var seliRow_DispoGrupoGrid 		= 0;		
var seliCol_DispoGrupoGrid 		= 0;	
var valAnt_DispoGrupoGrid		= null;

$(document).ready(function () {

	/*----------------------Se cargan los controles -----------------*/
	dispoGrupo_init();
	//DispoGrupo_initMantenimiento();
	
	$("#frm_dispo_grupo #grupo_dispo_cab_id").on('change', function(event){
		//$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		DispoGrupo_ConsultarInfoDispoGrupoCab($("#frm_dispo_grupo #grupo_dispo_cab_id").val());
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});
	
	
	$("#frm_dispo_grupo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	

	$("#frm_dispo_grupo #btn_nuevo").on('click', function(event){ 
		DispoGrupo_Nuevo(); 
		return false;
	});		
	
	$("#frm_dispo_grupo #editar_dispo_grupo").on('click', function(event){ 	
		$("#frm_dispo_grupo_mantenimiento #accion").val('M');
		DispoGrupo_Consultar($("#frm_dispo_grupo #grupo_dispo_cab_id").val())	
	});
	
	
	$("#frm_dispo_grupo_mantenimiento #btn_grabar").on('click', function(event){ 
		DispoGrupo_GrabarRegistro();
		return false;
	});	
	
	
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Dispobilidad General-----------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispo_grupo").jqGrid({
		url:'../../dispo/grupodispo/listadodata',
		postData: {
			grupo_dispo_cab_id: 	function() {return $("#frm_dispo_grupo #grupo_dispo_cab_id").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Id','Variedad','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU'],
		colModel:[
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string"},
			{name:'dgen_40',index:'dgen_40', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter},	
			{name:'dgru_40',index:'dgru_40', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && e.shiftKey)   // tab
																{
																	if ((seliRow_DispoGrupoGrid - 1) <= 0) return false;
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + jqgrid_get_columnIndexByName($("#grid_dispo_grupo"), "dgru_110") + ", true);", 10);														
																}																			
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
			{name:'dgen_50',index:'dgen_50', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },
			{name:'dgru_50',index:'dgru_50', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }											
			},
			{name:'dgen_60',index:'dgen_60', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },	
			{name:'dgru_60',index:'dgru_60', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																																							
									 }											
			},
			{name:'dgen_70',index:'dgen_70', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },	
			{name:'dgru_70',index:'dgru_70', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																																								
									 }											
			},
			{name:'dgen_80',index:'dgen_80', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },	
			{name:'dgru_80',index:'dgru_80', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },		
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																																						
									 }											
			},
			{name:'dgen_90',index:'dgen_90', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },	
			{name:'dgru_90',index:'dgru_90', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
			{name:'dgen_100',index:'dgen_100', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },	
			{name:'dgru_100',index:'dgru_100', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
			{name:'dgen_110',index:'dgen_110', width:50, align:"center", sorttype:"int", formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, },	
			{name:'dgru_110',index:'dgru_110', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoGrupo_GradosFormatter, unformat:gridDispoGrupo_GradosUnFormatter, 
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && !e.shiftKey)   // tab
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + jqgrid_get_columnIndexByName($("#grid_dispo_grupo"), "dgru_40") + ", true);", 10);														
																}																
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " + 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_grupo').editCell(" + seliRow_DispoGrupoGrid + " - 1, " + seliCol_DispoGrupoGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
		],
		rowNum:999999,
		pager: '#pager_dispo_grupo',
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
		loadComplete: function (data) {
			var ids =jQuery("#grid_dispo_grupo").jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++){	
				var rowId = ids[i];
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_40','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_50','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_60','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_70','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_80','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_90','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_100','','ui-jqgrid-blockcell-background');
				$("#grid_dispo_grupo").jqGrid('setCell',rowId,'dgru_110','','ui-jqgrid-blockcell-background');																				
			}//end for

			autoHeight_JqGrid_Refresh("grid_dispo_grupo");
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
			seliCol_DispoGrupoGrid  = iCol;
			seliRow_DispoGrupoGrid  = iRow;
			valAnt_DispoGrupoGrid   = value;
			//console.log('beforeEditCell iCol:', iCol,'*iRow:',iRow,'*valAnt_DispoGrupoGrid:',valAnt_DispoGrupoGrid);			
		},			
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			//Evita se llame la funcion grabar sin que se haya modificado el valor
			var cantidad  = number_val(jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, iCol), 2);
			if (number_val(cantidad)==number_val(valAnt_DispoGrupoGrid))
			{
				return false;
			}//end if
						
			var grupo_dispo_cab_id  = $("#frm_dispo_grupo #grupo_dispo_cab_id").val();			
			var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_dispo_grupo"), "variedad_id");
			var variedad_id			= jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, col_variedad_id);

			var nameColGrado		= jqgrid_get_columnNameByIndex($("#grid_dispo_grupo"), iCol);
			var arrColGrado			= nameColGrado.split("_");
			var grado_id			= arrColGrado[1];
			//console.log('grado_id:',grado_id);
			
			iCol_StockMaximo = iCol - 1;			
			var stock_maximo = number_val(jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, iCol_StockMaximo), 0);
			var stock_grupo  = number_val(jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, iCol), 0);

			//console.log('stock_maximo:',stock_maximo,'*stock_grupo:',stock_grupo);
			if (stock_grupo > stock_maximo)
			{				
				 alert('Stock del Grupo no puede sobrepasar al Stock General');
				 $("#grid_dispo_grupo").jqGrid('setCell', seliRow_DispoGrupoGrid, seliCol_DispoGrupoGrid, valAnt_DispoGrupoGrid);
				 return false;
			}//end if


			DispoGrupo_GrabarStock(grupo_dispo_cab_id, variedad_id, grado_id, stock_grupo);
		},
	});
	
	//Filtro
//	$("#grid_dispo_grupo").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	
	//Agrupar
	jQuery("#grid_dispo_grupo").jqGrid('setGroupHeaders', {
	  useColSpanStyle: true, 
	  groupHeaders:[
		{startColumnName: 'dgen_40', numberOfColumns: 2, titleText: '<em>40</em>'},
		{startColumnName: 'dgen_50', numberOfColumns: 2, titleText: '<em>50</em>'},
		{startColumnName: 'dgen_60', numberOfColumns: 2, titleText: '<em>60</em>'},
		{startColumnName: 'dgen_70', numberOfColumns: 2, titleText: '<em>70</em>'},
		{startColumnName: 'dgen_80', numberOfColumns: 2, titleText: '<em>80</em>'},
		{startColumnName: 'dgen_90', numberOfColumns: 2, titleText: '<em>90</em>'},
		{startColumnName: 'dgen_100', numberOfColumns: 2, titleText: '<em>100</em>'},
		{startColumnName: 'dgen_110', numberOfColumns: 2, titleText: '<em>110</em>'},
	  ]	
	});	
	
	jQuery("#grid_dispo_grupo").jqGrid('navGrid','#pager_dispo_grupo',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	});
	
	
	function gridDispoGrupo_GradosFormatter(cellvalue, options, rowObject){
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
		
	function gridDispoGrupo_GradosUnFormatter(cellvalue, options, cell){
		return number_val($('span', cell).html());
	}	
	

	function dispoGrupo_init()
	{
		//if (typeof(grupo_dispo_1er_elemento) == 'undefined') {grupo_dispo_1er_elemento = '&lt;SELECCIONE&gt;';}
		$("#grid_dispo_grupo").jqGrid('clearGridData');
		$("#frm_dispo_grupo #info_grupo_dispo_cab").html('');
		
		var data = 	{
						opcion: 'panel-control-disponibilidad',
						grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
						//grupo_dispo_cab_id:			grupo_dispo_cab_id;
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response){		
								$("body #frm_dispo_grupo #grupo_dispo_cab_id").html(response.grupo_dispo_opciones);
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function dispoGrupo_init
	
	
	
	function DispoGrupo_GrabarStock(grupo_dispo_cab_id, variedad_id, grado_id, stock_grupo)
	{
		var data = 	{
						grupo_dispo_cab_id:			grupo_dispo_cab_id,
						variedad_id:				variedad_id,
						grado_id:					grado_id,
						cantidad_bunch_disponible:	stock_grupo,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/grabarStock',
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
	
	}//end function DispoGrupo_GrabarStock



	function DispoGrupo_ConsultarInfoDispoGrupoCab(grupo_dispo_cab_id)
	{
		var data = 	{
						grupo_dispo_cab_id:		grupo_dispo_cab_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/consultarcabecera',
							'control_process':true,
							'show_cargando':false,
							'async':true,
							'finish':function(response){
								if (response.respuesta_code == 'OK')
								{
									$("#frm_dispo_grupo #info_grupo_dispo_cab").html(response.row.inventario_id+' - '+response.row.calidad_nombre+' - '+response.row.calidad_clasifica_fox );
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function DispoGrupo_ConsultarInfoDispoGrupoCab
	
	
	function DispoGrupo_Nuevo()
	{
		var data = 	{
						inventario_1er_elemento:	'&lt;SELECCIONE&gt;',
						calidad_1er_elemento:		'&lt;SELECCIONE&gt;',
					}		
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/nuevodata',
							'control_process':true,
							'show_cargando':true,
							
							'finish':function(response){	
								$("#accion").val("I");
								$("#dialog_dispo_grupo_mantenimiento_titulo").html("NUEVO REGISTRO");
								$("#frm_dispo_grupo_mantenimiento #id").val('');
								$("#frm_dispo_grupo_mantenimiento #nombre").val('');
								$("body #frm_dispo_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
								$("body #frm_dispo_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
								
								$('#dialog_dispo_grupo_mantenimiento').modal('show')								
							 }							
				           }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function DispoGrupo_Nuevo
	
	
	function DispoGrupo_GrabarRegistro()
	{
		if (!ValidateControls('frm_dispo_grupo_mantenimiento')) 
		{
			return false;
		}//end if
				
		var accion  		= $("#frm_dispo_grupo_mantenimiento #accion").val();
		var id  			= $("#frm_dispo_grupo_mantenimiento #id").val();
		var nombre  		= $("#frm_dispo_grupo_mantenimiento #nombre").val();
		var inventario_id 	= $("#frm_dispo_grupo_mantenimiento #inventario_id").val();
		var calidad_id  	= $("#frm_dispo_grupo_mantenimiento #calidad_id").val();

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
							'url':'../../dispo/grupodispo/grabardata',
							'control_process':true,
							'show_cargando':false,
							'async':true, 
							'finish':function(response){
									if ($("#frm_dispo_grupo_mantenimiento #accion").val()=='I'){
										dispoGrupo_init();
									}//end if
									DispoGrupo_MostrarRegistro(response);
									
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
	}//end function DispoGrupo_GrabarRegistro
	


	function DispoGrupo_MostrarRegistro(response)
	{
		var row = response.row;
		
		if (row==null) return false;
		
		$("#frm_dispo_grupo_mantenimiento #accion").val("M");
		$("#frm_dispo_grupo_mantenimiento #id").val(row.id);
		$("#frm_dispo_grupo_mantenimiento #nombre").val(row.nombre);
		$("#frm_dispo_grupo_mantenimiento #inventario_id").html(response.inventario_opciones);
		$("#frm_dispo_grupo_mantenimiento #calidad_id").html(response.calidad_opciones);
	}//end function DispoGrupo_MostrarRegistro
	
	
	function DispoGrupo_Consultar(id)
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{grupo_dispo_cab_id:id}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/consultarregistrodata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									DispoGrupo_MostrarRegistro(response);
									cargador_visibility('hide');

									$('#dialog_dispo_grupo_mantenimiento').modal('show')
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function DispoGrupo_Consultar