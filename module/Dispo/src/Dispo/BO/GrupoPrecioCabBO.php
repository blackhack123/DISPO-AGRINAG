<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\GrupoPrecioCabDAO;


class GrupoPrecioCabBO extends Conexion
{

	/**
	 * 
	 * @param string $grupoprecio
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboGrupoPrecio($grupoprecio, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());

		$result = $GrupoPrecioCabDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $grupoprecio, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


}//end class
