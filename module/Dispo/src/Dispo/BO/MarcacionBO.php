<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\MarcacionDAO;
use Dispo\Data\MarcacionData;

class MarcacionBO extends Conexion
{


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
	function ingresar(MarcacionData $MarcacionData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$MarcacionDAO = new MarcacionDAO();
			$MarcacionDAO->setEntityManager($this->getEntityManager());
			$MarcacionData2 = $MarcacionDAO->consultar($MarcacionData->getMarcacionSec());
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
	 * 
	 * @param int $marcacion_sec
	 * @return Ambigous <\Dispo\Data\MarcacionData, NULL>
	 */
	function consultar($marcacion_sec){
		$MarcacionDAO = new MarcacionDAO();
		$MarcacionDAO->setEntityManager($this->getEntityManager());
		$MarcacionData = $MarcacionDAO->consultar($marcacion_sec);
		return $MarcacionData;		
	}//end function consultar
}//end class PaisBO
