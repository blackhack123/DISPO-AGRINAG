<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\ParametrizarDAO;
use Dispo\Data\ParametrizarData;


class ParametrizarBO extends Conexion
{
	


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
		$ParametrizarDAO = new ParametrizarDAO();
		$ParametrizarDAO->setEntityManager($this->getEntityManager());
		$result = $ParametrizarDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\AgenciaCargaData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$ParametrizarDAO = new ParametrizarDAO();
		$ParametrizarDAO->setEntityManager($this->getEntityManager());
		$reg = $ParametrizarDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar

	
	/**
	 * Modificar
	 *
	 * @param ParametrizarData $ParametrizarData
	 * @return array
	 */
	function modificar(ParametrizarData $ParametrizarData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$ParametrizarDAO = new ParametrizarDAO();
			$ParametrizarDAO->setEntityManager($this->getEntityManager());
			$ParametrizarData2 = $ParametrizarDAO->consultar($ParametrizarData->getId());
			//$result = $ParametrizarDAO->consultarDuplicado('M',$ParametrizarData->getId());
			$id=		$ParametrizarData->getId();
			//$Descripcion=	$ParametrizarData->getDescripcion();
			if (!empty($result))
			{
	
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
	
				$id = $ParametrizarDAO->modificar($ParametrizarData);
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

	
}//end class
