<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\InventarioBO;
use Dispo\Data\InventarioData;


class InventarioController extends AbstractActionController
{

	/*-----------------------------------------------------------------------------*/
	public function getComboDataGridAction()
	/*-----------------------------------------------------------------------------*/
	{
		try{
			$EntityManagerPlugin	= $this->EntityManagerPlugin();
			$InventarioBO 		= new InventarioBO();
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$request 		= $this->getRequest();
			$opciones		= utf8_encode($InventarioBO->getComboDataGrid());
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
	
	
	
	function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$InventarioBO = new InventarioBO();
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$texto_primer_elemento	= $json['texto_primer_elemento'];
			$inventario_id			= $json['inventario_id'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
	
			$opciones 	= $InventarioBO->getCombo($inventario_id, $texto_primer_elemento);
				
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
	
	
}//end controller