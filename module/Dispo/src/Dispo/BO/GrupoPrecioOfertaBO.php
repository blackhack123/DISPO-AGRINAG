<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\GrupoPrecioCabDAO;
use Dispo\DAO\GrupoPrecioDetDAO;
use Dispo\DAO\DispoDAO;
use Dispo\Data\GrupoPrecioCabData;
use Dispo\DAO\GrupoPrecioOfertaDAO;

class GrupoPrecioOfertaBO extends Conexion
{

	
	/**
	 * @param string
	 * @param array $condiciones  (grupo_precio_cab_id, producto_id, $variedad_id, $grado_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$GrupoPrecioOfertaDAO	= new GrupoPrecioOfertaDAO();
		
		$GrupoPrecioOfertaDAO->setEntityManager($this->getEntityManager());
		
		$result = $GrupoPrecioOfertaDAO->listado($condiciones);
		
		return $result;
	}//end function listado

	
	/**
	 *
	 * @param  \Application\Dispo\Data\GrupoPrecioDetData $GrupoPrecioDetData
	 * @throws Exception
	 * @return string
	 */
/*	public function registrarPrecio($tipo_precio, $GrupoPrecioDetData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoPrecioDetDAO = new GrupoPrecioDetDAO();
			$GrupoPrecioDetDAO->setEntityManager($this->getEntityManager());
	
			$GrupoPrecioDetDAO->registrarPrecio($tipo_precio, $GrupoPrecioDetData);
	
			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
	
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	
	}//end function registrarPrecio
*/	
	
	/**
	 * 
	 * @param string $accion
	 * @param GrupoPrecioCabData $GrupoPrecioCabData
	 * @throws Exception
	 * @return array
	 */
/*	function registrarPorAccion($accion, GrupoPrecioCabData $GrupoPrecioCabData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
			$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
				
			switch($accion)
			{
				case 'I':
					$id = $GrupoPrecioCabDAO->ingresar($GrupoPrecioCabData);
					$result['id']	 	= $id;
					break;
						
				case 'M':
					$id = $GrupoPrecioCabDAO->modificar($GrupoPrecioCabData);
					$result['id']		= $id;
					break;
			}//end switch
				
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function registrarPorAccion
*/	
	
	/**
	 * 
	 * @param int $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\GrupoPrecioCabData, NULL, multitype:>
	 */
/*	public function consultarCabecera($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$reg = $GrupoPrecioCabDAO->consultar($id, $resultType);
		return $reg;		
	}//end function consultarCabecera
*/	
	
	
/*	function listadoGrupoPrecioNoAsignadas()
	{
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoPrecioCabDAO->listadoGrupoPrecioNoAsignadas();
		return $result;
	}//end function listadoNoAsignadas
*/	
	
	/**
	 *
	 * @param array $condiciones (grupo_precio_cab_id);
	 * @return array
	 */
/*	function listadoAsignadas($condiciones)
	{
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoPrecioCabDAO->listadoAsignadas($condiciones);
		return $result;
	}//end function listadoAsignadas
*/	
	
	
	public function registrar($GrupoPrecioOfertaData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoPrecioOfertaDAO = new GrupoPrecioOfertaDAO();
			$GrupoPrecioOfertaDAO->setEntityManager($this->getEntityManager());

			$GrupoPrecioOfertaDAO->registrar($GrupoPrecioOfertaData);

			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';

			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}//end try
	}//end function registrar

	
	public function eliminarMasivo($ArrGrupoPrecioOfertaData)
	{
		$GrupoPrecioOfertaDAO = new GrupoPrecioOfertaDAO();
		$GrupoPrecioOfertaDAO->setEntityManager($this->getEntityManager());
		
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrGrupoPrecioOfertaData as $GrupoPrecioOfertaData)
			{
				$GrupoPrecioOfertaDAO->eliminar($GrupoPrecioOfertaData);
			}//end foreach
		
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
	}//end function eliminarMasivo
	
}//end class
