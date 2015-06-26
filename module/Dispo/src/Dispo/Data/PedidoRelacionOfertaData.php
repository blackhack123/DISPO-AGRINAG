<?php

namespace Dispo\Data;

class PedidoRelacionOfertaData
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
	protected $pedido_det_sec_item_hueso;

	
	//metodos GET
	public function getPedidoCabId() 					{return $this->pedido_cab_id;}
	public function getPedidoDetSec() 					{return $this->pedido_det_sec;}
	public function getPedidoDetSecItemHueso() 			{return $this->pedido_det_sec_item_hueso;}

	
	//metodos SET
	public function setPedidoCabId($valor) 				{$this->pedido_cab_id					= $valor;}
	public function setPedidoDetSec($valor) 			{$this->pedido_det_sec					= $valor;}
	public function setPedidoDetSecItemHueso($valor) 	{$this->pedido_det_sec_item_hueso		= $valor;}

}//fin class

?>
