<?php

namespace General\Data;

/**
* ParametroData.
*
*/
class ParametroData
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
     * @var float
	*/
    protected $valor_numerico;
    
    /**
     * @var string
     */
    protected $valor_texto;

	/**
     * @var string
	*/
    protected $fecha_ing;

	/**
     * @var string
	*/
    protected $fecha_mod;
	
	/**
     * @var int
	*/
    protected $usuario_ing_id;

	/**
     * @var int
	*/
    protected $usuario_mod_id;
	

	/*------------------------------------------------------------------------------*/
	/*------------------------------- METODOS GET y SET ----------------------------*/
	/*------------------------------------------------------------------------------*/
	
	//Metodos GET
    public function getId() 			{return $this->id;}
    public function getDescripcion() 	{return $this->descripcion;}
    public function getTipo() 			{return $this->tipo;}
    public function getValorNumerico() 	{return $this->valor_numerico;}
    public function getValorTexto() 	{return $this->valor_texto;}
    public function getFechaIng() 		{return $this->fecha_ing;}
    public function getFechaMod() 		{return $this->fecha_mod;}
    public function getUsuarioIngId() 	{return $this->usuario_ing_id;}
    public function getUsuarioModId() 	{return $this->usuario_mod_id;}	
    
	//Metodos SET
	public function setId($valor)				{$this->id 					= $valor;}
	public function setDescripcion($valor)		{$this->descripcion 		= $valor;}
	public function setTipo($valor)				{$this->tipo 				= $valor;}
	public function setValorNumerico($valor)	{$this->valor_numerico 		= $valor;}
	public function setValorTexto($valor)		{$this->valor_texto 		= $valor;}
	public function setFechaIng($valor)			{$this->fecha_ing 			= $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod 			= $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id 		= $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id 		= $valor;}		
	
}//end class	