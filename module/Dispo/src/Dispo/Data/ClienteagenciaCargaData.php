<?php

namespace Dispo\Data;

class ClienteAgenciaCargaData
{
	/**
	 * @var string
	 */	
	protected $cliente_id;
	
	/**
	 * @var string
	 */	
	protected $agencia_carga_id;
	
	
	//metodos GET
	public function getClienteId() 						{return $this->cliente_id;}
	public function getAgenciaCargaId() 				{return $this->agencia_carga_id;}
	
	
	//metodos SET
	public function setClienteId($valor) 				{$this->cliente_id				= $valor;}
	public function setAgenciaCargaId($valor) 			{$this->agencia_carga_id		= $valor;}

}//end class

