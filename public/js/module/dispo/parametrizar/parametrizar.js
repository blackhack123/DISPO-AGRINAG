


$(document).ready(function () {
	
	 anchoPantalla = document.body.offsetWidth - 320;
	//EVENTOS  GRABAR
	 
     $("#frm_matenimiento #btn_grabar").on('click', function(event){ 
     	grabar();
			return false;
     });  
     
	
	/*---------------------------------------------------------------*/
	/*--------Se configura los JQGRID's PARAMETRO--------------------*/
	/*---------------------------------------------------------------*/	
	jQuery("#grid_parametro_listado").jqGrid({
		url:'../../dispo/parametrizar/listadodata',
		datatype: "json",
		loadonce: true,			
		/*height:'400',*/
		colNames:['Codigo','Descripcion','Tipo','Valor Texto','Valor Numerico','Observacion', 'Ultima Mod.',''],
		colModel:[
			{name:'id',index:'id', width:70, align:"left", sorttype:"string"},
			{name:'descripcion',index:'descripcion', width:230, sorttype:"string"},
			{name:'tipo',index:'tipo', width:40, sorttype:"string", formatter: gridParametroListado_FormatterTipo},	
			{name:'valor_texto',index:'valor_texto', width:80, sorttype:"string", align: 'left' },	
			{name:'valor_numerico',index:'valor_numerico', width:60, sorttype:"string", align:"left"},
			{name:'observacion',index:'observacion', width:80, sorttype:"string", align:"left"},
			{name:'fec_modifica',index:'fec_modifica', width:60, sorttype:"string", align:"center", hidden:true},
			{name:'btn_editar',index:'', width:30, align:"center", formatter: gridParametroListado_FormatterEdit,
			cellattr: function () { return ' title=" Modificar"'; }
			},
		],
		rowNum:999999,
		pager: '#pager_parametro_listado',
		toppager:false,
		pgbuttons:false,
		pginput:false,
		rownumbers: true,
		rowList:false,
		loadComplete:  grid_setAutoHeight, 
		resizeStop: grid_setAutoHeight, 
		width: anchoPantalla,
		gridview:false,	
		multiselect: false,
		jsonReader: {
			repeatitems : false,
		},		
		/*caption:"Grilla de Prueba",*/
		
		ondblClickRow: function (rowid,iRow,iCol,e) {
				var data = $('#grid_parametro_listado').getRowData(rowid);
				consultar(data.id);
				//return false;
		},
		
		loadBeforeSend: function (xhr, settings) {
			this.p.loadBeforeSend = null; //remove event handler
			return true; // don't send load data request
		},				
		loadError: function (jqXHR, textStatus, errorThrown) {
			message_error('ERROR','HTTP message body (jqXHR.responseText): ' + '<br>' + jqXHR.responseText);
		}
		
	});
	
	
	function gridParametroListado_FormatterTipo(cellvalue, options, rowObject)
	{	
		switch (rowObject.tipo)
		{
		case 'T':
			new_format_value = 'TEXTO'; 
			break;
		case 'N':
			new_format_value = 'NUMERICO';
			break;
		}//end switch
		return new_format_value;
		
	}//end function gridParametroListado_FormatterTipo
	
	
	function gridParametroListado_FormatterEdit(cellvalue, options, rowObject)
	{
		var id = rowObject.id;	
		new_format_value = '<a href="javascript:void(0)" onclick="consultar(\''+id+'\')"><i class="glyphicon glyphicon-pencil" style="color:orange"></i></a>'; 
		return new_format_value
		
	}//end function gridUsuarioListado_FormatterEdit
	
	
	jQuery("#grid_parametro_listado").jqGrid('navGrid','#pager_parametro_listado',{edit:false,add:false,del:false});

	
	/*---------------------------------------------------------------*/	
	/*---------------------------------------------------------------*/
	
});

		
	function consultar(id)
	{
		//Se llama mediante AJAX 
		var data = 	{id: id}
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',
							'contentType': 'application/json',
							'url':'../../dispo/parametrizar/consultardata',
							'control_process':true,
							'show_cargando':true,
							'finish':function(response){
									mostrar_registro(response)
									cargador_visibility('hide');
	
									$("#dialog_mantenimiento").modal('show');
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function consultar
	
	
	function mostrar_registro(response)
	{
		var espacio = '';
		var row = response.row;
		if (row!==null)
		{
			ValidateControlsInit();
			$("#accion").val("M");			
			$("#dialog_mantenimiento_parametros_nombre").html(row.id);
			$("#frm_matenimiento #id").val(row.id);
			$("#frm_matenimiento #id").prop('readonly',true);
			$("#frm_matenimiento #descripcion").html(row.descripcion);
			$("#frm_matenimiento #valor_texto").val(espacio);
			$("#frm_matenimiento #valor_numerico").val(espacio);
			
			if (row.tipo == "T")
			{
				 $("#frm_matenimiento #tipo").html('Texto');
				 $("#valor_texto").show();
				 $("#valor_numerico").hide();
				 $("#frm_matenimiento #valor_texto").val(row.valor_texto);
			}
			else
			{
				$("#frm_matenimiento #tipo").html('Numerico');
				$("#valor_texto").hide();
				$("#valor_numerico").show();
				$("#frm_matenimiento #valor_numerico").val(row.valor_numerico);
			}//end if
			
			$("#frm_matenimiento #observacion").val(row.observacion);
			$("#frm_matenimiento #lbl_fec_ingreso").html(row.fec_ingreso);
			$("#frm_matenimiento #lbl_fec_modifica").html(row.fec_modifica);
			$("#frm_matenimiento #lbl_usuario_ing").html(row.usuario_ing_user_name);
			$("#frm_matenimiento #lbl_usuario_mod").html(row.usuario_mod_user_name);
			
		}//end if
	}//end function mostrar_registro
	
	
	function grabar(){
		if (!ValidateControls('frm_matenimiento')) {
			return false;
		}
		if ($('frm_matenimiento #tipo').text() == "TEXTO")
		{
			valor_texto = $("#frm_matenimiento #valor_texto").val();	
			valor_numerico = null;
		}
		else
		{
			valor_numerico = $("#frm_matenimiento #valor_numerico").val();
			valor_texto = null ;
		} 
		//Se llama mediante AJAX 
		var data = 	{	accion: $("#accion").val(),
					 	id: $("#frm_matenimiento #id").val(),
					  	observacion:$("#frm_matenimiento #observacion").val(),
					  	valor_texto: $("#frm_matenimiento #valor_texto").val(),
					  	valor_numerico: $("#frm_matenimiento #valor_numerico").val(),
					}
		
		data = JSON.stringify(data);
		
		var parameters = {	'type': 'POST',//'POST',
							'contentType': 'application/json',
							'url':'../../dispo/parametrizar/grabardata',
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
										$("#dialog_mantenimiento").modal('hide');
										$('#grid_parametro_listado').jqGrid("setGridParam",{datatype:"json"}).trigger("reloadGrid");
									}else{
										swal({title: response.respuesta_mensaje,   
											//text: "Desea continuar utilizando la misma marcacion? Para seguir realizando mas pedidos",  
											//html:true,
											type: "error",
											showCancelButton: false,
											confirmButtonColor: "#DD6B55",
											confirmButtonText: "OK",
											cancelButtonText: "",
											closeOnConfirm: false,
											closeOnCancel: false,
											/*timer: 2000*/
										});
									
									}									
							}							
		                 }
		response = ajax_call(parameters, data);		
		return false;		
	}//end function grabar
	
	