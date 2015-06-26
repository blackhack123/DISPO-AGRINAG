<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\DispoBO;
use Dispo\BO\MarcacionBO;


class DisponibilidadController extends AbstractActionController
{

	/**
	 * 
	 * Se pregunta si tiene seleccionada la marcacion y la agencia de carga
	 * en caso de tener asignado lo obligara a que lo tenga
	 */
	public function seleccionarMarcacionAgenciaAction()
	{
		try
		{
			$viewModel 			= new ViewModel();
			
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$SesionUsuarioPlugin->getUserLayout();
			$viewModel->setTemplate('Dispo/Disponibilidad/seleccionarmarcacionagencia.phtml');
			
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			return $viewModel;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end seleccionarMarcacionAgenciaAction
	
	
	
	public function asignarMarcacionAgenciaAction()
	{
		try
		{
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$MarcacionBO 			= new MarcacionBO();
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
						
			$marcacion_sec		= $this->params()->fromPost('marcacion_sec','');
			$agencia_carga_id	= $this->params()->fromPost('agencia_carga_id','');			
			
			
			$MarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			//Consulta la marcacion para obtener el nombre
			$MarcacionData = $MarcacionBO->consultar($marcacion_sec);
			$SesionUsuarioPlugin->setClienteSeleccionMarcacionNombre($MarcacionData->getNombre());
			unset($MarcacionData, $MarcacionBO);
			 
			//Consulta la carga para obtener el nombre
			$SesionUsuarioPlugin->setClienteSeleccionMarcacionSec	($marcacion_sec);
			$SesionUsuarioPlugin->setClienteSeleccionAgenciaId		($agencia_carga_id);			
		
			return $this->redirect()->toRoute('dispo-disponibilidad-listado');	
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end public function
	
	
	
	public function listadoAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
			
			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			//Se pregunta si se ha seleccionado una marcacion y una agencia, caso contrario lo rutea
			//para obligarlo a seleccionar
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionId();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();

			//Se pregunta si ya existe una marcacion y agencia seleccionada por el cliente
			//en caso de no estar, se lo dirige a la pantalla para que lo seleccione
			if ((empty($marcacion_id))||(empty($agencia_id)))
			{
				return $this->redirect()->toRoute('dispo-disponibilidad-seleccionar-marcacion-agencia');			
			}//end if

			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 	= $SesionUsuarioPlugin->getUserClienteId();
			$usuario_id 	= $SesionUsuarioPlugin->getUsuarioId();
			$marcacion_sec	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionId();

			$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec);
			
			$viewModel->result				= $result;
			$viewModel->marcacion_nombre 	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionNombre();
//			echo("<br>");var_dump($result);echo("<br>");
//			exit;
			
			$this->layout($SesionUsuarioPlugin->getUserLayout());

			return $viewModel;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadoAction

	
	
	public function listadodetalledispoAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
				
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
				
			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			//Se pregunta si se ha seleccionado una marcacion y una agencia, caso contrario lo rutea
			//para obligarlo a seleccionar
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionId();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
		
			//Se pregunta si ya existe una marcacion y agencia seleccionada por el cliente
			//en caso de no estar, se lo dirige a la pantalla para que lo seleccione
			if ((empty($marcacion_id))||(empty($agencia_id)))
			{
				return $this->redirect()->toRoute('dispo-disponibilidad-seleccionar-marcacion-agencia');
			}//end if
		
			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 	= $SesionUsuarioPlugin->getUserClienteId();
			$usuario_id 	= $SesionUsuarioPlugin->getUsuarioId();
			$marcacion_sec	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionId();
		
			$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec);
				
			$viewModel->result	= $result;

			$viewModel->setTemplate('Dispo/disponibilidad/listado_detalle_dispo.phtml');			
			$viewModel->setTerminal(true);
		
			return $viewModel;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadodispoAction

	
	/*-----------------------------------------------------------------------------*/
	public function listadodataAction()
	/*-----------------------------------------------------------------------------*/
	{
/*		try
		{
			$PruebaBO = new PruebaBO();
			$PruebaBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$condiciones = array();
			$result = $PruebaBO->listado($condiciones);
			//var_dump($result);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				//var_dump($row);
				$response->rows[$i] = $row;
				$i++;
			}//end foreach
			$response->userdata['nro_regs'] = count($result);
			$json = new JsonModel(get_object_vars($response));
			
			return $json;
			exit;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
*/	}//end function listadodataAction


}