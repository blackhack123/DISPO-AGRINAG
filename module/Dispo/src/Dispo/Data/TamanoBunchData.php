<?php

namespace Dispo\Data;

use Zend\Filter\Int;
class TamanoBunchData
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
	 * @var Int
	 */
	protected $orden;
	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getOrden() 						{return $this->orden;}
	
	
	//metodos SET
	public function setId($valor) 					{$this->id						= $valor;}
	public function setNombre($valor) 				{$this->nombre					= $valor;}
	public function setOrden($valor) 				{$this->orden					= $valor;}
}//end class

