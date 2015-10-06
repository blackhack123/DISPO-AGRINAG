<?php

namespace Dispo\Data;

class ParametrizarData
{
	/**
	 * @var string
	 */	
	protected $id;

	/**
	 * @var string
	 */	
	protected $descripcion;
	
	/**
	 * @var string
	 */
	protected $tipo;
	
	/**
	 * @var string
	 */
	protected $valor_texto;
	
	/**
	 * @var float
	 */
	protected $valor_numerico;
	
	
	/**
	 * @var string
	 */
	protected $observacion;
	
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
	public function getId() 					{return $this->id;}
	public function getDescripcion()			{return $this->descripcion;}
	public function getTipo()	 				{return $this->tipo;}
	public function getValorTexto()				{return $this->valor_texto;}
	public function getValorNumerico()			{return $this->valor_numerico;}
	public function getObservacion()			{return $this->observacion;}
	public function getFecIngreso() 			{return $this->fec_ingreso;}
	public function getFecModifica() 			{return $this->fec_modifica;}
	public function getUsuarioIngId() 			{return $this->usuario_ing_id;}
	public function getUsuarioModId() 			{return $this->usuario_mod_id;}


	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setDescripcion($valor) 			{$this->descripcion				= $valor;}
	public function setTipo($valor) 				{$this->tipo					= $valor;}
	public function setValorTexto($valor) 			{$this->valor_texto				= $valor;}
	public function setValorNumerico($valor) 		{$this->valor_numerico			= $valor;}
	public function setObservacion($valor) 			{$this->observacion				= $valor;}
	public function setFecIngreso($valor) 			{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor) 			{$this->fec_modifica			= $valor;}
	public function setUsuarioIngId($valor) 		{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor) 		{$this->usuario_mod_id			= $valor;}

	
}//end class

