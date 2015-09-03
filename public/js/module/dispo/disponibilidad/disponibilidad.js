/**
 * 
 */


$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	disponibilidad_init();
	
	$("#frm_dispo #inventario_id, #frm_dispo #calidad_id, #frm_dispo #proveedor_id").on('change', function(event){
//		$("#grid_dispo_general").jqGrid('clearGridData');
		$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});
	
	
	$("#frm_dispo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_general').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	
	
	$("#frm_dispo_proveedor #btn_grabar").on('click', function(event){ 
		grabar_dispo_proveedor();
		return false;
	});	

	
	/*---------------------------------------------------------------*/	
	

	/*---------------------------------------------------------------*/
	/*-----Se configura los JQGRID de Dispobilidad General-----------*/
	/*---------------------------------------------------------------*/		
	jQuery("#grid_dispo_general").jqGrid({
		url:'../../dispo/disponibilidad/disponibilidaddata',
		postData: {
			inventario_id: 	function() {return $("#frm_dispo #inventario_id").val();},
			proveedor_id: 	function() {return $("#frm_dispo #proveedor_id").val();},
			clasifica: 		function() {return $("#frm_dispo #calidad_id").val();}
		},
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['','variedad_id','Variedad','40','50','60','70','80','90', '100', '110'],
		colModel:[
			{name:'seleccion',index:'', width:50,  formatter: 'checkbox', align: 'center',editable: true, formatoptions: {disabled : false}, editoptions: {value:"1:0" },editrules:{required:false}},
			{name:'variedad_id',index:'variedad_id', width:50, align:"center", sorttype:"int"},
			{name:'variedad',index:'variedad', width:170, sorttype:"string"},
			{name:'40',index:'40', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'50',index:'50', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'60',index:'60', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'70',index:'70', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'80',index:'80', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'90',index:'90', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'100',index:'100', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter},	
			{name:'110',index:'110', width:50, align:"center", sorttype:"int", formatter: gridDispoGeneral_GradosFormatter}	
		],
		rowNum:999999,
		pager: '#pager_dispo_general',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rowList:false,
		gridview:false,	
		shrinkToFit: false,
		loadComplete: grid_setAutoHeight,
		resizeStop: grid_setAutoHeight, 
		jsonReader: {
			repeatitems : false,
		},		
		/*loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return false; // dont send load data request
		},*/				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
	});
	$("#grid_dispo_general").jqGrid('filterToolbar',{stringResult:true, defaultSearch : "cn", searchOnEnter : false});
		
		
	function gridDispoGeneral_GradosFormatter(cellvalue, options, rowObject){
		var color = "Black"
		if (cellvalue==0)
		{
			color = "LightGray";
		}
		
		new_format_value = '<a href="javascript:void(0)" data-toggle="modal" data-target="#dialog_dispo_proveedores" onclick="open_dialog_dispo(\''+rowObject.variedad_id+'\',\''+rowObject.variedad+'\',\''+options.colModel.name+'\')" style="color:'+color+'">'+cellvalue+ '</a>';
		return new_format_value;
	}
		
	jQuery("#grid_dispo_general").jqGrid('navGrid','#pager_dispo_general',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
	});
	
	

	function disponibilidad_init()
	{
		var data = 	{
						opcion: 'panel-control-disponibilidad',
						inventario_1er_elemento:	'',
						calidad_1er_elemento:		'',
						proveedor_1er_elemento:		'&lt;TODAS&gt;'
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
							 }							
						 }
		response = ajax_call(parameters, data);		
		return false;	
	}//end function disponibilidad_init

	
	function open_dialog_dispo(variedad_id, variedad_nombre, grado_id)
	{
		$("#dialog_dispo_proveedores_titulo").html(variedad_nombre+' - Grado:'+grado_id);

		$("body #frm_dispo_proveedor #stock_agr").val("");
		$("body #frm_dispo_proveedor #stock_htc").val("");
		$("body #frm_dispo_proveedor #stock_lma").val("");		
		var data = 	{
						inventario_id:	$("#frm_dispo #inventario_id").val(),
						clasifica_fox:	$("#frm_dispo #calidad_id").val(),
						proveedor_id:	$("#frm_dispo #proveedor_id").val(),
						variedad_id:	variedad_id,
						grado_id:		grado_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/disponibilidad/consultarPorInventarioPorCalidadPorProveedorPorGrado',
							'show_cargando':false,
							'async':true,
							'finish':function(response){
								//Se asignan las variables al dialogo
								$("#frm_dispo_proveedor #inventario_id").val($("#frm_dispo #inventario_id").val());
								$("#frm_dispo_proveedor #calidad_id").val($("#frm_dispo #calidad_id").val());
								$("#frm_dispo_proveedor #proveedor_id").val($("#frm_dispo #proveedor_id").val());
								$("#frm_dispo_proveedor #grado_id").val(grado_id);
								$("#frm_dispo_proveedor #variedad_id").val(variedad_id);								
								
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
	

	function grabar_dispo_proveedor()
	{
		var stock_agr = $("#frm_dispo_proveedor #stock_agr").val();
		var stock_htc = $("#frm_dispo_proveedor #stock_htc").val();
		var stock_lma = $("#frm_dispo_proveedor #stock_lma").val();
		
		if ((stock_agr=='')&&(stock_htc=='')&&(stock_lma=='')){
			alert('Debe de Ingresar por lo menos un valor');
			$("#frm_dispo_proveedor #stock_agr").focus();
			return false;
		}//end if
		
		console.log('stock_agr:',stock_agr,'*stock_htc:',stock_htc,'*stock_lma:',stock_lma);
		
		var data = 	{
						inventario_id:	$("#frm_dispo_proveedor #inventario_id").val(),
						clasifica_fox:	$("#frm_dispo_proveedor #calidad_id").val(),
						proveedor_id:	$("#frm_dispo_proveedor #proveedor_id").val(),
						variedad_id:	$("#frm_dispo_proveedor #variedad_id").val(),
						grado_id:		$("#frm_dispo_proveedor #grado_id").val(),
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
									mostrar_registro(response)
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
										/*timer: 2000*/
									});
								}else{
									message_error('ERROR', response);
								}//end if									
							}							
						 }
		response = ajax_call(parameters, data);		
		return false;				
	}//end function grabar_dispo_proveedor
