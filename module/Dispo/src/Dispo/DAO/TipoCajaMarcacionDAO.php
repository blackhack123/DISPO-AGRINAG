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


}//end class

?>