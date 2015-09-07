<?php

namespace Dispo\BO;

use Doctrine\ORM\EntityManager;
use Application\Classes\Conexion;
use Dispo\DAO\CalidadDAO;



class CalidadBO extends Conexion
{

	/**
	 * 
	 * @param string $calidad
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboCalidad($calidad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$CalidadDAO = new CalidadDAO();
		
		$CalidadDAO->setEntityManager($this->getEntityManager());

		$result = $CalidadDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$calidad_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboCalidad

	
	/**
	 *
	 * @param string $calidad
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboCalidadFox($clasifica_fox, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$CalidadDAO = new CalidadDAO();
	
		$CalidadDAO->setEntityManager($this->getEntityManager());
	
		$result = $CalidadDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'clasifica_fox', 'nombre',$clasifica_fox, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboCalidadFox	

}//end class
