<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoDispoCabDataData;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dispo\Data\GrupoDispoCabData;

class GrupoDispoCabDAO extends Conexion 
{
	private $table_name	= 'grupo_dispo_cab';

	/**
	 * Ingresar
	 *
	 * @param GrupoDispoCabDataData $GrupoDispoCabDataData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoDispoCabDataData $GrupoDispoCabDataData)
	{
		$key    = array(
				'id'						        => $GrupoDispoCabDataData->getId(),
		);
		$record = array(
				'id'								=> $GrupoDispoCabDataData->getId(),
				'nombre'		                    => $GrupoDispoCabDataData->getNombre(),
				'inventario_id'		            	=> $GrupoDispoCabDataData->getInventarioId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoDispoCabDataData $GrupoDispoCabDataData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoDispoCabDataData $GrupoDispoCabDataData)
	{
		$key    = array(
				'id'						        => $GrupoDispoCabDataData->getId(),
		);
		$record = array(
				'id'								=> $GrupoDispoCabDataData->getId(),
				'nombre'		                    => $GrupoDispoCabDataData->getNombre(),
				'inventario_id'		            	=> $GrupoDispoCabDataData->getInventarioId()

		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GrupoDispoCabDataData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return GrupoDispoCabDataData|null
	 */	
	public function consultar($id)
	{
		$GrupoDispoCabDataData 		    = new GrupoDispoCabDataData();

		$sql = 	' SELECT grupo_dispo_cab.* '.
				' FROM grupo_dispo_cab '.
				' WHERE grupo_dispo_cab.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoDispoCabDataData->setId			    ($row['id']);
			$GrupoDispoCabDataData->setNombre		    ($row['nombre']);
			$GrupoDispoCabDataData->setInventarioId		($row['inventario_id']);
		
			return $GrupoDispoCabDataData;
		}else{
			return null;
		}//end if

	}//end function consultar

	
	
	/**
	 * 
	 * @param int $usuario_id
	 * @return array
	 */
	public function consultarPorUsuarioId($usuario_id)
	{
		$sql = 	' SELECT usuario.grupo_dispo_cab_id, grupo_dispo_cab.inventario_id, grupo_dispo_cab.nombre as grupo_dispo_cab_nombre '.
				' FROM usuario INNER JOIN grupo_dispo_cab '.
				'                      ON grupo_dispo_cab.id   			= usuario.grupo_dispo_cab_id'.
				' WHERE usuario.id 		= :usuario_id';
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':usuario_id',$usuario_id);;
		$stmt->execute();
		$row = $stmt->fetch();		
		if($row){
			$GrupoDispoCabDataData = new GrupoDispoCabData();
			
			$GrupoDispoCabDataData->setId			    ($row['grupo_dispo_cab_id']);
			$GrupoDispoCabDataData->setNombre		    ($row['grupo_dispo_cab_nombre']);
			$GrupoDispoCabDataData->setInventarioId		($row['inventario_id']);
		
			return $GrupoDispoCabDataData;
		}else{
			return null;
		}//end if
		return $row;
	}//end function consultarPorUsuarioId

}//end class

?>