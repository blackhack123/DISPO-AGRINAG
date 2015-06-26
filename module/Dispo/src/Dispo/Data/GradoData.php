<?php

namespace Dispo\Data;

class GradoData
{
	/**
	 * @var string
	 */	
	
	protected $id;


	/**
	 * @var string
	 */	
	
	protected $nombre;

	
	
	//metodos GET
	public function getId() 				{return $this->id;}
	public function getNombre() 			{return $this->nombre;}



	
	//metodos SET
	public function setId($valor) 			{$this->id			= $valor;}
	public function setNombre($valor) 		{$this->nombre		= $valor;}

}//fin class

?>
