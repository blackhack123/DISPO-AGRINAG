/**
 * 
 */
var selRowId_DispoFincaGrid 	= 0;	
var selColName_DispoFincaGrid 	= 0;	
var seliRow_DispoFincaGrid 		= 0;		
var seliCol_DispoFincaGrid 		= 0;	
var valAnt_DispoFincaGrid		= null;


$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	DispoFinca_init();
	
	$("#frm_finca #inventario_id, #frm_finca #calidad_id, #frm_finca #proveedor_id, #frm_finca #color_ventas_id, #frm_finca #calidad_variedad_id, #frm_finca #nro_tallos").on('change', function(event){
//		$("#grid_dispo_finca").jqGrid('clearGridData');
		$('#grid_dispo_finca').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});


	$("#frm_finca #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_finca').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	

	$("#frm_finca #btn_excel").on('click', function(event){ 
		DispoFinca_ExportarExcel();
		return false;
	});	

	
	$("#frm_finca #btn_nuevo").on('click', function(event){ 
		DispoFinca_nueva_variedad();
		return false;
	});
	

	$("#frm_finca #btn_cero").on('click', function(event){ 
		event.preventDefault();
		DispoFinca_ActualizarCero();
		return false;
	});		
	

	$("#frm_variedad #btn_grabar").on('click', function(event){ 
		DispoFinca_GrabarDisponibilidadNueva();
		return false;
	});	

	$("#frm_finca_general_stockgrado #porcentaje").on('change', function(event){ 
		$("#frm_finca_general_stockgrado #valor").val('');
		return false;
	});

	$("#frm_finca_general_stockgrado #valor").on('change', function(event){ 
		$("#frm_finca_general_stockgrado #porcentaje").val('');
		return false;
	});	

	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Disponibilidad General---------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispo_finca").jqGrid({
		url:'../../dispo/disponibilidad/disponibilidaddata',
		postData: {
			inventario_id: 	function() {return $("#frm_finca #inventario_id").val();},
			proveedor_id: 	function() {return $("#frm_finca #proveedor_id").val();},
			clasifica: 		function() {return $("#frm_finca #calidad_id").val();},
			color_ventas_id:function() {return $("#frm_finca #color_ventas_id").val();},
			calidad_variedad_id: function() {return $("#frm_finca #calidad_variedad_id").val();},
			nro_tallos:		function() {return $("#frm_finca #nro_tallos").val();},
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['tallos_x_bunch','variedad_nombre','Id','Variedad','','Color','40','50','60','70','80','90', '100', '110','Total'],
		colModel:[
/*			{name:'seleccion',index:'', width:30,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
*/			{name:'tallos_x_bunch',index:'tallos_x_bunch', width:50, align:"center", sorttype:"int", hidden:true},
			{name:'variedad',index:'variedad_nombre', width:50, sorttype:"string", hidden:true},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string", formatter: gridDispoFinca_VariedadNombreFormatter},
			{name:'btn_foto',index:'', width:30, align:"center", formatter:GridDispoFinca_FotoFormatter,
				   cellattr: function () { return ' title=" Modificar"'; }
				},
			{name:'color_ventas_nombre',index:'color_ventas_nombre', width:120, sorttype:"string"},
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },	
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && e.shiftKey)   // tab
																{
																	if ((seliRow_DispoFincaGrid - 1) <= 0) return false;
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + jqgrid_get_columnIndexByName($("#grid_dispo_finca"), "dgru_110") + ", true);", 10);														
																}																			
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", editable:true,  formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }											
			},
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }
			},
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }
			},
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }
			},
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }
			},
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					

									 }
			},
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", editable:true, formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter,
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },
										dataEvents: [
														{ 
															type: 'keydown', 
															fn: function(e) { 
																var key = e.charCode || e.keyCode;
																if(key == 9 && !e.shiftKey)   // tab
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + jqgrid_get_columnIndexByName($("#grid_dispo_finca"), "40") + ", true);", 10);														
																}																
																if ((key == 13)||(key == 40))//enter, abajo
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " + 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}
																else if ((key == 38))//arriba
																{
																	setTimeout("jQuery('#grid_dispo_finca').editCell(" + seliRow_DispoFincaGrid + " - 1, " + seliCol_DispoFincaGrid + ", true);", 10);
																}													
															}
														} 
													]																					
									 }											
			},
			{name:'total',index:'total', width:50, align:"right", sorttype:"int", formatter:gridDispoFinca_GradosFormatter, unformat:gridDispoFinca_GradosUnFormatter}
		],
		rowNum:999999,
		cellEdit: true,
		cellsubmit: 'clientArray',
		editurl: 'clientArray',				
		multiselect: true,
		pager: '#pager_dispo_finca',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		shrinkToFit: false,
		footerrow : true,
		userDataOnFooter : true,		
		//loadComplete: grid_setAutoHeight,
		loadComplete: function (data) {
			autoHeight_JqGrid_Refresh("grid_dispo_finca");
		},		
		resizeStop: grid_setAutoHeight, 
		rownumbers: true,
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
			seliCol_DispoFincaGrid  = iCol;
			seliRow_DispoFincaGrid  = iRow;
			valAnt_DispoFincaGrid   = value;
		},			
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			//Evita se llame la funcion grabar sin que se haya modificado el valor
			var cantidad  = number_val(jQuery("#grid_dispo_finca").jqGrid('getCell',rowid, iCol), 2);
			if (number_val(cantidad)==number_val(valAnt_DispoFincaGrid))
			{
				return false;
			}//end if

			var col_variedad_id 	= jqgrid_get_columnIndexByName($("#grid_dispo_finca"), "variedad_id");
			var col_tallos_x_bunch	= jqgrid_get_columnIndexByName($("#grid_dispo_finca"), "tallos_x_bunch");

			var inventario_id	= $("#frm_finca #inventario_id").val();
			var proveedor_id  	= $("#frm_finca #proveedor_id").val();			
			var clasifica_fox 	= $("#frm_finca #calidad_id").val();
			var variedad_id	  	= jQuery("#grid_dispo_finca").jqGrid('getCell',rowid, col_variedad_id);
			var grado_id	  	= $("#frm_finca #grado_id").val();
			var tallos_x_bunch	= jQuery("#grid_dispo_finca").jqGrid('getCell',rowid, col_tallos_x_bunch);
			var stock			= cantidad;
			var grado_id		= jqgrid_get_columnNameByIndex($("#grid_dispo_finca"), iCol);

			DispoFinca_GrabarStock(inventario_id, proveedor_id, clasifica_fox, variedad_id, grado_id, tallos_x_bunch, stock);
		},
	});
	
	jQuery("#grid_dispo_finca").jqGrid('navGrid','#pager_dispo_finca',{edit:false,add:false,del:false});	



	function gridDispoFinca_VariedadNombreFormatter(cellvalue, options, rowObject){
		if (rowObject.tallos_x_bunch==25)
		{
			new_format_value = rowObject.variedad;
		}else{
			new_format_value = rowObject.variedad + ' <em><b style="color:orange; font-style: italic;">('+rowObject.tallos_x_bunch+')</b></em>';
		}//end if
		return new_format_value;
	}//end function gridDispoFinca_VariedadNombreFormatter



	function GridDispoFinca_FotoFormatter(cellvalue, options, rowObject){
		var id = rowObject.id;	
		var new_format_value = '';
		if (rowObject.url_ficha === undefined || rowObject.url_ficha === null || rowObject.url_ficha=='')
		{
			new_format_value = '';
		}else{
			new_format_value = '<a href="javascript:void(0)" onclick="window.open(\''+rowObject.url_ficha+'\',this.target,\'scrollbars=yes,resizable=yes,height=600,width=1000,left=100,top=100\')"><i class="glyphicon glyphicon-camera" style="color:green"></i></a>'; 
		}//end if
		
		return new_format_value;
	}//end function ListadoCliente_FormatterEdit
		

	

	function gridDispoFinca_GradosFormatter(cellvalue, options, rowObject){
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
		
	function gridDispoFinca_GradosUnFormatter(cellvalue, options, cell){
		return number_val($('span', cell).html());
	}		

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/



	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Dispobilidad General-----------*/
	/*---------------------------------------------------------------*/		
/*	var dataGridDispoVariedad = [{
					'40': 0,
					'50': 0,
					'60': 0,
					'70': 0,
					'80': 0,
					'90': 0,
					'100': 0,
					'110': 0
				}];

	jQuery("#grid_dispofinca_variedad").jqGrid({
        data: dataGridDispoVariedad,       
        datatype: "local",
		loadonce: true,			
		colNames:['40','50','60','70','80','90', '100', '110'],
		colModel:[
			{name:'40',index:'40', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'50',index:'50', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'60',index:'60', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'70',index:'70', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'80',index:'80', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'90',index:'90', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'100',index:'100', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter},	
			{name:'110',index:'110', width:50, align:"center", formatter: gridDispoVariedad_GradosFormatter}	
		],
		rowNum:999999,
		pager: '#pager_dispofinca_variedad',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		shrinkToFit: false,
		jsonReader: {
			repeatitems : false,
		},		
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});
		

	function gridDispoVariedad_GradosFormatter(cellvalue, options, rowObject){
		new_format_value = '<a href="javascript:void(0)" onclick="DispoFinca_NuevaVariedad(\''+options.rowId+'\',\''+options.colModel.name+'\')">'+cellvalue+ '</a>';		
		return new_format_value;
	}

	jQuery("#grid_dispofinca_variedad").jqGrid('navGrid','#pager_dispofinca_variedad',{edit:false,add:false,del:false});
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura el JQGRID de COLOR de la DISPO X GRUPO-------*/
	/*---------------------------------------------------------------*/		
/*	jQuery("#grid_DispoFinca_color").jqGrid({
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
		pager: '#pager_DispoFinca_color',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		width: 280,
		shrinkToFit: true,
		rownumbers: true,
		multiselect: true,
		jsonReader: {
			repeatitems : false,
		},
		loadComplete: function (data) {
			//autoHeight_JqGrid_Refresh("grid_dispo_finca");
			//autoWidthContainer_JqGrid("grid_dispogrupo_color");
		},
		loadError: function (jqXHR, textStatus, errorThrown) {
			//message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});							
	jQuery("#grid_DispoFinca_color").jqGrid('navGrid','#pager_DispoFinca_color',{edit:false,add:false,del:false});
*/	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	
	/*---------------------------------------------------------------*/
	/*---Se configura el JQGRID de CATEGORIAS de la DISPO X GRUPO----*/
	/*---------------------------------------------------------------*/		
/*	jQuery("#grid_DispoFinca_categoria").jqGrid({
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
		pager: '#pager_DispoFinca_categoria',
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
		multiselect: true,
		jsonReader: {
			repeatitems : false,
		},
		loadComplete: function (data) {
			//autoHeight_JqGrid_Refresh("grid_dispo_finca");
			//autoWidthContainer_JqGrid("grid_dispogrupo_color");
		},
		loadError: function (jqXHR, textStatus, errorThrown) {
//			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});
	jQuery("#grid_DispoFinca_categoria").jqGrid('navGrid','#pager_DispoFinca_categoria',{edit:false,add:false,del:false});
*/	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/	
	
	
});
	
	

	function DispoFinca_init()
	{
		//Deshabilita los botones del formulario
		$("#frm_finca button").prop('disabled', true);

		var data = 	{
						opcion: 'panel-control-disponibilidad',
						inventario_id: 				'USA',
						inventario_1er_elemento:	'',
						calidad_1er_elemento:		'',
						proveedor_1er_elemento:		'',
						color_ventas_1er_elemento:  '&lt;COLORES&gt;',
						calidad_variedad_1er_elemento:  '&lt;CATEGORIAS&gt;',
						nro_tallos_1er_elemento:		'&lt;TALLOS&gt;',
						buscar_proveedor_id:		proveedor_seleccionado
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response){		
								$("body #frm_finca #inventario_id").html(response.inventario_opciones);
								$("body #frm_finca #calidad_id").html(response.calidad_opciones);
								$("body #frm_finca #proveedor_id").html(response.proveedor_opciones);
								$("body #frm_finca #color_ventas_id").html(response.color_ventas_opciones);
								$("body #frm_finca #calidad_variedad_id").html(response.calidad_variedad_opciones);
								$("body #frm_finca #nro_tallos").html(response.nro_tallos_opciones);								

								//Habilita los botones del formulario
								$("#frm_finca button").prop('disabled', false);								
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function DispoFinca_init


	function DispoFinca_GrabarStock(inventario_id, proveedor_id, clasifica_fox, variedad_id, grado_id, tallos_x_bunch, stock)
	{
		var stock_agr = 0;
		var stock_htc = 0;
		var stock_lma = 0;
		
		switch(proveedor_id)
		{
			case 'AGR':
				stock_agr = stock;
				break;
				
			case 'LMA':
				stock_lma = stock;
				break;
				
			case 'HTC':
				stock_htc = stock;
				break;
		}//end switch
		
		var data = 	{	inventario_id:		inventario_id, 
						proveedor_id:		proveedor_id, 
						clasifica_fox:		clasifica_fox,
						variedad_id:		variedad_id,
						grado_id:			grado_id,
						tallos_x_bunch:		tallos_x_bunch,
						stock_agr:			stock_agr,
						stock_lma:			stock_lma,
						stock_htc:			stock_htc
					}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/grabarstockproveedor',
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
	}//end function DispoFinca_GrabarStock



	function disponibilidadfinca_nueva_variedad()
	{
		$("#frm_variedad #fincas_stock").hide();

		titulo = $("#frm_finca #inventario_id option:selected").text() + ' - ' + $("#frm_finca #calidad_id option:selected").text();
		$("#dialog_dispo_variedad_titulo").html(titulo);
		$('#dialog_dispo_variedad').modal('show');
		DispoFinca_NuevaVariedad_CargarCombo();
	}//end function disponibilidadfinca_nueva_variedad
	
	
	
	function DispoFinca_NuevaVariedad_CargarCombo()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{
						texto_primer_elemento: '&lt;SELECCIONE VARIEDAD&gt;',
						inventario_id: 	$("#frm_finca #inventario_id").val(),
						calidad_id: 	$("#frm_finca #calidad_id").val(),	
						variedad_id:	null
					}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/getcombovariedadnoexiste',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									$("#frm_variedad #variedad_id").html(response.variedad_opciones);
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function DispoFinca_NuevaVariedad_CargarCombo
	


	function DispoFinca_NuevaVariedad(rowId, grado_id)
	{
		selRowId_DispoFincaGrid = rowId;
		$("#grado_seleccionado_id").val(grado_id);

		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{						
						inventario_id: 	$("#frm_finca #inventario_id").val(),
						calidad_id: 	$("#frm_finca #calidad_id").val(),	
						variedad_id:	$("#frm_variedad #variedad_id").val(),
						grado_id:		grado_id,
					}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/consultarPorInventarioPorCalidadPorVariedadPorGrado',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									$("#frm_variedad #fincas_stock").show();
								
									if (response.row === 'undefined' || response.row === null)
									{
										$("#frm_variedad #agr_cantidad_bunch").val("0");
										$("#frm_variedad #htc_cantidad_bunch").val("0");
										$("#frm_variedad #lma_cantidad_bunch").val("0");
									}else{
										$("#frm_variedad #agr_cantidad_bunch").val(response.row.AGR.tot_bunch_disponible);
										$("#frm_variedad #htc_cantidad_bunch").val(response.row.HTC.tot_bunch_disponible);
										$("#frm_variedad #lma_cantidad_bunch").val(response.row.LMA.tot_bunch_disponible);
									}//end if
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;					
	}//end function DispoFinca_NuevaVariedad
	
	

	function DispoFinca_GrabarDisponibilidadNueva()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{						
						inventario_id: 	$("#frm_finca #inventario_id").val(),
						calidad_id: 	$("#frm_finca #calidad_id").val(),	
						variedad_id:	$("#frm_variedad #variedad_id").val(),
						grado_id:		$("#frm_variedad #grado_seleccionado_id").val(),
						agr_cantidad_bunch: $("#frm_variedad #agr_cantidad_bunch").val(),
						htc_cantidad_bunch: $("#frm_variedad #htc_cantidad_bunch").val(),
						lma_cantidad_bunch: $("#frm_variedad #lma_cantidad_bunch").val(),
					}
		data = JSON.stringify(data);

		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/grabarstocknuevo',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
								if (response.validacion_code == 'OK')
								{		
									cargador_visibility('hide');
									
									//Se actualiza el grid
									stock_total = 	number_val($("#frm_variedad #agr_cantidad_bunch").val()) +
									 		 		number_val($("#frm_variedad #htc_cantidad_bunch").val()) +
											 		number_val($("#frm_variedad #lma_cantidad_bunch").val())
									icol_grado = jqgrid_get_columnIndexByName($("#grid_dispofinca_variedad"), $("#frm_variedad #grado_seleccionado_id").val());									

									$("#grid_dispofinca_variedad").jqGrid('setCell', selRowId_DispoFincaGrid , icol_grado, stock_total);
									
									
									
								}else{
									message_error('ERROR', response);
								}//end if
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function DispoFinca_GrabarDisponibilidadNueva
	
	

	function DispoFinca_ExportarExcel()
	{
		cargador_visibility('show');

		var url = '../../dispo/disponibilidad/exportarexcel';
		var params = '?inventario_id='+$("#frm_finca #inventario_id").val()+
					 '&proveedor_id='+$("#frm_finca #proveedor_id").val()+
				 	 '&clasifica='+$("#frm_finca #calidad_id").val()+
					 '&color_ventas_id='+$("#frm_finca #color_ventas_id").val()+
					 '&calidad_variedad_id='+$("#frm_finca #calidad_variedad_id").val()+
					 '&nro_tallos='+$("#frm_finca #nro_tallos").val();
		url = url + params;
		var win = window.open(url);
		
		cargador_visibility('hide');
	}//end function DispoFinca_ExportarExcel
	
	
	
	function DispoFinca_ActualizarCero()
	{
		var grid 				= $("#grid_dispo_finca");
        var rowKey 	= grid.getGridParam("selrow");

        if (!rowKey)
		{
			alert("SELECCIONE UN REGISTRO");
			return false;
		}//end if
		
		
		swal({  title: 'CONFIGURAR EN CERO',   
			text: "Esta seguro de poner a CERO las variedades seleccionadas?",
			//text: "<b style='color:blue'>Desea continuar utilizando la misma marcacion? <br> Para seguir realizando mas pedidos</b>",  
			html:true,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Si",
			cancelButtonText: "No",
			closeOnConfirm: true,
			closeOnCancel: true,
			/*timer: 2000*/
		},
		function(isConfirm){													
			if (isConfirm) {
						var grid 				= $("#grid_dispo_finca");
						var col_variedad_id	 	= jqgrid_get_columnIndexByName(grid, "variedad_id");
						var col_tallos_x_bunch 	= jqgrid_get_columnIndexByName(grid, "tallos_x_bunch");

						var selectedIDs = grid.getGridParam("selarrrow");
						var variedad_id  = null;
						
						var arr_data 	= new Array();
						for (var i = 0; i < selectedIDs.length; i++) {
							variedad_id 	= grid.jqGrid('getCell',selectedIDs[i], col_variedad_id);
							tallos_x_bunch 	= grid.jqGrid('getCell',selectedIDs[i], col_tallos_x_bunch);
							
							var element				= {};
							element.variedad_id		= variedad_id;
							element.tallos_x_bunch  = tallos_x_bunch;
							arr_data.push(element);
						}//end for
						
				
						var data = 	{
										inventario_id: 	$("#frm_finca #inventario_id").val(),
										proveedor_id: 	$("#frm_finca #proveedor_id").val(),
										clasifica: 		$("#frm_finca #calidad_id").val(),
										grid_data: 		arr_data,
									}
						//console.log(data);
						data = JSON.stringify(data);		
						
						//$("frm_finca_general_stockgrado #btn_grabar").button('loading')
						
						var parameters = {	'type': 'POST',//'POST',
											'contentType': 'application/json',
											'url':'../../dispo/disponibilidad/actualizarcerostock',
											'control_process':false,
											'show_cargando':true,
											'async':true, 
											'finish':function(response){
													//$("frm_finca_general_stockgrado #btn_grabar").button('reset');
													cargador_visibility('hide');
													$('#grid_dispo_finca').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
											}							
										 }
						response = ajax_call(parameters, data);		
			}
		});
		return false;
	}//end function DispoFinca_ActualizarCero