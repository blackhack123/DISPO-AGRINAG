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
	
	
	
	/**
	 *
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado
	 *
	 * @param array $condiciones
	 * @return array
	 */
	public function listado($condiciones)
	{
	
		$sql = 	' SELECT calidad_variedad.* '.
				' FROM calidad_variedad   '.
				' WHERE 1 = 1 ';
	
	
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and estado = '".$condiciones['estado']."'";
		}//end if
	
		$sql=$sql." order by calidad_variedad.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado
	
	
}//end class

?>