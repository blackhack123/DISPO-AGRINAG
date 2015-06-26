<?php

namespace Dispo\Data;

class PedidoDetData
{
	/**
	 * @var int
	 */	
	 protected $pedido_cab_id;

	/**
	 * @var int
	 */	
	 protected $pedido_det_sec;


	/**
	 * @var int
	 */	
	 protected $marcacion_sec;


	/**
	 * @var string
	 */	
	 protected $inventario_id;


	/**
	 * @var string
	 */	
	protected $variedad_id;

	/**
	 * @var string
	 */	
	protected $grado_id;

	/**
	 * @var string
	 */	
	protected $tipo_caja_id;


	/**
	 * @var string
	 */	
	protected $tipo_caja_origen_estado;

	/**
	 * @var string
	 */	
	protected $tipo_caja_origen_id;


	/**
	 * @var int
	 */	
	protected $nro_cajas;


	/**
	 * @var int
	 */	
	protected $cantidad_bunch;


	/**
	 * @var int
	 */	
	protected $tallos_x_bunch;


	/**
	 * @var int
	 */	
	protected $tallos_total;


	/**
	 * @var float
	 */	
	protected $precio;


	/**
	 * @var float
	 */	
	protected $total;


	/**
	 * @var string
	 */	
	protected $agencia_carga_id;


	/**
	 * @var string
	 */	
	protected $comentario;

	/**
	 * @var string
	 */	
	protected $sec;


	/**
	 * @var string
	 */	
	protected $secc;

	/**
	 * @var int
	 */	
	protected $pedido_cab_oferta_id;


	/**
	 * @var int
	 */	
	protected $pedido_det_oferta_sec;


	/**
	 * @var int
	 */	
	protected $estado_precio_contraoferta;


	/**
	 * @var int
	 */	
	protected $estado_pedido_adicional;


	/**
	 * @var int
	 */	
	protected $estado_mixto;


	/**
	 * @var string
	 */	
	protected $estado_reg_oferta;
	

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
	public function getPedidoCabId() 								{return $this->pedido_cab_id;}
	public function getPedidoDetSec() 								{return $this->pedido_det_sec;}
	public function getMarcacionSec() 								{return $this->marcacion_sec;}
	public function getInventarioId() 								{return $this->inventario_id;}
	public function getVariedadId() 								{return $this->variedad_id;}
	public function getGradoId() 									{return $this->grado_id;}
	public function getTipoCajaId() 								{return $this->tipo_caja_id;}
	public function getTipoCajaOrigenEstado() 						{return $this->tipo_caja_origen_estado;}
	public function getTipoCajaOrigenId() 							{return $this->tipo_caja_origen_id;}
	public function getNroCajas() 									{return $this->nro_cajas;}
	public function getCantidadBunch() 								{return $this->cantidad_bunch;}
	public function getTallosxBunch() 								{return $this->tallos_x_bunch;}
	public function getTallosTotal()							 	{return $this->tallos_total;}
	public function getPrecio() 									{return $this->precio;}
	public function getTotal() 										{return $this->total;}
	public function getAgenciaCargaId() 							{return $this->agencia_carga_id;}
	public function getComentario() 								{return $this->comentario;}
	public function getSec() 										{return $this->sec;}
	public function getSecc()										{return $this->secc;}
	public function getPedidoCabOfertaId()	   				    	{return $this->pedido_ab_oferta_id;}
	public function getPedidoDetOfertaSec() 						{return $this->pedido_det_oferta_sec;}
	public function getEstadoPrecioContraoferta() 					{return $this->estado_precio_contraoferta;}
	public function getEstadoPedidoAdicional() 						{return $this->estado_pedido_adicional;}
	public function getEstadoMixto() 								{return $this->estado_mixto;}
	public function getEstadoRegOferta() 							{return $this->estado_reg_oferta;}
	public function getFecIngreso() 								{return $this->fec_ingreso;}
	public function getFecModifica() 								{return $this->fec_modifica;}
	public function getUsuarioIngId() 								{return $this->usuario_ing_id;}
	public function getUsuarioModId() 								{return $this->usuario_mod_id;}


	//metodos SET
	public function setPedidoCabId($valor) 							{$this->pedido_cab_id 				= $valor;}
	public function setPedidoDetSec($valor) 						{$this->pedido_det_sec 				= $valor;}
	public function setMarcacionSec($valor) 						{$this->marcacion_sec 				= $valor;}
	public function setInventarioId($valor) 						{$this->inventario_id 				= $valor;}
	public function setVariedadId($valor) 							{$this->variedad_id 				= $valor;}
	public function setGradoId($valor) 								{$this->grado_id 					= $valor;}
	public function setTipoCajaId($valor) 							{$this->tipo_caja_id 				= $valor;}
	public function setTipoCajaOrigenEstado($valor) 				{$this->tipo_caja_origen_estado		= $valor;}
	public function setTipoCajaOrigenId($valor) 					{$this->tipo_caja_origen_id			= $valor;}
	public function setNroCajas($valor) 							{$this->nro_cajas 					= $valor;}
	public function setCantidadBunch($valor) 						{$this->cantidad_bunch				= $valor;}
	public function setTallosxBunch($valor) 						{$this->tallos_x_bunch				= $valor;}
	public function setTallosTotal($valor)						 	{$this->tallos_total 				= $valor;}
	public function setPrecio($valor) 								{$this->precio 						= $valor;}
	public function setTotal($valor) 								{$this->total 						= $valor;}
	public function setAgenciaCargaId($valor) 						{$this->agencia_carga_id 			= $valor;}
	public function setComentario($valor) 							{$this->comentario 					= $valor;}
	public function setSec($valor) 									{$this->sec 						= $valor;}
	public function setSecc($valor)									{$this->secc 						= $valor;}
	public function setPedidoCabOfertaId($valor)	   		    	{$this->pedido_ab_oferta_id 		= $valor;}
	public function setPedidoDetOfertaSec($valor) 					{$this->pedido_det_oferta_sec 		= $valor;}
	public function setEstadoPrecioContraoferta($valor) 			{$this->estado_precio_contraoferta 	= $valor;}
	public function setEstadoPedidoAdicional($valor) 				{$this->estado_pedido_adicional 	= $valor;}
	public function setEstadoMixto($valor) 							{$this->estado_mixto 				= $valor;}
	public function setEstadoRegOferta($valor) 						{$this->estado_reg_oferta 			= $valor;}
	public function setFecIngreso($valor) 							{$this->fec_ingreso 				= $valor;}
	public function setFecModifica($valor) 							{$this->fec_modifica 				= $valor;}
	public function setUsuarioIngId($valor) 						{$this->usuario_ing_id 				= $valor;}
	public function setUsuarioModId($valor) 						{$this->usuario_mod_id 				= $valor;}
	
}//fin class

?>
