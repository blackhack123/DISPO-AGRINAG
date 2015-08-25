<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\RepObtentorData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RepObtentorDAO extends Conexion 
{
	private $table_name	= 'rep_obtentor';

	/**
	 * Ingresar
	 *
	 * @param RepObtentorData $RepObtentorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(RepObtentorData $RepObtentorData)
	{
		$key    = array(
				'id'						        => $RepObtentorData->getId()
		);
		$record = array(
				'id'								=> $RepObtentorData->getId(),
				'nombre'		                    => $RepObtentorData->getNombre(),
				'estado'		                    => $RepObtentorData->getEstado(),
				'fec_ingreso'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'	                => $RepObtentorData->getUsuarioIngId(),
				'sincronizado'	                    => 0
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $key;
	}//end function ingresar

	/**
	 * Modificar
	 *
	 * @param RepObtentorData $RepObtentorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(RepObtentorData $RepObtentorData)
	{
		$key    = array(
				'id'						        => $RepObtentorData->getId()
		);
		$record = array(
				'nombre'		                    => $RepObtentorData->getNombre(),
				'estado'		                    => $RepObtentorData->getEstado(),
				'fec_modifica'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                    => $RepObtentorData->getUsuarioModId(),
				'sincronizado'	                    => 0				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $RepObtentorData->getId();
	}//end function modificar


	/**
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\RepObtentorData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$RepObtentorData	    = new RepObtentorData();
		
				$sql = 	' SELECT rep_obtentor.* '.
						' FROM rep_obtentor '.
						' WHERE rep_obtentor.id = :id ';
						
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$RepObtentorData->setId								($row['id']);				
					$RepObtentorData->setNombre 						($row['nombre']);
					$RepObtentorData->setEstado    						($row['estado']);
					$RepObtentorData->setFecIngreso 					($row['fec_ingreso']);
					$RepObtentorData->setFecModifica 					($row['fec_modifica']);
					$RepObtentorData->setUsuarioIngId 					($row['usuario_ing_id']);
					$RepObtentorData->setUsuarioModId 					($row['usuario_mod_id']);
					$RepObtentorData->setSincronizado 					($row['sincronizado']);
					$RepObtentorData->setFecSincronizado				($row['fec_sincronizado']);
					return $RepObtentorData;
				}else{
					return null;
				}//end if
				break;

			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT rep_obtentor.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM rep_obtentor LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = rep_obtentor.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = rep_obtentor.usuario_mod_id '.
						' WHERE rep_obtentor.id = :id ';
		
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch
		
	}//end function consultar

		

}//end class

