<?php
namespace Seguridad\Controller;

use Seguridad\BO\UsuarioBO;

use Zend\Mvc\Controller\AbstractActionController;
use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
//use Doctrine\ORM\EntityManager;
use Zend\Session\Container;
use Dispo\BO\PedidoBO;

class LoginController extends AbstractActionController
{

	private $_ipAcceso;
	private $_nombreHost;
	private $_agenteUsuario;


 	private function capturaDatosRedUsuario()
 	{
 		$nombreHost = "";
 		$ipFinalUsuario = "";
 		
 		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
 		{
 			$ipUsuario = explode(',',$_SERVER["HTTP_X_FORWARDED_FOR"]);
 			
 			for($i=0; $i<count($ipUsuario); $i++)
 			{
	 			if(trim($ipUsuario[$i])!="127.0.0.1" && isset($ipUsuario[$i]))
	 			{
		 			if($i>0)
		 			{
		 				$ipFinalUsuario .= ",";
		 			}
		 			$ipFinalUsuario .= $ipUsuario[$i];
		 			$tmp = gethostbyaddr($ipUsuario[$i]);
		 			if(isset($tmp))
		 			{
		 				$nombreHost = gethostbyaddr($ipUsuario[$i]);
		 			}
	 			}
 			}
 			$this->_ipAcceso = $ipFinalUsuario;
 			$this->_nombreHost = $nombreHost;
 		}
 		else
 		{
 			$this->_ipAcceso = $_SERVER["REMOTE_ADDR"];
 			$this->_nombreHost = gethostbyaddr($_SERVER["REMOTE_ADDR"]); 			
 		}
 		$this->_agenteUsuario = $_SERVER["HTTP_USER_AGENT"];
 	}
	
 	
 	
	public function autenticarAction()
	{
		try{
				$EntityManagerPlugin = $this->EntityManagerPlugin();
				
				$UsuarioBO 	= new UsuarioBO();
				$PedidoBO 	= new PedidoBO();
				
				$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				$PedidoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
				$request = $this->getRequest();
				$this->capturaDatosRedUsuario();
				if ($this->getRequest()->isPost()){
					$usuario = $this->getRequest()->getPost('username', null);
					$clave = $this->getRequest()->getPost('password', null);

					$ipAcceso = $this->_ipAcceso;
					$nombreHost = $this->_nombreHost;
					$agenteUsuario = $this->_agenteUsuario;					
				
					if (empty($usuario)||empty($clave)){
						$this->flashmessenger()->addMessage("Usuario y/o Clave no válidos");
						return $this->redirect()->toRoute('home');
					}//end if

					$rsUsuario = $UsuarioBO->login($usuario, $clave, $ipAcceso, $nombreHost, $agenteUsuario);
//					$menuDinamico = $UsuarioBO->getMenuDinamicoPerfil($rsUsuario['id'], $dispositivo_id, 1);
					
					if (empty($rsUsuario['id']))
					{
						$this->flashmessenger()->addMessage($rsUsuario['respuesta_mensaje']);
						return $this->redirect()->toRoute('home');
					}
					else
					{
						switch($rsUsuario['respuesta_codigo'])
						{
							case "OK":
								$session = new Container('usuario');
								$session->offsetSet('usuario_id', $rsUsuario['id']);
								$session->offsetSet('usuario_nombre', $rsUsuario['usuario_nombre']);
								$session->offsetSet('username', $rsUsuario['username']);
								$session->offsetSet('email', $rsUsuario['email']);
								$session->offsetSet('perfil_id', $rsUsuario['perfil_id']);
								$session->offsetSet('perfil_nombre', $rsUsuario['perfil_nombre']);
								$session->offsetSet('cliente_id', $rsUsuario['cliente_id']);
								$session->offsetSet('cliente_nombre', $rsUsuario['cliente_nombre']);
								$session->offsetSet('cliente_seleccion_marcacion_sec', null);
								$session->offsetSet('cliente_seleccion_agencia_id', null);
								//Variables de sesion usadas por el usuario de vendedor para emular a los clientes
								$session->offsetSet('cliente_usuario_id', null);
								$session->offsetSet('cliente_usuario_nombre', null);
								$session->offsetSet('cliente_usuario_username', null);								

								switch($rsUsuario['perfil_id'])
								{
									case \Application\Constants\Perfil::ID_CLIENTE: //CLIENTE								
										//Se setea las variables del cliente, que solo es usuario en la emulacion del usuario vendedor
										//pero se lo deja inicializado para tener constancia de su existencia
										$session->offsetSet('cliente_usuario_id', $rsUsuario['id']);
										$session->offsetSet('cliente_usuario_nombre', $rsUsuario['usuario_nombre']);
										$session->offsetSet('cliente_usuario_username', $rsUsuario['username']);
										
										//Se consulta si existe un pedido comprando para reactivar y seguir en la compra
										$reg_pedido =  $PedidoBO->consultarUltimoPedidoComprando( $rsUsuario['cliente_id']);
										//Si existe el registro en estado comprando lo asigna a la variable de sesion para que lo utilize
										if ($reg_pedido)
										{
											$session->offsetSet('cliente_pedido_cab_id_actual', $reg_pedido['id']);
											
											//Se consulta la marcacion y la agencia de carga del detalle de la factura
											$reg_det	= $PedidoBO->consultarPedidoDetUltimoRegistro($reg_pedido['id']);//end if
											if ($reg_det)
											{
												$session->offsetSet('cliente_seleccion_marcacion_sec', $reg_det['marcacion_sec']);
												$session->offsetSet('cliente_seleccion_marcacion_nombre', $reg_det['marcacion_nombre']);
												$session->offsetSet('cliente_seleccion_agencia_id', $reg_det['agencia_carga_id']);
											}//end if
											
										}//end if
										
										$session->offsetSet('layout','layout/pedido');										
										unset($reg_pedido);
										break;

									case \Application\Constants\Perfil::ID_VENTAS:
										$session->offsetSet('vendedor_usuario_id', $rsUsuario['id']);
										$session->offsetSet('vendedor_nombre_usuario', $rsUsuario['usuario_nombre']);
										$session->offsetSet('vendedor_username', $rsUsuario['username']);
										$session->offsetSet('layout','layout/pedido');
										break;

									case \Application\Constants\Perfil::ID_ADMIN: //ADMIN
										$session->offsetSet('vendedor_usuario_id', $rsUsuario['id']);
										$session->offsetSet('vendedor_nombre_usuario', $rsUsuario['usuario_nombre']);
										$session->offsetSet('vendedor_username', $rsUsuario['username']);
										$session->offsetSet('layout','layout/pedido');
										//$session->offsetSet('layout','layout/admin');
										break;
								}//end switch								

								return $this->redirect()->toRoute('load-app');
								break;

							case "CAMBIO":
						/*		$session = new Container('usuario');
								$session->offsetSet('usuario_id', $rsUsuario['id']);
								$session->offsetSet('usuario_nombre', $rsUsuario['nombre_usuario']);
								$session->offsetSet('username', $rsUsuario['nombre_persona']);
								$session->offsetSet('email', $rsUsuario['nombre_persona']);
								$session->offsetSet('perfil_id', $rsUsuario['perfil_id']);
								$session->offsetSet('perfil_nombre', $rsUsuario['nombre_perfil']);
								$session->offsetSet('cliente_id', $rsUsuario['perfil_siglas']);
								$session->offsetSet('cliente_nombre', $rsUsuario['grupo_empresarial_id']);
																
								$this->flashmessenger()->addMessage('Debe cambiar su clave');
								return $this->redirect()->toRoute('seguridad-cambioclave');
						*/
							break;
							default:
								$this->flashmessenger()->addMessage($rsUsuario['respuesta_mensaje']);
								return $this->redirect()->toRoute('home');
								break;
						}
					}//end if
				}else{
					$this->flashmessenger()->addMessage("Acceso no válido");
					return $this->redirect()->toRoute('home');			
				}//end if
			}catch (\Exception $e) {
				$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
				$response = $this->getResponse();
				$response->setStatusCode(500);
				$response->setContent($excepcion_msg);
				return $response;			
			}//end try	
	}//end function autenticarAction

	
	
	public function encriptarAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
			
			$UsuarioBO = new UsuarioBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$username 	= 'ventas1';
			$clave 		= 'vent@s-321';			
			$UsuarioBO->usuarioencriptar($username, $clave);
			exit;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}//end try
		
		
	}//end function encriptarAction
	
	

	public function logoutAction()
	{
		try
		{
			$plugin = $this->SesionUsuarioPlugin();
			$plugin->logout();
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}//end try	
	}//end public function logoutAction
	
	/*public function cambioclaveAction()
	{        
		$viewModel = new ViewModel();
        $viewModel->flashMessages = $this->flashMessenger()->getMessages();
        $viewModel->setTerminal(true);        
		return $viewModel;
	}
	
	public function procesarcambioclaveAction()
	{
		$EntityManagerPlugin = $this->EntityManagerPlugin();				
		$UsuarioBO = new UsuarioBO();
		$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
		$request = $this->getRequest();
		$this->capturaDatosRedUsuario();
		if ($this->getRequest()->isPost()){
			if($this->getRequest()->getPost('clave', null)==$this->getRequest()->getPost('repetirclave', null))
			{
				$session = new Container('usuario');
				$idUsuario = $session->offsetGet("usuario_id");
				$claveAntigua = $this->getRequest()->getPost('claveAntigua', null);
				$clave = $this->getRequest()->getPost('clave', null);
				$ipAcceso = $this->_ipAcceso;
				$nombreHost = $this->_nombreHost;
				$agenteUsuario = $this->_agenteUsuario;
				$result = $UsuarioBO->cambioClave($usuario_id, $clave,$clave_antigua, $ipAcceso, $nombreHost, $agenteUsuario);
				return $this->redirect()->toRoute('load-app');
			}
			else
			{
				$this->flashmessenger()->clearMessage();
				$this->flashmessenger()->addMessage("La clave nueva ingresada y su verificacion no son iguales");
				$this->redirect()->toRoute('seguridad-login',
										array('controller'=>'login',
												'action' => 'cambioclave',
												'params' =>null));
				return false;
			}
		}//end if
		else
		{
			$this->flashmessenger()->clearMessage();
			$this->flashmessenger()->addMessage("No se ha enviado ningun dato desde el formulario");
			return $this->redirect()->toRoute('seguridad-login',
										array('controller'=>'login',
												'action' => 'cambioclave',
												'params' =>null));
		}
	}
	*/
}
