<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ClienteBO;

class BuscadorController extends AbstractActionController
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
		
		
			
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/buscador/mantenimiento.phtml');
			return $viewModel;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function 
	
	
	
	public function consultarclientefacturaAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$ClienteBO = new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 			= 	$this->getRequest();
			$cliente_factura_id	= 	$request-> getQuery('term');
				
			//$term  = $request->getRequest('term');
				
			$condiciones = array(
					"cliente_factura_id"	=> $cliente_factura_id,
			);
			$result = $ClienteBO->ConsultarClienteFactura($condiciones);
			//$response = new \stdClass();
			$i=0;
			$result2 = null;
			foreach($result as $row){
				$row2['id'] 			= $row['id'];
				$row2['value'] 			= trim($row['nombre']);
				//$row2['nombre'] 		= trim($row['nombre']);
				$result2[] 				= $row2;
			}//end foreach
			$data = new JsonModel($result2);
			return $data;
				
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	
	
	}//end function consultarClienteFacturaAction
	
}//end controller