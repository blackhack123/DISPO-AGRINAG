<?php

namespace Seguridad\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
* Permisos del Perfil.
*
* @ORM\Entity
* @ORM\Table(name="seguridad.perfil_permiso")
*/
class PerfilPermiso 
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	*/
    protected $perfil_id;

	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	*/
    protected $dispositivo_id;
	
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	*/
    protected $opcion_id;

	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	*/
    protected $accion_id;
	
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
	public function getPerfilId()		{return $this->perfil_id;}
	public function getDispositivoId()	{return $this->dispositivo_id;}	
	public function getOpcionId()		{return $this->opcion_id;}	
	public function getAccionId()		{return $this->accion_id;}	
	public function getFechaIng()		{return $this->fecha_ing;}
	public function getFechaMod() 		{return $this->fecha_mod;}
	public function getUsuarioIngId()	{return $this->usuario_ing_id;}
	public function getUsuarioModId()	{return $this->usuario_mod_id;}	


	//Metodos SET
	public function setPerfilId($valor)			{$this->perfil_id 			= $valor;}
	public function setDispositivoId($valor)	{$this->dispositivo_id		= $valor;}		
	public function setOpcionId($valor)			{$this->opcion_id			= $valor;}	
	public function setAccionId($valor)			{$this->accion_id 			= $valor;}	
	public function setFechaIng($valor)			{$this->fecha_ing 			= $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod 			= $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id 		= $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id 		= $valor;}		
	
	/*------------------------------------------------------------------------------*/
	/*----------------------------- RELACIONES FORANEAS ----------------------------*/
	/*------------------------------------------------------------------------------*/
	
	/**
	 * @ORM\ManyToOne(targetEntity="Perfil", inversedBy="perfil_permisos")
	 * @ORM\JoinColumn(name="perfil_id", referencedColumnName="id")
	 **/
	private $perfil;
		
	/**
	 * @ORM\ManyToOne(targetEntity="OpcionAccion", inversedBy="perfil_permisos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dispositivo_id", referencedColumnName="dispositivo_id"),	 
     *   @ORM\JoinColumn(name="opcion_id", referencedColumnName="opcion_id"),
     *   @ORM\JoinColumn(name="accion_id", referencedColumnName="accion_id")
     * })	 
	 **/
	private $opcion_accion;

	public function getOpcionAccion()
	{
		return $this->opcion_accion;
	}
	
    public function __construct()
    {
    }
	
}	