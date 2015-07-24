<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ObtentorData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ObtentorDAO extends Conexion 
{
	private $table_name	= 'Obtentor';

	/**
	 * Ingresar
	 *
	 * @param ObtentorData $ObtentorData
	 * @return array Retorna un Array $id el cual contiene el id
	 */
	public function ingresar(ObtentorData $ObtentorData)
	{
		$id   = array(
				'id'						        => $ObtentorData->getid(),
		);
		$record = array(
				'id'								=> $ObtentorData->getId(),
				'nombre'		                    => $ObtentorData->getNombre(),
				'rep_obtentor_id'		            => $ObtentorData->getRepObtentorId(),
				'estado'                			=> $AgenciaCargaData->getEstado(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'fec_modifica'                		=> $AgenciaCargaData->getFecModifica(),
				'usuario_ing_id'                	=> $AgenciaCargaData->getUsuarioIngId(),
				'usuario_mod_id'                	=> $AgenciaCargaData->getUsuarioIngId(),
				'sincronizado'                		=> 0
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param ObtentorData $ObtentorData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(ObtentorData $ObtentorData)
	{
		$key    = array(
				'id'						        => $ObtentorData->getId(),
		);
		$record = array(
				'id'								=> $ObtentorData->getId(),
				'nombre'		                    => $ObtentorData->getNombre(),
				'rep_obtentor_id'		            => $ObtentorData->getRepObtentorId(),
				'estado'                			=> $AgenciaCargaData->getEstado(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $AgenciaCargaData->getUsuarioModId(),
				'sincronizado'       	         	=> 0
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ObtentorData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return ObtentorData|null
	 */	
	public function consultar($id)
	{
		$ObtentorData 		    = new ObtentorData();

		$sql = 	' SELECT Obtentor.* '.
				' FROM Obtentor '.
				' WHERE Obtentor.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$ObtentorData->getId		($row['id']);				
			$ObtentorData->getNombre 	($row['nombre']);
			return $ObtentorData;
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
		$sql = 	' SELECT obtentor.* '.
				' FROM obtentor '.
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
}//end class

?>