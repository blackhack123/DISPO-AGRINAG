<?php

namespace Seguridad\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
* Opcion.
*
* @ORM\Entity
* @ORM\Table(name="seguridad.opcion")
*/
class Opcion 
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;

	/**
	* @ORM\Column(type="integer")
	*/
    protected $modulo_id;

	/**
	* @ORM\Column(type="string")
	*/
    protected $nombre;	

	/**
	* @ORM\Column(type="string")
	*/
    protected $tipo_opcion;	

	/**
	* @ORM\Column(type="string")
	*/
    protected $url_acceso;	

	/**
	* @ORM\Column(type="string")
	*/
    protected $url_logo;	

	/**
	* @ORM\Column(type="integer")
	*/
    protected $nro_orden;	
	
	/**
	* @ORM\Column(type="string")
	*/
    protected $estado;

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
	public function getId()				{return $this->id;}
	public function getModuloId()		{return $this->modulo_id;}	
	public function getNombre()			{return $this->nombre;}
	public function getTipoOpcion()		{return $this->tipo_opcion;}
	public function getUrlAcceso()		{return $this->url_acceso;}
	public function getUrlLogo()		{return $this->url_logo;}
	public function getNroOrden()		{return $this->nro_orden;}
	public function getEstado() 		{return $this->estado;}
	public function getFechaIng()		{return $this->fecha_ing;}
	public function getFechaMod() 		{return $this->fecha_mod;}
	public function getUsuarioIngId()	{return $this->usuario_ing_id;}
	public function getUsuarioModId()	{return $this->usuario_mod_id;}

	//Metodos SET
	public function setId($valor)				{$this->id 			= $valor;}
	public function setModuloId($valor)			{$this->modulo_id	= $valor;}		
	public function setNombre($valor)			{$this->nombre 		= $valor;}	
	public function setTipoOpcion($valor)		{$this->tipo_opcion	= $valor;}
	public function setUrlAcceso($valor)		{$this->url_acceso	= $valor;}
	public function setUrlLogo($valor)			{$this->url_logo	= $valor;}
	public function setNroOrden($valor)			{$this->nro_orden	= $valor;}
	public function setEstado($valor)			{$this->estado 		= $valor;}	
	public function setFechaIng($valor)			{$this->fecha_ing 	= $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod 	= $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id = $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id = $valor;}		
		
	/*------------------------------------------------------------------------------*/
	/*----------------------------- RELACIONES FORANEAS ----------------------------*/
	/*------------------------------------------------------------------------------*/
	
	/**
	 * @ORM\ManyToOne(targetEntity="Modulo", inversedBy="opciones")
	 * @ORM\JoinColumn(name="modulo_id", referencedColumnName="id")
	 **/
	private $modulo;
	
    /**
     * @ORM\OneToMany(targetEntity="OpcionAccion", mappedBy="opcion") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="opcion_id")	 	 	 	 	 
     **/
	protected $opcion_acciones = null;

	public function getOpcionAcciones(){
		return $this->opcion_acciones;
	}	
	
    public function __construct()
    {
        $this->opcion_acciones = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
}	