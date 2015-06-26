<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\InventarioData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class InventarioDAO extends Conexion 
{
	private $table_name	= 'inventario';

	/**
	 * Ingresar
	 *
	 * @param InventarioData $InventarioData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(InventarioData $InventarioData)
	{
		$key    = array(
				'id'						        => $InventarioData->getId(),
		);
		$record = array(
				'nombre'		                    => $InventarioData->getNombre()

		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param InventarioData $InventarioData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(InventarioData $InventarioData)
	{
		$key    = array(
				'id'						        => $InventarioData->getId(),
		);
		$record = array(
				'nombre'		                    => $InventarioData->getNombre()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $AgenciaCargaData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return InventarioData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new InventarioData();

		$sql = 	' SELECT inventario.* '.
				' FROM inventario '.
				' WHERE inventario.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$InventarioData->setId				    ($row['id']);
			$InventarioData->setNombre		   		($row['nombre']);
			return $InventarioData;
		}else{
			return null;
		}//end if

	}//end function consultar

}//end class

?>