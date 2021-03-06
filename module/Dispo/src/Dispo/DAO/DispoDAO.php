<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\DispoData;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dispo\Data\Dispo\Data;
use Dispo\Data\GrupoDispoDetData;

class DispoDAO extends Conexion 
{
	private $table_name	= 'dispo';

	/**
	 * 
	 * @param DispoData $DispoData
	 * @return array
	 */
	public function registrarBunchDisponibles(DispoData $DispoData)
	{
		$DispoData2 = $this->consultarPorKey($DispoData);
		
		if (empty($DispoData2))
		{
			$accion = 'I';
			$key = $this->ingresar($DispoData);
		}else{
			$accion = 'M';
			$key = $this->modificarStockBunchDisponibles($DispoData);
		}//end if
		
		return array($accion, $key);
	}//end function registrarBunchDisponibles
	
	
	
	
	/**
	 * Ingresar
	 *
	 * @param DispoData $DispoData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(DispoData $DispoData)
	{
		$key    = array(
				'id'						        => $DispoData->getId(),
		);
		$record = array(
				'id'								=> $DispoData->getId(),
				'fecha'		                  		=> $DispoData->getFecha(),
				'inventario_id'		            	=> $DispoData->getInventarioId(),
				'fecha_bunch'		            	=> $DispoData->getFechaBunch(),
				'proveedor_id'		            	=> $DispoData->getProveedorId(),
				'producto'		            		=> $DispoData->getProducto(),
				'variedad_id'		            	=> $DispoData->getVariedadId(),
				'grado_id'		            		=> $DispoData->getGradoId(),
				'tallos_x_bunch'	         		=> $DispoData->getTallosxBunch(),
				'clasifica'		            		=> $DispoData->getClasifica(),
				'cantidad_bunch'		            => $DispoData->getCantidad_bunch(),
				'cantidad_bunch_disponible'		    => $DispoData->getCantidadBunchDisponible()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param DispoData $DispoData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(DispoData $DispoData)
	{
		$key    = array(
				'id'						        => $DispoData->getId(),
		);
		$record = array(
				'id'								=> $DispoData->getId(),
				'fecha'		                  		=> $DispoData->getFecha(),
				'inventario_id'		            	=> $DispoData->getInventarioId(),
				'fecha_bunch'		            	=> $DispoData->getFechaBunch(),
				'proveedor_id'		            	=> $DispoData->getProveedorId(),
				'producto'		            		=> $DispoData->getProducto(),
				'variedad_id'		            	=> $DispoData->getVariedadId(),
				'grado_id'		            		=> $DispoData->getGradoId(),
				'tallos_x_bunch'	         		=> $DispoData->getgetTallosxBunch(),
				'clasifica'		            		=> $DispoData->getClasifica(),
				'cantidad_bunch'		            => $DispoData->getgetCantidadBunch(),
				'cantidad_bunch_disponible'		    => $DispoData->getCantidadBunchDisponible()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $DispoData->getId();
	}//end function modificar


	/**
	 * 
	 * @param int $id
	 * @param int $bunch_disponible
	 * @return int
	 */
	public function modificarBunchDisponibles($id, $bunch_disponible)
	{
		$key    = array(
				'id'						        => $id,
		);
		$record = array(
				'cantidad_bunch_disponible'		    => $bunch_disponible
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $id;
	}///end function modificarBunchDisponibles 
	
	
	/**
	 *
	 * @param DispoData $DispoData
	 * @return array
	 */
	public function modificarStockBunchDisponibles($DispoData)
	{
		$key    = array(
				'fecha'		                  		=> $DispoData->getFecha(),
				'inventario_id'		            	=> $DispoData->getInventarioId(),
				'fecha_bunch'		            	=> $DispoData->getFechaBunch(),
				'proveedor_id'		            	=> $DispoData->getProveedorId(),
				'producto'		            		=> $DispoData->getProducto(),
				'variedad_id'		            	=> $DispoData->getVariedadId(),
				'grado_id'		            		=> $DispoData->getGradoId(),
				'tallos_x_bunch'	         		=> $DispoData->getTallosxBunch(),
				'clasifica'		            		=> $DispoData->getClasifica(),				
		);
		$record = array(
				'cantidad_bunch'		    		=> $DispoData->getCantidad_bunch(),
				'cantidad_bunch_disponible'		    => $DispoData->getCantidadBunchDisponible()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
	}///end function modificarStockBunchDisponibles	
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return DispoData|null
	 */	
	public function consultar($id)
	{
		$DispoData 		    = new DispoData();

		$sql = 	' SELECT dispo.* '.
				' FROM dispo '.
				' WHERE dispo.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$DispoData->setId						($row['id']);				
				$DispoData->setFecha 					($row['fecha']);
				$DispoData->setInventarioId				($row['inventario_id']);
				$DispoData->setFechaBunch				($row['fecha_bunch']);
				$DispoData->setProveedorId				($row['proveedor_id']);
				$DispoData->setProducto					($row['producto']);
				$DispoData->setVariedadId				($row['variedad_id']);
				$DispoData->setGradoId					($row['grado_id']);
	      	    $DispoData->setTallosxBunch				($row['tallos_x_bunch']);
		        $DispoData->setClasifica				($row['clasifica']);
		        $DispoData->setCantidadBunch			($row['cantidad_bunch']);
				$DispoData->setCantidadBunchDisponible	($row['cantidad_bunch_disponible']);
				
			return $DispoData;
		}else{
			return null;
		}//end if

	}//end function consultar

	
	
	/**
	 * 
	 * @param array $condiciones  ($cliente_id, $usuario_id, $marcacion_sec)
	 * @return array
	 */
/*	public function listado($condiciones)
	{
		$sql = 	' SELECT *'.
				' FROM dispo '.
				' WHERE ';
		
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;
	}//end function listado
*/
	
	
	/**
	 * consultarInventarioPorUsuario
	 * 
	 * Se obtiene el inventario para el cliente por proveedor y tipo de inventario especifico,
	 * por lo que dara los bunches disponibles, y posteriormente si existe una 
	 * RESTRICCION en GRUPO_DISPO se aplica la regla para la dispo por grupo
	 * 
	 * @param string $cliente_id
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @param string $clasifica_fox
	 * @return array
	 */
	/*public function consultarInventarioPorCliente($cliente_id, $inventario_id, $grupo_dispo_cab_id, $variedad_id, $grado_id, $clasifica_fox)*/
	public function consultarInventarioPorUsuario($usuario_id, $producto_id, $variedad_id, $grado_id, $clasifica_fox)
	{
		$sql = 	' SELECT dispo.producto as producto_id, '.
				'       variedad.nombre as variedad_nombre,'.
				'		dispo.variedad_id, '.
				'		dispo.grado_id as grado_id,'.
				'       dispo.tallos_x_bunch,'.
				'		dispo.proveedor_id, '.				
				'		grupo_dispo_det.cantidad_bunch_disponible as grupo_dispo_det_cantidad_bunch_disponible, '.
				'       grupo_precio_det.precio, grupo_precio_det.precio_oferta, '.
				'		color_ventas.nombre as color_nombre, variedad.url_ficha,'.
				'	sum(dispo.cantidad_bunch_disponible) as tot_bunch_disponible, '.
				'	sum(dispo.tallos_x_bunch) as tot_tallos_x_bunch,'.
				'	count(*) as veces_tallos_x_bunch'.
				' FROM  usuario INNER JOIN cliente '.
				'                       ON cliente.id = usuario.cliente_id'.
				'		        INNER JOIN grupo_precio_det'.
				'					 ON grupo_precio_det.grupo_precio_cab_id = usuario.grupo_precio_cab_id';  /*GRUPO PRECIO*/
		if (!empty($producto_id)){
			$sql = $sql."           AND grupo_precio_det.producto_id   = '".$producto_id."'";
		}//end if
		if (!empty($variedad_id)){
			$sql = $sql."		    AND grupo_precio_det.variedad_id	= '".$variedad_id."'";
		}//end if
		if (!empty($grado_id)){
			$sql = $sql."		    AND grupo_precio_det.grado_id		= '".$grado_id."'";
		}//end if
		$sql = $sql.'		 INNER JOIN dispo '.
				"					 ON dispo.inventario_id	= usuario.inventario_id ".  /*INVENTARIO*/
				'                   AND dispo.producto      = grupo_precio_det.producto_id'.
				'					AND dispo.variedad_id	= grupo_precio_det.variedad_id'.
				'					AND dispo.grado_id		= grupo_precio_det.grado_id'.
				"					AND dispo.clasifica		= '".$clasifica_fox."'".
				'					AND dispo.cantidad_bunch_disponible > 0'.
				'		 	 INNER JOIN variedad'.
				'					 ON variedad.id			= dispo.variedad_id'.
				'            LEFT JOIN color_ventas '.
				'                    ON color_ventas.id		= variedad.color_ventas_id'.
				'            LEFT JOIN grupo_dispo_det '.
				'                    ON grupo_dispo_det.grupo_dispo_cab_id 		= usuario.grupo_dispo_cab_id'. /*GRUPO DISPO*/
				'                   AND grupo_dispo_det.producto_id				= dispo.producto'.
				'                   AND grupo_dispo_det.variedad_id				= dispo.variedad_id'.
				'					AND grupo_dispo_det.grado_id    			= dispo.grado_id '.
				'                   AND grupo_dispo_det.tallos_x_bunch			= dispo.tallos_x_bunch'.
				" WHERE usuario.id = ".$usuario_id.
				//" AND dispo.clasifica = '1'".//PARA TOMAR CALIDAD DE FLOR (RECIEN ADICIONADO)
				' GROUP BY dispo.producto, variedad.nombre, dispo.variedad_id, dispo.grado_id, dispo.tallos_x_bunch, dispo.proveedor_id, '.
				'          grupo_dispo_det.cantidad_bunch_disponible, grupo_precio_det.precio, grupo_precio_det.precio_oferta, '.
				'          color_ventas.nombre, variedad.url_ficha '.
				' ORDER BY dispo.producto, variedad.nombre, dispo.variedad_id, dispo.grado_id, dispo.tallos_x_bunch, tot_bunch_disponible DESC';

		//die($sql);
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;		
	}//end function consultarInventarioPorUsuario
	
	
	
	/**
	 * 
	 * @param string $proveedor_id
	 * @param string $inventario_id
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @param string $clasifica_fox
	 * @return array
	 */
	public function consultarInventarioPorProveedor($proveedor_id, $inventario_id, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $clasifica_fox)
	{
		$sql = " SELECT cantidad_bunch_disponible, fecha, inventario_id, fecha_bunch,  ".
				"        proveedor_id, producto, variedad_id, grado_id, tallos_x_bunch ".
				" FROM dispo ".
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and proveedor_id  	= '".$proveedor_id."'".
				"   and producto		= '".$producto_id."'".		//NUEVO
				"   and variedad_id		= '".$variedad_id."'".
				"   and grado_id		= '".$grado_id."'".
				"   and tallos_x_bunch	= ".$tallos_x_bunch. 		//NUEVO
				"   and clasifica		= '".$clasifica_fox."'".	//NUEVO
				" ORDER BY fecha_bunch ";
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;		
	}//end function consultaInventarioPorProveedor
	
	
	
	/**
	 * 
	 * @param DispoData $DispoData
	 * @param number $cantidad_descontar
	 */
	public function rebajar(DispoData $DispoData, $cantidad_descontar)
	{
		if ($cantidad_descontar == 0)
		{
			return 0;	
		}//end if
		
		$sql = 	" UPDATE dispo ".
				" SET cantidad_bunch_disponible = cantidad_bunch_disponible - ".$cantidad_descontar.
				" WHERE fecha			= '".$DispoData->getFecha()."'".
				"	and inventario_id 	= '".$DispoData->getInventarioId()."'".
				"	and fecha_bunch 	= '".$DispoData->getFechaBunch()."'".
				"   and proveedor_id  	= '".$DispoData->getProveedorId()."'".
				"   and producto		= '".$DispoData->getProducto()."'".
				"   and variedad_id		= '".$DispoData->getVariedadId()."'".
				"   and grado_id		= '".$DispoData->getGradoId()."'".
			    "   and tallos_x_bunch  = ".$DispoData->getTallosxbunch().
				"   and clasifica		= '".$DispoData->getClasifica()."'";
		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;		
	}//end function rebajar
	
	
	

	/**
	 *
	 * @param array $condiciones (inventario_id, proveedor_id, clasifica, color_ventas_id, calidad_variedad_id, nro_tallos, $group_by_proveedor_id)
	 * @return array:
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT ';
		if (array_key_exists('group_by_proveedor_id',$condiciones))
		{
			$sql = $sql.' dispo.proveedor_id, ';
		}//end if		
		
		$sql = $sql.'    dispo.producto as producto_id, variedad.nombre as variedad, dispo.variedad_id, dispo.tallos_x_bunch, '.
				'        color_ventas.nombre as color_ventas_nombre, variedad.url_ficha, '.
				" 		 SUM(if(dispo.grado_id=40,  dispo.cantidad_bunch_disponible, 0)) as '40',".
				" 		 SUM(if(dispo.grado_id=50,  dispo.cantidad_bunch_disponible, 0)) as '50',".
				" 		 SUM(if(dispo.grado_id=60,  dispo.cantidad_bunch_disponible, 0)) as '60',".
				" 		 SUM(if(dispo.grado_id=70,  dispo.cantidad_bunch_disponible, 0)) as '70',".
				" 		 SUM(if(dispo.grado_id=80,  dispo.cantidad_bunch_disponible, 0)) as '80',".
				" 		 SUM(if(dispo.grado_id=90, dispo.cantidad_bunch_disponible, 0)) as '90',".
				" 		 SUM(if(dispo.grado_id=100, dispo.cantidad_bunch_disponible, 0)) as '100',".
				" 		 SUM(if(dispo.grado_id=110, dispo.cantidad_bunch_disponible, 0)) as '110'".				
				' FROM dispo LEFT JOIN variedad '.
				'		                ON variedad.id      = dispo.variedad_id '.
				'            LEFT JOIN color_ventas '.
				'                       ON color_ventas.id	= variedad.color_ventas_id '.
				' WHERE 1 = 1 ';

		if (!empty($condiciones['inventario_id']))
		{
			$sql = $sql." and dispo.inventario_id = '".$condiciones['inventario_id']."'";
		}//end if
		
		if (!empty($condiciones['proveedor_id']))
		{
			$sql = $sql." and dispo.proveedor_id = '".$condiciones['proveedor_id']."'";
		}//end if
		
		if (!empty($condiciones['clasifica']))
		{
			$sql = $sql." and dispo.clasifica = '".$condiciones['clasifica']."'";
		}//end if
		
		if (!empty($condiciones['color_ventas_id']))
		{
			$sql = $sql." and variedad.color_ventas_id = ".$condiciones['color_ventas_id'];
		}//end if
		
		if (!empty($condiciones['calidad_variedad_id']))
		{
			$sql = $sql." and variedad.calidad_variedad_id = ".$condiciones['calidad_variedad_id'];
		}//end if
		
		if (!empty($condiciones['cadena_color_ventas_ids']))
		{
			$sql = $sql." and variedad.color_ventas_id in (".$condiciones['cadena_color_ventas_ids'].")";
		}//end if
		
		if (!empty($condiciones['cadena_calidad_variedad_ids']))
		{
			$sql = $sql." and variedad.calidad_variedad_id in (".$condiciones['cadena_calidad_variedad_ids'].")";
		}//end if
		
		if (!empty($condiciones['nro_tallos']))
		{
			switch($condiciones['nro_tallos'])
			{
				case 'NO25':
					$sql = $sql." and tallos_x_bunch <> 25";
					break;
					
				default:
					$sql = $sql." and tallos_x_bunch = ".$condiciones['nro_tallos'];					
					break;
			}//end switch
			
		}//end if		
		
		if (array_key_exists('fecha_bunch', $condiciones))
		{
			if (!empty($condiciones['fecha_bunch']))
			{
				$sql = $sql." and dispo.fecha_bunch = '".$condiciones['fecha_bunch']."'";
			}//end if
		}//end if
			
		if (array_key_exists('group_by_proveedor_id',$condiciones))
		{
			$sql = $sql.' GROUP BY dispo.proveedor_id, dispo.producto, variedad.nombre, dispo.variedad_id, tallos_x_bunch, color_ventas.nombre, url_ficha  ';
		}else{
			$sql=$sql.' GROUP BY dispo.producto, variedad.nombre, dispo.variedad_id, tallos_x_bunch, color_ventas.nombre, url_ficha ';
		}//end if		
		
		
		$sql=$sql." ORDER BY variedad.nombre ";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		return $result;		
	}//end function listado
	
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $clasifica_fox
	 * @param string $proveedor_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch 
	 * @return array
	 */
	public function consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch)
	{
		$sql = 	' SELECT proveedor_id, sum(cantidad_bunch_disponible) as tot_bunch_disponible '.
				' FROM dispo '.
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and clasifica		= '".$clasifica_fox."'".
				"   and variedad_id		= '".$variedad_id."'".
				"   and grado_id		= '".$grado_id."'".
				"   and tallos_x_bunch  = ".$tallos_x_bunch;

		if (!empty($proveedor_id))
		{
			$sql = $sql."  and proveedor_id = '".$proveedor_id."'";	
		}//end if
		
		$sql = $sql." GROUP BY proveedor_id";

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro

/*		$row_consolidado = null;
		foreach($result as $reg)
		{
			$row_consolidado[$reg['proveedor_id']]['cantidad_bunch_disponible'] = $reg['cantidad_bunch_disponible'];
		}//end foreach
*/
		return $result;		
	}//end function consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo
	
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $producto
	 * @param string $proveedor_id
	 * @return unknown
	 */
	public function consultarFechaMaximaDispo($inventario_id, $producto, $proveedor_id)
	{
		$sql = 	' SELECT max(fecha) as fecha, max(fecha_bunch) as fecha_bunch  '.
				' FROM dispo '.
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and producto		= '".$producto."'";
		
		if (!empty($proveedor_id))
		{
			$sql = $sql."   and proveedor_id	= '".$proveedor_id."'";
		}
			
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro

		return $row;
	}//end function consultarFechaMaximaDispo
	
	

	/**
	 * 
	 * @param string $inventario_id
	 * @param string $producto
	 * @param string $clasifica_fox
	 * @param string $proveedor_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @return array
	 */
	public function consultarRegistrosPorStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch)
	{
		$DispoData 		    = new DispoData();
		
		$sql = 	' SELECT *  '.
				' FROM dispo '.
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and producto		= '".$producto."'".
				"   and clasifica		= '".$clasifica_fox."'".
				"   and proveedor_id	= '".$proveedor_id."'".				
				"   and variedad_id		= '".$variedad_id."'".
				"   and grado_id		= '".$grado_id."'".
				"   and tallos_x_bunch  = ".$tallos_x_bunch.
				" ORDER BY id DESC";
				//" LIMIT 1";
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
/*		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro

		if($row){
			$DispoData->getId						($row['id']);
			$DispoData->getFecha 					($row['Fecha']);
			$DispoData->getInventarioId				($row['inventario_id']);
			$DispoData->getFechaBunch				($row['fecha_bunch']);
			$DispoData->getProveedorId				($row['proveedor_id']);
			$DispoData->getProducto					($row['producto']);
			$DispoData->getVariedadId				($row['variedad_id']);
			$DispoData->getGradoId					($row['grado_id']);
			$DispoData->getTallosxBunch				($row['tallos_x_bunch']);
			$DispoData->getClasifica				($row['clasifica']);
			$DispoData->getCantidadBunch			($row['cantidad_bunch']);
			$DispoData->getCantidadBunchDisponible	($row['cantidad_bunch_disponible']);
		
			return $DispoData;
		}else{
			return null;
		}//end if				
*/	}//end function consultarRegistrosPorStock
	
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $producto
	 * @param string $clasifica_fox
	 * @param string $proveedor_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param string $stock
	 * @return number
	 */
	public function actualizarStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch, $stock_new)
	{
		$DispoData = new DispoData();
		
		//Consulta el ultimo registro para obtener el ID
		//$DispoData = $this->consultarMaximoRegistroPorStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id);
		$result = $this->consultarRegistrosPorStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch);
		
		if ($result)
		{
			$reg_stock = $this->consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch);
			$stock_actual = $reg_stock[0]['tot_bunch_disponible'];
			$stock_process= $stock_new;
			$stock_diferencia = $stock_actual - $stock_new;
			
			foreach ($result as $reg)
			{
				if ($stock_new > $stock_actual)
				{
					//Incrementa el stock solo al primer registro
					
					$incremento = $stock_new - $stock_actual;					
					$bunch_disponible = $reg['cantidad_bunch_disponible'] + $incremento;					
					$id = $this->modificarBunchDisponibles($reg['id'], $bunch_disponible);

					break;
				}//end if

				if ($stock_new < $stock_actual)
				{	
					//Decrementa el stock
					if ($stock_diferencia > $reg['cantidad_bunch_disponible'])
					{
						$bunch_disponible	= 0;
						$decremento			= $reg['cantidad_bunch_disponible'];
						$stock_diferencia	= $stock_diferencia - $decremento;
					}else{
						$decremento			= $stock_diferencia;
						$bunch_disponible 	= $reg['cantidad_bunch_disponible'] - $decremento;
						$stock_diferencia 	= 0;
					}//end if

					//Actualiza
					$id = $this->modificarBunchDisponibles($reg['id'], $bunch_disponible);

					//Si el stock_process es CERO se sale del proceso
					if ($stock_diferencia==0)
					{
						break;
					}
					
				}//end if
			}//end foreach
		}else{
			//Ingresa
			//$DispoData->setId($valor);
			$reg_fecha = $this->consultarFechaMaximaDispo($inventario_id, $producto, $proveedor_id);

			if (empty($reg_fecha['fecha']))
			{
				$reg_fecha = $this->consultarFechaMaximaDispo($inventario_id, $producto, null);
			}//end if			
			
			$DispoData->setFecha 			($reg_fecha['fecha']);
			$DispoData->setInventarioId		($inventario_id);
			$DispoData->setFechaBunch		($reg_fecha['fecha_bunch']);
			$DispoData->setProveedorId		($proveedor_id);
			$DispoData->setProducto			($producto);
			$DispoData->setVariedadId		($variedad_id);
			$DispoData->setGradoId			($grado_id);
			$DispoData->setTallosxBunch		($tallos_x_bunch);
			$DispoData->setClasifica		($clasifica_fox);
			$DispoData->setCantidadBunch	($stock_new);
			$DispoData->setCantidadBunchDisponible($stock_new);

			$id = $this->ingresar($DispoData);
		}//end if

		return true;
	}//end function actualizarStock
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @return array
	 */
	public function agrupadoPorInventarioPorVariedad($inventario_id, $variedad_id, $orden = null, $variedad_nombre = null)
	{
		$sql = " SELECT inventario_id, variedad_id, variedad.nombre as variedad_nombre ".
				" FROM dispo INNER JOIN variedad ".
				"                    ON variedad.id = dispo.variedad_id ".
				" WHERE 1= 1";
		if (!empty($inventario_id)){
			$sql = $sql." and inventario_id 	= '".$inventario_id."'";
		}//end if
		if (!empty($variedad_id)){
			$sql = $sql." and variedad_id		= '".$variedad_id."'";
		}//end if
		if (!empty($variedad_nombre))
		{
			$sql = $sql." and variedad.nombre like '%".$variedad_nombre."%'";
		}//end if
		$sql = $sql." GROUP BY inventario_id, variedad_id ";
		if (!empty($orden))
		{
			$sql = $sql. " ORDER BY ".$orden;
		}//end if
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;
	}//end function agrupadoPorInventarioPorVariedadPorGrado



	/**
	 * 
	 * @param string $inventario_id
	 * @param int $clasifica_fox
	 * @return array
	 */
	public function variedadesNoExiste($inventario_id, $clasifica_fox)
	{
		$sql = 	' SELECT variedad.id as variedad_id, variedad.nombre as variedad_nombre '.
				' FROM variedad LEFT JOIN dispo '.
				'                      ON  dispo.variedad_id = variedad.id'. 
				"					   AND dispo.inventario_id = '".$inventario_id."'".
				"                      AND dispo.clasifica = '".$clasifica_fox."'".
				" WHERE variedad.estado = 'A'".
				'   and dispo.id IS NULL ';
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;		
	}//end function variedadesNoExiste

	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $clasifica_fox
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return array
	 */
	public function consultarPorInventarioPorCalidadPorVariedadPorGrado($inventario_id, $clasifica_fox, $variedad_id, $grado_id)
	{
		$sql = 	' SELECT proveedor_id, sum(cantidad_bunch_disponible) as tot_bunch_disponible '.
				' FROM dispo '.
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and clasifica		= '".$clasifica_fox."'".
				"   and variedad_id		= '".$variedad_id."'".
				"   and grado_id		= '".$grado_id."'";
		$sql = $sql." GROUP BY proveedor_id";
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro

		return $result;
	}//end function consultarPorInventarioPorCalidadPorVariedadPorGrado

	
	
	/**
	 *
	 * @param array $condiciones ($sin_fecha, $inventario_id, $clasifica, $proveedor_id, $variedad_id, $grado_id)
	 * @return array
	 */
	public function consultarDetallado($condiciones)
	{
		if ($condiciones['sin_fecha']==true)
		{
			$sql_fecha = '';
		}else{
			$sql_fecha = 'dispo.fecha, ';
		}//end if
		
		if ($condiciones['sin_fecha_bunch']==true)
		{
			$sql_fecha_bunch = '';			
		}else{
			$sql_fecha_bunch = 'dispo.fecha_bunch, ';
		}
		
		
		$sql = 	' SELECT '.$sql_fecha.' dispo.inventario_id, '.$sql_fecha_bunch.' dispo.proveedor_id, '.
				'        dispo.producto, dispo.variedad_id, dispo.tallos_x_bunch, dispo.clasifica, '.
				" 		 SUM(if(dispo.grado_id=40,  dispo.cantidad_bunch_disponible, 0)) as '40',".
				" 		 SUM(if(dispo.grado_id=50,  dispo.cantidad_bunch_disponible, 0)) as '50',".
				" 		 SUM(if(dispo.grado_id=60,  dispo.cantidad_bunch_disponible, 0)) as '60',".
				" 		 SUM(if(dispo.grado_id=70,  dispo.cantidad_bunch_disponible, 0)) as '70',".
				" 		 SUM(if(dispo.grado_id=80,  dispo.cantidad_bunch_disponible, 0)) as '80',".
				" 		 SUM(if(dispo.grado_id=90, dispo.cantidad_bunch_disponible, 0)) as '90',".
				" 		 SUM(if(dispo.grado_id=100, dispo.cantidad_bunch_disponible, 0)) as '100',".
				" 		 SUM(if(dispo.grado_id=110, dispo.cantidad_bunch_disponible, 0)) as '110'".
				' FROM dispo LEFT JOIN variedad '.
				'		                ON variedad.id      = dispo.variedad_id '.
				'            LEFT JOIN color_ventas '.
				'                       ON color_ventas.id	= variedad.color_ventas_id '.
				' WHERE 1 = 1 ';
	
		if (!empty($condiciones['inventario_id']))
		{
			$sql = $sql."	and dispo.inventario_id = '".$condiciones['inventario_id']."'";
		}//end if
	
		if (!empty($condiciones['proveedor_id']))
		{
			$sql = $sql."  and dispo.proveedor_id 	= '".$condiciones['proveedor_id']."'";
		}//end if

		if (!empty($condiciones['producto']))
		{
			$sql = $sql."   and dispo.producto		= '".$condiciones['producto']."'";
		}//end if
		
		if (!empty($condiciones['variedad_id']))
		{
			$sql = $sql."   and dispo.variedad_id	= '".$condiciones['variedad_id']."'";
		}//end if

		if (!empty($condiciones['tallos_x_bunch']))
		{
			$sql = $sql."   and dispo.tallos_x_bunch	= ".$condiciones['tallos_x_bunch'];
		}//end if
		
		if (!empty($condiciones['clasifica']))
		{
			$sql = $sql."   and dispo.clasifica		= '".$condiciones['clasifica']."'";
		}//end if

		/*	if (!empty($condiciones['grado_id']))
			{
			$sql = $sql."   and dispo.grado_id		= '".$condiciones['grado_id']."'";
			}//end if
		*/

		if (!empty($condiciones['cadena_color_ventas_ids']))
		{
			$sql = $sql." and variedad.color_ventas_id in (".$condiciones['cadena_color_ventas_ids'].")";
		}//end if
	
		if (!empty($condiciones['cadena_calidad_variedad_ids']))
		{
			$sql = $sql." and variedad.calidad_variedad_id in (".$condiciones['cadena_calidad_variedad_ids'].")";
		}//end if
	
		$sql = $sql.' GROUP BY '.$sql_fecha.' dispo.inventario_id, '.$sql_fecha_bunch.' dispo.proveedor_id, '.
				    '          dispo.producto, dispo.variedad_id, dispo.tallos_x_bunch, dispo.clasifica ';
	
		
		if (!empty($sql_fecha))			{$order_fecha 		= 'dispo.fecha DESC,';}			else{$order_fecha 		= '';}
		if (!empty($sql_fecha_bunch))	{$order_fecha_bunch = 'dispo.fecha_bunch DESC,';}	else{$order_fecha_bunch = '';}
		
		$sql = $sql.' ORDER BY '.$order_fecha.' dispo.inventario_id, '.$order_fecha_bunch.' dispo.proveedor_id, '.
					'          dispo.producto, dispo.variedad_id, dispo.tallos_x_bunch, dispo.clasifica ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarDetallado
	
	
	
	/**
	 * Consultar
	 *
	 * @param DispoData $DispoData
	 * @return DispoData|null
	 */
	public function consultarPorKey($DispoData)
	{
		$DispoData2 		    = new DispoData();
	
		$sql = 	' SELECT dispo.* '.
				' FROM dispo '.
				' WHERE dispo.fecha 		= :fecha '.
				'   and dispo.inventario_id = :inventario_id'.
				'   and dispo.fecha_bunch 	= :fecha_bunch'.
				'   and dispo.proveedor_id 	= :proveedor_id'.
				'   and dispo.producto 		= :producto'.
				'   and dispo.variedad_id	= :variedad_id'.
				'   and dispo.grado_id		= :grado_id'.
				'   and dispo.tallos_x_bunch= :tallos_x_bunch'.
				'   and dispo.clasifica		= :clasifica';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':fecha',$DispoData->getFecha());
		$stmt->bindValue(':inventario_id',$DispoData->getInventarioId());
		$stmt->bindValue(':fecha_bunch',$DispoData->getFechaBunch());
		$stmt->bindValue(':proveedor_id',$DispoData->getProveedorId());
		$stmt->bindValue(':producto',$DispoData->getProducto());
		$stmt->bindValue(':variedad_id',$DispoData->getVariedadId());
		$stmt->bindValue(':grado_id',$DispoData->getGradoId());
		$stmt->bindValue(':tallos_x_bunch',$DispoData->getTallosxbunch());
		$stmt->bindValue(':clasifica',$DispoData->getClasifica());

		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){	
			$DispoData2->setId						($row['id']);
			$DispoData2->setFecha 					($row['fecha']);
			$DispoData2->setInventarioId			($row['inventario_id']);
			$DispoData2->setFechaBunch				($row['fecha_bunch']);
			$DispoData2->setProveedorId				($row['proveedor_id']);
			$DispoData2->setProducto				($row['producto']);
			$DispoData2->setVariedadId				($row['variedad_id']);
			$DispoData2->setGradoId					($row['grado_id']);
			$DispoData2->setTallosxBunch			($row['tallos_x_bunch']);
			$DispoData2->setClasifica				($row['clasifica']);
			$DispoData2->setCantidadBunch			($row['cantidad_bunch']);
			$DispoData2->setCantidadBunchDisponible	($row['cantidad_bunch_disponible']);
	
			return $DispoData2;
		}else{
			return null;
		}//end if
	
	}//end function consultar

	
	/**
	 * 
	 * @param DispoData $DispoData
	 * @return int
	 */
	public function actualizarCeroStock($DispoData)
	{
		$proveedor_id = $DispoData->getProveedorId();
		
		$sql = 	" UPDATE dispo ".
				" SET cantidad_bunch_disponible = 0".
				" WHERE inventario_id 	= '".$DispoData->getInventarioId()."'";
		
		if (!empty($proveedor_id))
		{
			$sql = $sql."   and proveedor_id  	= '".$DispoData->getProveedorId()."'";
		}//end if
		
		$sql = $sql."   and producto		= '".$DispoData->getProducto()."'".
				"   and variedad_id		= '".$DispoData->getVariedadId()."'".
				"   and tallos_x_bunch  = ".$DispoData->getTallosxbunch().
				"   and clasifica		= '".$DispoData->getClasifica()."'";
		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;		
	}//end function actualizarCeroStock
	
	
	
	/**
	 * 
	 * @param DispoData $DispoData
	 * @param array $grados
	 * @param int $color_ventas_id
	 * @param int $calidad_variedad_id
	 */
	public function moverStock($DispoData, $grados, $color_ventas_id, $calidad_variedad_id, $clasifica_destino)
	{
		$GrupoDispoDetDAO 	= new GrupoDispoDetDAO();
		$DispoDAO			= new DispoDAO();
		$CalidadDAO			= new CalidadDAO();
		$GrupoDispoCabDAO	= new GrupoDispoCabDAO();
		$GrupoDispoDetData	= new GrupoDispoDetData();
		
		$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
		$DispoDAO->setEntityManager($this->getEntityManager());
		$CalidadDAO->setEntityManager($this->getEntityManager());
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		
		//Se establece el RANGO DE GRADOS al que se va afectar
		$flag_1era_vez = true;
		$sql_grados = '';
		foreach($grados as $reg)
		{
			if (!$flag_1era_vez)
			{
				$sql_grados = $sql_grados.",";
			}//end if			
			$sql_grados = $sql_grados."'".$reg['grado_id']."'";
			
			$flag_1era_vez = false;
		}//end foreach
		
		//Se consulta el registro de la DISPO ORIGEN
		$sql = 	" SELECT dispo.* ".
				" FROM dispo INNER JOIN variedad ".
				"                    ON variedad.id 		= dispo.variedad_id ";
		if (!empty($calidad_variedad_id))
		{
			$sql=$sql."             AND variedad.calidad_variedad_id = ".$calidad_variedad_id;
		}//end if		
		if (!empty($color_ventas_id))
		{
			$sql=$sql."      LEFT JOIN color_ventas ".
				"                    ON color_ventas.id 	= dispo.color_ventas_id ";
		}//end if
		$sql=$sql." WHERE dispo.inventario_id 	= '".$DispoData->getInventarioId()."'".
				"   and dispo.producto 			= '".$DispoData->getProducto()."'".
				"   and dispo.variedad_id		= '".$DispoData->getVariedadId()."'".
				"   and dispo.grado_id 			in (".$sql_grados.")".
				"   and dispo.tallos_x_bunch 	= ".$DispoData->getTallosxbunch().
				"	and dispo.clasifica		 	= '".$DispoData->getClasifica()."'";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);		
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro		
		
		$DispoDataFind 		= new DispoData();
		$DispoDataTarget 	= new DispoData();
		foreach($result as $reg)
		{
			$DispoDataFind->setFecha		($reg['fecha']);
			$DispoDataFind->setInventarioId	($reg['inventario_id']);
			$DispoDataFind->setFechaBunch	($reg['fecha_bunch']);
			$DispoDataFind->setProveedorId	($reg['proveedor_id']);
			$DispoDataFind->setProducto		($reg['producto']);
			$DispoDataFind->setVariedadId	($reg['variedad_id']);
			$DispoDataFind->setGradoId		($reg['grado_id']);
			$DispoDataFind->setTallosxBunch	($reg['tallos_x_bunch']);
			$DispoDataFind->setClasifica	($clasifica_destino);

			$DispoDataSource = $this->consultarPorKey($DispoDataFind);
			if (empty($DispoDataSource))
			{
				$DispoDataTarget->setFecha			($reg['fecha']);
				$DispoDataTarget->setInventarioId	($reg['inventario_id']);
				$DispoDataTarget->setFechaBunch		($reg['fecha_bunch']);
				$DispoDataTarget->setProveedorId	($reg['proveedor_id']);
				$DispoDataTarget->setProducto		($reg['producto']);
				$DispoDataTarget->setVariedadId		($reg['variedad_id']);
				$DispoDataTarget->setGradoId		($reg['grado_id']);
				$DispoDataTarget->setTallosxBunch	($reg['tallos_x_bunch']);
				$DispoDataTarget->setClasifica		($clasifica_destino);
				$DispoDataTarget->setCantidadBunch	($reg['cantidad_bunch']);
				$DispoDataTarget->setCantidadBunchDisponible($reg['cantidad_bunch_disponible']);
				
				$DispoDataTarget->setClasifica($clasifica_destino);
				$id = $this->ingresar($DispoDataTarget);
			}else{
				//Se actualiza la dispo de DESTINO (acumula)
				$sql = 	" UPDATE dispo ".
					   	" SET cantidad_bunch_disponible = cantidad_bunch_disponible + ".$reg['cantidad_bunch_disponible'].
						" WHERE fecha				= '".$reg['fecha']."'".
						"	and inventario_id 		= '".$reg['inventario_id']."'".
						"   and fecha_bunch			= '".$reg['fecha_bunch']."'".
						"   and proveedor_id		= '".$reg['proveedor_id']."'".
						"	and producto			= '".$reg['producto']."'".
						"   and variedad_id			= '".$reg['variedad_id']."'".
						"	and grado_id			= ".$reg['grado_id'].
						"	and tallos_x_bunch		= ".$reg['tallos_x_bunch'].
						"	and clasifica			= '".$clasifica_destino."'";
				$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
			}//end if
			

			//Se resta la dispo de ORIGEN (DISMINUYE)
			$sql = 	" UPDATE dispo ".
					" SET cantidad_bunch_disponible = cantidad_bunch_disponible - ".$reg['cantidad_bunch_disponible'].
					" WHERE fecha				= '".$reg['fecha']."'".
					"	and inventario_id 		= '".$reg['inventario_id']."'".
					"   and fecha_bunch			= '".$reg['fecha_bunch']."'".
					"   and proveedor_id		= '".$reg['proveedor_id']."'".
					"   and producto			= '".$reg['producto']."'".
					"   and variedad_id			= '".$reg['variedad_id']."'".
					"	and grado_id			= ".$reg['grado_id'].
					"	and tallos_x_bunch		= ".$reg['tallos_x_bunch'].
					"	and clasifica			= '".$reg['clasifica']."'";
			$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
				
			

			//ACTUALIZA EL STOCK DE LOS GRUPOS DESTINO
			$tot_stock = $DispoDAO->consultartotalInventario($reg['inventario_id'], $reg['producto'], $clasifica_destino, $reg['variedad_id'], $reg['grado_id'], $reg['tallos_x_bunch']);
			/*if ($tot_stock['tot_cantidad_bunch'] < $tot_stock['tot_bunch_disponible'])
			{
				$tot_stock['tot_bunch_disponible'] = $tot_stock['tot_cantidad_bunch'];
			}//end if
			*/
			$CalidadData = $CalidadDAO->consultarPorClasificaFox($clasifica_destino);
			$result_dispocab = $GrupoDispoCabDAO->consultarPorInventario($reg['inventario_id'], $CalidadData->getId());
			foreach($result_dispocab as $reg_dispocab)
			{
				$GrupoDispoDetData->setGrupoDispoCabId			($reg_dispocab['id']);
				$GrupoDispoDetData->setProductoId				($reg['producto']);
				$GrupoDispoDetData->setVariedadId				($reg['variedad_id']);
				$GrupoDispoDetData->setGradoId					($reg['grado_id']);
				$GrupoDispoDetData->setTallosXBunch				($reg['tallos_x_bunch']);
				$GrupoDispoDetData->setCantidadBunch			($tot_stock['tot_cantidad_bunch']);
				$GrupoDispoDetData->setCantidadBunchDisponible	($tot_stock['tot_bunch_disponible']);
				$GrupoDispoDetData->setUsuarioModId				(1);
				$GrupoDispoDetData->setUsuarioIngId				(1);
			
				$GrupoDispoDetDAO->registrar($GrupoDispoDetData);
			}//end foreach	

			
			//ACTUALIZA EL STOCK DE LOS GRUPOS ORIGEN
			$tot_stock = $DispoDAO->consultartotalInventario($reg['inventario_id'], $reg['producto'], $reg['clasifica'], $reg['variedad_id'], $reg['grado_id'], $reg['tallos_x_bunch']);
			/*if ($tot_stock['tot_cantidad_bunch'] < $tot_stock['tot_bunch_disponible'])
			{
				$tot_stock['tot_bunch_disponible'] = $tot_stock['tot_cantidad_bunch'];
			}//end if
			*/
			$CalidadData = $CalidadDAO->consultarPorClasificaFox($reg['clasifica']);
			$result_dispocab = $GrupoDispoCabDAO->consultarPorInventario($reg['inventario_id'], $CalidadData->getId());
			foreach($result_dispocab as $reg_dispocab)
			{
				$GrupoDispoDetData->setGrupoDispoCabId			($reg_dispocab['id']);
				$GrupoDispoDetData->setProductoId				($reg['producto']);
				$GrupoDispoDetData->setVariedadId				($reg['variedad_id']);
				$GrupoDispoDetData->setGradoId					($reg['grado_id']);
				$GrupoDispoDetData->setTallosXBunch				($reg['tallos_x_bunch']);
				$GrupoDispoDetData->setCantidadBunch			($tot_stock['tot_cantidad_bunch']);
				$GrupoDispoDetData->setCantidadBunchDisponible	($tot_stock['tot_bunch_disponible']);
				$GrupoDispoDetData->setUsuarioModId				(1);
				$GrupoDispoDetData->setUsuarioIngId				(1);
					
				$GrupoDispoDetDAO->registrar($GrupoDispoDetData);
			}//end foreach			
			
			
			
		}//end foreach
		
		return true;
	}//end function moverStock
	
	
	
	
	/**
	 *
	 * @param string $inventario_id
	 * @param string $clasifica_fox
	 * @return array
	 */
	function consultarFechaMinimaInventario($inventario_id, $clasifica_fox)
	{
		$sql = 	" SELECT min(fecha) as fecha_minima ".
				" FROM dispo ".
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and clasifica		= '".$clasifica_fox."'";
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$reg = $stmt->fetch();
		return $reg;
	}//end function consultarFechaMinimaInventario	

	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $producto
	 * @param string $clasifica_fox
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @return array
	 */
	function consultartotalInventario($inventario_id, $producto, $clasifica_fox, $variedad_id, $grado_id, $tallos_x_bunch)
	{
		$sql = 	" SELECT sum(cantidad_bunch) as tot_cantidad_bunch, sum(cantidad_bunch_disponible) as tot_bunch_disponible ".
				" FROM dispo ".
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and producto		= '".$producto."'".
				"   and clasifica		= '".$clasifica_fox."'".
				"   and variedad_id		= '".$variedad_id."'".
				"	and grado_id		= '".$grado_id."'".
				"	and tallos_x_bunch	= ".$tallos_x_bunch;
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$reg = $stmt->fetch();
		return $reg;
	}//end function consultartotalInventario


	/**
	 * 
	 * @param array $result
	 * @return array
	 */
	function TransformarResultIndexadoProveedor($result)
	{
		//Estructura la forma de ver la informacion
		$result2 = null;
		foreach($result as $reg)
		{
			$key = $reg['producto_id'].'-'.$reg['variedad_id'].'-'.$reg['tallos_x_bunch'];
			$subkey = $reg['proveedor_id'];
			$result2[$key][$subkey] = $reg;
		}//end foreach
		
		//Se totaliza el STOCK con la estructura reformada
		foreach($result2 as &$reg)
		{
			$total['40'] = 0;
			$total['50'] = 0;
			$total['60'] = 0;
			$total['70'] = 0;
			$total['80'] = 0;
			$total['90'] = 0;
			$total['100'] = 0;
			$total['110'] = 0;
			foreach($reg as $key_proveedor => $reg_proveedor)
			{
				$total['40'] 	= $total['40'] + $reg_proveedor['40'];
				$total['50'] 	= $total['50'] + $reg_proveedor['50'];
				$total['60'] 	= $total['60'] + $reg_proveedor['60'];
				$total['70'] 	= $total['70'] + $reg_proveedor['70'];
				$total['80'] 	= $total['80'] + $reg_proveedor['80'];
				$total['90'] 	= $total['90'] + $reg_proveedor['90'];
				$total['100'] 	= $total['100'] + $reg_proveedor['100'];
				$total['110'] 	= $total['110'] + $reg_proveedor['110'];				
			}//end foreach
			
			$reg['40'] 	= $total['40'];
			$reg['50'] 	= $total['50'];
			$reg['60'] 	= $total['60'];
			$reg['70'] 	= $total['70'];
			$reg['80'] 	= $total['80'];
			$reg['90'] 	= $total['90'];
			$reg['100'] = $total['100'];
			$reg['110'] = $total['110'];
		}//end foreach

		return $result2;
	}//end function TransformarResultIndexadoProveedor

	
	
	/**
	 * 
	 * @param string $producto_id
	 * @param string $inventario_id
	 * @param string $clasifica_fox
	 * @param string $variedad_id
	 * @param int $tallos_x_bunch
	 * @return array
	 */
	function consultarPorInventarioPorClasificaPorVariedadPorTallos($producto_id, $inventario_id, $clasifica_fox, $variedad_id, $tallos_x_bunch,
																	$opcion_dispo = 'BUNCH_TODOS', $dispo_rotacion_dias_inicio = NULL,
																	$arr_fechas_cajas = NULL)
	{
		$sql = 	" SELECT grado_id, proveedor_id, sum(cantidad_bunch_disponible) as tot_bunch_disponible ".
				" FROM dispo ".
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and producto		= '".$producto_id."'".
				"   and clasifica		= '".$clasifica_fox."'".
				"   and variedad_id		= '".$variedad_id."'".
			//	"	and grado_id		= '".$grado_id."'".
				"	and tallos_x_bunch	= ".$tallos_x_bunch;

		switch($opcion_dispo)
		{
			case 'BUNCH_ROTACION':
				$sql = $sql." and fecha_bunch <= DATE_SUB(DATE_FORMAT(NOW(),'%y-%m-%d') , INTERVAL ".$dispo_rotacion_dias_inicio." DAY)";
				break;
				
			case 'BUNCH_NUEVA':
				$sql = $sql." and fecha_bunch > DATE_SUB(DATE_FORMAT(NOW(),'%y-%m-%d') , INTERVAL ".$dispo_rotacion_dias_inicio." DAY)";
				break;
				
			case 'BUNCH_TODOS':
				break;
				
			case 'BUNCH_X_FECHA':
				$sql = $sql.' and fecha_bunch in (';
				$bd_entra = false;
				foreach($arr_fechas_cajas as $fecha)
				{
					$sql = $sql."'".$fecha."',";
					$bd_entra = true;
				}//end foreach
				if ($bd_entra==true)
				{
					$sql = substr($sql,0,-1);
				}//end if
				$sql = $sql.')';
				break;
		}//end switch
		
		$sql = $sql." GROUP BY grado_id, proveedor_id ".
				" ORDER BY tot_bunch_disponible DESC";
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();

		$result2 = null;
		foreach($result as $reg)
		{
			$reg_new[$reg['grado_id']][$reg['proveedor_id']] = $reg['tot_bunch_disponible'];
			$result2 = $reg_new;
		}//end foreach
		
		return $result2;		
	}//end function consultarPorInventarioPorClasificaPorVariedadPorTallos
	
	
	
	/**
	 *
	 * @param array $condiciones (inventario_id, proveedor_id, clasifica, color_ventas_id, calidad_variedad_id, nro_tallos, $group_by_proveedor_id)
	 * @return array:
	 */
	public function listadoAgrupadoPorFechaBunch($condiciones)
	{
		$sql = 	' SELECT dispo.fecha_bunch, count(*) as nro_reg '.
				' FROM dispo LEFT JOIN variedad '.
				'		                ON variedad.id      = dispo.variedad_id '.
				'            LEFT JOIN color_ventas '.
				'                       ON color_ventas.id	= variedad.color_ventas_id '.
				' WHERE dispo.cantidad_bunch_disponible > 0 ';

		if (!empty($condiciones['inventario_id']))
		{
			$sql = $sql." and dispo.inventario_id = '".$condiciones['inventario_id']."'";
		}//end if
	
		if (!empty($condiciones['proveedor_id']))
		{
			$sql = $sql." and dispo.proveedor_id = '".$condiciones['proveedor_id']."'";
		}//end if
	
		if (!empty($condiciones['clasifica']))
		{
			$sql = $sql." and dispo.clasifica = '".$condiciones['clasifica']."'";
		}//end if
	
		if (!empty($condiciones['color_ventas_id']))
		{
			$sql = $sql." and variedad.color_ventas_id = ".$condiciones['color_ventas_id'];
		}//end if
	
		if (!empty($condiciones['calidad_variedad_id']))
		{
			$sql = $sql." and variedad.calidad_variedad_id = ".$condiciones['calidad_variedad_id'];
		}//end if
	
		if (!empty($condiciones['cadena_color_ventas_ids']))
		{
			$sql = $sql." and variedad.color_ventas_id in (".$condiciones['cadena_color_ventas_ids'].")";
		}//end if

		if (!empty($condiciones['cadena_calidad_variedad_ids']))
		{
			$sql = $sql." and variedad.calidad_variedad_id in (".$condiciones['cadena_calidad_variedad_ids'].")";
		}//end if

		if (!empty($condiciones['nro_tallos']))
		{
			switch($condiciones['nro_tallos'])
			{
				case 'NO25':
					$sql = $sql." and tallos_x_bunch <> 25";
					break;
						
				default:
					$sql = $sql." and tallos_x_bunch = ".$condiciones['nro_tallos'];
					break;
			}//end switch
				
		}//end if
	
		$sql = $sql.' GROUP BY dispo.fecha_bunch ';
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listadoAgrupadoPorFechaBunch	
}//end class


?>