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
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\BO\Dispo\BO;
use Dispo\BO\CalidadBO;
use Dispo\BO\InventarioBO;
use Dispo\BO\ProveedorBO;

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
				$viewModel->setTemplate('dispo/disponibilidad/seleccionarmarcacionagencia.phtml');
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
			$SesionUsuarioPlugin->setClienteSeleccionMarcacionPuntoCorte($MarcacionData->getPuntoCorte());
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
			$viewModel->setTemplate('dispo/disponibilidad/listado_detalle_dispo.phtml');
			return $viewModel;

		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadodispoAction

	
	
	public function getcajasAction()
	{
		try
		{
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
				
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
				
			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);			
			$tipo_caja_id		= $json['tipo_caja_id'];
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];
				
			//Se pregunta si se ha seleccionado una marcacion y una agencia, caso contrario lo rutea
			//para obligarlo a seleccionar
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();

			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 	= $SesionUsuarioPlugin->getUserClienteId();
			$usuario_id 	= $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();

			$cbo_nro_caja		= "";
			$nro_cajas			= 0;			
			$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec, $tipo_caja_id, $variedad_id, $grado_id);

			if($result)
			{
				$result_dispo = $result['result_dispo'][0];
				if ($result_dispo)
				{
					$arr_cajas = array();
					for($i=1; $i<=$result_dispo['nro_cajas'];$i++) $arr_cajas[$i]=$i;
					
					$cbo_nro_caja		= \Application\Classes\Combo::getComboDataArray($arr_cajas, 1, "");	
					$nro_cajas			= $result_dispo['nro_cajas'];
				}///end if	
			}//end function 
			
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje    = $result['respuesta_msg'];
			$response->respuesta_codex		= $result['respuesta_code'];
			$response->cbo_nro_caja			= $cbo_nro_caja;
			$response->nro_cajas			= $nro_cajas;

			$json = new JsonModel(get_object_vars($response));
			return $json;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end function getcajasAction
	
	
	

	
	
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

			$marcacion_opciones 	= $MarcacionBO->getComboActivosPorClienteId($cliente_id, $marcacion_sec, $marcacion_texto_primer_elemento);
			$agenciacarga_opciones 	= $AgenciaCargaBO->getComboActivos($agencia_carga_id, $agenciacarga_texto_primer_elemento);	

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
			//$tipo_caja_id		= $json['tipo_caja_id'];  //Envia el tipo de caja con que el cliente ha seleccionado en la grilla de dispo
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$cliente_usuario_id	= $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();			

			//Consulta el cliente para saber con que precio especial debe de trabajar
			$dispo_precio_oferta = $DispoBO->consultarPrecioOfertaPorCliente($cliente_id, $cliente_usuario_id, $marcacion_sec, $variedad_id, $grado_id); 
			$reg_dispo_precio_oferta = null;
			if ($dispo_precio_oferta) {
				$reg_dispo_precio_oferta = $dispo_precio_oferta['result_dispo'][0];
			}

			//Asigna las variables a la vista
			//echo("<pre>");var_dump($rs_precio_oferta);echo("</pre>");exit();
			$viewModel->reg_dispo_precio_oferta		= $reg_dispo_precio_oferta;

			$viewModel->setTemplate('dispo/disponibilidad/oferta_variedad.phtml');
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

	
	
	//MORONITOR PILAS CON ESTO
	public function getcajasofertasAction()
	{
		try
		{
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
	
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$oferta_tipo_caja_id		= $json['oferta_tipo_caja_id'];
			$oferta_variedad_id			= $json['oferta_variedad_id'];
			$oferta_grado_id			= $json['oferta_grado_id'];
			$oferta_nro_caja			= $json['oferta_nro_caja'];
	
			//Se pregunta si se ha seleccionado una marcacion y una agencia, caso contrario lo rutea
			//para obligarlo a seleccionar
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
	
			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$cliente_usuario_id = $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec		= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			
			$result_hueso = $DispoBO->consultarPrecioOfertaPorClienteHueso($cliente_id, $cliente_usuario_id, $marcacion_sec, $oferta_variedad_id, $oferta_grado_id, $oferta_tipo_caja_id, $oferta_nro_caja);
	
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje    = '';  //$result['respuesta_msg'];
			$response->respuesta_codex		= 'OK'; //$result['respuesta_code'];
			$response->result				= $result_hueso;
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcajasofertaAction
	
	

	public function panelAction()
	{
		try
		{
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
	
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$viewModel 				= new ViewModel();
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/disponibilidad/panel.phtml');
			return $viewModel;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadodispoAction	
	
	

	
	public function remotesincronizarpreviewAction()
	{
		try
		{		
			$config 			= $this->getServiceLocator()->get('Config');
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			if(empty($json['agr_connect'])){
				$agr_connect=0;
			}else{
				$agr_connect = $json['agr_connect'];
			}//end if
	
			if(empty($json['htc_connect'])){
				$htc_connect=0;
			}else{
				$htc_connect = $json['htc_connect'];
			}//end if
			
			if(empty($json['lma_connect'])){
				$lma_connect=0;
			}else{
				$lma_connect = $json['lma_connect'];
			}//end if
			
			$uri = $config['url_server_integrador'].'/sincronizador/disponibilidad/sincronizarpreview';
			$data = array(
							'agr_connect' => $agr_connect,
							'htc_connect' => $htc_connect,
							'lma_connect' => $lma_connect
			);
			$json = json_encode($data);
			
			//Instantiate a client object
			$client = new Client($uri, array('timeout'      => 60));
			
			$requestHeaders = $client->getRequest()->getHeaders();
			$client->setRawBody($json);
			$client->setMethod('post');
			//setting header optional - some API need this;
			$headerString = 'Accept: application/json';
			$requestHeaders->addHeaderLine($headerString);

			// The following request will be sent over a TLS secure connection.
			$response = $client->send();
			//if ($response->isSuccess()) {
			$json_string = $response->getBody();
			//echo("<pre>");var_dump($json); echo("</pre");
			$json =  json_decode($json_string);
			$json->respuesta_code 		= $json->response->code;
			$json->respuesta_mensaje	= $json->response->message;
			$json->respuesta_codex 		= 'OK';
			//echo("<pre>");var_dump($json); echo("</pre");
			$json = new JsonModel(get_object_vars($json));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}			
	}//end function remotesincronizarpreviewAction
		
	
	
	public function remotesincronizarAction()
	{
		try
		{
			$config 				= $this->getServiceLocator()->get('Config');
				
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			if(empty($json['agr_connect'])){
				$agr_connect = 0;
				$agr_fecha 	 = '';
			}else{
				$agr_connect = $json['agr_connect'];
				$agr_fecha 	 = $json['agr_fecha'];				
			}//end if
	
			if(empty($json['htc_connect'])){
				$htc_connect = 0;
				$htc_fecha 	 = '';
			}else{
				$htc_connect = $json['htc_connect'];
				$htc_fecha 	 = $json['htc_fecha'];				
			}//end if
				
			if(empty($json['lma_connect'])){
				$lma_connect = 0;
				$lma_fecha 	 = '';
			}else{
				$lma_connect = $json['lma_connect'];
				$lma_fecha 	 = $json['lma_fecha'];				
			}//end if
				
			$uri = $config['url_server_integrador'].'/sincronizador/disponibilidad/sincronizar';
			$data = array(
					'agr_connect' => $agr_connect,
					'htc_connect' => $htc_connect,
					'lma_connect' => $lma_connect,
					'agr_fecha'	  => $agr_fecha,
					'htc_fecha'	  => $htc_fecha,
					'lma_fecha'	  => $lma_fecha
			);
			$json = json_encode($data);
				
			//Instantiate a client object
			$client = new Client($uri, array('timeout'      => 60));
				
			$requestHeaders = $client->getRequest()->getHeaders();
			$client->setRawBody($json);
			$client->setMethod('post');
			//setting header optional - some API need this;
			$headerString = 'Accept: application/json';
			$requestHeaders->addHeaderLine($headerString);
	
			// The following request will be sent over a TLS secure connection.
			$response = $client->send();
			//if ($response->isSuccess()) {
			$json_string = $response->getBody();
			//echo("<pre>");var_dump($json_string); echo("</pre");
			$json =  json_decode($json_string);
			$json->respuesta_code 		= 'OK';
			$json->respuesta_mensaje	= $json->response->message;
			$json->respuesta_codex 		= $json->response->code;
			//echo("<pre>");var_dump($json); echo("</pre");
			$json = new JsonModel(get_object_vars($json));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function remotesincronizarAction
		
	
	
	public function disponibilidaddataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$DispoBO = new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 		= $this->getRequest();
			$inventario_id  = $request->getQuery('inventario_id', "");
			$proveedor_id  	= $request->getQuery('proveedor_id', "");
			$clasifica  	= $request->getQuery('clasifica', "");
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$DispoBO->setPage($page);
			$DispoBO->setLimit($limit);
			$DispoBO->setSidx($sidx);
			$DispoBO->setSord($sord);
			$condiciones = array(
					"inventario_id"	=> $inventario_id,
					"proveedor_id"	=> $proveedor_id,
					"clasifica"		=> $clasifica
			);
			$result = $DispoBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){	
				$row['variedad'] = trim($row['variedad']);	
				$response->rows[$i] = $row;
				$i++;
			}//end foreach
			$tot_reg = $i;
			$response->total 	= ceil($tot_reg/$limit);
			$response->page 	= $page;
			$response->records 	= $tot_reg;
			$json = new JsonModel(get_object_vars($response));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function disponibilidaddataAction

	

	
	public function initcontrolsAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$InventarioBO 	= new InventarioBO();
			$CalidadBO		= new CalidadBO();
			$ProveedorBO	= new ProveedorBO();

			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ProveedorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$opcion						= $json['opcion'];
			$inventario_1er_elemento	= $json['inventario_1er_elemento'];
			$calidad_1er_elemento		= $json['calidad_1er_elemento'];
			$proveedor_1er_elemento		= $json['proveedor_1er_elemento'];
			$inventario_id	= null;
			$clasifica_fox	= null;
			$proveedor_id	= null;

			$inventario_opciones 	= $InventarioBO->getCombo($inventario_id, $inventario_1er_elemento);
			$calidad_opciones 		= $CalidadBO->getComboCalidadFox($clasifica_fox, $calidad_1er_elemento);
			$proveedor_opciones 	= $ProveedorBO->getCombo($proveedor_id, $proveedor_1er_elemento);

			$response = new \stdClass();
			$response->inventario_opciones		= $inventario_opciones;
			$response->calidad_opciones			= $calidad_opciones;
			$response->proveedor_opciones		= $proveedor_opciones;
			$response->respuesta_code 			= 'OK';

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
	
	
	
	function consultarPorInventarioPorCalidadPorProveedorPorGradoAction()
	{
			try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			
			$DispoBO 			= new DispoBO();
			
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$inventario_id		= $json['inventario_id'];
			$clasifica_fox		= $json['clasifica_fox'];
			$proveedor_id		= $json['proveedor_id'];
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];

			$row				= $DispoBO->consultarPorInventarioPorCalidadPorProveedorPorGrado($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id);

			$response = new \stdClass();
			$response->row					= $row;
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';

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
	}//end function consultarPorInventarioPorCalidadPorProveedorPorGradoAction
	
	
	
	function grabarstockproveedorAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
				
			$DispoBO 			= new DispoBO();
				
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$inventario_id  	= $json['inventario_id'];
			$producto			= 'ROS';
			$clasifica_fox  	= $json['clasifica_fox'];
			$proveedor_id  		= $json['proveedor_id'];
			$variedad_id  		= $json['variedad_id'];
			$grado_id  			= $json['grado_id'];
			$stock['AGR'] 		= $json['stock_agr'];
			$stock['HTC'] 		= $json['stock_htc'];
			$stock['LMA'] 		= $json['stock_lma'];
			$result = $DispoBO->actualizarStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id,  $stock);

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
			if ($row)
			{
				$response->row					= $row;
				$response->cbo_tipo				= $AgenciaCargaBO->getComboTipo($row['tipo'], " ");
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			}else{
				$response->row					= null;
				$response->cbo_tipo				= '';
				$response->cbo_estado			= '';
			}//end if
	
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
	}//end function grabarstockproveedorAction
	
	
}