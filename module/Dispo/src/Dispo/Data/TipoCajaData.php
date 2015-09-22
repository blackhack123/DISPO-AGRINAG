<?php

namespace Dispo\Data;

class TipoCajaData
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
	protected $tipo_caja_homologada_id;

	/**
	 * @var int
	 */	
	protected $orden;
	
	
	/**
	 * @var float
	 */
	protected $ancho;
	
	
	/**
	 * @var float
	 */
	protected $alto;
	
	
	/**
	 * @var float
	 */
	protected $largo;
	
	
	/**
	 * @var string
	 */
	protected $url_foto;
	
	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getTipoCajaHomologadaId() 		{return $this->tipo_caja_homologada_id;}
	public function getOrden() 						{return $this->orden;}
	public function getAncho() 						{return $this->ancho;}
	public function getAlto() 						{return $this->alto;}
	public function getLargo() 						{return $this->largo;}
	public function getUrlFoto() 					{return $this->url_foto;}

	
	//metodos SET
	public function setId($valor) 							{$this->id							= $valor;}
	public function setNombre($valor) 						{$this->nombre						= $valor;}
	public function setTipoCajaHomologadaId($valor) 		{$this->tipo_caja_homologada_id		= $valor;}
	public function setOrden($valor) 						{$this->orden						= $valor;}
	public function setAncho($valor) 						{$this->ancho						= $valor;}
	public function setAlto($valor) 						{$this->alto						= $valor;}
	public function setLargo($valor) 						{$this->largo						= $valor;}
	public function setUrlFoto($valor) 						{$this->url_foto					= $valor;}
	

}//fin class

?>
