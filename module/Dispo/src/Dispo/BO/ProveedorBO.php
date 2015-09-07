<?php

namespace Dispo\BO;

use Doctrine\ORM\EntityManager;
use Application\Classes\Conexion;
use Dispo\DAO\ProveedorDAO;



class ProveedorBO extends Conexion
{

	/**
	 * 
	 * @param string $proveedor_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($proveedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ProveedorDAO = new ProveedorDAO();
		
		$ProveedorDAO->setEntityManager($this->getEntityManager());

		$result = $ProveedorDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$proveedor_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


}//end class
