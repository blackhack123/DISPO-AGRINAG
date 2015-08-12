<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\EstadosData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EstadosDAO extends Conexion 
{
	private $table_name	= 'estados';

	/**
	 * Ingresar
	 *
	 * @param EStadosData $EstadosData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(EstadosData $EstadosData)
	{
		$key    = array(
				'id'						        => $EstadosData->getId(),
		);
		$record = array(
				'id'								=> $EstadosData->getId(),
				'nombre'		                    => $EstadosData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar


	/**
	 * Modificar
	 *
	 * @param EstadosData $EstadosData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(EstadosData $EstadosData)
	{
		$key    = array(
				'id'						        => $EstadosData->getId(),
		);
		$record = array(
				'id'								=> $EstadosData->getId(),
				'nombre'		                    => $EstadosData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $EstadosData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return EstadosData|null
	 */	
	public function consultar($id)
	{
		$EstadosData 		    = new EstadosData();

		$sql = 	' SELECT estados.* '.
				' FROM estados '.
				' WHERE estados.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$EstadosData->getId			($row['id']);				
			$EstadosData->getNombre 	($row['nombre']);
			return $EstadosData;
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
		$sql = 	' SELECT estados.* '.
				' FROM estados '.
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