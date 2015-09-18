<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\CalidadData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CalidadVariedadDAO extends Conexion 
{
	private $table_name	= 'calidad';





	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return CalidadData|null
	 */	
	public function consultar($id)
	{
		$CalidadData 		    = new CalidadData();

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
		
			return $CalidadData;
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
		$sql = 	' SELECT calidad_variedad.* '.
				' FROM calidad_variedad ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		//return new ViewModel(array(result'=>$result));
		return $result;
	}//end function consultarTodos
	
}//end class

?>