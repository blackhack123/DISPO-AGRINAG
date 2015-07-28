<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\PaisData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaisDAO extends Conexion 
{
	private $table_name	= 'Pais';

	/**
	 * Ingresar
	 *
	 * @param PaisData $PaisData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(PaisData $PaisData)
	{
		$key    = array(
				'id'						        => $PaisData->getId(),
		);
		$record = array(
				'id'								=> $PaisData->getId(),
				'nombre'		                    => $PaisData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param PaisData $PaisData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(PaisData $PaisData)
	{
		$key    = array(
				'id'						        => $PaisData->getId(),
		);
		$record = array(
				'id'								=> $PaisData->getId(),
				'nombre'		                    => $PaisData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $PaisData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return PaisData|null
	 */	
	public function consultar($id)
	{
		$PaisData 		    = new PaisData();

		$sql = 	' SELECT pais.* '.
				' FROM pais '.
				' WHERE pais.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$PaisData->setid						($row['id']);				
				$PaisData->setNombre 					($row['nombre']);
			return $PaisData;
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
		$sql = 	' SELECT pais.* '.
				' FROM pais '.
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