<?php

namespace Seguridad\BO;

use Seguridad\DAO\UsuarioDAO;
use Application\Classes\PHPExcelApp;
use Seguridad\DAO\UsuarioEmpresaSucursalDAO;
use Application\Classes\Conexion;
use Seguridad\Data\UsuarioData;
use Seguridad\DAO\Seguridad\DAO;

class UsuarioBO extends Conexion{
	private $page		= null;
	private	$limit		= null;
	private $sidx		= null;
	private $sord		= null;

	function setPage($valor)					{$this->page = $valor;}
	function setLimit($valor)					{$this->limit = $valor;}
	function setSidx($valor)					{$this->sidx = $valor;}
	function setSord($valor)					{$this->sord = $valor;}

	function getPage()					{return $this->page;}
	function getLimit()					{return $this->limit;}
	function getSidx()					{return $this->sidx;}
	function getSord()					{return $this->sord;}
	
	
	function login($usuario, $clave, $ipAcceso, $nombreHost, $AgenteUsuario)
	{
		$UsuarioDAO = new UsuarioDAO;
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$resultDatosUsuario = $UsuarioDAO->login($usuario, $clave, $ipAcceso, $nombreHost, $AgenteUsuario);
		return $resultDatosUsuario;
	}//end function login
	
	
	
	function encriptar($clave)
	{
		$UsuarioDAO = new UsuarioDAO;
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$clave_encriptada = $UsuarioDAO->encriptar($clave);
		return $clave_encriptada;
	}//end function encriptar
	
	
	
	function usuarioencriptar($username, $clave)
	{
		$UsuarioDAO = new UsuarioDAO;
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());

		$clave_encriptada = $UsuarioDAO->encriptar($clave);

		$result = $UsuarioDAO->usuarioencriptar($username, $clave_encriptada);

		return $result;
		exit;
	}//end function usuarioencriptar
	
	
	/**
	 *
	 * @param string $usuario_vendedor_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodosVendedores($usuario_vendedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$UsuarioDAO = new UsuarioDAO();
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$result = $UsuarioDAO->consultarTodosVendedores();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $usuario_vendedor_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodosVendedores
	
	
	/**
	 *
	 * @param string $usuario_vendedor_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboVendedoresAdmin($usuario_vendedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$UsuarioDAO = new UsuarioDAO();
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$result = $UsuarioDAO->consultarVendedoresAdmin();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $usuario_vendedor_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodosVendedores
	
	
	
	/**
	 * 
	 * @param string $cliente_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorCliente($cliente_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$UsuarioDAO = new UsuarioDAO();
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$result = $UsuarioDAO->consultarPorCliente($cliente_id);
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre_completo', $cliente_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;		
	}//end function getComboPorCliente

	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\UsuarioData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$reg = $UsuarioDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar

	
	
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
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$result = $UsuarioDAO->listado($condiciones);
		return $result;
	}//end function listado
	

	
	
	/**
	 * Ingresar
	 * 
	 * @param UsuarioData $UsuarioData
	 * @return array
	 */
	function ingresar(UsuarioData $UsuarioData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$UsuarioDAO = new UsuarioDAO();
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			$UsuarioData2 = $UsuarioDAO->consultarDuplicado('I', $UsuarioData->getId(), $UsuarioData->getNombre(), $UsuarioData->getUsername());
			if (!empty($UsuarioData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $UsuarioDAO->ingresar($UsuarioData);
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
	 * @param UsuarioData $UsuarioData
	 * @return array
	 */
	function modificar(UsuarioData $UsuarioData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$UsuarioDAO = new UsuarioDAO();
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			//$UsuarioData2 = $UsuarioDAO->consultar($UsuarioData->getId());
			$result = $UsuarioDAO->consultarDuplicado('M',$UsuarioData->getId(), $UsuarioData->getNombre(), $UsuarioData->getUsername());
			$id=		$UsuarioData->getId();
			$nombre=	$UsuarioData->getNombre();
			$username=	$UsuarioData->getUsername();
			if (!empty($result))
			{
				
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
				
				$id = $UsuarioDAO->modificar($UsuarioData);
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
	 * 
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function desvincularGrupoDispo($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->desvincularGrupoDispo($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo	
	
	
	
	/**
	 *
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function vincularGrupoDispo($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->vincularGrupoDispo($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo	
	

	
	
	/**
	 *
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function vincularGrupoPrecio($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->vincularGrupoPrecio($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo
	
	
	
	/**
	 *
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function desvincularGrupoPrecio($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->desvincularGrupoPrecio($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo
	
	

	function actualizarEstadoEnviarDispoPorGrupoDispo($grupo_dispo_cab_id ,$estado_enviar_dispo)
	{
		$UsuarioDAO	= new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$result = $UsuarioDAO->actualizarEstadoEnviarDispoPorGrupoDispo($grupo_dispo_cab_id, $estado_enviar_dispo);
		
		return $result;
	}//end function actualizarEstadoEnviarDispo
	
	

	/***
	 *
	 * @param array $condiciones
	 */
	public function generarExcel($condiciones)
	{
		
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		
		$UsuarioDAO			= new UsuarioDAO();
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$texto_criterio_busqueda	= '';
		$texto_estado 				= 'TODOS';
		$texto_perfil				= 'TODOS';
		
		if (!empty($condiciones['criterio_busqueda'])){
			$texto_criterio_busqueda	= $condiciones['criterio_busqueda'];
		}//end if
		
		switch ($condiciones['estado'])
		{
			case 'A':
				$texto_estado		=  'ACTIVO';
				break;
		
			case 'I':
				$texto_estado		=  'INACTIVO';
				break;
		
		}//end switch
		
		
		switch ($condiciones['perfil_id'])
		{
			case '2':
				$texto_perfil		=  'VENDEDOR';
				break;
		
			case '3':
				$texto_perfil		=  'ADMINISTRADOR';
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
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Lista de Usuarios");
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		//------------------------------Registra criterios linea 1--------------------------
		$row				= 2;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		
		$objRichText = new \PHPExcel_RichText();
		$objRichText->createText('');
		
		$objCriterio = $objRichText->createTextRun('Criterio: ');
		$objCriterio->getFont()->setBold(true);
		$objCriterio->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_criterio_busqueda);
		
		
		$objEstado = $objRichText->createTextRun('    Estado: ');
		$objEstado->getFont()->setBold(true);
		$objEstado->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_estado);
		
		
		$objPerfil = $objRichText->createTextRun('     Perfil: ');
		$objPerfil->getFont()->setBold(true);
		$objPerfil->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_perfil);
		
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row, "Nombre");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row, "Usuario");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row, "Login Fox");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row, "Correo");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row, "Perfil");

		
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
		$result = $this->listado($condiciones);
		
		
		$cont_linea = 0;
		foreach($result as $reg)
		{
				
			$reg['nombre'] 		= trim($reg['nombre']);
			$reg['username'] 	= trim($reg['username']);
			$reg['login_fox']	= trim($reg['login_fox']);
			$reg['email']	= trim($reg['email']);
				
			$cont_linea++;
			$row=$row+1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $reg['nombre'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['username']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $reg['login_fox']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $reg['email']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reg['perfil_nombre']);
			
		}// end foreach
		
		
		//Margenes
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(5);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_ini.":".$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_BORDE_TODO));
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Listado Usuarios');
		
		$PHPExcelApp->save($objPHPExcel, $PHPExcelApp::FORMAT_EXCEL_2007, "ListadoUsuarios.xlsx" );
		
		
		
	}//end function generarExcel
	
	
}//end class UsuarioBO
