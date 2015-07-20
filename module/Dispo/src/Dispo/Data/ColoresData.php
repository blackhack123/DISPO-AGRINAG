<?php

namespace Dispo\Data;

class ColoresData
{
	/**
	 * @var string
	 */	
	protected $color;
	
	/**
	 * @var string
	 */	
	protected $nombre;
	
	
	//metodos GET
	public function getColor() 						{return $this->color;}
	public function getNombre() 					{return $this->nombre;}
	
	
	//metodos SET
	public function setColor($valor) 				{$this->color					= $valor;}
	public function setNombre($valor) 				{$this->nombre					= $valor;}

}//end class

