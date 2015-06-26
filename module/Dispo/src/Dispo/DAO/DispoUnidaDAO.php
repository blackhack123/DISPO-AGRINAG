<?php

namespace DispoUnida\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\DispoUnidaData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DispoUnidaDAO extends Conexion 
{
	private $table_name	= 'dispo_unida';

	/**
	 * Ingresar
	 *
	 * @param DispoUnidaData $DispoUnidaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(DispoUnidaData $DispoUnidaData)
	{
		$key    = array(
				'id'						        => $DispoUnidaData->getId(),
		);
		$record = array(
				'variedad_id'							=> $DispoUnidaData->getVariedadId(),
				'grado_id'		                  		=> $DispoUnidaData->getGradoId(),
				'Totalgrados'		            		=> $DispoUnidaData->getTotalGrados(),
				'HBTotal'		            			=> $DispoUnidaData->getHBTotal(),
				'HB'		            				=> $DispoUnidaData->getHB(),	
				'HBRest'		            			=> $DispoUnidaData->getHBRest()	

		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param DispoUnidaData $DispoUnidaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(DispoUnidaData $DispoUnidaData)
	{
		$key    = array(
				'id'						        	=> $DispoUnidaData->getId(),
		);
		$record = array(
				'variedad_id'							=> $DispoUnidaData->getVariedadId(),
				'grado_id'		                  		=> $DispoUnidaData->getGradoId(),
				'Totalgrados'		            		=> $DispoUnidaData->getTotalGrados(),
				'HBTotal'		            			=> $DispoUnidaData->getHBTotal(),
				'HB'		            				=> $DispoUnidaData->getHB(),	
				'HBRest'		            			=> $DispoUnidaData->getHBRest()	

		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $DispoUnidaData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return DispoUnidaData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new DispoUnidaData();

		$sql = 	' SELECT dispo_unida.* '.
				' FROM dispo__unida '.
				' WHERE dispo_unida.id = :id ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$DispoUnidaData->getVariedadId							($row['variedad_id']);				
				$DispoUnidaData->getGradoId 							($row['grado_id']);
				$DispoUnidaData->getTotalGrados							($row['Totalgrados']);
				$DispoUnidaData->getHBTotal								($row['HBTotal']);
				$DispoUnidaData->getHB									($row['HB']);
				$DispoUnidaData->getHBRest								($row['HBRest']);
			return $DispoUnidaData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

?>`