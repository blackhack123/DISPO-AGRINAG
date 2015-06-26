<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Prueba\DAO\PruebaDAO;


class PruebaBORRARRRRRRRRBO extends Conexion{
	private $page		= null;
	private	$limit		= null;
	private $sidx		= null;
	private $sord		= null;

	function setPage($valor)			{$this->page = $valor;}
	function setLimit($valor)			{$this->limit = $valor;}
	function setSidx($valor)			{$this->sidx = $valor;}
	function setSord($valor)			{$this->sord = $valor;}
	
	function getPage()					{return $this->page;}
	function getLimit()					{return $this->limit;}
	function getSidx()					{return $this->sidx;}
	function getSord()					{return $this->sord;}
	
	

	/*-----------------------------------------------------------------------------*/	 	
    /**
     * Listado
     *
     * @param  array $condiciones	 
     * @return array
     */
	function listado($condiciones){
	/*-----------------------------------------------------------------------------*/	
		$PruebaDAO = new PruebaDAO();
		
		$PruebaDAO->setEntityManager($this->getEntityManager());
		$PruebaDAO->setPage($this->page);
		$PruebaDAO->setLimit($this->limit);
		$PruebaDAO->setSidx($this->sidx);
		$PruebaDAO->setSord($this->sord);

		$result = $PruebaDAO->listado($condiciones);		
		return $result;
	}//end function listado


}//end class PaisBO
