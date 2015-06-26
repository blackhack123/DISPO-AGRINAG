<?php

namespace Application\Classes;

use Doctrine\ORM\EntityManager;


class Conexion {

/**
* @var Doctrine\ORM\EntityManager
*/
    protected $em;

    /**
     * Esta conexion solo debe de ser utilizada si el $em tiene conexion establecida con otra base de datos que no sea el ERP
     * y se necesita realizar al mismo tiempo una conexion a la base de datos del ERP
     * @var Doctrine\ORM\EntityManager
     */
    protected $em_erp;

    
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
 
    public function getEntityManager()
    {
         return $this->em;
    }

    function setEntityManagerErp($valor){
    	$this->em_erp = $valor;
    }    
    
    public function getEntityManagerErp()
    {
         return $this->em_erp;
    }
}//end class Conexion
