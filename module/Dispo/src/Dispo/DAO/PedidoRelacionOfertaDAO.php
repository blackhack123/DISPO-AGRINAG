<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoRelacionOfertaData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoRelacionOfertaDAO extends Conexion 
{
	private $table_name	= 'pedido_relacion_oferta';

	/**
	 * Ingresar
	 *
	 * @param PedidoRelacionOfertaData $PedidoRelacionOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoRelacionOfertaData $PedidoRelacionOfertaData)
	{
		$key    = array(
				'pedido_cab_id'						=> $PedidoRelacionOfertaData->getPedidoCabId(),
				'pedido_det_sec'					=> $PedidoRelacionOfertaData->getPedidoDetSec(),
				'pedido_det_sec_item_hueso'			=> $PedidoRelacionOfertaData->getPedidoDetSecItemHueso()
				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoRelacionOfertaData->getPedido_cabId(),
				'pedido_det_sec'             					=> $PedidoRelacionOfertaData->getPedidoDetSec(),
				'pedido_det_sec_item_hueso'				        => $PedidoRelacionOfertaData->getPedidoDetSecItemHueso()
			
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $PedidoCabId;
		return $PedidoDetSec;
		return $PedidoDetSecItemHueso;
		
	}//end function ingresar


	/**
	 * Modificar
	 *
	 * @param PedidoRelacionOfertaData $PedidoRelacionOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoRelacionOfertaData $PedidoRelacionOfertaData)
	{
		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoRelacionOfertaData->getPedidoCabId(),
				'pedido_det_sec'						      => $PedidoRelacionOfertaData->getPedidoDetSec(),
				'pedido_det_sec_item_hueso'					  => $PedidoRelacionOfertaData->getPedidoDetSecItemHueso()
				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoRelacionOfertaData->getPedido_cabId(),
				'pedido_det_sec'             					=> $PedidoRelacionOfertaData->getPedidoDetSec(),
				'pedido_det_sec_item_hueso'	            		=> $PedidoRelacionOfertaData->getPedidoDetSecItemHueso()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoRelacionOfertaData->getPedidoCabId();
		return $PedidoRelacionOfertaData->getPedidoDetSec();
		return $PedidoRelacionOfertaData->getPedidoDetSecItemHueso();
		
	}//end function modificar


	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @param int $pedido_det_sec_item_hueso
	 * @return \Dispo\Data\PedidoRelacionOfertaData|NULL
	 */
	public function consultar($pedido_cab_id, $pedido_det_sec,$pedido_det_sec_item_hueso )
	{
		$PedidoRelacionOfertaData 		    = new PedidoRelacionOfertaData();

		$sql = 	' SELECT pedido_relacion_oferta.* '.
				' FROM pedido_relacion_oferta '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				' and pedido_det_sec = :pedido_det_sec'.
				'and pedido_det_sec_item_hueso = :pedido_det_sec_item_hueso';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_sec',$pedido_det_sec);
		$stmt->bindValue(':pedido_det_sec_item_hueso',$proveedor_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$PedidoRelacionOfertaData->setPedidoCabId					($row['pedido_cab_id']);
			$PedidoRelacionOfertaData->setPedidoDetSec					($row['pedido_det_adicional_sec']);
			$PedidoRelacionOfertaData->setPedidoDetSecItemHueso			($row['pedido_det_sec_item_hueso']);
				
			return $PedidoRelacionOfertaData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class
?>
