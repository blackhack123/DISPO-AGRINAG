<?php

namespace Dispo\Data;

class GrupoPrecioDetData
{


	/**
	 * @var int
	 */	
	protected $grupo_precio_cab_id;

	/**
	 * @var string
	 */	
	protected $variedad_id;

	/**
	 * @var string
	 */	
	protected $grado_id;

	/**
	 * @var float
	 */	
	protected $precio;

	/**
	 * @var float
	 */	
	protected $precio_oferta;



	//metodos GET
	public function getGrupoPrecioCabId()				 	 {return $this->grupo_precio_cab_id;}
	public function getVariedadId() 						 {return $this->variedad_id;}
	public function getGradoId() 							 {return $this->grado_id;}
	public function getPrecio() 							 {return $this->precio;}
	public function getPrecioOferta() 						 {return $this->precio_oferta;}


	//metodos SET
	public function setGrupoPrecioCabId($valor) 			{$this->grupo_precio_cab_id		= $valor;}
	public function setVariedadId($valor) 					{$this->variedad_id				= $valor;}
	public function setGradoId($valor) 						{$this->grado_id				= $valor;}
	public function setPrecio($valor) 						{$this->precio					= $valor;}
	public function setPrecioOferta($valor) 				{$this->precio_oferta			= $valor;}


}//end class

?>
