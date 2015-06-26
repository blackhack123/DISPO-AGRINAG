<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ClienteData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ClienteDAO extends Conexion 
{
	private $table_name	= 'cliente';

	/**
	 * Ingresar
	 *
	 * @param ClienteData $ClienteData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(ClienteData $ClienteData)
	{
		$key    = array(
				'id'						        => $ClienteData->getId(),
		);
		$record = array(
				'id'								=> $ClienteData->getId(),
				'nombre'		                    => $ClienteData->getNombre(),
				'direccion'		            		=> $ClienteData->getDireccion(),
				'pais_id'		            		=> $ClienteData->getPaisId(),
				'ciudad'		            		=> $ClienteData->getCiudad(),
				'telefono1'		            		=> $ClienteData->getTelefono1(),
				'telefono2'		            		=> $ClienteData->getTelefono2(),
				'fax1'		            			=> $ClienteData->getFax1(),
				'fax2'		            			=> $ClienteData->getFax2(),
				'email'		            			=> $ClienteData->getEmail(),
				'grupo'		            			=> $ClienteData->getGrupo(),
				'grupo_precio_cab_id'		        => $ClienteData->getGrupoPrecioCabId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param ClienteData $ClienteData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(ClienteData $ClienteData)
	{
		$key    = array(
				'id'						        => $ClienteData->getId(),
		);
		$record = array(
				'id'								=> $ClienteData->getId(),
				'nombre'		                    => $ClienteData->getNombre(),
				'direccion'		            		=> $ClienteData->getDireccion(),
				'pais_id'		            		=> $ClienteData->getPaisId(),
				'ciudad'		            		=> $ClienteData->getCiudad(),
				'telefono1'		            		=> $ClienteData->getTelefono1(),
				'telefono2'		            		=> $ClienteData->getTelefono2(),
				'fax1'		            			=> $ClienteData->getFax1(),
				'fax2'		            			=> $ClienteData->getFax2(),
				'email'		            			=> $ClienteData->getEmail(),
				'grupo'		            			=> $ClienteData->getGrupo(),
				'grupo_precio_cab_id'		        => $ClienteData->getGrupoPrecioCabId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ClienteData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return ClienteData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new ClienteData();

		$sql = 	' SELECT cliente.* '.
				' FROM cliente '.
				' WHERE cliente.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$ClienteData->getId						($row['id']);				
				$ClienteData->getNombre 				($row['nombre']);
				$ClienteData->getDireccion				($row['direccion']);
				$ClienteData->getPaisId					($row['pais_id']);
				$ClienteData->getCiudad					($row['ciudad']);
				$ClienteData->getTelefono1				($row['telefono1']);
				$ClienteData->getTelefono2				($row['telefono2']);
				$ClienteData->getFax1					($row['fax1']);
	      	    $ClienteData->getFax2					($row['fax2']);
		        $ClienteData->getEmail					($row['email']);
		        $ClienteData->getGrupo					($row['grupo']);
				$ClienteData->getGrupoPrecioCabId		($row['grupo_precio_cab_id']);
			return $ClienteData;
		}else{
			return null;
		}//end if

	}//end function consultar



}//end class

?>