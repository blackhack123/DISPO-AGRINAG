<?php

namespace Dispo\Data;

use Zend\Db\Sql\Ddl\Column\Float;
use Prophecy\Argument\Token\StringContainsToken;
class ClienteData
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
	 * @var string
	 */	
	protected $direccion;
	
	/**
	 * @var string
	 */
	protected $ciudad;
	
	/**
	 * @var string
	 */
	protected $estados_id;
	
	/**
	 * @var string
	 */
	protected $pais_id;
	
	/**
	 * @var string
	 */
	protected $codigo_postal;
	
	/**
	 * @var string
	 */
	protected $estado_nombre;
	
	/**
	 * @var string
	 */
	protected $telefono1;
	
	/**
	 * @var string
	 */
	protected $telefono1_ext;

	/**
	 * @var string
	 */
	protected $telefono2;
	
	/**
	 * @var string
	 */
	protected $telefono2_ext;
	
	/**
	 * @var string
	 */
	protected $fax1;
	
	/**
	 * @var string
	 */
	protected $fax1_ext;
	
	/**
	 * @var string
	 */
	protected $fax2;
	
	/**
	 * @var string
	 */
	protected $fax2_ext;
	
	/**
	 * @var string
	 */
	protected $email;
	
	/**
	 * @var string
	 */
	protected $contacto;
	
	/**
	 * @var string
	 */
	protected $comprador;
	
	/**
	 * @var string
	 */
	protected $cliente_factura_id;
	
	/**
	 * @var string
	 */
	protected $telefono_fact1;
	
	/**
	 * @var string
	 */
	protected $telefono_fact1_ext;
	
	/**
	 * @var string
	 */
	protected $telefono_fact2;
	
	/**
	 * @var string
	 */
	protected $telefono_fact2_ext;
	
	/**
	 * @var string
	 */
	protected $fax_fact1;
	
	/**
	 * @var string
	 */
	protected $fax_fact1_ext;
	
	/**
	 * @var string
	 */
	protected $fax_fact2;
	
	/**
	 * @var string
	 */
	protected $fax_fact2_ext;
		
	/**
	 * @var string
	 */
	protected $email_factura;
	
	/**
	 * @var int
	 */
	protected $usuario_vendedor_id;
	
	/**
	 * @var int
	 */
	protected $est_credito_suspendido;
	
	/**
	 * @var string
	 */
	protected $credito_suspendido_razon;
	
	/**
	 * @var int
	 */
	protected $est_incobrable;
	
	/**
	 * @var float
	 */
	protected $tc_interes;
	
	/**
	 * @var float
	 */
	protected $tc_limite_credito;
	
	/**
	 * @var string
	 */
	protected $tc_forma_pago;
	
	/**
	 * @var float
	 */
	protected $tc_nro_cuotas;
	
	/**
	 * @var float
	 */
	protected $tc_plazo;
	
	/**
	 * @var float
	 */
	protected $tc_1er_cierre;
	
	/**
	 * @var float
	 */
	protected $tc_1er_cierre_pago;
	
	/**
	 * @var float
	 */
	protected $tc_2do_cierre;
	
	/**
	 * @var float
	 */
	protected $tc_2do_cierre_pago;
	
	/**
	 * @var string
	 */
	protected $formato_estado_cta;
	
	/**
	 * @var float
	 */
	protected $porc_iva;
	
	/**
	 * @var int
	 */
	protected $cliente_especial;
	
	/**
	 * @var int
	 */
	protected $incobrable;
	
	/**
	 * @var int
	 */
	protected $grupo_precio_cab_id;
	
	/**
	 * @var string
	 */
	protected $estado;
	
	/**
	 * @var string
	 */
	protected $fec_ingeso;
	
	/**
	 * @var string
	 */
	protected $fec_modifica;
	
	/**
	 * @var int
	 */
	protected $usuario_ing_id;
	
	/**
	 * @var int
	 */
	protected $usuario_mod_id;
	
	/**
	 * @var int
	 */
	protected $sincronizado;
	
	/**
	 * @var string
	 */
	protected $fec_sincronizado;
	

	
	//metodos GET
	public function getId() 						{return $this->id;}
	public function getNombre() 					{return $this->nombre;}
	public function getDireccion() 					{return $this->direccion;}
	public function getCiudad() 					{return $this->ciudad;}
	public function getEstadosId() 					{return $this->estados_id;}
	public function getPaisId() 					{return $this->pais_id;}
	public function getCodigoPostal() 				{return $this->codigo_postal;}
	public function getEstadoNombre() 				{return $this->estado_nombre;}
	public function getTelefono1() 					{return $this->telefono1;}
	public function getTelefono1Ext() 				{return $this->telefono1_ext;}
	public function getTelefono2() 					{return $this->telefono2;}
	public function getTelefono2Ext() 				{return $this->telefono2_ext;}
	public function getFax1() 						{return $this->fax1;}
	public function getFax1Ext() 					{return $this->fax1_ext;}
	public function getFax2() 						{return $this->fax2;}
	public function getFax2Ext() 					{return $this->fax2_ext;}
	public function getEmail()	 					{return $this->email;}
	public function getContacto()	 				{return $this->contacto;}
	public function getComprador()	 				{return $this->comprador;}
	public function getClienteFacturaId()	 		{return $this->cliente_factura_id;}
	public function getTelefonoFact1()	 			{return $this->telefono_fact1;}
	public function getTelefonoFact1Ext()	 		{return $this->telefono_fact1_ext;}
	public function getTelefonoFact2()	 			{return $this->telefono_fact2;}
	public function getTelefonoFact2Ext()	 		{return $this->telefono_fact2_ext;}
	public function getFaxFact1() 					{return $this->fax_fact1;}
	public function getFaxFact1Ext() 				{return $this->fax_fact1_ext;}
	public function getFaxFact2() 					{return $this->fax_fact2;}
	public function getFaxFact2Ext() 				{return $this->fax_fact2_ext;}
	public function getEmailFactura() 				{return $this->email_factura;}
	public function getUsuarioVendedorId() 			{return $this->usuario_vendedor_id;}
	public function getEstCreditoSuspendido() 		{return $this->est_credito_suspendido;}
	public function getCreditoSuspendidoRazon() 	{return $this->credito_suspendido_razon;}
	public function getEstIncobrable() 				{return $this->est_incobrable;}
	public function getTcInteres() 					{return $this->tc_interes;}
	public function getTcLimiteCredito() 			{return $this->tc_limite_credito;}
	public function getTcFormaPago() 				{return $this->tc_forma_pago;}
	public function getTcNroCuotas() 				{return $this->tc_nro_cuotas;}
	public function getTcPlazo() 					{return $this->tc_plazo;}
	public function getTc1erCierre() 				{return $this->tc_1er_cierre;}
	public function getTc1erCierrePago() 			{return $this->tc_1er_cierre_pago;}
	public function getTc2doCierre() 				{return $this->tc_2do_cierre;}
	public function getTc2doCierrePago() 			{return $this->tc_2do_cierre_pago;}
	public function getFormatoEstadoCta() 			{return $this->formato_estado_cta;}
	public function getPorcIva() 					{return $this->porc_iva;}
	public function getClienteEspecial() 			{return $this->cliente_especial;}
	public function getIncobrable() 				{return $this->incobrable;}
	public function getGrupoPrecioCabId() 			{return $this->grupo_precio_cab_id;}
	public function getEstado() 					{return $this->estado;}
	public function getFecIngreso() 				{return $this->fec_ingreso;}
	public function getFecModifica() 				{return $this->fec_modifica;}
	public function getUsuarioIngId() 				{return $this->usuario_ing_id;}
	public function getUsuarioModId() 				{return $this->usuario_mod_id;}
	public function getSincronizado() 				{return $this->sincronizado;}
	public function getFecSincronizado() 			{return $this->fec_sincronizado;}
	
	
	//metodos SET
	public function setId($valor) 						{$this->id						= $valor;}
	public function setNombre($valor) 					{$this->nombre					= $valor;}
	public function setDireccion($valor) 				{$this->direccion				= $valor;}
	public function setCiudad($valor) 					{$this->ciudad					= $valor;}
	public function setEstadosId($valor) 				{$this->estados_id				= $valor;}
	public function setPaisId($valor) 					{$this->pais_id					= $valor;}
	public function setCodigoPostal($valor) 			{$this->codigo_postal			= $valor;}
	public function setEstadoNombre($valor) 			{$this->estado_nombre			= $valor;}
	public function setTelefono1($valor) 				{$this->telefono1				= $valor;}
	public function setTelefono1Ext($valor) 			{$this->telefono1_ext			= $valor;}
	public function setTelefono2($valor) 				{$this->telefono2				= $valor;}
	public function setTelefono2Ext($valor) 			{$this->telefono2_ext			= $valor;}
	public function setFax1($valor) 					{$this->fax1					= $valor;}
	public function setFax1Ext($valor) 					{$this->fax1_ext				= $valor;}
	public function setFax2($valor) 					{$this->fax2					= $valor;}
	public function setFax2Ext($valor) 					{$this->fax2_ext				= $valor;}
	public function setEmail($valor) 					{$this->email					= $valor;}
	public function setContacto($valor) 				{$this->contacto				= $valor;}
	public function setComprador($valor) 				{$this->comprador				= $valor;}
	public function setClienteFacturaId($valor) 		{$this->cliente_factura_id		= $valor;}
	public function setTelefonoFact1($valor) 			{$this->telefono_fact1			= $valor;}
	public function setTelefonoFact1Ext($valor) 		{$this->telefono_fact1_ext		= $valor;}
	public function setTelefonoFact2($valor) 			{$this->telefono_fact2			= $valor;}
	public function setTelefonoFact2Ext($valor) 		{$this->telefono_fact2_ext		= $valor;}
	public function setFaxFact1($valor) 				{$this->fax_fact1				= $valor;}
	public function setFaxFact1Ext($valor) 				{$this->fax_fact1_ext			= $valor;}
	public function setFaxFact2($valor) 				{$this->fax_fact2				= $valor;}
	public function setFaxFact2Ext($valor) 				{$this->fax_fact2_ext			= $valor;}
	public function setEmailFactura($valor) 			{$this->email_factura			= $valor;}
	public function setUsuarioVendedorId($valor) 		{$this->usuario_vendedor_id		= $valor;}
	public function setEstCreditoSuspendido($valor) 	{$this->est_credito_suspendido	= $valor;}
	public function setCreditoSuspendidoRazon($valor) 	{$this->credito_suspendido_razon= $valor;}
	public function setEstIncobrable($valor) 			{$this->est_incobrable			= $valor;}
	public function setTcInteres($valor) 				{$this->tc_interes				= $valor;}
	public function setTcLimiteCredito($valor) 			{$this->tc_limite_credito		= $valor;}
	public function setTcFormaPago($valor) 				{$this->tc_forma_pago			= $valor;}
	public function setNroCuotas($valor) 				{$this->tc_nro_cuotas			= $valor;}
	public function setTcPlazo($valor) 					{$this->tc_plazo				= $valor;}
	public function setTc1erCierre($valor) 				{$this->tc_1er_cierre			= $valor;}
	public function setTc1erCierrePago($valor) 			{$this->tc_1er_cierre_pago		= $valor;}
	public function setTc2doCierre($valor) 				{$this->tc_2do_cierre			= $valor;}
	public function setTc2doCierrePago($valor) 			{$this->tc_2do_cierre_pago		= $valor;}
	public function setFormatoEstadoCta($valor) 		{$this->formato_estado_cta		= $valor;}
	public function setPorcIva($valor) 					{$this->porc_iva				= $valor;}
	public function setClienteEspecial($valor) 			{$this->cliente_especial		= $valor;}
	public function setIncobrable($valor) 				{$this->incobrable				= $valor;}
	public function setGrupoPrecioCabId($valor) 		{$this->grupo_precio_cab_id		= $valor;}
	public function setEstado($valor) 					{$this->estado					= $valor;}
	public function setFecIngreso($valor) 				{$this->fec_ingreso				= $valor;}
	public function setFecModifica($valor) 				{$this->fec_modifica			= $valor;}
	public function setUsuarioIngId($valor) 			{$this->usuario_ing_id			= $valor;}
	public function setUsuarioModId($valor)				{$this->usuario_mod_id			= $valor;}
	public function setSincronizado($valor) 			{$this->sincronizado			= $valor;}
	public function setFecSincronizado($valor)			{$this->fec_sincronizado		= $valor;}

}//fin class


