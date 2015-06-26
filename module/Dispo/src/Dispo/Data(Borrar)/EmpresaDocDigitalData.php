<?php

namespace General\Data;

/**
* EmpresaDocDigitalData.
*
*/
class EmpresaDocDigitalData
{
    /**
     * @var int
     */
    protected $empresa_id;

    
    /**
     * @var int
     */
    protected $doc_digital_sec;
    
    
    /**
     * @var int
     */
    protected $tipo_doc_digital_id;
     
    
   
    /**
     * @var string
     */
    protected $asunto;

    
    /**
     * @var string
     */
    protected $ubicacion_custodia;
    

    /**
     * @var string
     */
    protected $url;
     
    
    /**
     * @var string
     */
    protected $accion;
    
    
    
    /**
     * @var int
     */
    protected $notaria_id;

    
    /**
     * @var string
     */
    protected $fec_otorgamiento;
    
    
    /**
     * @var string
     */
    protected $fec_inscripcion_reg_mercantil;
    

    
    /**
     * @var string
     */
    protected $observacion;
     
    
    
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
	public function getEmpresaId()			    		{return $this->empresa_id;}
	public function getDocDigitalSec()					{return $this->doc_digital_sec;}
	public function getTipoDocDigitalId()				{return $this->tipo_doc_digital_id;}
	public function getAsunto()			        		{return $this->asunto;}
	public function getUbicacionCustodia()	    		{return $this->ubicacion_custodia;}
	public function getUrl()	                		{return $this->url;}
	public function getNotariaId()	            		{return $this->notaria_id;}
	public function getFecOtorgamiento()	    		{return $this->fec_otorgamiento;}
	public function getFecInscripcionRegMercantil()	    {return $this->fec_inscripcion_reg_mercantil;}
	public function getObservacion()	                {return $this->observacion;}
	public function getAccion()	                        {return $this->accion;}

	public function getFechaIng()			            {return $this->fecha_ing;}
	public function getFechaMod() 			            {return $this->fecha_mod;}
	public function getUsuarioIngId()		            {return $this->usuario_ing_id;}
	public function getUsuarioModId()		            {return $this->usuario_mod_id;}

	//Metodos SET
    public function setEmpresaId($valor)			    {$this->empresa_id         = $valor;}
	public function setDocDigitalSec($valor)			{$this->doc_digital_sec    = (empty($valor) ? null : $valor);}
	public function setTipoDocDigitalId($valor)			{$this->tipo_doc_digital_id= $valor;}
	public function setAsunto($valor)			        {$this->asunto             = $valor;}
	public function setUbicacionCustodia($valor)	    {$this->ubicacion_custodia = $valor;}
	public function setUrl($valor)	                	{$this->url= $valor;}
	public function setNotariaId($valor)	            {$this->notaria_id= $valor;}
	public function setFecOtorgamiento($valor)	    	{$this->fec_otorgamiento= $valor;}
	public function setFecInscripcionRegMercantil($valor){$this->fec_inscripcion_reg_mercantil= (empty($valor) ? null : $valor);}
	public function setObservacion($valor)	            {$this->observacion= $valor;}
	public function setAccion($valor)	                {$this->accion=$valor;}
	
	public function setFechaIng($valor)					{$this->fecha_ing 			= $valor;}	
	public function setFechaMod($valor)					{$this->fecha_mod 			= $valor;}	
	public function setUsuarioIngId($valor)				{$this->usuario_ing_id 		= $valor;}	
	public function setUsuarioModId($valor)				{$this->usuario_mod_id 		= $valor;}		

	
}//end class	