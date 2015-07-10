<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        /*------Se configura las variables PHP que se inicializan en la aplicacion---------*/
        $app         = $e->getParam('application');
        $config      = $app->getConfig();
        
        $config      = $app->getConfig();
        $phpSettings = $config['phpSettings'];
        if($phpSettings) {
        	foreach($phpSettings as $key => $value) {
        		ini_set($key, $value);
        	}//end foreach
        }//end if
        
        \Application\Classes\Fecha::setFormato($config['formatoFecha']);
        /*---------------------------------------------------------------------------------*/
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
