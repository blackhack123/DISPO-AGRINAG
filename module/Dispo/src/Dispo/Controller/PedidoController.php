<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\PedidoBO;
use Dispo\BO\DispoBO;
use Dispo\BO\Dispo\BO;
use Dispo\Data\PedidoCabData;
use Dispo\BO\AgenciaCargaBO;
use Dispo\Data\PedidoDetData;


class PedidoController extends AbstractActionController
{

	public function additemAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor(); //Controla el inicio de sesion

			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO				= new PedidoBO();
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			//Se establecen los paremetros para realizar el AddItem
			$usuario_id			= $SesionUsuarioPlugin->getClienteUsuarioId();
			$vendedor_usuario_id= $SesionUsuarioPlugin->getVendedorUsuarioId();
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$marcacion_sec 		= $SesionUsuarioPlugin->getClienteSeleccionMarcacionSec();
			$agencia_carga_id 	= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
			$pedido_cab_id		= $SesionUsuarioPlugin->getClientePedidoCabIdActual();
			$usuario_calidad_id = $SesionUsuarioPlugin->getUserCalidadId();
			//$marcacion_punto_corte = $SesionUsuarioPlugin->getClienteSeleccionMarcacionPuntoCorte();
			$marcacion_punto_corte = $SesionUsuarioPlugin->getUserPuntoCorte();
			$producto_id		= $json['producto_id'];
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];
			$tallos_x_bunch		= $json['tallos_x_bunch'];
			$tipo_caja_id		= $json['tipo_caja_id'];
			$cantidad_order		= $json['cantidad_order'];
			$flag_oferta		= false;

			//CONFIGURACION ADICIONAL
			$PedidoBO->setCalidadId($usuario_calidad_id);		//Setea clasifica al PedidoBO (Calidad de la Flor)
			$PedidoBO->setPuntoCorte($marcacion_punto_corte);	//Setea el punto de corte del Pedido

			//Se obtiene la bandera de oferta y el hueso
			if (!(empty($json['hueso_variedad_id'])))
			{
				$flag_oferta = true;
				$hueso_producto_id		= $json['hueso_producto_id']; //NUEVO
				$hueso_variedad_id		= $json['hueso_variedad_id'];
				$hueso_grado_id			= $json['hueso_grado_id'];
				$hueso_tallos_x_bunch	= $json['tallos_x_bunch']; //NUEVO
				$hueso_tipo_caja_id		= $json['hueso_tipo_caja_id'];
				$hueso_cantidad_order	= $json['hueso_cantidad_order'];
			}//end if

			//Se verifica que el pedido_cab_id sea valido en la base de datos, en caso de no serlo se inicializa a CERO
			$reg_cabecera =  $PedidoBO->consultarPedidoCabecera($pedido_cab_id);
			if (empty($reg_cabecera))
			{
				$SesionUsuarioPlugin->setClientePedidoCabIdActual(0);
				$pedido_cab_id		= $SesionUsuarioPlugin->getClientePedidoCabIdActual();
			}//end if

			//Se invoca la llamada
			if ($flag_oferta == false)
			{
				$result = $PedidoBO->addItem($pedido_cab_id, $cliente_id, $usuario_id, $vendedor_usuario_id, $marcacion_sec, $agencia_carga_id, 
											 $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id, $cantidad_order);
			}else{
				list($result, $result_hueso) = $PedidoBO->addItemOferta($pedido_cab_id, $cliente_id, $usuario_id, $vendedor_usuario_id, $marcacion_sec, $agencia_carga_id,
													$producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id, $cantidad_order,
													$hueso_producto_id, $hueso_variedad_id, $hueso_grado_id, $hueso_tallos_x_bunch, $hueso_tipo_caja_id, $hueso_cantidad_order);
			}//end if

			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';						  //Se forza a responder OK, para que pueda ir por el evento finish de la LIBRERIA AJAX
			$response->respuesta_codex 		= $result['respuesta_code'];  //Se utiliza esta variable para no lo controle la LIBRERIA AJAX LAS NOVEDADES 
			if ($result['respuesta_code']=='OK'){
				$response->pedido_cab_id		= $result['pedido_cab_id'];
				$response->pedido_cab_sec		= $result['pedido_cab_sec'];				
				$response->respuesta_mensaje 	= $cantidad_order.' Box '.$result['variedad_nombre'].' '.$grado_id.' cm was added.';
				if (empty($pedido_cab_id))
				{
					$SesionUsuarioPlugin->setClientePedidoCabIdActual($result['pedido_cab_id']);
				}//end if				
			}else{
				$response->respuesta_mensaje 	= $result['respuesta_msg'];
			}//end if

			$json = new JsonModel(get_object_vars($response));
			return $json;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function additemAction	

	
	public function consultarnroitemspedidocomprandoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor(); //Controla el inicio de sesion

			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO				= new PedidoBO();
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$pedido_cab_id		= $SesionUsuarioPlugin->getClientePedidoCabIdActual();

			//Se invoca la llamada
			$nro_items = $PedidoBO->consultarNroItemsPorPedido($pedido_cab_id, \Application\Constants\Pedido::ESTADO_COMPRANDO);
			$response = new \stdClass();
			$response->nro_items_comprando		= $nro_items;
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje 		= '';

			$json = new JsonModel(get_object_vars($response));
			return $json;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end public function consultaritemsporpedidoAction

	

	public function consultarpedidoactualAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
	
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$PedidoBO				= new PedidoBO();
			$AgenciaCargaBO			= new AgenciaCargaBO();

			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$pedido_cab_actual_id		= $SesionUsuarioPlugin->getClientePedidoCabIdActual();
			$cliente_id					= $SesionUsuarioPlugin->getUserClienteId();
			
			
			if (empty($pedido_cab_actual_id))
			{
				$viewModel->pedido_cab_id				= null;
				$viewModel->cbo_agencia_carga			= null;
			}else{
				$viewModel->pedido_cab_id				= $pedido_cab_actual_id;
				$viewModel->cbo_agencia_carga_all		= $AgenciaCargaBO->getComboTodos("", '&lt;Cargo Agency&gt;');
				$viewModel->cbo_agencia_carga_asignada	= $AgenciaCargaBO->getComboPorCliente($cliente_id, "", '&lt;Cargo Agency&gt;');
			}//end if
			//Se consulta la dispo, considerando los criterios de busqueda
			$data = $SesionUsuarioPlugin->getRecord();
			$viewModel->identidad_usuario 	= $data;

			$this->layout($SesionUsuarioPlugin->getUserLayout());
	
			$viewModel->setTemplate('dispo/pedido/pedido_actual.phtml');
			return $viewModel;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end public consultarordenactualAction
	
	

//	MORONITOR A FULL,  AHORA ES LA FINAL
	public function consultardetallehtmlAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
		
			$config = $this->getServiceLocator()->get('Config');
				
			$PedidoBO				= new PedidoBO();
		
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			//Se consulta la dispo, considerando los criterios de busqueda
			$pedido_cab_actual_id		= $SesionUsuarioPlugin->getClientePedidoCabIdActual();
		
			if (empty($pedido_cab_actual_id))
			{
				$viewModel->pedido_cab_id				= '';
				$viewModel->nro_pedido_formateado		= '';
				$viewModel->marcacion_nombre			= '';
				$viewModel->pedido_fecha				= '';
				$viewModel->pedido_cab_estado			= '';
				$viewModel->pedido_comentario			= '';
				$viewModel->rs_pedido_det				= null;
			}else{
				list($reg_pedido_cab, $rs_pedido_det) 		= $PedidoBO->consultarPedido($pedido_cab_actual_id);

				$viewModel->pedido_cab_id				= $reg_pedido_cab['id'];
				$viewModel->nro_pedido_formateado		= \Application\Classes\Mascara::getNroPedidoFormateado($reg_pedido_cab['id'], $config['mascara_pedido']);
				$viewModel->marcacion_nombre			= $reg_pedido_cab['marcacion_nombre'];
				$viewModel->agencia_carga_nombre		= $reg_pedido_cab['agencia_carga_nombre'];
				$viewModel->pedido_fecha				= $reg_pedido_cab['fecha'];
				$viewModel->pedido_cab_estado			= $reg_pedido_cab['estado'];
				$viewModel->pedido_comentario			= $reg_pedido_cab['comentario'];
				$viewModel->rs_pedido_det				= $rs_pedido_det;
			}//end if
		
			$data = $SesionUsuarioPlugin->getRecord();
			$viewModel->identidad_usuario 	= $data;

			$viewModel->setTemplate('dispo/pedido/pedido_actual_detalle.phtml');
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
	}//end function consultardetallehtmlAction
	
	
	
	
	public function eliminardetalleAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
				
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO 				= new PedidoBO();
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$SesionUsuarioPlugin->isLoginClienteVendedor();
			
			$pedido_cab_id		= intval($this->params()->fromPost('pedido_cab_id',''));
			$pedido_det_sec		= intval($this->params()->fromPost('pedido_det_sec',''));

			//Consulta la marcacion para obtener el nombre
			$nro_reg_det		= $PedidoBO->eliminarPorPedidoCabIdPorPedidoDetSec($pedido_cab_id, $pedido_det_sec);
			//$nro_reg_det = $PedidoBO->eliminarPorPedidoCabIdPorPedidoDetSec($pedido_cab_id, $pedido_det_sec);
			
			//En caso de ser CERO los detalles de los registros, la variable de session de PedidoCabIdActual debe de encerarse
			if ($nro_reg_det == 0)
			{				
				$SesionUsuarioPlugin->setClientePedidoCabIdActual(0);
			}//end function
			
			
			$response = new \stdClass();
			$response->nro_reg_det				= $nro_reg_det;
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';

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
	}//end public eliminardetallepedidoAction
	
	
	
	
	public function grabarcomentarioAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO 				= new PedidoBO();
			$PedidoCabData			= new PedidoCabData();
						
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$usuario_id			= $SesionUsuarioPlugin->getUsuarioId();
			$pedido_cab_id		= intval($this->params()->fromPost('pedido_cab_id',''));
			$comentario			= $this->params()->fromPost('comentario','');
			
			$PedidoCabData->setId			($pedido_cab_id);
			$PedidoCabData->setComentario	($comentario);
			$PedidoCabData->setUsuarioModId	($usuario_id);			
		
			//Consulta la marcacion para obtener el nombre
			$result		= $PedidoBO->grabarComentario($PedidoCabData);
			//$nro_reg_det = $PedidoBO->eliminarPorPedidoCabIdPorPedidoDetSec($pedido_cab_id, $pedido_det_sec);
				
			//En caso de ser CERO los detalles de los registros, la variable de session de PedidoCabIdActual debe de encerarse
			$response = new \stdClass();
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';
		
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
	}//end public grabarcomentarioAction
	
	
	
	
	public function grabarcambiocargaagenciaAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO 				= new PedidoBO();
			$PedidoDetData			= new PedidoDetData();
			$PedidoCabData			= new PedidoCabData();
	
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$usuario_id			= $SesionUsuarioPlugin->getUsuarioId();
			$pedido_cab_id		= intval($this->params()->fromPost('pedido_cab_id',''));
			$pedido_det_sec		= intval($this->params()->fromPost('pedido_det_sec',''));			
			$agencia_carga_id	= $this->params()->fromPost('agencia_carga_id','');

			/*$PedidoDetData->setPedidoCabId($pedido_cab_id);
			$PedidoDetData->setPedidoDetSec($pedido_det_sec);
			$PedidoDetData->setAgenciaCargaId($agencia_carga_id);
			$PedidoDetData->setUsuarioModId	($usuario_id);
			*/
			$PedidoCabData->setId($pedido_cab_id);
			$PedidoCabData->setAgenciaCargaId($agencia_carga_id);
			$PedidoCabData->setUsuarioModId($usuario_id);

			//Realiza el cambio de la marcacion
			//$result		= $PedidoBO->cambiarAgenciaCarga($PedidoDetData);
			//$result		= $PedidoBO->consultarDetallePedido($pedido_cab_id, $pedido_det_sec, \Application\Constants\ResultType::MATRIZ);
			$result		= $PedidoBO->cambiarAgenciaCarga($PedidoCabData);
			$result		= $PedidoBO->consultarPedidoCabecera($pedido_cab_id);
		

			//En caso de ser CERO los detalles de los registros, la variable de session de PedidoCabIdActual debe de encerarse
			$response = new \stdClass();
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';
			$response->agencia_carga_id			= $result['agencia_carga_id'];  	//Se retorna el id, para confirmar en el frontend el cambio			
			$response->agencia_carga_nombre		= $result['agencia_carga_nombre'];	//Se retorna el nombre, para confirmar en el frontend el cambio
	
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
	}//end public grabarcambiocargaagenciaAction
		
	
	
	
	public function confirmarAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO 				= new PedidoBO();
			$PedidoCabData			= new PedidoCabData();
		
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin->isLoginClienteVendedor();
		
			$usuario_cliente_id		= $SesionUsuarioPlugin->getClienteUsuarioId();
			$usuario_vendedor_id	= $SesionUsuarioPlugin->getVendedorUsuarioId();
			$pedido_cab_id			= intval($this->params()->fromPost('pedido_cab_id',''));
				
			//Consulta la marcacion para obtener el nombre
			$result		= $PedidoBO->confirmar($pedido_cab_id, $usuario_cliente_id, $usuario_cliente_id, $usuario_vendedor_id);
			
			//Si el resultado es correcto, entonces se inicializan las variables de sesion del pedido actual
			if ($result['respuesta']=='OK')
			{
				$SesionUsuarioPlugin->setClientePedidoCabIdActual	(null);
			}//end if
			
			//En caso de ser CERO los detalles de los registros, la variable de session de PedidoCabIdActual debe de encerarse
			$response = new \stdClass();
			$response->respuesta_code 			= 'OK';			
			$response->respuesta_codex 			= $result['respuesta'];
			if (empty($result['respuesta_descripcion'])){
				$response->respuesta_mensaje	= '';
			}else{
				$response->respuesta_mensaje	= $result['respuesta_descripcion'];
			}//end if
			$response->novedades_pedido_det		= $result['novedades_pedido_det'];
			$response->nro_dias_procesa			= $result['nro_dias_procesa'];
			$response->dia_semana_procesa		= $result['dia_semana_procesa'];
			$response->html						= '';
			if ($result['respuesta']=='NOVEDAD')
			{
				$viewModel 	= new ViewModel(array("result" => $result['novedades_pedido_det']));
				$viewModel->setTemplate('dispo/pedido/pedidodetallesinstock.phtml');
				$viewModel->setTerminal(true);
				$viewRender = $this->getServiceLocator()->get('ViewRenderer');
				$html = $viewRender->render($viewModel);
				
				$response->html = $html;
			}//end if
			
			$json = new JsonModel(get_object_vars($response));
			return $json;

		}catch (\Dispo\Exception\PedidoException $e){
			$response = new \stdClass();
			$response->respuesta_code 	= 'OK';
			$response->respuesta_codex 	= 'NOOK';			
			$response->respuesta_msg = $e->getMessage();
		
			$json = new JsonModel(get_object_vars($response));
			return $json;			
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}//end try
	}//end function confirmarAction
	
	
	
	public function actualizarnrocajasAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor(); //Controla el inicio de sesion

			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO				= new PedidoBO();
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			//Se establecen los paremetros para realizar el AddItem
			//$usuario_id			= $SesionUsuarioPlugin->getClienteUsuarioId();
			$usuario_id			= $SesionUsuarioPlugin->getUsuarioId();
			//$vendedor_usuario_id= $SesionUsuarioPlugin->getVendedorUsuarioId();
			$pedido_cab_id		= $json['pedido_cab_id'];
			$pedido_det_sec		= $json['pedido_det_sec'];
			$nro_cajas_en_stock	= $json['nro_cajas_en_stock'];

			$result = $PedidoBO->actualizarNroCajas($pedido_cab_id, $pedido_det_sec, $nro_cajas_en_stock, $usuario_id);

			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';	 					//Se forza a responder OK, para que pueda ir por el evento finish de la LIBRERIA AJAX			
			$response->respuesta_codex 		= $result['respuesta_code'];						  
			$response->respuesta_mensaje 	= $result['respuesta_msg'];
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function actualizarnrocajasAction
	
	
	
	
	public function listadoclienteAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			
			$config = $this->getServiceLocator()->get('Config');
				
			$PedidoBO				= new PedidoBO();
		
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$condiciones = array('cliente_id' => $cliente_id);
			$result	= $PedidoBO->listado($condiciones);
			$viewModel->result				= $result;
			$viewModel->mascara_pedido		= $config['mascara_pedido'];
			
/*			$data = $SesionUsuarioPlugin->getRecord();
			$viewModel->identidad_usuario 	= $data;
*/
			$this->layout($SesionUsuarioPlugin->getUserLayout());

			$viewModel->setTemplate('dispo/pedido/pedido_listado_cliente.phtml');
			return $viewModel;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadoclienteAction
	
	
	
	public function listadoclientedetallehtmlAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
			$cliente_id 			= $SesionUsuarioPlugin->getUserClienteId();
					
			$config = $this->getServiceLocator()->get('Config');
		
			$PedidoBO				= new PedidoBO();
			
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$condiciones = array('pedido_cab_id' 	=> $json['pedido_cab_id'],
								 'cliente_id'		=> $cliente_id
								);
			//Se pasa el ID del Cliente para evitar que se consulte registro de pedidos no autorizados (hacking)
			$result = $PedidoBO->consultarPedidoDetalle($condiciones);

			$viewModel->result 	= $result;
		
			$viewModel->setTemplate('dispo/pedido/pedido_listado_cliente_detalle.phtml');
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
	}//end function listadoclientedetallehtml
	
	
	
	public function listadovendedorAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
	
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();
				
			$this->layout($SesionUsuarioPlugin->getUserLayout());
	
			$viewModel->setTemplate('dispo/pedido/pedido_listado_vendedor.phtml');
			return $viewModel;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadovendedorAction


	
	public function listadovendedordataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
		
			$PedidoBO = new PedidoBO();
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$cliente_nombre		= $json['cliente_nombre'];
			$fecha_ini			= $json['fecha_ini'];
			$fecha_fin			= $json['fecha_fin'];
			$desglosar_fincas	= $json['desglosar_fincas'];
			
			$condiciones = array(
					"cliente_nombre"	=> $cliente_nombre,
					"fecha_ini"			=> $fecha_ini,
					"fecha_fin"			=> $fecha_fin,
					"desglosar_fincas"	=> $desglosar_fincas
			);
			$result = $PedidoBO->listadoVendedor($condiciones);
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
	}//end function listadovendedordataAction 
	
	
	public function grabarmarcaAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PedidoBO 				= new PedidoBO();
			$PedidoDetData			= new PedidoDetData();
	
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$usuario_id			= $SesionUsuarioPlugin->getUsuarioId();
			$pedido_cab_id		= intval($this->params()->fromPost('pedido_cab_id',''));
			$pedido_det_sec		= intval($this->params()->fromPost('pedido_det_sec',''));
			$marca			= $this->params()->fromPost('marca','');
				
			$PedidoDetData->setPedidoCabId($pedido_cab_id);
			$PedidoDetData->setPedidoDetSec($pedido_det_sec);			
			$PedidoDetData->setMarca($marca);
			$PedidoDetData->setUsuarioModId	($usuario_id);
	
			//Consulta la marcacion para obtener el nombre
			$result		= $PedidoBO->grabarMarca($PedidoDetData);
			//$nro_reg_det = $PedidoBO->eliminarPorPedidoCabIdPorPedidoDetSec($pedido_cab_id, $pedido_det_sec);
	
			//En caso de ser CERO los detalles de los registros, la variable de session de PedidoCabIdActual debe de encerarse
			$response = new \stdClass();
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';
	
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
	}//end public grabarcomentarioAction
	
	
	
}//end controller