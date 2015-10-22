<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Application\Classes\PHPExcelApp;
use Dispo\DAO\GrupoDispoCabDAO;
use Dispo\DAO\DispoDAO;
use Dispo\DAO\GrupoDispoDetDAO;
use Dispo\Data\GrupoDispoCabData;
use Dispo\Data\GrupoDispoDetData;


class GrupoDispoCabBO extends Conexion
{

	/**
	 * 
	 * @param string $grupodispo_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboGrupoDispo($grupodispo_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());

		$result = $GrupoDispoCabDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $grupodispo_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


	/**
	 * 
	 * @param integer $grupo_dispo_cab_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorInventario($grupo_dispo_cab_id, $inventario_id, $calidad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
	
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
	
		$result = $GrupoDispoCabDAO->consultarPorInventario($inventario_id, $calidad_id);
	
		$opciones_dispo = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$grupo_dispo_cab_id,  $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones_dispo;
	}//end function getComboPorInventario
	
	
	/**
	 * 
	 * @param array $condiciones  (grupo_dispo_cab_id, color_ventas_id, calidad_variedad_id, cadena_color_ventas_ids, cadena_calidad_variedad_ids)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$GrupoDispoCabDAO 	= new GrupoDispoCabDAO();
		$DispoDAO 			= new DispoDAO();
		
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$DispoDAO->setEntityManager($this->getEntityManager());
		
		/**
		 * Se obtiene el registro CABECERA de la DISPO X GRUPO
		 */
		$reg_grupoDispoCab = $GrupoDispoCabDAO->consultarArray($condiciones['grupo_dispo_cab_id']);
		if (empty($reg_grupoDispoCab))
		{
			return null;
		}//end if
		
		
		if (!array_key_exists('cadena_color_ventas_ids', $condiciones))		{$condiciones['cadena_color_ventas_ids']='';}
		if (!array_key_exists('cadena_calidad_variedad_ids', $condiciones))	{$condiciones['cadena_calidad_variedad_ids']='';}
		
		/**
		 * Se obtiene los registro de la DISPO GENERAL  (UNIVERSO)
		 */
		$condiciones2 = array(
				"inventario_id"					=> $reg_grupoDispoCab['inventario_id'],
				"proveedor_id"					=> null,
				"clasifica"						=> $reg_grupoDispoCab['clasifica_fox'],
				"color_ventas_id"				=> $condiciones['color_ventas_id'],
				"calidad_variedad_id" 			=> $condiciones['calidad_variedad_id'],
				"cadena_color_ventas_ids"		=> $condiciones['cadena_color_ventas_ids'],
				"cadena_calidad_variedad_ids"	=> $condiciones['cadena_calidad_variedad_ids']
		);
		$result_dispo = $DispoDAO->listado($condiciones2);
		
		/**
		 * Se obtiene los registros de la DISPO POR GRUPO
		 */
		$condiciones2 = array(
				"grupo_dispo_cab_id"			=> $condiciones['grupo_dispo_cab_id'],
				"color_ventas_id"				=> $condiciones['color_ventas_id'],
				"calidad_variedad_id" 			=> $condiciones['calidad_variedad_id'],
				"cadena_color_ventas_ids"		=> $condiciones['cadena_color_ventas_ids'],
				"cadena_calidad_variedad_ids"	=> $condiciones['cadena_calidad_variedad_ids']
		);		
		$result_dispo_grupo = $GrupoDispoCabDAO->listado($condiciones2);
		

		/**
		 * Se realizar el proceso de consolidacion de informacion
		 */
		//Indexar el RESULT de la DISPO GENERAL
		$result = null;
		foreach($result_dispo as $reg)
		{
			$reg_new['producto_id'] 	= $reg['producto_id'];
			$reg_new['variedad_id'] 	= $reg['variedad_id'];
			$reg_new['variedad'] 		= trim($reg['variedad']);
			$reg_new['tallos_x_bunch'] 	= $reg['tallos_x_bunch'];
			$reg_new['color_ventas_nombre'] 	= $reg['color_ventas_nombre'];
			$reg_new['dgen_40'] 		= $reg['40'];
			$reg_new['dgen_50'] 		= $reg['50'];
			$reg_new['dgen_60'] 		= $reg['60'];
			$reg_new['dgen_70'] 		= $reg['70'];
			$reg_new['dgen_80'] 		= $reg['80'];
			$reg_new['dgen_90'] 		= $reg['90'];
			$reg_new['dgen_100'] 		= $reg['100'];
			$reg_new['dgen_110'] 		= $reg['110'];
			$reg_new['dgru_40']			= 0;
			$reg_new['dgru_50']			= 0;
			$reg_new['dgru_60']			= 0;
			$reg_new['dgru_70']			= 0;
			$reg_new['dgru_80']			= 0;
			$reg_new['dgru_90']			= 0;
			$reg_new['dgru_100']		= 0;
			$reg_new['dgru_110']		= 0;
			$reg_new['existe']			= 0;
			$result[$reg['producto_id'].'-'.$reg['variedad_id'].'-'.$reg['tallos_x_bunch']] = $reg_new;
		}//end foreach
		
		//Completa los campos del RESULT con la DISPO POR GRUPO
		foreach($result_dispo_grupo as $reg)
		{
			$reg_result = &$result[$reg['producto_id'].'-'.$reg['variedad_id'].'-'.$reg['tallos_x_bunch']]; 
			
			$reg_result['dgru_40']	= $reg['40'];
			$reg_result['dgru_50']	= $reg['50'];
			$reg_result['dgru_60']	= $reg['60'];
			$reg_result['dgru_70']	= $reg['70'];
			$reg_result['dgru_80']	= $reg['80'];
			$reg_result['dgru_90']	= $reg['90'];
			$reg_result['dgru_100']	= $reg['100'];
			$reg_result['dgru_110']	= $reg['110'];
			$reg_result['existe']	= 1;
		}//end foreach
		
		return $result;
	}//end function listado

	
	
	/**
	 * 
	 * @param  \Application\Dispo\Data\GrupoDispoDetData $GrupoDispoDetData
	 * @throws Exception
	 * @return string
	 */
	public function registrarStock($GrupoDispoDetData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoDispoDetDAO = new GrupoDispoDetDAO();
			$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
		
			$GrupoDispoDetDAO->registrarStock($GrupoDispoDetData);
		
			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function registrarStock

	
	
	
	public function consultarCabecera($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$reg = $GrupoDispoCabDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultarCabecera
		
	
	
	/**
	 * Ingresar
	 *
	 * @param GrupoDispoCabData $GrupoDispoCabData
	 * @return array
	 */
	function registrarPorAccion($accion, GrupoDispoCabData $GrupoDispoCabData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoDispoCabDAO = new GrupoDispoCabDAO();
			$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
			
			switch($accion)
			{
				case 'I':
					$id = $GrupoDispoCabDAO->ingresar($GrupoDispoCabData);
					$result['id']	 	= $id;					
					break;
					
				case 'M':
					$id = $GrupoDispoCabDAO->modificar($GrupoDispoCabData);
					$result['id']		= $id;					
					break;
			}//end switch
			
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function registrar	
	

	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @return array
	 */
	function listadoNoAsignadas($grupo_dispo_cab_id)
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoDispoCabDAO->listadoNoAsignadas($grupo_dispo_cab_id);
		return $result;
	}//end function listadoNoAsignadas
	
	
	/**
	 *
	 * @param array $condiciones (grupo_dispo_cab_id);
	 * @return array
	 */	
	function listadoAsignadas($condiciones)
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoDispoCabDAO->listadoAsignadas($condiciones);
		return $result;
	}//end function listadoAsignadas	

	

	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @param string $grado_id
	 * @param string $cadena_color_ventas_ids
	 * @param string $cadena_calidad_variedad_ids
	 * @param float $porcentaje
	 * @param int $valor
	 * @param int $usuario_id
	 * @throws Exception
	 * @return array
	 */
	function grabarPorGrupoPorGrado($grupo_dispo_cab_id, $grado_id, $cadena_color_ventas_ids, 
									$cadena_calidad_variedad_ids, $porcentaje, $valor, $usuario_id)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoDispoDetDAO 	= new GrupoDispoDetDAO();
			$GrupoDispoDetData 	= new GrupoDispoDetData();
			$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
		
			$condiciones['grupo_dispo_cab_id']			= $grupo_dispo_cab_id;
			$condiciones['color_ventas_id']				= null;
			$condiciones['calidad_variedad_id']			= null;
			$condiciones['cadena_color_ventas_ids']		= $cadena_color_ventas_ids;
			$condiciones['cadena_calidad_variedad_ids']	= $cadena_calidad_variedad_ids;
			$result = $this->listado($condiciones);
			$campo_grado_dispogen = "dgen_".$grado_id;
			
			
			foreach($result as $reg)
			{
				$GrupoDispoDetData->setGrupoDispoCabId	($grupo_dispo_cab_id);
				$GrupoDispoDetData->setProductoId		($reg['producto_id']);
				$GrupoDispoDetData->setVariedadId		($reg['variedad_id']);
				$GrupoDispoDetData->setGradoId			($grado_id);
				$GrupoDispoDetData->setTallosXBunch		($reg['tallos_x_bunch']);
				if ($porcentaje!=0)
				{
					$cantidad_bunch = floor($reg[$campo_grado_dispogen]*$porcentaje/100);
				}else if ($valor != 0){
					$cantidad_bunch = $valor;
				}else{
					$cantidad_bunch = 0;
				}//end if
				if ($cantidad_bunch > $reg[$campo_grado_dispogen])
				{
					$cantidad_bunch = $reg[$campo_grado_dispogen];
				}//end if
				$GrupoDispoDetData->setCantidadBunch($cantidad_bunch);
				$GrupoDispoDetData->setCantidadBunchDisponible($cantidad_bunch);
				$GrupoDispoDetData->setUsuarioModId($usuario_id);
				$GrupoDispoDetData->setUsuarioIngId($usuario_id);
				
				$GrupoDispoDetDAO->registrar($GrupoDispoDetData);
			}//end foreach

			
			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function grabarPorGrupoPorGrado
	
	
	
	/***
	 *
	 * @param array $condiciones
	 */
	public function generarExcel($condiciones)
	{
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		
		$GrupoDispoCabDAO			= new GrupoDispoCabDAO();
		
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$texto_grupo_dispo_cab_id	= 'TODOS';
		$texto_color_ventas_id		= 'TODOS';
		$texto_calidad_variedad_id	= 'TODOS';
		
		
		if (!empty($condiciones['grupo_dispo_cab_id'])){
			$texto_grupo_dispo_cab_id	= $condiciones['grupo_dispo_cab_id'];
		}//end if
		
		if (!empty($condiciones['color_ventas_id'])){
			$texto_color_ventas_id		= $condiciones['color_ventas_id'];
		}//end if
		
		if (!empty($condiciones['calidad_variedad_id'])){
			$texto_calidad_variedad_id	= $condiciones['calidad_variedad_id'];
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
		$col_fin 			= $PHPExcelApp->getNameFromNumber(19);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Disponibilidad Por Grupo");
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		//------------------------------Registra criterios linea 1--------------------------
		$row				= 2;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(19);
		
		$objRichText = new \PHPExcel_RichText();
		$objRichText->createText('');
		
		$objInventario = $objRichText->createTextRun('     Grupo: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_grupo_dispo_cab_id);
		
		
		$objInventario = $objRichText->createTextRun('     Color: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_color_ventas_id);
		
		
		$objInventario = $objRichText->createTextRun('     Calidad: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_calidad_variedad_id);
		
		
		$objPHPExcel->getActiveSheet()->getCell($col_ini.$row)->setValue($objRichText);
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		
		
		//------------------------------ Registro de Fecha de Generacion --------------------------------
		$row				= 3;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(19);
		
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Generado: ".\Application\Classes\Fecha::getFechaHoraActualServidor());
		
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		
		//---------------------------IMPRIME TITULO DE COLUMNA-----------------------------
		
		$row 				= $row + 1;
		$row_detalle_ini 	= $row;
		$row_custom			= $row_detalle_ini + 1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row_custom, "Nro");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row_custom, "Id");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row_custom, "Variedad");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row_custom, "Color");
		
		$objPHPExcel->getActiveSheet()->mergeCells('E4:F4');
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row, "40");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row_custom, "GRUPO");
		
		$objPHPExcel->getActiveSheet()->mergeCells('G4:H4');
		$objPHPExcel->getActiveSheet()->getStyle('G4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row, "50");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row_custom, "GRUPO");
		
		
		$objPHPExcel->getActiveSheet()->mergeCells('I4:J4');
		$objPHPExcel->getActiveSheet()->getStyle('I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row, "60");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row_custom, "GRUPO");
		
		
		$objPHPExcel->getActiveSheet()->mergeCells('K4:L4');
		$objPHPExcel->getActiveSheet()->getStyle('K4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row, "70");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row_custom, "GRUPO");
		
		$objPHPExcel->getActiveSheet()->mergeCells('M4:N4');
		$objPHPExcel->getActiveSheet()->getStyle('M4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$row, "80");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$row_custom, "GRUPO");
		
		$objPHPExcel->getActiveSheet()->mergeCells('O4:P4');
		$objPHPExcel->getActiveSheet()->getStyle('O4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$row, "90");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$row_custom, "GRUPO");
		
		$objPHPExcel->getActiveSheet()->mergeCells('Q4:R4');
		$objPHPExcel->getActiveSheet()->getStyle('Q4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$row, "100");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$row_custom, "GRUPO");
		
		$objPHPExcel->getActiveSheet()->mergeCells('S4:T4');
		$objPHPExcel->getActiveSheet()->getStyle('S4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$row, "110");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$row_custom, "GENERAL");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$row_custom, "GRUPO");
		
		
		//----------------------AUTO DIMENSIONAR CELDAS DE ACUERDO AL CONTENIDO---------------
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
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(14)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(15)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(16)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(17)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(18)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(19)->setAutoSize(true);
		
		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row_custom)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		
		$objPHPExcel_getActiveSheet=$objPHPExcel->getActiveSheet();
		
	
		//----------------------CONSULTA LOS REGISTROS A EXPORTAR---------------
		$result = $this->listado($condiciones);
		
		
		$cont_linea = 0;
		$row		= 5;
		
		foreach($result as $reg){
			$reg['variedad_id'] 			= trim($reg['variedad_id']);
			$reg['variedad'] 				= trim($reg['variedad']);
			$reg['color_ventas_nombre'] 	= trim($reg['color_ventas_nombre']);
				

			$cont_linea++;
			$row		= $row + 1;
			
				
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $reg['variedad_id'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['variedad'] );
			if ($reg['tallos_x_bunch']==25)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['variedad'] );
			}else{
				$objRichText = new \PHPExcel_RichText();
				$objRichText->createText($reg['variedad'] );
			
				$objInventario = $objRichText->createTextRun(' ('.$reg['tallos_x_bunch'].')');
				$objInventario->getFont()->setBold(true);
				$objInventario->getFont()->setItalic(true);
			
				$col_variedad 			= $PHPExcelApp->getNameFromNumber(2);
				$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\Application\Classes\PHPExcelApp::COLOR_ORANGE));
				$objPHPExcel->getActiveSheet()->getCell($col_variedad.$row)->setValue($objRichText);
				//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['variedad'] );
			}//end if
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $reg['color_ventas_nombre'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $reg['dgen_40'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reg['dgru_40'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $reg['dgen_50'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $reg['dgru_50'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $reg['dgen_60'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $reg['dgru_60'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $reg['dgen_70'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $reg['dgru_70'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $reg['dgen_80'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $reg['dgru_80'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $reg['dgen_90'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $reg['dgru_90'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $reg['dgen_100'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $reg['dgru_100'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $reg['dgen_110'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $reg['dgru_100'] );
			
			
		}// end foreach
		
		
		//Margenes
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(19);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_ini.":".$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_BORDE_TODO));
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Disponibilidad  por Grupo');
		
		$PHPExcelApp->save($objPHPExcel, $PHPExcelApp::FORMAT_EXCEL_2007, "Dispo Grupo.xlsx" );
	}
	
}//end class
