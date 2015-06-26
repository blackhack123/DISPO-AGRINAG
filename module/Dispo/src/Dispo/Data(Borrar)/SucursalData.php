<?php

namespace General\Data;

/**
* PaisData.
*
*/
class SucursalData
{
    /**
     * @var int
     */
    protected $id;

	/**
     * @var string
	*/
    protected $nombre_largo;

    /**
     * @var string
     */
    protected $nombre_corto;
    
    /**
     * @var int
     */
    protected $sucursal_homologada_id;    

    /**
     * @var int
     */
    protected $estado_mo_equipo_menor;
	
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
	
	//Metodos GET
	public function getId()					    {return $this->id;}
	public function getNombreLargo()		    {return $this->nombre_largo;}
    public function getNombreCorto()	        {return $this->nombre_corto;}
    public function getSucursalHomologadaId()	{return $this->sucursal_homologada_id;}
    public function getEstadoMoEquipoMenor()	{return $this->estado_mo_equipo_menor;}
	public function getEstado() 			    {return $this->estado;}
	public function getFechaIng()			    {return $this->fecha_ing;}
	public function getFechaMod() 			    {return $this->fecha_mod;}
	public function getUsuarioIngId()		    {return $this->usuario_ing_id;}
	public function getUsuarioModId()		    {return $this->usuario_mod_id;}

	//Metodos SET
	public function setId($valor)				{$this->id 					= $valor;}
	public function setNombreLargo($valor)		{$this->nombre_largo	    = $valor;}
	public function setNombreCorto($valor)		{$this->nombre_corto	    = $valor;}
	public function setMonedaNacionalId($valor)	{$this->moneda_nacional_id 	= $valor;}	
	public function setSucursalHomologadaId($valor)	{$this->sucursal_homologada_id	= $valor;}	
	public function setEstadoMoEquipoMenor($valor)	{$this->estado_mo_equipo_menor	= $valor;}	
	public function setEstado($valor)			{$this->estado 				= $valor;}	
	public function setFechaIng($valor)			{$this->fecha_ing 			= $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod 			= $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id 		= $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id 		= $valor;}		

	
}//end class	