<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\TipoCajaBO;
use Dispo\Data\TipoCajaData;
use Dispo\BO\GrupoPrecioCabBO;
use Dispo\BO\VariedadBO;
use Dispo\BO\GradoBO;


class PrecioController extends AbstractActionController
{


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
			$viewModel->setTemplate('dispo/precio/panel.phtml');
			return $viewModel;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end function panelAction



	public function initcontrolsAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$GrupoPrecioCabBO 	= new GrupoPrecioCabBO();

			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$opcion						= $json['opcion'];

			switch ($opcion)
			{
				case 'panel-precio':
					$grupo_dispo_1er_elemento	= $json['grupo_dispo_1er_elemento'];
					$tipo_precio_1er_elemento	= '';					
					$grupo_precio_cab_id		= null;
					$variedad_id				= null;
					$grado_id					= null;
						
					$grupo_precio_opciones 	= $GrupoPrecioCabBO->getComboGrupoPrecio($grupo_precio_cab_id, $grupo_dispo_1er_elemento);
					$tipo_precio_opciones	= $GrupoPrecioCabBO->getComboTipoPrecio('', $tipo_precio_1er_elemento);
					//$variedad_opciones		= $VariedadBO->getCombo($variedad_id);
					
					$response = new \stdClass();
					$response->grupo_precio_opciones	= $grupo_precio_opciones;
					$response->tipo_precio_opciones		= $tipo_precio_opciones;
					$response->respuesta_code 			= 'OK';
					break;

			}//end switch
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function initcontrolsAction	
}//end controller