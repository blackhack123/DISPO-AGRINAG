<?php

namespace General\Data;

/**
* UsuarioData.
*
*/
class GrupoEmpresarialData
{
    /**
     * @var int
     */
    private $id;
	
    /**
     * @var string
     */
    private $nombre_grupo;

    /**
     * @var string
     */
    private $mascara_contable;

    /**
     * @var int
     */
    private $nro_nivel_contable;

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

	
	public function getId()					  	{return $this->id;}
	public function getNombreGrupo()		  	{return $this->nombre_grupo;}	
	public function getMascaraContable()	 	{return $this->mascara_contable;}	
	public function getNroNivelContable()	  	{return $this->nro_nivel_contable;}
	public function getEstado()					{return $this->estado;}
	public function getFechaIng()				{return $this->fecha_ing;}
	public function getFechaMod()				{return $this->fecha_mod;}
	public function getUsuarioIngId()			{return $this->usuario_ing_id;}
	public function getUsuarioModId()			{return $this->usuario_mod_id;}

	public function setId($valor)					{$this->id 					= $valor;}
	public function setNombreGrupo($valor)			{$this->nombre_grupo		= $valor;}	
	public function setMascaraContable($valor)		{$this->mascara_contable	= $valor;}	
	public function setNroNivelContable($valor)		{$this->nro_nivel_contable 	= $valor;}	
	public function setEstado($valor)				{$this->estado				= $valor;}
	public function setFechaIng($valor)				{$this->fecha_ing			= $valor;}
	public function setFechaMod($valor)				{$this->fecha_mod			= $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id		= $valor;}
	public function setUsuarioModId($valor)			{$this->usuario_mod_id		= $valor;}
	
}//end class	