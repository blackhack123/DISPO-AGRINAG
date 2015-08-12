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
					
				//Se consulta la marcacion y la agencia de carga del detalle de la factura
/*				$reg_det	= $PedidoBO->consultarPedidoDetUltimoRegistro($reg_pedido['id']);//end if
				if ($reg_det)
				{
					if (!empty($reg_det['marcacion_sec']))
					{
						$session->offsetSet('cliente_seleccion_marcacion_sec', $reg_det['marcacion_sec']);
					}else{
						$session->offsetSet('cliente_seleccion_marcacion_sec', null);
					}//end if
					$session->offsetSet('cliente_seleccion_marcacion_nombre', $reg_det['marcacion_nombre']);
					if (!empty($reg_det['marcacion_sec']))
					{					
						$session->offsetSet('cliente_seleccion_agencia_id', $reg_det['agencia_carga_id']);
					}else{
						$session->offsetSet('cliente_seleccion_agencia_id', null);
					}
				}//end if
*/			}//end if			
			
			
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
			$condiciones['perfil_id']						= $this->params()->fromPost('busqueda_perfil_id','');
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
			$usuario_id		= $json['usuario_id'];
	
			$row					= $UsuarioBO->consultar($usuario_id, \Application\Constants\ResultType::MATRIZ);
	
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
	}//end function consultarAction
	
	
	
	
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
			$UsuarioData->setId			($json['id']);
			$UsuarioData->setNombre		($json['nombre']);
			$UsuarioData->setUsername	($json['username']);
			$UsuarioData->setPassword	($json['password']);
			$UsuarioData->setEmail		($json['email']);
			$UsuarioData->setPerfilId	($json['perfil_id']);
			$UsuarioData->setEstado		($json['estado']);
	
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
	}//end function consultarAction
	

}
