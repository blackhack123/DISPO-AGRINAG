<?php

namespace Dispo\Data;

class ClienteData
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
	 * @var string
	 */
	protected $grupo;
	
	/**
	 * @var int
	 */
	protected $grupo_precio_cab_id;
	

	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getPaisId() 					{return $this->pais_id;}
	public function getCiudad() 					{return $this->ciudad;}
	public function getTelefono1() 					{return $this->telefono1;}
	public function getTelefono2() 					{return $this->telefono2;}
	public function getFax1() 						{return $this->fax1;}
	public function getFax2() 						{return $this->fax2;}
	public function getEmail()	 					{return $this->email;}
	public function getGrupo() 						{return $this->grupo;}
	public function getgrupoPrecioCabId() 			{return $this->grupo_precio_cab_id;}
	
	
	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setPaisId($valor) 				{$this->pais_id					= $valor;}
	public function setCiudad($valor) 				{$this->ciudad					= $valor;}
	public function setTelefono1($valor) 			{$this->telefono1				= $valor;}
	public function setTelefono2($valor) 			{$this->telefono2				= $valor;}
	public function setFax1($valor) 				{$this->fax1					= $valor;}
	public function setFax2($valor) 				{$this->fax2					= $valor;}
	public function setEmail($valor) 				{$this->Email					= $valor;}
	public function setGrupo($valor) 				{$this->grupo					= $valor;}
	public function setGrupoPrecioCabId ($valor) 	{$this->grupo_precio_cab_id		= $valor;}

}//fin class


?>
