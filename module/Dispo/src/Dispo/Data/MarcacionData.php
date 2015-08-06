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
	
	/**
	 * @var string
	 */
	protected $estado;
	
	/**
	 * @var string
	 */
	protected $fec_ingreso;
	
	/**
	 * @var string
	 */
	protected $fec_modifica;
	
	/**
	 * @var int
	 */
	protected $usuario_ing_id;
	
	/**
	 * @var int
	 */
	
	protected $usuario_mod_id;
	
	/**
	 * @var int
	 */
	protected $sincronizado;
	
	/**
	 * @var string
	 */
	protected $fec_sincronizado;
	



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
	public function getEstado() 					{return $this->estado;}
	public function getFecIngreso() 				{return $this->fec_ingreso;}
	public function getFecModifica() 				{return $this->fec_modifica;}
	public function getUsuarioIngId()				{return $this->usuario_ing_id;}
	public function getUsuarioModId()				{return $this->usuario_mod_id;}
	public function getSincronizado() 				{return $this->sincronizado;}
	public function getFecSincronizado() 			{return $this->fec_sincronizado;}

	
	//metodos SET
	
	public function setMarcacionsec($valor) 				{$this->marcacion_sec		= $valor;}
	public function setClienteId($valor) 					{$this->cliente_id			= $valor;}
	public function setNombre($valor) 						{$this->nombre				= $valor;}
	public function setDireccion($valor) 					{$this->direccion			= $valor;}
	public function setCiudad($valor) 						{$this->ciudad				= $valor;}
	public function setPaisId($valor) 						{$this->pais_id				= $valor;}
	public function setContacto($valor) 					{$this->contacto			= $valor;}
	public function setTelefono($valor) 					{$this->telefono			= $valor;}
	public function setZip($valor) 							{$this->zip					= $valor;}
	public function setEstado($valor) 						{$this->estado				= $valor;}
	public function setFecIngreso($valor) 					{$this->fec_ingreso			= $valor;}
	public function setFecModifica($valor) 					{$this->fec_modifica		= $valor;}
	public function setUsuarioIngId($valor) 				{$this->usuario_ing_id		= $valor;}
	public function setUsuarioModId($valor) 				{$this->usuario_mod_id		= $valor;}
	public function setSinronizado($valor) 					{$this->sincronizado		= $valor;}
	public function setFecSincronizado($valor) 				{$this->fec_sincronizado	= $valor;}


}//fin class

