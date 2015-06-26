<?php

namespace Dispo\Data;

class AgenciaCargaData
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
	protected $direccion;
	
	/**
	 * @var string
	 */	
	protected $telefono;

	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getDireccion() 					{return $this->direccion;}
	public function getTelefono() 					{return $this->telefono;}	
	
	//metodos SET
	public function setId($valor) 					{$this->id			= $valor;}
	public function setNombre($valor) 				{$this->nombre		= $valor;}
	public function setDireccion($valor) 			{$this->direccion	= $valor;}
	public function setTelefono($valor) 			{$this->telefono	= $valor;}	
}//fin class

?>
