<?php

namespace General\Data;

/**
* PersonaData.
*
*/
class ProveedorData
{
    /**
     * @var int
     */
    protected $persona_id;

	/**
     * @var int
	*/
    protected $tipo_proveedor_id;
    
    /**
     * @var string
     */
    protected $estado;    

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
	
	//METODO GET
	public function getPersonaId()			  	{return $this->persona_id;}					
	public function getTipoProveedorId()	  	{return $this->tipo_proveedor_id;}
	public function getNroIdentificacion()	  	{return $this->nro_identificacion;}
	public function getEstado()					{return $this->estado;}
	public function getFechaIng()	  			{return $this->fecha_ing;}			
	public function getFechaMod()	  			{return $this->fecha_mod;}			
	public function getUsuarioIngId()			{return $this->usuario_ing_id;}		
	public function getUsuarioModId()			{return $this->usuario_mod_id;}

	//METODO SET
	public function setPersonaId($valor)			{$this->persona_id 			= $valor;} 
	public function setTipoProveedorId($valor)  	{$this->tipo_proveedor_id	= $valor;}
	public function setEstado($valor)				{$this->estado				= $valor;} 		
	public function setFechaIng($valor)				{$this->fecha_ing			= $valor;}		
	public function setFechaMod($valor)				{$this->fecha_mod			= $valor;}		
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id		= $valor;}		
	public function setUsuarioModId($valor)			{$this->usuario_mod_id		= $valor;}		

}//end class	