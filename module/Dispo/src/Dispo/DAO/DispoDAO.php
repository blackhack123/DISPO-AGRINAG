<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\DispoData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DispoDAO extends Conexion 
{
	private $table_name	= 'dispo';

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
	 * @param int $grupo_dispo_cab_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param string $clasifica_fox
	 * @return array
	 */
	public function consultarInventarioPorCliente($cliente_id, $inventario_id, $grupo_dispo_cab_id, $variedad_id, $grado_id, $clasifica_fox)
	{
		$sql = 	' SELECT variedad.nombre as variedad_nombre,'.
				'		dispo.variedad_id, '.
				'		dispo.grado_id,'.
				'		dispo.proveedor_id, '.				
				'		grupo_dispo_det.cantidad_bunch_disponible as grupo_dispo_det_cantidad_bunch_disponible, '.
				'       grupo_precio_det.precio, grupo_precio_det.precio_oferta, '.
				'	sum(dispo.cantidad_bunch_disponible) as tot_bunch_disponible, '.
				'	sum(dispo.tallos_x_bunch) as tot_tallos_x_bunch,'.
				'	count(*) as veces_tallos_x_bunch'.
				' FROM cliente INNER JOIN grupo_precio_det'.
				'					 ON grupo_precio_det.grupo_precio_cab_id = cliente.grupo_precio_cab_id';
		if (!empty($variedad_id)){
			$sql = $sql."		    AND grupo_precio_det.variedad_id	= '".$variedad_id."'";
		}//end if
		if (!empty($grado_id)){
			$sql = $sql."		    AND grupo_precio_det.grado_id		= '".$grado_id."'";
		}//end if				
		$sql = $sql.'		 INNER JOIN dispo '.
				"					 ON dispo.inventario_id	= '".$inventario_id."'".
				'					AND dispo.variedad_id	= grupo_precio_det.variedad_id'.
				'					AND dispo.grado_id		= grupo_precio_det.grado_id'.
				"					AND dispo.clasifica		= '".$clasifica_fox."'".
				'					AND dispo.cantidad_bunch_disponible > 0'.
				'		 	 INNER JOIN variedad'.
				'					 ON variedad.id			= dispo.variedad_id'.
				'            LEFT JOIN grupo_dispo_det '.
				'                    ON grupo_dispo_det.grupo_dispo_cab_id 		= '.$grupo_dispo_cab_id.
				'                   AND grupo_dispo_det.variedad_id				= dispo.variedad_id'.
				'					AND grupo_dispo_det.grado_id    			= dispo.grado_id '.
				" WHERE cliente.id = '".$cliente_id."'".
				//" AND dispo.clasifica = '1'".//PARA TOMAR CALIDAD DE FLOR (RECIEN ADICIONADO)
				' GROUP BY variedad.nombre, dispo.variedad_id, dispo.grado_id, dispo.proveedor_id, grupo_dispo_det.cantidad_bunch_disponible, grupo_precio_det.precio, grupo_precio_det.precio_oferta '.
				' ORDER BY variedad.nombre, dispo.variedad_id, dispo.grado_id, tot_bunch_disponible DESC';

		//die($sql);
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;		
	}//end function consultarInventarioPorCliente
	
	
	
	/**
	 * 
	 * @param string $proveedor_id
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return array
	 */
	public function consultarInventarioPorProveedor($proveedor_id, $inventario_id, $variedad_id, $grado_id)
	{
		$sql = " SELECT cantidad_bunch_disponible, fecha, inventario_id, fecha_bunch,  ".
				"        proveedor_id, variedad_id, grado_id ".
				" FROM dispo ".
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and proveedor_id  	= '".$proveedor_id."'".
				"   and variedad_id		= '".$variedad_id."'".
				"   and grado_id		= '".$grado_id."'";
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
				"   and variedad_id		= '".$DispoData->getVariedadId()."'".
				"   and grado_id		= '".$DispoData->getGradoId()."'";

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;		
	}//end function rebajar
	
	
	

	/**
	 *
	 * @param array $condiciones (inventario_id, proveedor_id, $clasifica)
	 * @return array:
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT variedad.nombre as variedad, dispo.variedad_id, '.
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

		$sql=$sql.' GROUP BY variedad.nombre, dispo.variedad_id ';
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
	 * @return array
	 */
	public function consultarPorInventarioPorCalidadPorProveedorPorGrado($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id)
	{
		$sql = 	' SELECT proveedor_id, sum(cantidad_bunch_disponible) as tot_bunch_disponible '.
				' FROM dispo '.
				" WHERE inventario_id 	= '".$inventario_id."'".
				"   and clasifica		= '".$clasifica_fox."'".
				"   and variedad_id		= '".$variedad_id."'".
				"   and grado_id		= '".$grado_id."'";

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
	}//end function consultarPorInventarioPorCalidadPorProveedorPorGrado
	
	
	
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
				"   and producto		= '".$producto."'".
				"   and proveedor_id	= '".$proveedor_id."'";
		
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
	 * @return array
	 */
	public function consultarRegistrosPorStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id)
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
		$result = $this->consultarRegistrosPorStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id);
		
		if ($result)
		{
			$reg_stock = $this->consultarPorInventarioPorCalidadPorProveedorPorGrado($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id);
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
	
	
	
}//end class

?>