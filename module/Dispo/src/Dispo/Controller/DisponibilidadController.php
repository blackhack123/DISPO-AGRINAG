<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\DispoBO;
use Dispo\BO\MarcacionBO;
use Dispo\BO\AgenciaCargaBO;


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
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$SesionUsuarioPlugin->getUserLayout();
			
			$marcacion_sec 		= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_carga_id 	= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
				
			//identidad_usuario
			if (empty($marcacion_sec)||(empty($agencia_carga_id)))
			{
				$viewModel 			= new ViewModel();
				
				$data = $SesionUsuarioPlugin->getRecord();
				$viewModel->identidad_usuario 	= $data;
				$viewModel->setTemplate('Dispo/Disponibilidad/seleccionarmarcacionagencia.phtml');
				$this->layout($SesionUsuarioPlugin->getUserLayout());
				return $viewModel;
			}else{
				return $this->redirect()->toRoute('dispo-disponibilidad-listado');
			}//end if
		
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
	}//end public asignarMarcacionAgenciaAction
	
	
	
	public function liberarMarcacionAgenciaAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
		
			//Consulta la carga para obtener el nombre
			$SesionUsuarioPlugin->setClienteSeleccionMarcacionSec	(null);
			$SesionUsuarioPlugin->setClienteSeleccionAgenciaId		(null);
		
			return $this->redirect()->toRoute('dispo-disponibilidad-seleccionar-marcacion-agencia');
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function  liberarMarcacionAgenciaAction
	
	
	
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
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();

			//Se pregunta si ya existe una marcacion y agencia seleccionada por el cliente
			//en caso de no estar, se lo dirige a la pantalla para que lo seleccione
			if ((empty($marcacion_id))||(empty($agencia_id)))
			{
				return $this->redirect()->toRoute('dispo-disponibilidad-seleccionar-marcacion-agencia');			
			}//end if

			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 	= $SesionUsuarioPlugin->getUserClienteId();
			$usuario_id 	= $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();

			
			$data = $SesionUsuarioPlugin->getRecord();
			$viewModel->identidad_usuario 	= $data;			
			//$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec);  //MORONITOR			
			//$viewModel->result				= $result;	//MORONITOR
			
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
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
				
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
				
			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			//Se pregunta si se ha seleccionado una marcacion y una agencia, caso contrario lo rutea
			//para obligarlo a seleccionar
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
		
			//Se pregunta si ya existe una marcacion y agencia seleccionada por el cliente
			//en caso de no estar, se lo dirige a la pantalla para que lo seleccione
/*			if ((empty($marcacion_id))||(empty($agencia_id)))
			{
				return $this->redirect()->toRoute('dispo-disponibilidad-seleccionar-marcacion-agencia');
			}//end if
*/		
			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 	= $SesionUsuarioPlugin->getUserClienteId();
			$usuario_id 	= $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();

			$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec);

			$viewModel 							= new ViewModel();
			$viewModel->respuesta_dispo_code	= $result['respuesta_code'];
			$viewModel->respuesta_dispo_msg		= $result['respuesta_msg'];	
			if (!empty($result['result_dispo']))
			{			
				$viewModel->result					= $result['result_dispo'];
			}else{
				$viewModel->result					= null;
			}//end if

			$viewModel->setTerminal(true);				
			$viewModel->setTemplate('Dispo/disponibilidad/listado_detalle_dispo.phtml');
			return $viewModel;

		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadodispoAction

	
	
	
	public function getcomboMarcacionAgenciacargaAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
				
			$AgenciaCargaBO = new AgenciaCargaBO();
			$MarcacionBO 	= new MarcacionBO();
						
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$MarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());			
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$marcacion_texto_primer_elemento		= $json['marcacion_texto_primer_elemento'];
			$agenciacarga_texto_primer_elemento		= $json['agenciacarga_texto_primer_elemento'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
			$marcacion_sec		= null;
			$agencia_carga_id 	= null;
		
			$marcacion_opciones 	= $MarcacionBO->getComboPorClienteId($cliente_id, $marcacion_sec, $marcacion_texto_primer_elemento);
			$agenciacarga_opciones 	= $AgenciaCargaBO->getComboTodos($agencia_carga_id, $agenciacarga_texto_primer_elemento);	
		
			$response = new \stdClass();
			$response->marcacion_opciones				= $marcacion_opciones;
			$response->agenciacarga_opciones			= $agenciacarga_opciones;			
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
	}//end function getcomboMarcacionAgenciacargaAction
	
	
	
	
	public function consultarofertahtmlAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
		
			$config = $this->getServiceLocator()->get('Config');
		
			$DispoBO				= new DispoBO();
		
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			//Recibe las variables
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
				
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$cliente_usuario_id	= $SesionUsuarioPlugin->getClienteUsuarioId();

			//Consulta el cliente para saber con que precio especial debe de trabajar
			list($reg_grupo_precio_det, $rs_precio_oferta) 	= $DispoBO->consultarPrecioOfertaPorCliente($cliente_id, $variedad_id, $grado_id); 

			//Asigna las variables a la vista
			$viewModel->reg_grupo_precio_det		= $reg_grupo_precio_det;
			$viewModel->rs_precio_oferta			= $rs_precio_oferta;

			$viewModel->setTemplate('Dispo/disponibilidad/oferta_variedad.phtml');
			$viewModel->setTerminal(true);
			$viewRender = $this->getServiceLocator()->get('ViewRenderer');
			$html = $viewRender->render($viewModel);

			$response = new \stdClass();
			$response->respuesta_code 			= 'OK';
			$response->respuesta_codex 			= 'OK'; //$result['respuesta'];
			$response->respuesta_mensaje		= '';
			$response->html = $html;
		
			$json = new JsonModel(get_object_vars($response));
			return $json;
		
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end function consultarofertahtmlAction
	
}