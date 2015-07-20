<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\VariedadDAO;


class ColoresBO extends Conexion
{

	/**
	 * 
	 * @param string $colorbase
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodos($colorbase, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$VariedadDAO = new VariedadDAO();
		
		$VariedadDAO->setEntityManager($this->getEntityManager());

		$result = $VariedadDAO->consultarColores();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'color', 'nombre', $colorbase, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboTodos


}//end class
