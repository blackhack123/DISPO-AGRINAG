<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoCabData;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dispo\Data\Dispo\Data;

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
				'fec_ingreso'						=> \Application\Classes\Fecha::getFechaHoraActualServidor(),				
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
	 * Actualiza el Comentario
	 *
	 * @param PedidoCabData $PedidoCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function actualizarComentario(PedidoCabData $PedidoCabData)
	{
		$key    = array(
				'id'						        => $PedidoCabData->getId(),
		);
		$record = array(
				'comentario'	                   	=> $PedidoCabData->getComentario(),
				'usuario_mod_id'               		=> $PedidoCabData->getUsuarioModId(),
				'fec_modifica'		                => \Application\Classes\Fecha::getFechaHoraActualServidor()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoCabData->getid();		
	}//end function actualizarComentario



	/**
	 * Actualiza el estado del pedido
	 * 
	 * @param int $pedido_cab_id
	 * @param string $estado
	 * @param int $usuario_id
	 */
	public function actualizarEstado($pedido_cab_id, $estado, $usuario_id)
	{
		$key    = array(
				'id'						        => $pedido_cab_id,
		);
		$record = array(
				'estado'	                   		=> $estado,
				'usuario_mod_id'               		=> $usuario_id,
				'fec_modifica'		                => \Application\Classes\Fecha::getFechaHoraActualServidor()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;	
	}//end function actualizarEstado
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return boolean
	 */
	public function eliminar($pedido_cab_id)
	{
		$key    = array(
				'id'						=> $pedido_cab_id,					
		);
	
		$this->getEntityManager()->getConnection()->delete($this->table_name, $key);
		return true;
	}//end function actualizarComentario		
		
	
	

	/**
	 * Consultar
	 * 
	 * @param unknown $id
	 * @param unknown $type_result
	 * @return \Dispo\Data\PedidoCabData|array|NULL
	 */
	public function consultar($id, $type_result = \Application\Constants\ResultType::OBJETO)
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
			
			switch ($type_result)
			{
				case \Application\Constants\ResultType::OBJETO:
					$PedidoCabData				= new PedidoCabData();
					$PedidoCabData->setId							($row['id']);
					$PedidoCabData->setFecha 						($row['fecha']);
					$PedidoCabData->setClienteId 					($row['cliente_id']);
					$PedidoCabData->setTotal 						($row['total']);
					$PedidoCabData->setComentario 					($row['comentario']);
					$PedidoCabData->setEstado 						($row['estado']);
					$PedidoCabData->setUsuarioClienteId 			($row['usuario_cliente_id']);
					$PedidoCabData->setUsuarioAprobadoId 			($row['usuario_aprobado_id']);
					$PedidoCabData->setUsuarioAnuladoId 			($row['usuario_anulado_id']);
					$PedidoCabData->setFecExportado 				($row['fec_exportado']);

					return $PedidoCabData;
					break;

				case \Application\Constants\ResultType::MATRIZ:
					return $row;
			}//end switch
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
	
	
	
	/**
	 * 
	 * @param array $condiciones
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT pedido_cab.*, cliente.nombre as cliente_nombre '.
				' FROM pedido_cab INNER JOIN cliente '.
				'                         ON cliente.id = pedido_cab.cliente_id '.
				' WHERE 1=1';
		
		if (!empty($condiciones['cliente_id']))
		{
			$sql = $sql."  and pedido_cab.cliente_id 	= '".$condiciones['cliente_id']."'";
		}
		$sql = $sql.' ORDER BY id DESC';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		
		$result = $stmt->fetchAll();
		
		return $result;		
	}//end function listado
	
	
}//end class

?>