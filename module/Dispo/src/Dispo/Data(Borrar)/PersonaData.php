<?php

namespace General\Data;

/**
* PersonaData.
*
*/
class PersonaData
{
    /**
     * @var int
     */
    protected $id;

	/**
     * @var string
	*/
    protected $tipo_identificacion;

	/**
     * @var string
	*/
    protected $nro_identificacion;	

	/**
     * @var string
	*/
    protected $nombre;	

    /**
     * @var string
     */
    protected $apellido;
        
	/**
     * @var string
	*/
    protected $fec_nacimiento;	
	
	/**
     * @var string
	*/
    protected $estado_civil;	
	
	/**
     * @var string
	*/
    protected $sexo;	
	
	
	//Las siguiente 2 variables no estan en la tabla, pero son necesarias para cuando se haga la carga del select
	private $pais_id;
	private $provincia_id;
	
	/**
     * @var int
	*/
    protected $localidad_id;	
	
	/**
     * @var string
	*/
    protected $direccion;	

	/**
     * @var string
	*/
    protected $telefono;	
	
	/**
     * @var string
	*/
    protected $movil;	
	
	/**
     * @var string
	*/
    protected $fax;	

	/**
     * @var string
	*/
    protected $email;	
	
	/**
     * @var string
	*/
    protected $url_sitioweb;	

	/**
     * @var int
	*/
    protected $estado_proveedor;	
	
	/**
     * @var int
	*/
    protected $estado_cliente;	

	/**
     * @var int
	*/
    protected $estado_empleado;	
    
    /**
     * @var int
     */
    protected $estado_juzgado;
    
    
    /**
     * @var int
     */
    protected $estado_notaria;
    
    
    /**
     * @var string
     */
    protected $url_foto;
    
    
    /**
     * @var int
     */
    protected $estado_abogado;
    
   
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
	public function getId()			  			{return $this->id;}					
	public function getTipoIdentificacion()	  	{return $this->tipo_identificacion;}
	public function getNroIdentificacion()	  	{return $this->nro_identificacion;}
	public function getNombre()				  	{return $this->nombre;}
	public function getApellido()				{return $this->apellido;}
	public function getApellidoNombre()		    {return $this->apellido.' '.$this->nombre;}	
	public function getFecNacimiento()		  	{return $this->fec_nacimiento;}
	public function getEstadoCivil()		  	{return $this->estado_civil;}
	public function getSexo()		  			{return $this->sexo;}
	public function getPaisId()		  			{return $this->pais_id;}		
	public function getProvinciaId()		  	{return $this->provincia_id;}		
	public function getLocalidadId()		  	{return $this->localidad_id;}		
	public function getDireccion()			  	{return $this->direccion;}
	public function getTelefono()			  	{return $this->telefono;}
	public function getMovil()				  	{return $this->movil;}	
	public function getFax()				  	{return $this->fax;}
	public function getEmail()				 	{return $this->email;}
	public function getUrlSitioWeb()		  	{return $this->url_sitioweb;}
	public function getEstadoProveedor()	  	{return $this->estado_proveedor;}
	public function getEstadoCliente()		  	{return $this->estado_cliente;}
	public function getUrlFoto()		  	    {return $this->url_foto;}
	public function getEstadoEmpleado()		  	{return $this->estado_empleado;}
	public function getEstadoAbogado()		  	{return $this->estado_abogado;}
	public function getEstadoJuzgado()		  	{return $this->estado_juzgado;}
	public function getEstadoNotaria()		  	{return $this->estado_notaria;}
	
	public function getEstado()		 			{return $this->estado;}				
	public function getFechaIng()	  			{return $this->fecha_ing;}			
	public function getFechaMod()	  			{return $this->fecha_mod;}			
	public function getUsuarioIngId()			{return $this->usuario_ing_id;}		
	public function getUsuarioModId()			{return $this->usuario_mod_id;}		


	//METODO SET
	public function setId($valor)					{$this->id 					= $valor;} 
	public function setTipoIdentificacion($valor)  	{$this->tipo_identificacion	= $valor;}
	public function setNroIdentificacion($valor)  	{$this->nro_identificacion	= $valor;}
	public function setNombre($valor)			  	{$this->nombre				= $valor;}
	public function setApellido($valor)				{$this->apellido			= $valor;}	
	public function setFecNacimiento($valor)		{$this->fec_nacimiento		= $valor;}
	public function setEstadoCivil($valor)		  	{$this->estado_civil		= $valor;}
	public function setSexo($valor)		  			{$this->sexo				= $valor;}	
	public function setPaisId($valor)		  		{$this->pais_id				= $valor;}		
	public function setProvinciaId($valor)		  	{$this->provincia_id		= $valor;}			
	public function setLocalidadId($valor)			{$this->localidad_id		= $valor;}	
	public function setDireccion($valor)		  	{$this->direccion			= $valor;}
	public function setTelefono($valor)			  	{$this->telefono			= $valor;}
	public function setMovil($valor)				{$this->movil				= $valor;}		
	public function setFax($valor)				  	{$this->fax					= $valor;}
	public function setEmail($valor)			 	{$this->email				= $valor;}
	public function setUrlSitioWeb($valor)		  	{$this->url_sitioweb		= $valor;}
	public function setEstadoProveedor($valor)	  	{$this->estado_proveedor	= $valor;}
	public function setEstadoCliente($valor)	  	{$this->estado_cliente		= $valor;}
	public function setUrlFoto($valor)	  	        {$this->url_foto		    = $valor;}
	public function setEstadoEmpleado($valor)	  	{$this->estado_empleado		= $valor;}
	public function setEstadoAbogado($valor)	  	{$this->estado_abogado		= $valor;}
	public function setEstadoJuzgado($valor)	  	{$this->estado_juzgado		= $valor;}
	public function setEstadoNotaria($valor)	  	{$this->estado_notaria		= $valor;}
	
	public function setEstado($valor)				{$this->estado				= $valor;} 		
	public function setFechaIng($valor)				{$this->fecha_ing			= $valor;}		
	public function setFechaMod($valor)				{$this->fecha_mod			= $valor;}		
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id		= $valor;}		
	public function setUsuarioModId($valor)			{$this->usuario_mod_id		= $valor;}		
	
}//end class	