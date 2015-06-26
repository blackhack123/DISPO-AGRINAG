<?php

namespace Dispo\Data;

class PedidoDetAdicionalData
{
	/**
	 * @var int
	 */	
	protected $pedido_cab_id;

	/**
	 * @var int
	 */	
	protected $pedido_det_adicional_sec;

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
	protected $estado;


	/**
	 * @var string
	 */	
	protected $fecha_pedido;

	/**
	 * @var int
	 */	
	protected $usuario_cliente_id;


	/**
	 * @var int
	 */	
	protected $usuario_venta_id;

	/**
	 * @var int
	 */	
	protected $activo;

	//metodos GET
	public function getPedidoCabId() 						{return $this->pedido_cab_id;}
	public function getPedidoDetAdicionalSec() 				{return $this->pedido_det_adicional_sec;}
	public function getMarcacionSec() 						{return $this->marcacion_sec;}
	public function getInventarioId() 						{return $this->inventario_id;}
	public function getVariedadId() 						{return $this->variedad_id;}
	public function getGradoId() 							{return $this->grado_id;}
	public function getTipoCajaOrigenEstado() 				{return $this->tipo_caja_origen_estado;}
	public function getTipoCajaOrigenId() 					{return $this->tipo_caja_origen_id;}
	public function getNroCajas() 							{return $this->nro_cajas;}
	public function getCantidadBunch() 						{return $this->cantidad_bunch;}
	public function getTallosxBunch() 						{return $this->tallos_x_bunch;}
	public function getTallosTotal() 						{return $this->tallos_total;}
	public function getPrecio() 							{return $this->precio;}
	public function getTotal() 								{return $this->total;}
	public function getAgenciaCargaId() 					{return $this->agencia_carga_id;}
	public function getComentario() 						{return $this->comentario;}
	public function getEstado()								{return $this->estado;}
	public function getFechaPedido() 						{return $this->fecha_pedido;}
	public function getUsuarioClienteId() 					{return $this->usuario_cliente_id;}
	public function getUsuarioVentaId() 					{return $this->usuario_venta_id;}
	public function getActivo() 							{return $this->activo;}

	
	//metodos SET
	public function setPedidoCabId($valor) 					{$this->pedido_cab_id				= $valor;}
	public function setPedidoDetAdicionalSec($valor) 		{$this->pedido_det_adicional_sec	= $valor;}
	public function setMarcacionSec($valor) 				{$this->marcacion_sec				= $valor;}
	public function setInventarioId($valor) 				{$this->inventario_id				= $valor;}
	public function setVariedadId($valor) 					{$this->variedad_id					= $valor;}
	public function setGradoId($valor) 						{$this->grado_id					= $valor;}
	public function setTipoCajaOrigenEstado($valor) 		{$this->tipo_caja_origen_estado		= $valor;}
	public function setTipoCajaOrigenId($valor) 			{$this->tipo_caja_origen_id			= $valor;}
	public function setNroCajas($valor) 					{$this->nro_cajas					= $valor;}
	public function setCantidadBunch($valor) 				{$this->cantidad_bunch				= $valor;}
	public function setTallosxBunch($valor) 				{$this->tallos_x_bunch				= $valor;}
	public function setTallosTotal($valor) 					{$this->tallos_total				= $valor;}
	public function setPrecio($valor) 						{$this->precio						= $valor;}
	public function setTotal($valor) 						{$this->total						= $valor;}
	public function setAgenciaCargaId($valor) 				{$this->agencia_carga_id			= $valor;}
	public function setComentario($valor) 					{$this->comentario					= $valor;}
	public function setEstado($valor) 						{$this->estado						= $valor;}
	public function setFechaPedido($valor) 					{$this->fecha_pedido				= $valor;}
	public function setUsuarioClienteId($valor) 			{$this->usuario_cliente_id			= $valor;}
	public function setUsuarioVentaId($valor) 				{$this->usuario_venta_id			= $valor;}
	public function setActivo($valor) 						{$this->activo						= $valor;}
	

}//fin class

?>
