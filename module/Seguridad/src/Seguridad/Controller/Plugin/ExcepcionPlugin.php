<?php
namespace Seguridad\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
 
class ExcepcionPlugin extends AbstractPlugin{
    public function getMessageFormat(\Exception $e){
		return $e->getMessage().' '.$e->getTraceAsString() . "<b>";        
    }//end getMessage
	
	public function getTraceFormat(){
	
	}//end getTraceFormat
}