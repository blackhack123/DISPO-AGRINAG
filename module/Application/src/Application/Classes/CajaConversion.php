<?php
namespace Application\Classes;


class CajaConversion {
	
	
	
	/**
	 * 
	 * @param string $tipo_caja
	 * @param int $nro_caja
	 * @return number
	 */
	static function equivalenciaFB($tipo_caja, $nro_caja)
	{
		$factor = 0;
		switch($tipo_caja)
		{
			case 'FB':
				$factor = 1;
				break;
				
			case 'HB':
				$factor = 0.5;
				break;
					
			case 'HJ':
				$factor = 0.5;  //Confirmado por Jorge
				break; 
				
			case 'OB':
				$factor = 0.125;
				break;
				
			case 'QB':
				$factor = 0.25;
				break;
		}//end switch

		$nro_cajas_fb = $nro_caja * $factor;
		
		return $nro_cajas_fb;
	}//end function rellenar_con_espacios
	
		
}///end class
