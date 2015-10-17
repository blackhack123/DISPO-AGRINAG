<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PedidoDetData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PedidoDetDAO extends Conexion 
{
	private $table_name	= 'pedido_det';

	/**
	 * Ingresar
	 *
	 * @param PedidoDetData $PedidoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PedidoDetData $PedidoDetData)
	{
		$key    = array(
				'pedido_cab_id'						=> $PedidoDetData->getPedidoCabId(),
				'pedido_det_sec'					=> $PedidoDetData->getPedidoDetSec()				
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoDetData->getPedidoCabId(),
				'pedido_det_sec'	                			=> $PedidoDetData->getPedidoDetSec(),
				//'marcacion_sec'		            				=> $PedidoDetData->getMarcacionSec(),
				'inventario_id'  	             			 	=> $PedidoDetData->getInventarioId(),
				'variedad_id'  		      						=> $PedidoDetData->getVariedadId(),
				'grado_id'        								=> $PedidoDetData->getGradoId(),
				'tipo_caja_id'        							=> $PedidoDetData->getTipoCajaId(),
				'tipo_caja_origen_estado'        				=> $PedidoDetData->getTipoCajaOrigenEstado(),
				'tipo_caja_origen_id'        					=> $PedidoDetData->getTipoCajaOrigenId(),
				'nro_cajas'        								=> $PedidoDetData->getNroCajas(),
				'cantidad_bunch'   		     					=> $PedidoDetData->getCantidadBunch(),
				'tallos_x_bunch'	        					=> $PedidoDetData->getTallosxBunch(),
				'tallos_total'    		    					=> $PedidoDetData->getTallosTotal(),
				'precio'        								=> $PedidoDetData->getPrecio(),
				'total_x_caja'									=> $PedidoDetData->getTotalXCaja(), 
				'total'        									=> $PedidoDetData->getTotal(),
				//'agencia_carga_id'        						=> $PedidoDetData->getAgenciaCargaId(),
				//'comentario'        							=> $PedidoDetData->getComentario(),				
				//'sec'        									=> $PedidoDetData->getSec(),
				//'secc'        									=> $PedidoDetData->getSecc(),
				'pedido_cab_oferta_id'        					=> $PedidoDetData->getPedidoCabOfertaId(),
				'pedido_det_oferta_sec'        					=> $PedidoDetData->getPedidoDetOfertaSec(),
				/*'estado_precio_contraoferta'        			=> $PedidoDetData->getEstadoPrecioContraoferta(),
				'estado_pedido_adicional'        				=> $PedidoDetData->getEstadoPedidoAdicional(),
				'estado_mixto'        							=> $PedidoDetData->getEstadoMixto(),
				*/
				'estado_reg_oferta'        						=> $PedidoDetData->getEstadoRegOferta(),
				'calidad_id'									=> $PedidoDetData->getCalidadId(),
				'punto_corte'									=> $PedidoDetData->getPuntoCorte(),
				'eq_fb'											=> $PedidoDetData->getEqFb(),
				'usuario_ing_id'								=> $PedidoDetData->getUsuarioIngId(),
				'usuario_mod_id'								=> $PedidoDetData->getUsuarioModId(),
				'fec_ingreso'									=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'fec_modifica'									=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $key;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param PedidoDetData $PedidoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PedidoDetData $PedidoDetData)
	{
/*		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoDetData->getGrupoPrecioCab(),
				'pedido_det_sec'						      => $PedidoDetData->getVariedadId()
		);
		$record = array(
				'pedido_cab_id'									=> $PedidoDetData->getPedidoCabId(),
				'pedido_det_sec'	                			=> $PedidoDetData->getPedidoDetSec(),
				'marcacion_sec'		            				=> $PedidoDetData->getMarcacionSec(),
				'inventario_id'  	             			 	=> $PedidoDetData->getInventarioId(),
				'variedad_id'  		      						=> $PedidoDetData->getVariedadId(),
				'grado_id'        								=> $PedidoDetData->getGradoId(),
				'tipo_caja_id'        							=> $PedidoDetData->getTipoCajaId(),
				'tipo_caja_origen_estado'        				=> $PedidoDetData->getTipoCajaOrigenEstado(),
				'tipo_caja_origen_id'        					=> $PedidoDetData->getTipoCajaOrigenId(),
				'nro_cajas'        								=> $PedidoDetData->getNroCajas(),
				'cantidad_bunch'   		     					=> $PedidoDetData->getCantidadBunch(),
				'tallos_x_bunch'	        					=> $PedidoDetData->getTallosxBunch(),
				'total_x_caja'									=> $PedidoDetData->getTotalXCaja(),				
				'tallos_total'    		    					=> $PedidoDetData->getTallosTotal(),
				'precio'        								=> $PedidoDetData->getPrecio(),
				'total'        									=> $PedidoDetData->getTotal(),
				'agencia_carga_id'        						=> $PedidoDetData->getAgenciaCargaId(),
				'comentario'        							=> $PedidoDetData->getComentario(),
				'sec'        									=> $PedidoDetData->getSec(),
				'secc'        									=> $PedidoDetData->getSecc(),
				'pedido_cab_oferta_id'        					=> $PedidoDetData->getPedidoCabOfertaId(),
				'pedido_det_oferta_sec'        					=> $PedidoDetData->getPedidoDetOfertaSec(),
				'estado_precio_contraoferta'        			=> $PedidoDetData->getEstadoPrecioContraoferta(),
				'estado_pedido_adicional'        				=> $PedidoDetData->getEstadoPedidoAdicional(),
				'estado_mixto'        							=> $PedidoDetData->getEstadoMixto(),
				'estado_reg_oferta'        						=> $PedidoDetData->getEstadoRegOferta()
				'calidad_id'									=> $PedidoDetData->getCalidadId(),
				'punto_corte'									=> $PedidoDetData->getPuntoCorte(),				
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PedidoDetData->getPedidoCabId();
		return $PedidoDetData->getPedidoDetSec();
*/	}//end function modificar


	/**
	 * Actualiza el numero de cajas
	 * 
	 * @param PedidoDetData $PedidoDetData
	 * @return array $key
	 */
	public function actualizarNroCajasxx(PedidoDetData $PedidoDetData)
	{
		$cajas_fb = \Application\Classes\CajaConversion::equivalenciaFB($PedidoDetData->getTipoCajaId(), $PedidoDetData->getNroCajas());
				
		$key    = array(
				'pedido_cab_id'			      		  		  => $PedidoDetData->getPedidoCabId(),
				'pedido_det_sec'						      => $PedidoDetData->getPedidoDetSec()
		);
		$record = array(
				'eq_fb'											=> $cajas_fb,
				'nro_cajas'        								=> $PedidoDetData->getNroCajas(),
				'cantidad_bunch'   		     					=> $PedidoDetData->getCantidadBunch(),
				//'tallos_x_bunch'	        					=> $PedidoDetData->getTallosxBunch(),
				'tallos_total'    		    					=> $PedidoDetData->getTallosTotal(),
				//'precio'        								=> $PedidoDetData->getPrecio(),
				'total_x_caja'									=> $PedidoDetData->getTotalXCaja(),				
				'total'        									=> $PedidoDetData->getTotal(),
				'usuario_mod_id'								=> $PedidoDetData->getUsuarioModId(),
				'fec_modifica'									=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
	}//end function actualizarNroCajas
		
		
		
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @return boolean
	 */
	public function eliminar($pedido_cab_id, $pedido_det_sec)
	{
		$key    = array(
				'pedido_cab_id'				=> $pedido_cab_id,
				'pedido_det_sec'			=> $pedido_det_sec,
		);

		$this->getEntityManager()->getConnection()->delete($this->table_name, $key);
		return true;		
	}//end function eliminar


	public function eliminarHueso($pedido_cab_id, $pedido_det_sec)
	{
		$key    = array(
				'pedido_cab_oferta_id'			=> $pedido_cab_id,
				'pedido_det_oferta_sec'			=> $pedido_det_sec,
		);
	
		$this->getEntityManager()->getConnection()->delete($this->table_name, $key);
		return true;
	}//end function eliminar	
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @return boolean
	 */
	public function eliminarOferta($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoDetData = $this->consultar($pedido_cab_id, $pedido_det_sec);
		
		//PRIMER CASO.-  Eliminar el HUESO en caso de ser CARNE el registro consultado
		//SEGUNDO CASO.- Eliminar la CARNE en caso que el registro consultado sea HUESO		
		if ($PedidoDetData->getEstadoRegOferta()==1)
		{
			//Se elimina el hueso
			$resultado = $this->eliminarHueso($PedidoDetData->getPedidoCabId(), $PedidoDetData->getPedidoDetSec());			
		}else{
			//Se elimina la carne
			$resultado = $this->eliminar($PedidoDetData->getPedidoCabOfertaId(), $PedidoDetData->getPedidoDetOfertaSec());
		}//end function
		return $resultado; 
	}//end function eliminarOferta



	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @return \Dispo\Data\PedidoDetData|NULL
	 */
	public function consultar($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoDetData 		    = new PedidoDetData();

		$sql = 	' SELECT pedido_det.* '.
				' FROM pedido_det '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				'   and pedido_det_sec		= :pedido_det_sec';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_sec',$pedido_det_sec);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$PedidoDetData->setPedidoCabId					($row['pedido_cab_id']);
			$PedidoDetData->setPedidoDetSec					($row['pedido_det_sec']);
			//$PedidoDetData->setMarcacionSec	    			($row['marcacion_sec']);
			$PedidoDetData->setInventarioId					($row['inventario_id']);
			$PedidoDetData->setVariedadId					($row['variedad_id']);
			$PedidoDetData->setGradoId						($row['grado_id']);
			$PedidoDetData->setTipoCajaId					($row['tipo_caja_id']);
			$PedidoDetData->setTipoCajaOrigenEstado			($row['tipo_caja_origen_estado']);
			$PedidoDetData->setTipoCajaOrigenId				($row['tipo_caja_origen_id']);
			$PedidoDetData->setNroCajas						($row['nro_cajas']);
			$PedidoDetData->setCantidadBunch				($row['cantidad_bunch']);
			$PedidoDetData->setTallosxBunch					($row['tallos_x_bunch']);
			$PedidoDetData->setTallosTotal					($row['tallos_total']);
			$PedidoDetData->setPrecio						($row['precio']);
			$PedidoDetData->setTotal						($row['total']);
			//$PedidoDetData->setAgenciaCargaId				($row['agencia_carga_id']);
			//$PedidoDetData->setComentario					($row['comentario']);
			//$PedidoDetData->setSec							($row['sec']);
			//$PedidoDetData->setSecc							($row['secc']);
			$PedidoDetData->setPedidoCabOfertaId			($row['pedido_cab_oferta_id']);
			$PedidoDetData->setPedidoDetOfertaSec			($row['pedido_det_oferta_sec']);
			$PedidoDetData->setEstadoPrecioContraOferta		($row['estado_precio_contraoferta']);
			$PedidoDetData->setEstadoPedidoAdicional		($row['estado_pedido_adicional']);
			$PedidoDetData->setEstadoMixto					($row['estado_mixto']);
			$PedidoDetData->setEstadoRegOferta				($row['estado_reg_oferta']);
			$PedidoDetData->setCalidadId					($row['calidad_id']);
			$PedidoDetData->setPuntoCorte					($row['punto_corte']);
			
			return $PedidoDetData;
		}else{
			return null;
		}//end if

	}//end function consultar



	/**
	 *
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @return array
	 */
	public function consultarArray($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoDetData 		    = new PedidoDetData();
	
		$sql = 	' SELECT pedido_det.*, agencia_carga.nombre as agencia_carga_nombre '.
				' FROM pedido_det LEFT JOIN agencia_carga '.
				'                        ON agencia_carga.id = pedido_det.agencia_carga_id '.
				' WHERE pedido_cab_id = :pedido_cab_id '.
				'   and pedido_det_sec		= :pedido_det_sec';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->bindValue(':pedido_det_sec',$pedido_det_sec);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		return $row;
	}//end function consultarArray	
	
	
	

	/**
	 * Consulta todos los pedidos del cliente que este en estado comprando
	 *
	 * @param string $cliente_id
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param string $calidad_id
	 * @return array
	 */
	public function consultarPedidosEstadoComprando($cliente_id, $inventario_id, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $calidad_id)
	{
		$sql =  " SELECT ".
				'   variedad.producto_id,'.
				"	pedido_det.variedad_id,".
				"	pedido_det.grado_id,".
				'   pedido_det.tallos_x_bunch,'.
				"	sum(pedido_det.cantidad_bunch) as pedido_tot_bunch".
				" FROM pedido_cab INNER JOIN pedido_det".
				"						ON pedido_det.pedido_cab_id = pedido_cab.id".
				"					   AND pedido_det.inventario_id	= '".$inventario_id."'".
			    "                      AND pedido_det.calidad_id	= ".$calidad_id;
		if (!empty($variedad_id)){
			$sql = $sql."			   AND pedido_det.variedad_id 	= '".$variedad_id."'";
		}
		if (!empty($grado_id)){
			$sql = $sql."			   AND pedido_det.grado_id 		= '".$grado_id."'";
		}
		if (!empty($tallos_x_bunch)){
			$sql = $sql."			   AND pedido_det.tallos_x_bunch= ".$tallos_x_bunch;
		}
		$sql = $sql.'             INNER JOIN variedad'.
				'                       ON variedad.id  			= pedido_det.variedad_id ';
		if (!empty($producto_id)){
			$sql = $sql."			   AND variedad.producto_id 	= '".$producto_id."'";
		}
		$sql = $sql."  WHERE pedido_cab.cliente_id 	= '".$cliente_id."'".
				"  AND pedido_cab.estado 		= 'C'".
				" GROUP BY variedad.producto_id, pedido_det.variedad_id, pedido_det.grado_id, pedido_det.tallos_x_bunch";
	
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
	
		return $result;
	}//end class consultarPedidosEstadoComprando	
	
	
	
	/**
	 * Se obtiene el numero de cajas homologadas por los diferentes formatos
	 * 
	 * @param string $inventario_id
	 * @param string $cliente_id
	 * @param int $marcacion_sec
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param string $estado_pedido
	 * @param string $tipo_caja_id
	 * @param int $tallos_x_bunch
	 * @return number
	 */
	public function getCajasHomologadaPedido($inventario_id, $cliente_id, $marcacion_sec, $variedad_id, $grado_id, $estado_pedido, $tipo_caja_id, $tallos_x_bunch, $calidad_id)
	{
		/**
			* Se resta del stock los pedidos del cliente que se encuentra comprando
			*/
		$sql = 	" SELECT ".
				"	(CASE ".
				"		WHEN tipo_caja_marcacion.id IS NOT NULL THEN 'MAR' ".
				"		ELSE 'MAT' ".
				"	END) AS tipo_caja_origen_estado,".
				"	(CASE".
				"		WHEN tipo_caja_marcacion.id IS NOT NULL THEN tipo_caja_marcacion.id".
				"		ELSE tipo_caja_matriz.id".
				"	END) AS tipo_caja_origen_id,".
				"	(CASE".
				"		WHEN tipo_caja_marcacion.id IS NOT NULL THEN tipo_caja_marcacion.unds_bunch".
				"		ELSE tipo_caja_matriz.unds_bunch".
				"	END) AS tipo_caja_unds_bunch,".
				"	(CASE".
				"		WHEN tipo_caja_marcacion.id IS NOT NULL THEN tipo_caja_marcacion.tipo_caja_id".
				"		ELSE tipo_caja_matriz.tipo_caja_id".
				"	END) AS tipo_caja_id,".
				"	sum(pedido_det.cantidad_bunch) as pedido_tot_bunch".
				" FROM pedido_cab 	INNER JOIN pedido_det ".
				"						ON pedido_det.pedido_cab_id = pedido_cab.id".
				"					   AND pedido_det.inventario_id	= '".$inventario_id."'".
				"					   AND pedido_det.variedad_id	= '".$variedad_id."'".
				"					   AND pedido_det.grado_id		= '".$grado_id."'".
				"                      AND pedido_det.tallos_x_bunch= ".$tallos_x_bunch.
				"					   AND pedido_det.calidad_id	= ".$calidad_id.
				"					INNER JOIN tipo_caja_matriz".
				"					    ON tipo_caja_matriz.inventario_id 	= pedido_det.inventario_id".
				"		   			   AND tipo_caja_matriz.variedad_id 	= pedido_det.variedad_id".
				"		   			   AND tipo_caja_matriz.grado_id 		= pedido_det.grado_id".
				"		   			   AND tipo_caja_matriz.tipo_caja_id	= '".$tipo_caja_id."'".
				"					LEFT JOIN tipo_caja_marcacion ".
				"                       ON tipo_caja_marcacion.marcacion_sec = ".$marcacion_sec.
				"					   AND tipo_caja_marcacion.tipo_caja_id 	= tipo_caja_matriz.tipo_caja_id".
				"					   AND tipo_caja_marcacion.inventario_id 	= tipo_caja_matriz.inventario_id".
				"					   AND tipo_caja_marcacion.variedad_id 		= tipo_caja_matriz.variedad_id".
				"					   AND tipo_caja_marcacion.grado_id 		= tipo_caja_matriz.grado_id".
				"					   AND tipo_caja_marcacion.tipo_caja_id 	= tipo_caja_matriz.tipo_caja_id".
				"					LEFT JOIN tipo_caja AS tipo_caja_maestro_mat ".
				"					    ON tipo_caja_maestro_mat.id = tipo_caja_matriz.tipo_caja_id".
				"					LEFT JOIN tipo_caja AS tipo_caja_maestro_mar ".
				"						ON tipo_caja_maestro_mar.id = tipo_caja_matriz.tipo_caja_id".
				" WHERE pedido_cab.cliente_id 	= '".$cliente_id."'".
				"   AND pedido_cab.estado 		= '".$estado_pedido."'".
				" GROUP BY tipo_caja_origen_estado, tipo_caja_origen_id, tipo_caja_unds_bunch, tipo_caja_id";
	
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$row = $stmt->fetch();
				
		//Ajusta el porcentaje de acuerdo a la restriccion de GRUPO_DISPO
		$nro_cajas = 0;
		if($row) {
			$nro_cajas					= ceil ($row['pedido_tot_bunch']/$row['tipo_caja_unds_bunch']);
		}//end while

		return $nro_cajas;
	}//end function getCajasHomologadaPedido
	
	
	public function consultarMaximaSecuencia($pedido_cab_id)
	{
		$sql = 	' SELECT max(pedido_det_sec) as max_sec '.
				' FROM pedido_det '.
				' WHERE pedido_cab_id = :pedido_cab_id ';
				
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id',$pedido_cab_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro

		if (empty($row))
		{
			$secuencia = 0;
		}else{
			$secuencia = $row['max_sec'];
		}//end if
		
		return $secuencia;
	}//end function consultarMaximaSecuencia

	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return number
	 */
	public function consultarNroItemsPorPedido($pedido_cab_id, $estado = null)
	{
		$sql = 	' SELECT count(*) as nro_reg '.
				' FROM pedido_det INNER JOIN pedido_cab '.
				'                         ON pedido_cab.id 		= pedido_det.pedido_cab_id ';
		if (!empty($estado)){
			$sql = $sql."				 AND pedido_cab.estado  = '".$estado."'";
		}
		$sql = $sql.' WHERE pedido_det.pedido_cab_id= :pedido_cab_id ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id', $pedido_cab_id);
		$stmt->execute();
		$reg = $stmt->fetch();
		
		$nro_reg = 0;
		if ($reg)
		{
			if (!empty($reg['nro_reg']))
			{
				$nro_reg = $reg['nro_reg'];
			}//end if
		}//end if
		return $nro_reg;
	}//end function consultarNroItemsPorPedido
			
	
	/**
	 * 
	 * @param intn $pedido_cab_id
	 * @param string $estado (OPCIONAL) Esta campo se lo usara para fortalece el query de pedidos por tema de seguridad 
	 * @return array
	 */
	public function consultarPorPedidoCabId($pedido_cab_id, $estado = null)
	{
/*		$sql = 	' SELECT pedido_det.*, variedad.nombre as variedad_nombre, agencia_carga.nombre as agencia_carga_nombre, '.
				'        marcacion.nombre as marcacion_nombre, pedido_cab.cliente_id, '.
*/		$sql = 	' SELECT pedido_det.*, variedad.producto_id as variedad_producto_id, variedad.nombre as variedad_nombre, '.
				'        pedido_cab.cliente_id, pedido_cab.marcacion_sec, '.
				' 		 pedido_cab.agencia_carga_id, color_ventas.nombre as color_ventas_nombre, '.	
				'        tipo_caja.nombre as tipo_caja_nombre, '.			
				'		 CASE pedido_det.estado_reg_oferta '.
				'			WHEN 1 THEN variedad_hueso.nombre '.
				'			WHEN 0 THEN variedad_carne.nombre '.
				'        END as variedad_nombre_oferta_vinculada, '.
				'		 CASE pedido_det.estado_reg_oferta '.
				'			WHEN 1 THEN variedad_hueso.id '.
				'			WHEN 0 THEN variedad_carne.id '.
				'        END as variedad_id_oferta_vinculada '.				
				/*'        pedido_vinculado_carne.variedad_id as variedad_id_oferta_vinculada, variedad_carne.nombre as variedad_nombre_oferta_vinculada '.*/
				' FROM pedido_det INNER JOIN pedido_cab '.
				'				     ON pedido_cab.id			= pedido_det.pedido_cab_id ';
		if (!empty($estado)){
			$sql .= "               AND pedido_cab.estado		= '".$estado."'";
		}//end if
		$sql .=	'				  INNER JOIN variedad '.
				'                    ON variedad.id				= pedido_det.variedad_id '.
				'				  LEFT JOIN color_ventas '.
				' 					 ON color_ventas.id			= variedad.color_ventas_id '.
				'				  INNER JOIN tipo_caja '.
				'                    ON tipo_caja.id            = pedido_det.tipo_caja_id '.				
				'                 LEFT JOIN pedido_det as pedido_det_vinculado_carne '.
				'					 ON pedido_det_vinculado_carne.pedido_cab_id	= pedido_det.pedido_cab_oferta_id '.
				'				    AND pedido_det_vinculado_carne.pedido_det_sec	= pedido_det.pedido_det_oferta_sec '.
				'                 LEFT JOIN variedad as variedad_carne '.
				'					 ON variedad_carne.id				   			= pedido_det_vinculado_carne.variedad_id'.
				'                 LEFT JOIN pedido_det as pedido_det_vinculado_hueso '.
				'					 ON pedido_det_vinculado_hueso.pedido_cab_oferta_id	 = pedido_det.pedido_cab_id '.
				'				    AND pedido_det_vinculado_hueso.pedido_det_oferta_sec = pedido_det.pedido_det_sec '.
				'                 LEFT JOIN variedad as variedad_hueso '.
				'					 ON variedad_hueso.id				   				 = pedido_det_vinculado_hueso.variedad_id'.
				' WHERE pedido_det.pedido_cab_id	= :pedido_cab_id'.
				' ORDER BY pedido_cab_id, pedido_det_sec';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id', $pedido_cab_id);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		return $result;
	}//end function consultarPorPedidoCabId
	

	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return array
	 */
	public function consultarPorPedidoCabIdUltimoRegistro($pedido_cab_id)
	{
/*		$sql = 	' SELECT pedido_det.*, variedad.nombre as variedad_nombre, agencia_carga.nombre as agencia_carga_nombre, '.
				'        marcacion.nombre as marcacion_nombre, pedido_cab.cliente_id '.
				' FROM pedido_det INNER JOIN pedido_cab '.
				'				     ON pedido_cab.id			= pedido_det.pedido_cab_id '.
				'				  INNER JOIN variedad '.
				'                    ON variedad.id				= pedido_det.variedad_id '.
				'				  LEFT JOIN agencia_carga '.
				'                    ON agencia_carga.id 		= pedido_cab.agencia_carga_id '.
				'				  LEFT JOIN marcacion '.
				'					 ON marcacion.marcacion_sec	= pedido_cab.marcacion_sec '.
				' WHERE pedido_det.pedido_cab_id	= :pedido_cab_id'.
				' ORDER BY pedido_det.pedido_det_sec DESC'.
				' LIMIT 0, 1';		
*/
		$sql = 	' SELECT pedido_det.*, variedad.nombre as variedad_nombre'.
				' FROM pedido_det LEFT JOIN marcacion '.
				'					 ON marcacion.marcacion_sec	= pedido_cab.marcacion_sec '.
				' WHERE pedido_det.pedido_cab_id	= :pedido_cab_id'.
				' ORDER BY pedido_det.pedido_det_sec DESC'.
				' LIMIT 0, 1';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':pedido_cab_id', $pedido_cab_id);
		$stmt->execute();
		$reg = $stmt->fetch();
		
		return $reg;
	}//end function consultarPorPedidoCabIdUltimoRegistro
	

	
	/**
	 * 
	 * @param array $condiciones  array(pedido_cab_id, cliente_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT pedido_det.*, variedad.nombre as variedad_nombre, agencia_carga.nombre as agencia_carga_nombre, '.
				'		 tipo_caja.nombre as tipo_caja_nombre, '.
				'        marcacion.nombre as marcacion_nombre, pedido_cab.cliente_id, color_ventas.nombre as  color_ventas_nombre '.
				' FROM pedido_cab INNER JOIN pedido_det '.
				'				     ON pedido_det.pedido_cab_id= pedido_cab.id '.
				'				  INNER JOIN variedad '.
				'                    ON variedad.id				= pedido_det.variedad_id '.
				'				  INNER JOIN tipo_caja'.
				'					 ON tipo_caja.id			= pedido_det.tipo_caja_id '.
				'				  LEFT JOIN color_ventas '.
				'					 ON color_ventas.id 		= variedad.color_ventas_id '.
				'				  LEFT JOIN agencia_carga '.
				'                    ON agencia_carga.id 		= pedido_cab.agencia_carga_id '.
				'				  LEFT JOIN marcacion '.
				'					 ON marcacion.marcacion_sec	= pedido_cab.marcacion_sec '.
				' WHERE 1=1';

		if (!empty($condiciones['pedido_cab_id']))
		{
			$sql = $sql."  and pedido_cab.id 			= '".$condiciones['pedido_cab_id']."'";
		}
		if (!empty($condiciones['cliente_id']))
		{
			$sql = $sql."  and pedido_cab.cliente_id 	= '".$condiciones['cliente_id']."'";
		}
		$sql = $sql.' ORDER BY pedido_det.pedido_cab_id, pedido_det.pedido_det_sec';
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		
		$result = $stmt->fetchAll();
		
		return $result;		
	}
}//end class
?>
