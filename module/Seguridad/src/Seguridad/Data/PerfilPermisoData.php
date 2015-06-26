<?php

namespace Seguridad\Data;

/**
* PerfilPermisoData.
*
*/
class PerfilPermisoData
 
{
    /**
     * @var int
     */
    private $perfil_id;
    
    /**
     * @var int
     */
    private $opcion_accion_id;
    
    //Get
	public function getPerfilId()		       	{return $this->perfil_id;}
	public function getOpcionAccionId()		  	{return $this->opcion__accion_id;}
	
	//Set
	public function setPerfilId($valor)		   	{$this->perfil_id 			= $valor;}
	public function setOpcionAccionId($valor) 	{$this->opcion_accion_id	= $valor;}
		
}//end class	