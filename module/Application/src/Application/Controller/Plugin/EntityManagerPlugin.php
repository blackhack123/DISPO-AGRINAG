<?php
namespace Application\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
//	Zend\Authentication\AuthenticationService; 

 
class EntityManagerPlugin extends AbstractPlugin {
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
    protected $em;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em_alternative;
		
	/*-----------------------------------------------------------------------------*/
    public function setEntityManager(EntityManager $em)
	/*-----------------------------------------------------------------------------*/
    {
        $this->em = $em;
    }
 
	/*-----------------------------------------------------------------------------*/
    public function getEntityManager()
	/*-----------------------------------------------------------------------------*/
    {
        if (null === $this->em) {
            $this->em = $this->getController()->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    /*-----------------------------------------------------------------------------*/
    public function getEntityAlternativeManager()
    /*-----------------------------------------------------------------------------*/
    {
    	if (null === $this->em_alternative) {
    		$this->em_alternative = $this->getController()->getServiceLocator()->get('doctrine.entitymanager.orm_alternative');
    	}
    	return $this->em_alternative;
    }    
}//end class