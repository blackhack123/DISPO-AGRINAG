<?php
namespace Seguridad\Controller\Plugin;

use Seguridad\BO\UsuarioBO;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;


class SesionUsuarioPlugin extends AbstractPlugin {

	public function getUsuarioId()						{return $this->getRecord()['usuario_id'];}
	public function getUserNombre()						{return $this->getRecord()['nombre_usuario'];}
	public function getUserName()						{return $this->getRecord()['username'];}
	public function getUserEmail()						{return $this->getRecord()['email'];}
	public function getUserNombrePerfil()				{return $this->getRecord()['nombre_perfil'];}
	public function getUserPerfilId()					{return $this->getRecord()['perfil_id'];}
	public function getUserPerfilNombre()				{return $this->getRecord()['perfil_nombre'];}	
	public function getUserClienteId()					{return $this->getRecord()['cliente_id'];}
	public function getUserClienteNombre()				{return $this->getRecord()['cliente_nombre'];}
	public function getClienteSeleccionMarcacionSec()	{return $this->getRecord()['cliente_seleccion_marcacion_sec'];}
	public function getClienteSeleccionMarcacionNombre(){return $this->getRecord()['cliente_seleccion_marcacion_nombre'];}	
	/*public function getClienteSeleccionMarcacionPuntoCorte(){return $this->getRecord()['cliente_seleccion_marcacion_puntocorte'];}*/
	public function getClienteSeleccionAgenciaId()		{return $this->getRecord()['cliente_seleccion_agencia_id'];}
	public function getClientePedidoCabIdActual()		{return $this->getRecord()['cliente_pedido_cab_id_actual'];}
	public function getVendedorUsuarioId()				{return $this->getRecord()['vendedor_usuario_id'];}
	public function getVendedorNombre()					{return $this->getRecord()['vendedor_nombre_usuario'];}
	public function getVendedorUserName()				{return $this->getRecord()['vendedor_username'];}
	public function getClienteUsuarioId()				{return $this->getRecord()['cliente_usuario_id'];}
	public function getClienteUsuarioNombre()			{return $this->getRecord()['cliente_usuario_nombre'];}	
	public function getClienteUsuarioUserName()			{return $this->getRecord()['cliente_usuario_username'];}
	/*public function getClienteCalidadId()				{return $this->getRecord()['cliente_calidad_id'];}*/
	//2015-09-29 Nuevos Campos
	public function getUserInventarioId()				{return $this->getRecord()['cliente_usuario_inventario_id'];}
	public function getUserCalidadId()					{return $this->getRecord()['cliente_usuario_calidad_id'];}
	public function getUserGrupoPrecioCabId()			{return $this->getRecord()['cliente_usuario_grupo_precio_cab_id'];}
	public function getUserGrupoDispoCabId()			{return $this->getRecord()['cliente_usuario_grupo_dispo_cab_id'];}
	public function getUserPuntoCorte()					{return $this->getRecord()['cliente_usuario_punto_corte'];}
	public function getMarcacionTipoCajaDefaultId()		{return $this->getRecord()['marcacion_tipo_caja_default_id'];}
	
	public function getAtributos()						{return $this->getRecord()['atributos'];}
	//------------------------

	
	public function getUserLayout(){
		$record = $this->getRecord();
		$this->getController()->layout()->identidad_usuario = $record;
		return $record['layout'];
	}//end public getUserLayout
	
	
	public function setUserClienteId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_id', $valor);	
	}//end function
	public function setUserClienteNombre($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_nombre', $valor);
	}//end function
	public function setClienteSeleccionMarcacionSec($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_marcacion_sec', $valor);
	}//end function
	public function setClienteSeleccionMarcacionNombre($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_marcacion_nombre', $valor);
	}//end function	
/*	
 	public function setClienteSeleccionMarcacionPuntoCorte($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_marcacion_puntocorte', $valor);
	}//end function
*/	
	public function setClienteSeleccionAgenciaId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_agencia_id', $valor);
	}//end function
	public function setClientePedidoCabIdActual($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_pedido_cab_id_actual', $valor);
	}//end function	
	public function setClienteUsuarioId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_id', $valor);
	}//end function	
	public function setClienteUsuarioNombre($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_nombre', $valor);
	}//end function
	public function setClienteUsuarioUsername($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_username', $valor);
	}//end function
/*	public function setClienteCalidadId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_calidad_id', $valor);
	}//end function
*/	
	//2015-09-29 Nuevos Campos
	public function setUserInventarioId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_inventario_id', $valor);
	}//end function
	public function setUserCalidadId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_calidad_id', $valor);
	}//end function
	public function setUserGrupoPrecioCabId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_grupo_precio_cab_id', $valor);
	}//end function
	public function setUserGrupoDispoCabId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_grupo_dispo_cab_id', $valor);
	}//end function
	public function setUserPuntoCorte($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_usuario_punto_corte', $valor);
	}//end function
	public function setMarcacionTipoCajaDefaultId($valor){
		$session = new Container('usuario');
		$session->offsetSet('marcacion_tipo_caja_default_id', $valor);
	}//end function	
	//-----------------------------
	
	/*-----------------------------------------------------------------------------*/
	public function getRecord()
	/*-----------------------------------------------------------------------------*/
	{
		$session = new Container('usuario');

		$result = 	[	
					'usuario_id'						=> $session->offsetGet('usuario_id'),
					'nombre_usuario'					=> $session->offsetGet('usuario_nombre'),
					'username'    						=> $session->offsetGet('username'),
					'email'  							=> $session->offsetGet('email'),
					'nombre_perfil'  					=> $session->offsetGet('nombre_perfil'),
					'perfil_id'							=> $session->offsetGet('perfil_id'),
					'perfil_nombre'  					=> $session->offsetGet('perfil_nombre'),
					'cliente_id'						=> $session->offsetGet('cliente_id'),
					'cliente_nombre'					=> $session->offsetGet('cliente_nombre'),
					'layout'							=> $session->offsetGet('layout'),
					'cliente_nombre'					=> $session->offsetGet('cliente_nombre'),
					'cliente_seleccion_marcacion_sec'	=> $session->offsetGet('cliente_seleccion_marcacion_sec'),
					'cliente_seleccion_marcacion_nombre'=> $session->offsetGet('cliente_seleccion_marcacion_nombre'),
					'cliente_seleccion_agencia_id'		=> $session->offsetGet('cliente_seleccion_agencia_id'),
//					'cliente_seleccion_marcacion_puntocorte'=> $session->offsetGet('cliente_seleccion_marcacion_puntocorte'),
					'cliente_pedido_cab_id_actual'		=> $session->offsetGet('cliente_pedido_cab_id_actual'),
					'vendedor_usuario_id'				=> $session->offsetGet('vendedor_usuario_id'),
					'vendedor_nombre_usuario'			=> $session->offsetGet('vendedor_nombre_usuario'),
					'vendedor_username'					=> $session->offsetGet('vendedor_username'),
					'cliente_usuario_id'				=> $session->offsetGet('cliente_usuario_id'),
					'cliente_usuario_nombre'			=> $session->offsetGet('cliente_usuario_nombre'),
					'cliente_usuario_username'			=> $session->offsetGet('cliente_usuario_username'),
					'cliente_usuario_inventario_id'		=> $session->offsetGet('inventario_id'),				//2015-09-29 NUEVO
					'cliente_usuario_calidad_id'		=> $session->offsetGet('cliente_usuario_calidad_id'),	//2015-09-29 NUEVO
					'cliente_usuario_grupo_precio_cab_id'=> $session->offsetGet('grupo_precio_cab_id'),			//2015-09-29 NUEVO
					'cliente_usuario_grupo_dispo_cab_id'=> $session->offsetGet('grupo_dispo_cab_id'),			//2015-09-29 NUEVO
					'cliente_usuario_punto_corte'		=> $session->offsetGet('cliente_usuario_punto_corte'),	//2015-09-29 NUEVO
					'marcacion_tipo_caja_default_id'	=> $session->offsetGet('marcacion_tipo_caja_default_id'),	//2015-09-29 NUEVO
//					'cliente_calidad_id'				=> $session->offsetGet('cliente_calidad_id'),
					'atributos'							=> $session->offsetGet('atributos')
					];
		return $result;
	}//end function getRecord

		

	
	/*-----------------------------------------------------------------------------*/
	public function isLogin()
	/*-----------------------------------------------------------------------------*/
	{
		$session = new Container('usuario');
		if (!isset($session)){
			$this->getController()->flashmessenger()->addMessage("Debe iniciar sesión");
			$this->getController()->plugin('redirect')->toRoute('home'); //seguridad-login;
			return false;
		}
		return true;
	}//end function isLogin
	
	
	/*-----------------------------------------------------------------------------*/
	public function isLoginClienteVendedor()
	/*-----------------------------------------------------------------------------*/
	{		
		//Si no es cliente ni vendedor
		if ((!$this->isLoginCliente())&&(!$this->isLoginVentas()))
		{
			$this->getController()->flashmessenger()->addMessage("Debe iniciar sesión");
			$this->getController()->plugin('redirect')->toRoute('home'); //seguridad-login;
			return false;
		}
		return true;
	}//end function isLogin	

	
	
	
	/*-----------------------------------------------------------------------------*/
	/**
	 * Me redirecciona al home siempre y cuando no sea perfil administrador
	 * 
	 * @param boolean $redirect
	 * @return boolean
	 */
	public function isLoginAdmin($redirect = false)
	/*-----------------------------------------------------------------------------*/
	{
		if ($this->isLogin())
		{
			if ($this->getUserPerfilId() == \Application\Constants\Perfil::ID_ADMIN)
			{	
				$respuesta = true;
			}else{
				$respuesta = false;
			}//end if
		}//end if
		
		
		if (($respuesta==false)&&($redirect==true))
		{
			$this->getController()->flashmessenger()->addMessage("Debe iniciar sesión");
			$this->getController()->plugin('redirect')->toRoute('home'); //seguridad-login;
		}//end if
		
		return $respuesta;
	}//end function isLoginAdmin

	
	/*-----------------------------------------------------------------------------*/
	public function isLoginCliente()
	/*-----------------------------------------------------------------------------*/
	{
		if ($this->isLogin())
		{
			if ($this->getUserPerfilId() == \Application\Constants\Perfil::ID_CLIENTE)
			{
				return true;
			}//end if
		}//end if
		return false;
	}//end function isLoginCliente
	

	/*-----------------------------------------------------------------------------*/
	public function isLoginVentas()
	/*-----------------------------------------------------------------------------*/
	{
		if ($this->isLogin())
		{
			if (($this->getUserPerfilId() == \Application\Constants\Perfil::ID_VENTAS) || ($this->getUserPerfilId() == \Application\Constants\Perfil::ID_ADMIN))
			{
				return true;
			}//end if
		}//end if
		return false;
	}//end function isLoginVentas
	
	
	public function isPerfil($perfil)
	{
		if ($this->isLogin())
		{
			if (($this->getUserPerfilId() == $perfil))
			{
				return true;
			}//end if
		}//end if
		return false;		
	}
	
	
	public function gotoHome()
	{
		$this->getController()->plugin('redirect')->toRoute('home'); //seguridad-login;		
	}//end function gotoHome
	
	
	/*-----------------------------------------------------------------------------*/
	public function logout()
	/*-----------------------------------------------------------------------------*/
	{
		$session = new \Zend\Session\Container();
		$session->getManager()->destroy();

		$this->getController()->flashmessenger()->addMessage("Ud. cerró la sesión");
		$this->getController()->plugin('redirect')->toRoute('home'); //seguridad-login;
		 
		return true;
	}//end function logout
	


	/*-----------------------------------------------------------------------------*/
	public function existeAtributo($atribute)
	/*-----------------------------------------------------------------------------*/
	{
		$cadena_atributos =  $this->getAtributos();
		$arr_atributos	  = $porciones = explode("|", $cadena_atributos);
		
		return in_array($atribute, $arr_atributos, true);		
	}//end function existeAtributo



}//end class