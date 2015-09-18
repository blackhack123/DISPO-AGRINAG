<?php

namespace Dispo\BO;

use Doctrine\ORM\EntityManager;
use Application\Classes\Conexion;
use Dispo\DAO\CalidadVariedadDAO;



class CalidadVariedadBO extends Conexion
{

	/**
	 * 
	 * @param unknown $calidad_variedad_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboCalidadVariedad($calidad_variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$CalidadVariedadDAO = new CalidadVariedadDAO();
		
		$CalidadVariedadDAO->setEntityManager($this->getEntityManager());

		$result = $CalidadVariedadDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$calidad_variedad_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboCalidad



}//end class
