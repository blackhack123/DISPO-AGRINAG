<?php

namespace Dispo\Data;

class DispoData
{
	/**
	 * @var int
	 */	
	protected $id;
	
	/**
	 * @var string
	 */	
	protected $fecha;
	
	/**
	 * @var string
	 */	
	protected $inventario_id;

	/**
	 * @var string
	 */	
	protected $fecha_bunch;

	/**
	 * @var string
	 */	
	protected $proveedor_id;

	/**
	 * @var string
	 */	
	protected $producto;	


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
	protected $tallos_x_bunch;

	/**
	 * @var string
	 */	
	protected $clasifica;

	/**
	 * @var int
	 */	
	protected $cantidad_bunch;

	/**
	 * @var int
	 */	
	protected $cantidad_bunch_disponible;


	//metodos GET
	public function getId() 								{return $this->id;}
	public function getFecha() 								{return $this->fecha;}
	public function getInventarioId() 						{return $this->inventario_id;}
	public function getFecha_bunch() 						{return $this->fecha_bunch;}
	public function getProveedor_id() 						{return $this->proveedor_id;}
	public function getProducto() 							{return $this->producto;}
	public function getVariedadId() 						{return $this->variedad_id;}
	public function getGradoId() 							{return $this->grado_id;}
	public function getTallosxbunch() 					{return $this->tallos_x_bunch;}
	public function getClasifica() 							{return $this->clasifica;}
	public function getCantidad_bunch() 					{return $this->cantidad_bunch;}
	public function getCantidadBunchDisponible() 			{return $this->cantidad_bunch_disponible;}



	//metodos SET
	public function setId($valor) 							{$this->id								= $valor;}
	public function setFecha($valor) 						{$this->fecha							= $valor;}
	public function setInventarioId($valor) 				{$this->inventario_id					= $valor;}
	public function setFechaBunch($valor) 					{$this->fecha_bunch						= $valor;}
	public function setProveedorId($valor) 				{	$this->getProveedor_id					= $valor;}
	public function setProducto($valor) 					{$this->producto						= $valor;}
	public function setVariedadId($valor) 					{$this->variedad_id						= $valor;}
	public function setGradoId($valor) 						{$this->grado_id						= $valor;}
	public function setTallosxBunch($valor) 				{$this->tallos_x_bunch					= $valor;}
	public function setClasifica($valor) 					{$this->clasifica						= $valor;}
	public function setCantidadBunch($valor) 				{$this->cantidad_bunch					= $valor;}
	public function setCantidadBunchDisponible($valor) 		{$this->cantidad_bunch_disponible		= $valor;}

}//fin class

?>
