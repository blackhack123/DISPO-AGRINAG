/**
 * 
 */
var selRowId_DispoGrupoGrid 	= 0;	
var selColName_DispoGrupoGrid 	= 0;
var seliRow_DispoGrupoGrid 		= 0;		
var seliCol_DispoGrupoGrid 		= 0;	
var val_DispoGrupoGrid		= null;

$(document).ready(function () {
	
	/*----------------------Se cargan los controles -----------------*/
	dispoGrupo_init();
	
	$("#frm_dispo_grupo #grupo_dispo_cab_id").on('change', function(event){
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;		
	});
	
	
	$("#frm_dispo_grupo #btn_consultar").on('click', function(event){ 
		$('#grid_dispo_grupo').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
		return false;
	});	
	/*
	$("#frm_dispo_grupo #btn_nuevo").on('click', function(event){ 
		$("#dialog_dispo_variedad_titulo").html('Variedad - Grado: 30');
		$('#dialog_dispo_variedad').modal('show')
		return false;
	});		
	
	$("#frm_dispo_grupo_stock #btn_grabar").on('click', function(event){ 
		grabar_dispoGrupo_stock();
		return false;
	});	
	*/

	
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
			{name:'dgen_40',index:'dgen_40', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_40',index:'dgru_40', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_50',index:'dgen_50', width:50, align:"center", sorttype:"int"},
			{name:'dgru_50',index:'dgru_50', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_60',index:'dgen_60', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_60',index:'dgru_60', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_70',index:'dgen_70', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_70',index:'dgru_70', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_80',index:'dgen_80', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_80',index:'dgru_80', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_90',index:'dgen_90', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_90',index:'dgru_90', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_100',index:'dgen_100', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_100',index:'dgru_100', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
									 }											
			},
			{name:'dgen_110',index:'dgen_110', width:50, align:"center", sorttype:"int"},	
			{name:'dgru_110',index:'dgru_110', width:50, align:"center", sorttype:"int", editable:true, formatter:'number', formatoptions:{decimalPlaces: 0},
						editoptions: {
										dataInit : function (elem) { $(elem).focus(function(){ this.select();}) },									
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
		loadComplete: grid_setAutoHeight,
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
			val_DispoGrupoGrid   = value;
			//console.log('beforeEditCell iCol:', iCol,'*iRow:',iRow,'*val_DispoGrupoGrid:',val_DispoGrupoGrid);			
		},			
		afterSaveCell : function(rowid,name,val,iRow,iCol) {
			//console.log('rowid:'+rowid+'*name:'+name+'*val:'+val+'*iRow:'+iRow+'*iCol:'+iCol);
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
				 $("#grid_dispo_grupo").jqGrid('setCell', seliRow_DispoGrupoGrid, seliCol_DispoGrupoGrid, val_DispoGrupoGrid);
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
	
		
		
	function gridDispoGeneral_GradosFormatter(cellvalue, options, rowObject){
		var color = "Black"
		if (cellvalue==0)
		{
			color = "LightGray";
		}
		
		new_format_value = '<a href="javascript:void(0)" data-toggle="modal" data-target="#dialog_dispogrupo_stock" onclick="open_dialog_dispoGrupo(\''+options.rowId+'\',\''+options.colModel.name+'\',\''+rowObject.variedad_id+'\',\''+rowObject.variedad+'\',\''+options.colModel.name+'\')" style="color:'+color+'">'+cellvalue+ '</a>';
		return new_format_value;
	}
		
	jQuery("#grid_dispo_grupo").jqGrid('navGrid','#pager_dispo_grupo',{edit:false,add:false,del:false});

	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	});
	
	

	function dispoGrupo_init()
	{
		var data = 	{
						opcion: 'panel-control-disponibilidad',
						grupo_dispo_1er_elemento:	'&lt;SELECCIONE&gt;',
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

	
	function open_dialog_dispoGrupo(rowId, colName, variedad_id, variedad_nombre, grado_id)
	{
		selRowId_DispoGrupoGrid 	= rowId;
		selColName_DispoGrupoGrid = colName;
			
			
		$("#dialog_dispogrupo_stock .modal-title").html(variedad_nombre+' - Grado:'+grado_id);

		$("body #frm_dispo_grupo_stock #valor_maximo").val("");
		$("body #frm_dispo_grupo_stock #valor_actual").val("");
		
		$("body #frm_dispo_grupo_stock #valor_maximo").prop("readonly",true);
		$("body #frm_dispo_grupo_stock #valor_actual").prop("readonly",true);
			
		var data = 	{
						grupo_dispo_cab_id:	$("#frm_dispo_grupo #grupo_dispo_cab_id").val(),
						variedad_id:		variedad_id,
						grado_id:			grado_id,
					}
		data = JSON.stringify(data);
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/grupodispo/consultarStock',
							'show_cargando':false,
							'async':true,
							'finish':function(response){
								//Se asignan las variables al dialogo
								$("#frm_dispo_grupo_stock #grupo_dispo_cab_id").val($("#frm_dispo_grupo #grupo_dispo_cab_id").val());
								$("#frm_dispo_grupo_stock #grado_id").val(grado_id);
								$("#frm_dispo_grupo_stock #variedad_id").val(variedad_id);								
								
								//Se asigna los valores del stock
								if(response.hasOwnProperty("row")){
									$("#frm_dispo_grupo_stock #cantidad_bunch").val(response.row.cantidad_bunch);
									$("#frm_dispo_grupo_stock #cantidad_bunch_disponible").val(response.row.cantidad_bunch_disponible);
								}//end if
							 }							
						 }
		response = ajax_call(parameters, data);					
	}//end function open_dialog_dispoGrupo
	


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


