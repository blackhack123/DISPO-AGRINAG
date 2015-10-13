<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\DispoData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DispoDAO extends Conexion 
{
	private $table_name	= 'dispo';

	
	public function registrarBunchDisponibles(DispoData $DispoData)
	{
		$DispoData2 = $this->consultar($DispoData->getId());
		
		if (empty($DispoData2))
		{
			$accion = 'I';
			$key = $this->ingresar($DispoData);
		}else{
			$accion = 'M';
			$key = $this->modificarStockBunchDisponibles($DispoData->getId(), $DispoData);
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
	 * @param int $id
	 * @param DispoData $DispoData
	 * @return int
	 */
	public function modificarStockBunchDisponibles($id, $DispoData)
	{
		$key    = array(
				'id'						        => $id,
		);
		$record = array(
				'cantidad_bunch'		    		=> $DispoData->getCantidad_bunch(),
				'cantidad_bunch_disponible'		    => $DispoData->getCantidadBunchDisponible()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $id;
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
	 * consultarInventarioPorCliente
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
	public function consultarInventarioPorUsuario($usuario_id, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $clasifica_fox)
	{
		$sql = 	' SELECT dispo.producto as producto_id, '.
				'       variedad.nombre as variedad_nombre,'.
				'		dispo.variedad_id, '.
				'		dispo.grado_id,'.
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
		if (!empty($tallos_x_bunch)){
			$sql = $sql."           AND grupo_precio_det.tallos_x_bunch = ".$tallos_x_bunch;
		}//end if			
		$sql = $sql.'		 INNER JOIN dispo '.
				"					 ON dispo.inventario_id	= usuario.inventario_id ".  /*INVENTARIO*/
				'                   AND dispo.producto      = grupo_precio_det.producto_id'.
				'					AND dispo.variedad_id	= grupo_precio_det.variedad_id'.
				'					AND dispo.grado_id		= grupo_precio_det.grado_id'.
				'					AND dispo.tallos_x_bunch= grupo_precio_det.tallos_x_bunch'.
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
	}//end function consultarInventarioPorCliente
	
	
	
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
	 * @param array $condiciones (inventario_id, proveedor_id, clasifica, color_ventas_id, calidad_variedad_id)
	 * @return array:
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT dispo.producto as producto_id, variedad.nombre as variedad, dispo.variedad_id, dispo.tallos_x_bunch, '.
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
		
		
		$sql=$sql.' GROUP BY variedad.nombre, dispo.variedad_id, tallos_x_bunch, color_ventas.nombre, url_ficha ';
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
	 * @param array $condiciones ($inventario_id, $clasifica, $proveedor_id, $variedad_id, $grado_id)
	 * @return array
	 */
	public function consultarDetallado($condiciones)
	{
		$sql = 	' SELECT dispo.id, dispo.fecha, dispo.inventario_id, dispo.fecha_bunch, dispo.proveedor_id, '.
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
		
		if (!empty($condiciones['clasifica']))
		{		
			$sql = $sql."   and dispo.clasifica		= '".$condiciones['clasifica']."'";
		}//end if
		
		if (!empty($condiciones['proveedor_id']))
		{
			$sql = $sql."  and dispo.proveedor_id 	= '".$condiciones['proveedor_id']."'";
		}//end if
		
		if (!empty($condiciones['variedad_id']))
		{
			$sql = $sql."   and dispo.variedad_id	= '".$condiciones['variedad_id']."'";
		}//end if
		
/*		if (!empty($condiciones['grado_id']))
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

		$sql = $sql.' GROUP BY dispo.id, dispo.fecha, dispo.inventario_id, dispo.fecha_bunch, dispo.proveedor_id, '.
				    '        dispo.producto, dispo.variedad_id, dispo.tallos_x_bunch, dispo.clasifica ';
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro

		return $result;
	}//end function consultarDetallado
		
	
}//end class


?>