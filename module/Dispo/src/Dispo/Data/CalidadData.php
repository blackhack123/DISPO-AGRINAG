<?php

namespace Dispo\Data;

class CalidadData
{
	/**
	 * @var int
	 */	
	protected $id;
	
	/**
	 * @var string
	 */	
	protected $nombre;
	
	
	/**
	 * @var string
	 */
	protected $clasifica_fox;
	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getClasificaFox()				{return $this->clasifica_fox;}
	
	
	//metodos SET
	public function setId($valor) 					{$this->id				= $valor;}
	public function setNombre($valor) 				{$this->nombre			= $valor;}
	public function setClasificaFox($valor) 		{$this->clasifica_fox	= $valor;}

}//fin class

?>
