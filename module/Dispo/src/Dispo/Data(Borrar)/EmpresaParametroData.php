<?php

namespace General\Data;

/**
* EmpresaParametroData.
*
*/
class EmpresaParametroData
{
    /**
     * @var int
     */
    private $empresa_id;

    /**
     * @var int
     */
    private $parametro_id;
    
    
	/**
     * @var string
	*/
    private $descripcion;

	/**
     * @var string
	*/
    private $tipo;
	
	  /**
     * @var float
     */
    private $valor_numerico;

	/**
     * @var string
	*/
    private $valor_texto;

    
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
	public function getEmpresaId()				    {return $this->empresa_id;}
	public function getParametroId()			    {return $this->parametro_id;}
	public function getDescripcion()		        {return $this->descripcion;}
	public function getTipo()		                {return $this->tipo;}
	public function getValorNumerico()			    {return $this->valor_numerico;}
	public function getValorTexto()			        {return $this->valor_texto;}
	public function getFechaIng()			        {return $this->fecha_ing;}
	public function getFechaMod() 			        {return $this->fecha_mod;}
	public function getUsuarioIngId()		        {return $this->usuario_ing_id;}
	public function getUsuarioModId()		        {return $this->usuario_mod_id;}

	//Metodos SET
	public function setEmpresaId($valor)			{$this->empresa_id 		= $valor;}
	public function setParametroId($valor)		    {$this->parametro_id	= $valor;}
	public function setDescripcion($valor)			{$this->descripcion	    = $valor;}
	public function setTipo($valor)		            {$this->tipo            = $valor;}
	public function setValorNumerico($valor)	    {$this->valor_numerico	= $valor;}
	public function setValorTexto($valor)			{$this->valor_texto		= $valor;}
	public function setFechaIng($valor)				{$this->fecha_ing 		= $valor;}	
	public function setFechaMod($valor)				{$this->fecha_mod 		= $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id 	= $valor;}	
	public function setUsuarioModId($valor)			{$this->usuario_mod_id 	= $valor;}		
	
}//end class	