<?php

namespace Seguridad\Data;

/**
* UsuarioData.
*
*/
class UsuarioData //extends \General\Data\PersonaData
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */	
    private $persona_id;

    /**
     * @var int
     */	
    private $perfil_id;	

    /**
     * @var int
     */	
    private $grupo_empresarial_id;	
	
    /**
     * @var string
     */
    private $nombre_usuario;

    /**
     * @var string
     */
    private $clave;

    /**
     * @var int
     */
    private $estado_cambio_clave;

    /**
     * @var int
     */	
    private $nro_intentos;

    /**
     * @var string
     */
    private $estado;

    /**
     * @var string
     */
    private $fecha_ing;

    /**
     * @var string
     */
    private $fecha_mod;
	
    /**
     * @var int
     */
    private $usuario_ing_id;

    /**
     * @var int
     */
    private $usuario_mod_id;
    private $xml_empresa_sucursal;
    private $xml_extension;
	
	public function getId()					  	{return $this->id;}
	public function getPersonaId()			  	{return $this->persona_id;}	
	public function getPerfilId()			  	{return $this->perfil_id;}	
	public function getGrupoEmpresarialId()	  	{return $this->grupo_empresarial_id;}	
	public function getNombreUsuario()		  	{return $this->nombre_usuario;}	
	public function getClave()				  	{return $this->clave;}	
	public function getEstadoCambioClave()	  	{return $this->estado_cambio_clave;}
	public function getNroIntentos()		  	{return $this->nro_intentos;}
	public function getEstado()					{return $this->estado;}
	public function getFechaIng()				{return $this->fecha_ing;}
	public function getFechaMod()				{return $this->fecha_mod;}
	public function getUsuarioIngId()			{return $this->usuario_ing_id;}
	public function getUsuarioModId()			{return $this->usuario_mod_id;}
	public function getXmlEmpresaSurcursal()	{return $this->xml_empresa_sucursal;}
	public function getXmlExtension()			{return $this->xml_extension;}
	
	public function setId($valor)					{$this->id 				= $valor;}
	public function setPersonaId($valor)			{$this->persona_id		= $valor;}	
	public function setPerfilId($valor)			  	{$this->perfil_id		= $valor;}	
	public function setGrupoEmpresarialId($valor)	{$this->grupo_empresarial_id = $valor;}	
	public function setNombreUsuario($valor)		{$this->nombre_usuario	= $valor;}	
	public function setClave($valor)				{$this->clave			= $valor;}	
	public function setEstadoCambioClave($valor)	{$this->estado_cambio_clave	= $valor;}
	public function setNroIntentos($valor)		  	{$this->nro_intentos	= $valor;}
	public function setEstado($valor)				{$this->estado			= $valor;}
	public function setFechaIng($valor)				{$this->fecha_ing		= $valor;}
	public function setFechaMod($valor)				{$this->fecha_mod		= $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id	= $valor;}
	public function setUsuarioModId($valor)			{$this->usuario_mod_id	= $valor;}
	
	function setXmlXmlEmpresaSurcursal($EmpresaSucursalData){
		$resultado = "<EmpresasSucursales>";
		if(count($EmpresaSucursalData)>0)
		{
			foreach ($EmpresaSucursalData	as $reg){
				if ($reg['tipo_accion']!='C'){  //No se procesan aquellos registros que no han sido alterados
					$resultado .= "<EmpresaSucursal>";
					$resultado .= "<empresa_id>" . $reg['empresa_id'] . "</empresa_id>";
					$resultado .= "<sucursal_id>" . $reg['sucursal_id'] . "</sucursal_id>";
					$resultado .= "<tipo_tran>" . $reg['tipo_accion'] . "</tipo_tran>";
					$resultado .= "</EmpresaSucursal>";
				}//end if
			}//end foreach
			$resultado .= "</EmpresasSucursales>";
			$this->xml_empresa_sucursal = $resultado;
		}//end if
		else
		{
			$this->xml_empresa_sucursal = null;
		}
	}//end function setXmlXmlEmpresaSurcursal
	
	function setXmlExtension($ExtensionData){
		$resultado = "<UsuarioExtensiones>";
		if(count($ExtensionData)>0)
		{
			foreach ($ExtensionData	as $reg){
				if ($reg['accion']!='C'){  //No se procesan aquellos registros que no han sido alterados
					$resultado .= "<UsuarioExtension>";
					$resultado .= "<id>" . $reg['id'] . "</id>";
					$resultado .= "<tipo_extension>" . $reg['tipo_extension'] . "</tipo_extension>";
					$resultado .= "<numero_extension>" . $reg['numero_extension'] . "</numero_extension>";
					$resultado .= "<tipo_tran>" . $reg['accion'] . "</tipo_tran>";
					$resultado .= "</UsuarioExtension>";
				}//end if
			}//end foreach
			$resultado .= "</UsuarioExtensiones>";
			$this->xml_extension = $resultado;
		}//end if
		else
		{
			$this->xml_extension = null;
		}
	}//end function setXmlExtension
}//end class	