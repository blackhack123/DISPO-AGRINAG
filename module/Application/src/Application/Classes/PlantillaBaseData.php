<?php
namespace Application\Classes;

class PlantillaBaseData
{
	private $id;
	private	$nombre;
	private $estado;
	private $fecha_ing;
	private $fecha_mod;
	private $usuario_ing_id;
	private $usuario_mod_id;
	private $xml_id;

	function setId($valor)				{$this->id = $valor;}
	function setNombre($valor)			{$this->nombre = $valor;}
	function setEstado($valor)			{$this->estado = $valor;}
	function setFechaIng($valor)		{$this->fecha_ing = $valor;}
	function setFechaMod($valor)		{$this->fecha_mod = $valor;}
	function setUsuarioIngId($valor)	{$this->usuario_ing_id = $valor;}
	function setUsuarioModId($valor)	{$this->usuario_mod_id = $valor;}

	function getId()					{return $this->id;}
	function getNombre()				{return $this->nombre;}
	function getEstado()				{return $this->estado;}
	function getFechaIng()				{return $this->fecha_ing;}
	function getFechaMod()				{return $this->fecha_mod;}
	function getUsuarioIngId()			{return $this->usuario_ing_id;}
	function getUsuarioModId()			{return $this->usuario_mod_id;}
	function getXmlIds() 				{return $this->xml_id;}

	/*-----------------------------------------------------------------------------*/
	function __construct() {
		$this->nombre = "";
		$this->estado = "";
		$this->fecha_ing = null;
		$this->fecha_mod = null;
		$this->usuario_ing_id = 0;
		$this->usuario_mod_id = 0;
		$this->xmlIds = "";
	}//end function constructor general
	
	/*-----------------------------------------------------------------------------*/
	function setXmlId($ids,$entidad) {
		$resultado = "<" . $entidad . ">";
	
		foreach($ids as $id)
		{
			$resultado .= "<id>" . $id . "</id>";
		}
	
		$resultado .= "</" . $entidad . ">";
		$this->xml_id = $resultado;
	}//end function setXmlIds
}