<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Application\Classes\PHPExcelApp;
use Zend\View\Model\JsonModel;
use Dispo\DAO\AgenciaCargaDAO;
use Dispo\Data\AgenciaCargaData;


class AgenciaCargaBO extends Conexion
{

	/**
	 * 
	 * @param int $agencia_carga_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodos($agencia_carga_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());

		$result = $AgenciaCargaDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $agencia_carga_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboTodos
	
	
	
	/**
	 * 
	 * @param int $agencia_carga_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboActivos($agencia_carga_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$AgenciaCargaDAO = new AgenciaCargaDAO();
	
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
	
		$result = $AgenciaCargaDAO->consultarTodos("A");
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $agencia_carga_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboActivos	

	
	
	
	function getComboTipo($tipo, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$arrData = array('A'=>'Agencia','B'=>'Ambas','C'=>'Cuarto Frio');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $tipo, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboTipo

	
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
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$result = $AgenciaCargaDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	/***
	 * 
	 * @param array $condiciones
	 * @return Ambigous <\Dispo\Data\AgenciaCargaData, NULL>
	 */
	public function listadoExcel($condiciones)
	{
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$result = $AgenciaCargaDAO->consultarExcel($condiciones);
		return $result;
	}//end function listado
	
	/**
	 * Consultar 
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\AgenciaCargaData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$reg = $AgenciaCargaDAO->consultar($id, $resultType);
		return $reg;		
	}//end function consultar
	
	
	/**
	 * Ingresar
	 * 
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array
	 */
	function ingresar(AgenciaCargaData $AgenciaCargaData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$AgenciaCargaDAO = new AgenciaCargaDAO();
			$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
			$AgenciaCargaData2 = $AgenciaCargaDAO->consultar($AgenciaCargaData->getId());
			if (!empty($AgenciaCargaData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $AgenciaCargaDAO->ingresar($AgenciaCargaData);
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
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array
	 */
	function modificar(AgenciaCargaData $AgenciaCargaData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$AgenciaCargaDAO = new AgenciaCargaDAO();
			$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
			//$AgenciaCargaData2 = $AgenciaCargaDAO->consultar($AgenciaCargaData->getId());
			$result = $AgenciaCargaDAO->consultarDuplicado('M',$AgenciaCargaData->getId(), $AgenciaCargaData->getNombre());
			$id=		$AgenciaCargaData->getId();
			$nombre=	$AgenciaCargaData->getNombre();
			if (!empty($result))
			{
				
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
				
				$id = $AgenciaCargaDAO->modificar($AgenciaCargaData);
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
	
	
	/***
	 * 
	 * @param array $condiciones
	 */
	public function generarExcel($condiciones)
	{
		
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
	
	
		
		$AgenciaCargaDAO			= new AgenciaCargaDAO();
	
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$criterio_busqueda		= 'TODOS';
		$estado 				= 'TODOS';
		$sincronizado			= 'TODAS';
		
		
		if (!empty($condiciones['criterio_busqueda'])){
			$AgenciaCargaData 		= $AgenciaCargaDAO->consultarExcel($condiciones['criterio_busqueda']);
			$criterio_busqueda		= $AgenciaCargaData->getNombre();
		}//end if
		
		if (!empty($condiciones['estado'])){
			$AgenciaCargaData 		= $AgenciaCargaDAO->consultarExcel($condiciones['estado']);
			$criterio_busqueda		= $AgenciaCargaData->getEstado();
		}//end if
		
		if (!empty($condiciones['sincronizado'])){
			$AgenciaCargaData 		= $AgenciaCargaDAO->consultarExcel($condiciones['sincronizado']);
			$criterio_busqueda		= $AgenciaCargaData->getSincronizado();
		}//end if
		
		
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
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Agencia Carga");
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		
		//------------------------------Registra criterios linea 1--------------------------
		$row				= 2;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		
		$objRichText = new \PHPExcel_RichText();
		$objRichText->createText('');
		
		$objInventario = $objRichText->createTextRun('     Criterio: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($criterio_busqueda);
		
		
		$objInventario = $objRichText->createTextRun('     Estado: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($estado);
		
		
		$objInventario = $objRichText->createTextRun('     Sincronizado: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($sincronizado);
		
		$objPHPExcel->getActiveSheet()->getCell($col_ini.$row)->setValue($objRichText);
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		
		
		//------------------------------ Registro de Fecha de Generacion --------------------------------
		$row				= 3;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		
		//$etiqueta = "";
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Generado: ".\Application\Classes\Fecha::getFechaHoraActualServidor());
		
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		
		//---------------------------IMPRIME TITULO DE COLUMNA-----------------------------
		$row = $row + 1;
		$row_detalle_ini = $row;
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row, "Nro");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row, "Id");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row, "Agencia");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row, "Direccion");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row, "Telefono");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row, "Estado");
		
		//----------------------AUTO DIMENSIONAR CELDAS DE ACUERDO AL CONTENIDO---------------
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		$objPHPExcel_getActiveSheet=$objPHPExcel->getActiveSheet();
		
		//----------------------CONSULTA LOS REGISTROS A EXPORTAR---------------
		$result = $this->listadoExcel($condiciones);
		
		
		$cont_linea = 0;
		foreach($result as $reg){
			
			$reg['nombre'] 		= trim($reg['nombre']);
			$reg['direccion'] 	= trim($reg['direccion']);
			
			
			$cont_linea++;
			$row=$row+1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $reg['id'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['nombre'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $reg['direccion'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $reg['telefono'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reg['estado'] );
			
		}// end foreach
		
		
		//Margenes
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_ini.":".$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_BORDE_TODO));
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Listado Agencias');
		
		$PHPExcelApp->save($objPHPExcel, $PHPExcelApp::FORMAT_EXCEL_2007, "Listado Agencias.xlsx" );
		
	}//end generarExcel
	
	
	
	
}//end class
