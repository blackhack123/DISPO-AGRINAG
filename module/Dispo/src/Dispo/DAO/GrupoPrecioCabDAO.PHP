<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoPrecioCabData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoPrecioCabDAO extends Conexion 
{
	private $table_name	= 'grupo_precio_cab';

	/**
	 * Ingresar
	 *
	 * @param GrupoPrecioCabData $GrupoPrecioCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoPrecioCabData $GrupoPrecioCabData)
	{
		$key    = array(
				'id'						        => $GrupoPrecioCabData->getId(),
		);
		$record = array(
				'id'								=> $GrupoPrecioCabData->getId(),
				'nombre'		                    => $GrupoPrecioCabData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoPrecioCabData $GrupoPrecioCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoPrecioCabData $GrupoPrecioCabData)
	{
		$key    = array(
				'id'						        => $GrupoPrecioCabData->getId(),
		);
		$record = array(
				'id'		                   		=> $GrupoPrecioCabData->getId(),
				'nombre'		                    => $GrupoPrecioCabData->getNombre()
				
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GrupoPrecioCabData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return GrupoPrecioCabData|null
	 */	
	public function consultar($id)
	{
		$GrupoPrecioCabData 		    = new GrupoPrecioCabData();

		$sql = 	' SELECT grupo_precio_cab.* '.
				' FROM grupo_precio_cab '.
				' WHERE grupo_precio_cab.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoPrecioCabData->setId			    ($row['id']);
			$GrupoPrecioCabData->setNombre		    ($row['nombre']);
		
			return $GrupoPrecioCabData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

