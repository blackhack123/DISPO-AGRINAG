<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\VariedadDAO;
use Dispo\Data\VariedadData;


class VariedadBO extends Conexion
{
	

	/**
	 *
	 * @param int $variedad_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboColorBase($variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$VariedadDAO = new VariedadDAO();
	
		$VariedadDAO->setEntityManager($this->getEntityManager());
	
		$result = $VariedadDAO->consultarColores();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $variedad_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodos
	
	

	/**
	 * Ingresar
	 *
	 * @param VariedadData $VariedadData
	 * @return array
	 */
	function ingresar(VariedadData $VariedadData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$VariedadDAO = new VariedadDAO();
			$VariedadDAO->setEntityManager($this->getEntityManager());
			$VariedadData2 = $VariedadDAO->consultar($VariedadData->getId());
			if (!empty($VariedadData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $VariedadDAO->ingresar($VariedadData);
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
	 * 
	 * @return array
	 */
	function consultarTodos()
	{
	
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$result = $VariedadDAO->consultarTodos();
		return $result;
	}//end function ConsultarTodos
	

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
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$result = $VariedadDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	
}//end class
