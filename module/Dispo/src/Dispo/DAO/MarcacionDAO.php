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
	 * @return array Retorna un Array $marcacion_sec el cual contiene el marcacion_sec
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
				'zip'		                  		=> $MarcacionData->getZip(),
				'estado'                			=> $MarcacionData->getEstado(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $MarcacionData->getUsuarioModId()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $MarcacionData->getMarcacionSec();
	}//end function modificar


/**
	 * Consultar
	 * 
	 * @param string $marcacion_sec
	 * @param int $resultType
	 * @return \Dispo\Data\MarcacionData|NULL|array
	 */
	public function consultarmarcacion($marcacion_sec, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$MarcacionData 		    = new MarcacionData ();
				
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
					$MarcacionData->setEstado    			($row['estado']);
					$MarcacionData->setFecIngreso   		($row['fec_ingreso']);
					$MarcacionData->setFecModifica  		($row['fec_modifica']);
					$MarcacionData->setUsuarioIngId			($row['usuario_ing_id']);
					$MarcacionData->setUsuarioModId			($row['usuario_mod_id']);
					$MarcacionData->setSinronizado			($row['sincronizado']);
					$MarcacionData->setFecSincronizado		($row['fec_sincronizado']);
						
					return $MarcacionData;
				}else{
					return null;
				}//end if				
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT marcacion.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM marcacion LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = marcacion.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = marcacion.usuario_mod_id '.						
						' WHERE marcacion.marcacion_sec = :marcacion_sec ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':marcacion_sec',$marcacion_sec);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch


	}//end function consultar
	

	/**
	 *
	 * @param int $marcacion_sec
	 * @param string $nombre
	 * @return array
	 */
	public function consultarDuplicado($accion, $marcacion_sec, $nombre)
	{
		$sql = 	' SELECT marcacion.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
				' FROM marcacion LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = marcacion.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = marcacion.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
				$sql = $sql." WHERE marcacion.marcacion_sec 	 = '".$marcacion_sec."'".
						"    or marcacion.nombre = '".$nombre."'";
				break;
	
			case 'M':
				$sql = $sql." WHERE marcacion.marcacion_sec 	!= '".$marcacion_sec."'".
						"   and marcacion.nombre = '".$nombre."'";
				break;
		}//end switch
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarDuplicado
	
	
	
	
	
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
	
	
	
	/**
	 * 
	 * @param array $condiciones (cliente_id, nombre, estado)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT marcacion.*, pais.nombre as pais_nombre '.
				' FROM marcacion LEFT JOIN pais '.
				'		                ON pais.id      = marcacion.pais_id '.
				' WHERE 1 = 1 ';

		if (!empty($condiciones['cliente_id']))
		{
			$sql = $sql." and marcacion.cliente_id = '".$condiciones['cliente_id']."'";
		}//end if
				
		if (!empty($condiciones['nombre']))
		{
			$sql = $sql." and marcacion.nombre like '%".$condiciones['nombre']."%'";
		}//end if		
		
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and marcacion.estado = '".$condiciones['estado']."'";
		}//end if
		
		
		if (isset($condiciones['sincronizado']))
		{
			if ($condiciones['sincronizado']!='')
			{
				$sql = $sql." and marcacion.sincronizado = ".$condiciones['sincronizado'];
			}//end if
		}//end if
		$sql=$sql." order by marcacion.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		return $result;		
	}//end function listado
	
}//end class

?>