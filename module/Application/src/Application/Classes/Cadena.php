<?php
namespace Application\Classes;


class Cadena {
	
	
	static function rellenar_con_espacios($largo,$entero=NULL)
	{
		$salida=null;
		if($entero)
		{
			if(strlen($entero)===$largo)
				return $entero;
	
			$entero=$this->quitarAcentos($entero);
			$espacios=$largo-(strlen($entero));
		}
		else
			$espacios=$largo;
	
		for($i = 1; $i <= $espacios; $i++)
			$salida .= " ";
	
		$cadena=$entero.$salida;
	
		return $cadena;
	}//end function rellenar_con_espacios
	
	

	static function quitarAcentos($text)
	{
		$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
		$text = strtolower($text);
		$patron = array (
				// Espacios, puntos y comas por guion
				'/[\., ]+/' => ' ',
	
				// Vocales
				'/&agrave;/' => 'a',
				'/&egrave;/' => 'e',
				'/&igrave;/' => 'i',
				'/&ograve;/' => 'o',
				'/&ugrave;/' => 'u',
	
				'/&aacute;/' => 'a',
				'/&eacute;/' => 'e',
				'/&iacute;/' => 'i',
				'/&oacute;/' => 'o',
				'/&uacute;/' => 'u',
	
				'/&acirc;/' => 'a',
				'/&ecirc;/' => 'e',
				'/&icirc;/' => 'i',
				'/&ocirc;/' => 'o',
				'/&ucirc;/' => 'u',
	
				'/&atilde;/' => 'a',
				'/&etilde;/' => 'e',
				'/&itilde;/' => 'i',
				'/&otilde;/' => 'o',
				'/&utilde;/' => 'u',
	
				'/&auml;/' => 'a',
				'/&euml;/' => 'e',
				'/&iuml;/' => 'i',
				'/&ouml;/' => 'o',
				'/&uuml;/' => 'u',
	
				'/&auml;/' => 'a',
				'/&euml;/' => 'e',
				'/&iuml;/' => 'i',
				'/&ouml;/' => 'o',
				'/&uuml;/' => 'u',
	
				// Otras letras y caracteres especiales
				'/&aring;/' => 'a',
				'/&ntilde;/' => 'n',
	
				// Agregar aqui mas caracteres si es necesario
	
		);
	
		$text = preg_replace(array_keys($patron),array_values($patron),$text);
		return strtoupper($text);
	}//end function quitarAcentos
	
}///end class
