<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\ClienteDAO;
use Dispo\Data\ClienteData;

class ClienteBO extends Conexion
{


	/**
	 * 
	 * @param string $cliente_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getCombo($cliente_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$ClienteDAO = new ClienteDAO();
		
		$ClienteDAO->setEntityManager($this->getEntityManager());

		//$result = $ClienteDAO->consultarTodo();
		$result = $ClienteDAO->consultarUsuarioAsignado();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $cliente_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function listado
	
	
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
		$ClienteDAO = new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		$result = $ClienteDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\ClienteData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$ClienteDAO = new ClienteDAO();
		$ClienteDAO->setEntityManager($this->getEntityManager());
		$reg = $ClienteDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar
	
	
	/**
	 * Ingresar
	 *
	 * @param ClienteData $ClienteData
	 * @return array
	 */
	function ingresar(ClienteData $ClienteData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$ClienteDAO = new ClienteDAO();
			$ClienteDAO->setEntityManager($this->getEntityManager());
			$ClienteData2 = $ClienteDAO->consultar($ClienteData->getId());
			if (!empty($ClienteData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $ClienteDAO->ingresar($ClienteData);
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
	 * @param ClienteData $ClienteData
	 * @return array
	 */
	function modificar(ClienteData $ClienteData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$ClienteDAO = new ClienteDAO();
			$ClienteDAO->setEntityManager($this->getEntityManager());
			//$ClienteData2 = $ClienteDAO->consultar($ClienteData->getId());
			$result = $ClienteDAO->consultar('M',$ClienteData->getId(), $ClienteData->getNombre());
			$id=		$ClienteData->getId();
			$nombre=	$ClienteData->getNombre();
			if (!empty($result))
			{
	
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
	
				$id = $ClienteDAO->modificar($ClienteData);
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
	
	
	
	
}//end class MarcacionBO
