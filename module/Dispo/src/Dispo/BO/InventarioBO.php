<?php

namespace Dispo\BO;

use Doctrine\ORM\EntityManager;
use Application\Classes\Conexion;
use Dispo\DAO\InventarioDAO;
use Dispo\DAO\Dispo\DAO;



class InventarioBO extends Conexion
{

	/**
	 * 
	 * @param string $inventario_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($inventario_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$InventarioDAO = new InventarioDAO();
		
		$InventarioDAO->setEntityManager($this->getEntityManager());

		$result = $InventarioDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$inventario_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


	
	/**
	 *
	 * @return string
	 */
	function getComboDataGrid()
	{
		$InventarioDAO = new InventarioDAO();
	
		$InventarioDAO->setEntityManager($this->getEntityManager());
	
		$result = $InventarioDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'id', null, null, null, \Application\Classes\Combo::$tipo_combo_datagrid);
		return $opciones;
	}//end function getComboDataGrid
	
	
	/**
	 * Consultar 
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\InventarioData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$InventarioDAO = new InventarioDAO();
		$InventarioDAO->setEntityManager($this->getEntityManager());
		$reg = $InventarioDAO->consultar($id);
		return $reg;		
	}//end function consultar
	
	
}//end class
