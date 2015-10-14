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
		$CalidadVariedadDAO = new CalidadVariedadDAO();
		$CalidadVariedadDAO->setEntityManager($this->getEntityManager());
		$result = $CalidadVariedadDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	

	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\CalidadVariedadData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$CalidadVariedadDAO = new CalidadVariedadDAO();
		$CalidadVariedadDAO->setEntityManager($this->getEntityManager());
		$reg = $CalidadVariedadDAO->consultar($id);
		return $reg;
	}//end function consultar
	
	

}//end class
