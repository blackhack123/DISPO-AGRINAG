<?php

namespace Seguridad\BO;

use Seguridad\DAO\UsuarioDAO;
use Seguridad\DAO\UsuarioEmpresaSucursalDAO;
use Application\Classes\Conexion;
use Seguridad\Data\UsuarioData;

class UsuarioBO extends Conexion{
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

	
	
	function login($usuario, $clave, $ipAcceso, $nombreHost, $AgenteUsuario)
	{
		$UsuarioDAO = new UsuarioDAO;
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$resultDatosUsuario = $UsuarioDAO->login($usuario, $clave, $ipAcceso, $nombreHost, $AgenteUsuario);
		return $resultDatosUsuario;
	}//end function login
	
	
	
	function encriptar($clave)
	{
		$UsuarioDAO = new UsuarioDAO;
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$clave_encriptada = $UsuarioDAO->encriptar($clave);
		return $clave_encriptada;
	}//end function encriptar
	
	
	
	function usuarioencriptar($username, $clave)
	{
		$UsuarioDAO = new UsuarioDAO;
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());

		$clave_encriptada = $UsuarioDAO->encriptar($clave);

		$result = $UsuarioDAO->usuarioencriptar($username, $clave_encriptada);

		return $result;
		exit;
	}//end function usuarioencriptar
	
	
	

	/**
	 * 
	 * @param string $cliente_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorCliente($cliente_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$UsuarioDAO = new UsuarioDAO();
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$result = $UsuarioDAO->consultarPorCliente($cliente_id);
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre_completo', $cliente_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;		
	}//end function getComboPorCliente

	
	
	function consultar($id, $type_result = \Application\Constants\ResultType::OBJETO)
	{
		$UsuarioDAO = new UsuarioDAO();
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$result = null;
		switch ($type_result)
		{
			case \Application\Constants\ResultType::OBJETO:
				$result = $UsuarioDAO->consultar($id);
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$result = $UsuarioDAO->consultarArray($id);
				break;
		}//end switch
		
		return $result;
	}//end function consultar
	
	
/*	function ingresar(UsuarioData $UsuarioData, $isGenerarClave)
	{	
		$UsuarioDAO = new UsuarioDAO();
		
		try {
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			$result = $UsuarioDAO->setUsuario("I", $UsuarioData, $isGenerarClave);
				
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->close();
			throw $e;
			exit;
		}
	}//end function ingresar

	
	function modificar(UsuarioData $UsuarioData, $isGenerarClave)
	{
		$UsuarioDAO = new UsuarioDAO();
		
		try {
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			$result = $UsuarioDAO->setUsuario("M", $UsuarioData, $isGenerarClave);
		
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->close();
			throw $e;
			exit;
		}
	}//end function modificar

	
	function eliminar(UsuarioData $UsuarioData){
		$UsuarioDAO = new UsuarioDAO();
		
		try {
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			$result = $UsuarioDAO->setUsuario('E', $UsuarioData);
				
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->close();
			throw $e;
			exit;
		}
	}//end function eliminar

*/	
    /**
     * Eliminacion masiva
     *
     * @param \Seguridad\Data\UsuarioData $arrUsuarioData[]
	 * @return bool
     */	
/*	function eliminarMasivo($arrUsuarioData)
	{		
		$UsuarioDAO = new UsuarioDAO;
		
		$this->getEntityManager()->getConnection()->beginTransaction();		
		
		try {
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			foreach($arrUsuarioData as $UsuarioData){
				$id = $UsuarioDAO->eliminar($UsuarioData);		
			}//end foreach
			$this->getEntityManager()->getConnection()->commit();			
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function eliminarMasivo

	
	function consultar($id)
	{	
		$UsuarioDAO = new UsuarioDAO;

		$UsuarioDAO->setEntityManager($this->getEntityManager());

		$UsuarioData = $UsuarioDAO->consultar($id);		
		return $UsuarioData;
	}//end function consultar
	

*/



	 	
    /**
     * Listado
     *
     * @param  string $opcion
     * @param  array $condiciones	 
     * @return array
     */
/*	function listado($opcion, $condiciones)
	{	
		$UsuarioDAO = new UsuarioDAO;
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$UsuarioDAO->setPage($this->page);
		$UsuarioDAO->setLimit($this->limit);
		$UsuarioDAO->setSidx($this->sidx);
		$UsuarioDAO->setSord($this->sord);

		$result = $UsuarioDAO->listado($opcion, $condiciones);		
		return $result;
	}//end function listado

	 
	function getCboEstado($estado)
	{
		$arrData = array("A"=>"ACTIVO",
				  	     "I"=>"INACTIVO");
		$opcion = "";
		foreach($arrData as $clave => $valor){
			$seleccionado = "";
			if ($estado==$clave){
				$seleccionado = "selected";
			}//end if
			$opcion = $opcion.'<option value="'.$clave.'" '.$seleccionado.'>'.$valor.'</option>';			
		}//end foreach

		return $opcion;
	}//end function getCboEstado
*/	
	
	 
	
/*	function cambioClave($usuario_id, $clave,$clave_antigua, $ipAcceso, $nombreHost, $AgenteUsuario)
	{
		$UsuarioDAO = new UsuarioDAO;
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$result = $UsuarioDAO->setCambioClave($usuario_id, $clave,$clave_antigua, $ipAcceso, $nombreHost, $AgenteUsuario);
		return $result;
	}//end function login
	
*/	
	

	
}//end class UsuarioBO
