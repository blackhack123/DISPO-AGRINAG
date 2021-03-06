<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Dispo\BO\MarcacionBO;
use Zend\View\Model\JsonModel;
use Dispo\BO\VariedadBO;
use Dispo\BO\ColoresBO;
use Dispo\BO\ProductoBO;
use Dispo\BO\GrupoColorBO;
use Dispo\BO\CalidadVariedadBO;
use Dispo\BO\ObtentorBO;
use Dispo\Data\VariedadData;
use Dispo\BO\TamanoBunchBO;
use Dispo\BO\ColorVentasBO;
use Dispo\BO\Dispo\BO;

class VariedadController extends AbstractActionController
{

	public function mantenimientoAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
				
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			//Controla el acceso a la informacion, solo accedera si es administrador
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$respuesta =  $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			$VariedadBO				= new VariedadBO();
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$ColorVentasBO				= new ColorVentasBO();
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$condiciones['criterio_busqueda']		= $this->params()->fromPost('criterio_busqueda','');
			$condiciones['estado']					= $this->params()->fromPost('busqueda_estado','');
			$condiciones['color_ventas_id']			= $this->params()->fromPost('busqueda_color','');
			$condiciones['sincronizado']			= $this->params()->fromPost('busqueda_sincronizado','');

			$result 			= $VariedadBO->listado($condiciones);
			$color_ventas_id 	= null;
			
			$viewModel->criterio_busqueda			=  $condiciones['criterio_busqueda'];
			$viewModel->busqueda_estado				=  \Application\Classes\ComboGeneral::getComboEstado($condiciones['estado'],"&lt;ESTADO&gt;");
			$viewModel->busqueda_color				=  $ColorVentasBO->getCombo($color_ventas_id, "&lt;Seleccione&gt;");
			$viewModel->busqueda_sincronizado		=  \Application\Classes\ComboGeneral::getComboSincronizado($condiciones['sincronizado'],"&lt;SINCRONIZADO&gt;");
			$viewModel->result				= $result;
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/variedad/mantenimiento.phtml');
			return $viewModel;	
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function 

	
	
	public function nuevodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$VariedadBO 			= new VariedadBO();
			$ColoresBO				= new ColoresBO();
			$ProductoBO				= new ProductoBO();
			$GrupoColorBO			= new GrupoColorBO();
			$CalidadVariedadBO		= new CalidadVariedadBO();
			$ObtentorBO				= new ObtentorBO();
			$TamanoBunchBO			= new TamanoBunchBO();
			$ColorVentasBO			= new ColorVentasBO();
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ProductoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoColorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ObtentorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$TamanoBunchBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$colorbase 					= null;
			$calidad_variedad_id		= null;
			$cultivada 					= null;
			$id							=null;
			$solido						= 'S';
			$es_real 					= 'S';
			$obtentor					= null;
			$tamano_bunch_id			= null;
			$color_ventas_id			= null;
			
			$response = new \stdClass();
			$response->cbo_color_base				= $ColoresBO->getCombo($colorbase, "&lt;Seleccione&gt;");
			$response->cbo_color					= $ColoresBO->getCombo($colorbase, "&lt;Seleccione&gt;");
			$response->cbo_color2					= $ColoresBO->getCombo($colorbase, "&lt;Seleccione&gt;");
			$response->cbo_grupo_color_id			= $GrupoColorBO->getComboId($id, "&lt;Seleccione&gt;");
			$response->cbo_calidad_variedad_id		= $CalidadVariedadBO->getComboCalidadVariedad($calidad_variedad_id, "&lt;Seleccione&gt;");
			$response->cbo_solido					= $VariedadBO->getComboSolido($solido, "&lt;Seleccione&gt;");
			$response->cbo_es_real					= $VariedadBO->getComboEsReal($es_real, "&lt;Seleccione&gt;");
			$response->cbo_cultivada				= $VariedadBO->getComboCultivada($cultivada, "&lt;Seleccione&gt;");
			$response->cbo_obtentor_id				= $ObtentorBO->getComboObtentor($obtentor, "&lt;Seleccione&gt;");
			$response->cbo_color_ventas				= $ColorVentasBO->getCombo($color_ventas_id, "&lt;Seleccione&gt;");
			$response->cbo_producto_id				= $ProductoBO->getComboProductoId($id, "&lt;Seleccione&gt;");
			$response->cbo_tamano_bunch				= $TamanoBunchBO->getCombo($tamano_bunch_id, "&lt;Seleccione&gt;");
			$response->cbo_estado					= \Application\Classes\ComboGeneral::getComboEstado("","");
			$response->respuesta_code 				= 'OK';
			$response->respuesta_mensaje			= '';
		
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function nuevodataAction
	
	
	
	public function consultardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$VariedadBO 			= new VariedadBO();
			$ObtentorBO 			= new ObtentorBO();
			$ColoresBO				= new ColoresBO();
			$CalidadVariedadBO		= new CalidadVariedadBO();
			$GrupoColorBO			= new GrupoColorBO();
			$TamanoBunchBO			= new TamanoBunchBO();
			$ColorVentasBO			= new ColorVentasBO();
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ObtentorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoColorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$TamanoBunchBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$variedad_id		= $json['variedad_id'];
	
			$row					= $VariedadBO->consultar($variedad_id, \Application\Constants\ResultType::MATRIZ);
			
			
			$response = new \stdClass();
			$response->row							= $row;
			$response->cbo_color_base				= $ColoresBO->getCombo($row['colorbase'], "&lt;Seleccione&gt;");
			$response->cbo_color					= $ColoresBO->getCombo($row['color'], "&lt;Seleccione&gt;");
			$response->cbo_color2					= $ColoresBO->getCombo($row['color2'], "&lt;Seleccione&gt;");
			$response->cbo_grupo_color_id			= $GrupoColorBO->getComboId($row['grupo_color_id'], "&lt;Seleccione&gt;");
			$response->cbo_calidad_variedad_id		= $CalidadVariedadBO->getComboCalidadVariedad($row['calidad_variedad_id'], "&lt;Seleccione&gt;");
			$response->cbo_solido					= $VariedadBO->getComboSolido($row['solido'], "&lt;Seleccione&gt;");
			$response->cbo_es_real					= $VariedadBO->getComboEsReal($row['es_real'], "&lt;Seleccione&gt;");
			$response->cbo_cultivada				= $VariedadBO->getComboCultivada($row['cultivada'], "&lt;Seleccione&gt;");
			$response->cbo_obtentor_id				= $ObtentorBO->getComboObtentor($row['obtentor_id'], "&lt;Seleccione&gt;");
			$response->cbo_color_ventas				= $ColorVentasBO->getCombo($row['color_ventas_id'], "&lt;Seleccione&gt;");
			$response->cbo_tamano_bunch				= $TamanoBunchBO->getCombo($row['tamano_bunch_id'], "&lt;Seleccione&gt;");
			$response->cbo_estado					= \Application\Classes\ComboGeneral::getComboEstado("","");
			$response->respuesta_code 				= 'OK';
			$response->respuesta_mensaje			= '';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function consultarAction
	
	
	

	public function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$VariedadData			= new VariedadData();
			$VariedadBO 			= new VariedadBO();
			$ObtentorBO 			= new ObtentorBO();
			$ColoresBO 				= new ColoresBO();
			$CalidadVariedadBO 		= new CalidadVariedadBO();
			$TamanoBunchBO			= new TamanoBunchBO();
			$ColorVentasBO			= new ColorVentasBO();
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ObtentorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$TamanoBunchBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$VariedadData->setId					($json['id']); 
			$VariedadData->setNombre				($json['nombre']);
			$VariedadData->setNombreTecnico			($json['nombre_tecnico']);
			$VariedadData->setCalidadVariedadId		($json['calidad_variedad_id']);
			$VariedadData->setColor					($json['color']);
			$VariedadData->setColor2				($json['color2']);
			$VariedadData->setGrupoColorId			($json['grupo_color_id']);
			$VariedadData->setColorBase				($json['colorbase']);
			$VariedadData->setSolido				($json['solido']);
			$VariedadData->setEsReal				($json['es_real']);
			$VariedadData->setEstProductoEspecial	($json['est_producto_especial']);
			$VariedadData->setMensaje				($json['mensaje']);
			$VariedadData->setCultivada				($json['cultivada']);
			$VariedadData->setCicloProd				($json['ciclo_prod']);
			$VariedadData->setObtentorId			($json['obtentor_id']);
			$VariedadData->setColorVentasId			($json['color_ventas_id']);
			$VariedadData->setUrlFicha				($json['url_ficha']);
			$VariedadData->setTamanoBunchId			($json['tamano_bunch_id']);
			$VariedadData->setEstado				($json['estado']);

			switch ($accion)
			{
				case 'I':
					$VariedadData->setUsuarioIngId($usuario_id);
					$VariedadData->setProductoId('ROS');
					$result = $VariedadBO->ingresar($VariedadData);
					break;
					
				case 'M':
					$VariedadData->setUsuarioModId($usuario_id);
					$result = $VariedadBO->modificar($VariedadData);					
					break;
					
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
			
			}else{
				$row	= null;				
			}//end if
			
			//Retorna la informacion resultante por JSON
			$row	= null;
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];				
			if ($row)
			{
				$response->row					= $row;
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
				$response->cbo_color_base		= $ColoresBO->getCombo($row['colorbase'], "&lt;Seleccione&gt;");
			}else{
				$response->row					= null;
				$response->cbo_estado			= '';		
				$response->cbo_color_base		= '';
			}//end if
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function consultarAction	
	
	
	function exportarexcelAction()
	{
		try
		{
			$viewModel 			= new ViewModel();
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$VariedadBO 		= new VariedadBO();
	
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 				= $this->getRequest();
			$criterio_busqueda 	 	= $request->getQuery('criterio_busqueda', "");
			$busqueda_estado  		= $request->getQuery('busqueda_estado', "");
			$busqueda_color  		= $request->getQuery('busqueda_color', "");
	
			$condiciones = array(
					
					"criterio_busqueda"		=> $criterio_busqueda,
					"busqueda_estado"		=> $busqueda_estado,
					"busqueda_color"		=> $busqueda_color
			);
			$result = $VariedadBO->generarExcel($condiciones);
	
			exit;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function exportarexcel2Action
	
	
	function listadodataAction()
	{
		try 
		{
		
			$EntityManagerPlugin = $this->EntityManagerPlugin();
			
			$VariedadBO = new VariedadBO();
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
			
			$request 			= $this->getRequest();
			
			$criterio_busqueda	= $request->getQuery('criterio_busqueda');
			$estado 			= $request->getQuery('estado');
			$color_ventas_id	= $request->getQuery('color_ventas_id');
			
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			
			$VariedadBO->setPage($page);
			$VariedadBO->setLimit($limit);
			$VariedadBO->setSidx($sidx);
			$VariedadBO->setSord($sord);
			
			$condiciones = array(
					"criterio_busqueda"		=> $criterio_busqueda,
					"estado" 				=> $estado,
					"color_ventas_id"		=> $color_ventas_id
			);	
			
			$result = $VariedadBO->listado($condiciones);
			
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["id"] 				= $row["id"];
				$row2["nombre"] 			= trim($row["nombre"]);
				$row2["url_ficha"] 			= $row["url_ficha"];
				$row2["color_base_nombre"] 	= $row["color_base_nombre"];
				$row2["color_venta"] 		= $row["color_venta"];
				$row2["solido"] 			= $row["solido"];
				$row2["es_real"] 			= $row["es_real"];
				$row2["sincronizado"] 		= $row["sincronizado"];
				$row2["fec_sincronizado"] 	= $row["fec_sincronizado"];
				$row2["estado"] 			= $row["estado"];
				
				$response->rows[$i] = $row2;
				$i++;
			}//end foreach
			$tot_reg = $i;
			$response->total 	= ceil($tot_reg/$limit);
			$response->page 	= $page;
			$response->records 	= $tot_reg;
			$json = new JsonModel(get_object_vars($response));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
		
	}//end listadodataAction
	
}//end controller