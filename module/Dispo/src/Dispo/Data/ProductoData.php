<?php

namespace Dispo\Data;

class ProductoData
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
	protected $nombre_tec;
	
	/**
	 * @var string
	 */	
	protected $nombre_fam;

	/**
	 * @var string
	 */
	protected $tariff;
	
	/**
	 * @var string
	 */
	protected $tariff1;
	
	/**
	 * @var string
	 */
	protected $precio_adu;

	/**
	 * @var float
	 */
	protected $precio;
	
	/**
	 * @var string
	 */
	protected $unidad_id;
	
	/**
	 * @var int
	 */
	
	protected $unidad_caj;
	
	/**
	 * @var float
	 */
	protected $por_dump;
	
	/**
	 * @var float
	 */
	protected $por_nacional;
	
	/**
	 * @var int
	 */
	protected $secuencia;
	
	/**
	 * @var string
	 */
	protected $estado;
	
	/**
	 * @var int
	 */
	protected $diasA;
	
	/**
	 * @var int
	 */
	protected $diasB;
	
	/**
	 * @var int
	 */
	protected $diasC;
	
	/**
	 * @var int
	 */
	protected $diasM;
		
	/**
	 * @var int
	 */
	protected $diasN;
	
	/**
	 * @var string
	 */
	protected $solido;
	
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
	
	/**
	 * @var int
	 */
	protected $sincronizado;
	
	/**
	 * @var string
	 */
	protected $fec_sincronizado;
	
	
	//metodos set
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getNombreTec() 					{return $this->nombre_tec;}
	public function getNombreFam() 					{return $this->nombre_fam;}	
	public function getTariff() 					{return $this->tariff;}
	public function getTariff1() 					{return $this->tariff1;}
	public function getPrecioAdu() 					{return $this->precio_adu;}
	public function getPrecio() 					{return $this->precio;}
	public function getUnidadId()					{return $this->unidad_id;}
	public function getUnidadCaj()					{return $this->unidad_caj;}
	public function getPorDump() 					{return $this->por_dump;}
	public function getPorNacional() 				{return $this->por_nacional;}
	public function getSecuencia() 					{return $this->secuencia;}
	public function getEstado() 					{return $this->estado;}
	public function getDiasA() 						{return $this->diasA;}
	public function getDiasB() 						{return $this->diasB;}
	public function getDiasC() 						{return $this->diasC;}
	public function getDiasM()						{return $this->diasM;}
	public function getDiasN() 						{return $this->diasN;}
	public function getSolido() 					{return $this->solido;}
	public function getFecIngreso() 				{return $this->fec_ingreso;}
	public function getFecModifica() 				{return $this->fec_modifica;}
	public function getUsuarioIngId()				{return $this->usuario_ing_id;}
	public function getUsuarioModId()				{return $this->usuario_mod_id;}
	public function getSincronizado() 				{return $this->sincronizado;}
	public function getFecSincronizado() 			{return $this->fec_sincronizado;}
	
	//metodos SET
	public function setId($valor) 						{$this->id					= $valor;}
	public function setNombre($valor) 					{$this->nombre				= $valor;}
	public function setNombreTec($valor) 				{$this->nombre_tec			= $valor;}
	public function setNombreFam($valor) 				{$this->nombre_fam			= $valor;}	
	public function setTariff($valor) 					{$this->tariff				= $valor;}
	public function setTariff1($valor) 					{$this->tariff1				= $valor;}
	public function setPrecioAdu($valor) 				{$this->precio_adu			= $valor;}
	public function setPrecio($valor) 					{$this->precio				= $valor;}
	public function setUnidadId($valor)					{$this->unidad_id			= $valor;}
	public function setUnidadCaj($valor)				{$this->unidad_caj			= $valor;}
	public function setPorDump($valor) 					{$this->por_dump			= $valor;}
	public function setPorNacional($valor) 				{$this->por_nacional		= $valor;}
	public function setSecuencia($valor) 				{$this->secuencia			= $valor;}
	public function setEstado($valor) 					{$this->estado				= $valor;}
	public function setDiasA($valor) 					{$this->diasA				= $valor;}
	public function setDiasB($valor) 					{$this->diasB				= $valor;}
	public function setDiasC($valor) 					{$this->diasC				= $valor;}
	public function setDiasM($valor)					{$this->diasM				= $valor;}
	public function setDiasN($valor) 					{$this->diasN				= $valor;}
	public function setSolido($valor) 					{$this->solido				= $valor;}
	public function setFecIngreso($valor) 				{$this->fec_ingreso			= $valor;}
	public function setFecModifica($valor) 				{$this->fec_modifica		= $valor;}
	public function setUsuarioIngId($valor) 			{$this->usuario_ing_id		= $valor;}
	public function setUsuarioModId($valor) 			{$this->usuario_mod_id		= $valor;}
	public function setSinronizado($valor) 				{$this->sincronizado		= $valor;}
	public function setFecSincronizado($valor) 			{$this->fec_sincronizado	= $valor;}

	
}//fin class

?>
