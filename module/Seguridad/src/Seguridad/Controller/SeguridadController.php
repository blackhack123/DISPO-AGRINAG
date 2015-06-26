<?php

namespace Seguridad\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Doctrine\ORM\EntityManager,
    Seguridad\Entity\Usuario;

class SeguridadController extends AbstractActionController
{
/**
* @var Doctrine\ORM\EntityManager
*/
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
 
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'usuarios' => $this->getEntityManager()->getRepository('Seguridad\Entity\Usuario')->findAll()
        ));
    }

}