<?php

namespace Dispo\Data;

class GrupoPrecioOfertaData
{


	/**
	 * @var int
	 */	
	protected $grupo_precio_cab;

	/**
	 * @var string
	 */	
	protected $variedad_id;

	/**
	 * @var string
	 */	
	protected $grado_id;

	/**
	 * @var string
	 */	
	protected $variedad_combo_id;

	/**
	 * @var string
	 */	
	protected $grado_combo_id;

	/**
	 * @var float
	 */	
	protected $factor_combo;


	//metodos GET
	public function getGrupoPrecioCab()					 	 {return $this->grupo_precio_cab;}
	public function getVariedadId() 						 {return $this->variedad_id;}
	public function getGradoId() 							 {return $this->grado_id;}
	public function getVariedadComboId() 					 {return $this->variedad_combo_id;}
	public function getGradoComboId() 						 {return $this->grado_combo_id;}
	public function getFactorCombo() 						 {return $this->factor_combo;}


	//metodos SET
	public function setGrupo_precioCab($valor) 				{$this->grupo_precio_cab		= $valor;}
	public function setVariedadId($valor) 					{$this->variedad_id				= $valor;}
	public function setGradoId($valor) 						{$this->grado_id				= $valor;}
	public function setVariedadComboId($valor) 				{$this->variedad_combo_id		= $valor;}
	public function setGradoComboId($valor) 				{$this->grado_combo_id			= $valor;}
	public function setFactorCombo($valor) 					{$this->factor_combo			= $valor;}


}//end class

?>
