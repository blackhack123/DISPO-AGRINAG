<?php

namespace Dispo\Data;

class MarcacionData
{


	/**
	 * @var int
	 */	
	
	protected $marcacion_sec;


	/**
	 * @var string
	 */	

	protected $cliente_id;


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
	protected $ciudad;


	/**
	 * @var string
	 */	
	protected $pais_id;

	/**
	 * @var string
	 */	
	protected $contacto;

	/**
	 * @var string
	 */	
	protected $telefono;


	/**
	 * @var string
	 */	
	protected $zip;



	//metodos GET
	public function getMarcacionSec() 				{return $this->marcacion_sec;}
	public function getClienteId() 					{return $this->cliente_id;}
	public function getNombre() 					{return $this->nombre;}
	public function getDireccion() 					{return $this->direccion;}
	public function getCiudad() 					{return $this->ciudad;}
	public function getPaisId() 					{return $this->pais_id;}
	public function getContacto() 					{return $this->contacto;}
	public function getTelefono() 					{return $this->telefono;}
	public function getZip() 						{return $this->zip;}


	
	//metodos SET
	
	public function setMarcacionsec($valor) 				{$this->marcacion_sec	= $valor;}
	public function setClienteId($valor) 					{$this->cliente_id		= $valor;}
	public function setNombre($valor) 						{$this->nombre			= $valor;}
	public function setDireccion($valor) 					{$this->direccion		= $valor;}
	public function setCiudad($valor) 						{$this->ciudad			= $valor;}
	public function setPaisId($valor) 						{$this->pais_id			= $valor;}
	public function setContacto($valor) 					{$this->contacto		= $valor;}
	public function setTelefono($valor) 					{$this->telefono		= $valor;}
	public function setZip($valor) 							{$this->zip				= $valor;}



}//fin class

?>
