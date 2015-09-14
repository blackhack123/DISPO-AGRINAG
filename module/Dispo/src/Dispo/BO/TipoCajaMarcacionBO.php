<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\TipoCajaMarcacionDAO;
use Dispo\Data\TipoCajaMatrizData;
use Dispo\DAO\DispoDAO;
use Dispo\DAO\GradoDAO;


class TipoCajaMarcacionBO extends Conexion
{

	/**
	 * @param array $condiciones  (cliente_nombre, marcacion_nombre)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$TipoCajaMarcacionDAO 	= new TipoCajaMarcacionDAO();
		$TipoCajaMarcacionDAO->setEntityManager($this->getEntityManager());

		$result = $TipoCajaMarcacionDAO->listado($condiciones);
		return $result;
	}//end function listado
	

	
	
	/**
	 * 
	 * @param TipoCajaMatrizData $TipoCajaMatrizData
	 * @throws Exception
	 * @return array $result
	 */
	function registrarBunchs(TipoCajaMatrizData $TipoCajaMatrizData)
	{
/*		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$TipoCajaMarcacionDAO = new TipoCajaMarcacionDAO();
			$TipoCajaMarcacionDAO->setEntityManager($this->getEntityManager());
			
			$result = $TipoCajaMarcacionDAO->registrarBunchs($TipoCajaMatrizData);
			
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
*/	}//end function registrarBunchs



	/**
	 * @param string
	 * @param array $condiciones  (tipo_caja_id, inventario_id, variedad_id, grado_id)
	 * @return array
	 */
	public function listadoDetallado($condiciones)
	{
// 		$TipoCajaMarcacionDAO 	= new TipoCajaMarcacionDAO();
// 		$DispoDAO 			= new DispoDAO();
// 		$GradoDAO			= new GradoDAO();

// 		$TipoCajaMarcacionDAO->setEntityManager($this->getEntityManager());
// 		$DispoDAO->setEntityManager($this->getEntityManager());
// 		$GradoDAO->setEntityManager($this->getEntityManager());

		
// 		/**
// 		 * Se obtiene los grados con que se trabaja en el sistema
// 		 */
// 		$condicion['grado_id'] = $condiciones['grado_id'];
// 		$result_grado = $GradoDAO->listado($condicion);

// 		/**
// 		 * Se obtiene los registro de la DISPO GENERAL AGRUPADO(UNIVERSO)
// 		 */
// 		$result_dispo = $DispoDAO->agrupadoPorInventarioPorVariedad($condiciones['inventario_id'], $condiciones['variedad_id']);
		
// 		/**
// 		 * Se obtiene los registros de el PRECIO POR GRUPO
// 		 */
// 		$condiciones2 = array(
// 				"tipo_caja_id"	=> $condiciones['tipo_caja_id'],
// 				"inventario_id"	=> $condiciones['inventario_id'],
// 				"variedad_id"	=> $condiciones['variedad_id'],
// 				"grado_id"		=> $condiciones['grado_id'],
// 		);
// 		$result_dispo_caja = $TipoCajaMarcacionDAO->listadoDetallado($condiciones2);


// 		/**
// 		 * Se realizar el proceso de consolidacion de informacion
// 		 */
// 		//Indexar el RESULT de la DISPO GENERAL
// 		$result = null;
// 		foreach($result_dispo as $reg_dispo)
// 		{
// 			foreach($result_grado as $reg_grado)
// 			{
// 				$reg_new['tipo_caja_id']	= $condiciones['tipo_caja_id'];
// 				$reg_new['inventario_id'] 	= $reg_dispo['inventario_id'];
// 				$reg_new['variedad_id'] 	= $reg_dispo['variedad_id'];
// 				$reg_new['grado_id']		= $reg_grado['id'];
// 				$reg_new['unds_bunch']		= 0;
// 				$reg_new['existe']			= 0;
// 				$result[$reg_dispo['variedad_id'].'-'.$reg_grado['id']]= $reg_new;
// 			}//end foreach
// 		}//end foreach


// 		//Completa los campos del RESULT con la DISPO POR GRUPO
// 		foreach($result_dispo_caja as $reg)
// 		{
// 			//Se puede dar el caso que el registro exista en la lista de precios y no exista en el dispo
// 			//esto se puede deber a que de la dispo general lo han quitado por alguna razon de comercializacion
// 			$key = $reg['variedad_id'].'-'.$reg['grado_id'];
// 			//if ((!is_array($result)) || (!array_key_exists($key, $result)))
// 			if (!array_key_exists($key, $result))
// 			{
// 				$reg_new['variedad_id'] 	= $reg['variedad_id'];
// 				$reg_new['tipo_caja_id']	= $reg['tipo_caja_id'];
// 				$reg_new['inventario_id'] 	= $reg['inventario_id'];
// 				$reg_new['variedad_id'] 	= $reg['variedad_id'];
// 				$reg_new['grado_id']		= $reg['grado_id'];
// 				$reg_new['unds_bunch']		= $reg['unds_bunch'];
// 				$result[$reg['variedad_id'].'-'.$reg['grado_id']]= $reg_new;
// 			}//end if

// 			$reg_result = &$result[$reg['variedad_id'].'-'.$reg['grado_id']];

// 			$reg_result['unds_bunch']	= $reg['unds_bunch'];
// 			$reg_result['existe']		= 1;
// 		}//end foreach

// 		return $result;
	}//end function listadoDetallado	



	function actualizacionMasiva($parametros)
	{
// 		$this->getEntityManager()->getConnection()->beginTransaction();
// 		try
// 		{
// 			$TipoCajaMatrizData = new TipoCajaMatrizData();
// 			$TipoCajaMarcacionDAO = new TipoCajaMarcacionDAO();
// 			$TipoCajaMarcacionDAO->setEntityManager($this->getEntityManager());

// 			$condiciones['tipo_caja_id'] 	= $parametros['tipo_caja_id'];
// 			$condiciones['inventario_id'] 	= $parametros['inventario_id'];
// 			$condiciones['variedad_id'] 	= $parametros['variedad_id'];
// 			$condiciones['grado_id'] 		= $parametros['grado_id'];
// 			$result = $this->listadoDetallado($condiciones);

// 			foreach($result as $reg)
// 			{
// 				$TipoCajaMatrizData->setTipoCajaId		($reg['tipo_caja_id']);
// 				$TipoCajaMatrizData->setInventarioId	($reg['inventario_id']);
// 				$TipoCajaMatrizData->setVariedadId 		($reg['variedad_id']);
// 				$TipoCajaMatrizData->setGradoId			($reg['grado_id']);
// 				$TipoCajaMatrizData->setUndsBunch		($parametros['unds_bunch']);
// 				$TipoCajaMatrizData->setUsuarioIngId	($parametros['usuario_id']);
// 				$TipoCajaMatrizData->setUsuarioModId 	($parametros['usuario_id']);
// 				$result = $TipoCajaMarcacionDAO->registrarBunchs($TipoCajaMatrizData);
// 			}//end foreach

// 			$this->getEntityManager()->getConnection()->commit();
// 			return $result;
// 		} catch (Exception $e) {
// 			$this->getEntityManager()->getConnection()->rollback();
// 			$this->getEntityManager()->close();
// 			throw $e;
// 		}//end try
	}//end function actualizacionMasiva

	
	
	/**
	 *
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboVariedad($tipo_caja_id, $inventario_id, $variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
// 		$TipoCajaMarcacionDAO = new TipoCajaMarcacionDAO();
	
// 		$TipoCajaMarcacionDAO->setEntityManager($this->getEntityManager());
	
// 		$result = $TipoCajaMarcacionDAO->consultarVariedadPorInventario($tipo_caja_id, $inventario_id);
	
// 		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'variedad_id', 'variedad_nombre',$variedad_id, $texto_1er_elemento, $color_1er_elemento);
			
// 		return $opciones;
	}//end function getComboVariedadPorInventario	
}//end class
