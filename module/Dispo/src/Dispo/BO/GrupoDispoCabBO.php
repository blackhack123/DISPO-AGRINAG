<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\GrupoDispoCabDAO;
use Dispo\DAO\DispoDAO;
use Dispo\DAO\GrupoDispoDetDAO;
use Dispo\Data\GrupoDispoCabData;
use Dispo\Data\GrupoDispoDetData;


class GrupoDispoCabBO extends Conexion
{

	/**
	 * 
	 * @param string $grupodispo_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboGrupoDispo($grupodispo_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());

		$result = $GrupoDispoCabDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $grupodispo_id, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getCombo


	/**
	 * 
	 * @param integer $grupo_dispo_cab_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboPorInventario($grupo_dispo_cab_id, $inventario_id, $calidad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
	
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
	
		$result = $GrupoDispoCabDAO->consultarPorInventario($inventario_id, $calidad_id);
	
		$opciones_dispo = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre',$grupo_dispo_cab_id,  $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones_dispo;
	}//end function getComboPorInventario
	
	
	/**
	 * 
	 * @param array $condiciones  (grupo_dispo_cab_id, color_ventas_id, calidad_variedad_id, cadena_color_ventas_ids, cadena_calidad_variedad_ids)
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
		

		if (!array_key_exists('cadena_color_ventas_ids', $condiciones))		{$condiciones['cadena_color_ventas_ids']='';}
		if (!array_key_exists('cadena_calidad_variedad_ids', $condiciones))	{$condiciones['cadena_calidad_variedad_ids']='';}
		
		/**
		 * Se obtiene los registro de la DISPO GENERAL  (UNIVERSO)
		 */
		$condiciones2 = array(
				"inventario_id"			=> $reg_grupoDispoCab['inventario_id'],
				"proveedor_id"			=> null,
				"clasifica"				=> $reg_grupoDispoCab['clasifica_fox'],
				"color_ventas_id"		=> $condiciones['color_ventas_id'],
				"calidad_variedad_id" 	=> $condiciones['calidad_variedad_id'],
				"cadena_color_ventas_ids"		=> $condiciones['cadena_color_ventas_ids'],
				"cadena_calidad_variedad_ids"	=> $condiciones['cadena_calidad_variedad_ids']
		);
		$result_dispo = $DispoDAO->listado($condiciones2);
		
		/**
		 * Se obtiene los registros de la DISPO POR GRUPO
		 */
		$condiciones2 = array(
				"grupo_dispo_cab_id"	=> $condiciones['grupo_dispo_cab_id'],
				"color_ventas_id"		=> $condiciones['color_ventas_id'],
				"calidad_variedad_id" 	=> $condiciones['calidad_variedad_id'],
				"cadena_color_ventas_ids"		=> $condiciones['cadena_color_ventas_ids'],
				"cadena_calidad_variedad_ids"	=> $condiciones['cadena_calidad_variedad_ids']
		);		
		$result_dispo_grupo = $GrupoDispoCabDAO->listado($condiciones2);
		

		/**
		 * Se realizar el proceso de consolidacion de informacion
		 */
		//Indexar el RESULT de la DISPO GENERAL
		$result = null;
		foreach($result_dispo as $reg)
		{
			$reg_new['producto_id'] 	= $reg['producto_id'];
			$reg_new['variedad_id'] 	= $reg['variedad_id'];
			$reg_new['variedad'] 		= trim($reg['variedad']);
			$reg_new['tallos_x_bunch'] 	= $reg['tallos_x_bunch'];
			$reg_new['color_ventas_nombre'] 	= $reg['color_ventas_nombre'];
			$reg_new['dgen_40'] 		= $reg['40'];
			$reg_new['dgen_50'] 		= $reg['50'];
			$reg_new['dgen_60'] 		= $reg['60'];
			$reg_new['dgen_70'] 		= $reg['70'];
			$reg_new['dgen_80'] 		= $reg['80'];
			$reg_new['dgen_90'] 		= $reg['90'];
			$reg_new['dgen_100'] 		= $reg['100'];
			$reg_new['dgen_110'] 		= $reg['110'];
			$reg_new['dgru_40']			= 0;
			$reg_new['dgru_50']			= 0;
			$reg_new['dgru_60']			= 0;
			$reg_new['dgru_70']			= 0;
			$reg_new['dgru_80']			= 0;
			$reg_new['dgru_90']			= 0;
			$reg_new['dgru_100']		= 0;
			$reg_new['dgru_110']		= 0;
			$reg_new['existe']			= 0;
			$result[$reg['producto_id'].'-'.$reg['variedad_id'].'-'.$reg['tallos_x_bunch']] = $reg_new;
		}//end foreach
		
		//Completa los campos del RESULT con la DISPO POR GRUPO
		foreach($result_dispo_grupo as $reg)
		{
			$reg_result = &$result[$reg['producto_id'].'-'.$reg['variedad_id'].'-'.$reg['tallos_x_bunch']]; 
			
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
	

	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @return array
	 */
	function listadoNoAsignadas($grupo_dispo_cab_id)
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoDispoCabDAO->listadoNoAsignadas($grupo_dispo_cab_id);
		return $result;
	}//end function listadoNoAsignadas
	
	
	/**
	 *
	 * @param array $condiciones (grupo_dispo_cab_id);
	 * @return array
	 */	
	function listadoAsignadas($condiciones)
	{
		$GrupoDispoCabDAO = new GrupoDispoCabDAO();
		$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoDispoCabDAO->listadoAsignadas($condiciones);
		return $result;
	}//end function listadoAsignadas	

	

	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @param string $grado_id
	 * @param string $cadena_color_ventas_ids
	 * @param string $cadena_calidad_variedad_ids
	 * @param float $porcentaje
	 * @param int $valor
	 * @param int $usuario_id
	 * @throws Exception
	 * @return array
	 */
	function grabarPorGrupoPorGrado($grupo_dispo_cab_id, $grado_id, $cadena_color_ventas_ids, 
									$cadena_calidad_variedad_ids, $porcentaje, $valor, $usuario_id)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$GrupoDispoDetDAO 	= new GrupoDispoDetDAO();
			$GrupoDispoDetData 	= new GrupoDispoDetData();
			$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
		
			$condiciones['grupo_dispo_cab_id']			= $grupo_dispo_cab_id;
			$condiciones['color_ventas_id']				= null;
			$condiciones['calidad_variedad_id']			= null;
			$condiciones['cadena_color_ventas_ids']		= $cadena_color_ventas_ids;
			$condiciones['cadena_calidad_variedad_ids']	= $cadena_calidad_variedad_ids;
			$result = $this->listado($condiciones);
			$campo_grado_dispogen = "dgen_".$grado_id;
			
			
			foreach($result as $reg)
			{
				$GrupoDispoDetData->setGrupoDispoCabId	($grupo_dispo_cab_id);
				$GrupoDispoDetData->setProductoId		($reg['producto_id']);
				$GrupoDispoDetData->setVariedadId		($reg['variedad_id']);
				$GrupoDispoDetData->setGradoId			($grado_id);
				$GrupoDispoDetData->setTallosXBunch		($reg['tallos_x_bunch']);
				if ($porcentaje!=0)
				{
					$cantidad_bunch = floor($reg[$campo_grado_dispogen]*$porcentaje/100);
				}else if ($valor != 0){
					$cantidad_bunch = $valor;
				}else{
					$cantidad_bunch = 0;
				}//end if
				if ($cantidad_bunch > $reg[$campo_grado_dispogen])
				{
					$cantidad_bunch = $reg[$campo_grado_dispogen];
				}//end if
				$GrupoDispoDetData->setCantidadBunch($cantidad_bunch);
				$GrupoDispoDetData->setCantidadBunchDisponible($cantidad_bunch);
				$GrupoDispoDetData->setUsuarioModId($usuario_id);
				$GrupoDispoDetData->setUsuarioIngId($usuario_id);
				
				$GrupoDispoDetDAO->registrar($GrupoDispoDetData);
			}//end foreach

			
			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function grabarPorGrupoPorGrado
	
	
}//end class
