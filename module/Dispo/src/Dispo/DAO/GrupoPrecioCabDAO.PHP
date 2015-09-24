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
		/*$key    = array(
				'id'						        => $GrupoPrecioCabData->getId(),
		);*/
		$record = array(
				'nombre'		                    => $GrupoPrecioCabData->getNombre(),
				'inventario_id'						=> $GrupoPrecioCabData->getInventarioId(),
				'calidad_id'						=> $GrupoPrecioCabData->getCalidadId(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'                	=> $GrupoPrecioCabData->getUsuarioIngId(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $GrupoPrecioCabData->getUsuarioIngId(),
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$id = $this->getEntityManager()->getConnection()->lastInsertId();
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
				'nombre'		                    => $GrupoPrecioCabData->getNombre(),
				'inventario_id'						=> $GrupoPrecioCabData->getInventarioId(),
				'calidad_id'						=> $GrupoPrecioCabData->getCalidadId(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $GrupoPrecioCabData->getUsuarioModId(),
				
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
	
	
	
	
	public function listadoGrupoPrecioNoAsignadas()
	{
		$sql = 	' SELECT usuario.id, cliente.nombre, cliente.estado, usuario.nombre as usuario_nombre  '.
				' FROM   cliente INNER JOIN usuario '.
				'                       ON usuario.cliente_id	= cliente.id'.
				' WHERE usuario.grupo_precio_cab_id IS NULL '.
				"                        AND cliente.estado = 'A'";
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listadoNoAsignadas
	
	
	
	/**
	 *
	 * @param array $condiciones (grupo_dispo_cab_id);
	 * @return array
	 */
	public function listadoAsignadas($condiciones)
	{
		$sql = 	' SELECT usuario.id, cliente.nombre, cliente.estado, usuario.nombre as usuario_nombre '.
				' FROM   cliente INNER JOIN usuario '.
				'                        ON usuario.cliente_id	= cliente.id '.
				' WHERE 1 = 1';
	
		if (!empty($condiciones['grupo_precio_cab_id']))
		{
			$sql = $sql.' and usuario.grupo_precio_cab_id = '.$condiciones['grupo_precio_cab_id'];
		}//end if
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listadoAsignadas
	
	
	
	/**
	 *
	 * @param string $inventario_id
	 * @param int $calidad_id
	 * @return array
	 */
	function consultarPorInventario($inventario_id, $calidad_id)
	{
		$sql = 	" SELECT  grupo_precio_cab.id, grupo_precio_cab.nombre ".
				" FROM grupo_precio_cab ".
				" WHERE grupo_precio_cab.inventario_id = :inventario_id".
				" AND   grupo_precio_cab.calidad_id  = :calidad_id";
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue('inventario_id', $inventario_id);
		$stmt->bindValue('calidad_id', $calidad_id);
		$stmt->execute();
	
		//$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;
	}//end function function consultarPorCliente
	
}//end class

