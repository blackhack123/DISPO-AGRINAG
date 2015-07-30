<?php

namespace Dispo\Data;

class VariedadData
{
	/**
	 * @var string
	 */	
	protected $id;

	/**
	 * @var string
	 */	
	protected $nombre;
	
	/**
	 * @var string
	 */
	protected $nombre_tecnico;
	
	/**
	 * @var int
	 */
	protected $calidad_id;
	
	/**
	 * @var string
	 */
	protected $color;
	
	/**
	 * @var string
	 */
	protected $color2;
	
	/**
	 * @var string
	 */
	protected $grupo_color_id;

	/**
	 * @var string
	 */	
	protected $colorbase;
	
	/**
	 * @var string
	 */
	protected $solido;
	
	/**
	 * @var string
	 */
	protected $es_real;
	
	/**
	 * @var int
	 */
	protected $est_producto_especial;
	
	/**
	 * @var string
	 */
	protected $mensaje;
	
	/**
	 * @var string
	 */
	protected $cultivada;
	
	/**
	 * @var int
	 */
	protected $ciclo_prod;
	
	/**
	 * @var string
	 */
	protected $obtentor_id;
	
	/**
	 * @var string
	 */
	protected $producto_id;
	
	/**
	 * @var string
	 */
	protected $estado;
	
	/**
	 * @var string
	 */
	protected $fec_ingreso;
	

	/**
	 * @var string
	 */
	protected $fec_modifica;
	
	/**
	 * @var string
	 */
	protected $usuario_ing_id;
	
	/**
	 * @var string
	 */
	protected $usuario_mod_id;
	
	/**
	 * @var string
	 */
	protected $sincronizado;
	
	/**
	 * @var string
	 */
	protected $fec_sincronizado;
	
	

	//metodos GET
	public function getId() 					{return $this->id;}
	public function getNombre()			 		{return $this->nombre;}
	public function getNombreTecnico()	 		{return $this->nombre_tecnico;}
	public function getCalidadId()		 		{return $this->calidad_id;}
	public function getColor()			 		{return $this->color;}
	public function getColor2()			 		{return $this->color2;}
	public function getGrupoColorId()	 		{return $this->grupo_color_id;}
	public function getColorBase() 				{return $this->colorbase;}
	public function getSolido()			 		{return $this->solido;}
	public function getEsReal()			 		{return $this->es_real;}
	public function getEstProductoEspecial()	{return $this->est_producto_especial;}
	public function getMensaje()			 	{return $this->mensaje;}
	public function getCultivada()			 	{return $this->cultivada;}
	public function getCicloProd()		 		{return $this->ciclo_prod;}
	public function getObtentorId()		 		{return $this->obtentor_id;}
	public function getProductoId()		 		{return $this->producto_id;}
	public function getEstado() 				{return $this->estado;}
	public function getFecIngreso() 			{return $this->fec_ingreso;}
	public function getFecModifica() 			{return $this->fec_modifica;}
	public function getUsuarioIngId() 			{return $this->usuario_ing_id;}
	public function getUsuarioModId() 			{return $this->usuario_mod_id;}
	public function getSincronizado() 			{return $this->sincronizado;}
	public function getFecSincronizado() 		{return $this->fec_sincronizado;}


	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setNombre($valor) 				{$this->nombre					= $valor;}
	public function setNombreTecnico($valor) 		{$this->nombre_tecnico			= $valor;}
	public function setCalidadId($valor) 			{$this->calidad_id				= $valor;}
	public function setColor($valor) 				{$this->color					= $valor;}
	public function setColor2($valor) 				{$this->color2					= $valor;}
	public function setGrupoColorId($valor) 		{$this->grupo_color_id			= $valor;}
	public function setColorBase($valor) 			{$this->colorbase				= $valor;}
	public function setSolido($valor)		 		{$this->solido					= $valor;}
	public function setEsReal($valor)			 	{$this->es_real					= $valor;}
	public function setEstProductoEspecial($valor)	{$this->est_producto_especial	= $valor;}
	public function setMensaje($valor)			 	{$this->mensaje					= $valor;}
	public function setCultivada($valor)		 	{$this->cultivada				= $valor;}
	public function setCicloProd($valor)		 	{$this->ciclo_prod				= $valor;}
	public function setObtentorId($valor)		 	{$this->obtentor_id				= $valor;}
	public function setProductoId($valor)		 	{$this->producto_id				= $valor;}
	public function setEstado($valor) 				{$this->estado					= $valor;}
	public function setFecIngreso($valor) 			{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor) 			{$this->fec_modifica			= $valor;}
	public function setUsuarioIngId($valor) 		{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor) 		{$this->usuario_mod_id			= $valor;}
	public function setSincronizado($valor) 		{$this->sincronizado			= $valor;}
	public function setFecSincronizado($valor) 		{$this->fec_sincronizado		= $valor;}

}//end class

