<?php

namespace Seguridad\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
* Acciones de la Opcion.
*
* @ORM\Entity
* @ORM\Table(name="seguridad.opcion_accion")
*/
class OpcionAccion 
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	*/
    protected $dispositivo_id;

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
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
	public function getDispositivoId()	{return $this->dispositivo_id;}
	public function getOpcionId()		{return $this->opcion_id;}
	public function getAccionId()		{return $this->accion_id;}
	public function getFechaIng()		{return $this->fecha_ing;}
	public function getFechaMod() 		{return $this->fecha_mod;}
	public function getUsuarioIngId()	{return $this->usuario_ing_id;}
	public function getUsuarioModId()	{return $this->usuario_mod_id;}

	//Metodos SET
	public function setDispositivoId($valor)	{$this->dispositivo_id = $valor;}
	public function setOpcionId($valor)			{$this->opcion_id = $valor;}
	public function setAccionId($valor)			{$this->accion_id = $valor;}	
	public function setFechaIng($valor)			{$this->fecha_ing = $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod = $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id = $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id = $valor;}		
	
	
	/*------------------------------------------------------------------------------*/
	/*----------------------------- RELACIONES FORANEAS ----------------------------*/
	/*------------------------------------------------------------------------------*/

	/**
	 * @ORM\ManyToOne(targetEntity="Dispositivo", inversedBy="opcion_acciones")
	 * @ORM\JoinColumn(name="dispositivo_id", referencedColumnName="id")
	 **/
	private $dispositivo;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Accion", inversedBy="opcion_acciones")
	 * @ORM\JoinColumn(name="accion_id", referencedColumnName="id")
	 **/
	private $accion;

	/**
	 * @ORM\ManyToOne(targetEntity="Opcion", inversedBy="opcion_acciones")
	 * @ORM\JoinColumn(name="opcion_id", referencedColumnName="id")
	 **/
	private $opcion;
		
	public function getOpcion()
	{
		return $this->opcion;
	}

    /**
     * @ORM\OneToMany(targetEntity="PerfilPermiso", mappedBy="opcion_acciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dispositivo_id", referencedColumnName="dispositivo_id"),
     *   @ORM\JoinColumn(name="opcion_id", referencedColumnName="opcion_id"),
     *   @ORM\JoinColumn(name="accion_id", referencedColumnName="accion_id")
     * })
     */
	protected $perfil_permisos = null;

	public function getPerfilPermisos(){
		return $this->perfil_permisos;
	}	

    public function __construct()
    {
        $this->perfil_permisos = new \Doctrine\Common\Collections\ArrayCollection();
    }

	
}	