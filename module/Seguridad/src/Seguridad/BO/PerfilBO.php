<?php

namespace Seguridad\BO;

use Seguridad\DAO\PerfilDAO;
use Application\Classes\Conexion;
use Seguridad\Data\PerfilData;

class PerfilBO extends Conexion{
	private $page		= null;
	private	$limit		= null;
	private $sidx		= null;
	private $sord		= null;

	function setPage($valor)					{$this->page = $valor;}
	function setLimit($valor)					{$this->limit = $valor;}
	function setSidx($valor)					{$this->sidx = $valor;}
	function setSord($valor)					{$this->sord = $valor;}

	function getPage()					{return $this->page;}
	function getLimit()					{return $this->limit;}
	function getSidx()					{return $this->sidx;}
	function getSord()					{return $this->sord;}

	
	/**
	 * 
	 * @param int $perfil_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPerfilRestringido($perfil_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$arrData = array("2"=>'VENDEDOR',"3"=>'ADMINISTRADOR');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, (int)$perfil_id, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboPerfilRestringido

	
}//end class PerfilBO
