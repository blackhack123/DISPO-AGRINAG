<?php

namespace General\Data;

/**
* EmpresaSucursalData.
*
*/
class EmpresaSucursalData
{
    /**
     * @var int
     */
    private $empresa_id;

    /**
     * @var int
     */
    private $sucursal_id;
  
	/**
     * @var string
	*/
    private $direccion;
	
	/**
     * @var string
	*/
    private $telefonos;
    
    /**
     * @var int
     */
    private $localidad_id;

	/**
     * @var int
	*/
    private $estado_sucursal;

    /**
     * @var int
     */
    private $estado_obra;
	
    
    /**
     * @var string
     */
    private $nombre_obra_contrato;
    
	/**
     * @var string
	*/
    private $sri_nro_establecimiento;
    
    /**
     * var @int
     */
    private $sri_nro_guia_remision;

    /**
     * var @int
     */
    protected $residente_obra_id;	
    
    
    /**
     * @var string
     */
    private $firma_encargado_nombre;
    
    
    /**
     * @var string
     */
    private $firma_cargo_nombre;
    
    
    /**
     * @var string
     */
    private $estado_maestro_obligatorio;
    
    
    
    
	/**
     * @var string
	*/
    private $estado;

	/**
     * @var string
	*/
    private $fecha_ing;

	/**
     * @var string
	*/
    private $fecha_mod;
	
	/**
     * @var int
	*/
    private $usuario_ing_id;

	/**
     * @var int
	*/
    private $usuario_mod_id;
	


	/*------------------------------------------------------------------------------*/
	/*------------------------------- METODOS GET y SET ----------------------------*/
	/*------------------------------------------------------------------------------*/

	//Metodos GET
	public function getEmpresaId()					{return $this->empresa_id;}
	public function getSucursalId()					{return $this->sucursal_id;}
	public function getDireccion()				    {return $this->direccion;}
	public function getTelefonos()		            {return $this->telefonos;}
	public function getLocalidadId()		        {return $this->localidad_id;}
	public function getEstadoSucursal()			    {return $this->estado_sucursal;}
	public function getEstadoObra()			        {return $this->estado_obra;}
	public function getNombreObraContrato()			{return $this->nombre_obra_contrato;}
	public function getSriNroEstablecimiento() 		{return $this->sri_nro_establecimiento;}
	public function getSriNroGuiaRemision()			{return $this->sri_nro_guia_remision;}
	public function getResidenteObraId()			{return $this->residente_obra_id;}
	public function getFirmaEncargadoNombre()		{return $this->firma_encargado_nombre;}
	public function getFirmaCargoNombre()		    {return $this->firma_cargo_nombre;}
	public function getEstadoMaestroObligatorio()   {return $this->estado_maestro_obligatorio;}
	public function getEstado() 			        {return $this->estado;}
	public function getFechaIng()			        {return $this->fecha_ing;}
	public function getFechaMod() 			        {return $this->fecha_mod;}
	public function getUsuarioIngId()		        {return $this->usuario_ing_id;}
	public function getUsuarioModId()		        {return $this->usuario_mod_id;}

	//Metodos SET
	public function setEmpresaId($valor)		    {$this->empresa_id 				= $valor;}
	public function setSucursalId($valor)			{$this->sucursal_id 			= $valor;}
	public function setDireccion($valor)			{$this->direccion	            = $valor;}
	public function setTelefonos($valor)		    {$this->telefonos               = $valor;}
	public function setLocalidad_id($valor)			{$this->localidad_id		    = $valor;}
	public function setEstadoSucursal($valor)		{$this->estado_sucursal		    = $valor;}
	public function setNombreObraContrato($valor)	{$this->nombre_obra_contrato    = $valor;}
	public function setSriNroEstablecimiento($valor){$this->sri_nro_establecimiento	= $valor;}
	public function setSriNroGuiaRemision($valor)	{$this->sri_nro_guia_remision	= $valor;}
	public function setResidenteObraId($valor)		{$this->residente_obra_id		= $valor;}	
	public function setFirmaEncargadoNombre($valor)	{$this->firma_encargado_nombre  = $valor;}
	public function setFirmaCargoNombre($valor)		{$this->firma_cargo_nombre      = $valor;}	
	public function setEstadoMaestroObligatorio($valor){$this->estado_maestro_obligatorio= (empty($valor) ? 0 : $valor);}
	public function setEstadoObra($valor)		    {$this->estado_obra		        = $valor;}
	public function setEstado($valor)				{$this->estado 			        = $valor;}	
	public function setFechaIng($valor)				{$this->fecha_ing 		        = $valor;}	
	public function setFechaMod($valor)				{$this->fecha_mod 		        = $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id 	        = $valor;}	
	public function setUsuarioModId($valor)			{$this->usuario_mod_id 	        = $valor;}		
	
}//end class	