<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoDetContraOfertaData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoDetContraOfertaDAO extends Conexion 
{
	private $table_name	= 'pedido_det_contraoferta';

	/**
	 * Ingresar
	 *
	 * @param PedidoDetContraOfertaData $PedidoDetContraOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoDetContraOfertaData $PedidoDetContraOfertaData)
	{
		$key    = array(
				'pedido_cab_id'						=> $PedidoDetContraOfertaData->getGrupoPrecioCab(),
				'pedido_det_sec'					=> $PedidoDetContraOfertaData->getPedidoDetSec(),
				'fecha'								=> $PedidoDetContraOfertaData->getFecha()
				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoDetContraOfertaData->getPedidoCabId(),
				'pedido_det_sec'             					=> $PedidoDetContraOfertaData->getPedidoDetSec(),
				'fecha'				            				=> $PedidoDetContraOfertaData->getFecha(),
				'precio_original'  	             			 	=> $PedidoDetContraOfertaData->getPrecioOriginal(),
				'precio_contraoferta'      						=> $PedidoDetContraOfertaData->getPrecioContraoferta(),
				'comentario'      								=> $PedidoDetContraOfertaData->getComentario(),
				'estado'				        				=> $PedidoDetContraOfertaData->getEstado(),
				'usuario_cliente_id'        					=> $PedidoDetContraOfertaData->getUsuarioClienteId(),
				'usuario_venta_id'      						=> $PedidoDetContraOfertaData->getUsuarioVentaId(),
				'activo'   		     							=> $PedidoDetContraOfertaData->getActivo()	
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $PedidoCabId;
		return $PedidoDetSec;
		return $Fecha;
		
	}//end function ingresar


	/**
	 * Modificar
	 *
	 * @param PedidoDetContraOfertaData $PedidoDetContraOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoDetContraOfertaData $PedidoDetContraOfertaData)
	{
		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoDetContraOfertaData->getPedidoCabId(),
				'pedido_det_sec'						      => $PedidoDetContraOfertaData->getPedidoDetSec(),
				'fecha'									      => $PedidoDetContraOfertaData->getFecha()
				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoDetContraOfertaData->getPedidoCabId(),
				'pedido_det_sec'             					=> $PedidoDetContraOfertaData->getPedidoDetSec(),
				'fecha'				            				=> $PedidoDetContraOfertaData->getFecha(),
				'precio_original'  	             			 	=> $PedidoDetContraOfertaData->getPrecioOriginal(),
				'precio_contraoferta'      						=> $PedidoDetContraOfertaData->getPrecioContraoferta(),
				'comentario'      								=> $PedidoDetContraOfertaData->getComentario(),
				'estado'				        				=> $PedidoDetContraOfertaData->getEstado(),
				'usuario_cliente_id'        					=> $PedidoDetContraOfertaData->getUsuarioClienteId(),
				'usuario_venta_id'      						=> $PedidoDetContraOfertaData->getUsuarioVentaId(),
				'activo'   		     							=> $PedidoDetContraOfertaData->getActivo()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoDetContraOfertaData->getPedidoCabId();
		return $PedidoDetContraOfertaData->getPedidoDetSec();
		return $PedidoDetContraOfertaData->getFecha();
	}//end function modificar


	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @param int $fecha
	 * @return \Dispo\Data\PedidoDetContraOfertaData|NULL
	 */
	public function consultar($pedido_cab_id, $pedido_det_sec,$fecha)
	{
		$PedidoDetContraOfertaData 		    = new PedidoDetContraOfertaData();

		$sql = 	' SELECT pedido_det_contraoferta.* '.
				' FROM pedido_det_contraoferta '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				' and pedido_det_sec = :pedido_det_sec'.
				' and fecha=: fecha';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_sec',$pedido_det_sec);
		$stmt->bindValue(':fecha',$fecha);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$PedidoDetContraOfertaData->setPedidoCabId					($row['pedido_cab_id']);
			$PedidoDetContraOfertaData->setPedidoDetSec					($row['pedido_det_adicional_sec']);
			$PedidoDetContraOfertaData->setFecha						($row['fecha']);
			$PedidoDetContraOfertaData->setPrecioOriginal				($row['precio_original']);
			$PedidoDetContraOfertaData->setPrecioContraoferta			($row['precio_contraoferta']);
			$PedidoDetContraOfertaData->setComentario					($row['comentario']);
			$PedidoDetContraOfertaData->setEstado						($row['estado']);
			$PedidoDetContraOfertaData->setUsuarioClienteId				($row['usuario_cliente_id']);
			$PedidoDetContraOfertaData->setUsuarioVentaId				($row['usuario_venta_id']);
			$PedidoDetContraOfertaData->setActivo						($row['activo']);
				
			return $PedidoDetContraOfertaData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class
?>
