<?php

namespace Dispo\Data;

class InventarioData
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
	 * @var int
	 */
	
	protected $punto_corte;


	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getPuntoCorte() 				{return $this->punto_corte;}


	//metodos SET
	
	public function setId($valor) 					{$this->id				= $valor;}
	public function setNombre($valor) 				{$this->nombre			= $valor;}	
	public function setPuntoCorte($valor) 			{$this->punto_corte		= $valor;}
	

}//fin class

?>
