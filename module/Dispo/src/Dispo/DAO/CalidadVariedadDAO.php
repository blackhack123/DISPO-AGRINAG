<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\CalidadVariedadData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CalidadVariedadDAO extends Conexion 
{
	private $table_name	= 'calidad_variedad';





	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return CalidadVariedadData|null
	 */	
	public function consultar($id)
	{
		$CalidadVariedadData 		    = new CalidadVariedadData();

		$sql = 	' SELECT calidad_variedad.* '.
				' FROM calidad_variedad '.
				' WHERE calidad_variedad.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$CalidadVariedadData->setId				    ($row['id']);
			$CalidadVariedadData->setNombre		   		($row['nombre']);
		
			return $CalidadVariedadData;
		}else{
			return null;
		}//end if

	}//end function consultar


	
	/***
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