<?php

namespace General\Data;

/**
* TipoProveedorData.
*
*/
class TipoProveedorData
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
    protected $codigo_homologado_fox;

    /**
     * 
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

	//Metodos GET
	public function getId()					{return $this->id;}
	public function getNombre()				{return $this->nombre;}
	public function getCodigoHomologadoFox(){return $this->codigo_homologado_fox;}
	public function getEstado() 			{return $this->estado;}
	public function getFechaIng()			{return $this->fecha_ing;}
	public function getFechaMod() 			{return $this->fecha_mod;}
	public function getUsuarioIngId()		{return $this->usuario_ing_id;}
	public function getUsuarioModId()		{return $this->usuario_mod_id;}

	//Metodos SET
	public function setId($valor)					{$this->id 						= $valor;}
	public function setNombre($valor)				{$this->nombre					= $valor;}
	public function setCodigoHomologadoFox($valor)	{$this->codigo_homologado_fox 	= $valor;}
	public function setEstado($valor)				{$this->estado 					= $valor;}	
	public function setFechaIng($valor)				{$this->fecha_ing 				= $valor;}	
	public function setFechaMod($valor)				{$this->fecha_mod 				= $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id 			= $valor;}	
	public function setUsuarioModId($valor)			{$this->usuario_mod_id 			= $valor;}		

}//end class