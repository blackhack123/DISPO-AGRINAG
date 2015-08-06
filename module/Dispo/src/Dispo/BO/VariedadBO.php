<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\VariedadDAO;
use Dispo\DAO\ProductoDAO;
use Dispo\DAO\ObtentorDAO;
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
	 *
	 * @param int $obtentor
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboObtentor($obtentor, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$ObtentorDAO = new ObtentorDAO();
	
		$ObtentorDAO->setEntityManager($this->getEntityManager());
	
		$result = $ObtentorDAO->consultarTodos();
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $obtentor, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboTodos
	
	
	
	/**
	 * 
	 * @param string $solido
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboSolido($solido, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('S'=>'SI','N'=>'NO');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $solido, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboSolido
	
	
	/**
	 * 
	 * @param string $es_real
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboEsReal($es_real, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('S'=>'SI','N'=>'NO');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $es_real, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboSolido
	
	
	/**
	 *
	 * @param string $cultivada
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	public static function getComboCultivada($cultivada, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$arrData = array('S'=>'SI','N'=>'NO');
	
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $cultivada, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboSolido
	

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
	 * Modificar
	 *
	 * @param VariedadData $VariedadData
	 * @return array
	 */
	function modificar(VariedadData $VariedadData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$VariedadDAO = new VariedadDAO();
			$VariedadDAO->setEntityManager($this->getEntityManager());
			//$VariedadData2 = $VariedadDAO->consultar($VariedadData->getId());
			$result = $VariedadDAO->consultarDuplicado('M',$VariedadData->getId(), $VariedadData->getNombre());
			$id=		$VariedadData->getId();
			$nombre=	$VariedadData->getNombre();
			if (!empty($result))
			{
	
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
	
				$id = $VariedadDAO->modificar($VariedadData);
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
	 * Consultar
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\AgenciaCargaData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$VariedadDAO = new VariedadDAO();
		$VariedadDAO->setEntityManager($this->getEntityManager());
		$reg = $VariedadDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultar
	
	
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
