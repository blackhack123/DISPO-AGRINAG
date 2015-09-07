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
	 * @param int $id
	 * @return array
	 */
	public function consultarArray($id)
	{
		$sql = 	' SELECT grupo_dispo_cab.*, calidad.clasifica_fox '.
				' FROM grupo_dispo_cab INNER JOIN calidad  '.
				'                              ON calidad.id = grupo_dispo_cab.calidad_id '.
				' WHERE grupo_dispo_cab.id = :id ';
	
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		return $row;
	}//end function consultarArray	
	
	
	
	
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

	
	
	/**
	 * consultarTodos
	 *
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT grupo_dispo_cab.* '.
				' FROM grupo_dispo_cab '.
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
	 * @param array $condiciones (grupo_dispo_cab_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT variedad.nombre as variedad, grupo_dispo_det.variedad_id, '.
				" 		 SUM(if(grupo_dispo_det.grado_id=40,  grupo_dispo_det.cantidad_bunch_disponible, 0)) as '40',".
				" 		 SUM(if(grupo_dispo_det.grado_id=50,  grupo_dispo_det.cantidad_bunch_disponible, 0)) as '50',".
				" 		 SUM(if(grupo_dispo_det.grado_id=60,  grupo_dispo_det.cantidad_bunch_disponible, 0)) as '60',".
				" 		 SUM(if(grupo_dispo_det.grado_id=70,  grupo_dispo_det.cantidad_bunch_disponible, 0)) as '70',".
				" 		 SUM(if(grupo_dispo_det.grado_id=80,  grupo_dispo_det.cantidad_bunch_disponible, 0)) as '80',".
				" 		 SUM(if(grupo_dispo_det.grado_id=90,  grupo_dispo_det.cantidad_bunch_disponible, 0)) as '90',".
				" 		 SUM(if(grupo_dispo_det.grado_id=100, grupo_dispo_det.cantidad_bunch_disponible, 0)) as '100',".
				" 		 SUM(if(grupo_dispo_det.grado_id=110, grupo_dispo_det.cantidad_bunch_disponible, 0)) as '110'".
				' FROM grupo_dispo_det INNER JOIN variedad '.
				'		                      ON variedad.id = grupo_dispo_det.variedad_id '. 
				' WHERE grupo_dispo_det.grupo_dispo_cab_id = '.$condiciones['grupo_dispo_cab_id'].
				' GROUP BY variedad.nombre, variedad.id'.
				" ORDER BY variedad.nombre ";

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		foreach($result as &$reg)
		{
			$reg['variedad'] = trim($reg['variedad']);
		}
		
		return $result;
	}//end function listado
	
}//end class

?>