<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\ClienteDAO;


class ClienteBO extends Conexion
{


	/**
	 * 
	 * @param string $cliente_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($cliente_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ClienteDAO = new ClienteDAO();
		
		$ClienteDAO->setEntityManager($this->getEntityManager());

		//$result = $ClienteDAO->consultarTodo();
		$result = $ClienteDAO->consultarUsuarioAsignado();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $cliente_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function listado

}//end class MarcacionBO
