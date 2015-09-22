<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\TipoCajaBO;
use Dispo\Data\TipoCajaData;


class TipocajaController extends AbstractActionController
{

	/*-----------------------------------------------------------------------------*/
	public function getComboDataGridAction()
	/*-----------------------------------------------------------------------------*/
	{
		try{
			$EntityManagerPlugin	= $this->EntityManagerPlugin();
			$TipoCajaBO 		= new TipoCajaBO();
			$TipoCajaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$request 		= $this->getRequest();
			$opciones		= utf8_encode($TipoCajaBO->getComboDataGrid());
			$response = $this->getResponse();
			$response->setContent($opciones);
			return $response;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end  function getComboDataGridAction	
	
	
	
	
	public function panelAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			//Controla el acceso a la informacion, solo accedera si es administrador
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$respuesta =  $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
				
				
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/tipocaja/panel.phtml');
			return $viewModel;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end function panelAction
	
}//end controller