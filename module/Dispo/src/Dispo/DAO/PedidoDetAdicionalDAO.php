<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoDetAdicionalData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoDetAdicionalDAO extends Conexion 
{
	private $table_name	= 'pedido_det_adicional';

	/**
	 * Ingresar
	 *
	 * @param PedidoDetAdicionalData $PedidoDetAdicionalData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoDetAdicionalData $PedidoDetAdicionalData)
	{
		$key    = array(
				'pedido_cab_id'						=> $PedidoDetAdicionalData->getGrupoPrecioCab(),
				'pedido_det_sec'					=> $PedidoDetAdicionalData->getPedidoDetAdicionalSec()				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoDetAdicionalData->getPedidoCabId(),
				'pedido_det_adicional_sec'             			=> $PedidoDetAdicionalData->getPedidoDetAdicionalSec(),
				'marcacion_sec'		            				=> $PedidoDetAdicionalData->getMarcacionSec(),
				'inventario_id'  	             			 	=> $PedidoDetAdicionalData->getInventarioId(),
				'variedad_id'  		      						=> $PedidoDetAdicionalData->getVariedadId(),
				'grado_id'        								=> $PedidoDetAdicionalData->getGradoId(),
				'tipo_caja_origen_estado'        				=> $PedidoDetAdicionalData->getTipoCajaOrigenEstado(),
				'tipo_caja_origen_id'        					=> $PedidoDetAdicionalData->getTipoCajaOrigenId(),
				'nro_cajas'        								=> $PedidoDetAdicionalData->getNroCajas(),
				'cantidad_bunch'   		     					=> $PedidoDetAdicionalData->getCantidadBunch(),
				'tallos_x_bunch'	        					=> $PedidoDetAdicionalData->getTallosxBunch(),
				'tallos_total'    		    					=> $PedidoDetAdicionalData->getTallosTotal(),
				'precio'        								=> $PedidoDetAdicionalData->getPrecio(),
				'total'        									=> $PedidoDetAdicionalData->getTotal(),
				'agencia_carga_id'        						=> $PedidoDetAdicionalData->getAgenciaCargaId(),
				'comentario'        							=> $PedidoDetAdicionalData->getComentario(),
				'estado'        								=> $PedidoDetAdicionalData->getEstado(),
				'fecha_pedido'        							=> $PedidoDetAdicionalData->getFechaPedido(),
				'usuario_cliente_id'        					=> $PedidoDetAdicionalData->getUsuarioClienteId(),
				'usuario_venta_id'        						=> $PedidoDetAdicionalData->getUsuarioVentaId(),
				'activo'					        			=> $PedidoDetAdicionalData->getActivo()	
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $PedidoCabId;
		return $PedidoDetAdicionalSec;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param PedidoDetAdicionalData $PedidoDetAdicionalData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoDetAdicionalData $PedidoDetAdicionalData)
	{
		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoDetAdicionalData->getGrupoPrecioCab(),
				'pedido_det_sec'						      => $PedidoDetAdicionalData->getVariedadId()
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoDetAdicionalData->getPedidoCabId(),
				'pedido_det_adicional_sec'             			=> $PedidoDetAdicionalData->getPedidoDetAdicionalSec(),
				'marcacion_sec'		            				=> $PedidoDetAdicionalData->getMarcacionSec(),
				'inventario_id'  	             			 	=> $PedidoDetAdicionalData->getInventarioId(),
				'variedad_id'  		      						=> $PedidoDetAdicionalData->getVariedadId(),
				'grado_id'        								=> $PedidoDetAdicionalData->getGradoId(),
				'tipo_caja_origen_estado'        				=> $PedidoDetAdicionalData->getTipoCajaOrigenEstado(),
				'tipo_caja_origen_id'        					=> $PedidoDetAdicionalData->getTipoCajaOrigenId(),
				'nro_cajas'        								=> $PedidoDetAdicionalData->getNroCajas(),
				'cantidad_bunch'   		     					=> $PedidoDetAdicionalData->getCantidadBunch(),
				'tallos_x_bunch'	        					=> $PedidoDetAdicionalData->getTallosxBunch(),
				'tallos_total'    		    					=> $PedidoDetAdicionalData->getTallosTotal(),
				'precio'        								=> $PedidoDetAdicionalData->getPrecio(),
				'total'        									=> $PedidoDetAdicionalData->getTotal(),
				'agencia_carga_id'        						=> $PedidoDetAdicionalData->getAgenciaCargaId(),
				'comentario'        							=> $PedidoDetAdicionalData->getComentario(),
				'estado'        								=> $PedidoDetAdicionalData->getEstado(),
				'fecha_pedido'        							=> $PedidoDetAdicionalData->getFechaPedido(),
				'usuario_cliente_id'        					=> $PedidoDetAdicionalData->getUsuarioClienteId(),
				'usuario_venta_id'        						=> $PedidoDetAdicionalData->getUsuarioVentaId(),
				'activo'					        			=> $PedidoDetAdicionalData->getActivo()	
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoDetAdicionalData->getPedidoCabId();
		return $PedidoDetAdicionalData->getPedidoDetAdicionalSec();
	}//end function modificar


	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @return \Dispo\Data\PedidoDetAdicionalData|NULL
	 */
	public function consultar($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoDetAdicionalData 		    = new PedidoDetAdicionalData();

		$sql = 	' SELECT pedido_det_adicional.* '.
				' FROM pedido_det_adicional '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				'   and pedido_det_adicional_sec = :pedido_det_adicional_sec';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_adicional_sec',$pedido_det_adcional_sec);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$PedidoDetAdicionalData->setPedidoCabId					($row['pedido_cab_id']);
			$PedidoDetAdicionalData->setPedidoDetAdicionalSec		($row['pedido_det_adicional_sec']);
			$PedidoDetAdicionalData->setMarcacionSec	    		($row['marcacion_sec']);
			$PedidoDetAdicionalData->setInventarioId				($row['inventario_id']);
			$PedidoDetAdicionalData->setVariedadId					($row['variedad_id']);
			$PedidoDetAdicionalData->setGradoId						($row['grado_id']);
			$PedidoDetAdicionalData->setTipoCajaOrigenEstado		($row['tipo_caja_origen_estado']);
			$PedidoDetAdicionalData->setTipoCajaOrigenId			($row['tipo_caja_origen_id']);
			$PedidoDetAdicionalData->setNroCajas					($row['nro_cajas']);
			$PedidoDetAdicionalData->setCantidadBunch				($row['cantidad_bunch']);
			$PedidoDetAdicionalData->setTallosxBunch				($row['tallos_x_bunch']);
			$PedidoDetAdicionalData->setTallosTotal					($row['tallos_total']);
			$PedidoDetAdicionalData->setPrecio						($row['precio']);
			$PedidoDetAdicionalData->setTotal						($row['total']);
			$PedidoDetAdicionalData->setAgenciaCargaId				($row['agencia_carga_id']);
			$PedidoDetAdicionalData->setComentario					($row['comentario']);
			$PedidoDetAdicionalData->setEstado						($row['estado']);
			$PedidoDetAdicionalData->setFechaPedido					($row['fecha_pedido']);
			$PedidoDetAdicionalData->setUsuarioClienteId			($row['usuario_cliente_id']);
			$PedidoDetAdicionalData->setUsuarioVentaId				($row['usuario_venta_id']);
			$PedidoDetAdicionalData->setActivo						($row['activo']);
				
				
			return $PedidoDetAdicionalData;
		}else{
			return null;
		}//end if

	}//end function consultar


	
	
}//end class
?>
