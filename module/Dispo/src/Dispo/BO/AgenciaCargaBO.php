<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\AgenciaCargaDAO;
use Dispo\Data\AgenciaCargaData;


class AgenciaCargaBO extends Conexion
{

	/**
	 * 
	 * @param int $agencia_carga_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodos($agencia_carga_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());

		$result = $AgenciaCargaDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $agencia_carga_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboTodos

	
	
	
	function getComboTipo($tipo, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$arrData = array('A'=>'A','B'=>'B','C'=>'C');
		
		$opciones = \Application\Classes\Combo::getComboDataArray($arrData, $tipo, $texto_1er_elemento);
			
		return $opciones;
	}//end function getComboTipo

	
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
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$result = $AgenciaCargaDAO->listado($condiciones);
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
		$AgenciaCargaDAO = new AgenciaCargaDAO();
		$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
		$reg = $AgenciaCargaDAO->consultar($id, $resultType);
		return $reg;		
	}//end function consultar
	
	
	/**
	 * Ingresar
	 * 
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array
	 */
	function ingresar(AgenciaCargaData $AgenciaCargaData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$AgenciaCargaDAO = new AgenciaCargaDAO();
			$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
			$AgenciaCargaData2 = $AgenciaCargaDAO->consultar($AgenciaCargaData->getId());
			if (!empty($AgenciaCargaData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $AgenciaCargaDAO->ingresar($AgenciaCargaData);
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
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array
	 */
	function modificar(AgenciaCargaData $AgenciaCargaData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$AgenciaCargaDAO = new AgenciaCargaDAO();
			$AgenciaCargaDAO->setEntityManager($this->getEntityManager());
			//$AgenciaCargaData2 = $AgenciaCargaDAO->consultar($AgenciaCargaData->getId());
			$result = $AgenciaCargaDAO->consultarDuplicado('M',$AgenciaCargaData->getId(), $AgenciaCargaData->getNombre());
			$id=		$AgenciaCargaData->getId();
			$nombre=	$AgenciaCargaData->getNombre();
			if (!empty($result))
			{
				
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
				
				$id = $AgenciaCargaDAO->modificar($AgenciaCargaData);
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
