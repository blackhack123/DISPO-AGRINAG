<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\ColoresDAO;


class ColoresBO extends Conexion
{

	/**
	 * 
	 * @param string $colorbase
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($color, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ColoresDAO = new ColoresDAO();
		
		$ColoresDAO->setEntityManager($this->getEntityManager());

		$result = $ColoresDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'color', 'nombre', $color, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


}//end class
