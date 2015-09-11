<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ClienteAgenciaCargaData;
use Zend\View\Model\JsonModel;
use Dispo\BO\ClienteAgenciaCargaBO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ClienteAgenciaCargaDAO extends Conexion 
{
	private $table_name	= 'cliente_agencia_carga';

	/**
	 * Ingresar
	 *
	 * @param ClienteAgenciaCargaData $ClienteAgenciaCargaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(ClienteAgenciaCargaData $ClienteAgenciaCargaData)
	{
		$key    = array(
				'cliente_id'						        => $ClienteAgenciaCargaData->getClienteId(),
		);
		$record = array(
				'agencia_carga_id'		            => $ClienteAgenciaCargaData->getAgenciaCargaId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $key;
	}//end function ingresar

	
	
	/**
	 * Modificar
	 *
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function eliminar(ClienteAgenciaCargaData $ClienteAgenciaCargaData)
	{
		$key    = array(
				'cliente_id'						=> $ClienteAgenciaCargaData->getId(),
		);
		$record = array(
				'agencia_carga_id'            		=> $ClienteAgenciaCargaData->getAgenciaCargaId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ClienteAgenciaCargaData->getClienteId();
	}//end function modificar



	
	
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
		$sql = 	' SELECT agencia_carga.id, agencia_carga.nombre, agencia_carga.tipo  '.
				' FROM cliente_agencia_carga INNER JOIN agencia_carga '.
				'								     ON agencia_carga.id 		= cliente_agencia_carga.agencia_carga_id '.
				" WHERE cliente_agencia_carga.cliente_id = '".$condiciones['cliente_id']."'";
		
		
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado
	
	
	
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
	public function listadoactivos($condiciones)
	{
		$sql = 	' SELECT * '.
				' FROM agencia_carga '.
				' WHERE 1 = 1 '.
				' and estado= "A"';
	
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or id like '%".$condiciones['criterio_busqueda']."%'".
					"      or direccion like '%".$condiciones['criterio_busqueda']."%'".
					"      or telefono like '%".$condiciones['criterio_busqueda']."%')";
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

