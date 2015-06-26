<?php

namespace Seguridad\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
* Accion.
*
* @ORM\Entity
* @ORM\Table(name="seguridad.accion")
*/
class Accion
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;

	/**
	* @ORM\Column(type="string")
	*/
    protected $nombre;

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
	public function getNombre()			{return $this->nombre;}
	public function getFechaIng()		{return $this->fecha_ing;}
	public function getFechaMod() 		{return $this->fecha_mod;}
	public function getUsuarioIngId()	{return $this->usuario_ing_id;}
	public function getUsuarioModId()	{return $this->usuario_mod_id;}

	//Metodos SET
	public function setId($valor)				{$this->id 			= $valor;}
	public function setNombre($valor)			{$this->nombre 		= $valor;}
	public function setFechaIng($valor)			{$this->fecha_ing 	= $valor;}	
	public function setFechaMod($valor)			{$this->fecha_mod 	= $valor;}	
	public function setUsuarioIngId($valor)		{$this->usuario_ing_id = $valor;}	
	public function setUsuarioModId($valor)		{$this->usuario_mod_id = $valor;}		


	/*------------------------------------------------------------------------------*/
	/*----------------------------- RELACIONES FORANEAS ----------------------------*/
	/*------------------------------------------------------------------------------*/
	
    /**
     * @ORM\OneToMany(targetEntity="OpcionAccion", mappedBy="accion") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="accion_id")	 	 	 	 	 
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