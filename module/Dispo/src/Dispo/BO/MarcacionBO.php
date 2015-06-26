<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\MarcacionDAO;


class MarcacionBO extends Conexion
{


	/**
	 * 
	 * @param string $cliente_id
	 * @param int $marcacion_sec
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorClienteId($cliente_id, $marcacion_sec, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$MarcacionDAO = new MarcacionDAO();
		
		$MarcacionDAO->setEntityManager($this->getEntityManager());

		$result = $MarcacionDAO->consultarPorClienteId($cliente_id);
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'marcacion_sec', 'nombre', $marcacion_sec, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function listado


	
	/**
	 * 
	 * @param int $marcacion_sec
	 * @return Ambigous <\Dispo\Data\MarcacionData, NULL>
	 */
	function consultar($marcacion_sec){
		$MarcacionDAO = new MarcacionDAO();
		$MarcacionDAO->setEntityManager($this->getEntityManager());
		$MarcacionData = $MarcacionDAO->consultar($marcacion_sec);
		return $MarcacionData;		
	}//end function consultar
}//end class PaisBO
