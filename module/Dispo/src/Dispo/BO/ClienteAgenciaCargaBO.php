<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\ClienteAgenciaCargaDAO ;
use Dispo\Data\ClienteAgenciaCargaData;


class ClienteAgenciaCargaBO extends Conexion
{


	
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
	function listado($condiciones)
	{
		$ClienteAgenciaCargaDAO = new ClienteAgenciaCargaDAO();
		$ClienteAgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$result = $ClienteAgenciaCargaDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	
	/**
	 * Consultar 
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\AgenciaCargaData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$reg = $AgenciaCargaDAO->consultar($id, $resultType);
		return $reg;		
	}//end function consultar
	
	
	
	function grabar($ArrClienteAgenciaCargaData)
	{
		$ClienteAgenciaCargaDAO = new ClienteAgenciaCargaDAO();
		$ClienteAgenciaCargaDAO->setEntityManager($this->getEntityManager());
		
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrClienteAgenciaCargaData as $ClienteAgenciaCargaData)
			{
				$ClienteAgenciaCargaDAO->ingresar($ClienteAgenciaCargaData);
			}//end foreach
			
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function grabar
	
}//end class
