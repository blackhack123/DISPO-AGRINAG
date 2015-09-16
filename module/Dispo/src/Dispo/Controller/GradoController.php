<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GradoBO;
use Dispo\Data\GradoData;


class GradoController extends AbstractActionController
{

	/*-----------------------------------------------------------------------------*/
	public function getComboDataGridAction()
	/*-----------------------------------------------------------------------------*/
	{
		try{
			$EntityManagerPlugin	= $this->EntityManagerPlugin();
			$GradoBO 		= new GradoBO();
			$GradoBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$request 		= $this->getRequest();
			$opciones		= utf8_encode($GradoBO->getComboDataGrid());
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