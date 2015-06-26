<?php

namespace Application\Classes;


class GraficoEstadistico{
	const GRAFICO_BARRA 	= 1;
	const GRAFICO_LINEAL 	= 2;
	
	
	/*-----------------------------------------------------------------------------*/
	static public function getCombo($tipo_grafico, $texto_1er_elemento = "&lt;Seleccione&gt;")
	/*-----------------------------------------------------------------------------*/
	{
		$arrData = 	array(	self::GRAFICO_BARRA		=>"Grafico Barra",
							self::GRAFICO_LINEAL	=>"Grafico Lineal"
						);
	
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $tipo_grafico, $texto_1er_elemento);
		return $opciones;
	}//end function getCombo		
}//end class GraficoEstadistico
