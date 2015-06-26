<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\PedidoBO;


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
			$usuario_id			= $SesionUsuarioPlugin->getUsuarioId();
			$vendedor_usuario_id= $SesionUsuarioPlugin->getVendedorUsuarioId();
			$cliente_id 		= $SesionUsuarioPlugin->getUserClienteId();
			$marcacion_sec 		= $SesionUsuarioPlugin->getClienteSeleccionMarcacionId();
			$agencia_carga_id 	= $SesionUsuarioPlugin->getClienteSeleccionAgenciaId();
			$pedido_cab_id		= $SesionUsuarioPlugin->getClientePedidoCabIdActual();
			$variedad_id		= $json['variedad_id'];
			$grado_id			= $json['grado_id'];
			$tipo_caja_id		= $json['tipo_caja_id'];
			$cantidad_order		= $json['cantidad_order'];
			
			//Se invoca la llamada
			$result = $PedidoBO->addItem($pedido_cab_id, $cliente_id, $usuario_id, $vendedor_usuario_id, $marcacion_sec, $agencia_carga_id, $variedad_id, $grado_id, $tipo_caja_id, $cantidad_order);
			
			if (empty($pedido_cab_id))
			{
				$SesionUsuarioPlugin->setClientePedidoCabIdActual($result['pedido_cab_id']);
			}//end if
			
			$response = new \stdClass();
			$response->pedido_cab_id		= $result['pedido_cab_id'];
			$response->pedido_cab_sec		= $result['pedido_cab_sec'];			
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje 	= 'Se adiciono '.$cantidad_order.' cajas de '.$result['variedad_nombre'].' de '.$grado_id.' cms.';

			$json = new JsonModel(get_object_vars($response));
			return $json;
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function indexAction	

	
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
			$nro_items = $PedidoBO->consultarNroItemsComprandoPorPedido($pedido_cab_id);
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

}//end controller