<?php

namespace Dispo\Data;

class VariedadData
{
	/**
	 * @var string
	 */	
	protected $id;

	/**
	 * @var string
	 */	
	protected $nombre;

	/**
	 * @var string
	 */	
	protected $colorbase;


	//metodos GET
	public function getId() 				{return $this->id;}
	public function getNombre()			 	{return $this->nombre;}
	public function getColorBase() 			{return $this->colorbase;}


	//metodos SET
	public function setId($valor) 				{$this->id				= $valor;}
	public function setNombre($valor) 			{$this->nombre			= $valor;}
	public function setColorBase($valor) 		{$this->colorbase		= $valor;}

}//end class

?>
