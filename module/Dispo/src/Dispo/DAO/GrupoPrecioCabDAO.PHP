<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoPrecioCabData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoPrecioCabDAO extends Conexion 
{
	private $table_name	= 'grupo_precio_cab';

	/**
	 * Ingresar
	 *
	 * @param GrupoPrecioCabData $GrupoPrecioCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoPrecioCabData $GrupoPrecioCabData)
	{
		$key    = array(
				'id'						        => $GrupoPrecioCabData->getId(),
		);
		$record = array(
				'id'								=> $GrupoPrecioCabData->getId(),
				'nombre'		                    => $GrupoPrecioCabData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoPrecioCabData $GrupoPrecioCabData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoPrecioCabData $GrupoPrecioCabData)
	{
		$key    = array(
				'id'						        => $GrupoPrecioCabData->getId(),
		);
		$record = array(
				'id'		                   		=> $GrupoPrecioCabData->getId(),
				'nombre'		                    => $GrupoPrecioCabData->getNombre()
				
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GrupoPrecioCabData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return GrupoPrecioCabData|null
	 */	
/*	public function consultar($id)
	{
		$GrupoPrecioCabData 		    = new GrupoPrecioCabData();

		$sql = 	' SELECT grupo_precio_cab.* '.
				' FROM grupo_precio_cab '.
				' WHERE grupo_precio_cab.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoPrecioCabData->setId			    ($row['id']);
			$GrupoPrecioCabData->setNombre		    ($row['nombre']);
		
			return $GrupoPrecioCabData;
		}else{
			return null;
		}//end if

	}//end function consultar
*/
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\GrupoPrecioCabData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$GrupoPrecioCabData 		    = new GrupoPrecioCabData();
	
				$sql = 	' SELECT grupo_precio_cab.* '.
						' FROM grupo_precio_cab '.
						' WHERE grupo_precio_cab.id = :id ';
	
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$GrupoPrecioCabData->setId			    ($row['id']);
					$GrupoPrecioCabData->setNombre		    ($row['nombre']);
					$GrupoPrecioCabData->setCalidadId 		($row['calidad_id']);
					$GrupoPrecioCabData->setInventarioId  	($row['inventario_id']);
	
					return $GrupoPrecioCabData;
				}else{
					return null;
				}//end if
				break;
	
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT grupo_precio_cab.*, calidad.nombre as calidad_nombre, calidad.clasifica_fox as calidad_clasifica_fox  '.
						/*usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.*/
						' FROM grupo_precio_cab LEFT JOIN calidad '.
						'                              ON calidad.id = grupo_precio_cab.calidad_id '.
/*								LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = agencia_carga.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = agencia_carga.usuario_mod_id '.
*/
						' WHERE grupo_precio_cab.id = :id ';
	
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch
	
	
	}//end function consultar	
	
	

	/**
	 * consultarTodos
	 *
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT grupo_precio_cab.* '.
				' FROM grupo_precio_cab '.
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
	 * @param int $id
	 * @return array
	 */
	public function consultarArray($id)
	{
		$sql = 	' SELECT grupo_precio_cab.*, calidad.clasifica_fox '.
				' FROM grupo_precio_cab INNER JOIN calidad  '.
				'                              ON calidad.id = grupo_precio_cab.calidad_id '.
				' WHERE grupo_precio_cab.id = :id ';
	
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		return $row;
	}//end function consultarArray	
}//end class

