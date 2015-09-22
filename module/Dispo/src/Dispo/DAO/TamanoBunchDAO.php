<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\TamanoBunchData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TamanoBunchDAO extends Conexion 
{
	private $table_name	= 'tamano_bunch';




	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return ColoresData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new ColoresData();

		$sql = 	' SELECT tamano_bunch.* '.
				' FROM tamano_bunch '.
				' WHERE tamano_bunch.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$TamanoBuchData->getId			($row['id']);				
			$TamanoBuchData->getNombre 		($row['nombre']);
			return $TamanoBuchData;
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
		$sql = 	' SELECT tamano_bunch.* '.
				' FROM tamano_bunch '.
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