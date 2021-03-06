<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\ClienteAgenciaCargaDAO ;
use Dispo\Data\ClienteAgenciaCargaData;


class ClienteAgenciaCargaBO extends Conexion
{


	function listadoNoAsignadas($condiciones)
	{
		$ClienteAgenciaCargaDAO = new ClienteAgenciaCargaDAO();
		$ClienteAgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$result = $ClienteAgenciaCargaDAO->listadoNoAsignadas($condiciones);
		return $result;		
	}//end function listadoNoAsignadas
	
	
	
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
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function grabar
	
	
	function eliminar($ArrClienteAgenciaCargaData)
	{
		$ClienteAgenciaCargaDAO = new ClienteAgenciaCargaDAO();
		$ClienteAgenciaCargaDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrClienteAgenciaCargaData as $ClienteAgenciaCargaData)
			{
				$ClienteAgenciaCargaDAO->eliminar($ClienteAgenciaCargaData);
			}//end foreach
				
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function eliminar
	

	
	/**
	 *
	 * @param string $cliente_id
	 * @param int $agencia_carga_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboAgencia($cliente_id, $agencia_carga_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$ClienteAgenciaCargaDAO = new ClienteAgenciaCargaDAO();
	
		$ClienteAgenciaCargaDAO->setEntityManager($this->getEntityManager());
	
		$result = $ClienteAgenciaCargaDAO->consultarPorCliente($cliente_id);
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $agencia_carga_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboActivos	
	
}//end class
