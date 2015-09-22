<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\TamanoBunchDAO;


class TamanoBunchBO extends Conexion
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
		$TamanoBunchDAO = new TamanoBunchDAO();
		
		$TamanoBunchDAO->setEntityManager($this->getEntityManager());

		$result = $TamanoBunchDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


}//end class
