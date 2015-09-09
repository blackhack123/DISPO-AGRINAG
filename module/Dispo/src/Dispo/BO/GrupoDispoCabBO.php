<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\GrupoDispoCabDAO;
use Dispo\DAO\DispoDAO;
use Dispo\DAO\GrupoDispoDetDAO;
use Dispo\Data\GrupoDispoCabData;


class GrupoDispoCabBO extends Conexion
{

	/**
	 * 
	 * @param string $grupodispo
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboGrupoDispo($grupodispo, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());

		$result = $GrupoDispoCabDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $grupodispo, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


	
	
	
	/**
	 * 
	 * @param array $condiciones  (grupo_dispo_cab_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$GrupoDispoCabDAO 	= new GrupoDispoCabDAO();
		$DispoDAO 			= new DispoDAO();
		
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$DispoDAO->setEntityManager($this->getEntityManager());
		
		/**
		 * Se obtiene el registro CABECERA de la DISPO X GRUPO
		 */
		$reg_grupoDispoCab = $GrupoDispoCabDAO->consultarArray($condiciones['grupo_dispo_cab_id']);
		if (empty($reg_grupoDispoCab))
		{
			return null;
		}//end if
		
		
		/**
		 * Se obtiene los registro de la DISPO GENERAL  (UNIVERSO)
		 */
		$condiciones2 = array(
				"inventario_id"	=> $reg_grupoDispoCab['inventario_id'],
				"proveedor_id"	=> null,
				"clasifica"		=> $reg_grupoDispoCab['clasifica_fox'],
		);
		$result_dispo = $DispoDAO->listado($condiciones2);
		
		/**
		 * Se obtiene los registros de la DISPO POR GRUPO
		 */
		$condiciones2 = array(
				"grupo_dispo_cab_id"	=> $condiciones['grupo_dispo_cab_id'],
		);		
		$result_dispo_grupo = $GrupoDispoCabDAO->listado($condiciones2);
		

		/**
		 * Se realizar el proceso de consolidacion de informacion
		 */
		//Indexar el RESULT de la DISPO GENERAL
		$result = null;
		foreach($result_dispo as $reg)
		{
			$reg_new['variedad_id'] = $reg['variedad_id'];
			$reg_new['variedad'] 	= $reg['variedad'];
			$reg_new['dgen_40'] 	= $reg['40'];
			$reg_new['dgen_50'] 	= $reg['50'];
			$reg_new['dgen_60'] 	= $reg['60'];
			$reg_new['dgen_70'] 	= $reg['70'];
			$reg_new['dgen_80'] 	= $reg['80'];
			$reg_new['dgen_90'] 	= $reg['90'];
			$reg_new['dgen_100'] 	= $reg['100'];
			$reg_new['dgen_110'] 	= $reg['110'];
			$reg_new['dgru_40']		= 0;
			$reg_new['dgru_50']		= 0;
			$reg_new['dgru_60']		= 0;
			$reg_new['dgru_70']		= 0;
			$reg_new['dgru_80']		= 0;
			$reg_new['dgru_90']		= 0;
			$reg_new['dgru_100']	= 0;
			$reg_new['dgru_110']	= 0;
			$reg_new['existe']		= 0;
			$result[$reg['variedad_id']] = $reg_new;
		}//end foreach
		
		//Completa los campos del RESULT con la DISPO POR GRUPO
		foreach($result_dispo_grupo as $reg)
		{
			$reg_result = &$result[$reg['variedad_id']]; 
			
			$reg_result['dgru_40']	= $reg['40'];
			$reg_result['dgru_50']	= $reg['50'];
			$reg_result['dgru_60']	= $reg['60'];
			$reg_result['dgru_70']	= $reg['70'];
			$reg_result['dgru_80']	= $reg['80'];
			$reg_result['dgru_90']	= $reg['90'];
			$reg_result['dgru_100']	= $reg['100'];
			$reg_result['dgru_110']	= $reg['110'];
			$reg_result['existe']	= 1;
		}//end foreach
		
		return $result;
	}//end function listado

	
	
	/**
	 * 
	 * @param  \Application\Dispo\Data\GrupoDispoDetData $GrupoDispoDetData
	 * @throws Exception
	 * @return string
	 */
	public function registrarStock($GrupoDispoDetData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoDispoDetDAO = new GrupoDispoDetDAO();
			$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
		
			$GrupoDispoDetDAO->registrarStock($GrupoDispoDetData);
		
			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
		
	}//end function registrarStock

	
	
	
	public function consultarCabecera($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$reg = $GrupoDispoCabDAO->consultar($id, $resultType);
		return $reg;
	}//end function consultarCabecera
		
	
	
	/**
	 * Ingresar
	 *
	 * @param GrupoDispoCabData $GrupoDispoCabData
	 * @return array
	 */
	function registrarPorAccion($accion, GrupoDispoCabData $GrupoDispoCabData)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoDispoCabDAO = new GrupoDispoCabDAO();
			$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
			
			switch($accion)
			{
				case 'I':
					$id = $GrupoDispoCabDAO->ingresar($GrupoDispoCabData);
					$result['id']	 	= $id;					
					break;
					
				case 'M':
					$id = $GrupoDispoCabDAO->modificar($GrupoDispoCabData);
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
	}//end function registrar	
	
	

}//end class
