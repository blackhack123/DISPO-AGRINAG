<?php

namespace General\Data;

/**
* PersonaDocDigitalData.
*
*/
class PersonaDocDigitalData
{
    /**
     * @var int
     */
    protected $persona_id;

    
    /**
     * @var int
     */
    protected $doc_digital_sec;
    
    
    /**
     * @var int
     */
    protected $tipo_doc_digital;
    
    

    /**
     * @var string
     */
    protected $url;
    
    
	
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
	public function getPersonaId()			    {return $this->persona_id;}
	public function getDocDigitalSec()			{return $this->doc_digital_sec;}
	public function getTipoDocDigital()			{return $this->tipo_doc_digital;}
	public function getUrl()			        {return $this->url;}
	
	
	public function getFechaIng()			    {return $this->fecha_ing;}
	public function getFechaMod() 			    {return $this->fecha_mod;}
	public function getUsuarioIngId()		    {return $this->usuario_ing_id;}
	public function getUsuarioModId()		    {return $this->usuario_mod_id;}

	//Metodos SET
	public function setPersonaId($valor)		{$this->persona_id 			= $valor;}
	public function setDocDigitalSec($valor)	{$this->doc_digital_sec	    = $valor;}
	public function setTipoDocDigital($valor)	{$this->tipo_doc_digital	= $valor;}
	public function setUrl($valor)	            {$this->url	= $valor;}
	
	public function setFechaIng($valor)			{$this->fecha_ing 			= $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod 			= $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id 		= $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id 		= $valor;}		

	
}//end class	