<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PerfilData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PerfilDAO extends Conexion 
{
	private $table_name	= 'perfil';

	/**
	 * Ingresar
	 *
	 * @param PerfilData $PerfilData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PerfilData $PerfilData)
	{
		$key    = array(
				'id'						        => $PerfilData->getId(),
		);
		$record = array(
				'id'								=> $PerfilData->getid(),
				'nombre'		                    => $PerfilData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param PerfilData $PerfilData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PerfilData $PerfilData)
	{
		$key    = array(
				'id'						        => $PerfilData->getId(),
		);
		$record = array(
				'id'								=> $PerfilData->getId(),
				'nombre'		                    => $PerfilData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PerfilData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return PerfilData|null
	 */	
	public function consultar($id)
	{
		$PerfilData 		    = new PerfilData();

		$sql = 	' SELECT Perfil.* '.
				' FROM perfil '.
				' WHERE perfil.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$PerfilData->setId						($row['id']);				
				$PerfilData->setNombre 					($row['nombre']);
			return $PerfilData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

?>