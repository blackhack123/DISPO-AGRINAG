<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\MarcacionData;
use Dispo\Data\ClienteData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MarcacionDAO extends Conexion 
{
	private $table_name	= 'marcacion';

	/**
	 * Ingresar
	 *
	 * @param MarcacionData $MarcacionData
	 * @return array Retorna un Array $key el cual contiene el marcacion_sec
	 */
	public function ingresar(MarcacionData $MarcacionData)
	{
		//$key    = array(
		//		'marcacion_sec'						=> $MarcacionData->getMarcacionSec(),
		//);
		$record = array(
				'cliente_id'		                => $MarcacionData->getClienteId(),
				'nombre'		                    => $MarcacionData->getNombre(),
				'direccion'		                    => $MarcacionData->getDireccion(),
				'ciudad'		                    => $MarcacionData->getCiudad(),
				'pais_id'		                    => $MarcacionData->getPaisId(),
				'contacto'		                    => $MarcacionData->getContacto(),
				'telefono'		                    => $MarcacionData->getTelefono(),
				'zip'		                  		=> $MarcacionData->getZip(),
				'estado'                			=> $MarcacionData->getEstado(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'                	=> $MarcacionData->getUsuarioIngId(),
				'sincronizado'                		=> 0

		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$marcacion_sec= $this->getEntityManager()->getConnection()->lastInsertId();
		return $marcacion_sec;
	}//end function ingresar

 
	/**
	 * Modificar
	 *
	 * @param MarcacionData $MarcacionData
	 * @return array Retorna un Array $key el cual contiene el marcacion_sec
	 */
	public function modificar(MarcacionData $MarcacionData)
	{
		$key    = array(
				'marcacion_sec'						        => $MarcacionData->getMarcacionSec(),
		);
		$record = array(
				'marcacion_sec'		                => $MarcacionData->getMarcacionSec(),
				'cliente_id'		                => $MarcacionData->getClienteId(),
				'nombre'		                    => $MarcacionData->getNombre(),
				'direccion'		                    => $MarcacionData->getDireccion(),
				'ciudad'		                    => $MarcacionData->getCiudad(),
				'pais_id'		                    => $MarcacionData->getPaisId(),
				'contacto'		                    => $MarcacionData->getContacto(),
				'telefono'		                    => $MarcacionData->getTelefono(),
				'zip'		                  		=> $MarcacionData->getZip()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $MarcacionData->getmarcacion_sec();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $marcacion_sec
	 * @return MarcacionData|null
	 */	
	public function consultar($marcacion_sec)
	{
		$MarcacionData 		    = new MarcacionData();

		$sql = 	' SELECT marcacion.* '.
				' FROM marcacion '.
				' WHERE marcacion.marcacion_sec = :marcacion_sec ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':marcacion_sec',$marcacion_sec);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$MarcacionData->setMarcacionsec   		($row['marcacion_sec']);
			$MarcacionData->setClienteId			($row['cliente_id']);
			$MarcacionData->setNombre	   			($row['nombre']);
			$MarcacionData->setDireccion		   	($row['direccion']);
			$MarcacionData->setCiudad		   		($row['ciudad']);
			$MarcacionData->setPaisId		   		($row['pais_id']);
			$MarcacionData->setContacto		   		($row['contacto']);
			$MarcacionData->setTelefono		   		($row['telefono']);
			$MarcacionData->setZip		   			($row['zip']);
			return $MarcacionData;
		}else{
			return null;
		}//end if
	}//end function consultar

	
	
	
	/**
	 * consultarPorClienteId
	 * 
	 * @param int $cliente_id
	 * @return array
	 */
	public function consultarPorClienteId($cliente_id)
	{
		$sql = 	' SELECT marcacion.* '.
				' FROM marcacion '.
				" WHERE marcacion.cliente_id = :cliente_id ";


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':cliente_id',$cliente_id);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		return $result;
	}//end function consultarPorClienteId
	
}//end class

?>