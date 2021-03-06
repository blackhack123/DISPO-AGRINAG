/**
 * 
 */
var selRowId_DispoGeneralGrid 		= 0;	
var selColName_DispoGeneralGrid 	= 0;	
var primera_vez_DispoGeneralGrid 	= true;

var selRowId_DispoVariedadGrid		= null;

$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	disponibilidad_init();
	
	$("#frm_dispo #inventario_id, #frm_dispo #calidad_id, #frm_dispo #proveedor_id, #frm_dispo #color_ventas_id, #frm_dispo #calidad_variedad_id, #frm_dispo #nro_tallos").on('change', function(event){
//		$("#grid_dispo_generaxcell").jqGrid('clearGridData');
		$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});


	$("#frm_dispo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	

	$("#frm_dispo #btn_excel").on('click', function(event){ 
		DispoGeneral_ExportarExcel();
		return false;
	});	

	
	$("#frm_dispo #btn_nuevo").on('click', function(event){ 
		disponibilidad_nueva_variedad();
		return false;
	});
	
	$("#frm_dispo #btn_mover").on('click', function(event){ 
		//disponibilidad_mover();
		DispoGeneral_OpenDialog_MoverStock();
		return false;
	});	
	
	$("#frm_dispo #btn_cero").on('click', function(event){ 		
		DispoGeneral_ActualizarCero();
		return false;
	});		
	
	$("#frm_dispo_proveedor #btn_grabar").on('click', function(event){ 
		grabar_dispo_proveedor();
		return false;
	});	

	$("#frm_variedad #btn_grabar").on('click', function(event){ 
		grabar_disponibilidad_nueva();
		return false;
	});	

	$("#frm_dispo_general_stockgrado #porcentaje").on('change', function(event){ 
		$("#frm_dispo_general_stockgrado #valor").val('');
		return false;
	});

	$("#frm_dispo_general_stockgrado #valor").on('change', function(event){ 
		$("#frm_dispo_general_stockgrado #porcentaje").val('');
		return false;
	});	

	$( "body" ).on( "click", ".DispoGeneralCambiarValoresPorGrado", function() {
		var grado_id = $(this).data('grado');
		DispoGeneral_OpenDialog_GradosStock(grado_id);
	});	
	
	
	$("#frm_dispo_general_stockgrado #btn_grabar").on('click', function(event){ 
		DispoGeneral_GrabarStockPorGrado();
	});	
	
	
	$("#frm_dispo_general_moverstock #btn_mover").on('click', function(event){ 
		//disponibilidad_mover();
		DispoGeneral_MoverStock();
		return false;
	});		
	/*---------------------------------------------------------------*/	
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Disponibilidad General---------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispo_general").jqGrid({
		url:'../../dispo/disponibilidad/disponibilidaddata',
		postData: {
			inventario_id: 	function() {return $("#frm_dispo #inventario_id").val();},
			proveedor_id: 	function() {return $("#frm_dispo #proveedor_id").val();},
			clasifica: 		function() {return $("#frm_dispo #calidad_id").val();},
			color_ventas_id:function() {return $("#frm_dispo #color_ventas_id").val();},
			calidad_variedad_id: function() {return $("#frm_dispo #calidad_variedad_id").val();},
			nro_tallos:		function() {return $("#frm_dispo #nro_tallos").val();},
			omitir_registro_vacio: true
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
			{name:'variedad',index:'variedad', width:170, sorttype:"string", formatter: gridDispoGeneral_VariedadNombreFormatter},
			{name:'btn_foto',index:'', width:30, align:"center", formatter:GridDispoGeneral_FotoFormatter,
				   cellattr: function () { return ' title=" Modificar"'; }
				},
			{name:'color_ventas_nombre',index:'color_ventas_nombre', width:120, sorttype:"string"},
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},
			{name:'total',index:'total', width:50, align:"right", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2}
		],
		rowNum:999999,
		multiselect: true,
		pager: '#pager_dispo_general',
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
			autoHeight_JqGrid_Refresh("grid_dispo_general");
			primera_vez_DispoGeneralGrid = false;
		},		
		resizeStop: grid_setAutoHeight, 
		rownumbers: true,
		jsonReader: {
			repeatitems : false,
		},		
		beforeProcessing: function(data, status, xhr){
			primera_vez_DispoGeneralGrid = true;
		},
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
	
	jQuery("#grid_dispo_general").jqGrid('setGroupHeaders', {
	  useColSpanStyle: true, 
	  groupHeaders:[
		{startColumnName: '40', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="40"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '50', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="50"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '60', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="60"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '70', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="70"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '80', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="80"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '90', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="90"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '100', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="100"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
		{startColumnName: '110', numberOfColumns: 1, titleText: '<a href="#" class="DispoGeneralCambiarValoresPorGrado" data-grado="110"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:orange"></span></a>'},
	  ]	
	});		
/*	$("#grid_dispo_general").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});*/



	function gridDispoGeneral_VariedadNombreFormatter(cellvalue, options, rowObject){
		if (rowObject.tallos_x_bunch==25)
		{
			new_format_value = rowObject.variedad;
		}else{
			new_format_value = rowObject.variedad + ' <em><b style="color:orange; font-style: italic;">('+rowObject.tallos_x_bunch+')</b></em>';
		}//end if
		return new_format_value;
	}//end function gridDispoGeneral_VariedadNombreFormatter


	function GridDispoGeneral_FotoFormatter(cellvalue, options, rowObject){
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



	function gridDispoGeneral_GradosFormatter(cellvalue, options, rowObject)
	{
		if (primera_vez_DispoGeneralGrid == true)
		{
			col_grado_name 	= options.colModel.name;
			stock			= number_val(cellvalue);	
			variedad_id		= rowObject.variedad_id;
			variedad		= rowObject.variedad; //Nombre
			tallos_x_bunch	= rowObject.tallos_x_bunch;			
		}else{
			pos_col_grado 			= options.pos;
			pos_col_variedad_id 	= 4;
			pos_col_variedad_nombre	= 3;
			pos_col_tallos_x_bunch 	= 2;	
						
			stock 			= number_val(rowObject[pos_col_grado]);	
			variedad_id		= rowObject[pos_col_variedad_id];
			variedad		= rowObject[pos_col_variedad_nombre]; //Nombre
			tallos_x_bunch	= number_val(rowObject[pos_col_tallos_x_bunch]);
			//console.log('rowObject:',rowObject);
			//console.log('variedad_id:',variedad_id,'*variedad:',variedad,'*options.colModel.name:',options.colModel.name,'*tallos_x_bunch:',tallos_x_bunch,'*cellvalue:',cellvalue);
		}//end if		
		var color = "Black"
		if (cellvalue==0)
		{
			color = "LightGray";
		}//end if
		
		new_format_value = '<a href="javascript:void(0)" data-toggle="modal" data-target="#dialog_dispo_proveedores" onclick="open_dialog_dispo(\''+options.rowId+'\',\''+options.colModel.name+'\',\''+variedad_id+'\',\''+variedad+'\',\''+options.colModel.name+'\',\''+tallos_x_bunch+'\')" style="color:'+color+'">'+cellvalue+ '</a>';		
		return new_format_value;
	}//end function gridDispoGeneral_GradosFormatter
	
	
	
	function gridDispoGeneral_GradosFormatter2(cellvalue, options, rowObject){
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
	
	
	function gridDispoGeneral_GradosUnFormatter2(cellvalue, options, cell){
		return number_val($('span', cell).html());
	}		
	
		
		
	jQuery("#grid_dispo_general").jqGrid('navGrid','#pager_dispo_general',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/



	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Dispobilidad General-----------*/
	/*---------------------------------------------------------------*/		
	var dataGridDispoVariedad = [{
					'40': 0,
					'50': 0,
					'60': 0,
					'70': 0,
					'80': 0,
					'90': 0,
					'100': 0,
					'110': 0
				}];

	jQuery("#grid_dispo_variedad").jqGrid({
        data: dataGridDispoVariedad,       
        datatype: "local",
		//datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['40','50','60','70','80','90', '100', '110'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
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
		pager: '#pager_dispo_variedad',
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
		new_format_value = '<a href="javascript:void(0)" onclick="disponibilidad_nuevo_variedad_carga_finca(\''+options.rowId+'\',\''+options.colModel.name+'\')">'+cellvalue+ '</a>';		
		return new_format_value;
	}

	jQuery("#grid_dispo_variedad").jqGrid('navGrid','#pager_dispo_variedad',{edit:false,add:false,del:false});
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	/*---------------------------------------------------------------*/
	/*-----Se configura el JQGRID de COLOR de la DISPO X GRUPO-------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispogeneral_color").jqGrid({
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
		pager: '#pager_dispogeneral_color',
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
			//message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});							
	jQuery("#grid_dispogeneral_color").jqGrid('navGrid','#pager_dispogeneral_color',{edit:false,add:false,del:false});
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	
	
	/*---------------------------------------------------------------*/
	/*---Se configura el JQGRID de CATEGORIAS de la DISPO X GRUPO----*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispogeneral_categoria").jqGrid({
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
		pager: '#pager_dispogeneral_categoria',
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
//			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		},
	});
	jQuery("#grid_dispogeneral_categoria").jqGrid('navGrid','#pager_dispogeneral_categoria',{edit:false,add:false,del:false});
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/	
	
	
	
	jQuery("#grid_dispogeneral_moverstock").jqGrid({
		url:'../../dispo/disponibilidad/disponibilidaddata',
		postData: {
			inventario_id: 	function() {return $("#frm_dispo_general_moverstock #inventario_id").val();},
			clasifica: 		function() {return $("#frm_dispo_general_moverstock #calidad_id").val();},
			proveedor_id: 	0, /*function() {return $("#frm_dispo_general_moverstock #proveedor_id").val();},*/			
			color_ventas_id:function() {return $("#frm_dispo_general_moverstock #color_ventas_id").val();},
			calidad_variedad_id: function() {return $("#frm_dispo_general_moverstock #calidad_variedad_id").val();},
			nro_tallos:		function() {return $("#frm_dispo_general_moverstock #nro_tallos").val();},
			omitir_registro_vacio: true
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['tallos_x_bunch','variedad_nombre','Id','Variedad','Color','40','50','60','70','80','90', '100', '110','Total'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'tallos_x_bunch',index:'tallos_x_bunch', width:50, align:"center", sorttype:"int", hidden:true},
			{name:'variedad',index:'variedad_nombre', width:50, sorttype:"string", hidden:true},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:150, sorttype:"string", formatter: gridDispoGeneral_VariedadNombreFormatter},
			{name:'color_ventas_nombre',index:'color_ventas_nombre', width:90, sorttype:"string"},
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},	
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},	
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},	
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},	
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},	
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},	
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2},
			{name:'total',index:'total', width:50, align:"right", sorttype:"int", formatter:gridDispoGeneral_GradosFormatter2, unformat:gridDispoGeneral_GradosUnFormatter2}
		],
		rowNum:999999,
		pager: '#pager_dispogeneral_moverstock',
		multiselect: true,
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
			autoHeight_JqGrid_Refresh("grid_dispogeneral_moverstock");
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
		}
	});
	
	jQuery("#grid_dispogeneral_moverstock").jqGrid('setGroupHeaders', {
	  	useColSpanStyle: true, 
	  	groupHeaders:[
			{startColumnName: '40', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_40">'},
			{startColumnName: '50', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_50">'},
			{startColumnName: '60', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_60">'},
			{startColumnName: '70', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_70">'},
			{startColumnName: '80', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_80">'},
			{startColumnName: '90', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_90">'},
			{startColumnName: '100', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_100">'},
			{startColumnName: '110', numberOfColumns: 1, titleText: '<input type="checkbox" id="chk_mover_select_110">'},
		  ]	
	});		
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/	

});
	

	

	function disponibilidad_init()
	{
		//Deshabilita los botones del formulario
		$("#frm_dispo button").prop('disabled', true);

		var data = 	{
						opcion: 'panel-control-disponibilidad',
						inventario_id: 				'USA',
						inventario_1er_elemento:	'',
						calidad_1er_elemento:		'',
						proveedor_1er_elemento:		'&lt;FINCAS&gt;',
						color_ventas_1er_elemento:  '&lt;COLORES&gt;',
						calidad_variedad_1er_elemento:  '&lt;CATEGORIAS&gt;',
						nro_tallos_1er_elemento:		'&lt;TALLOS&gt;'
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/initcontrols',
							'show_cargando':false,
							'async':true,
							'finish':function(response){		
								$("body #frm_dispo #inventario_id").html(response.inventario_opciones);
								$("body #frm_dispo #calidad_id").html(response.calidad_opciones);
								$("body #frm_dispo #proveedor_id").html(response.proveedor_opciones);
								$("body #frm_dispo #color_ventas_id").html(response.color_ventas_opciones);
								$("body #frm_dispo #calidad_variedad_id").html(response.calidad_variedad_opciones);
								$("body #frm_dispo #nro_tallos").html(response.nro_tallos_opciones);								

								//Habilita los botones del formulario
								$("#frm_dispo button").prop('disabled', false);

								//Se incializa los controles en mover la dispo
								$("body #frm_dispo_general_moverstock #calidad_destino_id").html(response.calidad_opciones);
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function disponibilidad_init

	
	function open_dialog_dispo(rowId, colName, variedad_id, variedad_nombre, grado_id, tallos_x_bunch)
	{
		selRowId_DispoGeneralGrid 	= rowId;
		selColName_DispoGeneralGrid = colName;
			
			
		$("#dialog_dispo_proveedores_titulo").html(variedad_nombre+' - Grado:'+grado_id+' Tallos:'+tallos_x_bunch);

		$("body #frm_dispo_proveedor #stock_agr").val("");
		$("body #frm_dispo_proveedor #stock_htc").val("");
		$("body #frm_dispo_proveedor #stock_lma").val("");
		
		var proveedor_id = $("#frm_dispo #proveedor_id").val();
		
		if (proveedor_id==""){
			$("body #frm_dispo_proveedor #stock_agr").prop("readonly",false);
			$("body #frm_dispo_proveedor #stock_htc").prop("readonly",false);
			$("body #frm_dispo_proveedor #stock_lma").prop("readonly",false);						
		}else{
			$("body #frm_dispo_proveedor #stock_agr").prop("readonly",true);
			$("body #frm_dispo_proveedor #stock_htc").prop("readonly",true);
			$("body #frm_dispo_proveedor #stock_lma").prop("readonly",true);
			
			switch (proveedor_id)
			{
				case 'AGR':
					$("body #frm_dispo_proveedor #stock_agr").prop("readonly",false);
					break;
					
				case 'LMA':
					$("body #frm_dispo_proveedor #stock_lma").prop("readonly",false);
					break;
					
				case 'HTC':
					$("body #frm_dispo_proveedor #stock_htc").prop("readonly",false);				
					break;
			}//end switch
		}//end if
				
		var data = 	{
						inventario_id:	$("#frm_dispo #inventario_id").val(),
						clasifica_fox:	$("#frm_dispo #calidad_id").val(),
						proveedor_id:	$("#frm_dispo #proveedor_id").val(),
						variedad_id:	variedad_id,
						grado_id:		grado_id,
						tallos_x_bunch:	tallos_x_bunch
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo',
							'show_cargando':false,
							'async':true,
							'finish':function(response){
								//Se asignan las variables al dialogo
								$("#frm_dispo_proveedor #inventario_id").val($("#frm_dispo #inventario_id").val());
								$("#frm_dispo_proveedor #calidad_id").val($("#frm_dispo #calidad_id").val());
								$("#frm_dispo_proveedor #proveedor_id").val($("#frm_dispo #proveedor_id").val());
								$("#frm_dispo_proveedor #grado_id").val(grado_id);
								$("#frm_dispo_proveedor #variedad_id").val(variedad_id);
								$("#frm_dispo_proveedor #tallos_x_bunch").val(tallos_x_bunch);

								//Se asigna los valores del stock
								if(response.hasOwnProperty("row")){
									if(response.row.hasOwnProperty("AGR")){
										$("#frm_dispo_proveedor #stock_agr").val(response.row.AGR.tot_bunch_disponible);
									}//end if
									if(response.row.hasOwnProperty("HTC")){								
										$("#frm_dispo_proveedor #stock_htc").val(response.row.HTC.tot_bunch_disponible);
									}//end if
									if(response.row.hasOwnProperty("LMA")){
										$("#frm_dispo_proveedor #stock_lma").val(response.row.LMA.tot_bunch_disponible);
									}//end if									
								}//end if
							 }							
						 }
		response = ajax_call(parameters, data);			
		
	}//end if
	

	function grabar_dispo_proveedor() //moronitor
	{
		var stock_agr = $("#frm_dispo_proveedor #stock_agr").val();
		var stock_htc = $("#frm_dispo_proveedor #stock_htc").val();
		var stock_lma = $("#frm_dispo_proveedor #stock_lma").val();
		
		if ((stock_agr=='')&&(stock_htc=='')&&(stock_lma=='')){
			alert('Debe de Ingresar por lo menos un valor');
			$("#frm_dispo_proveedor #stock_agr").focus();
			return false;
		}//end if
		
		var data = 	{
						inventario_id:	$("#frm_dispo_proveedor #inventario_id").val(),
						clasifica_fox:	$("#frm_dispo_proveedor #calidad_id").val(),
						proveedor_id:	$("#frm_dispo_proveedor #proveedor_id").val(),
						variedad_id:	$("#frm_dispo_proveedor #variedad_id").val(),
						grado_id:		$("#frm_dispo_proveedor #grado_id").val(),
						tallos_x_bunch: $("#frm_dispo_proveedor #tallos_x_bunch").val(),
						stock_agr:		stock_agr,
						stock_htc:		stock_htc,
						stock_lma:		stock_lma
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/grabarstockproveedor',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
								if (response.validacion_code == 'OK')
								{
									//Actualiza la celda del GRID con el nuevo Stock
									//esto evita recargar el grid por completo.
									stock_total = number_val(stock_agr) + number_val(stock_htc) + number_val(stock_lma);
									icol_grado = jqgrid_get_columnIndexByName($("#grid_dispo_general"), selColName_DispoGeneralGrid);	

									$("#grid_dispo_general").jqGrid('setCell', selRowId_DispoGeneralGrid , icol_grado, stock_total);

									
									//mostrar_registro(response)
									$('#dialog_dispo_proveedores').modal('hide');
									cargador_visibility('hide');
									/*swal({  title: "Informacion grabada con exito!!",   
										//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
										//html:true,
										type: "success",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "OK",
										cancelButtonText: "",
										closeOnConfirm: false,
										closeOnCancel: false,
										timer: 1000
									});*/
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function grabar_dispo_proveedor


	function disponibilidad_nueva_variedad()
	{
		$("#frm_variedad #fincas_stock").hide();

		titulo = $("#frm_dispo #inventario_id option:selected").text() + ' - ' + $("#frm_dispo #calidad_id option:selected").text();
		$("#dialog_dispo_variedad_titulo").html(titulo);
		$('#dialog_dispo_variedad').modal('show');
		disponibilidad_nuevo_variedad_cargar_combo();
	}//end function disponibilidad_nueva_variedad
	
	
	
	function disponibilidad_nuevo_variedad_cargar_combo()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{
						texto_primer_elemento: '&lt;SELECCIONE VARIEDAD&gt;',
						inventario_id: 	$("#frm_dispo #inventario_id").val(),
						calidad_id: 	$("#frm_dispo #calidad_id").val(),	
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
	}//end function disponibilidad_nuevo_variedad_cargar_combo
	


	function disponibilidad_nuevo_variedad_carga_finca(rowId, grado_id)
	{
		selRowId_DispoVariedadGrid = rowId;
		$("#grado_seleccionado_id").val(grado_id);

		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{						
						inventario_id: 	$("#frm_dispo #inventario_id").val(),
						calidad_id: 	$("#frm_dispo #calidad_id").val(),	
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
	}//end function disponibilidad_nuevo_variedad_carga_finca
	
	

	function grabar_disponibilidad_nueva()
	{
		//Se llama mediante AJAX para adicionar al carrito de compras
		var data = 	{						
						inventario_id: 	$("#frm_dispo #inventario_id").val(),
						calidad_id: 	$("#frm_dispo #calidad_id").val(),	
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
									icol_grado = jqgrid_get_columnIndexByName($("#grid_dispo_variedad"), $("#frm_variedad #grado_seleccionado_id").val());									

									$("#grid_dispo_variedad").jqGrid('setCell', selRowId_DispoVariedadGrid , icol_grado, stock_total);
									
									
									
								}else{
									message_error('ERROR', response);
								}//end if
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function grabar_disponibilidad_nueva
	
	
	
	function DispoGeneral_OpenDialog_GradosStock(grado_id)
	{
		var inventario_id		= $("#frm_dispo #inventario_id").val();
		var calidad_id			= $("#frm_dispo #calidad_id").val();
		var proveedor_id		= $("#frm_dispo #proveedor_id").val();
		var color_ventas_id		= $("#frm_dispo #color_ventas_id").val();
		var calidad_variedad_id	= $("#frm_dispo #calidad_variedad_id").val();				

		if (inventario_id=='')
		{
			alert('Debe de Seleccionar un Inventario');
			$("#frm_dispo #inventario_id").focus();
			return false;
		}//end if
		
		if (proveedor_id=='')
		{
			alert('Debe de Seleccionar una Finca');
			$("#frm_dispo #proveedor_id").focus();
			return false;
		}//end if		

		//Almacena las variables que son parameros para la modificacion masiva
		$("#frm_dispo_general_stockgrado #inventario_id").val(inventario_id);
		$("#frm_dispo_general_stockgrado #calidad_id").val(calidad_id);
		$("#frm_dispo_general_stockgrado #proveedor_id").val(proveedor_id);				
		$("#frm_dispo_general_stockgrado #grado_id").val(grado_id);

		//---------------------------------------------------------
		//Desmarca todos los colores
		$('#grid_dispogeneral_color').jqGrid('resetSelection');
		
		//Marca el color que paso como parametros
		var ids = $('#grid_dispogeneral_color').jqGrid('getDataIDs');		
		var len = ids.length;
		for (var i=0; i < len; i++) {
			if ((color_ventas_id == ids[i])||(color_ventas_id == ''))
			{
				$('#grid_dispogeneral_color').jqGrid('setSelection', ids[i]);
			}//end if
		}//end for
		
		//-----------------------------------------------------------
		//Desmarca todos los categorias
		$('#grid_dispogeneral_categoria').jqGrid('resetSelection');
		
		//Marca las categorias que paso como parametros
		var ids = $('#grid_dispogeneral_categoria').jqGrid('getDataIDs');		
		var len = ids.length;
		for (var i=0; i < len; i++) {
			if ((calidad_variedad_id == ids[i])||(calidad_variedad_id == ''))
			{
				$('#grid_dispogeneral_categoria').jqGrid('setSelection', ids[i]);
			}//end if
		}//end for				
		//-------------------------------------------------------------
		
	
		//Setea el titulo 
		//Abre el dialogo
		var proveedor_nombre = $("#frm_dispo #proveedor_id option:selected").text();
		proveedor_nombre = proveedor_nombre.replace('<','');
		proveedor_nombre = proveedor_nombre.replace('>','');		
		//if (proveedor_id!='') {proveedor_nombre = $("#frm_dispo #proveedor_id option:selected").text();}
		var titulo = "<b><em style='color:blue'>INVENTARIO:</em></b> "+$("#frm_dispo #inventario_id option:selected").text()+" - <b><em style='color:blue'>FINCA:</em></b> "+proveedor_nombre+" - <b><em style='color:blue'>GRADO:</em></b> "+grado_id;
		$("#dialog_dispo_general_stockgrado_titulo").html(titulo);
		$('#dialog_dispo_general_gradostock').modal('show');
	}//end function DispoGeneral_OpenDialog_GradosStock		
	
	
	function DispoGeneral_GrabarStockPorGrado()
	{
		var inventario_id			= $("#frm_dispo_general_stockgrado #inventario_id").val();
		var clasifica				= $("#frm_dispo_general_stockgrado #calidad_id").val();
		var proveedor_id			= $("#frm_dispo_general_stockgrado #proveedor_id").val();
		var grado_id				= $("#frm_dispo_general_stockgrado #grado_id").val();		
		var color_ventas_ids 	 	= $("#grid_dispogeneral_color").jqGrid('getGridParam','selarrrow');
		var calidad_variedad_ids 	= $("#grid_dispogeneral_categoria").jqGrid('getGridParam','selarrrow');
		
		var porcentaje				= $("#frm_dispo_general_stockgrado #porcentaje").val();
		var valor					= $("#frm_dispo_general_stockgrado #valor").val();

		var data = 	{
						inventario_id:			inventario_id,
						clasifica:				clasifica,
						proveedor_id:			proveedor_id,
						grado_id:				grado_id,
						color_ventas_ids:		color_ventas_ids,
						calidad_variedad_ids:	calidad_variedad_ids,
						porcentaje:				porcentaje,
						valor:					valor
					}
		data = JSON.stringify(data);
		
		
		$("frm_dispo_general_stockgrado #btn_grabar").button('loading')
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/grabarmasivostock',
							'control_process':false,
							'show_cargando':true,
							'async':true, 
							'finish':function(response){
									$("frm_dispo_general_stockgrado #btn_grabar").button('reset');
									cargador_visibility('hide');
									$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
									$('#dialog_dispo_general_gradostock').modal('hide');
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;					
	}//end function DispoGeneral_GrabarStockPorGrado
	
	
	
	function DispoGeneral_OpenDialog_MoverStock()
	{
		var inventario_id		= $("#frm_dispo #inventario_id").val();
		var calidad_id			= $("#frm_dispo #calidad_id").val();
/*		var proveedor_id		= $("#frm_dispo #proveedor_id").val();*/
		var color_ventas_id		= $("#frm_dispo #color_ventas_id").val();
		var calidad_variedad_id	= $("#frm_dispo #calidad_variedad_id").val();
		var nro_tallos			= $("#frm_dispo #nro_tallos").val();			

		if (inventario_id=='')
		{
			alert('Debe de Seleccionar un Inventario');
			$("#frm_dispo #inventario_id").focus();
			return false;
		}//end if


		if (calidad_id=='')
		{
			alert('Debe de Seleccionar la calidad');
			$("#frm_dispo #calidad_id").focus();
			return false;
		}//endf if
		
/*		if (proveedor_id=='')
		{
			alert('Debe de Seleccionar una Finca');
			$("#frm_dispo #proveedor_id").focus();
			return false;
		}//end if		
*/
		//Almacena las variables que son parameros para el movimiento del inventario
		$("#frm_dispo_general_moverstock #inventario_id").val(inventario_id);
		$("#frm_dispo_general_moverstock #calidad_id").val(calidad_id);
//		$("#frm_dispo_general_moverstock #proveedor_id").val(proveedor_id);
		$("#frm_dispo_general_moverstock #color_ventas_id").val(color_ventas_id);
		$("#frm_dispo_general_moverstock #calidad_variedad_id").val(calidad_variedad_id);		
		$("#frm_dispo_general_moverstock #nro_tallos").val(nro_tallos);		

		//Carga el grid para mover el stock
		$('#grid_dispogeneral_moverstock').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");

		//Setea el titulo 
		//Abre el dialogo
/*		var proveedor_nombre = $("#frm_dispo #proveedor_id option:selected").text();
		proveedor_nombre = proveedor_nombre.replace('<','');
		proveedor_nombre = proveedor_nombre.replace('>','');		
*/		//if (proveedor_id!='') {proveedor_nombre = $("#frm_dispo #proveedor_id option:selected").text();}
		var titulo = "<b><em style='color:blue'>INVENTARIO:</em></b> "+$("#frm_dispo #inventario_id option:selected").text()+" - <b><em style='color:blue'>CALIDAD:</em></b> "+$("#frm_dispo #calidad_id option:selected").text()+" - <b><em style='color:blue'>FINCA:</em></b> TODAS - <b><em style='color:blue'>COLOR:</em></b> "+$("#frm_dispo #color_ventas_id option:selected").text()+" - <b><em style='color:blue'>CATEGORIA:</em></b> "+$("#frm_dispo #calidad_variedad_id option:selected").text()+" - <b><em style='color:blue'>TALLOS X BUNCH:</em></b> "+$("#frm_dispo #nro_tallos option:selected").text();
		$("#dialog_dispo_general_moverstock_titulo").html(titulo);
		$('#dialog_dispo_general_moverstock').modal('show');
	}//end function DispoGeneral_OpenDialog_MoverStock
	
	
	function DispoGeneral_ExportarExcel()
	{
		cargador_visibility('show');

		var url = '../../dispo/disponibilidad/exportarexcel';
		var params = '?inventario_id='+$("#frm_dispo #inventario_id").val()+
					 '&proveedor_id='+$("#frm_dispo #proveedor_id").val()+
				 	 '&clasifica='+$("#frm_dispo #calidad_id").val()+
					 '&color_ventas_id='+$("#frm_dispo #color_ventas_id").val()+
					 '&calidad_variedad_id='+$("#frm_dispo #calidad_variedad_id").val()+
					 '&nro_tallos='+$("#frm_dispo #nro_tallos").val();
		url = url + params;
		var win = window.open(url);
		
		cargador_visibility('hide');
	}//end function DispoGeneral_ExportarExcel
	
	
	
	function DispoGeneral_ActualizarCero()
	{
		var grid 				= $("#grid_dispo_general");
		var col_variedad_id	 	= jqgrid_get_columnIndexByName(grid, "variedad_id");
		var col_tallos_x_bunch 	= jqgrid_get_columnIndexByName(grid, "tallos_x_bunch");
        var rowKey 	= grid.getGridParam("selrow");

        if (!rowKey)
		{
			alert("SELECCIONE UN REGISTRO");
			return false;
		}//end if
		
		var r = confirm("Esta seguro de poner a CERO las variedades seleccionadas?");
		
		if (r == false)
		{ 
			return false; 
		}//end if
		
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
						inventario_id: 	$("#frm_dispo #inventario_id").val(),
						proveedor_id: 	$("#frm_dispo #proveedor_id").val(),
						clasifica: 		$("#frm_dispo #calidad_id").val(),
						grid_data: 		arr_data,
					}

		data = JSON.stringify(data);		
		
		//$("frm_dispo_general_stockgrado #btn_grabar").button('loading')
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/actualizarcerostock',
							'control_process':false,
							'show_cargando':true,
							'async':true, 
							'finish':function(response){
									//$("frm_dispo_general_stockgrado #btn_grabar").button('reset');
									cargador_visibility('hide');
									$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;					

	}//end function DispoGeneral_ActualizarCero
	
	
	
	function DispoGeneral_MoverStock()
	{
		var grid 				= $("#frm_dispo_general_moverstock #grid_dispogeneral_moverstock");
		var col_variedad_id	 	= jqgrid_get_columnIndexByName(grid, "variedad_id");
		var col_tallos_x_bunch 	= jqgrid_get_columnIndexByName(grid, "tallos_x_bunch");		
        var rowKey 				= grid.getGridParam("selrow");

		var check_40 			= ($("#frm_dispo_general_moverstock #chk_mover_select_40").is(':checked'));
		var check_50 			= ($("#frm_dispo_general_moverstock #chk_mover_select_50").is(':checked'));
		var check_60 			= ($("#frm_dispo_general_moverstock #chk_mover_select_60").is(':checked'));
		var check_70 			= ($("#frm_dispo_general_moverstock #chk_mover_select_70").is(':checked'));
		var check_80 			= ($("#frm_dispo_general_moverstock #chk_mover_select_80").is(':checked'));
		var check_90 			= ($("#frm_dispo_general_moverstock #chk_mover_select_90").is(':checked'));
		var check_100 			= ($("#frm_dispo_general_moverstock #chk_mover_select_100").is(':checked'));
		var check_110 			= ($("#frm_dispo_general_moverstock #chk_mover_select_110").is(':checked'));

        if (!rowKey)
		{
			alert("SELECCIONE UN REGISTRO");
			return false;
		}//end if
				
		if ((check_40==false) && (check_50==false) && (check_60==false) && (check_70==false) 
			&& (check_80==false) && (check_90==false) && (check_100==false) && (check_110==false))
		{
			alert('Debe de seleccionar por lo menos una columna');
			return false;
		}//end if
		
		//-----------Se parametriza los grados que se desean mover
		var arr_grado 	= new Array();
		if (check_40==true)	{var element = {}; element.grado_id = 40;  arr_grado.push(element);}
		if (check_50==true)	{var element = {}; element.grado_id = 50;  arr_grado.push(element);}
		if (check_60==true)	{var element = {}; element.grado_id = 60;  arr_grado.push(element);}
		if (check_70==true)	{var element = {}; element.grado_id = 70;  arr_grado.push(element);}
		if (check_80==true)	{var element = {}; element.grado_id = 80;  arr_grado.push(element);}
		if (check_90==true)	{var element = {}; element.grado_id = 90;  arr_grado.push(element);}
		if (check_100==true){var element = {}; element.grado_id = 100; arr_grado.push(element);}
		if (check_110==true){var element = {}; element.grado_id = 110; arr_grado.push(element);}
		
		//-----------Se parametriza los valores del GRID-----------------
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

		//------Se prepara el buffer que de la data que se va a enviar -----------------------------
		var data = 	{
						inventario_id: 			$("#frm_dispo_general_moverstock #inventario_id").val(),
						/*proveedor_id: 	$("#frm_dispo #proveedor_id").val(),*/
						clasifica: 				$("#frm_dispo_general_moverstock #calidad_id").val(),
						color_ventas_id:		$("#frm_dispo_general_moverstock #color_ventas_id").val(),
						calidad_variedad_id:	$("#frm_dispo_general_moverstock #calidad_variedad_id").val(),
						grid_data: 				arr_data,
						grados:					arr_grado,
						clasifica_destino:		$("#frm_dispo_general_moverstock #calidad_destino_id").val(),
					}
		data = JSON.stringify(data);			
		//console.log('data:',data); return false;
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/moverstock',
							'control_process':false,
							'show_cargando':true,
							'async':true,
							'finish':function(response){								
								if (response.validacion_code == 'OK')
								{	
									cargador_visibility('hide');
									$('#grid_dispogeneral_moverstock').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
									$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function DispoGeneral_MoverStock