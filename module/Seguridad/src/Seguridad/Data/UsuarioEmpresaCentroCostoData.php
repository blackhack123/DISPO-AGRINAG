<?php

namespace Seguridad\Data;

/**
* UsuarioEmpresaSucursalData.
*
*/
class UsuarioEmpresaSucursalData 
{
	/**
     * @var int
	*/
    protected $usuario_id;

	/**
     * @var int
	*/
    protected $empresa_id;

	/**
     * @var int
	*/
    protected $sucursal_id;

	/**
     * @var string
	*/
    protected $fecha_ing;

	/**
     * @var string
	*/
    protected $fecha_mod;

	/**
     * @var int
	*/
    protected $usuario_ing_id;

	/**
     * @var int
	*/
    protected $usuario_mod_id;
	

	/*------------------------------------------------------------------------------*/
	/*------------------------------- METODOS GET y SET ----------------------------*/
	/*------------------------------------------------------------------------------*/

	//Metodos GET
	public function getUsuarioId()		{return $this->usuario_id;}
	public function getEmpresaId()		{return $this->empresa_id;}
	public function getSucursalId()		{return $this->sucursal_id;}
	public function getFechaIng()		{return $this->fecha_ing;}
	public function getFechaMod() 		{return $this->fecha_mod;}
	public function getUsuarioIngId()	{return $this->usuario_ing_id;}
	public function getUsuarioModId()	{return $this->usuario_mod_id;}

	//Metodos SET
	public function setUsuarioId($valor)		{$this->usuario_id = $valor;}
	public function setEmpresaId($valor)		{$this->empresa_id = $valor;}
	public function setSucursalId($valor)		{$this->sucursal_id = $valor;}	
	public function setFechaIng($valor)			{$this->fecha_ing = $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod = $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id = $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id = $valor;}		

}//end class	