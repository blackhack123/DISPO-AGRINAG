<?php
namespace Application\Classes;


class SepararApellidos {

	public function  SEPARARAPELLIDOS_1eraParte($rng) {
		$nombreArr = array() ;
		$nuevaCadena='' ;
		$i=0 ;
		//'Dvidir el nombre por palabras en un arreglo
		//$nombreArr = Split(Trim($rng));
		$nombreArr = explode(' ',trim($rng));
		
		//die(var_dump($nombreArr));
		//'Analizar cada palabra dentro del arreglo
		for ($i = 0; $i < count($nombreArr); $i++) {
			switch (strtolower($nombreArr[$i])) {
				case "de":
				case "del":
				case "la":
				case "las":
				case "los":
				case "san":
					$nuevaCadena = $nuevaCadena.$nombreArr[$i]." ";
					break;
				default:
					$nuevaCadena = $nuevaCadena.$nombreArr[$i];
					break(2);
			}//end switch
		}//end for
		
		
		return $nuevaCadena;
		
		
	}//end function





	public function SEPARARAPELLIDOS_2eraParte($rng) {
		$nombreArr=array();
		$nuevaCadena='' ;
		$capturarNombre = False;
		$i;
		//'Dvidir el nombre por palabras en un arreglo
		//nombreArr = Split(Trim(rng.Value))
		$nombreArr = explode(' ',Trim($rng));
		//'Analizar cada palabra dentro del arreglo
		
		for ($i = 0; $i < count($nombreArr); $i++) {
			switch (strtolower($nombreArr[$i])) {
				case "de":
				case "del":
				case "la":
				case "las":
				case "los":
				case "san":
					If ($capturarNombre == True){
						$nuevaCadena = $nuevaCadena.$nombreArr[$i]." ";
					}
					break;

				default:
					If ($capturarNombre == False)
					$capturarNombre = True;
					Else
					{$nuevaCadena = $nuevaCadena.$nombreArr[$i];
					break(2);}
			}//end switch
		}//end for
		return $nuevaCadena;
		//self::SEPARARAPELLIDOS_2eraParte(nuevaCadena);
	}//end function



}//end class
