<?php

namespace Seguridad\Data;

class UsuarioData
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
	protected $username;

	/**
	 * @var string
	 */	
	protected $password;


	/**
	 * @var string
	 */	
	protected $email;

	/**
	 * @var int
	 */	
	protected $perfil_id;

	/**
	 * @var string
	 */	
	protected $cliente_id;

	/**
	 * @var string
	 */	
	protected $estado;

	/**
	 * @var int
	 */	
	protected $grupo_dispo_cab_id;
	
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


	
	//metodos GET
	public function getId() 								{return $this->id;}
	public function getNombre() 							{return $this->nombre;}
	public function getUsername() 							{return $this->username;}
	public function getPassword() 							{return $this->password;}
	public function getEmail() 								{return $this->email;}
	public function getPerfilId() 							{return $this->perfil_id;}
	public function getClienteId() 							{return $this->cliente_id;}
	public function getEstado() 							{return $this->estado;}
	public function getGrupoDispoCabId() 					{return $this->grupo_dispo_cab_id;}
	public function getFecIngreso() 						{return $this->fec_ingreso;}
	public function getFecModifica() 						{return $this->fec_modifica;}
	public function getUsuarioIngId() 						{return $this->usuario_ing_id;}
	public function getUsuarioModId()	 					{return $this->usuario_mod_id;}


	//metodos SET
	public function setId($valor)					 		{$this->id						= $valor;}
	public function setNombre($valor) 						{$this->nombre					= $valor;}
	public function setUsername($valor) 					{$this->username				= $valor;}
	public function setPassword($valor) 					{$this->password				= $valor;}
	public function setEmail($valor) 						{$this->email					= $valor;}
	public function setPerfilId($valor)						{$this->perfil_id				= $valor;}
	public function setClienteId($valor) 					{$this->cliente_id				= $valor;}
	public function setEstado($valor) 						{$this->estado					= $valor;}
	public function setGrupoDispoCabId($valor) 				{$this->grupo_dispo_cab_id		= $valor;}
	public function setFecIngreso($valor) 					{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor) 					{$this->fec_modifica			= $valor;}
	public function setUsuarioIngId($valor) 				{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor) 				{$this->usuario_mod_id			= $valor;}


}//fin class



?>
