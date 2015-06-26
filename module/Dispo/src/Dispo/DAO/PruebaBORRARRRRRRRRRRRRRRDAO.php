<?php

namespace Dispo\DAO;

use Doctrine\ORM\EntityManager,
General\Entity\Pais,
Application\Classes\Conexion;
use General\Data\PaisData;
use Seguridad\Data\UsuarioData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PruebaDAO extends Conexion {
	private $table_name	= 'cds';
	private $page		= null;
	private	$limit		= null;
	private $sidx		= null;
	private $sord		= null;

	function setPage($valor)	{
		$this->page = $valor;
	}
	function setLimit($valor)	{
		$this->limit = $valor;
	}
	function setSidx($valor)	{
		$this->sidx = $valor;
	}
	function setSord($valor)	{
		$this->sord = $valor;
	}

	function getPage()			{
		return $this->page;
	}
	function getLimit()			{
		return $this->limit;
	}
	function getSidx()			{
		return $this->sidx;
	}
	function getSord()			{
		return $this->sord;
	}



	/*-----------------------------------------------------------------------------*/
	/**
	 * Listado
	 *
	 * @param  array $condiciones
	 * @return array
	 */
	public function listado($condiciones){
		/*-----------------------------------------------------------------------------*/
		//$this->getEntityManager()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
		$sql = 'SELECT * FROM cds '.
				' WHERE 1 = 1';
/*		if (!empty($condiciones['nombre'])){
			$sql=$sql." and p.nombre like '%".$condiciones['nombre']."%'";
		}//end if
		if (!empty($condiciones['moneda_id'])){
			$sql=$sql." and m.id=".$condiciones['moneda_id'];
		}//end if
		if (!empty($condiciones['estado'])){
			$sql=$sql." and p.estado like '%".$condiciones['estado']."%'";
		}//end if
*/		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);


		$result = $stmt->fetchAll();
		return $result;
	}//end function listado

}//end class PaisDAO

