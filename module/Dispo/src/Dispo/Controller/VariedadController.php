<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Dispo\BO\MarcacionBO;
use Zend\View\Model\JsonModel;
use Dispo\BO\VariedadBO;
use Dispo\BO\ColoresBO;
use Dispo\BO\CalidadBO;
use Dispo\BO\ObtentorBO;
use Dispo\Data\VariedadData;


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
			
			$condiciones['criterio_busqueda']		= $this->params()->fromPost('criterio_busqueda','');
			$condiciones['estado']					= $this->params()->fromPost('busqueda_estado','');
			$condiciones['sincronizado']			= $this->params()->fromPost('busqueda_sincronizado','');

			$result 		= $VariedadBO->listado($condiciones);
			
			$viewModel->criterio_busqueda			= $condiciones['criterio_busqueda'];
			$viewModel->busqueda_estado				=  \Application\Classes\ComboGeneral::getComboEstado($condiciones['estado'],"&lt;ESTADO&gt;");
			$viewModel->busqueda_sincronizado		= \Application\Classes\ComboGeneral::getComboSincronizado($condiciones['sincronizado'],"&lt;SINCRONIZADO&gt;");
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
			$CalidadBO				= new CalidadBO();
			$ObtentorBO				= new ObtentorBO();
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ObtentorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$colorbase 		= null;
			$calidad_id		= null;
			$solido			= 'S';
			$es_real 		= 'S';
			$obtentor		= null;
			$response = new \stdClass();
			$response->cbo_color_base		= $ColoresBO->getCombo($colorbase, "&lt;Seleccione&gt;");
			$response->cbo_calidad_id		= $CalidadBO->getComboCalidad($calidad_id, "&lt;Seleccione&gt;");
			$response->cbo_solido			= $VariedadBO->getComboSolido($solido, "&lt;Seleccione&gt;");
			$response->cbo_es_real			= $VariedadBO->getComboEsReal($es_real, "&lt;Seleccione&gt;");
			$response->cbo_obtentor_id		= $ObtentorBO->getCombo($ObtentorBO, "&lt;Seleccione&gt;");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado("","");
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';
		
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
			$ColoresBO				= new ColoresBO();
			$CalidadBO				= new CalidadBO();
				
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$variedad_id		= $json['variedad_id'];
	
			$row					= $VariedadBO->consultar($variedad_id, \Application\Constants\ResultType::MATRIZ);
			
			$colorbase 		= null;
			$calidad_id		= null;
			$solido			= null;
			$es_real 		= null;
			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_color_base		= $ColoresBO->getCombo($row['colorbase'], "&lt;Seleccione&gt;");
			$response->cbo_calidad_id		= $CalidadBO->getComboCalidad($row['calidad_id'], "&lt;Seleccione&gt;");
			$response->cbo_solido			= $VariedadBO->getComboSolido($row['solido'], "&lt;Seleccione&gt;");
			$response->cbo_es_real			= $VariedadBO->getComboEsReal($row['es_real'], "&lt;Seleccione&gt;");
			$response->cbo_obtentor_id		= $VariedadBO->getComboObtentor($row['obtentor_id'], "&lt;Seleccione&gt;");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado("","");
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';
	
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
			$VariedadData		= new VariedadData();
			$VariedadBO 		= new VariedadBO();
			$ColoresBO 			= new ColoresBO();
			$CalidadBO 			= new CalidadBO();
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$VariedadData->setId					($json['id']); 
			$VariedadData->setNombre				($json['nombre']);
			$VariedadData->setColorBase				($json['colorbase']);
			$VariedadData->setCalidadId				($json['calidad_id']);
			$VariedadData->setSolido				($json['solido']);
			$VariedadData->setEsReal				($json['es_real']);
			$VariedadData->setCicloProd				($json['ciclo_prod']);
			$VariedadData->setObtentorId			($json['obtentor_id']);
			$VariedadData->setEstProductoEspecial	($json['est_producto_especial']);
			$VariedadData->setEstado				($json['estado']);

			switch ($accion)
			{
				case 'I':
					$VariedadData->setUsuarioIngId($usuario_id);
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
	
		
}//end controller