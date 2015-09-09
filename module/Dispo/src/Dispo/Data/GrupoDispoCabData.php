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
	
	/**
	 * @var int
	 */
	protected $calidad_id;

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
	protected $fec_ingreso;
	
	/**
	 * @var string
	 */
	protected $fec_modifica;
	
	
	//metodos GET
	public function getId() 					{return $this->id;}
	public function getNombre() 				{return $this->nombre;}
	public function getInventarioId() 			{return $this->inventario_id;}
	public function getCalidadId()				{return $this->calidad_id;}
	public function getUsuarioIngId()			{return $this->usuario_ing_id;}
	public function getUsuarioModId()			{return $this->usuario_mod_id;}
	public function getFecIngreso()				{return $this->fec_ingreso;}
	public function getFecModifica()			{return $this->fec_modifica;}


	//metodos SET
	public function setId($valor) 				{$this->id				= $valor;}
	public function setNombre($valor) 			{$this->nombre			= $valor;}
	public function setInventarioId($valor) 	{$this->inventario_id	= $valor;}
	public function setCalidadId($valor)		{$this->calidad_id 		= $valor;}
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id 	= $valor;}
	public function setUsuarioModId($valor)		{$this->usuario_mod_id 	= $valor;}
	public function setFecIngreso($valor)		{$this->fec_ingreso 	= $valor;}
	public function setFecModifica($valor)		{$this->fec_modifica 	= $valor;}	
}//fin class

?>
