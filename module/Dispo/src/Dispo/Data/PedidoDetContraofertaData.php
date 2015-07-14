<?php

namespace Dispo\Data;

class PedidoDetContraofertaData
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
	protected $fecha;


	/**
	 * @var float
	 */	
	protected $precio_original;


	/**
	 * @var float
	 */	
	protected $precio_contraoferta;


	/**
	 * @var string
	 */	
	protected $comentario;

	/**
	 * @var string
	 */	
	protected $estado;


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
	public function getPedidoDetSec() 						{return $this->pedido_det_sec;}
	public function getFecha() 								{return $this->fecha;}
	public function getPrecioOriginal()						{return $this->precio_original;}
	public function getPrecioContraoferta() 				{return $this->precio_contraoferta;}
	public function getComentario() 						{return $this->comentario;}
	public function getEstado() 							{return $this->estado;}
	public function getUsuarioClienteId()					{return $this->usuario_cliente_id;}
	public function getUsuarioVentaId() 					{return $this->usuario_venta_id;}
	public function getActivo() 							{return $this->activo;}
	
	//metodos SET
	public function setPedidoCabId($valor) 					{$this->pedido_cab_id			= $valor;}
	public function setPedidoDetSec($valor)					{$this->pedido_det_sec			= $valor;}
	public function setFecha($valor) 						{$this->fecha					= $valor;}
	public function setPrecioOriginal($valor) 				{$this->precio_original			= $valor;}
	public function setPrecioContraoferta($valor)			{$this->precio_contraoferta		= $valor;}
	public function setComentario($valor) 					{$this->comentario				= $valor;}
	public function setEstado($valor) 						{$this->estado					= $valor;}
	public function setUsuarioClienteId($valor) 			{$this->usuario_cliente_id		= $valor;}
	public function setUsuarioVentaId($valor) 				{$this->usuario_venta_id		= $valor;}
	public function setActivo($valor) 						{$this->activo					= $valor;}


}//fin class

?>
