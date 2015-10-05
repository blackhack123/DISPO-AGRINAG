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
	 * @param string $formato_estado_cta
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboFormatoEnvio($formato_estado_cta, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('P'=>'PDF','E'=>'EXCEL');
	
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $formato_estado_cta, $texto_1er_elemento);
			
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
	
	
	
	/**
	 *
	 * @param string $tipo
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboTipo($tipo, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('N'=>'NUMERICO','T'=>'TEXTO');
	
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $tipo, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboEstado
	
	
}//end class Combo
