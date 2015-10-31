<?php

namespace Dispo\BO;

use Doctrine\ORM\EntityManager;
use Application\Classes\Conexion;
use Dispo\DAO\ProveedorDAO;


class ProveedorBO extends Conexion
{

	/**
	 * 
	 * @param string $proveedor_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($proveedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA", $buscar_proveedor_id = null)
	{	
		$ProveedorDAO = new ProveedorDAO();
		
		$ProveedorDAO->setEntityManager($this->getEntityManager());

		$result = $ProveedorDAO->consultarTodos($buscar_proveedor_id);
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$proveedor_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo

	
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
		$ProveedorDAO = new ProveedorDAO();
		$ProveedorDAO->setEntityManager($this->getEntityManager());
		$result = $ProveedorDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\ProveedorData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$ProveedorDAO = new ProveedorDAO();
		$ProveedorDAO->setEntityManager($this->getEntityManager());
		$reg = $ProveedorDAO->consultar($id);
		return $reg;
	}//end function consultar
	
}//end class
