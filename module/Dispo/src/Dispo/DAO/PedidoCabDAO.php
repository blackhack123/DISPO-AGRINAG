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
				'marcacion_sec'						=> $PedidoCabData->getMarcacionSec(),
				'agencia_carga_id'					=> $PedidoCabData->getAgenciaCargaId(),
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
		
		if ($estado = \Application\Constants\Pedido::ESTADO_ACTIVO)
		{
			$fecha_hora_confirmado				    = \Application\Classes\Fecha::getFechaHoraActualServidor();
			$record['fec_confirmado']				= $fecha_hora_confirmado;
			$record['usuario_confirmado_id']		= $usuario_id;
		}//end if

		
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return array($key, $record);	
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

		$sql = 	' SELECT pedido_cab.*, agencia_carga.nombre as agencia_carga_nombre, '.
				'		 cliente.nombre as cliente_nombre, '.
				'		 cliente.direccion as cliente_direccion, '.
				'		 cliente.telefono1 as cliente_telefono1, '.
				'		 cliente.fax1 as cliente_fax1, '.
				'        marcacion.nombre as marcacion_nombre, '.
				'        marcacion.direccion as marcacion_direccion, '.
				'        marcacion.telefono as marcacion_telefono '.
				' FROM pedido_cab INNER JOIN cliente '.
				'                    ON cliente.id = pedido_cab.cliente_id '.
				'				  LEFT JOIN agencia_carga '.
				'                    ON agencia_carga.id 		= pedido_cab.agencia_carga_id '.
				'				  LEFT JOIN marcacion '.
				'					 ON marcacion.marcacion_sec	= pedido_cab.marcacion_sec '.
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
					$PedidoCabData->setMarcacionSec					($row['marcacion_sec']);
					$PedidoCabData->setAgenciaCargaId 				($row['agencia_carga_id']);
					$PedidoCabData->setCuartoFrioId 				($row['cuarto_frio_id']);
					$PedidoCabData->setTotal 						($row['total']);
					$PedidoCabData->setComentario 					($row['comentario']);
					$PedidoCabData->setEstado 						($row['estado']);
					$PedidoCabData->setFecAprobado					($row['fec_aprobado']);
					$PedidoCabData->setFecAnulado					($row['fec_anulado']);
					$PedidoCabData->setFecIngreso					($row['fec_ingreso']);
					$PedidoCabData->setFecModifica					($row['fec_modifica']);
					$PedidoCabData->setUsuarioClienteId 			($row['usuario_cliente_id']);
					$PedidoCabData->setUsuarioAprobadoId 			($row['usuario_aprobado_id']);
					$PedidoCabData->setUsuarioAnuladoId 			($row['usuario_anulado_id']);
					$PedidoCabData->setUsuarioIngId					($row['usuario_ing_id']);
					$PedidoCabData->setUsuarioModId					($row['usuario_mod_id']);
					$PedidoCabData->setSincronizado					($row['sincronizado']);
					$PedidoCabData->setFecSincronizado				($row['fec_sincronizado']);

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
		$sql = 	' SELECT pedido_cab.*, agencia_carga.nombre as agencia_carga_nombre, '.
				'        marcacion.nombre as marcacion_nombre, marcacion.tipo_caja_default_id, '.
				'        pedido_cab.cliente_id '.
				//'        marcacion.punto_corte as marcacion_punto_corte '.
				' FROM pedido_cab LEFT JOIN agencia_carga '.
				'                    ON agencia_carga.id 		= pedido_cab.agencia_carga_id '.
				'				  LEFT JOIN marcacion '.
				'					 ON marcacion.marcacion_sec	= pedido_cab.marcacion_sec '.
				' WHERE pedido_cab.cliente_id 	= :cliente_id '.
				"	AND pedido_cab.estado  = '".\Application\Constants\Pedido::ESTADO_COMPRANDO."'".
				' ORDER BY pedido_cab.id DESC '.
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
		$sql = 	' SELECT pedido_cab.*, cliente.nombre as cliente_nombre, agencia_carga.nombre as agencia_carga_nombre, '.
				'        marcacion.nombre as marcacion_nombre '.
				' FROM pedido_cab INNER JOIN cliente '.
				'                         ON cliente.id = pedido_cab.cliente_id '.
				'				  INNER JOIN marcacion '.
				'						  ON marcacion.marcacion_sec = pedido_cab.marcacion_sec '.
				'				  INNER JOIN agencia_carga '.
				'						  ON agencia_carga.id = pedido_cab.agencia_carga_id '.
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
	
	
	/**
	 *
	 * @param PedidoCabData $PedidoCabData
	 * @return array
	 */
	public function	cambiarAgenciaCarga(PedidoCabData $PedidoCabData)
	{
		$key    = array(
				'id'			=> $PedidoCabData->getId()
		);
	
		$record = array(
				'agencia_carga_id'		=> $PedidoCabData->getAgenciaCargaId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
	
		return $key;
	}//end function cambiarAgenciaCarga
	
	
	
	/**
	 *
	 * @param array $condiciones
	 * @return array
	 */
	public function listadoVendedor($condiciones)
	{
		$sql = 	' SELECT 	pedido_cab.id as pedido_cab_id,'.
				'			pedido_cab.cliente_id,'.
				'			pedido_cab.fec_ingreso,'.
				'	        pedido_cab.fec_despacho,'. 
				'	        pedido_cab.fec_confirmado,'.
				'	        pedido_cab.fec_sincronizado,'.
				'			pedido_cab.marcacion_sec,'.
				'	        pedido_cab.agencia_carga_id,'.
				'	        pedido_cab.comentario,'.
				'	        pedido_cab.estado,'.
				'	        cliente.nombre as cliente_nombre,'.
				'	        marcacion.nombre as marcacion_nombre,'.
				'	        agencia_carga.nombre as agencia_carga_nombre,'.
				'	        pedido_det.pedido_det_sec,'.
				'	        pedido_det.variedad_id,'.
				'	        variedad.nombre as variedad_nombre,'.
				'	        variedad.color_ventas_id,'.
				'	        color_ventas.nombre as color_ventas_nombre,'.
				'	        pedido_det.grado_id,'.
				'	        pedido_det.tallos_x_bunch,'. 
				'	        pedido_det.tipo_caja_id,'.
				'	        pedido_det.nro_cajas,'.
				'	        pedido_det.tallos_total as pedido_det_tallos_total,'.
				'	        pedido_det.precio as pedido_det_precio,'.
				'	        pedido_det.total_x_caja,'.
				'	        pedido_det.total as pedido_det_total,'.
				'	        pedido_det.marca,'.
				'	        pedido_det.eq_fb as pedido_det_eq_fb,'.
				'	        pedido_proveedor.proveedor_id,'.
				'	        pedido_proveedor.nro_cajas as pedido_proveedor_nro_cajas,'.
				'	        pedido_proveedor.eq_fb as pedido_proveedor_eq_fb,'.
				'	        pedido_proveedor.cantidad_bunch as pedido_proveedor_cantidad_bunch,'.
				'	        pedido_proveedor.tallos_total as pedido_proveedor_tallos_total,'.
				'	        pedido_proveedor.precio as pedido_proveedor_precio,'.
				'	        pedido_proveedor.total as pedido_proveedor_total,'.
				'	        pedido_proveedor.sync_pedfijo_tipo_doc,'.
				'	        pedido_proveedor.sync_pedfijo_serie,'.
				'	        pedido_proveedor.sync_pedfijo_pedido,'.
				'	        pedido_proveedor.sync_pedfijo_sec,'.
				'	        pedido_proveedor.sync_pedfijo_secc'.
				'	FROM pedido_cab INNER JOIN cliente'.
				'							ON cliente.id				= pedido_cab.cliente_id'.
				'					INNER JOIN marcacion'.
				'						    ON marcacion.marcacion_sec 	= pedido_cab.marcacion_sec'.
				'					INNER JOIN agencia_carga'.
				'							ON agencia_carga.id			= pedido_cab.agencia_carga_id'.
				'					 LEFT JOIN pedido_det'.
				'							ON pedido_det.pedido_cab_id	= pedido_cab.id'.
				'					 LEFT JOIN variedad'.
				'							ON variedad.id				= pedido_det.variedad_id'.
				'					 LEFT JOIN color_ventas'.
				'							ON color_ventas.id			= variedad.color_ventas_id'.
				'				     LEFT JOIN pedido_proveedor'.
				'							ON pedido_proveedor.pedido_cab_id	= pedido_det.pedido_cab_id'.
				'	                       AND pedido_proveedor.pedido_det_sec  = pedido_det.pedido_det_sec'.	 
				' WHERE 1=1';
	
		if (!empty($condiciones['cliente_nombre']))
		{
			$sql = $sql."  and cliente.nombre 	= '%".$condiciones['cliente_nombre']."%'";
		}
		if (!empty($condiciones['fec_ingreso_ini']))
		{
			$sql = $sql."  and pedido_cab.fec_ingreso 	>= '".$condiciones['fec_ingreso_ini']."'";
		}
		if (!empty($condiciones['fecha_pedido_fin']))
		{
			$sql = $sql."  and pedido_cab.fec_ingreso 	<= '".$condiciones['fec_ingreso_fin']."'";
		}

		$sql = $sql.' ORDER BY pedido_cab.id, pedido_det.pedido_det_sec ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
	
		$result = $stmt->fetchAll();
	
		return $result;
	}//end function listado

	

	/**
	 * 
	 * @param int $pedido_cab_id
	 * @param string $fec_despacho
	 * @return int
	 */
	public function actualizarFechaDespacho($pedido_cab_id, $fec_despacho)
	{
		$key    = array(
				'id'						        => $pedido_cab_id,
		);
		$record = array(
				'fec_despacho'	                   	=> $fec_despacho
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $pedido_cab_id;
	}//end function actualizarFechaDespacho

	
}//end class

?>