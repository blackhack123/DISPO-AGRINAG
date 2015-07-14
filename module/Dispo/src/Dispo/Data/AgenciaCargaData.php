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

	/**
	 * @var string
	 */
	protected $tipo;
	
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
	 * @var string
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
	public function getTelefono() 					{return $this->telefono;}	
	public function getTipo() 						{return $this->tipo;}
	public function getEstado() 					{return $this->estado;}
	public function getFecIngreso() 				{return $this->fec_ingreso;}
	public function getFecModifica() 				{return $this->fec_modifica;}
	public function getUsuarioIngId()				{return $this->usuario_ing_id;}
	public function getUsuarioModId()				{return $this->usuario_mod_id;}
	public function getSincronizado() 			{return $this->sincronizado;}
	public function getFecSincronizado() 			{return $this->fec_sincronizado;}
	//metodos SET
	public function setId($valor) 					{$this->id					= $valor;}
	public function setNombre($valor) 				{$this->nombre				= $valor;}
	public function setDireccion($valor) 			{$this->direccion			= $valor;}
	public function setTelefono($valor) 			{$this->telefono			= $valor;}
	public function setTipo($valor) 				{$this->tipo				= $valor;}
	public function setEstado($valor) 				{$this->estado				= $valor;}
	public function setFecIngreso($valor) 			{$this->fec_ingreso			= $valor;}
	public function setFecModifica($valor) 			{$this->fec_modifica		= $valor;}
	public function setUsuarioIngId($valor) 		{$this->usuario_ing_id		= $valor;}
	public function setUsuarioModId($valor) 		{$this->usuario_mod_id		= $valor;}
	public function setSinronizado($valor) 			{$this->sincronizado		= $valor;}
	public function setFecSincronizado($valor) 		{$this->fec_sincronizado	= $valor;}

	
}//fin class

?>
