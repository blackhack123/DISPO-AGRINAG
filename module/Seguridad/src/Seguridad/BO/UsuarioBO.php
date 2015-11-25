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
	 * @param string $usuario_vendedor_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboVendedoresAdmin($usuario_vendedor_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$UsuarioDAO = new UsuarioDAO();
	
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$result = $UsuarioDAO->consultarVendedoresAdmin();
	
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
			$UsuarioData2 = $UsuarioDAO->consultarDuplicado('I', $UsuarioData->getId(), $UsuarioData->getNombre(), $UsuarioData->getUsername());
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
			$result = $UsuarioDAO->consultarDuplicado('M',$UsuarioData->getId(), $UsuarioData->getNombre(), $UsuarioData->getUsername());
			$id=		$UsuarioData->getId();
			$nombre=	$UsuarioData->getNombre();
			$username=	$UsuarioData->getUsername();
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
	
	
	/**
	 * 
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function desvincularGrupoDispo($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->desvincularGrupoDispo($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo	
	
	
	
	/**
	 *
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function vincularGrupoDispo($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->vincularGrupoDispo($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo	
	

	
	
	/**
	 *
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function vincularGrupoPrecio($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->vincularGrupoPrecio($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo
	
	
	
	/**
	 *
	 * @param UsuarioData[] $ArrUsuarioData
	 * @throws Exception
	 * @return boolean
	 */
	function desvincularGrupoPrecio($ArrUsuarioData)
	{
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
	
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrUsuarioData as $UsuarioData)
			{
				$UsuarioDAO->desvincularGrupoPrecio($UsuarioData);
			}//end foreach
	
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function desvincularGrupoDispo
	
	

	function actualizarEstadoEnviarDispoPorGrupoDispo($grupo_dispo_cab_id ,$estado_enviar_dispo)
	{
		$UsuarioDAO	= new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		$result = $UsuarioDAO->actualizarEstadoEnviarDispoPorGrupoDispo($grupo_dispo_cab_id, $estado_enviar_dispo);
		
		return $result;
	}//end function actualizarEstadoEnviarDispo
	
	

	/***
	 *
	 * @param array $condiciones
	 */
	public function generarExcel($condiciones)
	{
		
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		
		$UsuarioDAO			= new UsuarioDAO();
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$texto_criterio_busqueda	= '';
		$texto_estado 				= 'TODOS';
		$texto_sincronizado			= 'TODOS';
		
		if (!empty($condiciones['criterio_busqueda'])){
			$texto_criterio_busqueda	= $condiciones['criterio_busqueda'];
		}//end if
		
		switch ($condiciones['estado'])
		{
			case 'A':
				$texto_estado		=  'ACTIVO';
				break;
		
			case 'I':
				$texto_estado		=  'INACTIVO';
				break;
		
		}//end switch
	
	}//end function generarExcel
	
	
}//end class UsuarioBO
