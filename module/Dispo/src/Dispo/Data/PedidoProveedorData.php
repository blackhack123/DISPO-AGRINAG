<?php

namespace Dispo\Data;

class PedidoProveedorData
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
	 * @var string
	 */	
	protected $proveedor_id;

	/**
	 * @var int
	 */	
	protected $nro_cajas;

	/**
	 * @var float
	 */
	protected $eq_fb;
	
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
	 * @var string
	 */	
	protected $variedad_id;

	/**
	 * @var string
	 */	
	protected $grado_id;


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
	protected $fec_exportado;
	
	//metodos GET

	public function getPedidoCabId() 					{return $this->pedido_cab_id;}
	public function getPedidoDetSec() 					{return $this->pedido_det_sec;}
	public function getProveedorId() 					{return $this->proveedor_id;}
	public function getNroCajas() 						{return $this->nro_cajas;}
	public function getEqFb()							{return $this->eq_fb;}
	public function getCantidadBunch() 					{return $this->cantidad_bunch;}
	public function getTallosxBunch() 					{return $this->tallos_x_bunch;}
	public function getTallosTotal() 					{return $this->tallos_total;}
	public function getVariedadId() 					{return $this->variedad_id;}
	public function getGradoId() 						{return $this->grado_id;}
	public function getPrecio() 						{return $this->precio;}
	public function getTotal() 							{return $this->total;}
	public function getFecExportado() 					{return $this->fec_exportado;}



	//metodos SET
	public function setPedidoCabId($valor) 				{$this->pedido_cab_id		= $valor;}
	public function setPedidoDetSec($valor) 			{$this->pedido_det_sec		= $valor;}
	public function setProveedorId($valor) 				{$this->proveedor_id		= $valor;}
	public function setNroCajas($valor) 				{$this->nro_cajas			= $valor;}
	public function setEqFb($valor)						{$this->eq_fb				= $valor;}	
	public function setCantidadBunch($valor)			{$this->cantidad_bunch		= $valor;}
	public function setTallosxBunch($valor)				{$this->tallos_x_bunch		= $valor;}
	public function setTallosTotal($valor) 				{$this->tallos_total		= $valor;}
	public function setVariedadId($valor) 				{$this->variedad_id			= $valor;}
	public function setGradoId($valor) 					{$this->grado_id			= $valor;}
	public function setPrecio($valor) 					{$this->precio				= $valor;}
	public function setTotal($valor) 					{$this->total				= $valor;}
	public function setFecExportado($valor) 			{$this->fec_exportado		= $valor;}

}//fin class

?>
