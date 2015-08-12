<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\MarcacionDAO;
use Dispo\Data\MarcacionData;

class MarcacionBO extends Conexion
{

	/**
	 *
	 * @param string $cliente_id
	 * @param int $marcacion_sec
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorClienteId($cliente_id, $marcacion_sec, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$MarcacionDAO = new MarcacionDAO();
	
		$MarcacionDAO->setEntityManager($this->getEntityManager());
	
		$result = $MarcacionDAO->consultarPorClienteId($cliente_id);
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'marcacion_sec', 'nombre', $marcacion_sec, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function listado

	/**
	 * 
	 * @param string $Marcacion_id
	 * @param int $marcacion_sec
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorMarcacionId($Marcacion_id, $marcacion_sec, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$MarcacionDAO = new MarcacionDAO();
		
		$MarcacionDAO->setEntityManager($this->getEntityManager());

		$result = $MarcacionDAO->consultarPorMarcacionId($Marcacion_id);
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'marcacion_sec', 'nombre', $marcacion_sec, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function listado


	/**
	 * Ingresar
	 *
	 * @param MarcacionData $MarcacionData
	 * @return array
	 */
	function ingresarmarcacion(MarcacionData $MarcacionData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$MarcacionDAO = new MarcacionDAO();
			$MarcacionDAO->setEntityManager($this->getEntityManager());
			$MarcacionData2 = $MarcacionDAO->consultarDuplicado('M',$MarcacionData->getMarcacionSec(), $MarcacionData->getNombre());
			$marcacion_sec	= $MarcacionData->getMarcacionSec();
			$nombre			= $MarcacionData->getNombre();
			if (!empty($MarcacionData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $MarcacionDAO->ingresar($MarcacionData);
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
	 * @param MarcacionData $MarcacionData
	 * @return array
	 */
	function modificarmarcacion(MarcacionData $MarcacionData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$MarcacionDAO = new MarcacionDAO();
			$MarcacionDAO->setEntityManager($this->getEntityManager());
			//$MarcacionData2 = $MarcacionDAO->consultar($MarcacionData->getId());
			$result = $MarcacionDAO->consultarDuplicado('M',$MarcacionData->getMarcacionSec(), $MarcacionData->getNombre());
			$marcacion_sec=		$MarcacionData->getMarcacionSec();
			$nombre=	$MarcacionData->getNombre();
			if (!empty($result))
			{
	
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
	
				$id = $MarcacionDAO->modificar($MarcacionData);
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
	 * 
	 * @param int $marcacion_sec
	 * @return Ambigous <\Dispo\Data\MarcacionData, NULL>
	 */
	function consultarmarcacion($marcacion_sec, $resultType = \Application\Constants\ResultType::OBJETO){
		$MarcacionDAO = new MarcacionDAO();
		$MarcacionDAO->setEntityManager($this->getEntityManager());
		$reg = $MarcacionDAO->consultarmarcacion($marcacion_sec, $resultType);
		return $reg;		
	}//end function consultar
	
	
	/**
	 * 
	 * @param array $condiciones (cliente_id, nombre, estado)
	 * @return array
	 */
	function listado($condiciones)
	{
		$MarcacionDAO = new MarcacionDAO();
		$MarcacionDAO->setEntityManager($this->getEntityManager());
		$MarcacionData = $MarcacionDAO->listado($condiciones);
		return $MarcacionData;		
	}//end function listado
	
}//end class PaisBO
