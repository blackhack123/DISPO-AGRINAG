<?php

namespace Dispo\Data;

class GrupoDispoDetData
{


	/**
	 * @var int
	 */	
	protected $grupo_dispo_cab_id;

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
	protected $cantidad_bunch_disponible;

	/**
	 * @var int
	 */	
	protected $usuario_mod_id;

	/**
	 * @var string
	 */	
	protected $fecha_mod;



	//metodos GET
public function getGrupoDispoCabId() 						{return $this->grupo_dispo_cab_id;}
public function getVariedadId() 							{return $this->variedad_id;}
public function getGradoId() 								{return $this->grado_id;}
public function getCantidadBunch() 							{return $this->cantidad_bunch;}
public function getCantidadBunchDisponible() 				{return $this->cantidad_bunch_disponible;}
public function getUsuarioModId() 							{return $this->usuario_mod_id;}
public function getFechaMod() 								{return $this->fecha_mod;}



	
	//metodos SET
	
public function setGrupoDispoCabId($valor) 					{$this->grupo_dispo_cab_id				= $valor;}
public function setVariedadId($valor) 						{$this->variedad_id						= $valor;}
public function setGradoId($valor) 							{$this->grado_id						= $valor;}
public function setCantidadBunch($valor) 					{$this->cantidad_bunch					= $valor;}
public function setCantidadBunchDisponible($valor) 			{$this->cantidad_bunch_disponible		= $valor;}
public function setUsuarioModId($valor) 					{$this->usuario_mod_id					= $valor;}
public function setFechaMod($valor) 						{$this->fecha_mod						= $valor;}


}//fin class

?>
