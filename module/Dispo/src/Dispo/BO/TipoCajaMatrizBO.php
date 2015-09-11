<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\DAO\TipoCajaMatrizDAO;
use Dispo\Data\TipoCajaMatrizData;
use Dispo\DAO\DispoDAO;


class TipoCajaMatrizBO extends Conexion
{

	/**
	 * @param string
	 * @param array $condiciones  (tipo_caja_id, inventario_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$TipoCajaMatrizDAO 	= new TipoCajaMatrizDAO();
		$DispoDAO 			= new DispoDAO();
	
		$TipoCajaMatrizDAO->setEntityManager($this->getEntityManager());
		$DispoDAO->setEntityManager($this->getEntityManager());

		/**
		 * Se obtiene los registro de la DISPO GENERAL  (UNIVERSO)
		 */
		$condiciones2 = array(
				"inventario_id"	=> $condiciones['inventario_id'],
				"proveedor_id"	=> null,
				"clasifica"		=> null,
		);
		$result_dispo = $DispoDAO->listado($condiciones2);
	
		/**
		 * Se obtiene los registros de el PRECIO POR GRUPO
		*/
		$condiciones2 = array(
				"tipo_caja_id"	=> $condiciones['tipo_caja_id'],
				"inventario_id"	=> $condiciones['inventario_id'],
		);
		$result_dispo_caja = $TipoCajaMatrizDAO->listado($condiciones2);


		/**
		 * Se realizar el proceso de consolidacion de informacion
		 */
		//Indexar el RESULT de la DISPO GENERAL
		$result = null;
		foreach($result_dispo as $reg)
		{
			$reg_new['variedad_id'] = $reg['variedad_id'];
			$reg_new['variedad'] 	= $reg['variedad'];
			$reg_new['40']		= 0;
			$reg_new['50']		= 0;
			$reg_new['60']		= 0;
			$reg_new['70']		= 0;
			$reg_new['80']		= 0;
			$reg_new['90']		= 0;
			$reg_new['100']		= 0;
			$reg_new['110']		= 0;
			$reg_new['existe']	= 0;
			$result[$reg['variedad_id']] = $reg_new;
		}//end foreach		

	
		//Completa los campos del RESULT con la DISPO POR GRUPO
		foreach($result_dispo_caja as $reg)
		{
			//Se puede dar el caso que el registro exista en la lista de precios y no exista en el dispo
			//esto se puede deber a que de la dispo general lo han quitado por alguna razon de comercializacion
			if (!array_key_exists($reg['variedad_id'], $result))
			{
				$reg_new['variedad_id'] = $reg['variedad_id'];
				$reg_new['variedad'] 	= $reg['variedad'];
				$reg_new['40'] 			= 0;
				$reg_new['50'] 			= 0;
				$reg_new['60'] 			= 0;
				$reg_new['70'] 			= 0;
				$reg_new['80'] 			= 0;
				$reg_new['90'] 			= 0;
				$reg_new['100']			= 0;
				$reg_new['110']			= 0;
				$result[$reg['variedad_id']]= $reg_new;
			}//end if
				
			$reg_result = &$result[$reg['variedad_id']];
				
			$reg_result['40']	= $reg['40'];
			$reg_result['50']	= $reg['50'];
			$reg_result['60']	= $reg['60'];
			$reg_result['70']	= $reg['70'];
			$reg_result['80']	= $reg['80'];
			$reg_result['90']	= $reg['90'];
			$reg_result['100']	= $reg['100'];
			$reg_result['110']	= $reg['110'];
			$reg_result['existe']	= 1;
		}//end foreach
	
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
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$TipoCajaMatrizDAO = new TipoCajaMatrizDAO();
			$TipoCajaMatrizDAO->setEntityManager($this->getEntityManager());
			
			$result = $TipoCajaMatrizDAO->registrarBunchs($TipoCajaMatrizData);
			
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
	}//end function registrarBunchs
	
	
	
	
}//end class
