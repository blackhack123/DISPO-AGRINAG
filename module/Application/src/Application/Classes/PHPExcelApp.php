<?php

namespace Application\Classes;

/**
 *
 * Permite liberar la libreria de PHPExcel para que se vincule de manera facil para la aplicacion del ZEND Framework
 *
 * @author msalazar
 *
 */
class PHPExcelApp {

	private $styleArray = null;
	private $user_name	= null;

	const STYLE_ARRAY_DETALLE_BORDE 		= 'detalle-borde';
	const STYLE_ARRAY_NEGRILLA 				= 'negrilla';
	const STYLE_ARRAY_TOTALES 				= 'totales';
	const STYLE_ARRAY_BORDE_ETINAR 		    = 'borde_etinar';
	const STYLE_ARRAY_BORDE_TODO 		    = 'borde_todo';
	const STYLE_ARRAY_BORDE_ARRIBA 		    = 'borde_arriba';
	const STYLE_ARRAY_BORDE_DERECHA 		= 'borde_derecha';
	const STYLE_ARRAY_UNDERLINE 		    = 'underline';

	const FORMAT_EXCEL_2007					= 'EXCEL_2007';
	const FORMAT_EXCEL_95					= 'EXCEL_95';
	const FORMAT_EXCEL_2007_TO_DISK			= 'EXCEL_2007_TO_DISK';
	const FORMAT_EXCEL_2007_TO_DISK_SERVER	= 'EXCEL_2007_TO_DISK_SERVER';	

	const COLOR_ORANGE						= 'FFFFA500';


	/**
	 * Obtiene el estilo
	 *
	 * @param string $const_style_array  STYLE_ARRAY_especific
	 * @return array
	 */
	public function getStyleArray($const_style_array)
	{
		return $this->styleArray[$const_style_array];
	}//end function getStyleArray


	/**
	 *
	 * @param string $valor
	 */
	public function setUserName($valor)
	{
		$this->user_name  = $valor;
	}//end function setUserName



	public function __construct()
	{				
		require_once 'vendor/PHPExcel/PHPExcel.php';
		
		$this->styleArrayLoad();
	}//end function __construct


	/**
	 * Llenar el Arreglo de Estilos
	 */
	private function styleArrayLoad()
	{
		//Se da formato a los detalles
		$this->styleArray['detalle-borde'] = array(
				'borders' => array(
						'allborders' => array(
								'style' => \PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => 'FF000000'),
						),
				),
		);
		$this->styleArray['negrilla'] = array(
				'font' => array(
						'bold' => 'true'
				),
		);
		//Se da formato a los totales
		$this->styleArray['totales'] = array(
				'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFF33')
				),
				'font' => array(
						'bold' => 'true'
				),
		);


	 $this->styleArray['borde_etinar'] = array(
				'borders' => array(
						'outline' => array(
								'style' => \PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '973D05'),
						),
				),
		);


	 $this->styleArray['borde_todo'] = array(
	 		'borders' => array(
	 				'allborders' => array(
	 						'style' => \PHPExcel_Style_Border::BORDER_THIN,
	 						'color' => array('rgb' => '000000'),
	 				),
	 		),
	 );

	  
	 $this->styleArray['borde_arriba'] = array(
	 		'borders' => array(
	 				'top'     => array(
								'style' => \PHPExcel_Style_Border::BORDER_THIN,
								'color' => array(
										'rgb' => '973D05'
								)
						)
	 		) );
	 $this->styleArray['borde_derecha'] = array(
	 		'borders' => array(
	 				'right'     => array(
	 						'style' => \PHPExcel_Style_Border::BORDER_THIN,
	 						'color' => array(
	 								'rgb' => '973D05'
	 						)
	 				)
	 		) );
	  
	 
	 
	 $this->styleArray['underline'] = array(
	 		'font' => array(
	 				'underline' => \PHPExcel_Style_Font::UNDERLINE_SINGLE
	 		)
	 );
	 
	 
	 //Se da formato a las columnas de los detalles
	 $this->styleArray['columna01'] = array(
	 		'fill' => array(
	 				'type' => \PHPExcel_Style_Fill::FILL_SOLID,
	 				'color' => array('rgb' => 'C4BD97')
	 		),
	 );
	 
	 $this->styleArray['columna02'] = array(
	 		'fill' => array(
	 				'type' => \PHPExcel_Style_Fill::FILL_SOLID,
	 				'color' => array('rgb' => 'CCC0DA')
	 		),
	 );
	 
	 
	 //Se da formato a los titulos
	 $this->styleArray['titulo01'] = array(
	 		'fill' => array(
	 				'type' => \PHPExcel_Style_Fill::FILL_SOLID,
	 				'color' => array('rgb' => '16365C')
	 		),
	 		'font' => array(
	 				'bold' => 'true',
	 		),
	 		'alignment' => array(
	 				'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	 		)
	 );
	 
	 $this->styleArray['titulo02'] = array(
	 		'fill' => array(
	 				'type' => \PHPExcel_Style_Fill::FILL_SOLID,
	 				'color' => array('rgb' => '974706')
	 		),
	 		'font' => array(
	 				'bold' => 'true',
	 		),
	 		'alignment' => array(
	 				'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	 		)
	 );	 

	}//end function styleArrayLoad






	/**
	 * Obtiene la letra de la columna de EXCEL a partir de un columna representada numericamente
	 *
	 * @param int $num
	 * @return string
	 */
	public function getNameFromNumber($num)
	{
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
			return $this->getNameFromNumber($num2 - 1) . $letter;
		} else {
			return $letter;
		}
	}



	/**
	 * Setea la seguridad de la hoja de cálculo para que sea imposible de modificar su contenido
	 *
	 * @param \PHPExcel $objPHPExcel
	 */
	public function setSecurity(&$objPHPExcel)
	{
		$password = $this->randomPassword();
		//Seguridad al documento
		$objPHPExcel->getSecurity()->setLockWindows(true);
		$objPHPExcel->getSecurity()->setLockStructure(true);
		$objPHPExcel->getSecurity()->setLockRevision(true);
		$objPHPExcel->getSecurity()->setWorkbookPassword($password);

		//Seguridad a las hojas de estilo
		//Se debe de realizar un proceso de seguridad a todas las hojas en excel
		$objPHPExcel->getActiveSheet()->getProtection()->setPassword($password);
		//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(false);
		$objPHPExcel->getActiveSheet()->getProtection()->setDeleteColumns(true);
		$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
		$objPHPExcel->getActiveSheet()->getProtection()->setInsertColumns(true);
		$objPHPExcel->getActiveSheet()->getProtection()->setInsertHyperlinks(true);
		$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
		$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);

		//Desactiva la seguridad de una celda especifica
		/*		$objPHPExcel->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(
		 \PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
		);
		*/
		$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
	}//end function setSecurity



	/**
	 * Genera un password de manera random
	 *
	 * @return string
	 */
	private function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}//end function randomPassword



	/**
	 * Setea el metadato de la hoja de cálculo
	 *
	 * @param \PHPExcel $objPHPExcel
	 */
	public function setMetaDataDocument(&$objPHPExcel)
	{
		if (!isset($this->user_name)){
			throw new \Exception('UserName no definido');
		}

		$objPHPExcel->getProperties()->setCreator($this->user_name)
		->setLastModifiedBy("Disponibilidad Agrinag")
		->setTitle("Office 2007 XLSX Disponibilidad")
		->setSubject("Office 2007 XLSX Disponibilidad")
		->setDescription("Dispo Agrinag")
		->setKeywords("office 2007 openxml php")
		->setCategory("Document Disponibilidad");
	}//end function setMetaDataDocument


	/**
	 *
	 * @param \PHPExcel $objPHPExcel
	 * @param string $type_output_format  Constante FORMAT_EXCEL_nombre
	 * @param string $name_file opcional
	 */
	public function save(&$objPHPExcel, $type_output_format, $name_file = '')
	{	
		$matriz = explode(".", $name_file);
		if (count($matriz) == 1)
		{
			$name_file.= ".xlsx";
		}//end if
		
		switch ($type_output_format)
		{
			case self::FORMAT_EXCEL_2007:
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$name_file.'"');
				header('Cache-Control: max-age=0');
				
				
				$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->setPreCalculateFormulas(false);
				$objWriter->save('php://output');

				break;
					
			case self::FORMAT_EXCEL_95:
				//$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
				//$objWriter->save("05featuredemo.xls");
				
				break;
				
				
			case self::FORMAT_EXCEL_2007_TO_DISK:
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$name_file.'"');
				header('Cache-Control: max-age=0');
			
				$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->setPreCalculateFormulas(false);
				$objWriter->save($name_file);
			
				break;
				
				
			case self::FORMAT_EXCEL_2007_TO_DISK_SERVER:
				$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->setPreCalculateFormulas(false);
				$objWriter->save($name_file);
				break;				
		}//end switch
	}//end public function save
}//end class PHPExcel
