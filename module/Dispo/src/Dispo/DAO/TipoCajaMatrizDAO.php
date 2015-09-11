<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dispo\Data\TipoCajaMatrizData;

class TipoCajaMatrizDAO extends Conexion 
{
	private $table_name	= 'tipo_caja_matriz';


	/**
	 * 
	 * @param unknown $inventario_id
	 * @param unknown $marcacion_sec
	 * @param unknown $variedad_id
	 * @param unknown $grado_id
	 * @param unknown $tipo_caja_id
	 * @return unknown
	 */
	public function consultaPorInventarioPorMarcacionPorVariedadPorGrado($inventario_id, $marcacion_sec, $variedad_id, $grado_id, $tipo_caja_id)
	{
		$sql =	' SELECT (CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  'MAR' ".
				"           ELSE 'MAT'".
				'       END) as tipo_caja_origen_estado, '.
				'   	(CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  tipo_caja_marcacion.id ".
				"           ELSE tipo_caja_matriz.id".
				'       END) as tipo_caja_origen_id, '.
				'   	(CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  tipo_caja_marcacion.unds_bunch ".
				"           ELSE tipo_caja_matriz.unds_bunch".
				'       END) as tipo_caja_unds_bunch, '.
				'   	(CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  tipo_caja_marcacion.tipo_caja_id ".
				"           ELSE tipo_caja_matriz.tipo_caja_id".
				'       END) as tipo_caja_id '.
				' FROM tipo_caja_matriz '.
				'            LEFT JOIN tipo_caja_marcacion '.
				'                   ON tipo_caja_marcacion.marcacion_sec = '.$marcacion_sec.
				"				   AND tipo_caja_marcacion.tipo_caja_id	 = tipo_caja_matriz.tipo_caja_id".
				'				   AND tipo_caja_marcacion.inventario_id = tipo_caja_matriz.inventario_id'.
				'				   AND tipo_caja_marcacion.variedad_id   = tipo_caja_matriz.variedad_id'.
				'				   AND tipo_caja_marcacion.grado_id    	 = tipo_caja_matriz.grado_id'.
				'             LEFT JOIN tipo_caja as tipo_caja_maestro_mat '.
				'                   ON tipo_caja_maestro_mat.id			 = tipo_caja_matriz.tipo_caja_id '.
				'             LEFT JOIN tipo_caja as tipo_caja_maestro_mar '.
				'                   ON tipo_caja_maestro_mar.id			 = tipo_caja_matriz.tipo_caja_id '.
				" WHERE tipo_caja_matriz.inventario_id 	= '".$inventario_id."'".
				"   AND tipo_caja_matriz.variedad_id	= '".$variedad_id."'".
				"   AND tipo_caja_matriz.grado_id		= '".$grado_id."'";
		if(!empty($tipo_caja_id))
		{
			$sql = $sql . " AND tipo_caja_matriz.tipo_caja_id 	= '".$tipo_caja_id."'";
		}//end if
		$sql = $sql." LIMIT 1";
				
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$reg = $stmt->fetch();
		return $reg;
	}//end function consultaPorInventarioPorMarcacionPorVariedadPorGrado
	
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return array
	 */
	public function consultarTipoCajaPorInventarioPorVariedadPorGrado($inventario_id, $variedad_id, $grado_id)
	{
		$sql = ' SELECT tipo_caja_id '.
								' FROM tipo_caja_matriz'.
								" WHERE inventario_id = '".$inventario_id."'".
								"   and variedad_id   = '".$variedad_id."'".
								"   and grado_id      = '".$grado_id."'";
			
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
	
		return $result;
	}//end class consultarTipoCajaPorInventarioPorVariedadPorGrado	
	
	
	
	
	/**
	 *
	 * @param array $condiciones (tipo_caja_id, inventario_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT variedad.nombre as variedad, tipo_caja_matriz.variedad_id, '.
				" 		 sum(if(tipo_caja_matriz.grado_id=40,  tipo_caja_matriz.unds_bunch, 0)) as '40',".
				" 		 sum(if(tipo_caja_matriz.grado_id=50,  tipo_caja_matriz.unds_bunch, 0)) as '50',".
				" 		 sum(if(tipo_caja_matriz.grado_id=60,  tipo_caja_matriz.unds_bunch, 0)) as '60',".
				" 		 sum(if(tipo_caja_matriz.grado_id=70,  tipo_caja_matriz.unds_bunch, 0)) as '70',".
				" 		 sum(if(tipo_caja_matriz.grado_id=80,  tipo_caja_matriz.unds_bunch, 0)) as '80',".
				" 		 sum(if(tipo_caja_matriz.grado_id=90,  tipo_caja_matriz.unds_bunch, 0)) as '90',".
				" 		 sum(if(tipo_caja_matriz.grado_id=100, tipo_caja_matriz.unds_bunch, 0)) as '100',".
				" 		 sum(if(tipo_caja_matriz.grado_id=110, tipo_caja_matriz.unds_bunch, 0)) as '110'".
				' FROM tipo_caja_matriz INNER JOIN variedad '.
				'		                      ON variedad.id = tipo_caja_matriz.variedad_id '.
				" WHERE tipo_caja_matriz.tipo_caja_id 	= '".$condiciones['tipo_caja_id']."'".
				"   and tipo_caja_matriz.inventario_id	='".$condiciones['inventario_id']."'".
				' GROUP BY variedad.nombre, variedad.id'.
				" ORDER BY variedad.nombre ";
			
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		foreach($result as &$reg)
		{
			$reg['variedad'] = trim($reg['variedad']);
		}
	
		return $result;
	}//end function listado
	
	
	/**
	 * 
	 * @param string $tipo_caja_id
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return array
	 */
	public function consultaRegUnico($tipo_caja_id, $inventario_id, $variedad_id, $grado_id)
	{
		$sql = ' SELECT id '.
				' FROM tipo_caja_matriz'.
				" WHERE tipo_caja_id  = '".$tipo_caja_id."'". 
				"	and	inventario_id = '".$inventario_id."'".
				"   and variedad_id   = '".$variedad_id."'".
				"   and grado_id      = '".$grado_id."'";

		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$reg = $stmt->fetch();
		
		return $reg;		
	}//end function consultaRegUnico
	
	
	
	/**
	 * 
	 * @param TipoCajaMatrizData $TipoCajaMatrizData
	 * @return array (id, accion)
	 */
	public function registrarBunchs(TipoCajaMatrizData $TipoCajaMatrizData)
	{
		$regCajaMatriz = $this->consultaRegUnico(
														$TipoCajaMatrizData->getTipoCajaId(), 
														$TipoCajaMatrizData->getInventarioId(),
														$TipoCajaMatrizData->getVariedadId(),
														$TipoCajaMatrizData->getGradoId()
														);
		if ($regCajaMatriz)
		{
			$TipoCajaMatrizData->setId($regCajaMatriz['id']);
			$id 	= $this->modificarUndsBunch($TipoCajaMatrizData);
			$accion = \Application\Constants\Accion::MODIFICAR;
		}else{
			$id 	= $this->ingresar($TipoCajaMatrizData);
			$accion = \Application\Constants\Accion::INGRESAR;
		}//end if
		$result['id'] 		= $id;
		$result['accion'] 	= $accion;
		
		return $result;
	}//end function registrarBunchs
	
	
	
	/**
	 * Ingresar
	 *
	 * @param TipoCajaMatrizData $TipoCajaMatrizData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(TipoCajaMatrizData $TipoCajaMatrizData)
	{
		$key    = array(
				'id'				=> $TipoCajaMatrizData->getId(),
		);
		$record = array(
				'tipo_caja_id' 		=> $TipoCajaMatrizData->getTipoCajaId(),			
				'inventario_id' 	=> $TipoCajaMatrizData->getInventarioId(),			
				'variedad_id' 		=> $TipoCajaMatrizData->getVariedadId(),
				'grado_id' 			=> $TipoCajaMatrizData->getGradoId(),
				'unds_bunch' 		=> $TipoCajaMatrizData->getUndsBunch(),
				'fec_ingreso' 		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'fec_modifica' 		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id' 	=> $TipoCajaMatrizData->getUsuarioIngId(),
				'usuario_mod_id' 	=> $TipoCajaMatrizData->getUsuarioModId(),
		);

		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar
	
	
	
	/**
	 * Modificar
	 *
	 * @param TipoCajaMatrizData $TipoCajaMatrizData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificarUndsBunch(TipoCajaMatrizData $TipoCajaMatrizData)
	{
		$key    = array(
				'id'				=> $TipoCajaMatrizData->getId(),
		);
		$record = array(
				'unds_bunch' 		=> $TipoCajaMatrizData->getUndsBunch(),
				'fec_modifica' 		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id' 	=> $TipoCajaMatrizData->getUsuarioModId(),
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $TipoCajaMatrizData->getId();
	}//end function modificarUndsBunch
	
	
	/**
	 *
	 * @param int $id
	 * @param int $resultType
	 * @return \Dispo\Data\TipoCajaMatrizData|NULL
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
		
				$TipoCajaMatrizData 		    = new TipoCajaMatrizData();
			
				$sql = 	' SELECT tipo_caja_matriz.* '.
						' FROM tipo_caja_matriz '.
						' WHERE id 	= :id ';
			
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$TipoCajaMatrizData->setId				($row['id']);
					$TipoCajaMatrizData->setTipoCajaId		($row['tipo_caja_id']);
					$TipoCajaMatrizData->setInventarioId	($row['inventario_id']);
					$TipoCajaMatrizData->setVariedadId		($row['variedad_id']);
					$TipoCajaMatrizData->setGradoId			($row['grado_id']);
					$TipoCajaMatrizData->setUndsBunch		($row['unds_bunch']);
					$TipoCajaMatrizData->setUsuarioIngId    ($row['usuario_ing_id']);
					$TipoCajaMatrizData->setFecIngreso		($row['fec_ingreso']);
					$TipoCajaMatrizData->setUsuarioModId    ($row['usuario_mod_id']);
					$TipoCajaMatrizData->setFecModifica		($row['fec_modifica']);
			
					return $GrupoDispoDetData;
				}else{
					return null;
				}//end if
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT tipo_caja_matriz.*, '.
						'	     usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM tipo_caja_matriz LEFT JOIN usuario as usuario_ing '.
						 '                           ON usuario_ing.id = agencia_carga.usuario_ing_id '.
						 '					 	LEFT JOIN usuario as usuario_mod '.
						 '                           ON usuario_mod.id = agencia_carga.usuario_mod_id '.
						' WHERE tipo_caja_matriz.id = :id ';
			
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch
	}//end function consultar

	
	
	/**
	 *
	 * @param array (tipo_caja_id, inventario_id, $variedad_id, grado_id) 
	 * @return array
	 */
	public function listadoDetallado($condiciones)
	{
		$sql = " SELECT tipo_caja_id, inventario_id, variedad_id, grado_id, unds_bunch ".
				" FROM tipo_caja_matriz ".
				" WHERE 1= 1";
		if (!empty($condiciones['tipo_caja_id'])){
			$sql = $sql." and tipo_caja_id	= '".$condiciones['tipo_caja_id']."'";
		}//end if
		if (!empty($condiciones['inventario_id'])){
			$sql = $sql." and inventario_id = '".$condiciones['inventario_id']."'";
		}//end if
		if (!empty($condiciones['variedad_id'])){
			$sql = $sql." and variedad_id	= '".$condiciones['variedad_id']."'";
		}//end if
		if (!empty($condiciones['grado_id'])){
			$sql = $sql." and grado_id		= '".$condiciones['grado_id']."'";
		}//end if
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;
	}//end function listadoDetallado
	
}//end class

?>