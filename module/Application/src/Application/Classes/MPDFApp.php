<?php

namespace Application\Classes;

/**
 *
 * Permite liberar la libreria de MPDF para que se vincule de manera facil para la aplicacion del ZEND Framework
 *
 * @author msalazar
 *
 */
class MPDFApp {


	public function __construct()
	{				
		require_once 'vendor/MPDF57/mpdf.php';
	}//end function __construct


	public function save()
	{		

	}//end public function save
}//end class MPDFApp
