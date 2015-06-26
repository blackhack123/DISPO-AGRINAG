<?php
namespace Application\Classes;


class ArchivoTexto {
	
	/**
	 * 
	 * @param string $ruta
	 * @param string $nombre_archivo
	 * @param array $cadena_array
	 * @return boolean
	 */
	static function creaArchivoConArray($ruta, $nombre_archivo, $cadena_array)
	{
		$fh = fopen($ruta.'/'.$nombre_archivo, 'w');
		$bd_1era_vez=true;
		foreach ($cadena_array as $value) {
			if ($bd_1era_vez==false)
			{
				$registro_linea = PHP_EOL; //Salto de linea
				fwrite($fh, $registro_linea);
			}//end if
			fwrite($fh, $value);
			$bd_1era_vez=false;
		}//end for

		return true;
	}//end function creaArchivoConArray
	
	
	
	
}///end class
