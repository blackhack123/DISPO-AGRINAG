<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoColorData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoColorDAO extends Conexion 
{
	private $table_name	= 'grupo_color';

	/**
	 * Ingresar
	 *
	 * @param GrupoColorData $GrupoColorData
	 * @return array Retorna un Array $id el cual contiene el id
	 */
	public function ingresar(idData $idData)
	{
		$id    = array(
				'id'						        => $GrupoColorData->getid(),
		);
		$record = array(
				'id'								=> $GrupoColorData->getid(),
				'nombre'		                    => $GrupoColorData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param idData $idData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoColorData $GrupoColorData)
	{
		$key    = array(
				'id'						        => $GrupoColorData->getId(),
		);
		$record = array(
				'id'								=> $GrupoColorData->getId(),
				'nombre'		                    => $GrupoColorData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $idData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return idData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new idData();

		$sql = 	' SELECT grupo_color.* '.
				' FROM grupo_color '.
				' WHERE grupo_color.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$GrupoColorData->getid		($row['id']);				
			$GrupoColorData->getNombre 	($row['nombre']);
			return $GrupoColorData;
		}else{
			return null;
		}//end if

	}//end function consultar


	/**
	 * consultarTodos
	 *
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT grupo_color.* '.
				' FROM grupo_color '.
				' ORDER BY nombre ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		//Elimina los espacios
		foreach($result as &$reg)
		{
			$reg['nombre'] = trim($reg['nombre']);
		}//end foreach
	
		return $result;
	}//end function consultarTodos	
}//end class

?>