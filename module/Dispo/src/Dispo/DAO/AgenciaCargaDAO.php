<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\AgenciaCargaData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AgenciaCargaDAO extends Conexion 
{
	private $table_name	= 'agencia_carga';

	/**
	 * Ingresar
	 *
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(AgenciaCargaData $AgenciaCargaData)
	{
		$key    = array(
				'id'						        => $AgenciaCargaData->getId(),
		);
		$record = array(
				'id'								=> $AgenciaCargaData->getId(),
				'nombre'		                    => $AgenciaCargaData->getNombre(),
				'direccion'		            		=> $AgenciaCargaData->getDireccion(),
				'telefono'                			=> $AgenciaCargaData->getTelefono(),
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(AgenciaCargaData $AgenciaCargaData)
	{
		$key    = array(
				'id'						        => $AgenciaCargaData->getId(),
		);
		$record = array(
				'nombre'		                    => $AgenciaCargaData->getNombre(),
				'direccion'		            		=> $AgenciaCargaData->getDireccion(),
				'telefono'                			=> $AgenciaCargaData->getTelefono(),
				//'fecha_mod'							=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $AgenciaCargaData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return AgenciaCargaData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new AgenciaCargaData();

		$sql = 	' SELECT agencia_carga.* '.
				' FROM agencia_carga '.
				' WHERE agencia_carga.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$AgenciaCargaData->setId			    ($row['id']);
			$AgenciaCargaData->setNombre		    ($row['nombre']);
			$AgenciaCargaData->setDireccion		    ($row['direccion']);
			$AgenciaCargaData->setTelefono    		($row['telefono']);
		
			return $AgenciaCargaData;
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
		$sql = 	' SELECT agencia_carga.* '.
				' FROM agencia_carga ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarTodos

}//end class

