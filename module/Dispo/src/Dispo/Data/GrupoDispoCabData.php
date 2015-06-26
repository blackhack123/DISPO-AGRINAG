<?php

namespace Dispo\Data;

class GrupoDispoCabData
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
	protected $inventario_id;
	
	
	//metodos GET
	public function getId() 					{return $this->id;}
	public function getNombre() 				{return $this->nombre;}
	public function getInventarioId() 			{return $this->inventario_id;}



	
	//metodos SET
	public function setId($valor) 				{$this->id				= $valor;}
	public function setNombre($valor) 			{$this->nombre			= $valor;}
	public function setInventarioId($valor) 	{$this->inventario_id	= $valor;}
}//fin class

?>
