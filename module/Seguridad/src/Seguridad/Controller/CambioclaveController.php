<?php
namespace Seguridad\Controller;

use Seguridad\BO\UsuarioBO;

use Zend\Mvc\Controller\AbstractActionController;
use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;

class CambioclaveController extends AbstractActionController
{
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
/*	protected $em;*/
	private $_ipAcceso;
	private $_nombreHost;
	private $_agenteUsuario;

/*	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}
*/	
 	private function capturaDatosRedUsuario()
 	{
 		$nombreHost = "";
 		
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
	
	public function cambioclaveAction()
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
				$usuario_id = $session->offsetGet("usuario_id");
				$clave_antigua = $this->getRequest()->getPost('claveAntigua', null);
				$clave = $this->getRequest()->getPost('clave', null);
				$ipAcceso = $this->_ipAcceso;
				$nombreHost = $this->_nombreHost;
				$agenteUsuario = $this->_agenteUsuario;
				$result = $UsuarioBO->cambioClave($usuario_id, $clave,$clave_antigua, $ipAcceso, $nombreHost, $agenteUsuario);
				if($result['mensaje']=="OK")
				{
					return $this->redirect()->toRoute('load-app');
				}
				else
				{
					$this->flashmessenger()->addMessage($result['mensaje']);
					$viewModel 			= new ViewModel();
					//$viewModel->mensaje = $result['mensaje'];
					$viewModel->flashMessages = $this->flashMessenger()->getMessages();
					$viewModel->setTerminal(true);
					$viewModel->setTemplate('Seguridad/cambioclave/cambioclave.phtml');
					return $viewModel;
				}				
			}
			else
			{
				$this->flashmessenger()->addMessage("La nueva clave ingresada y su verificacion no son iguales");
				$viewModel = new ViewModel();
				$viewModel->setTerminal(true);
				//$viewModel->mensaje = "La nueva clave ingresada y su verificacion no son iguales";
				$viewModel->flashMessages = $this->flashMessenger()->getMessages();
				$viewModel->setTemplate('Seguridad/cambioclave/cambioclave.phtml');
				return $viewModel;
			}
		}//end if
		else
		{
			$this->flashmessenger()->addMessage("No se ha enviado ningun dato desde el formulario");
			$viewModel 			= new ViewModel();
			//$viewModel->mensaje = "No se ha enviado ningun dato desde el formulario";
			$viewModel->flashMessages = $this->flashMessenger()->getMessages();
			$viewModel->setTerminal(true);
			$viewModel->setTemplate('Seguridad/cambioclave/cambioclave.phtml');
			return $viewModel;
		}
	}	
}
