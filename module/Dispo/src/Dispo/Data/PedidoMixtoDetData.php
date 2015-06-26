<?php

namespace Dispo\Data;

class PedidoMixtoDetData
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



	//metodos GET
	public function getPedido_cabId() 				{return $this->pedido_cab_id;}
	public function getPedidoDetSec() 				{return $this->pedido_det_sec;}
	public function getInventarioId() 				{return $this->inventario_id;}
	public function getVariedadId() 				{return $this->variedad_id;}
	public function getGradoId() 					{return $this->grado_id;}
	public function getCantidadBunch() 				{return $this->cantidad_bunch;}
	public function getTallosxBunch() 				{return $this->tallos_x_bunch;}
	public function getTallosTotal() 				{return $this->tallos_total;}
	public function getPrecio() 					{return $this->precio;}
	public function getTotal() 						{return $this->total;}

	
	//metodos SET
	public function setPedidoCabId($valor) 			{$this->pedido_cab_id		= $valor;}
	public function setPedidoDetSec($valor) 		{$this->pedido_det_sec		= $valor;}
	public function setInventarioId($valor) 		{$this->inventario_id		= $valor;}
	public function setVariedadId($valor) 			{$this->variedad_id			= $valor;}
	public function setGradoId($valor) 				{$this->grado_id			= $valor;}
	public function setCantidadBunch($valor) 		{$this->cantidad_bunch		= $valor;}
	public function setTallosxBunch($valor) 		{$this->tallos_x_bunch		= $valor;}
	public function setTallosTotal($valor) 			{$this->tallos_total		= $valor;}
	public function setPrecio($valor) 				{$this->precio				= $valor;}
	public function setTotal($valor) 				{$this->total				= $valor;}


}//fin class

?>
