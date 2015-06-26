<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
//        return new ViewModel();
    	$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);
    	$viewModel->flashMessages = $this->flashMessenger()->getMessages();
    	
    	return $viewModel;
    }
    
    public function pruebaAction()
    {
    	/*$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	echo("paso02");
    	exit;
    	*/
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	
    	$sql = 'select * from cds';
    	$stmt = $objectManager->getConnection()->prepare($sql);
    	//$stmt->bindValue(':estado', $estado);
    	$stmt->execute();

    	$result = $stmt->fetchAll();
    	var_dump($result);    	
    	echo("paso01");
    	exit;
    }
}
