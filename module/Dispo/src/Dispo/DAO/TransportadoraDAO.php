<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\TransportadoraData;
use Zend\View\Model\JsonModel;
use Dispo\BO\TransportadoraBO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TransportadoraDAO extends Conexion 
{
	private $table_name	= 'transportadora';

	/**
	 * Ingresar
	 *
	 * @param TransportadoraData $TransportadoraData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(TransportadoraData $TransportadoraData)
	{
		$key    = array(
				'id'						        => $TransportadoraData->getId(),
		);
		$record = array(
				'id'								=> $TransportadoraData->getId(),
				'nombre'		                    => $TransportadoraData->getNombre(),
				'tipo'                				=> $TransportadoraData->getTipo(),
				'estado'                			=> $TransportadoraData->getEstado(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'fec_modifica'                		=> $TransportadoraData->getFecModifica(),
				'usuario_ing_id'                	=> $TransportadoraData->getUsuarioIngId(),
				'usuario_mod_id'                	=> $TransportadoraData->getUsuarioIngId(),
				'sincronizado'                		=> 0
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $key;
	}//end function ingresar

	
	
	/**
	 * Modificar
	 *
	 * @param TransportadoraData $TransportadoraData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(TransportadoraData $TransportadoraData)
	{
		$key    = array(
				'id'						        => $TransportadoraData->getId(),
		);
		$record = array(
				'nombre'		                    => $TransportadoraData->getNombre(),
				'tipo'                				=> $TransportadoraData->getTipo(),
				'estado'                			=> $TransportadoraData->getEstado(),
				'fec_ingreso'                		=> $TransportadoraData->getFecIngreso(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'                	=> $TransportadoraData->getUsuarioIngId(),
				'usuario_mod_id'                	=> $TransportadoraData->getUsuarioModId(),
				'sincronizado'       	         	=> 0
				//'fecha_mod'							=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $TransportadoraData->getId();
	}//end function modificar



	/**
	 * Consultar
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\TransportadoraData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$TransportadoraData 		    = new TransportadoraData();
				
				$sql = 	' SELECT transportadora.* '.
						' FROM transportadora '.
						' WHERE transportadora.id = :id ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$TransportadoraData->setId			    ($row['id']);
					$TransportadoraData->setNombre		    ($row['nombre']);
					$TransportadoraData->setTipo			($row['tipo']);
					$TransportadoraData->setEstado    		($row['estado']);
					$TransportadoraData->setFecIngreso   	($row['fec_ingreso']);
					$TransportadoraData->setFecModifica  	($row['fec_modifica']);
					$TransportadoraData->setUsuarioIngId	($row['usuario_ing_id']);
					$TransportadoraData->setUsuarioModId	($row['usuario_mod_id']);
					$TransportadoraData->setSinronizado		($row['sincronizado']);
					$TransportadoraData->setFecSincronizado	($row['fec_sincronizado']);
				
					return $TransportadoraData;
				}else{
					return null;
				}//end if				
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT transportadora.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM transportadora LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = transportadora.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = transportadora.usuario_mod_id '.						
						' WHERE transportadora.id = :id ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch


	}//end function consultar

	
	
	/**
	 *
	 * @param string $id
	 * @param string $nombre
	 * @return array
	 */
	public function consultarDuplicado($accion, $id, $nombre)
	{
		$sql = 	' SELECT transportadora.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
				' FROM transportadora LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = transportadora.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = transportadora.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
					$sql = $sql." WHERE transportadora.id 	 = '".$id."'".
					            "    or transportadora.nombre = '".$nombre."'";
				break;
				
			case 'M':
				$sql = $sql." WHERE transportadora.id 	!= '".$id."'".
						    "   and transportadora.nombre = '".$nombre."'";
				break;
		}//end switch
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarDuplicado


	/**
	 * consultarTodos
	 * 
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT transportadora.* '.
				' FROM transportadora ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarTodos

	
	
	/**
	 * 
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado 
	 * 
	 * @param array $condiciones   
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT * '.
				' FROM transportadora '.
				' WHERE 1 = 1 ';
		
		if (!empty($condiciones['criterio_busqueda']))
		{
					$sql = $sql." and (nombre like '%".$condiciones['criterio_busqueda']."%'".
						"      or id like '%".$condiciones['criterio_busqueda']."%')";						
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

?>
