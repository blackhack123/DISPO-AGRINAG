<?php

namespace Seguridad\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Seguridad\BO\UsuarioBO;
use Seguridad\Data\UsuarioData;
use Application\Classes\CorreoElectronico;
use Dispo\BO\PedidoBO;
use Seguridad\BO\PerfilBO;
use Dispo\BO\GrupoDispoCabBO;
use Dispo\BO\GrupoPrecioCabBO;
use Dispo\BO\InventarioBO;
use Dispo\BO\CalidadBO;
use Dispo\BO\ClienteBO;

class UsuarioController extends AbstractActionController
{
	
	
	public function getcomboPorClienteAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
				
			$UsuarioBO = new UsuarioBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();  //Solo el Vendedor Puede hacer este procedimiento
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id 				= $json['cliente_id'];
	
			$opciones = $UsuarioBO->getComboPorCliente($cliente_id, $texto_primer_elemento);
	
			$response = new \stdClass();
			$response->opciones				= $opciones;
			$response->respuesta_code 		= 'OK';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcomboAction

	
	
	public function asignarClienteUsuarioAction()
	{
		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();

			$EntityManagerPlugin = $this->EntityManagerPlugin();
			$UsuarioBO 	= new UsuarioBO();			
			$PedidoBO	= new PedidoBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
				
			$cliente_id		= $json['cliente_id'];
			$usuario_id		= $json['usuario_id'];
			
			$reg_usuario = $UsuarioBO->consultar($usuario_id, \Application\Constants\ResultType::MATRIZ);

			$SesionUsuarioPlugin->setUserClienteId				($cliente_id);
			$SesionUsuarioPlugin->setUserClienteNombre			($reg_usuario['cliente_nombre']);			
			$SesionUsuarioPlugin->setClienteUsuarioId			($usuario_id);
			$SesionUsuarioPlugin->setClienteUsuarioNombre		($reg_usuario['nombre']);
			$SesionUsuarioPlugin->setClienteUsuarioUserName		($reg_usuario['username']);
			//$SesionUsuarioPlugin->setClienteCalidadId			($reg_usuario['calidad_id']);
			//-------------2015-09-29 Se asignan las nuevas variables de session --------------------
			$SesionUsuarioPlugin->setUserInventarioId			($reg_usuario['inventario_id']);
			$SesionUsuarioPlugin->setUserCalidadId				($reg_usuario['calidad_id']);
			$SesionUsuarioPlugin->setUserGrupoPrecioCabId		($reg_usuario['grupo_precio_cab_id']);
			$SesionUsuarioPlugin->setUserGrupoDispoCabId		($reg_usuario['grupo_dispo_cab_id']);
			$SesionUsuarioPlugin->setUserPuntoCorte				($reg_usuario['punto_corte']);
			//---------------------------------------------------------------------------------------
			
			$response = new \stdClass();
			$response->respuesta_code 			= 'OK';
			$response->cliente_id				= $cliente_id;
			$response->cliente_usuario_id		= $usuario_id;
			$response->cliente_nombre			= $reg_usuario['cliente_nombre'];
			$response->cliente_usuario_nombre	= $reg_usuario['nombre'];
			$response->cliente_usuario_username	= $reg_usuario['username'];
			
			//Se consulta si existe un pedido comprando para reactivar y seguir en la compra
			$reg_pedido =  $PedidoBO->consultarUltimoPedidoComprando($cliente_id);
			//Si existe el registro en estado comprando lo asigna a la variable de sesion para que lo utilize
			if ($reg_pedido)
			{
				$session = new Container('usuario');
				$session->offsetSet('cliente_pedido_cab_id_actual', $reg_pedido['id']);
				$session->offsetSet('cliente_seleccion_marcacion_sec', $reg_pedido['marcacion_sec']);
				$session->offsetSet('cliente_seleccion_marcacion_nombre', $reg_pedido['marcacion_nombre']);
				$session->offsetSet('cliente_seleccion_agencia_id', $reg_pedido['agencia_carga_id']);
				$session->offsetSet('marcacion_tipo_caja_default_id', $reg_pedido['tipo_caja_default_id']);
			}//end if

			$json = new JsonModel(get_object_vars($response));
			return $json;			

		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end seleccionarClienteUsuarioAction
	
	/*
	public function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
				
			$UsuarioBO = new UsuarioBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
			$usuario_id = null;
	
			$opciones = $UsuarioBO->getComboTodos($usuario_id, $texto_primer_elemento);
	
			$response = new \stdClass();
			$response->opciones				= $opciones;
			$response->respuesta_code 		= 'OK';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcomboAction
	
	*/
	
	public function mantenimientoAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
	
			//Controla el acceso a la informacion, solo accedera si es administrador
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$respuesta =  $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$UsuarioBO				= new UsuarioBO();
			$PerfilBO				= new PerfilBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PerfilBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$condiciones['criterio_busqueda']			= $this->params()->fromPost('criterio_busqueda','');
			$condiciones['estado']						= $this->params()->fromPost('busqueda_estado','');
			$condiciones['perfil_id']					= $this->params()->fromPost('busqueda_perfil_id','');
			$condiciones['solo_vendedor_administrador']	= 1;
				
			$result 		= $UsuarioBO->listado($condiciones);
				
			$viewModel->criterio_busqueda			= $condiciones['criterio_busqueda'];
			$viewModel->cbo_busqueda_perfil			=  $PerfilBO->getComboPerfilRestringido('','&lt;PERFIL&gt;');
			$viewModel->cbo_busqueda_estado			=  \Application\Classes\ComboGeneral::getComboEstado($condiciones['estado'],"&lt;ESTADO&gt;");
			
			$viewModel->result				= $result;
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('seguridad/usuario/mantenimiento.phtml');
			return $viewModel;
				
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function
	
	
	
	public function nuevodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO 				= new PerfilBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			
			$response = new \stdClass();
			$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido("","");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado("","");
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function nuevodataAction
	
	
	
	public function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioData			= new UsuarioData();
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO 				= new PerfilBO();
			
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
				
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$UsuarioData->setId					($json['id']);
			$UsuarioData->setNombre				($json['nombre']);
			$UsuarioData->setUsername			($json['username']);
			$UsuarioData->setPassword			($json['password']);
			$UsuarioData->setEmail				($json['email']);
			$UsuarioData->setLoginFox			($json['login_fox']);
			$UsuarioData->setPerfilId			($json['perfil_id']);
			$UsuarioData->setEstado				($json['estado']);
			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$UsuarioData->setUsuarioIngId($usuario_id);
					$result = $UsuarioBO->ingresar($UsuarioData);
					break;
	
				case 'M':
					$UsuarioData->setUsuarioModId($usuario_id);
					$result = $UsuarioBO->modificar($UsuarioData);
					break;
	
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $UsuarioBO->consultar($json['id'], \Application\Constants\ResultType::MATRIZ);
			}else{
				$row	= null;
			}//end if
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
			if ($row)
			{
				$response->row					= $row;
				$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido($row['perfil_id'], " ");
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			}else{
				$response->row					= null;
				$response->cbo_tipo				= '';
				$response->cbo_estado			= '';
			}//end if
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function grabardataAction
	
	
	
	public function consultardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO 				= new PerfilBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
				
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$id		= $json['id'];
	
			$row					= $UsuarioBO->consultar($id, \Application\Constants\ResultType::MATRIZ);
				
				
				
			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido($row['perfil_id'],"");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function consultardataAction
	
	
	
	/*
	 * *****************************************************************
	 * 		FUNCIONES USUARIO DEL MOD CLIENTE(CLIENTE->USUARIO)
	 * 
	 * *****************************************************************
	 */
	public function nuevousuarionormaldataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
			//$PerfilBO 				= new PerfilBO();
			$GrupoDispoCabBO		= new GrupoDispoCabBO();
			$GrupoPrecioCabBO		= new GrupoPrecioCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			$grupodispo			= null;
			$grupoprecio		= null;
			$inventario_id		= null;
			$calidad_id			= null;
			
			$response = new \stdClass();
			//$response->cbo_perfil_id			= $PerfilBO->getComboPerfilRestringido("","");
			$response->cbo_estado				= \Application\Classes\ComboGeneral::getComboEstado("","");
			//$response->cbo_grupo_dispo			= $GrupoDispoCabBO->getComboGrupoDispo($grupodispo, "&lt;Seleccione&gt;");
			//$response->cbo_grupo_precio			= $GrupoPrecioCabBO->getComboGrupoPrecio($grupoprecio, "&lt;Seleccione&gt;");
			$response->cbo_inventario_id		= $InventarioBO->getCombo($inventario_id, "&lt;Seleccione&gt;");
			$response->cbo_calidad				= $CalidadBO->getComboCalidad($calidad_id, "&lt;Seleccione&gt;");
			$response->cbo_grupo_dispo			= '<option value="">&lt;Seleccione&gt;</option>';
			$response->cbo_grupo_precio			= '<option value="">&lt;Seleccione&gt;</option>';
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function nuevodataAction
	
	
	
	public function consultarusuarionormaldataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO 				= new PerfilBO();
			$GrupoDispoCabBO		= new GrupoDispoCabBO();
			$GrupoPrecioCabBO		= new GrupoPrecioCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$id		= $json['id'];
	
			$row					= $UsuarioBO->consultar($id, \Application\Constants\ResultType::MATRIZ);
			

			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido($row['perfil_id'],"");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
//			$response->opciones_dispo		= $GrupoDispoCabBO->getComboGrupoDispo($row['grupo_dispo_cab_id'], "&lt;Seleccione&gt;");
//			$response->opciones_precio		= $GrupoPrecioCabBO->getComboGrupoPrecio($row['grupo_precio_cab_id'], "&lt;Seleccione&gt;");
			$response->opciones_dispo 		= $GrupoDispoCabBO->getComboPorInventario($row['grupo_dispo_cab_id'], $row['inventario_id'], $row['calidad_id']);
			$response->opciones_precio 		= $GrupoPrecioCabBO->getComboPorInventario($row['grupo_precio_cab_id'], $row['inventario_id'], $row['calidad_id']);
			$response->cbo_inventario_id	= $InventarioBO->getCombo($row['inventario_id'], "&lt;Seleccione&gt;");
			$response->cbo_calidad			= $CalidadBO->getComboCalidad($row['calidad_id'], "&lt;Seleccione&gt;");
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function consultardataAction
	
	
	
	
	public function grabarusuarionormaldataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioData			= new UsuarioData();
			$UsuarioBO 				= new UsuarioBO();
			//$PerfilBO 				= new PerfilBO();
			$GrupoDispoCabBO		= new GrupoDispoCabBO();
			$GrupoPrecioCabBO		= new GrupoPrecioCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$UsuarioData->setId					($json['id']);
			$UsuarioData->setClienteId			($json['cliente_id']);
			$UsuarioData->setNombre				($json['nombre']);
			$UsuarioData->setUsername			($json['username']);
			$UsuarioData->setPassword			($json['password']);
			$UsuarioData->setEmail				($json['email']);
			$UsuarioData->setPerfilId			(\Application\Constants\Perfil::ID_CLIENTE);
			$UsuarioData->setEstado				($json['estado']);
			$UsuarioData->setGrupoDispoCabId	($json['grupo_dispo_cab_id']);
			$UsuarioData->setGrupoPrecioCabId	($json['grupo_precio_cab_id']);
			$UsuarioData->setInventarioId		($json['inventario_id']);
			$UsuarioData->setCalidadId			($json['calidad_id']);
			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$UsuarioData->setUsuarioIngId($usuario_id);
					$result = $UsuarioBO->ingresar($UsuarioData);
					break;
						
				case 'M':
					$UsuarioData->setUsuarioModId($usuario_id);
					$result = $UsuarioBO->modificar($UsuarioData);
					break;
						
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $UsuarioBO->consultar($json['id'], \Application\Constants\ResultType::MATRIZ);
			}else{
				$row	= null;
			}//end if
				
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
			if ($row)
			{
				$response->row					= $row;
				//$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido($row['perfil_id'], " ");
				$response->cbo_cbo_grupo_dispo	= $GrupoDispoCabBO->getComboGrupoDispo($row['grupo_dispo_cab_id'], " ");
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			}else{
				$response->row					= null;
				$response->cbo_tipo				= '';
				$response->cbo_estado			= '';
			}//end if
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function grabardataAction
	
	
	public function getcomboDispoAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoDispoCabBO = new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();  //Solo el Vendedor Puede hacer este procedimiento
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			//$texto_primer_elemento		= null;
			$grupo_dispo_cab_id			= null;
			$inventario_id 				= $json['inventario_id'];
			$calidad_id 				= $json['calidad_id'];
	
			$opciones_dispo = $GrupoDispoCabBO->getComboPorInventario($grupo_dispo_cab_id, $inventario_id, $calidad_id);
	
			$response = new \stdClass();
			$response->opciones_dispo				= $opciones_dispo;
			$response->respuesta_code 		= 'OK';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcomboAction
	
	
	public function getcomboPrecioAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoPrecioCabBO = new GrupoPrecioCabBO();
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();  //Solo el Vendedor Puede hacer este procedimiento
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			//$texto_primer_elemento		= null;
			$grupo_precio_cab_id		= null;
			$inventario_id 				= $json['inventario_id'];
			$calidad_id 				= $json['calidad_id'];
	
			$opciones_precio = $GrupoPrecioCabBO->getComboPorInventario($grupo_precio_cab_id, $inventario_id, $calidad_id);
	
			$response = new \stdClass();
			$response->opciones_precio				= $opciones_precio;
			$response->respuesta_code 		= 'OK';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcomboAction
	
	
	public function getClienteFacturaAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$ClienteBO = new $ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();  //Solo el Vendedor Puede hacer este procedimiento
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			//$texto_primer_elemento		= null;
			//$grupo_dispo_cab_id			= null;
			$cliente_factura_id 		= $json['cliente_factura_id'];
			$nombre_cliente 			= $json['nombre_cliente'];
	
			$clientes = $ClienteBO->getClienteFactura($cliente_factura_id, $nombre_cliente);
			
			$response = new \stdClass();
			$response->clientes				= $clientes;
			$response->respuesta_code 		= 'OK';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function getcomboAction
	
	
	/*-----------------------------------------------------------------------------*/
	public function listadodataAction()
	/*-----------------------------------------------------------------------------*/
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$UsuarioBO = new UsuarioBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
			
			
			
			$request 		= $this->getRequest();
			$perfil_id     	= $request->getQuery('perfil');
			$nombre      	= $request->getQuery('nombre', "");
			$username      	= $request->getQuery('username', "");
			$email     		= $request->getQuery('email', "");
			$estado 		= $request->getQuery('estado', "");
			$cliente_id    	= $request->getQuery('cliente_id', "");
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$UsuarioBO->setPage($page);
			$UsuarioBO->setLimit($limit);
			$UsuarioBO->setSidx($sidx);
			$UsuarioBO->setSord($sord);
			$condiciones = array(
					"criterio_busqueda"		=> $nombre,
					"username"				=> $nombre,
					"estado" 				=> $estado,
					"cliente_id"			=> $cliente_id,
					"perfil_id"				=> $perfil_id
			);
			$result = $UsuarioBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["id"] 				= $row["id"];
				$row2["nombre"] 			= trim($row["nombre"]);
				$row2["username"] 			= trim($row["username"]);
				$row2["email"] 				= trim($row["email"]);
				$row2["inventario_id"] 		= trim($row["inventario_id"]);
				$row2["grupo_dispo"] 		= trim($row["grupo_dispo"]);
				$row2["grupo_precio"]		= trim($row["grupo_precio"]);
				$row2["nombre_calidad"]		= trim($row["nombre_calidad"]);
				$row2["login_fox"] 			= trim($row["login_fox"]);
				$row2["perfil_nombre"] 		= $row["perfil_nombre"];
				$row2["estado"] 			= $row["estado"];
				$response->rows[$i] = $row2;
				$i++;
			}//end foreach
			$tot_reg = $i;
			$response->total 	= ceil($tot_reg/$limit);
			$response->page 	= $page;
			$response->records 	= $tot_reg;
			$json = new JsonModel(get_object_vars($response));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function listadodataAction
	
	

	
	public function desvinculargrupodispoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 		= new UsuarioBO();
	
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			//Recibe las variables del Json
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$formData 			= $json['formData'];
			$grupo_dispo_cab_id	= $formData['grupo_dispo_cab_id'];
			$grid_data 			= $json['grid_data'];
	
			//Prepara el Buffer de datos antes de llamar al BO
			$ArrClienteAgenciaCargaData   	= array();
			foreach ($grid_data as $reg)
			{
				$UsuarioData = new UsuarioData();
				$UsuarioData->setId 			($reg['usuario_id']);
				$UsuarioData->setGrupoDispoCabId($grupo_dispo_cab_id);
				$UsuarioData->setUsuarioModId	($usuario_id);
	
				$ArrUsuarioData[] = $UsuarioData;
			}//end foreach
	
			//Graba
			$result = $UsuarioBO->desvincularGrupoDispo($ArrUsuarioData);

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			/*$response->validacion_code 		= $result['validacion_code'];
			 $response->respuesta_mensaje	= $result['respuesta_mensaje'];
			 */
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function desvinculargrupodispo
		

	
	
	public function vinculargrupodispoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 		= new UsuarioBO();
	
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			//Recibe las variables del Json
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$formData 			= $json['formData'];
			$grupo_dispo_cab_id	= $formData['grupo_dispo_cab_id'];
			$grid_data 			= $json['grid_data'];
	
			//Prepara el Buffer de datos antes de llamar al BO
			$ArrClienteAgenciaCargaData   	= array();
			foreach ($grid_data as $reg)
			{
				$UsuarioData = new UsuarioData();
				$UsuarioData->setId 			($reg['usuario_id']);
				$UsuarioData->setGrupoDispoCabId($grupo_dispo_cab_id);
				$UsuarioData->setUsuarioModId	($usuario_id);
	
				$ArrUsuarioData[] = $UsuarioData;
			}//end foreach
	
			//Graba
			$result = $UsuarioBO->vincularGrupoDispo($ArrUsuarioData);
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			/*$response->validacion_code 		= $result['validacion_code'];
			 $response->respuesta_mensaje	= $result['respuesta_mensaje'];
			 */
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function desvinculargrupodispo

	
	

	public function vinculargrupoprecioAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
	
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			//Recibe las variables del Json
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$formData 				= $json['formData'];
			$grupo_precio_cab_id	= $formData['grupo_precio_cab_id'];
			$grid_data 				= $json['grid_data'];

			//Prepara el Buffer de datos antes de llamar al BO
			$ArrUsuarioData   	= array();
			foreach ($grid_data as $reg)
			{
				$UsuarioData = new UsuarioData();
				$UsuarioData->setId 				($reg['id']);
				$UsuarioData->setGrupoPrecioCabId 	($grupo_precio_cab_id);
				$UsuarioData->setUsuarioModId		($usuario_id);

				$ArrUsuarioData[] = $UsuarioData;
			}//end foreach
	
			//Graba
			$result = $UsuarioBO->vincularGrupoPrecio($ArrUsuarioData);
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			/*$response->validacion_code 		= $result['validacion_code'];
			 $response->respuesta_mensaje	= $result['respuesta_mensaje'];
			 */
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function vinculargrupoprecioAction
	
	
	
	public function desvinculargrupoprecioAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
	
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			//Recibe las variables del Json
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$formData 			= $json['formData'];
			$grupo_precio_cab_id	= $formData['grupo_precio_cab_id'];
			$grid_data 			= $json['grid_data'];
	
			//Prepara el Buffer de datos antes de llamar al BO
			$ArrUsuarioData   	= array();
			foreach ($grid_data as $reg)
			{
				$UsuarioData = new UsuarioData();
				$UsuarioData->setId 				($reg['id']);
				$UsuarioData->setGrupoPrecioCabId	($grupo_precio_cab_id);
				$UsuarioData->setUsuarioModId		($usuario_id);
	
				$ArrUsuarioData[] = $UsuarioData;
			}//end foreach
	
			//Graba
			$result = $UsuarioBO->desvincularGrupoPrecio($ArrUsuarioData);
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';

			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function desvinculargrupoprecioAction

	
	
	public function enviaremailmasivoAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$UsuarioBO = new UsuarioBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$grupo_dispo_cab_id		= $json['grupo_dispo_cab_id'];
		
			$nro_regs = $UsuarioBO->actualizarEstadoEnviarDispoPorGrupoDispo($grupo_dispo_cab_id, 1);

			$response = new \stdClass();
//			$response->opciones				= $opciones;
			$response->respuesta_code 		= 'OK';
			$response->nro_regs				= $nro_regs;
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function enviaremailmasivo	
	
	
	
	function exportarexcelAction()
	{
		try
		{
			$viewModel 			= new ViewModel();
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$UsuarioBO 		= new UsuarioBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
			
				
			$request 			= $this->getRequest();
			$criterio_busqueda 	= $request->getQuery('criterio_busqueda', '');
			$estado		 		= $request->getQuery('estado', '');
			$perfil_id  		= $request->getQuery('perfil_id', '');
				
			$condiciones = array(
					"criterio_busqueda"		=> $criterio_busqueda,
					"estado"				=> $estado,
					"perfil_id"				=> $perfil_id
			);
				
			$result = $UsuarioBO->generarExcel($condiciones);
			exit;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function exportarexcelAction
	
}
