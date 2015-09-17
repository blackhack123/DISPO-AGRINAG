<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GrupoDispoCabBO;
use Dispo\BO\GrupoPrecioCabBO;


class GrupoclienteController extends AbstractActionController
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
			$viewModel->setTemplate('dispo/grupocliente/mantenimiento.phtml');
			return $viewModel;	
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function 


	
	public function initcontrolsAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$opcion						= $json['opcion'];
				
			switch ($opcion)
			{
				case 'panel-grupo-clientes':
						
					$GrupoDispoCabBO 	= new GrupoDispoCabBO();
					$GrupoPrecioCabBO 	= new GrupoPrecioCabBO();
						
					$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						
					$grupo_dispo_1er_elemento	= $json['grupo_dispo_1er_elemento'];
					$grupo_precio_1er_elemento	= $json['grupo_precio_1er_elemento'];
					$grupo_dispo_cab_id		= null;
					$grupo_precio_cab_id	= null;
						
					$grupo_dispo_opciones 	= $GrupoDispoCabBO->getComboGrupoDispo($grupo_dispo_cab_id, $grupo_dispo_1er_elemento);
					$grupo_precio_opciones	= $GrupoPrecioCabBO->getComboGrupoPrecio($grupo_precio_cab_id,$grupo_precio_1er_elemento);
						
					$response = new \stdClass();
					$response->grupo_dispo_opciones		= $grupo_dispo_opciones;
					$response->grupo_precio_opciones	= $grupo_precio_opciones;
					$response->respuesta_code 			= 'OK';
					break;
						
					/*				case 'panel-control-mantenimiento':
						$InventarioBO		= new InventarioBO();
						$CalidadBO			= new CalidadBO();
							
						$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
							
						$inventario_1er_elemento	= $json['inventario_1er_elemento'];
						$inventario_id				= null;
						$calidad_1er_elemento	= $json['calidad_1er_elemento'];
						$calidad_id				= null;
							
						$inventario_opciones  	= $InventarioBO->getCombo($inventario_id, $inventario_1er_elemento);
						$calidad_opciones 	 	= $CalidadBO->getComboCalidad($calidad_id, $calidad_1er_elemento);
							
						$response = new \stdClass();
						$response->inventario_opciones		= $inventario_opciones;
						$response->calidad_opciones			= $calidad_opciones;
						$response->respuesta_code 			= 'OK';
						break;
						*/
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