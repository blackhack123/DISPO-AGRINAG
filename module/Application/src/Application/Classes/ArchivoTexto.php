<?php
namespace Application\Classes;


class ArchivoTexto {
	
	/**
	 * 
	 * @param string $nombre_archivo
	 * @param array $cadena_array
	 * @return boolean
	 */
	static function creaArchivoConArray($archivo, $cadena_array)
	{
		$fh = fopen($archivo, 'w');
		$bd_1era_vez=true;
		foreach ($cadena_array as $value) {
			if ($bd_1era_vez==false)
			{
				//$registro_linea = PHP_EOL; //Salto de linea
				//$registro_linea = '\n';
				//$registro_linea = '\r\n\r\n';
				$salto_linea="\r\n";
				fwrite($fh, $salto_linea);
			}//end if
			//fwrite($fh, $value.PHP_EOL);
			fwrite($fh, $value);
			$bd_1era_vez=false;
		}//end for

		return true;
	}//end function creaArchivoConArray
	
	
	/**
	 * 
	 * @param string $ruta
	 * @param string $nombre_archivo
	 * @return boolean
	 */
	static function downloadFile($archivo)
	{
		if (file_exists($archivo)) {
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=".basename($archivo));			
			header('Expires: 0');
			header('Cache-Control: must-revalidate');			
			header('Pragma: public');
			header("Content-Type: application/force-download;");			
			header("Content-Length: " . filesize($archivo));

			readfile($archivo);
			return true;
		}else{
			echo("Error al leer el archivo");
			return false;
		}//end if				
	}//end function downloadFile
	
}///end class
