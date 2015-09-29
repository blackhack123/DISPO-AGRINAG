<?php

namespace Dispo\Data;

class GrupoDispoDetData
{


	/**
	 * @var int
	 */	
	protected $grupo_dispo_cab_id;

	/**
	 * 
	 * @var int
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
	 * @var int
	 */	
	protected $cantidad_bunch;

	/**
	 * @var int
	 */	
	protected $cantidad_bunch_disponible;

	/**
	 * @var int
	 */
	protected $usuario_ing_id;
	
	/**
	 * @var string
	 */
	protected $fec_ingreso;
		
	/**
	 * @var int
	 */	
	protected $usuario_mod_id;

	/**
	 * @var string
	 */	
	protected $fec_modifica;



	//metodos GET
	public function getGrupoDispoCabId() 						{return $this->grupo_dispo_cab_id;}
	public function getProductoId()								{return $this->producto_id;}
	public function getVariedadId() 							{return $this->variedad_id;}
	public function getGradoId() 								{return $this->grado_id;}
	public function getTallosXBunch()							{return $this->tallos_x_bunch;}
	public function getCantidadBunch() 							{return $this->cantidad_bunch;}
	public function getCantidadBunchDisponible() 				{return $this->cantidad_bunch_disponible;}
	public function getUsuarioModId() 							{return $this->usuario_mod_id;}
	public function getFecModifica() 							{return $this->fec_modifica;}
	public function getUsuarioIngId() 							{return $this->usuario_ing_id;}
	public function getFecIngreso() 							{return $this->fec_ingreso;}


	
	//metodos SET
	
	public function setGrupoDispoCabId($valor) 					{$this->grupo_dispo_cab_id				= $valor;}
	public function setProductoId($valor)						{$this->producto_id						= $valor;}	
	public function setVariedadId($valor) 						{$this->variedad_id						= $valor;}
	public function setGradoId($valor) 							{$this->grado_id						= $valor;}
	public function setTallosXBunch($valor)						{$this->tallos_x_bunch					= $valor;}
	public function setCantidadBunch($valor) 					{$this->cantidad_bunch					= $valor;}
	public function setCantidadBunchDisponible($valor) 			{$this->cantidad_bunch_disponible		= $valor;}
	public function setUsuarioModId($valor) 					{$this->usuario_mod_id					= $valor;}
	public function setFecModifica($valor) 						{$this->fec_modifica					= $valor;}
	public function setUsuarioIngId($valor) 					{$this->usuario_ing_id 					= $valor;}
	public function setFecIngreso($valor) 						{$this->fec_ingreso						= $valor;}

}//fin class

?>
