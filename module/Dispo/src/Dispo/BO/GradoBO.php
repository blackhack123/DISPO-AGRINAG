<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\GradoDAO;
use Dispo\DAO\ProductoDAO;
use Dispo\DAO\ObtentorDAO;
use Dispo\Data\VariedadData;
use Dispo\DAO\Dispo\DAO;


class GradoBO extends Conexion
{
	
	/**
	 *
	 * @param string $grado_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($grado_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$GradoDAO = new GradoDAO();
	
		$GradoDAO->setEntityManager($this->getEntityManager());
	
		$result = $GradoDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'id',$grado_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getCombo	
	
	
	/**
	 *
	 * @return string
	 */
	function getComboDataGrid()
	{
		$GradoDAO = new GradoDAO();
	
		$GradoDAO->setEntityManager($this->getEntityManager());
	
		$result = $GradoDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'id', null, null, null, \Application\Classes\Combo::$tipo_combo_datagrid);
		return $opciones;
	}//end function getComboDataGrid	
	
}//end class
