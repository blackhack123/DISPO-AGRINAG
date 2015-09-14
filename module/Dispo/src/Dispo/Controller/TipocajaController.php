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
	
	
}//end controller