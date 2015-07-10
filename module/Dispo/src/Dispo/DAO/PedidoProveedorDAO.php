<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoProveedorData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoProveedorDAO extends Conexion 
{
	private $table_name	= 'pedido_proveedor';

	/**
	 * Ingresar
	 *
	 * @param PedidoProveedorData $PedidoProveedorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoProveedorData $PedidoProveedorData)
	{
		$key    = array(
				'pedido_cab_id'		=> $PedidoProveedorData->getPedidoCabId(),
				'pedido_det_sec'	=> $PedidoProveedorData->getPedidoDetSec(),
				'proveedor_id'		=> $PedidoProveedorData->getProveedorId()
		);
		$record = array(
				'pedido_cab_id'		=> $PedidoProveedorData->getPedidoCabId(),
				'pedido_det_sec'    => $PedidoProveedorData->getPedidoDetSec(),
				'proveedor_id'		=> $PedidoProveedorData->getProveedorId(),
				'nro_cajas'  	    => $PedidoProveedorData->getNroCajas(),
				'cantidad_bunch'    => $PedidoProveedorData->getCantidadBunch(),
				'tallos_x_bunch'	=> $PedidoProveedorData->getTallosxBunch(),
				'tallos_total'      => $PedidoProveedorData->getTallosTotal(),
				'variedad_id'       => $PedidoProveedorData->getVariedadId(),
				'grado_id'        	=> $PedidoProveedorData->getGradoId(),
				'precio'      		=> $PedidoProveedorData->getPrecio(),
				'total'   		    => $PedidoProveedorData->getTotal(),	
				//'fec_exportado'   	=> $PedidoProveedorData->getFecExportado()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $key;		
	}//end function ingresar


	/**
	 * Modificar
	 *
	 * @param PedidoProveedorData $PedidoProveedorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoProveedorData $PedidoProveedorData)
	{
/*		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoProveedorData->getPedidoCabId(),
				'pedido_det_sec'						      => $PedidoProveedorData->getPedidoDetSec(),
				'proveedor_id'								  => $PedidoProveedorData->getProveedorId()
				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoProveedorData->getPedido_cabId(),
				'pedido_det_sec'             					=> $PedidoProveedorData->getPedidoDetSec(),
				'proveedor_id'				            		=> $PedidoProveedorData->getProveedorId(),
				'nro_cajas'  	             			 		=> $PedidoProveedorData->getNroCajas(),
				'cantidad_bunch'      							=> $PedidoProveedorData->getCantidadBunch(),
				'tallos_x_bunch'				        		=> $PedidoProveedorData->getTallosxBunch(),
				'tallos_total'        							=> $PedidoProveedorData->getTallosTotal(),
				'variedad_id'        							=> $PedidoProveedorData->getVariedadId(),
				'grado_id'        								=> $PedidoProveedorData->getGradoId(),
				'precio'      									=> $PedidoProveedorData->getPrecio(),
				'total'   		     							=> $PedidoProveedorData->getTotal(),	
				'fec_exportado'   		     					=> $PedidoProveedorData->getFecExportado()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
*/		
	}//end function modificar


	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @param string $proveedor_id
	 * @return \Dispo\Data\PedidoProveedorData|NULL
	 */
	public function consultar($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoProveedorData 		    = new PedidoProveedorData();

		$sql = 	' SELECT pedido_proveedor.* '.
				' FROM pedido_proveedor '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				' and pedido_det_sec = :pedido_det_sec'.
				'and proveedor_id = :proveedor_id';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_sec',$pedido_det_sec);
		$stmt->bindValue(':proveedor_id',$proveedor_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$PedidoProveedorData->setPedidoCabId					($row['pedido_cab_id']);
			$PedidoProveedorData->setPedidoDetSec					($row['pedido_det_adicional_sec']);
			$PedidoProveedorData->setProveedorId					($row['proveedor_id']);
			$PedidoProveedorData->setNroCajas						($row['nro_cajas']);
			$PedidoProveedorData->setCantidadBunch					($row['cantidad_bunch']);
			$PedidoProveedorData->setTallosxBunch					($row['tallos_x_bunch']);
			$PedidoProveedorData->setTallosTotal					($row['tallos_total']);
			$PedidoProveedorData->setVariedadId						($row['variedad_id']);
			$PedidoProveedorData->setGradoId						($row['grado_id']);
			$PedidoProveedorData->setPrecio							($row['precio']);
			$PedidoProveedorData->setTotal							($row['total']);
			$PedidoProveedorData->setFecExportado					($row['fec_exportado']);
			
			return $PedidoProveedorData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class
?>
