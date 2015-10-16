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
		$ColorVentasData 		    = new ColorVentasData();

		$sql = 	' SELECT color_ventas.* '.
				' FROM color_ventas '.
				' WHERE color_ventas.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$ColorVentasData->setId			($row['id']);				
			$ColorVentasData->setNombre 	($row['nombre']);
			return $ColorVentasData;
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
		
		$sql = 	' SELECT color_ventas.* '.
				' FROM color_ventas   '.
				' WHERE 1 = 1 ';
		
		
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and estado = '".$condiciones['estado']."'";
		}//end if
		
		$sql=$sql." order by color_ventas.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		return $result;
	}//end function listado
	
	
}//end class

?>