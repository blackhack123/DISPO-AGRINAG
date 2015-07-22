<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\UsuarioDAO;
use Dispo\Data\UsuarioData;


class UsuarioBOxxxxxxxphp extends Conexion
{

	/**
	 * 
	 * @param int $usuario_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboTodos($usuario_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$UsuarioDAO = new UsuarioDAO();
		
		$UsuarioDAO->setEntityManager($this->getEntityManager());

		$result = $UsuarioDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $usuario_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboTodos


	function getComboTipo($tipo, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$arrData = array('2'=>'VENDEDOR','3'=>'ADMINISTRADOR');
		
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
		$UsuarioDAO = new UsuarioDAO();
		$UsuarioDAO->setEntityManager($this->getEntityManager());
		$result = $UsuarioDAO->listado($condiciones);
		return $result;
	}//end function listado
	

	
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
	
	
	
}//end class
