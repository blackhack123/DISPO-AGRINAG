<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Application\Classes\PHPExcelApp;
use Dispo\DAO\ClienteDAO;
use Dispo\Data\ClienteData;

class ClienteBO extends Conexion
{


	/**
	 * 
	 * @param string $cliente_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($cliente_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ClienteDAO = new ClienteDAO();
		
		$ClienteDAO->setEntityManager($this->getEntityManager());

		//$result = $ClienteDAO->consultarTodo();
		$result = $ClienteDAO->consultarUsuarioAsignado();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $cliente_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function listado
	
	
	/**
	 *
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado
	 *
	 * @param array $condiciones
	 * @return array
	 */
	function listado($condiciones)
	{
		$ClienteDAO = new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		$result = $ClienteDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\ClienteData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$ClienteDAO = new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		$reg = $ClienteDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar
	

	
	
	/**
	 * Ingresar
	 *
	 * @param ClienteData $ClienteData
	 * @return array
	 */
	function ingresar(ClienteData $ClienteData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$ClienteDAO = new ClienteDAO();
			$ClienteDAO->setEntityManager($this->getEntityManager());
			$ClienteData2 = $ClienteDAO->consultar($ClienteData->getId());
			if (!empty($ClienteData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $ClienteDAO->ingresar($ClienteData);
				$result['validacion_code'] 	= 'OK';
				$result['respuesta_mensaje']= '';
			}//end if
				
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function ingresar
	
	
	/**
	 * Modificar
	 *
	 * @param ClienteData $ClienteData
	 * @return array
	 */
	function modificar(ClienteData $ClienteData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$ClienteDAO = new ClienteDAO();
			$ClienteDAO->setEntityManager($this->getEntityManager());
			//$ClienteData2 = $ClienteDAO->consultar($ClienteData->getId());
			$result = $ClienteDAO->consultar('M',$ClienteData->getId(), $ClienteData->getNombre());
			$id=		$ClienteData->getId();
			$nombre=	$ClienteData->getNombre();
			if (!empty($result))
			{
	
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
	
				$id = $ClienteDAO->modificar($ClienteData);
				$result['validacion_code'] 	= 'OK';
				$result['respuesta_mensaje']= '';
			}//end if
	
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function ingresar
	
	
	
	/**
	 *
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado
	 *
	 * @param array $condiciones
	 * @return array
	 */
	function getClienteFactura($condiciones)
	{
		$ClienteDAO = new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		$result = $ClienteDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	/**
	 *
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado
	 *
	 * @param array $condiciones
	 * @return array
	 */
	function ConsultarClienteFactura($condiciones)
	{
		$ClienteDAO = new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		$result = $ClienteDAO->ConsultarClienteFactura($condiciones);
		return $result;
	}//end function listado
	
	
	
	/**
	 *
	 * @param array $condiciones (criterio_busqueda, busqueda_color , busqueda_estado)
	 */
	public function generarExcel($condiciones)
	{
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		$ClienteDAO				= new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$texto_criterio_busqueda 		= 'TODOS';
		$texto_estado					= 'TODOS';
		
		if (!empty($condiciones['criterio_busqueda'])){
			$texto_criterio_busqueda	= $condiciones['criterio_busqueda'];
		}//end if
		
		switch ($condiciones['busqueda_estado'])
		{
			case 'A':
				$texto_estado		=  'ACTIVO';
				break;
		
			case 'I':
				$texto_estado		=  'INACTIVO';
				break;
		
		}//end switch
		
		
		//----------------Se inicia la configuracion del PHPExcel-----------------
		
		$PHPExcelApp 	= new PHPExcelApp();
		$objPHPExcel 	= new \PHPExcel;
		
		// Set document properties
		$PHPExcelApp->setUserName('');
		$PHPExcelApp->setMetaDataDocument($objPHPExcel);
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		//Configura el tamaÃ±o del Papel
		$objPHPExcel->getActiveSheet()->getPageSetup()
		->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()
		->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		
		
		//Se establece la escala de la pagina
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		
		//Se establece los margenes de la pagina
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.1);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.1);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.1);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.1);
		
		
		//------------------------------Registra la cabecera--------------------------------
		$row				= 1;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(10);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "LISTADO DE CLIENTES");
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//------------------------------Registra criterios linea 1--------------------------
		$row				= 2;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(11);
		
		$objRichText = new \PHPExcel_RichText();
		$objRichText->createText('');
		
		$objInventario = $objRichText->createTextRun('  Criterio: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_criterio_busqueda);
		
		$objCalidad = $objRichText->createTextRun('    Estado: ');
		$objCalidad->getFont()->setBold(true);
		$objCalidad->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_estado);
		
		
		$objPHPExcel->getActiveSheet()->getCell($col_ini.$row)->setValue($objRichText);
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		
		
		//------------------------------ Registro de Fecha de Generacion --------------------------------
		$row				= 3;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(10);
		
		//$etiqueta = "";
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Generado: ".\Application\Classes\Fecha::getFechaHoraActualServidor());
		
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		
		//-------------------------ESTABLECE TITULO DE COLUMNAS----------------------------
		$row = $row + 1;
		$row_detalle_ini = $row;
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row, "Nro");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row, "Id");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row, "Nombre");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row, "Direccion");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row, "Pais");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row, "Estado");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row, "Cod. Postal");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row, "Telefono");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row, "Fax");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row, "Email");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row, "Estado");
		
		
		//-------------------------ESTABLECE ANCHO DE COLUMNAS----------------------------
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setAutoSize(true);
		
		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		$objPHPExcel_getActiveSheet=$objPHPExcel->getActiveSheet();
		
		
		$result = $this->listado($condiciones);
		
		
		$cont_linea = 0;
		foreach($result as $reg){
				
			$cont_linea++;
			$row=$row+1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $reg['id'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['nombre'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $reg['direccion'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $reg['pais_nombre'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reg['nombre_estados'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $reg['codigo_postal'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $reg['telefono1'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $reg['fax1'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $reg['email'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $reg['estado'] );
			
		}//end foreach
		
		
		//Margenes
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(10);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_ini.":".$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_BORDE_TODO));
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Listado Clientes');
		
		$PHPExcelApp->save($objPHPExcel, $PHPExcelApp::FORMAT_EXCEL_2007, "Listado_Clientes.xlsx" );
	}
	
}//end class ClienteBO
