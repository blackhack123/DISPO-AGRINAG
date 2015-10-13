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
	function getCombo($proveedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ProveedorDAO = new ProveedorDAO();
		
		$ProveedorDAO->setEntityManager($this->getEntityManager());

		$result = $ProveedorDAO->consultarTodos();
		
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
	
}//end class
