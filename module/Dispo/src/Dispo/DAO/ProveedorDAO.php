<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ProveedorData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProveedorDAO extends Conexion 
{
	private $table_name	= 'proveedor';

	/**
	 * Ingresar
	 *
	 * @param ProveedorData $ProveedorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(ProveedorData $ProveedorData)
	{
		$key    = array(
				'id'						        => $ProveedorData->getId(),
		);
		$record = array(
				'id'								=> $ProveedorData->getId(),
				'nombre'		                    => $ProveedorData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param ProveedorData $ProveedorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(ProveedorData $ProveedorData)
	{
		$key    = array(
				'id'						        => $ProveedorData->getId(),
		);
		$record = array(
				'id'								=> $ProveedorData->getId(),
				'nombre'		                    => $ProveedorData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ProveedorData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return ProveedorData|null
	 */	
	public function consultar($id)
	{
		$ProveedorData 		    = new ProveedorData();

		$sql = 	' SELECT proveedor.* '.
				' FROM proveedor '.
				' WHERE proveedor.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$ProveedorData->setId						($row['id']);				
				$ProveedorData->setNombre 					($row['nombre']);
			return $ProveedorData;
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
		$sql = 	' SELECT proveedor.* '.
				' FROM proveedor ';
	
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
	
		$sql = 	' SELECT proveedor.* '.
				' FROM proveedor   '.
				' WHERE 1 = 1 ';
	
	
	
		$sql=$sql." order by proveedor.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado
	
}//end class

?>