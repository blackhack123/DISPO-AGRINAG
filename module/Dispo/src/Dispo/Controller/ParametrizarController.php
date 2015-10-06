<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ParametrizarBO;

class ParametrizarController extends AbstractActionController
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
			
			$ParametrizarBO				= new ParametrizarBO();
			$ParametrizarBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			
			$condiciones	= null;
			$result 		= $ParametrizarBO->listado($condiciones);
			
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->result				= $result;
			$viewModel->setTemplate('dispo/parametrizar/mantenimiento.phtml');
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
				
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			$condiciones	= null;
			
			$response = new \stdClass();
			$response->cbo_tipo						= \Application\Classes\ComboGeneral::getComboTipo("","");
			
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
			$ParametrizarBO 			= new ParametrizarBO();
				
			$ParametrizarBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$id		= $json['id'];
	
			$row					= $ParametrizarBO->consultar($id, \Application\Constants\ResultType::MATRIZ);
				
				
			$response = new \stdClass();
			$response->row						= $row;
			$response->cbo_tipo					= \Application\Classes\ComboGeneral::getComboTipo("","");
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';
	
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
	}//end function consultardataAction
	

}//end controller