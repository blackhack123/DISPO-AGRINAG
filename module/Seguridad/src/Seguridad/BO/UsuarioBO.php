<?php

namespace Seguridad\BO;

use Seguridad\DAO\UsuarioDAO;
use Seguridad\DAO\UsuarioEmpresaSucursalDAO;
use Application\Classes\Conexion;
use Seguridad\Data\UsuarioData;
use Seguridad\DAO\Seguridad\DAO;

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
	 * @param string $usuario_vendedor_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodosVendedores($usuario_vendedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$UsuarioDAO = new UsuarioDAO();
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$result = $UsuarioDAO->consultarTodosVendedores();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $usuario_vendedor_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodosVendedores
	
	
	
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

	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\UsuarioData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$reg = $UsuarioDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar

	
	
	/**
	 * 
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado 
	 *   
	 * @param array $condiciones
	 * @return array
	 */
	function listado($condiciones)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$result = $UsuarioDAO->listado($condiciones);
		return $result;
	}//end function listado
	

	
	
	/**
	 * Ingresar
	 * 
	 * @param UsuarioData $UsuarioData
	 * @return array
	 */
	function ingresar(UsuarioData $UsuarioData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$UsuarioDAO = new UsuarioDAO();
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			$UsuarioData2 = $UsuarioDAO->consultar($UsuarioData->getId());
			if (!empty($UsuarioData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $UsuarioDAO->ingresar($UsuarioData);
				$result['validacion_code'] 	= 'OK';
				$result['respuesta_mensaje']= '';
			}//end if
			
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}			
	}//end function ingresar
	
	
	/**
	 * Modificar
	 * 
	 * @param UsuarioData $UsuarioData
	 * @return array
	 */
	function modificar(UsuarioData $UsuarioData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$UsuarioDAO = new UsuarioDAO();
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			//$UsuarioData2 = $UsuarioDAO->consultar($UsuarioData->getId());
			$result = $UsuarioDAO->consultarDuplicado('M',$UsuarioData->getId(), $UsuarioData->getNombre());
			$id=		$UsuarioData->getId();
			$nombre=	$UsuarioData->getNombre();
			if (!empty($result))
			{
				
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
				
				$id = $UsuarioDAO->modificar($UsuarioData);
				$result['validacion_code'] 	= 'OK';
				$result['respuesta_mensaje']= '';
			}//end if

			$this->getEntityManager()->getConnection()->commit();
			return $result;	
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function modificar	
}//end class UsuarioBO
