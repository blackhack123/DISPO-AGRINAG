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
	
	$("#frm_dispo #inventario_id, #frm_dispo #calidad_id, #frm_dispo #proveedor_id, #frm_dispo #color_ventas_id").on('change', function(event){
//		$("#grid_dispo_general").jqGrid('clearGridData');
		$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});
	
	
	$("#frm_dispo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	
	$("#frm_dispo #btn_nuevo").on('click', function(event){ 
		disponibilidad_nueva_variedad();
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
			color_ventas_id:function() {return $("#frm_dispo #color_ventas_id").val();}
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['tallos_x_bunch','variedad_nombre','Id','Variedad','','Color','40','50','60','70','80','90', '100', '110','Total'],
		colModel:[
/*			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},*/
			{name:'tallos_x_bunch',index:'tallos_x_bunch', width:50, align:"center", sorttype:"int", hidden:true},
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
			{name:'total',index:'total', width:50, align:"right", sorttype:"int"}
		],
		rowNum:999999,
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
		console.log(rowObject.url_ficha);
		if (rowObject.url_ficha === undefined || rowObject.url_ficha === null || rowObject.url_ficha=='')
		{
			new_format_value = '';
		}else{
			new_format_value = '<a href="javascript:void(0)" onclick="window.open(\''+rowObject.url_ficha+'\',this.target,\'scrollbars=yes,resizable=yes,height=600,width=1000,left=100,top=100\')"><i class="glyphicon glyphicon-camera" style="color:green"></i></a>'; 
		}//end if
		
		return new_format_value;
	}//end function ListadoCliente_FormatterEdit



	function gridDispoGeneral_GradosFormatter(cellvalue, options, rowObject){

		if (primera_vez_DispoGeneralGrid == true)
		{
			col_grado_name 	= options.colModel.name;
			stock			= number_val(cellvalue);	
			variedad_id		= rowObject.variedad_id;
			variedad		= rowObject.variedad_nombre; //Nombre
			tallos_x_bunch	= rowObject.tallos_x_bunch;
		}else{
			pos_col_grado 			= options.pos;
			pos_col_variedad_id 	= 3;
			pos_col_variedad_nombre	= 2;
			pos_col_tallos_x_bunch 	= 1;			
			stock 			= number_val(rowObject[pos_col_grado]);	
			variedad_id		= rowObject[pos_col_variedad_id];
			variedad		= rowObject[pos_col_variedad_nombre]; //Nombre
			tallos_x_bunch	= number_val(rowObject[pos_col_tallos_x_bunch]);
		}//end if		
		var color = "Black"
		if (cellvalue==0)
		{
			color = "LightGray";
		}//end if
		
		new_format_value = '<a href="javascript:void(0)" data-toggle="modal" data-target="#dialog_dispo_proveedores" onclick="open_dialog_dispo(\''+options.rowId+'\',\''+options.colModel.name+'\',\''+variedad_id+'\',\''+variedad+'\',\''+options.colModel.name+'\',\''+tallos_x_bunch+'\')" style="color:'+color+'">'+cellvalue+ '</a>';		
		return new_format_value;
	}//end function gridDispoGeneral_GradosFormatter
	
		
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
	
	});
	
	

	function disponibilidad_init()
	{
		//Deshabilita los botones del formulario
		$("#frm_dispo button").prop('disabled', true);
		
		var data = 	{
						opcion: 'panel-control-disponibilidad',
						inventario_1er_elemento:	'USA',
						calidad_1er_elemento:		'',
						proveedor_1er_elemento:		'&lt;TODAS LAS FINCAS&gt;',
						color_ventas_1er_elemento:  '&lt;TODOS LOS COLORES&gt;'
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
								
								//Habilita los botones del formulario
								$("#frm_dispo button").prop('disabled', false);
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
										timer: 1000
									});
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