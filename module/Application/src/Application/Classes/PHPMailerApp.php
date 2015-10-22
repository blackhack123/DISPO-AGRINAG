<?php

namespace Application\Classes;

/**
 *
 * Permite liberar la libreria de MPDF para que se vincule de manera facil para la aplicacion del ZEND Framework
 *
 * @author msalazar
 *
 */
class PHPMailerApp {


	public function __construct()
	{				
		require_once 'vendor/PHPMailer/PHPMailerAutoload.php';
	}//end function __construct


}//end class PHPMailerApp
