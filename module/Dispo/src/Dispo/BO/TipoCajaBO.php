<?php

namespace Dispo\BO;

use Doctrine\ORM\EntityManager;
use Application\Classes\Conexion;
use Dispo\DAO\TipoCajaDAO;



class TipoCajaBO extends Conexion
{

	/**
	 * 
	 * @param string $tipo_caja_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($tipo_caja_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$TipoCajaDAO = new TipoCajaDAO();

		$TipoCajaDAO->setEntityManager($this->getEntityManager());

		$result = $TipoCajaDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$tipo_caja_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


	
	/**
	 *
	 * @return string
	 */
	function getComboDataGrid()
	{
		$TipoCajaDAO = new TipoCajaDAO();
	
		$TipoCajaDAO->setEntityManager($this->getEntityManager());
	
		$result = $TipoCajaDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', null, null, null, \Application\Classes\Combo::$tipo_combo_datagrid);
		return $opciones;
	}//end function getComboDataGrid	
	
}//end class
