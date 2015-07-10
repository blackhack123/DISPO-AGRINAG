<?php
namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ClienteBO;


class ClienteController extends AbstractActionController
{

	public function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
			
			$ClienteBO = new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();  //Solo el Vendedor Puede hacer este procedimiento

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;			
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id 				= null;

			$opciones = $ClienteBO->getCombo($cliente_id, $texto_primer_elemento);

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