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

	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getTipoCajaHomologadaId() 		{return $this->tipo_caja_homologada_id;}
	public function getOrden() 						{return $this->orden;}

	
	//metodos SET
	public function setId($valor) 							{$this->id							= $valor;}
	public function setNombre($valor) 						{$this->nombre						= $valor;}
	public function setTipoCajaHomologadaId($valor) 		{$this->tipo_caja_homologada_id		= $valor;}
	public function setOrden($valor) 						{$this->orden						= $valor;}

}//fin class

?>
