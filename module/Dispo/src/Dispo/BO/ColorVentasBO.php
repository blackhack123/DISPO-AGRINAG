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


}//end class
