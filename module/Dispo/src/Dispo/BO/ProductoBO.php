<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\ProductoDAO;


class ProductoBO extends Conexion
{

	/**
	 * 
	 * @param string $id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboProductoId($id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ProductoDAO = new ProductoDAO();
		
		$ProductoDAO->setEntityManager($this->getEntityManager());

		$result = $ProductoDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


}//end class
