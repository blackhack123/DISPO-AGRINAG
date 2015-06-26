<?php

namespace Seguridad\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* Usuario.
*
* @ORM\Entity
* @ORM\Table(name="seguridad.usuario")
*/
class Usuario 
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
    protected $persona_id;

	/**
	* @ORM\Column(type="integer")
	*/
    protected $perfil_id;	

	/**
	* @ORM\Column(type="integer")
	*/
    protected $grupo_empresarial_id;		
	
	/**
	* @ORM\Column(type="string")
	*/
    protected $nombre_usuario;

	/**
	* @ORM\Column(type="string")
	*/
    protected $clave;

	/**
	* @ORM\Column(type="smallint")
	*/
    protected $estado_cambio_clave;

	/**
	* @ORM\Column(type="integer")
	*/
    protected $nro_intentos;
	
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
	
	/**
	* Magic getter to expose protected properties.
	*
	* @param string $property
	* @return mixed
	*/
    public function __get($property)
    {
        return $this->$property;
    }

	/**
	* Magic setter to save protected properties.
	*
	* @param string $property
	* @param mixed $value
	*/
	public function __set($property, $value)
    {
        $this->$property = $value;
    }

	//Metodos GET
	public function getId()					{return $this->id;}
	public function getPersonaId()			{return $this->persona_id;}				
	public function getPerfilId()			{return $this->perfil_id;}	
	public function getGrupoEmpresarialId()	{return $this->grupo_empresarial_id;}	
	public function getNombreUsuario()		{return $this->nombre_usuario;}
	public function getClave()				{return $this->clave;}
	public function getEstadoCambioClave()	{return $this->estado_cambio_clave;}
	public function getNroIntentos()		{return $this->nro_intentos;}
	public function getEstado()				{return $this->estado;}
	public function getFechaIng()			{return $this->fecha_ing;}
	public function getFechaMod()			{return $this->fecha_mod;}
	public function getUsuarioIngId()		{return $this->usuario_ing_id;}
	public function getUsuarioModId()		{return $this->usuario_mod_id;}

	//Metodos SET
	public function setId($valor)					{$this->id 				= $valor;}
	public function setPersonaId($valor)			{$this->persona_id 		= $valor;}	
	public function setPerfilId($valor)				{$this->perfil_id 		= $valor;}
	public function setGrupoEmpresarialId($valor)	{$this->grupo_empresarial_id	= $valor;}	
	public function setNombreUsuario($valor)		{$this->nombre_usuario	= $valor;}	
	public function setClave($valor)				{$this->clave 			= $valor;}	
	public function setEstadoCambioClave($valor)	{$this->estado_cambio_clave = $valor;}	
	public function setNroIntentos($valor)			{$this->nro_intentos 	= $valor;}	
	public function setEstado($valor)				{$this->estado 			= $valor;}	
	public function setFechaIng($valor)				{$this->fecha_ing 		= $valor;}	
	public function setFechaMod($valor)				{$this->fecha_mod 		= $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id 	= $valor;}	
	public function setUsuarioModId($valor)			{$this->usuario_mod_id 	= $valor;}	
	
	/*------------------------------------------------------------------------------*/
	/*----------------------------- RELACIONES FORANEAS ----------------------------*/
	/*------------------------------------------------------------------------------*/
	
	/**
    * @ORM\OneToOne(targetEntity="General\Entity\Persona", mappedBy="usuario") 
	* @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
	*/
    protected $persona;
	
	public function getPersona(){
		return $this->persona;	
	}
	
	public function setPersona($valor){
		$this->persona  = $valor;
	}		


    /**
     * @ORM\ManyToOne(targetEntity="Perfil", inversedBy="usuarios")
	 * @ORM\JoinColumn(name="perfil_id", referencedColumnName="id")	 
     **/		
    private $perfil;

	public function getPerfil(){
		return $this->perfil;
	}

	public function setPerfil($valor){
		$this->perfil = $valor;
	}


    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\GrupoEmpresarial", inversedBy="usuarios")
	 * @ORM\JoinColumn(name="grupo_empresarial_id", referencedColumnName="id")	 
     **/		
    private $grupo_empresarial;

	public function getGrupoEmpresarial(){
		return $this->grupo_empresarial;
	}

	public function setGrupoEmpresarial($valor){
		$this->grupo_empresarial = $valor;
	}	
	

    /**
     * @ORM\OneToMany(targetEntity="UsuarioEmpresaSucursal", mappedBy="usuario") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_id")
     **/
	protected $usuario_empresa_sucursales;

	public function getUsuarioEmpresaSucursales(){
		return $this->usuario_empresa_sucursales;
	}		

	
    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="usuario_ings")
	 * @ORM\JoinColumn(name="usuario_ing_id", referencedColumnName="id")	 
     **/		
    private $usuario_ing;
	
	public function getUsuarioIng(){
		return $this->usuario_ing;
	}

	public function setUsuarioIng($valor){
		$this->usuario_ing = $valor;
	}	

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings;

	public function getUsuarioIngs(){
		return $this->usuarios_ings;
	}		
	
	
    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="usuario_mods")
	 * @ORM\JoinColumn(name="usuario_mod_id", referencedColumnName="id")	 
     **/		
    private $usuario_mod;
	
	public function getUsuarioMod(){
		return $this->usuario_mod;
	}

	public function setUsuarioMod($valor){
		$this->usuario_mod = $valor;
	}	

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods;

	public function getUsuarioMods(){
		return $this->usuarios_mods;
	}

    /**
     * @ORM\OneToMany(targetEntity="General\Entity\GrupoEmpresarial", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_grupoempresarial;

	public function getUsuarioIngsGrupoEmpresarial(){
		return $this->usuario_ings_grupoempresarial;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="General\Entity\GrupoEmpresarial", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_grupoempresarial;

	public function getUsuarioModsGrupoEmpresarial(){
		return $this->usuario_mods_grupoempresarial;	
	}


    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoParentezco", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_tipoparentezco;

	public function getUsuarioIngsTipoParentezco(){
		return $this->usuario_ings_tipoparentezco;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoParentezco", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_tipoparentezco;

	public function getUsuarioModsTipoParentezco(){
		return $this->usuario_mods_tipoparentezco;	
	}	
	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoDiscapacidad", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_tipodiscapacidad;

	public function getUsuarioIngsTipoDiscapacidad(){
		return $this->usuario_ings_tipodiscapacidad;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoDiscapacidad", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_tipodiscapacidad;

	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\CargoGrupo", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_cargogrupo;

	public function getUsuarioIngsCargoGrupo(){
		return $this->usuario_ings_cargogrupo;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\CargoGrupo", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_cargogrupo;
	
	public function getUsuarioModsTipoCargoGrupo(){
		return $this->usuario_mods_cargogrupo;	
	}	
	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoEmpleadorIess", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_tipoempleadoriess;

	public function getUsuarioIngsTipoEmpleadorIess(){
		return $this->usuario_ings_tipoempleadoriess;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoEmpleadorIess", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_tipoempleadoriess;
	
	public function getUsuarioModsTipoEmpleadorIess(){
		return $this->usuario_mods_tipoempleadoriess;	
	}	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoContrato", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_tipocontrato;

	public function getUsuarioIngsTipoContrato(){
		return $this->usuario_ings_tipocontrato;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoContrato", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_tipocontrato;

	public function getUsuarioModsTipoContrato(){
		return $this->usuario_mods_tipocontrato;	
	}	
	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\MotivoIngreso", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_motivoingreso;

	public function getUsuarioIngsMotivoIngreso(){
		return $this->usuario_ings_motivoingreso;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\MotivoIngreso", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_motivoingreso;
	
	public function getUsuarioModsMotivoIngreso(){
		return $this->usuario_mods_motivoingreso;	
	}		

	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\MotivoSalida", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_motivosalida;

	public function getUsuarioIngsMotivoSalida(){
		return $this->usuario_ings_motivosalida;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\MotivoSalida", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_motivosalida;
	
	public function getUsuarioModsMotivoSalida(){
		return $this->usuario_mods_motivosalida;	
	}		


	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\Area", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_area;

	public function getUsuarioIngsArea(){
		return $this->usuario_ings_area;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\Area", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_area;
	
	public function getUsuarioModsArea(){
		return $this->usuario_mods_area;	
	}		
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\Departamento", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_departamento;

	public function getUsuarioIngsDepartamento(){
		return $this->usuario_ings_departamento;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\Departamento", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_departamento;
	
	public function getUsuarioModsDepartamento(){
		return $this->usuario_mods_departamento;	
	}		


    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoHorario", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_tipohorario;

	public function getUsuarioIngsTipoHorario(){
		return $this->usuario_ings_tipohorario;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\TipoHorario", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_tipohorario;
	
	public function getUsuarioModsTipoHorario(){
		return $this->usuario_mods_tipohorario;	
	}		


    /**
     * @ORM\OneToMany(targetEntity="Financiero\Entity\Banco", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_banco;

	public function getUsuarioIngsBanco(){
		return $this->usuario_ings_banco;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Financiero\Entity\Banco", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_banco;
	
	public function getUsuarioModsBanco(){
		return $this->usuario_mods_banco;	
	}		
	
	
	
    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\Empleado", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_empleado;

	public function getUsuarioIngsEmpleado(){
		return $this->usuario_ings_empleado;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="Talentohumano\Entity\Empleado", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_empleado;
	
	public function getUsuarioModsEmpleado(){
		return $this->usuario_mods_empleado;	
	}		


    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Persona", mappedBy="usuario_ing") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_ing_id")
     **/
	protected $usuario_ings_persona;

	public function getUsuarioIngsPersona(){
		return $this->usuario_ings_persona;	
	}
	

    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Persona", mappedBy="usuario_mod") 
	 * @ORM\JoinColumn(name="id", referencedColumnName="usuario_mod_id")
     **/
	protected $usuario_mods_persona;
	
	public function getUsuarioModsPersona(){
		return $this->usuario_mods_persona;	
	}		
	
    public function __construct()
    {
        $this->usuario_ings 						= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods 						= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_empresa_sucursales 			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_ings_grupoempresarial 		= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_grupoempresarial 		= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_tipoparentezco 			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_tipoparentezco 			= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_tipodiscapacidad 		= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_tipodiscapacidad 		= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_cargogrupo 				= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_cargogrupo 				= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_tipoempleadoriess 		= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_tipoempleadoriess 		= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_tipocontrato			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_tipocontrato	 		= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_motivoingreso			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_motivoingreso	 		= new \Doctrine\Common\Collections\ArrayCollection();		
        $this->usuario_ings_motivosalida			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_motivosalida	 		= new \Doctrine\Common\Collections\ArrayCollection();	
        $this->usuario_ings_area					= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_area	 				= new \Doctrine\Common\Collections\ArrayCollection();	
        $this->usuario_ings_departamento			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_departamento	 		= new \Doctrine\Common\Collections\ArrayCollection();	
        $this->usuario_ings_tipohorario				= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_tipohorario	 			= new \Doctrine\Common\Collections\ArrayCollection();	
        $this->usuario_ings_banco					= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_banco	 				= new \Doctrine\Common\Collections\ArrayCollection();	
        $this->usuario_ings_empleado				= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_empleado	 			= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_ings_persona					= new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_mods_persona		 			= new \Doctrine\Common\Collections\ArrayCollection();
    }
	

	
	

}	