<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoCabData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoCabDAO extends Conexion 
{
	private $table_name	= 'pedido_cab';

	/**
	 * Ingresar
	 *
	 * @param PedidoCabData $PedidoCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoCabData $PedidoCabData)
	{
		/*$key    = array(
				'id'						        => $PedidoCabData->getId(),
		);*/
		$record = array(
		//		'id'								=> $PedidoCabData->getId(),  //Se omite porque se configuro que la clave sea autoincrementable
				'fecha'		                    	=> $PedidoCabData->getFecha(),
				'cliente_id'                    	=> $PedidoCabData->getClienteId(),
				'total'		                    	=> $PedidoCabData->getTotal(),
				'comentario'	                   	=> $PedidoCabData->getComentario(),
				'estado'		                  	=> $PedidoCabData->getEstado(),
				'fec_ingreso'						=> \Application\Classes\Fecha::getFechaActualServidor(),				
				'usuario_cliente_id'               	=> $PedidoCabData->getUsuarioClienteId(),
				'usuario_ing_id'					=> $PedidoCabData->getUsuarioIngId(),
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param PedidoCabData $PedidoCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoCabData $PedidoCabData)
	{
/*		$key    = array(
				'id'						        => $PedidoCabData->getId(),
		);
		$record = array(
				'id'								=> $PedidoCabData->getId(),
				'fecha'		                    	=> $PedidoCabData->getFecha(),
				'cliente_id'                    	=> $PedidoCabData->getClienteId(),
				'total'		                    	=> $PedidoCabData->getTotal(),
				'comentario'	                   	=> $PedidoCabData->getComentario(),
				'estado'		                  	=> $PedidoCabData->getEstado(),
				'usuario_cliente_id'               	=> $PedidoCabData->getUsuarioClienteId(),
				'usuario_aprobacion'		        => $PedidoCabData->getUsuarioAprobacion(),
				'usuario_anulacion'		            => $PedidoCabData->getUsuarioAnulacion(),
				'fec_exportado'		                => $PedidoCabData->getFecExportado()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoCabData->getid();
*/	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return PedidoCabData|null
	 */	
	public function consultar($id)
	{
		$PedidoCabData 		    = new PedidoCabData();

		$sql = 	' SELECT pedido_cab.* '.
				' FROM pedido_cab '.
				' WHERE pedido_cab.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$PedidoCabData->setId							($row['id']);				
				$PedidoCabData->setFecha 						($row['fecha']);
				$PedidoCabData->setClienteId 					($row['cliente_id']);
				$PedidoCabData->setTotal 						($row['total']);
				$PedidoCabData->setComentario 					($row['comentario']);
				$PedidoCabData->setEstado 						($row['estado']);
				$PedidoCabData->setUsuarioClienteId 			($row['usuario_cliente_id']);
				$PedidoCabData->setUsuarioAprobacion 			($row['usuario_aprobacion']);
				$PedidoCabData->setUsuarioAnulacion 			($row['usuario_anulacion']);
				$PedidoCabData->setFecExportado 				($row['fec_exportado']);
				
			return $IdData;
		}else{
			return null;
		}//end if

	}//end function consultar


	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return number
	 */
	public function actualizarTotal($pedido_cab_id)
	{
		$sql = 	' SELECT sum(total) as tot '.
				' FROM pedido_det '.
				' WHERE pedido_cab_id = :pedido_cab_id ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro		

		if ($row)
		{
			if (empty($row['tot'])){
				$total = 0;
			}else{
				$total = $row['tot'];
			}//end if

			$sql = 	' UPDATE pedido_cab '.
					' SET total	= '.$total;
					' WHERE id 	= '. $pedido_cab_id;

			$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);

			return $count;
		}//end if
		
		return -1;
	}//end function actualizarTotal
	
	
	
	/**
	 * 
	 * @param string $cliente_id
	 * @return array
	 */
	public function consultarUltimoPedidoComprando($cliente_id)
	{
		$sql = 	' SELECT * '.
				' FROM pedido_cab '. 
				' WHERE cliente_id 	= :cliente_id '.
				"	AND estado  = '".\Application\Constants\Pedido::ESTADO_COMPRANDO."'".
				' ORDER BY id DESC '.
				' LIMIT 0, 1 ';
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':cliente_id', $cliente_id);
		$stmt->execute();
		
		$reg = $stmt->fetch();
		
		return $reg;
	}//end function consultarUltimoPedidoComprando
	
	
}//end class

?>