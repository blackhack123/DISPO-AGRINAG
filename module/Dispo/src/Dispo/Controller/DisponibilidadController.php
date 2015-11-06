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
use Dispo\BO\CalidadBO;
use Dispo\BO\InventarioBO;
use Dispo\BO\ProveedorBO;
use Dispo\BO\ColorVentasBO;
use Dispo\BO\ClienteAgenciaCargaBO;
use Dispo\BO\TipoCajaBO;
use Dispo\BO\CalidadVariedadBO;
use Dispo\BO\Dispo\BO;
use Dispo\Data\DispoData;

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
			//$SesionUsuarioPlugin->setClienteSeleccionMarcacionPuntoCorte($MarcacionData->getPuntoCorte());
			$tipo_caja_default_id = $MarcacionData->getTipoCajaDefaultId();
			if (empty($tipo_caja_default_id))
			{
				$SesionUsuarioPlugin->setMarcacionTipoCajaDefaultId('HB'); //CAJA POR DEFECTO EN CASO QUE NO TENGA ASIGNADO LA MARCACION
			}else{				
				$SesionUsuarioPlugin->setMarcacionTipoCajaDefaultId($MarcacionData->getTipoCajaDefaultId());
			}//end if
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
			$ColorVentasBO			= new ColorVentasBO();
			
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());

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
			
			$viewModel->cbo_color = $ColorVentasBO->getCombo(null, "ALL");
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
			$TipoCajaBO				= new TipoCajaBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$TipoCajaBO->setEntityManager($EntityManagerPlugin->getEntityManager());

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
			$tipo_caja_id	= $SesionUsuarioPlugin->getMarcacionTipoCajaDefaultId();		

			$result_cajas	= $TipoCajaBO->getArrayIndexado();

			$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec, $tipo_caja_id);

			$viewModel 							= new ViewModel();
			$viewModel->respuesta_dispo_code	= $result['respuesta_code'];
			$viewModel->respuesta_dispo_msg		= $result['respuesta_msg'];
			$viewModel->result_cajas			= $result_cajas;	
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
	}//end function listadodetalledispoAction


	
	
	
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
			$producto_id		= $json['producto_id'];			
			$tipo_caja_id		= $json['tipo_caja_id'];
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];
			$tallos_x_bunch		= $json['tallos_x_bunch'];
				
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
			$result 		= $DispoBO->getDispo($cliente_id, $usuario_id, $marcacion_sec, $tipo_caja_id, $variedad_id, $grado_id,
												false, true, false, $producto_id, $tallos_x_bunch);

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
				
			$ClienteAgenciaCargaBO = new ClienteAgenciaCargaBO();
			$MarcacionBO 	= new MarcacionBO();
						
			$ClienteAgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
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
			$agenciacarga_opciones 	= $ClienteAgenciaCargaBO->getComboAgencia($cliente_id, $agencia_carga_id, $agenciacarga_texto_primer_elemento);	

			$response = new \stdClass();
			$response->marcacion_opciones		= $marcacion_opciones;
			$response->agenciacarga_opciones	= $agenciacarga_opciones;			
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

			$producto_id		= $json['producto_id'];
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];
			$tallos_x_bunch		= $json['tallos_x_bunch'];
			//$tipo_caja_id		= $json['tipo_caja_id'];  //Envia el tipo de caja con que el cliente ha seleccionado en la grilla de dispo
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$cliente_usuario_id	= $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec		= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();		
			$tipo_caja_id		= $SesionUsuarioPlugin->getMarcacionTipoCajaDefaultId();

			//Consulta el cliente para saber con que precio especial debe de trabajar
			$dispo_precio_oferta = $DispoBO->consultarPrecioOfertaPorCliente($cliente_id, $cliente_usuario_id, $marcacion_sec, 
																			 $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id); 
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
			$ofertas_producto_id		= $json['oferta_producto_id'];			
			$oferta_variedad_id			= $json['oferta_variedad_id'];
			$oferta_grado_id			= $json['oferta_grado_id'];
			$oferta_tallos_x_bunch		= $json['oferta_tallos_x_bunch'];
			$oferta_nro_caja			= $json['oferta_nro_caja'];

			//Se pregunta si se ha seleccionado una marcacion y una agencia, caso contrario lo rutea
			//para obligarlo a seleccionar
			$marcacion_id	= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_id		= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
	
			//Se consulta la dispo, considerando los criterios de busqueda
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$cliente_usuario_id = $SesionUsuarioPlugin->getClienteUsuarioId();
			$marcacion_sec		= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();

			$result_hueso = $DispoBO->consultarPrecioOfertaPorClienteHueso($cliente_id, $cliente_usuario_id, $marcacion_sec, 
														$ofertas_producto_id, $oferta_variedad_id, $oferta_grado_id, $oferta_tallos_x_bunch, 
														$oferta_tipo_caja_id, $oferta_nro_caja);
	
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
			if (($SesionUsuarioPlugin->isLoginAdmin()==false)&&($SesionUsuarioPlugin->isPerfil(\Application\Constants\Perfil::ID_DISPO)==false))
			{
				$SesionUsuarioPlugin->gotoHome();
			}//end if
			
			$proveedor_seleccionado = '';
			if ($SesionUsuarioPlugin->existeAtributo('DISPO-AGR')){
				$proveedor_seleccionado = 'AGR'; 
			}//end if
			if ($SesionUsuarioPlugin->existeAtributo('DISPO-HTC')){
				$proveedor_seleccionado = 'HTC'; 
			}//end if
			if ($SesionUsuarioPlugin->existeAtributo('DISPO-LMA')){
				$proveedor_seleccionado = 'LMA'; 
			}//end if

			$DispoBO				= new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$viewModel 				= new ViewModel();
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/disponibilidad/panel.phtml');
			$viewModel->usuario_perfil_id 		= $SesionUsuarioPlugin->getUserPerfilId();
			$viewModel->proveedor_seleccionado 	= $proveedor_seleccionado;
			return $viewModel;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function panelAction
	
	

	
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
			$client = new Client($uri, array('timeout'      => 600));
				
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
			$color_ventas_id= $request->getQuery('color_ventas_id', "");
			$calidad_variedad_id= $request->getQuery('calidad_variedad_id', "");
			$nro_tallos		= $request->getQuery('nro_tallos', "");
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$DispoBO->setPage($page);
			$DispoBO->setLimit($limit);
			$DispoBO->setSidx($sidx);
			$DispoBO->setSord($sord);
			$condiciones = array(
					"inventario_id"		=> $inventario_id,
					"proveedor_id"		=> $proveedor_id,
					"clasifica"			=> $clasifica,
					"color_ventas_id"	=> $color_ventas_id,
					"calidad_variedad_id"=> $calidad_variedad_id,
					"nro_tallos"		=> $nro_tallos
			);
			$result = $DispoBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			$totales['40'] = 0; 
			$totales['50'] = 0;
			$totales['60'] = 0;
			$totales['70'] = 0;
			$totales['80'] = 0;
			$totales['90'] = 0;
			$totales['100'] = 0;
			$totales['110'] = 0;
			$totales['total'] = 0;
			foreach($result as $row){	
				$row['variedad'] = trim($row['variedad']);
				$row['total']	 = $row['40'] + $row['50'] + $row['60'] + $row['70'] + $row['80'] + $row['90'] + $row['100'] + $row['110'];
				$response->rows[$i] = $row;
				$i++;
				
				$totales['40'] 		= $totales['40'] + $row['40'];
				$totales['50'] 		= $totales['50'] + $row['50'];
				$totales['60'] 		= $totales['60'] + $row['60'];
				$totales['70'] 		= $totales['70'] + $row['70'];
				$totales['80'] 		= $totales['80'] + $row['80'];
				$totales['90'] 		= $totales['90'] + $row['90'];
				$totales['100'] 	= $totales['100'] + $row['100'];
				$totales['110'] 	= $totales['110'] + $row['110'];
				$totales['total'] 	= $totales['total'] + $row['total'];
			}//end foreach
			$tot_reg = $i;
			$response->total 	= ceil($tot_reg/$limit);
			$response->page 	= $page;
			$response->records 	= $tot_reg;
			
			$response->userdata['variedad']='TOTALES DE BUNCHS'; 
			$response->userdata['tallos_x_bunch']=25;
			$response->userdata['40'] = $totales['40'];
			$response->userdata['50'] = $totales['50'];
			$response->userdata['60'] = $totales['60'];
			$response->userdata['70'] = $totales['70'];
			$response->userdata['80'] = $totales['80'];
			$response->userdata['90'] = $totales['90'];
			$response->userdata['100'] = $totales['100'];
			$response->userdata['110'] = $totales['110'];
			$response->userdata['total'] = $totales['total'];
			
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

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$opcion						= $json['opcion'];
			switch ($opcion)
			{
				case 'panel-control-disponibilidad':
					$InventarioBO 	= new InventarioBO();
					$CalidadBO		= new CalidadBO();
					$ProveedorBO	= new ProveedorBO();
					$ColorVentasBO  = new ColorVentasBO();
					$CalidadVariedadBO = new CalidadVariedadBO();
					
					$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$ProveedorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
										
					$inventario_1er_elemento	= $json['inventario_1er_elemento'];
					$calidad_1er_elemento		= $json['calidad_1er_elemento'];
					$proveedor_1er_elemento		= $json['proveedor_1er_elemento'];
					$color_ventas_1er_elemento	= $json['color_ventas_1er_elemento'];
					$inventario_id				= $json['inventario_id'];
					$calidad_variedad_1er_elemento	= $json['calidad_variedad_1er_elemento'];
					$nro_tallos_1er_elemento	= $json['nro_tallos_1er_elemento'];
					$buscar_proveedor_id			= null;
					if (array_key_exists('buscar_proveedor_id',$json))
					{
						$buscar_proveedor_id		= $json['buscar_proveedor_id'];
					}//end if

					$clasifica_fox	= null;
					$proveedor_id	= null;
					$color_ventas_id= null;
					$calidad_variedad_id= null;
					$nro_tallos= null;
					
					$inventario_opciones 	= $InventarioBO->getCombo($inventario_id, $inventario_1er_elemento);
					$calidad_opciones 		= $CalidadBO->getComboCalidadFox($clasifica_fox, $calidad_1er_elemento);
					$proveedor_opciones 	= $ProveedorBO->getCombo($proveedor_id, $proveedor_1er_elemento, null, $buscar_proveedor_id);
					$color_ventas_opciones 	= $ColorVentasBO->getCombo($color_ventas_id, $color_ventas_1er_elemento);
					$calidad_variedad_opciones= $CalidadVariedadBO->getComboCalidadVariedad($calidad_variedad_id, $calidad_variedad_1er_elemento);
					$nro_tallos_opciones	= \Application\Classes\ComboGeneral::getComboNroTallos($nro_tallos, $nro_tallos_1er_elemento);

					$response = new \stdClass();
					$response->inventario_opciones		= $inventario_opciones;
					$response->calidad_opciones			= $calidad_opciones;
					$response->proveedor_opciones		= $proveedor_opciones;
					$response->color_ventas_opciones	= $color_ventas_opciones;
					$response->calidad_variedad_opciones= $calidad_variedad_opciones;
					$response->nro_tallos_opciones		= $nro_tallos_opciones;
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
	
	
	
	function consultarPorInventarioPorCalidadPorProveedorPorGradoPorTalloAction()
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
			$tallos_x_bunch		= $json['tallos_x_bunch'];

			$row				= $DispoBO->consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch);

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
	
	
	
	function consultarPorInventarioPorCalidadPorVariedadPorGradoAction()
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
			//$clasifica_fox		= $json['clasifica_fox'];
			$calidad_id			= $json['calidad_id'];
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];

			$row				= $DispoBO->consultarPorInventarioPorCalidadPorVariedadPorGrado($inventario_id, $calidad_id, $variedad_id, $grado_id);
		
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
	}//end function consultarPorInventarioPorCalidadPorVariedadPorGradoAction
	
	
	
	function grabarstockproveedorAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
				
			//$config = $this->getServiceLocator()->get('Config');
			$DispoBO 			= new DispoBO();
				
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			if (($SesionUsuarioPlugin->isLoginAdmin()==false)&&($SesionUsuarioPlugin->isPerfil(\Application\Constants\Perfil::ID_DISPO)==false))
			{
				return false;
			}//end if
					
/*			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
*/
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$inventario_id  	= $json['inventario_id'];
			$producto			= 'ROS';
			$clasifica_fox  	= $json['clasifica_fox'];
			$proveedor_id  		= $json['proveedor_id'];
			$variedad_id  		= $json['variedad_id'];
			$grado_id  			= $json['grado_id'];
			$tallos_x_bunch		= $json['tallos_x_bunch'];
			$stock['AGR'] 		= $json['stock_agr'];
			$stock['HTC'] 		= $json['stock_htc'];
			$stock['LMA'] 		= $json['stock_lma'];
			$result = $DispoBO->actualizarStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch, $stock);

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
	
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



	public function listadovariedaddialogdataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$DispoBO = new DispoBO();
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 			= $this->getRequest();
			$estado  			= $request->getQuery('estado', "");
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			$DispoBO->setPage($page);
			$DispoBO->setLimit($limit);
			$DispoBO->setSidx($sidx);
			$DispoBO->setSord($sord);

			$variedad_nombre = $request->getQuery('term', "");
			$result = $DispoBO->listadoVariedadPorInventario($request->getQuery('inventario_id'), $variedad_nombre);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				//$row['variedad'] = trim($row['variedad']);
				$row2['variedad_id'] 		= $row['variedad_id'];
				$row2['variedad_nombre']	= trim($row['variedad_nombre']);
				$response->rows[$i] 		= $row2;
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
	}//end function listadodialogdataAction	

	

	
	public function getcombovariedadnoexisteAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$DispoBO = new DispoBO();
	
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$texto_primer_elemento	= $json['texto_primer_elemento'];
			$inventario_id			= $json['inventario_id'];
			$calidad_id				= $json['calidad_id'];
			$variedad_id			= $json['variedad_id'];
	
			$variedad_opciones 	= $DispoBO->getComboVariedadNoExiste($inventario_id, $calidad_id, $variedad_id, $texto_primer_elemento);
	
			$response = new \stdClass();
			$response->variedad_opciones	= $variedad_opciones;
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
	
	
	
	function grabarStockNuevoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
	
			$config = $this->getServiceLocator()->get('Config');
	
			$DispoBO 			= new DispoBO();
	
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$inventario_id  	= $json['inventario_id'];
			$producto			= 'ROS';
			$calidad_id  		= $json['calidad_id'];
			//$clasifica_fox  	= $json['clasifica_fox'];
			$variedad_id  		= $json['variedad_id'];			
			$grado_id  			= $json['grado_id'];
			$stock['AGR'] 		= $json['agr_cantidad_bunch'];
			$stock['HTC'] 		= $json['htc_cantidad_bunch'];
			$stock['LMA'] 		= $json['lma_cantidad_bunch'];
			$result = $DispoBO->registrarStockNuevo($inventario_id, $producto, $calidad_id, $variedad_id, $grado_id, $config['tallos_x_bunch_default'], $stock);
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
	
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
	}//end function grabarstocknuevoAction	
	

	
	
	function grabarmasivostockAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$DispoBO 				= new DispoBO();
			$CalidaBO				= new CalidadBO();
	
			//$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$inventario_id 			= $json['inventario_id'];
			$clasifica 				= $json['clasifica'];
			$proveedor_id 			= $json['proveedor_id'];
			$grado_id				= $json['grado_id'];
			$color_ventas_ids		= $json['color_ventas_ids'];
			$calidad_variedad_ids	= $json['calidad_variedad_ids'];
			$porcentaje				= $json['porcentaje'];
			$valor					= $json['valor'];

			
			//Convierte en cadena el array de color de ventas
			$cadena_color_ventas_id = '';
			$flag_1era_vez = true;
			foreach($color_ventas_ids as $clave => $valor2)
			{
				if ($flag_1era_vez == false)
				{
					$cadena_color_ventas_id = $cadena_color_ventas_id.",";
				}//end if
				$cadena_color_ventas_id = $cadena_color_ventas_id.$valor2;
				$flag_1era_vez = false;
			}//end if
				
			//Convierte en cadena el array de color de ventas
			$cadena_calidad_variedad_ids = '';
			$flag_1era_vez = true;
			foreach($calidad_variedad_ids as $clave => $valor2)
			{
				if ($flag_1era_vez == false)
				{
					$cadena_calidad_variedad_ids = $cadena_calidad_variedad_ids.",";
				}//end if
				$cadena_calidad_variedad_ids = $cadena_calidad_variedad_ids.$valor2;
				$flag_1era_vez = false;
			}//end if
				
			$result = $DispoBO->grabarMasivoStock($inventario_id, $clasifica, $proveedor_id, $grado_id, 
												 $cadena_color_ventas_id, $cadena_calidad_variedad_ids, 
												 $porcentaje, $valor, $usuario_id);

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
	
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
	}//end function grabarmasivostockAction	
	

	
	function exportarexcelOLDAction()
	{
		try
		{
			$viewModel 			= new ViewModel();
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$DispoBO 			= new DispoBO();
			$InventarioBO 		= new InventarioBO();
			$CalidadBO			= new CalidadBO();
			$ProveedorBO		= new ProveedorBO();
			$ColorVentasBO		= new ColorVentasBO();
			$CalidadVariedadBO	= new CalidadVariedadBO();
			
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ProveedorBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 			= $this->getRequest();
			$inventario_id 	 	= $request->getQuery('inventario_id', "");
			$proveedor_id  		= $request->getQuery('proveedor_id', "");
			$clasifica  		= $request->getQuery('clasifica', "");
			$color_ventas_id	= $request->getQuery('color_ventas_id', "");
			$calidad_variedad_id= $request->getQuery('calidad_variedad_id', "");
			$nro_tallos			= $request->getQuery('nro_tallos', "");

/*			$InventarioData 		= $InventarioBO->consultar($inventario_id, Application\Constants\ResultType::OBJETO);
			$CalidadData			= $CalidadBO->consultarPorClasificaFox($clasifica, Application\Constants\ResultType::OBJETO);
			$ProveedorData			= $ProveedorBO->consultar($proveedor_id, Application\Constants\ResultType::OBJETO);
			$ColorVentasData 		= $ColorVentasBO->consultar($color_ventas_id, Application\Constants\ResultType::OBJETO);			
			$CalidadVariedadData 	= $CalidadVariedadBO->consultar($calidad_variedad_id, Application\Constants\ResultType::OBJETO);
*/
			$condiciones = array(
					"inventario_id"		=> $inventario_id,
					"proveedor_id"		=> $proveedor_id,
					"clasifica"			=> $clasifica,
					"color_ventas_id"	=> $color_ventas_id,
					"calidad_variedad_id"=> $calidad_variedad_id,
					"nro_tallos"		=> $nro_tallos
			);
			$result = $DispoBO->listado($condiciones);

			$totales['40'] = 0; 
			$totales['50'] = 0;
			$totales['60'] = 0;
			$totales['70'] = 0;
			$totales['80'] = 0;
			$totales['90'] = 0;
			$totales['100'] = 0;
			$totales['110'] = 0;
			$totales['total'] = 0;
			foreach($result as &$row){	
				$row['variedad'] = trim($row['variedad']);
				$row['total']	 = $row['40'] + $row['50'] + $row['60'] + $row['70'] + $row['80'] + $row['90'] + $row['100'] + $row['110'];

				//Array de Totales
				$totales['40'] 		= $totales['40'] + $row['40'];
				$totales['50'] 		= $totales['50'] + $row['50'];
				$totales['60'] 		= $totales['60'] + $row['60'];
				$totales['70'] 		= $totales['70'] + $row['70'];
				$totales['80'] 		= $totales['80'] + $row['80'];
				$totales['90'] 		= $totales['90'] + $row['90'];
				$totales['100'] 	= $totales['100'] + $row['100'];
				$totales['110'] 	= $totales['110'] + $row['110'];
				$totales['total'] 	= $totales['total'] + $row['total'];
			}//end foreach

			$viewModel->result 	= $result;
			$viewModel->totales = $totales;
/*			$viewModel->inventario_nombre 	= $InventarioData->getNombre();
			$viewModel->calidad_nombre		= $CalidadData->getNombre();
*/			
			//echo("<pre>");var_dump($result);echo("</pre>");exit;
			$viewModel->setTerminal(true);			
			//$this->layout('layout/mobile');
			$viewModel->setTemplate('dispo/disponibilidad/exportalexcel.phtml');
			return $viewModel;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function exportarexcelAction 

	
	
	function exportarexcelAction()
	{
		try
		{
			$viewModel 			= new ViewModel();
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$DispoBO 			= new DispoBO();

				
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 			= $this->getRequest();
			$inventario_id 	 	= $request->getQuery('inventario_id', "");
			$proveedor_id  		= $request->getQuery('proveedor_id', "");
			$clasifica  		= $request->getQuery('clasifica', "");
			$color_ventas_id	= $request->getQuery('color_ventas_id', "");
			$calidad_variedad_id= $request->getQuery('calidad_variedad_id', "");
	
			$condiciones = array(
					"inventario_id"		=> $inventario_id,
					"proveedor_id"		=> $proveedor_id,
					"clasifica"			=> $clasifica,
					"color_ventas_id"	=> $color_ventas_id,
					"calidad_variedad_id"=> $calidad_variedad_id
			);
			$result = $DispoBO->generarExcel($condiciones);
		
			exit;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function exportarexcel2Action
	
	

	public function actualizarcerostockAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$DispoBO 				= new DispoBO();
	
			$DispoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$inventario_id 		= $json['inventario_id'];
			$clasifica 			= $json['clasifica'];
			$proveedor_id 		= $json['proveedor_id'];
			$grid_data 			= $json['grid_data'];

			//Prepara el Buffer de datos antes de llamar al BO
			$ArrDispoData   	= array();
			foreach ($grid_data as $reg)
			{
				$DispoData = new DispoData();
				$DispoData->setInventarioId($inventario_id);
				$DispoData->setProveedorId($proveedor_id);
				$DispoData->setProducto('ROS');
				$DispoData->setVariedadId($reg['variedad_id']);
				$DispoData->setTallosxBunch($reg['tallos_x_bunch']);
				$DispoData->setClasifica($clasifica);

				$ArrDispoData[] = $DispoData;
			}//end foreach			

			//Convierte en cadena el array de color de ventas
			$result = $DispoBO->actualizarCerosStock($ArrDispoData);

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
	
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
	}//end function actualizarcerostockAction

	
}