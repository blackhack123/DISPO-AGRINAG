<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Application\Classes\PHPExcelApp;
use Dispo\DAO\VariedadDAO;
use Dispo\DAO\ProductoDAO;
use Dispo\DAO\ObtentorDAO;
use Dispo\BO\ColorVentasBO;
use Dispo\Data\VariedadData;
use Dispo\DAO\Dispo\DAO;


class VariedadBO extends Conexion
{
	

	/**
	 *
	 * @param int $variedad_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboColorBase($variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$VariedadDAO = new VariedadDAO();
	
		$VariedadDAO->setEntityManager($this->getEntityManager());
	
		$result = $VariedadDAO->consultarColores();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $variedad_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodos
	
	
	
	/**
	 *
	 * @param int $obtentor
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
/*	function getComboObtentor($obtentor, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$ObtentorDAO = new ObtentorDAO();
	
		$ObtentorDAO->setEntityManager($this->getEntityManager());
	
		$result = $ObtentorDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $obtentor, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodos
*/	
	
	
	/**
	 * 
	 * @param string $solido
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboSolido($solido, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('S'=>'SI','N'=>'NO');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $solido, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboSolido
	
	
	/**
	 * 
	 * @param string $es_real
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboEsReal($es_real, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('S'=>'SI','N'=>'NO');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $es_real, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboSolido
	
	
	/**
	 *
	 * @param string $cultivada
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboCultivada($cultivada, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('S'=>'SI','N'=>'NO');
	
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $cultivada, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboSolido
	

	/**
	 * Ingresar
	 *
	 * @param VariedadData $VariedadData
	 * @return array
	 */
	function ingresar(VariedadData $VariedadData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$VariedadDAO = new VariedadDAO();
			$VariedadDAO->setEntityManager($this->getEntityManager());
			$VariedadData2 = $VariedadDAO->consultar($VariedadData->getId());
			if (!empty($VariedadData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $VariedadDAO->ingresar($VariedadData);
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
	 * @param VariedadData $VariedadData
	 * @return array
	 */
	function modificar(VariedadData $VariedadData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$VariedadDAO = new VariedadDAO();
			$VariedadDAO->setEntityManager($this->getEntityManager());
			//$VariedadData2 = $VariedadDAO->consultar($VariedadData->getId());
			$result = $VariedadDAO->consultarDuplicado('M',$VariedadData->getId(), $VariedadData->getNombre());
			$id=		$VariedadData->getId();
			$nombre=	$VariedadData->getNombre();
			if (!empty($result))
			{
	
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
	
				$id = $VariedadDAO->modificar($VariedadData);
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
	}//end function modificar
	
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\VariedadData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$reg = $VariedadDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\VariedadData, NULL, array>
	 */
	function consultarVariedad($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$reg = $VariedadDAO->consultarVariedad($id, $resultType);
		return $reg;
	}//end function consultar
	
	
	
	/**
	 *
	 * 
	 * @return array
	 */
	function consultarTodos()
	{
	
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$result = $VariedadDAO->consultarTodos();
		return $result;
	}//end function ConsultarTodos
	

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
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$result = $VariedadDAO->listado($condiciones);
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
	function listadoExcel($condiciones)
	{
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$result = $VariedadDAO->listadoExcel($condiciones);
		return $result;
	}//end function listado
	

	
	/**
	 *
	 * @param string $variedad_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$VariedadDAO = new VariedadDAO();
	
		$VariedadDAO->setEntityManager($this->getEntityManager());
	
		$result = $VariedadDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$variedad_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getCombo	
	
	
	
	/**
	 *
	 * @param array $condiciones (inventario_id, proveedor_id, clasifica, color_ventas_id, calidad_variedad_id)
	 */
	public function generarExcel($condiciones)
	{
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		$ColorVentasBO				= new ColorVentasBO();
		$ColorVentasBO->setEntityManager($this->getEntityManager());
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$criterio_busqueda 		= 'TODOS';
		$busqueda_color 		= 'TODOS';
		$busqueda_estado		= 'TODOS';
		/*
		if (!empty($condiciones['criterio_busqueda'])){
			$VariedadData 		= $this->consultarVariedad($condiciones['criterio_busqueda']);
			$busqueda_color		= $VariedadData->getNombre();
		}//end i
		
		
		if (!empty($condiciones['busqueda_color'])){
			$ColorVentasData 		= $ColorVentasBO->consultar($condiciones['busqueda_color ']);
			$busqueda_color			= $ColorVentasData->getNombre();
		}//end i
		
		if (empty($condiciones['busqueda_estado'])){
			$busqueda_estado		= 'TODOS';
			
		}else
	    {
			$busqueda_estado = $condiciones['busqueda_estado '];
			
		}//end if
		
		
*/
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
		$col_fin 			= $PHPExcelApp->getNameFromNumber(11);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "LISTADO DE VARIEDADES");
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		//------------------------------Registra criterios linea 1--------------------------
		$row				= 2;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(11);
		
		$objRichText = new \PHPExcel_RichText();
		$objRichText->createText('');
		
		$objInventario = $objRichText->createTextRun('  Variedad: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($criterio_busqueda);
		
		$objCalidad = $objRichText->createTextRun('    Color Ventas: ');
		$objCalidad->getFont()->setBold(true);
		$objCalidad->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($busqueda_color);
		
		$objProveedor = $objRichText->createTextRun('   Estado: ');
		$objProveedor->getFont()->setBold(true);
		$objProveedor->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($busqueda_estado);
		
		$objPHPExcel->getActiveSheet()->getCell($col_ini.$row)->setValue($objRichText);
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		//------------------------------ Registro de Fecha de Generacion --------------------------------
		$row				= 3;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(11);
		
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row, "Variedad");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row, " ");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row, "Calidad");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row, "Obtentor");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row, "Bunch");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row, "Color Base");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row, "Color Venta");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row, "Solido");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row, "Real");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row, "Estado");
		
		
		//-------------------------ESTABLECE ANCHO DE COLUMNAS----------------------------
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(11);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(7);
		
		
		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		$objPHPExcel_getActiveSheet=$objPHPExcel->getActiveSheet();
		
		
		$result = $this->listadoExcel($condiciones);
		
		
		$cont_linea = 0;
		foreach($result as $reg){
			
			$cont_linea++;
			$row=$row+1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $reg['id'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['nombre'] );
			if (!empty($reg['url_ficha']))
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'FOTO');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $reg['url_ficha'] );
			}else 
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $reg['calidad'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reg['nombre_obtentor'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $reg['nombre_bunch'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $reg['color_base'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $reg['color_venta'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $reg['solido'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $reg['es_real'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $reg['estado'] );
				
		} //end foreach
		
		//$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		//Margenes
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(11);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_ini.":".$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_BORDE_TODO));
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Listado Variedades');
		
		$PHPExcelApp->save($objPHPExcel, $PHPExcelApp::FORMAT_EXCEL_2007, "Listado_Variedades.xlsx" );
		
	} //end function generarExcel
		
	
	
	
}//end class
