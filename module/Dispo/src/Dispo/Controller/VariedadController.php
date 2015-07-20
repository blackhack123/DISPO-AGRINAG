<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Dispo\BO\MarcacionBO;
use Zend\View\Model\JsonModel;
use Dispo\BO\VariedadBO;
use Dispo\BO\ColoresBO;
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
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$colorbase = null;
			$response = new \stdClass();
			$response->cbo_color_base		= $ColoresBO->getCombo($colorbase, "&lt;Seleccione&gt;");
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
	
	

		public function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$VariedadData		= new VariedadData();
			$VariedadBO 		= new VariedadBO();
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$VariedadData->setId		($json['id']); 
			$VariedadData->setNombre	($json['nombre']);
			$VariedadData->setColorBase	($json['colorbase']);
			$VariedadData->setEstado	($json['estado']);
			$response = new \stdClass();
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
				$row	= $VariedadBO->consultar($json['id'], \Application\Constants\ResultType::MATRIZ);
			}else{
				$row	= null;				
			}//end if
			
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];				
			if ($row)
			{
				$response->row					= $row;
				$response->cbo_tipo				= $VariedadBO->getComboTipo($row['tipo'], " ");
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			}else{
				$response->row					= null;
				$response->cbo_tipo				= '';
				$response->cbo_estado			= '';				
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
	


	public function consultardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$VariedadBO 			= new VariedadBO();
			$ColoresBO				= new ColoresBO();
			
			$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColoresBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
		
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$variedad		= $json['variedad'];
	
			$row					= $VariedadBO->consultar($variedad, \Application\Constants\ResultType::MATRIZ);
				
			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_color_base		= $ColoresBO->getCombo($row['colobase'], "&lt;Seleccione&gt;");
	
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