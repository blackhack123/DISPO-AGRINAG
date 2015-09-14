<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\TipoCajaMarcacionData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TipoCajaMarcacionDAO extends Conexion 
{
	private $table_name	= 'tipo_caja_marcacion';

	/**
	 * Ingresar
	 *
	 * @param TipoCajaMarcacionData $TipoCajaMarcacionData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(TipoCajaMarcacionData $TipoCajaMarcacionData)
	{
		$key    = array(
				'id'						        => $TipoCajaMarcacionData->getId(),
		);
		$record = array(
				'id'								=> $TipoCajaMarcacionData->getId(),
				'marcacion_sec'		                => $TipoCajaMarcacionData->getMarcacionSec(),
				'tipo_caja_id'	        			=> $TipoCajaMarcacionData->getTipoCajaId(),
				'inventario_id'		                => $TipoCajaMarcacionData->getInventarioId(),
				'variedad_id'		                => $TipoCajaMarcacionData->getVariedadId(),
				'grado_id'		                	=> $TipoCajaMarcacionData->getGradoId(),
				'unds_bunch'		                => $TipoCajaMarcacionData->getUndsBunch(),
				'tipo_caja_id1'		                => $TipoCajaMarcacionData->getTipoCajaId1()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param TipoCajaMarcacionData $TipoCajaMarcacionData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(TipoCajaMarcacionData $TipoCajaMarcacionData)
	{
		$key    = array(
				'id'						        => $TipoCajaMarcacionData->getId(),
		);
		$record = array(
				'id'								=> $TipoCajaMarcacionData->getId(),
				'marcacion_sec'		                => $TipoCajaMarcacionData->getMarcacionSec(),
				'tipo_caja_id'	        			=> $TipoCajaMarcacionData->getTipoCajaId(),
				'inventario_id'		                => $TipoCajaMarcacionData->getInventarioId(),
				'variedad_id'		                => $TipoCajaMarcacionData->getVariedadId(),
				'grado_id'		                	=> $TipoCajaMarcacionData->getGradoId(),
				'unds_bunch'		                => $TipoCajaMarcacionData->getUndsBunch(),
				'tipo_caja_id1'		                => $TipoCajaMarcacionData->getTipoCajaId1()
				
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $TipoCajaMarcacionData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return TipoCajaMarcacionData|null
	 */	
	public function consultar($id)
	{
		$TipoCajaMarcacionData 		    = new TipoCajaMarcacionData();

		$sql = 	' SELECT tipo_caja_marcacion.* '.
				' FROM tipo_caja_marcacion '.
				' WHERE tipo_caja_marcacion.id = :id ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$TipoCajaMarcacionData->setId											($row['id']);				
				$TipoCajaMarcacionData->setMarcacionSec 								($row['marcacion_sec']);
				$TipoCajaMarcacionData->setTipoCajaId									($row['tipo_caja_id']);
				$TipoCajaMarcacionData->setInventarioId 								($row['inventario_id']);
				$TipoCajaMarcacionData->setVariedadId 									($row['variedad_id']);
				$TipoCajaMarcacionData->setGradoId 										($row['grado_id']);
				$TipoCajaMarcacionData->setUndsBunch 									($row['unds_bunch']);
				$TipoCajaMarcacionData->setTipoCajaId1 									($row['tipo_caja_id1']);

			return $TipoCajaMarcacionData;
		}else{
			return null;
		}//end if

	}//end function consultar


	
	/**
	 * @param array $condiciones  (cliente_nombre, marcacion_nombre)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql =  " SELECT tipo_caja_marcacion.*, cliente.nombre as cliente_nombre, marcacion.cliente_id, ".
				" 	     marcacion.nombre as marcacion_nombre, variedad.nombre as variedad_nombre ".
				" FROM tipo_caja_marcacion INNER JOIN marcacion".
				"                                  ON marcacion.marcacion_sec = tipo_caja_marcacion.marcacion_sec ".
				"                          INNER JOIN cliente".
				"                                  ON cliente.id 	= marcacion.cliente_id ".
				"                          INNER JOIN variedad".
				"                                  ON variedad.id 	= tipo_caja_marcacion.variedad_id".
				" WHERE 1 = 1";
		
		if (!empty($condiciones['cliente_nombre']))
		{
			$sql = $sql." AND cliente.nombre like '%".$condiciones['cliente_nombre']."%'";
		}//end if

		if (!empty($condiciones['marcacion_nombre']))
		{
			$sql = $sql." AND marcacion.nombre like '%".$condiciones['marcacion_nombre']."%'";
		}//end if
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro

		foreach($result as &$reg)
		{
			$reg['variedad_nombre'] = trim($reg['variedad_nombre']);
		}//end foreach

		return $result;		
	}//end function listado
}//end class

?>