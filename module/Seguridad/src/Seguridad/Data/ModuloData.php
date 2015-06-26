<?php
namespace Seguridad\Data;
use Application\Classes\PlantillaBaseData;

class ModuloData extends PlantillaBaseData
{
	private $siglas;
	private $url_logo;
	private $nro_orden;
	
	function setSiglas($valor)			{$this->siglas = $valor;}
	function setUrlLogo($valor)			{$this->url_logo = $valor;}
	function setOrden($valor)			{$this->nro_orden = $valor;}
	
	function getSiglas()				{return $this->siglas;}
	function getUrlLogo()				{return $this->url_logo;}
	function getOrden()					{return $this->nro_orden;}
	
	/*-----------------------------------------------------------------------------*/
	function __construct() {
		$this->siglas = "";
		$this->url_logo = "";
		$this->nro_orden = 0;
	}//end function constructor general
	
	/*-----------------------------------------------------------------------------*/
	function setXmlIds($ids) {
		$this->setXmlId($ids, "Modulos");
	}//end function setXmlIds
}
?>