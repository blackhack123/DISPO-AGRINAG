<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\TransportadoraDAO;
use Dispo\Data\TransportadoraData;


class TransportadoraBO extends Conexion
{

	/**
	 * 
	 * @param int $transportadora_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodos($transportadora_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$TransportadoraDAO = new TransportadoraDAO();
		
		$TransportadoraDAO->setEntityManager($this->getEntityManager());

		$result = $TransportadoraDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $transportadora_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboTodos


	
	function getComboTipo($tipo, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$arrData = array('A'=>'A','T'=>'T');
		
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
		$TransportadoraDAO = new TransportadoraDAO();
		$TransportadoraDAO->setEntityManager($this->getEntityManager());
		$result = $TransportadoraDAO->listado($condiciones);
		return $result;
	}//end function listado
	
	
	
	
	/**
	 * Consultar 
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\TransportadoraData, NULL, array>
	 */
	function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$TransportadoraDAO = new TransportadoraDAO();
		$TransportadoraDAO->setEntityManager($this->getEntityManager());
		$reg = $TransportadoraDAO->consultar($id, $resultType);
		return $reg;		
	}//end function consultar
	
	
	/**
	 * Ingresar
	 * 
	 * @param TransportadoraData $TransportadoraData
	 * @return array
	 */
	function ingresar(TransportadoraData $TransportadoraData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$TransportadoraDAO = new TransportadoraDAO();
			$TransportadoraDAO->setEntityManager($this->getEntityManager());
			$TransportadoraData2 = $TransportadoraDAO->consultar($TransportadoraData->getId());
			if (!empty($TransportadoraData2))
			{
				$result['validacion_code'] 	= 'EXISTS';
				$result['respuesta_mensaje']= 'El registro ya existe, no puede ser ingresado!!';
			}else{
				$id = $TransportadoraDAO->ingresar($TransportadoraData);
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
	 * @param TransportadoraData $TransportadoraData
	 * @return array
	 */
	function modificar(TransportadoraData $TransportadoraData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$TransportadoraDAO = new TransportadoraDAO();
			$TransportadoraDAO->setEntityManager($this->getEntityManager());
			//$TransportadoraData2 = $TransportadoraDAO->consultar($TransportadoraData->getId());
			$result = $TransportadoraDAO->consultarDuplicado('M',$TransportadoraData->getId(), $TransportadoraData->getNombre());
			$id=		$TransportadoraData->getId();
			$nombre=	$TransportadoraData->getNombre();
			if (!empty($result))
			{
				
				$result['validacion_code'] 	= 'NO-EXISTS';
				$result['respuesta_mensaje']= 'El registro  existe, no puede ser moficado!!';
			}else{
				
				$id = $TransportadoraDAO->modificar($TransportadoraData);
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
