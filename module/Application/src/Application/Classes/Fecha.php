<?php

namespace Application\Classes;


class Fecha {
    public static $formatoFecha = null;
    public static $arr_mes_corto = array(
    		1 => 'ENE',
    		2 => 'FEB',
    		3 => 'MAR',
    		4 => 'ABR',
    		5 => 'MAY',
    		6 => 'JUN',
    		7 => 'JUL',
    		8 => 'AGO',
    		9 => 'SEP',
    		10 => 'OCT',
    		11 => 'NOV',
    		12 => 'DIC'
 	);
    		
    public static $arr_mes_completo = array(
    		1 => 'ENERO',
    		2 => 'FEBRERO',
    		3 => 'MARZO',
    		4 => 'ABRIL',
    		5 => 'MAYO',
    		6 => 'JUNIO',
    		7 => 'JULIO',
    		8 => 'AGOSTO',
    		9 => 'SEPTIEMBRE',
    		10 => 'OCTUBRE',
    		11 => 'NOVIEMBRE',
    		12 => 'DICIEMBRE'
    );
    
    
    const IMPRESION_FECHA_LARGA = 1;
    const IMPRESION_FECHA_CORTA = 2;
    
    public static function getArrMesCorto()
    {
    	return self::$arr_mes_corto;
    }

	public static function getArrMesCompleto()
	{
		return self::$arr_mes_completo;
	}
    
    
	/*-----------------------------------------------------------------------------*/		
	public static function setFormato($valor)
	/*-----------------------------------------------------------------------------*/			
	{
		self::$formatoFecha = $valor;
	}
	
	
	
	/*-----------------------------------------------------------------------------*/			
	public static function getFormato()
	/*-----------------------------------------------------------------------------*/			
	{
		return self::$formatoFecha; 
	}

	/*-----------------------------------------------------------------------------*/	
	public static function getFechaFormato($fecha, $formato)
	/*-----------------------------------------------------------------------------*/		
	{
        if (empty($formato)){
            return ''; //'Formato invalido';
        }
 
 		//var_dump($fecha);echo("<br>");
		if (empty($fecha)){
            return '';
        }

		$dato = $fecha instanceof \DateTime;
		if ($fecha instanceof \DateTime){  //Pregunta si es tipo DateTime
			$fecha_new = $fecha;
		}else{  						   //Caso contrario es String y hay que convertilo en clase DateTime
			$fecha_new = new \DateTime($fecha);
		}//end if
		
		$dato = $fecha_new->format($formato);
		return $dato;
	}//end function


	/*-----------------------------------------------------------------------------*/		
	public static function SepararDMY($fecha)
	/*-----------------------------------------------------------------------------*/		
	{
		if (empty($fecha)){
            return '';
        }

		$dato = $fecha instanceof \DateTime;
		if ($fecha instanceof \DateTime){  //Pregunta si es tipo DateTime
			$fecha_new = $fecha;
		}else{  						   //Caso contrario es String y hay que convertilo en clase DateTime
			$fecha_new = new \DateTime($fecha);
		}//end if

		$dia = $fecha_new->format('d');
		$mes = $fecha_new->format('m');
		$anio = $fecha_new->format('Y');				
		
		return array('dia'=>$dia, 'mes'=>$mes, 'anio'=>$anio);
	}//end function


	/*-----------------------------------------------------------------------------*/			
	public static function getFechaActualServidor()
	/*-----------------------------------------------------------------------------*/			
	{
		//$formato = self::getFormato()['corta']['servidor'];   //REVISAR HAY FALLO
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['corta']['servidor'];
		$now = new \Datetime("now");
		$fecha = \Application\Classes\Fecha::getFechaFormato($now, $formato);
	
		return self::getFechaFormato($now, $formato); 
	}//end function getFechaActualServidor


	/*-----------------------------------------------------------------------------*/			
	public static function getFechaHoraActualServidor()
	/*-----------------------------------------------------------------------------*/			
	{		
		//$formato = self::getFormato()['larga']['servidor'];
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['larga']['servidor'];
		$now = new \Datetime("now");
		$fecha = \Application\Classes\Fecha::getFechaFormato($now, $formato);
	
		return self::getFechaFormato($now, $formato); 
	}//end function getFechaHoraActualServidor



	/*-----------------------------------------------------------------------------*/			
	public static function getFechaActualFrontEnd()
	/*-----------------------------------------------------------------------------*/			
	{		
		//$formato = self::getFormato()['corta']['frontend'];
		$arr_fecha_data = self::getFormato();	
		$formato = $arr_fecha_data['corta']['frontend'];
		$now = new \Datetime("now");
		$fecha = \Application\Classes\Fecha::getFechaFormato($now, $formato);

		return self::getFechaFormato($now, $formato); 
	}//end function getFechaActualFrontEnd


	/*-----------------------------------------------------------------------------*/			
	public static function getFechaHoraActualFrontEnd()
	/*-----------------------------------------------------------------------------*/			
	{
		//$formato = self::getFormato()['larga']['frontend'];
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['larga']['frontend'];
		
		$now = new \Datetime("now");
		$fecha = \Application\Classes\Fecha::getFechaFormato($now, $formato);
	
		return self::getFechaFormato($now, $formato); 
	}//end function getNowLargo


	/*-----------------------------------------------------------------------------*/			
	/**
	 * Retorna Fecha con el formato del servidor 
	 *
	 * @param string|Date $fecha
	 * @return string 
	 */	
	public static function convertirFechaPHPToFechaServidor($fecha)
	/*-----------------------------------------------------------------------------*/				
	{
		//$formato = self::getFormato()['corta']['servidor'];
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['corta']['servidor'];
		
		$fecha_new = self::getFechaFormato($fecha, $formato);
		return $fecha_new; 
	}//end function 

	
	
	/*-----------------------------------------------------------------------------*/
	/**
	 * Retorna Fecha Larga (Fecha y Hora) con el formato del servidor
	 *
	 * @param string|Date $fecha
	 * @return string
	 */
	public static function convertirFechaLargaPHPToFechaServidor($fecha)
	/*-----------------------------------------------------------------------------*/
	{
		//$formato = self::getFormato()['corta']['servidor'];
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['larga']['servidor'];
	
		$fecha_new = self::getFechaFormato($fecha, $formato);
		return $fecha_new;
	}//end function convertirFechaLargaPHPToFechaServidor

	

	/*-----------------------------------------------------------------------------*/			
	/**
	 * Retorna Fecha con el formato del servidor 
	 *
	 * @param string|Date $fecha
	 * @return string 
	 */	
	public static function convertirFechaServidorToFechaFrontEnd($fecha)
	/*-----------------------------------------------------------------------------*/				
	{
		//$formato = self::getFormato()['corta']['frontend'];
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['corta']['frontend'];
		
		$fecha_new = self::getFechaFormato($fecha, $formato);
		return $fecha_new; 
	}//end function 

	
	
	/*-----------------------------------------------------------------------------*/
	/**
	 * Retorna Fecha Larga (Fecha y Hora) con el formato del servidor
	 *
	 * @param string|Date $fecha
	 * @return string
	 */
	public static function convertirFechaLargaServidorToFechaFrontEnd($fecha)
	/*-----------------------------------------------------------------------------*/
	{
		//$formato = self::getFormato()['corta']['frontend'];
		$arr_fecha_data = self::getFormato();
		$formato = $arr_fecha_data['larga']['frontend'];
	
		$fecha_new = self::getFechaFormato($fecha, $formato);
		return $fecha_new;
	}//end function convertirFechaLargaServidorToFechaFrontEnd
	
	
	
	/*-----------------------------------------------------------------------------*/			
	/**
	 * Retorna Fecha con el formato del servidor 
	 *
	 * @param string|Date $fecha
	 * @return string 
	 */	
	public static function convertirFechaServidorToFechaFrontEnd_MesTexto($fecha)
	/*-----------------------------------------------------------------------------*/				
	{
		//Aqui falta reconocer en que posicion se encuentra el mes para realizar el reemplazo
		//por el momento se asumir� que el fronend siempre ser� con el formato d/m//yy
		$arr = self::SepararDMY($fecha);
		$dato = $arr['dia'].'/'.self::$arr_mes_corto[intval($arr['mes'])].'/'.$arr['anio'];
		unset($mes_corto, $arr);
		return $dato;
	}//end function convertirFechaServidorToFechaFrontEnd_MesTexto


	
	/**
	 * Obtiene la edad de acuerdo a la fecha de nacimiento
	 * 
	 * @param string $fec_nacimiento
	 * @return number
	 */
	public static function obtenerEdad($fecha_base, $fec_nacimiento)
	{
		//fecha actual
		$fecha_base = new \DateTime($fecha_base);
		$dia	= $fecha_base->format('d');
		$mes	= $fecha_base->format('m');
		$ano	= $fecha_base->format('Y');
		
		//fecha de nacimiento
		$fec_nacimiento = new \DateTime($fec_nacimiento);
		$dianaz	= $fec_nacimiento->format('d');
		$mesnaz	= $fec_nacimiento->format('m');
		$anonaz	= $fec_nacimiento->format('Y');
		
		//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual
		if (($mesnaz == $mes) && ($dianaz > $dia)) {
			$ano=($ano-1); 
		}

		//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual		
		if ($mesnaz > $mes) {
			$ano=($ano-1);
		}
		
		//ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
		$edad=($ano-$anonaz);
		
		return $edad;
	}//end function obtenerEdad
	
	
	
	/**
	 * Esta funcion fue creada para que coexista 
	 * @param unknown $EntityManager
	 * @return string
	 */
	public static function getAutoFormatoFechaDatabase($EntityManager)
	{
		if ($EntityManager->getConnection()->getHost()=='PCHMERO') //ESTOY EN LA BASE DE DATOS DE COSTOS
		{
			$formato_fecha = self::getFechaHoraActualServidor();
		}else{
			$formato_fecha = self::getFechaHoraActualFrontEnd();
		}//end if
		return $formato_fecha;
	}//end public function getAutoFormatoFechaDatabase()

	
	
	/**
	 * Obtiene de la fecha la parte de la hora
	 *
	 * @return String|Date
	 */
	public static function convertirFechaToHora($fecha)
	
	{
		//$formato = self::getFormato()['corta']['servidor'];
		$formato = 'H:i:s';
	
		$fecha_new = self::getFechaFormato($fecha, $formato);
		return $fecha_new;
	}//end function convertirFechaToHora	
	

	/**
	 * 
	 * @param string $fecha
	 * @param number $tipo_salida_fecha
	 * @return string
	 */
	public static function getFechaImpresion($fecha, $tipo_salida_fecha = IMPRESION_FECHA_LARGA)
	{
		if (empty($fecha))
		{
			return '';
		}//end if
		
		$fecha_new = new \DateTime($fecha);
		
		$mes 		= $fecha_new->format("M");
		$dia_numero = $fecha_new->format("j");
		$dia_texto  = $fecha_new->format("l");
		$anio 		= $fecha_new->format("Y");
		$hora 		= $fecha_new->format("H");
		$minuto 	= $fecha_new->format("i");
		
		switch($tipo_salida_fecha)
		{
			case 1: //Fecha Larga
				$texto = $mes.', '.$dia_numero.' '.$anio.', '.$dia_texto.', '.$hora.':'.$minuto;
				break;
			
			case 2:
				$texto = null; //Por implementar
				break;
				
			case 3:  //USO FUTURO
				break;
		}//end switch
		return $texto;
	}//end function getFechaLargaImpresion
	
}//end class Fecha
