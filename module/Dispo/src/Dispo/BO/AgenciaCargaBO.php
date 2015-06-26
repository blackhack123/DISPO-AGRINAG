<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\AgenciaCargaDAO;


class AgenciaCargaBO extends Conexion
{

	/**
	 * 
	 * @param int $agencia_carga_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodos($agencia_carga_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());

		$result = $AgenciaCargaDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $agencia_carga_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboTodos


}//end class
