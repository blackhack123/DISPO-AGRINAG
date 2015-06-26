<?php

namespace Seguridad\Data;
use Application\Classes\PlantillaBaseData;

/**
* PerfilData.
*
*/
class PerfilData extends PlantillaBaseData
{
    private $siglas;
    private $xml_perfil_opcion_accion;
    
	function setSiglas($valor)				{$this->siglas = $valor;}
	function getSiglas()					{return $this->siglas;}
	function getXmlPerfilOpcionAccion()		{return $this->xml_perfil_opcion_accion;}
	
	/*-----------------------------------------------------------------------------*/
	function __construct() {
		$this->siglas = "";
	}//end function constructor general
	
	/*-----------------------------------------------------------------------------*/
	function setXmlIds($ids) {
		$this->setXmlId($ids, "Perfiles");
	}//end function setXmlIds
	
	function setXmlPerfilOpcionAccion($PerfilPermisoData){
		$resultado = "<PerfilPermisos>";
		if(count($PerfilPermisoData)>0)
		{
			foreach ($PerfilPermisoData	as $reg){
				if ($reg['tipo_accion']!='C'){  //No se procesan aquellos registros que no han sido alterados
					$resultado .= "<OpcionAccion>";
					$resultado .= "<perfil_id>" . $reg['perfil_id'] . "</perfil_id>";
					$resultado .= "<opcion_accion_id>" . $reg['opcion_accion_id'] . "</opcion_accion_id>";
					//$resultado .= "<seleccion>" . $reg['seleccion'] . "</seleccion>";
					$resultado .= "</OpcionAccion>";
				}//end if
			}//end foreach
			$resultado .= "</PerfilPermisos>";
			$this->xml_perfil_opcion_accion = $resultado;
		}//end if
		else
		{
			$this->xml_perfil_opcion_accion = null;
		}
	}//end function setXmlPerfilOpcionAccion
}//end class	