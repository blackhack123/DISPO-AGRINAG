<?php

namespace Dispo\Data;

class TipoCajaMatrizData
{
	/**
	 * @var int
	 */	
	protected $id;

	/**
	 * @var string
	 */	
	protected $tipo_caja_id;

	/**
	 * @var string
	 */	
	protected $inventario_id;


	/**
	 * @var string
	 */	
	protected $variedad_id;

	/**
	 * @var string
	 */	
	protected $grado_id;

	/**
	 * @var int
	 */	
	protected $tallos_x_bunch;
	
	/**
	 * @var int
	 */
	protected $unds_bunch;
	
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
	public function getId() 						{return $this->id;}
	public function getTipoCajaId() 				{return $this->tipo_caja_id;}
	public function getInventarioId() 				{return $this->inventario_id;}
	public function getVariedadId() 				{return $this->variedad_id;}
	public function getGradoId() 					{return $this->grado_id;}
	public function getTallosxBunch()				{return $this->tallos_x_bunch;}
	public function getUndsBunch()					{return $this->unds_bunch;}
	public function getFecIngreso()					{return $this->fec_ingreso;}
	public function getFecModifica()				{return $this->fec_modifica;}
	public function getUsuarioIngId()				{return $this->usuario_ing_id;}
	public function getUsuarioModId()				{return $this->usuario_mod_id;}


	//metodos SET
	public function setId($valor) 					{$this->id					= $valor;}
	public function setTipoCajaId($valor) 			{$this->tipo_caja_id		= $valor;}
	public function setInventarioId($valor) 		{$this->inventario_id		= $valor;}
	public function setVariedadId($valor) 			{$this->variedad_id			= $valor;}
	public function setGradoId($valor) 				{$this->grado_id			= $valor;}
	public function setTallosxBunch($valor) 		{$this->tallos_x_bunch		= $valor;}
	public function setUndsBunch($valor) 			{$this->unds_bunch			= $valor;}
	public function setFecIngreso($valor)			{$this->fec_ingreso			= $valor;}
	public function setFecModifica($valor)			{$this->fec_modifica 		= $valor;}
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id		= $valor;}
	public function setUsuarioModId($valor)			{$this->usuario_mod_id		= $valor;}
	

}//fin class

?>
