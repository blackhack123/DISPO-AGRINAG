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
	public function getClienteSeleccionMarcacionId()	{return $this->getRecord()['cliente_seleccion_marcacion_id'];}
	public function getClienteSeleccionMarcacionNombre(){return $this->getRecord()['cliente_seleccion_marcacion_nombre'];}	
	public function getClienteSeleccionAgenciaId()		{return $this->getRecord()['cliente_seleccion_agencia_id'];}
	public function getClientePedidoCabIdActual()		{return $this->getRecord()['cliente_pedido_cab_id_actual'];}
	public function getVendedorUsuarioId()				{return $this->getRecord()['vendedor_usuario_id'];}
	public function getVendedorNombre()					{return $this->getRecord()['vendedor_nombre_usuario'];}
	public function getVendedorUserName()				{return $this->getRecord()['vendedor_username'];}
	public function getUserLayout(){
		$record = $this->getRecord();
		$this->getController()->layout()->identidad_usuario = $record;
		return $record['layout'];
	}
	
	public function setClienteSeleccionMarcacionSec($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_marcacion_id', $valor);
	}//end function
	public function setClienteSeleccionMarcacionNombre($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_marcacion_nombre', $valor);
	}//end function
	
	public function setClienteSeleccionAgenciaId($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_seleccion_agencia_id', $valor);
	}//end function
	public function setClientePedidoCabIdActual($valor){
		$session = new Container('usuario');
		$session->offsetSet('cliente_pedido_cab_id_actual', $valor);
	}//end function	

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
					'cliente_seleccion_marcacion_id'	=> $session->offsetGet('cliente_seleccion_marcacion_id'),
					'cliente_seleccion_marcacion_nombre'=> $session->offsetGet('cliente_seleccion_marcacion_nombre'),
					'cliente_seleccion_agencia_id'		=> $session->offsetGet('cliente_seleccion_agencia_id'),
					'cliente_pedido_cab_id_actual'		=> $session->offsetGet('cliente_pedido_cab_id_actual'),
					'vendedor_usuario_id'				=> $session->offsetGet('vendedor_usuario_id'),
					'vendedor_nombre_usuario'			=> $session->offsetGet('vendedor_nombre_usuario'),
					'vendedor_username'					=> $session->offsetGet('vendedor_username'),
					];
		return $result;
	}//end function getRecord

		

	
	/*-----------------------------------------------------------------------------*/
	public function isLogin()
	/*-----------------------------------------------------------------------------*/
	{
		$session = new Container('usuario');
		if (!isset($session)){
			$this->getController()->flashmessenger()->addMessage("Debe de Iniciar Session");
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
		if ((!$this->isLoginCliente())&&(!$this->isLoginCliente()))
		{
			$this->getController()->flashmessenger()->addMessage("Debe de Iniciar Session");
			$this->getController()->plugin('redirect')->toRoute('home'); //seguridad-login;
			return false;
		}
		return true;
	}//end function isLogin	

	/*-----------------------------------------------------------------------------*/
	public function isLoginAdmin()
	/*-----------------------------------------------------------------------------*/
	{
		if ($this->isLogin())
		{
			if ($this->getUserPerfilId() == \Application\Constants\Perfil::ID_ADMIN)
			{	
				return true;
			}//end if
		}//end if
		return false;
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
			if ($this->getUserPerfilId() == \Application\Constants\Perfil::ID_VENTAS)
			{
				return true;
			}//end if
		}//end if
		return false;
	}//end function isLoginVentas
	
	
	
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
}//end class