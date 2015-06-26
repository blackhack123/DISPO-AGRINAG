<?php

namespace Dispo\Data;

class DispoUnidaData
{
	/**
	 * @var int
	 */	
	
	protected $variedad_id;

	/**
	 * @var int
	 */
	protected $grado_id;

	/**
	 * @var int
	 */
	protected $Totalgrados;

	/**
	 * @var int
	 */
	protected $HBTotal;
	
	/**
	 * @var int
	 */
	protected $HB;
	
	/**
	 * @var int
	 */
	protected $HBRest;
	
	
	//metodos GET
	public function getVariedadId() 					{return $this->variedad_id;}
	public function getGradoId() 						{return $this->grado_id;}
	public function getTotalGrados() 					{return $this->Totalgrados;}
	public function getHBTotal() 						{return $this->HBTotal;}
	public function getHB() 							{return $this->HB;}
	public function getHBRest() 						{return $this->HBRest;}
	


	
	//metodos SET
	public function setVariedadId($valor) 				{$this->variedad_id		= $valor;}
	public function setGradoId($valor) 					{$this->grado_id		= $valor;}
	public function setTotalGrados($valor) 				{$this->Totalgrados		= $valor;}
	public function setHBTotal($valor) 					{$this->HBTotal			= $valor;}
	public function setHB($valor) 						{$this->HB				= $valor;}
	public function setHBRest($valor) 					{$this->HBRest			= $valor;}

}//fin class

?>
