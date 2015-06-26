<?php

namespace Seguridad\Data;

/**
* PerfilData.
*
*/
class OpcionAccionData 
{
    /**
     * @var int
     */
    private $dispositivo_id;
    
    /**
     * @var int
     */
    private $opcion_id;
    
    
    /**
     * @var int
     */
    private $accion_id;
     
  
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

	//Get
	public function getId()					    {return $this->dispositivo_id;}
	public function getOpcionId()			    {return $this->opcion_id;}
	public function getAccionId()		  	    {return $this->accion_id;}
	
	public function getFechaIng()			    {return $this->fecha_ing;}
	public function getFechaMod()			    {return $this->fecha_mod;}
	public function getUsuarioIngId()		    {return $this->usuario_ing_id;}
	public function getUsuarioModId()		    {return $this->usuario_mod_id;}

	//Set
	public function setDispositivoId($valor)	{$this->dispositivo_id  = $valor;}
	public function setModuloId($valor)		    {$this->opcion_id 	    = $valor;}
	public function setNombre($valor)		    {$this->accion_id		= $valor;}
	
	public function setFechaIng($valor)		    {$this->fecha_ing		= $valor;}
	public function setFechaMod($valor)		    {$this->fecha_mod		= $valor;}	
	public function setUsuarioIngId($valor)	    {$this->usuario_ing_id	= $valor;}
	public function setUsuarioModId($valor)	    {$this->usuario_mod_id	= $valor;}
	
}//end class	