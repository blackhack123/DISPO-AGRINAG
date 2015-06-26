<?php

namespace Seguridad\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
* UsuarioEmpresaentroCosto
*
* @ORM\Entity
* @ORM\Table(name="usuario_empresa_sucursal")
*/
class UsuarioEmpresaSucursal 
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	*/
    protected $usuario_id;

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	*/
    protected $empresa_id;

	/**
	* @ORM\Id	
	* @ORM\Column(type="integer")
	*/
    protected $sucursal_id;

	/**
	* @ORM\Column(type="datetime")
	*/
    protected $fecha_ing;

	/**
	* @ORM\Column(type="datetime")
	*/
    protected $fecha_mod;
	
	/**
	* @ORM\Column(type="integer")
	*/
    protected $usuario_ing_id;

	/**
	* @ORM\Column(type="integer")
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
	
	
	/*------------------------------------------------------------------------------*/
	/*----------------------------- RELACIONES FORANEAS ----------------------------*/
	/*------------------------------------------------------------------------------*/

	/**
	 * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="usuario_empresa_centrocostos")
	 * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
	 **/
	private $usuario;

	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($valor){
		$this->pais = $usuario;
	}
		
	
	/**
	 * @ORM\ManyToOne(targetEntity="General\entity\EmpresaSucursal", inversedBy="usuario_empresa_centro_costos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa_id", referencedColumnName="empresa_id"),
     *   @ORM\JoinColumn(name="sucursal_id", referencedColumnName="sucursal_id"),
     * })
	 **/
	private $empresa_centro_costo;

	public function getEmpresaSucursal(){
		return $this->empresa_centro_costo;
	}

	public function setEmpresaSucursal($valor){
		$this->pais = $empresa_centro_costo;
	}
	


    public function __construct()
    {
    }

	
}	