<?php

namespace Application\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
//	Zend\Authentication\AuthenticationService; 

 
class ArchivoPlugin extends AbstractPlugin 
{


	public function abrir($fileName, $download=false)
	
	{
		
		//die(var_dump($fileName));
		
		if(!is_file($fileName)) {
			//do something
		}//end if
	
	
		$content_type = $this->returnMIMEType($fileName);
		$fileContents = file_get_contents($fileName);
	
		$response = $this->getController()->getResponse();
		$response->setContent($fileContents);
	
		$headers = $response->getHeaders();
		$headers->clearHeaders()
		->addHeaderLine('Content-Type', $content_type)
		//->addHeaderLine('Content-Disposition', 'attachment; filename="' . $archivo . '"')
		->addHeaderLine('Content-Length', strlen($fileContents));
	
		if ($download==true){
				
			$headers->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"');
		}//end if
	
		
		
		return $response;
	}//end function abrir
	
	
	
	public function downloadForce($nombre_archivo)
	{
		header("Content-Description: File Transfer");
		header("Content-Type: application/force-download");
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Disposition: attachment; filename=\"$nombre_archivo\";" );
		header("Content-Transfer-Encoding: binary");
		//print "hola mundo";
		readfile("$nombre_archivo");
		exit();
	}//end function downloadForce
	
	
	/*-----------------------------------------------------------------------------*/
	public function returnMIMEType($filename)
	/*-----------------------------------------------------------------------------*/
    {

        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
                return "image/".strtolower($fileSuffix[1]);

            case "tiff" :
            case "tif" :			
                return "image/TIF";
				
            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
            case "xlsx" :            	
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }

            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }//end function returnMIMEType

}//end class ArchivosService

