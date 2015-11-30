<?php
namespace Application\Classes;


class Compress {


	/**
	 * 
	 * @param array|string $files
	 * @param string $zipname
	 * @return boolean
	 */
	static function comprimir($files, $zipname)
	{
		//$files = array('readme.txt', 'test.html', 'image.gif');
		$zip = new \ZipArchive;
		$zip->open($zipname, \ZipArchive::CREATE);
		if (is_array($files))
		{
			foreach ($files as $file) {
			  $zip->addFile($file, basename($file));
			}
		}else{
			if ($files){
				$zip->addFile($files, basename($files));
			}//end if
		}//end if
		$zip->close();
		return true;
	}//end function comprimir
	
	
	/**
	 *
	 * @param string $ruta
	 * @param string $nombre_archivo
	 * @return boolean
	 */
	static function downloadFile($archivo)
	{
		if (file_exists($archivo)){
			//ini_set('zlib.output_compression', 'Off');
			
			//header('Content-Description: File Transfer');
			//header('Content-Type: application/octet-stream');
			header('Content-Type: application/zip');
			//header("Content-type: application/force-download");
			header("Content-Transfer-Encoding: binary");
			header('Content-disposition: attachment; filename="'.basename($archivo).'"');
			header('Expires: 0');
			//header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($archivo));
			/*ob_clean();
			flush();*/
			$fp = @fopen($archivo,"rb");
			fpassthru($fp);
			fclose($fp);
			//readfile($archivo);
			return true;
		}else{
			echo("Error al leer el archivo");
			return false;
		}//end if
	}//end function downloadFile	
	
	
}///end class
