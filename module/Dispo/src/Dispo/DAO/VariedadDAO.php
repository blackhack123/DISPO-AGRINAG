<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\VariedadData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class VariedadDAO extends Conexion 
{
	private $table_name	= 'variedad';

	/**
	 * Ingresar
	 *
	 * @param VariedadData $VariedadData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(VariedadData $VariedadData)
	{
		$key    = array(
				'id'						        => $VariedadData->getId()
		);
		$record = array(
				'id'								=> $VariedadData->getId(),
				'nombre'		                    => $VariedadData->getNombre(),
				'colorbase'		                    => $VariedadData->getColorBase(),
				'estado'		                    => $VariedadData->getEstado(),
				'fec_ingreso'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'fec_modifica'	                    => $VariedadData->getFecModifica(),
				'usuario_ing_id'	                => $VariedadData->getUsuarioIngId(),
				'usuario_mod_id'                    => $VariedadData->getUsuarioModId(),
				'sincronizado'	                    => 0
	);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $key;
	}//end function ingresar

	/**
	 * Modificar
	 *
	 * @param VariedadData $VariedadData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(VariedadData $VariedadData)
	{
		$key    = array(
				'id'						        => $VariedadData->getId()
		);
		$record = array(
				'id'								=> $VariedadData->getId(),
				'nombre'		                    => $VariedadData->getNombre(),
				'colorbase'		                    => $VariedadData->getColorBase(),
				'estado'                			=> $VariedadData->getEstado(),
				'fec_modifica'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                    => $VariedadData->getUsuarioModId(),
				'sincronizado'	                    => 0
				//'fec_sincronizado'                  => \Application\Classes\Fecha::getFechaHoraActualServidor()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $VariedadData->getid();
	}//end function modificar


	/**
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\VariedadData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$VariedadData	    = new VariedadData();
		
				$sql = 	' SELECT variedad.* '.
						' FROM variedad '.
						' WHERE variedad.id = :id ';
						
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$VariedadData->setId							($row['id']);				
					$VariedadData->setNombre 						($row['nombre']);
					$VariedadData->setColorBase 					($row['colorbase']);
					$VariedadData->setEstado    					($row['estado']);
					$VariedadData->setFecIngreso 					($row['fec_ingreso']);
					$VariedadData->setFecModifica 					($row['fec_modifica']);
					$VariedadData->setUsuarioIngId 					($row['usuario_ing_id']);
					$VariedadData->setUsuarioModId 					($row['usuario_mod_id']);
					$VariedadData->setSincronizado 					($row['sincronizado']);
					$VariedadData->setFecSincronizado				($row['fec_sincronizado']);
					return $VariedadData;
				}else{
					return null;
				}//end if
				break;
		
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT variedad.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM variedad LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = variedad.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = variedad.usuario_mod_id '.
						' WHERE variedad.id = :id ';
		
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
		$sql = 	' SELECT variedad.* '.
				' FROM variedad ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		//return new ViewModel(array(result'=>$result));
		return $result;
	}//end function consultarTodos
	
	
	

	/**
	 *
	 * @param string $id
	 * @param string $nombre
	 * @return array
	 */
	public function consultarDuplicado($accion, $id, $nombre)
	{
		$sql = 	' SELECT variedad.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
				' FROM variedad LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = variedad.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = variedad.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
				$sql = $sql." WHERE variedad.id 	 = '".$id."'".
						"    or variedad.nombre = '".$nombre."'";
				break;
	
			case 'M':
				$sql = $sql." WHERE variedad.id 	!= '".$id."'".
						"   and variedad.nombre = '".$nombre."'";
				break;
		}//end switch
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarDuplicado
	

	
	
	public function listado($condiciones)
	{
		$sql = 	' SELECT * '.
				' FROM variedad '.
				' WHERE 1 = 1 ';
	
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or id like '%".$condiciones['criterio_busqueda']."%'".
					"      or colorbase like '%".$condiciones['criterio_busqueda']."%')";
					
		}//end if
	
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and estado = '".$condiciones['estado']."'";
		}//end if 

		
		if (isset($condiciones['sincronizado']))
		{
			if ($condiciones['sincronizado']!='')
			{
				$sql = $sql." and sincronizado = ".$condiciones['sincronizado'];
			}//end if
		}//end if
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado
	

}//end class

