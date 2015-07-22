<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\MarcacionBO;
use Dispo\BO\TransportadoraBO;
use Dispo\Data\TransportadoraData;


class TransportadoraController extends AbstractActionController
{

	public function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
			
			$TransportadoraBO = new TransportadoraBO();
			$TransportadoraBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;			
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
			$transportadora_id = null;

			$opciones = $TransportadoraBO->getComboTodos($transportadora_id, $texto_primer_elemento);

			$response = new \stdClass();
			$response->opciones				= $opciones;
			$response->respuesta_code 		= 'OK';

			$json = new JsonModel(get_object_vars($response));
			return $json;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcomboAction	

	
	
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
		
			$TransportadoraBO				= new TransportadoraBO();
			$TransportadoraBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$condiciones['criterio_busqueda']		= $this->params()->fromPost('criterio_busqueda','');
			$condiciones['estado']					= $this->params()->fromPost('busqueda_estado','');
			$condiciones['sincronizado']			= $this->params()->fromPost('busqueda_sincronizado','');			

			$result 		= $TransportadoraBO->listado($condiciones);
			
			$viewModel->criterio_busqueda	= $condiciones['criterio_busqueda'];
			$viewModel->busqueda_estado				=  \Application\Classes\ComboGeneral::getComboEstado($condiciones['estado'],"&lt;ESTADO&gt;");
			$viewModel->busqueda_sincronizado		= \Application\Classes\ComboGeneral::getComboSincronizado($condiciones['sincronizado'],"&lt;SINCRONIZADO&gt;");
			$viewModel->result				= $result;
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/transportadora/mantenimiento.phtml');
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
			$TransportadoraBO 		= new TransportadoraBO();
			$TransportadoraBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$response = new \stdClass();
			$response->cbo_tipo				= $TransportadoraBO->getComboTipo("", " ");
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
			$TransportadoraBO 				= new TransportadoraBO();
			$TransportadoraBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$transportadora_id		= $json['transportadora_id'];

			$row					= $TransportadoraBO->consultar($transportadora_id, \Application\Constants\ResultType::MATRIZ);

			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_tipo				= $TransportadoraBO->getComboTipo($row['tipo'], " ");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
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
			$TransportadoraData		= new TransportadoraData();
			$TransportadoraBO 		= new TransportadoraBO();
			$TransportadoraBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$TransportadoraData->setId		($json['id']); 
			$TransportadoraData->setNombre	($json['nombre']);
			$TransportadoraData->setTipo	($json['tipo']);
			$TransportadoraData->setEstado	($json['estado']);

			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$TransportadoraData->setUsuarioIngId($usuario_id);
					$result = $TransportadoraBO->ingresar($TransportadoraData);
					break;
					
				case 'M':
					$TransportadoraData->setUsuarioModId($usuario_id);
					$result = $TransportadoraBO->modificar($TransportadoraData);					
					break;
					
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $TransportadoraBO->consultar($json['id'], \Application\Constants\ResultType::MATRIZ);
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
				$response->cbo_tipo				= $TransportadoraBO->getComboTipo($row['tipo'], " ");
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
	
	
}//end controller