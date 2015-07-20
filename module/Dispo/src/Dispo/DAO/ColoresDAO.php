<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ColoresData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ColoresDAO extends Conexion 
{
	private $table_name	= 'colores';

	/**
	 * Ingresar
	 *
	 * @param ColoresData $ColoresData
	 * @return array Retorna un Array $key el cual contiene el color
	 */
	public function ingresar(ColoresData $ColoresData)
	{
		$key    = array(
				'color'						        => $ColoresData->getColor(),
		);
		$record = array(
				'color'								=> $ColoresData->getColor(),
				'nombre'		                    => $ColoresData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$color = $this->getEntityManager()->getConnection()->lastInsertcolor();
		return $color;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param ColoresData $ColoresData
	 * @return array Retorna un Array $key el cual contiene el color
	 */
	public function modificar(ColoresData $ColoresData)
	{
		$key    = array(
				'color'						        => $ColoresData->getcolor(),
		);
		$record = array(
				'color'								=> $ColoresData->getcolor(),
				'nombre'		                    => $ColoresData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ColoresData->getColor();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $color
	 * @return ColoresData|null
	 */	
	public function consultar($color)
	{
		$AgenciaCargaData 		    = new ColoresData();

		$sql = 	' SELECT colores.* '.
				' FROM colores '.
				' WHERE colores.color = :color ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':color',$color);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$ColoresData->getcolor		($row['color']);				
			$ColoresData->getNombre 	($row['nombre']);
			return $ColoresData;
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
		$sql = 	' SELECT colores.* '.
				' FROM colores '.
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