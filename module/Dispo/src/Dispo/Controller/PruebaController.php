<?php
namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\TipoCajaBO;



class PruebaController extends AbstractActionController
{
	
	
	public function mantenimientoAction()
	{
		try
		{
			
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$viewModel 				= new ViewModel();
			
			
			$TipoCajaBO = new TipoCajaBO();
			$TipoCajaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			//Controla el acceso a la informacion, solo accedera si es administrador
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$respuesta =  $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
				
			
			$result 					= $TipoCajaBO->consultarTodos();
			$viewModel->result			= $result;
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/prueba/mantenimiento.phtml');
			return $viewModel;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function
	
	
	
}//end controller