<?php

namespace Dispo\Data;

class GrupoPrecioOfertaData
{
	/**
	 * @var int
	 */	
	protected $grupo_precio_cab_id;

	/**
	 * @var string
	 */	
	protected $producto_id;

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
	 * @var string
	 */
	protected $producto_combo_id;
	
	/**
	 * @var string
	 */
	protected $variedad_combo_id;
	
	/**
	 * @var string
	 */
	protected $grado_combo_id;

	/**
	 * @var int
	 */
	protected $tallos_x_bunch_combo;
	
	/**
	 * @var float
	 */
	protected $factor_combo;	
	
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
	public function getGrupoPrecioCabId() 		{return $this->grupo_precio_cab_id;}
	public function getProductoId()				{return $this->producto_id;}
	public function getVariedadId() 			{return $this->variedad_id;}
	public function getGradoId() 				{return $this->grado_id;}
	public function getTallosXBunch()			{return $this->tallos_x_bunch;}
	public function getProductoComboId()		{return $this->producto_combo_id;}
	public function getVariedadComboId() 		{return $this->variedad_combo_id;}
	public function getGradoComboId()			{return $this->grado_combo_id;}
	public function getTallosXBunchCombo()		{return $this->tallos_x_bunch_combo;}
	public function getFactorCombo()			{return $this->factor_combo;}
	public function getFecIngreso() 			{return $this->fec_ingreso;}
	public function getFecModifica() 			{return $this->fec_modifica;}
	public function getUsuarioIngId() 			{return $this->usuario_ing_id;}
	public function getUsuarioModId() 			{return $this->usuario_mod_id;}


	//metodos SET
	public function setGrupoPrecioCabId($valor) {$this->grupo_precio_cab_id	= $valor;}
	public function setProductoId($valor)		{$this->producto_id			= $valor;}	
	public function setVariedadId($valor) 		{$this->variedad_id			= $valor;}
	public function setGradoId($valor) 			{$this->grado_id			= $valor;}
	public function setTallosXBunch($valor)		{$this->tallos_x_bunch		= $valor;}
	public function setProductoComboId($valor)	{$this->producto_combo_id	= $valor;}
	public function setVariedadComboId($valor) 	{$this->variedad_combo_id	= $valor;}
	public function setGradoComboId($valor)		{$this->grado_combo_id		= $valor;}
	public function setTallosXBunchCombo($valor){$this->tallos_x_bunch_combo= $valor;}
	public function setFactorCombo($valor)		{$this->factor_combo		= $valor;}
	public function setFecIngreso($valor) 		{$this->fec_ingreso			= $valor;}
	public function setFecModifica($valor) 		{$this->fec_modifica		= $valor;}
	public function setUsuarioIngId($valor) 	{$this->usuario_ing_id		= $valor;}
	public function setUsuarioModId($valor) 	{$this->usuario_mod_id		= $valor;}

}//end class

?>
