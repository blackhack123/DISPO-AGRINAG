<?php

namespace Dispo\Data;

class TipoCajaMatrizData
{
	/**
	 * @var int
	 */	
	protected $id;

	/**
	 * @var string
	 */	
	protected $tipo_caja_id;

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
	protected $unds_bunch;


	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getTipoCajaId() 				{return $this->tipo_caja_id;}
	public function getInventarioId() 				{return $this->inventario_id;}
	public function getVariedadId() 				{return $this->variedad_id;}
	public function getGradoId() 					{return $this->grado_id;}
	public function getUndsBunch()					{return $this->unds_bunch;}


	//metodos SET
	public function setId($valor) 					{$this->id					= $valor;}
	public function setTipoCajaId($valor) 			{$this->tipo_caja_id		= $valor;}
	public function setInventarioId($valor) 		{$this->inventario_id		= $valor;}
	public function setVariedadId($valor) 			{$this->variedad_id			= $valor;}
	public function setGradoId($valor) 				{$this->grado_id			= $valor;}
	public function setUndsBunch($valor) 			{$this->unds_bunch			= $valor;}


}//fin class

?>
