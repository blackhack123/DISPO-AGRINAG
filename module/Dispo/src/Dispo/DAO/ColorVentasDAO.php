<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ColorVentasData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ColorVentasDAO extends Conexion 
{
	private $table_name	= 'color_ventas';


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return ColorVentasData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new ColoresData();

		$sql = 	' SELECT color_ventas.* '.
				' FROM color_ventas '.
				' WHERE color_ventas.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$ColoresData->getId		($row['id']);				
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
		$sql = 	' SELECT color_ventas.* '.
				' FROM color_ventas '.
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