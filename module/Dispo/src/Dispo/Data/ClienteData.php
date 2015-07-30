<?php

namespace Dispo\Data;

class ClienteData
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
	protected $direccion;
	
	/**
	 * @var string
	 */
	protected $pais_id;
	/**
	 * @var string
	 */
	protected $ciudad;
	/**
	 * @var string
	 */
	protected $telefono1;

	/**
	 * @var string
	 */
	protected $telefono2;
	
	/**
	 * @var string
	 */
	protected $fax1;
	
	/**
	 * @var string
	 */
	protected $fax2;
	
	/**
	 * @var string
	 */
	protected $email;
		
	/**
	 * @var int
	 */
	protected $grupo_precio_cab_id;
	
	/**
	 * @var string
	 */
	protected $estado;
	
	/**
	 * @var string
	 */
	protected $fec_ingeso;
	
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
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getDireccion() 					{return $this->direccion;}
	public function getPaisId() 					{return $this->pais_id;}
	public function getCiudad() 					{return $this->ciudad;}
	public function getTelefono1() 					{return $this->telefono1;}
	public function getTelefono2() 					{return $this->telefono2;}
	public function getFax1() 						{return $this->fax1;}
	public function getFax2() 						{return $this->fax2;}
	public function getEmail()	 					{return $this->email;}
	public function getGrupoPrecioCabId() 			{return $this->grupo_precio_cab_id;}
	public function getEstado() 					{return $this->estado;}
	public function getFecIngreso() 				{return $this->fec_ingreso;}
	public function getFecModifica() 				{return $this->fec_modifica;}
	public function getUsuarioIngId() 				{return $this->usuario_ing_id;}
	public function getUsuarioModId() 				{return $this->usuario_mod_id;}
	public function getSincronizado() 				{return $this->sincronizado;}
	public function getFecSincronizado() 			{return $this->fec_sincronizado;}
	
	
	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setNombre($valor) 				{$this->nombre					= $valor;}
	public function setDireccion($valor) 			{$this->direccion				= $valor;}
	public function setPaisId($valor) 				{$this->pais_id					= $valor;}
	public function setCiudad($valor) 				{$this->ciudad					= $valor;}
	public function setTelefono1($valor) 			{$this->telefono1				= $valor;}
	public function setTelefono2($valor) 			{$this->telefono2				= $valor;}
	public function setFax1($valor) 				{$this->fax1					= $valor;}
	public function setFax2($valor) 				{$this->fax2					= $valor;}
	public function setEmail($valor) 				{$this->email					= $valor;}
	public function setGrupoPrecioCabId($valor) 	{$this->grupo_precio_cab_id		= $valor;}
	public function setEstado($valor) 				{$this->estado					= $valor;}
	public function setFecIngreso($valor) 			{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor) 			{$this->fec_modifica			= $valor;}
	public function setUsuarioIngId($valor) 		{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor)			{$this->usuario_mod_id			= $valor;}
	public function setSincronizado($valor) 		{$this->sincronizado			= $valor;}
	public function setFecSincronizado($valor)		{$this->fec_sincronizado		= $valor;}

}//fin class


