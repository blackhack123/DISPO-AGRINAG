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
	
	$("#frm_dispo_grupo #btn_excel").on('click', function(event){ 
		DispoGrupo_ExportarExcel();
		return false;
	});	
	
	
	$("#frm_dispo_grupo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	

	$("#frm_dispo_grupo #grupo_dispo_cab_id").on('change', function(event){
		if ($('#frm_dispo_grupo #grupo_dispo_cab_id').val()=='') 
		{
			$("#grid_dispo_grupo").jqGrid('clearGridData');
			return false;
		}//end if
		
		DispoGrupo_ConsultarInfoDispoGrupoCab($("#frm_dispo_grupo #grupo_dispo_cab_id").val());
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});

	
	$("#frm_dispo_grupo #color_ventas_id, #frm_dispo_grupo #calidad_variedad_id").on('change', function(event){
		if ($('#frm_dispo_grupo #grupo_dispo_cab_id').val()=='') 
		{
			$("#grid_dispo_grupo").jqGrid('clearGridData');
			return false;
		}//end if

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
	
	$("#frm_dispo_grupo #btn_modificar").on('click', function(event){ 
		$("#frm_grupo_usuario_mantenimiento #accion").val('M');
		DispoGrupo_Consultar($("#frm_dispo_grupo #grupo_dispo_cab_id").val())	
		return false;
	});		
	
/*	$("#frm_dispo_grupo #editar_dispo_grupo").on('click', function(event){ 	
		$("#frm_dispo_grupo_mantenimiento #accion").val('M');
		DispoGrupo_Consultar($("#frm_dispo_grupo #grupo_dispo_cab_id").val())	
	});
*/	
	
	$("#frm_dispo_grupo_mantenimiento #btn_grabar").on('click', function(event){ 
		DispoGrupo_GrabarRegistro();
		return false;
	});	
	

	$( "body" ).on( "click", ".DispoGrupoCambiarValoresPorGrado", function() {
		var grado_id = $(this).data('grado');
		DispoGrupo_OpenDialog_GradosStock(grado_id);
	});	
	
	
	
	
	$("#frm_dispo_grupo_stockgrado #porcentaje").on('change', function(event){ 
		$("#frm_dispo_grupo_stockgrado #valor").val('');
		return false;
	});

	$("#frm_dispo_grupo_stockgrado #valor").on('change', function(event){ 
		$("#frm_dispo_grupo_stockgrado #porcentaje").val('');
		return false;
	});	

	$("#frm_dispo_grupo_stockgrado #btn_grabar").on('click', function(event){ 
		DispoGrupo_GrabarStockPorGrado();
	});

	
	
	$("#frm_dispo_grupo #btn_excel_cajas_usa").on('click', function(event){ 
		DispoGrupo_ExportarExcelCajas('USA');
		return false;
	});	


	$("#frm_dispo_grupo #btn_excel_cajas_rusa").on('click', function(event){ 
		DispoGrupo_ExportarExcelCajas('RUS');
		return false;
	});	
	
	
	/*--------------------------------------------------------------------------*/
	/**/
	$("#frm_dispo_grupo #btn_skype_cajas_usa").on('click', function(event){ 
		DispoGrupo_OpenDialogExportarSkypeCajas('USA')
		return false;
	});	
	
	$("#frm_dispo_grupo #btn_skype_cajas_rusa").on('click', function(event){ 
		DispoGrupo_OpenDialogExportarSkypeCajas('RUS')
		return false;
	});		
	
	//DIALOG
	$("#frm_dispo_grupo_generar_skype #btn_separar_archivo").on('click', function(event){ 
		var separar_archivo = 'S';
		DispoGrupo_ExportarSkypeCajas($("#frm_dispo_grupo_generar_skype #inventario_id").val(), separar_archivo);
		return false;
	});	

	//DIALOG
	$("#frm_dispo_grupo_generar_skype #btn_unificar_archivo").on('click', function(event){
		var separar_archivo = 'N'; 
		DispoGrupo_ExportarSkypeCajas($("#frm_dispo_grupo_generar_skype #inventario_id").val(), separar_archivo);
		return false;
	});	
	

	/*---------------------------------------------------------------------------*/
	$("#frm_dispo_grupo #btn_excel_interno_usa").on('click', function(event){ 
		DispoGrupo_ExportarExcelInternoCajas('USA');
		return false;
	});	
	
	$("#frm_dispo_grupo #btn_excel_interno_rusa").on('click', function(event){ 
		DispoGrupo_ExportarExcelInternoCajas('RUS');
		return false;
	});		

	$("#frm_dispo_grupo #btn_skype_cajas_x_finca_usa").on('click', function(event){ 
		DispoGrupo_ExportarSkypeCajasXFincas('USA');
		return false;
	});
	
	$("#frm_dispo_grupo #btn_skype_cajas_x_finca_rusa").on('click', function(event){ 
		DispoGrupo_ExportarSkypeCajasXFincas('RUS');
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
			color_ventas_id:		function() {return $("#frm_dispo_grupo #color_ventas_id").val();},
			calidad_variedad_id:	function() {return $("#frm_dispo_grupo #calidad_variedad_id").val();}
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['producto_id','tallos_x_bunch','Id','Variedad','Color','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU','GEN','GRU'],
		colModel:[
			{name:'producto_id',index:'producto_id', width:50, sorttype:"string", hidden:true},
			{name:'tallos_x_bunch',index:'tallos_x_bunch', width:50, sorttype:"int", hidden:true},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string", formatter: gridDispoGrupo_VariedadNombreFormatter},
			{name:'color_ventas_nombre',index:'color_ventas_nombre', width:100, sorttype:"string"},
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
			var col_producto_id 	= jqgrid_get_columnIndexByName($("#grid_dispo_grupo"), "producto_id");			
			var col_tallos_x_bunch	= jqgrid_get_columnIndexByName($("#grid_dispo_grupo"), "tallos_x_bunch");			
			var variedad_id			= jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, col_variedad_id);
			var producto_id			= jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, col_producto_id);
			var tallos_x_bunch		= jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, col_tallos_x_bunch);

			var nameColGrado		= jqgrid_get_columnNameByIndex($("#grid_dispo_grupo"), iCol);
			var arrColGrado			= nameColGrado.split("_");
			var grado_id			= arrColGrado[1];
			
			iCol_StockMaximo = iCol - 1;			
			var stock_maximo = number_val(jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, iCol_StockMaximo), 0);
			var stock_grupo  = number_val(jQuery("#grid_dispo_grupo").jqGrid('getCell',rowid, iCol), 0);

			if (stock_grupo > stock_maximo)
			{				
				 alert('Stock del Grupo no puede sobrepasar al Stock General');
				 $("#grid_dispo_grupo").jqGrid('setCell', seliRow_DispoGrupoGrid, seliCol_DispoGrupoGrid, valAnt_DispoGrupoGrid);
				 return false;
			}//end if


			DispoGrupo_GrabarStock(producto_id, grupo_dispo_cab_id, variedad_id, grado_id, tallos_x_bunch, stock_grupo);
		},
	});
	
	//Filtro
//	$("#grid_dispo_grupo").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
	
	//Agrupar
	jQuery("#grid_dispo_grupo").jqGrid('setGroupHeaders', {
	  useColSpanStyle: true, 
	  groupHeaders:[
		{startColumnName: 'dgen_40', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="40"><em>40</em></a>'},
		{startColumnName: 'dgen_50', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="50"><em>50</em></a>'},
		{startColumnName: 'dgen_60', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="60"><em>60</em></a>'},
		{startColumnName: 'dgen_70', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="70"><em>70</em></a>'},
		{startColumnName: 'dgen_80', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="80"><em>80</em></a>'},
		{startColumnName: 'dgen_90', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="90"><em>90</em></a>'},
		{startColumnName: 'dgen_100', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="100"><em>100</em></a>'},
		{startColumnName: 'dgen_110', numberOfColumns: 2, titleText: '<a href="#" class="DispoGrupoCambiarValoresPorGrado" data-grado="110"><em>110</em></a>'},
	  ]	
	});	
	
	jQuery("#grid_dispo_grupo").jqGrid('navGrid','#pager_dispo_grupo',{edit:false,add:false,del:false});



	function gridDispoGrupo_VariedadNombreFormatter(cellvalue, options, rowObject){
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
	/*-----Se configura el JQGRID de COLOR de la DISPO X GRUPO-------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispogrupo_color").jqGrid({
		url:'../../dispo/colorventas/listadodata',
		postData: {
		},
		datatype: "json",
		loadonce: true,			
		height:'160',
		colNames:['id','Color'],
		colModel:[
			{name:'id',index:'id',  sorttype:"int", hidden:true},
			{name:'nombre',index:'nombre'},
		],
		rowNum:999999,
		pager: '#pager_dispogrupo_color',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		width: 280,
//		autowidth: true,
		shrinkToFit: true,
//		forceFit: true,
//		resizeStop: grid_setAutoHeight,
		rownumbers: true,
/*		cellsubmit: 'clientArray',
		editurl: 'clientArray',		
*/		multiselect: true,
		jsonReader: {
			repeatitems : false,
		},
		loadComplete: function (data) {
			//autoHeight_JqGrid_Refresh("grid_dispo_grupo");
			//autoWidthContainer_JqGrid("grid_dispogrupo_color");
		},
		loadBeforeSend: function (xhr, settings) {
			/*this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request*/
		},
		loadError: function (jqXHR, textStatus, errorThrown) {
//			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});							
	jQuery("#grid_dispogrupo_color").jqGrid('navGrid','#pager_dispogrupo_color',{edit:false,add:false,del:false});
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	
	/*---------------------------------------------------------------*/
	/*---Se configura el JQGRID de CATEGORIAS de la DISPO X GRUPO----*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispogrupo_categoria").jqGrid({
		url:'../../dispo/calidadvariedad/listadodata',
		postData: {
		},
		datatype: "json",
		loadonce: true,			
		height:'160',
		colNames:['id','Categoria'],
		colModel:[
			{name:'id',index:'id',  sorttype:"int", hidden:true},
			{name:'nombre',index:'nombre'},
		],
		rowNum:999999,
		pager: '#pager_dispogrupo_categoria',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		width: 280,
//		autowidth: true,
		shrinkToFit: true,
//		forceFit: true,
//		resizeStop: grid_setAutoHeight,
		rownumbers: true,
/*		cellEdit: true,
		cellsubmit: 'clientArray',
		editurl: 'clientArray',		
*/		multiselect: true,
		jsonReader: {
			repeatitems : false,
		},
		loadComplete: function (data) {
			//autoHeight_JqGrid_Refresh("grid_dispo_grupo");
			//autoWidthContainer_JqGrid("grid_dispogrupo_color");
		},
		loadBeforeSend: function (xhr, settings) {
			/*this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request*/
		},
		loadError: function (jqXHR, textStatus, errorThrown) {
			//message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});							
	jQuery("#grid_dispogrupo_categoria").jqGrid('navGrid','#pager_dispogrupo_categoria',{edit:false,add:false,del:false});
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
		//Deshabilita la botonera
		$("#frm_dispo_grupo button").prop('disabled', true);

		$("#grid_dispo_grupo").jqGrid('clearGridData');
		$("#frm_dispo_grupo #info_grupo_dispo_cab").html('');
		
		var data = 	{
						opcion: 'panel-control-disponibilidad',
						grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
						//grupo_dispo_cab_id:			grupo_dispo_cab_id;
						color_ventas_1er_elemento:  '&lt;COLORES&gt;',
						calidad_variedad_1er_elemento:  '&lt;CATEGORIAS&gt;'
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response){										
								$("body #frm_dispo_grupo #grupo_dispo_cab_id").html(response.grupo_dispo_opciones);
								$("body #frm_dispo_grupo #color_ventas_id").html(response.color_ventas_opciones);
								$("body #frm_dispo_grupo #calidad_variedad_id").html(response.calidad_variedad_opciones);

								//Habilita la botonera
								$("#frm_dispo_grupo button").prop('disabled', false);
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function dispoGrupo_init
	
	
	
	function DispoGrupo_GrabarStock(producto_id, grupo_dispo_cab_id, variedad_id, grado_id, tallos_x_bunch, stock_grupo)
	{
		var data = 	{
						producto_id: 				producto_id,
						grupo_dispo_cab_id:			grupo_dispo_cab_id,
						variedad_id:				variedad_id,
						grado_id:					grado_id,
						tallos_x_bunch:				tallos_x_bunch,
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
									//Lineas de codigo
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
						inventario_1er_elemento:	'USA',
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
									$('#dialog_dispo_grupo_mantenimiento').modal('hide')

									//----Se refresca los combos de las pestañas de los tabs
									$("#frm_dispo_grupo #grupo_dispo_cab_id").html(response.grupo_opciones);   					//DISPO X GRUPO
									DispoGrupo_ConsultarInfoDispoGrupoCab($("#frm_dispo_grupo #grupo_dispo_cab_id").val());  	//ACTUALIZA LA INFO ADICINOAL

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
		$("#dialog_dispo_grupo_mantenimiento_titulo").html($("#frm_dispo_grupo #grupo_dispo_cab_id option:selected").text());
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




	function DispoGrupo_OpenDialog_GradosStock(grado_id)
	{
		var grupo_dispo_cab_id	= $("#frm_dispo_grupo #grupo_dispo_cab_id").val();
		var color_ventas_id		= $("#frm_dispo_grupo #color_ventas_id").val();
		var calidad_variedad_id	= $("#frm_dispo_grupo #calidad_variedad_id").val();
			
		if (grupo_dispo_cab_id=='')
		{
			alert('Debe de Seleccionar un Grupo');
			$("#frm_dispo_grupo #grupo_dispo_cab_id").focus();
			return false;
		}//end if

		//Almacena las variables que son parameros para la modificacion masiva
		$("#frm_dispo_grupo_stockgrado #grupo_dispo_cab_id").val(grupo_dispo_cab_id);
		$("#frm_dispo_grupo_stockgrado #grado_id").val(grado_id);

		//---------------------------------------------------------
		//Desmarca todos los colores
		$('#grid_dispogrupo_color').jqGrid('resetSelection');
		
		//Marca el color que paso como parametros
		var ids = $('#grid_dispogrupo_color').jqGrid('getDataIDs');		
		var len = ids.length;
		for (var i=0; i < len; i++) {
			if ((color_ventas_id == ids[i])||(color_ventas_id == ''))
			{
				$('#grid_dispogrupo_color').jqGrid('setSelection', ids[i]);
			}//end if
		}//end for
		
		//-----------------------------------------------------------
		//Desmarca todos los categorias
		$('#grid_dispogrupo_categoria').jqGrid('resetSelection');
		
		//Marca las categorias que paso como parametros
		var ids = $('#grid_dispogrupo_categoria').jqGrid('getDataIDs');		
		var len = ids.length;
		for (var i=0; i < len; i++) {
			if ((calidad_variedad_id == ids[i])||(calidad_variedad_id == ''))
			{
				$('#grid_dispogrupo_categoria').jqGrid('setSelection', ids[i]);
			}//end if
		}//end for		
		
		//-------------------------------------------------------------
		//Setea el titulo 
		//Abre el dialogo
		var titulo = "<b><em style='color:blue'>GRUPO:</em></b> "+$("#frm_dispo_grupo #grupo_dispo_cab_id option:selected").text()+" - <b><em style='color:blue'>GRADO:</em></b> "+grado_id;
		$("#dialog_dispo_grupo_stockgrado_titulo").html(titulo);
		$('#dialog_dispo_grupo_gradostock').modal('show');
	}//end function DispoGrupo_OpenDialog_GradosStock		
	
	
	
	
	function DispoGrupo_GrabarStockPorGrado()
	{
		var grupo_dispo_cab_id		= $("#frm_dispo_grupo_stockgrado #grupo_dispo_cab_id").val();
		var grado_id				= $("#frm_dispo_grupo_stockgrado #grado_id").val();
		var color_ventas_ids 	 	= $("#grid_dispogrupo_color").jqGrid('getGridParam','selarrrow');
		var calidad_variedad_ids 	= $("#grid_dispogrupo_categoria").jqGrid('getGridParam','selarrrow');
		var porcentaje				= $("#frm_dispo_grupo_stockgrado #porcentaje").val();
		var valor					= $("#frm_dispo_grupo_stockgrado #valor").val();

		var data = 	{
						grupo_dispo_cab_id:		grupo_dispo_cab_id,
						grado_id:				grado_id,
						color_ventas_ids:		color_ventas_ids,
						calidad_variedad_ids:	calidad_variedad_ids,
						porcentaje:				porcentaje,
						valor:					valor
					}
		data = JSON.stringify(data);
		
		
		$("frm_dispo_grupo_stockgrado #btn_grabar").button('loading')
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/grabarporgrupoporgrado',
							'control_process':false,
							'show_cargando':true,
							'async':true, 
							'finish':function(response){
									$("frm_dispo_grupo_stockgrado #btn_grabar").button('reset');
									cargador_visibility('hide');
									$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
									$('#dialog_dispo_grupo_gradostock').modal('hide');
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;					
	}//end function DispoGrupo_GrabarStockPorGrado
	
	
	
	
		function DispoGrupo_ExportarExcel()
		{
			if ($("#frm_dispo_grupo #grupo_dispo_cab_id").val()=='')
			{
				swal({  title: "Debe seleccionar un grupo",   
					//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
					//html:true,
					type: "warning",
					showCancelButton: false,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "OK",
					cancelButtonText: "",
					closeOnConfirm: false,
					closeOnCancel: false,
				});
				return false;
			}//end if
						
			cargador_visibility('show');

			var url = '../../dispo/grupodispo/exportarExcelDispoGrupo';
			var params = '?grupo_dispo_cab_id='+$("#frm_dispo_grupo #grupo_dispo_cab_id").val()+
						 '&color_ventas_id='+$("#frm_dispo_grupo #color_ventas_id").val()+
					 	 '&calidad_variedad_id='+$("#frm_dispo_grupo #calidad_variedad_id").val();
			url = url + params;
			var win = window.open(url);
			
			cargador_visibility('hide');
		}//end function DispoGeneral_ExportarExcel


	
	
	
	function DispoGrupo_ExportarExcelCajas(inventario_id)
	{
		if ($("#frm_dispo_grupo #grupo_dispo_cab_id").val()=='')
		{
			swal({  title: "Debe seleccionar un grupo",   
				//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
				//html:true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "OK",
				cancelButtonText: "",
				closeOnConfirm: false,
				closeOnCancel: false,
			});
			return false;
		}//end if
				
		cargador_visibility('show');

		var url = '../../dispo/grupodispo/exportaExcelCajasDispoGrupo';
		var params = '?inventario_id='+inventario_id+'&grupo_dispo_cab_id='+$("#frm_dispo_grupo #grupo_dispo_cab_id").val()+
					 '&color_ventas_id='+$("#frm_dispo_grupo #color_ventas_id").val()+
					 '&calidad_variedad_id='+$("#frm_dispo_grupo #calidad_variedad_id").val();
		url = url + params;
		var win = window.open(url);
		
		cargador_visibility('hide');
	}//end function DispoGeneral_ExportarExcel
	

	function DispoGrupo_OpenDialogExportarSkypeCajas(inventario_id)
	{
		if ($("#frm_dispo_grupo #grupo_dispo_cab_id").val()=='')
		{
			swal({  title: "Debe seleccionar un grupo",   
				//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
				//html:true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "OK",
				cancelButtonText: "",
				closeOnConfirm: false,
				closeOnCancel: false,
			});
			return false;
		}else{
			$("#frm_dispo_grupo_generar_skype #inventario_id").val(inventario_id);
			
			$("#dialog_dispo_grupo_generar_skype").modal('show');
		}//end if
	}//end function DispoGrupo_DialogExportarSkypeCajas
	
	
	function DispoGrupo_ExportarSkypeCajas(inventario_id, separar_archivo)
	{ 
		cargador_visibility('show');
	
		var url = '../../dispo/grupodispo/exportarSkypeCajasDispoGrupo';
		var params = '?inventario_id='+inventario_id+'&grupo_dispo_cab_id='+$("#frm_dispo_grupo #grupo_dispo_cab_id").val()+
					 '&color_ventas_id='+$("#frm_dispo_grupo #color_ventas_id").val()+
					 '&calidad_variedad_id='+$("#frm_dispo_grupo #calidad_variedad_id").val()+
					 '&separar_archivo='+separar_archivo;
		url = url + params;
		var win = window.open(url);
	
		cargador_visibility('hide');
		swal.close();					
	
		return false;
	}//end function DispoGrupo_ExportarSkypeCajas
		
		
		
	function DispoGrupo_ExportarSkypeCajasXFincas(inventario_id)
	{ 
		if ($("#frm_dispo_grupo #grupo_dispo_cab_id").val()=='')
		{
			swal({  title: "Debe seleccionar un grupo",   
				//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
				//html:true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "OK",
				cancelButtonText: "",
				closeOnConfirm: false,
				closeOnCancel: false,
			});
			return false;
		}//end if
			
		cargador_visibility('show');

		var url = '../../dispo/grupodispo/exportarSkypeCajasXFincasDispoGrupo';
		var params = '?inventario_id='+inventario_id+'&grupo_dispo_cab_id='+$("#frm_dispo_grupo #grupo_dispo_cab_id").val()+
					 '&color_ventas_id='+$("#frm_dispo_grupo #color_ventas_id").val()+
					 '&calidad_variedad_id='+$("#frm_dispo_grupo #calidad_variedad_id").val();
		url = url + params;
		var win = window.open(url);

		cargador_visibility('hide');

		return false;
	}//end function DispoGrupo_ExportarSkypeCajasXFincas		
		
		
		
	function DispoGrupo_ExportarExcelInternoCajas(inventario_id)
	{  
		if ($("#frm_dispo_grupo #grupo_dispo_cab_id").val()=='')
		{
			swal({  title: "Debe seleccionar un grupo",   
				//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
				//html:true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "OK",
				cancelButtonText: "",
				closeOnConfirm: false,
				closeOnCancel: false,
			});
			return false;
		}//end if
	
		cargador_visibility('show');

		var url = '../../dispo/grupodispo/exportaExcelInternoCajasDispoGrupo';
		var params = '?inventario_id='+inventario_id+'&grupo_dispo_cab_id='+$("#frm_dispo_grupo #grupo_dispo_cab_id").val()+
					 '&color_ventas_id='+$("#frm_dispo_grupo #color_ventas_id").val()+
					 '&calidad_variedad_id='+$("#frm_dispo_grupo #calidad_variedad_id").val();
		url = url + params;
		var win = window.open(url);

		cargador_visibility('hide');

		return false;
	}//end function DispoGeneral_ExportarExcel
		