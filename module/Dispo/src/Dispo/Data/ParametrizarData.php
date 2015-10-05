<?php

namespace Dispo\Data;

class ParametrizarData
{
	/**
	 * @var string
	 */	
	protected $id;

	/**
	 * @var string
	 */	
	protected $descripcion;
	
	/**
	 * @var string
	 */
	protected $tipo;
	
	/**
	 * @var string
	 */
	protected $valor_texto;
	
	/**
	 * @var float
	 */
	protected $valor_numerico;
	
	
	/**
	 * @var string
	 */
	protected $observacion;
	
	/**
	 * @var string
	 */
	protected $fec_ingreso;
	

	/**
	 * @var string
	 */
	protected $fec_modifica;
	
	/**
	 * @var int
	 */
	protected $usuario_ing_id;
	
	/**
	 * @var int
	 */
	protected $usuario_mod_id;
	

	//metodos GET
	public function getId() 					{return $this->id;}
	public function getDescripcion()			{return $this->descripcion;}
	public function getTipo()	 				{return $this->tipo;}
	public function getValorTexto()				{return $this->valor_texto;}
	public function getValorNumerico()			{return $this->valor_numerico;}
	public function getObservacion()			{return $this->observacion;}
	public function getFecIngreso() 			{return $this->fec_ingreso;}
	public function getFecModifica() 			{return $this->fec_modifica;}
	public function getUsuarioIngId() 			{return $this->usuario_ing_id;}
	public function getUsuarioModId() 			{return $this->usuario_mod_id;}


	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setDescripcion($valor) 			{$this->descripcion				= $valor;}
	public function setTipo($valor) 				{$this->tipo					= $valor;}
	public function setValorTexto($valor) 			{$this->valor_texto				= $valor;}
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
	public function setTamanoBunchId($valor)		{$this->tamano_bunch_id			= $valor;}
	public function setProductoId($valor)		 	{$this->producto_id				= $valor;}
	public function setColorVentasId($valor)		{$this->color_ventas_id			= $valor;}
	public function setUrlFicha($valor)				{$this->url_ficha				= $valor;}
	public function setEstado($valor) 				{$this->estado					= $valor;}
	public function setFecIngreso($valor) 			{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor) 			{$this->fec_modifica			= $valor;}
	public function setUsuarioIngId($valor) 		{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor) 		{$this->usuario_mod_id			= $valor;}
	public function setSincronizado($valor) 		{$this->sincronizado			= $valor;}
	public function setFecSincronizado($valor) 		{$this->fec_sincronizado		= $valor;}

}//end class

