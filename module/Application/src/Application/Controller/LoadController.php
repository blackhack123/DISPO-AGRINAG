<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

class LoadController extends AbstractActionController
{
	/*-----------------------------------------------------------------------------*/
    public function indexAction()
	/*-----------------------------------------------------------------------------*/	
    {
    	$EntityManagerPlugin = $this->EntityManagerPlugin();
    	    	
		$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
		$SesionUsuarioPlugin->isLogin();
/*		$SesionUsuarioPlugin->setDispositivoId(1); //WEB
		$SesionUsuarioPlugin->cargarPermisos();
*/
		$config = $this->getServiceLocator()->get('Config');

		$viewModel = new ViewModel();
	
		$data = $SesionUsuarioPlugin->getRecord();

		switch($SesionUsuarioPlugin->getUserPerfilId())	
		{
			case \Application\Constants\Perfil::ID_CLIENTE: //CLIENTE
				//$this->layout('layout/pedido');
				return $this->redirect()->toRoute('dispo-disponibilidad');
				break;
				
			case \Application\Constants\Perfil::ID_VENTAS: //VENTAS
				//$this->layout('layout/pedido');
				return $this->redirect()->toRoute('dispo-disponibilidad');
				break;
				
			case \Application\Constants\Perfil::ID_ADMIN: //ADMIN
				//$this->layout('layout/admin');
				return $this->redirect()->toRoute('dispo-admin');
				break;
		}//end switch	

		$this->layout()->identidad_usuario 	= $data;

		return $viewModel;		
    }//end function indexAction

}//end class