<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoMixtoDetData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoMixtoDetDAO extends Conexion 
{
	private $table_name	= 'pedido_mixto_det';

	/**
	 * Ingresar
	 *
	 * @param PedidoMixtoDetData $PedidoMixtoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoMixtoDetData $PedidoMixtoDetData)
	{
		$key    = array(
				'pedido_cab_id'						=> $PedidoMixtoDetData->getGrupoPrecioCab(),
				'pedido_det_sec'					=> $PedidoMixtoDetData->getPedidoDetSec()				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoMixtoDetData->getPedido_cabId(),
				'pedido_det_sec'             					=> $PedidoMixtoDetData->getPedidoDetSec(),
				'inventario_id'				            		=> $PedidoMixtoDetData->getInventarioId(),
				'variedad_id'  	             			 		=> $PedidoMixtoDetData->getVariedadId(),
				'grado_id'      								=> $PedidoMixtoDetData->getGradoId(),
				'cantidad_bunch'      							=> $PedidoMixtoDetData->getCantidadBunch(),
				'tallos_x_bunch'				        		=> $PedidoMixtoDetData->getTallosxBunch(),
				'tallos_total'        							=> $PedidoMixtoDetData->getTallosTotal(),
				'precio'      									=> $PedidoMixtoDetData->getPrecio(),
				'total'   		     							=> $PedidoMixtoDetData->getTotal()	
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $PedidoCabId;
		return $PedidoDetSec;
		
	}//end function ingresar


	/**
	 * Modificar
	 *
	 * @param PedidoMixtoDetData $PedidoMixtoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoMixtoDetData $PedidoMixtoDetData)
	{
		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoMixtoDetData->getPedidoCabId(),
				'pedido_det_sec'						      => $PedidoMixtoDetData->getPedidoDetSec()
				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoMixtoDetData->getPedido_cabId(),
				'pedido_det_sec'             					=> $PedidoMixtoDetData->getPedidoDetSec(),
				'inventario_id'				            		=> $PedidoMixtoDetData->getInventarioId(),
				'variedad_id'  	             			 		=> $PedidoMixtoDetData->getVariedadId(),
				'grado_id'      								=> $PedidoMixtoDetData->getGradoId(),
				'cantidad_bunch'      							=> $PedidoMixtoDetData->getCantidadBunch(),
				'tallos_x_bunch'				        		=> $PedidoMixtoDetData->getTallosxBunch(),
				'tallos_total'        							=> $PedidoMixtoDetData->getTallosTotal(),
				'precio'      									=> $PedidoMixtoDetData->getPrecio(),
				'total'   		     							=> $PedidoMixtoDetData->getTotal()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoMixtoDetData->getPedidoCabId();
		return $PedidoMixtoDetData->getPedidoDetSec();
	}//end function modificar


	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @return \Dispo\Data\PedidoMixtoDetData|NULL
	 */
	public function consultar($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoMixtoDetData 		    = new PedidoMixtoDetData();

		$sql = 	' SELECT pedido_mixto_det.* '.
				' FROM pedido_mixto_det '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				' and pedido_det_sec = :pedido_det_sec';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_sec',$pedido_det_sec);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$PedidoMixtoDetData->setPedidoCabId						($row['pedido_cab_id']);
			$PedidoMixtoDetData->setPedidoDetSec					($row['pedido_det_adicional_sec']);
			$PedidoMixtoDetData->setInventarioId					($row['inventario_id']);
			$PedidoMixtoDetData->setVariedadId						($row['variedad_id']);
			$PedidoMixtoDetData->setGradoId							($row['grado_id']);
			$PedidoMixtoDetData->setCantidadBunch					($row['cantidad_bunch']);
			$PedidoMixtoDetData->setTallosxBunch					($row['tallos_x_bunch']);
			$PedidoMixtoDetData->setTallosTotal						($row['tallos_total']);
			$PedidoMixtoDetData->setPrecio							($row['precio']);
			$PedidoMixtoDetData->setTotal							($row['total']);
				
			return $PedidoMixtoDetData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class
?>
