<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\PedidoCabDAO;
use Dispo\DAO\PedidoDetDAO;
use Dispo\Data\PedidoCabData;
use Dispo\Data\PedidoDetData;



class PedidoBO extends Conexion
{

	/**
	 * Permite crear un Pedido o agregar detalles a un Pedido ya Existente
	 * 
	 * @param int|null $pedido_cab_id
	 * @param string $cliente_id
	 * @param int $usuario_cliente_id
	 * @param int $usuario_vendedor_id
	 * @param int $marcacion_sec
	 * @param string $agencia_carga_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param string $tipo_caja_id
	 * @param int $nro_cajas_seleccionada
	 * @return array
	 */
	public function addItem($pedido_cab_id, $cliente_id, $usuario_cliente_id, $usuario_vendedor_id, $marcacion_sec, $agencia_carga_id, 
						    $variedad_id, $grado_id, $tipo_caja_id, $nro_cajas_seleccionada)
	{
		$DispoBO		= new DispoBO();		
		$PedidoCabDAO 	= new PedidoCabDAO();
		$PedidoDetDAO 	= new PedidoDetDAO();
		$PedidoCabData	= new PedidoCabData();
		$PedidoDetData	= new PedidoDetData();

		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$DispoBO->setEntityManager($this->getEntityManager());		
			$PedidoCabDAO->setEntityManager($this->getEntityManager());
			$PedidoDetDAO->setEntityManager($this->getEntityManager());
			

			/*
			 * 1. Se establece cual es el usuario que va ingresar el pedido o anadir items al detalle
			 */
			if (empty($usuario_vendedor_id)){
				$usuario_ing_id = $usuario_cliente_id;
			}else{
				$usuario_ing_id = $usuario_vendedor_id;
			}

			
			/*
			 * 2. Revisar si tiene un pedido activo 
			 */
			if (empty($pedido_cab_id))
			{
				//Se llena los campos
				//$PedidoCabData->setId				($pedido_cab_id);
				$PedidoCabData->setFecha			(\Application\Classes\Fecha::getFechaHoraActualServidor());
				$PedidoCabData->setClienteId		($cliente_id);
				$PedidoCabData->setTotal			(0);
				$PedidoCabData->setComentario		('');
				$PedidoCabData->setEstado			(\Application\Constants\Pedido::ESTADO_COMPRANDO);
				$PedidoCabData->setUsuarioClienteId	($usuario_cliente_id);
				$PedidoCabData->setUsuarioIngId		($usuario_ing_id);
	
				$pedido_cab_id 		= $PedidoCabDAO->ingresar($PedidoCabData);
			}//end if
			
			
			/*
			 * 3. Se obtiene la DISPO ACTUAL
			 */
			$result_dispo = $DispoBO->getDispo($cliente_id, $usuario_cliente_id, $marcacion_sec, $tipo_caja_id, $variedad_id, $grado_id);
			$reg_dispo	  = $result_dispo[0];
			
			
			/*
			 * 4. Se registra el detalle
			 */
			$pedido_cab_sec = $PedidoDetDAO->consultarMaximaSecuencia($pedido_cab_id);
			$pedido_cab_sec++;
			
			$bunch_total 	= $nro_cajas_seleccionada * $reg_dispo['cantidad_bunch']; //Se multiplica por la cantidad de bunch que tiene una caja
			$tallos_total	= $bunch_total * $reg_dispo['tallos_x_bunch'];
			$precio_total	= $tallos_total *  $reg_dispo['precio']; //Se multiplica el precio del tallo
			
			$PedidoDetData->setPedidoCabId			($pedido_cab_id);
			$PedidoDetData->setPedidoDetSec 		($pedido_cab_sec);
			$PedidoDetData->setMarcacionSec			($marcacion_sec);
			$PedidoDetData->setInventarioId			($reg_dispo['inventario_id']);
			$PedidoDetData->setVariedadId			($variedad_id);
			$PedidoDetData->setGradoId				($grado_id);
			$PedidoDetData->setTipoCajaId			($tipo_caja_id);
			$PedidoDetData->setTipoCajaOrigenEstado	($reg_dispo['tipo_caja_origen_estado']);
			$PedidoDetData->setTipoCajaOrigenId		($reg_dispo['tipo_caja_origen_id']);
			$PedidoDetData->setNroCajas				($nro_cajas_seleccionada);
			$PedidoDetData->setCantidadBunch		($bunch_total);
			$PedidoDetData->setTallosxBunch			($reg_dispo['tallos_x_bunch']);
			$PedidoDetData->setTallosTotal			($tallos_total);
			$PedidoDetData->setPrecio				($reg_dispo['precio']);
			$PedidoDetData->setTotal				($precio_total);
			$PedidoDetData->setAgenciaCargaId		($agencia_carga_id);
			$PedidoDetData->setComentario			('');
			$PedidoDetData->setPedidoCabOfertaId 	(null);
			$PedidoDetData->setPedidoDetOfertaSec 	(null);
			$PedidoDetData->setEstadoRegOferta		(0);
			$PedidoDetData->setUsuarioIngId			($usuario_ing_id);
			$PedidoDetData->setUsuarioModId			($usuario_ing_id);
			$key_det =  $PedidoDetDAO->ingresar($PedidoDetData);
			
			
			/*
			 * 5. Actualizar el total de la CABECERA de PEDIDO
			 */
			$PedidoCabDAO->actualizarTotal($pedido_cab_id);
			
			
			/*
			 * 6. Devuelve el resultado del registro del PEDIDO
			 */
			$result['pedido_cab_id'] 	= $pedido_cab_id;
			$result['pedido_cab_sec'] 	= $pedido_cab_sec;
			$result['variedad_nombre']	= $reg_dispo['variedad_nombre'];
			
			$this->getEntityManager()->getConnection()->commit();
			return $result;
			
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}			
	}//end function addItem


	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return number
	 */
	function consultarNroItemsComprandoPorPedido($pedido_cab_id)
	{
		$PedidoDetDAO 	= new PedidoDetDAO();
		
		$PedidoDetDAO->setEntityManager($this->getEntityManager());
		
		$nro_items = $PedidoDetDAO->consultarNroItemsComprandoPorPedido($pedido_cab_id);
		
		return $nro_items;
	}//end function consultarNroItemsComprandoPorPedido
	
	

	/**
	 * 
	 * @param string $cliente_id
	 * @return array
	 */
	function consultarUltimoPedidoComprando($cliente_id)
	{
		$PedidoCabDAO 	= new PedidoCabDAO();
		
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
		
		$reg = $PedidoCabDAO->consultarUltimoPedidoComprando($cliente_id);
		
		return $reg;
	}//end function consultarUltimoPedidoComprando
	
	
	
	
	/**
	 * 
	 * @param string $cliente_id
	 * @param int $marcacion_sec
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
/*	function getComboPorClienteId($cliente_id, $marcacion_sec, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$MarcacionDAO = new MarcacionDAO();
		
		$MarcacionDAO->setEntityManager($this->getEntityManager());

		$result = $MarcacionDAO->consultarPorClienteId($cliente_id);
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'marcacion_sec', 'nombre', $marcacion_sec, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function listado
*/

	
	
}//end class PedidoBO
