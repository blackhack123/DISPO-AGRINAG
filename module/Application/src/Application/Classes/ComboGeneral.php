<?php

namespace Application\Classes;


class ComboGeneral {


	/**
	 * 
	 * @param string $estado
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboEstado($estado, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('A'=>'ACTIVO','I'=>'INACTIVO');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $estado, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboEstado
	

	/**
	 *
	 * @param string $estado
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboSincronizado($estado, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array(0=>'Pendiente',1=>'Sincronizado');
	
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $estado, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboEstado
	

	
	
}//end class Combo
