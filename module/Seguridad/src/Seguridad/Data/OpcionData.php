<?php
namespace Seguridad\Data;
use Application\Classes\PlantillaBaseData;

class OpcionData extends PlantillaBaseData
{
	private $modulo_id;
	private $tipo_opcion;
	private $url_acceso;
	private $url_logo;
	private $nro_orden;
	private $xml_opcion_accion;
	private $iframe;
	
	function setModuloId($valor)		{$this->modulo_id = $valor;}
	function setTipoOpcion($valor)		{$this->tipo_opcion = $valor;}
	function setUrlAcceso($valor)		{$this->url_acceso = $valor;}
	function setUrlLogo($valor)			{$this->url_logo = $valor;}
	function setOrden($valor)			{$this->nro_orden = $valor;}
	function setIframe($valor)			{$this->iframe = $valor;}
	
	function getModuloId()				{return $this->modulo_id;}
	function getTipoOpcion()			{return $this->tipo_opcion;}
	function getUrlAcceso()				{return strtolower($this->url_acceso);}
	function getUrlLogo()				{return $this->url_logo;}
	function getOrden()					{return $this->nro_orden;}
	function getXmlOpcionAccion()		{return $this->xml_opcion_accion;}
	function getIframe()				{return $this->iframe;}
	
	/*-----------------------------------------------------------------------------*/
	function __construct() {
		$this->modulo_id = 0;
		$this->nombre = "";
		$this->tipo_opcion = "";
		$this->url_acceso = "";
		$this->url_logo = "";
		$this->xml_opcion_accion = null;
	}//end function constructor general
	
	/*-----------------------------------------------------------------------------*/
	function setXmlIds($ids) {
		$this->setXmlId($ids, "Opciones");
	}//end function setXmlIds
	
	function setXmlOpcionAccion($OpcionAccionData){
		$resultado = "<OpcionAcciones>";
		if(count($OpcionAccionData)>0)
		{
			foreach ($OpcionAccionData	as $reg){
				if ($reg['accion']!='C'){  //No se procesan aquellos registros que no han sido alterados
					$resultado .= "<OpcionAccion>";
					$resultado .= "<id>" . $reg['id'] . "</id>";
					$resultado .= "<dispositivo_id>" . $reg['dispositivo_id'] . "</dispositivo_id>";
					$resultado .= "<accion_id>" . $reg['accion_id'] . "</accion_id>";
					$resultado .= "<tipo_tran>" . $reg['accion'] . "</tipo_tran>";
					$resultado .= "</OpcionAccion>";
				}//end if
			}//end foreach
			$resultado .= "</OpcionAcciones>";
			$this->xml_opcion_accion = $resultado;
		}//end if
		else 
		{
			$this->xml_opcion_accion = null;
		}
	}//end function setXmlOpcionAccion
}