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
	 * @var int
	 */	
	protected $direccion;
	


	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getNivel() 						{return $this->nivel;}
	
	
	//metodos SET
	public function setId($valor) 					{$this->id			= $valor;}
	public function setNombre($valor) 				{$this->nombre		= $valor;}
	public function setNivel($valor) 				{$this->nivel		= $valor;}

}//fin class

?>
