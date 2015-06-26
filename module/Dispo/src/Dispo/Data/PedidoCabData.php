<?php

namespace Dispo\Data;

class PedidoCabData
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
	protected $cliente_id;

	/**
	 * @var float
	 */
	protected $total;

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
	protected $fec_exportado;

	/**
	 * @var string
	 */
	protected $fec_aprobado;
	
	/**
	 * @var string
	 */
	protected $fec_anulado;

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
	protected $usuario_cliente_id;

	/**
	 * @var int
	 */
	protected $usuario_aprobado_id;

	/**
	 * @var int
	 */
	protected $usuario_exportado_id;
		
	/**
	 * @var int
	 */
	protected $usuario_anulado_id;

	/**
	 * @var int
	 */
	protected $usuario_ing_id;
	
	/**
	 * @var int
	 */
	protected $usuario_mod_id;
	

	
	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getFecha() 						{return $this->fecha;}
	public function getClienteId() 					{return $this->cliente_id;}
	public function getTotal() 						{return $this->total;}
	public function getComentario() 				{return $this->comentario;}
	public function getEstado() 					{return $this->estado;}
	public function getFecAprobado()				{return $this->fec_aprobado;}	
	public function getFecExportado() 				{return $this->fec_exportado;}
	public function getFecAnulado()					{return $this->fec_anulado;}
	public function getFecIngreso()					{return $this->fec_ingreso;}
	public function getFecModifica()				{return $this->fec_modifica;}	
	public function getUsuarioClienteId() 			{return $this->usuario_cliente_id;}
	public function getUsuarioAprobadoId() 			{return $this->usuario_aprobado_id;}
	public function getUsuarioExportadoId() 		{return $this->usuario_exportado_id;}
	public function getUsuarioAnuladoId() 			{return $this->usuario_anulado_id;}
	public function getUsuarioIngId() 				{return $this->usuario_ing_id;}
	public function getUsuarioModId() 				{return $this->usuario_mod_id;}
	

	
	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setFecha($valor) 				{$this->fecha					= $valor;}
	public function setClienteId($valor) 			{$this->cliente_id				= $valor;}
	public function setTotal($valor) 				{$this->total					= $valor;}
	public function setComentario($valor) 			{$this->comentario				= $valor;}
	public function setEstado($valor) 				{$this->estado					= $valor;}
	public function setFecAprobado($valor)			{$this->fec_aprobado			= $valor;}	
	public function setFecExportado($valor) 		{$this->fec_exportado			= $valor;}
	public function setFecAnulado($valor)			{$this->fec_anulado				= $valor;}
	public function setFecIngreso($valor)			{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor)			{$this->fec_modifica			= $valor;}	
	public function setUsuarioClienteId($valor) 	{$this->usuario_cliente_id		= $valor;}
	public function setUsuarioAprobadoId($valor) 	{$this->usuario_aprobado_id		= $valor;}
	public function setUsuarioExportadoId($valor) 	{$this->usuario_exportado_id	= $valor;}
	public function setUsuarioAnuladoId($valor) 	{$this->usuario_anulado_id		= $valor;}
	public function setUsuarioIngId($valor) 		{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor) 		{$this->usuario_mod_id			= $valor;}
		

}//fin class

?>
