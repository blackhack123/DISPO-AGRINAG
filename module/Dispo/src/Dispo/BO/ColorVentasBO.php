<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\ColorVentasDAO;


class ColorVentasBO extends Conexion
{

	/**
	 * 
	 * @param string $id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ColorVentasDAO = new ColorVentasDAO();
		
		$ColorVentasDAO->setEntityManager($this->getEntityManager());

		$result = $ColorVentasDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $id, $texto_1er_elemento, $color_1er_elemento);
		 
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
		$ColorVentasDAO = new ColorVentasDAO();
		$ColorVentasDAO->setEntityManager($this->getEntityManager());
		$result = $ColorVentasDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	

	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\ColorVentasData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$ColorVentasDAO = new ColorVentasDAO();
		$ColorVentasDAO->setEntityManager($this->getEntityManager());
		$reg = $ColorVentasDAO->consultar($id);
		return $reg;
	}//end function consultar

}//end class
