<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\CalidadData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CalidadDAO extends Conexion 
{
	private $table_name	= 'calidad';

	/**
	 * Ingresar
	 *
	 * @param CalidadData $CalidadData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(CalidadData $CalidadData)
	{
		$key    = array(
				'id'						        => $CalidadData->getId(),
		);
		$record = array(
				'id'								=> $CalidadData->getId(),
				'nombre'		                    => $CalidadData->getNombre(),
				'nivel'		            			=> $CalidadData->getNivel()

		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param CalidadData $CalidadData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(CalidadData $CalidadData)
	{
		$key    = array(
				'id'						        => $CalidadData->getId(),
		);
		$record = array(
				'id'								=> $CalidadData->getId(),
				'nombre'		                    => $CalidadData->getNombre(),
				'nivel'		            			=> $CalidadData->getNivel()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $AgenciaCargaData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return CalidadData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new CalidadData();

		$sql = 	' SELECT calidad.* '.
				' FROM calidad '.
				' WHERE calidad.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$CalidadData->setId				    ($row['id']);
			$CalidadData->setNombre		   		($row['nombre']);
			$CalidadData->setNivel			    ($row['nivel']);
		
			return $CalidadData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

?>