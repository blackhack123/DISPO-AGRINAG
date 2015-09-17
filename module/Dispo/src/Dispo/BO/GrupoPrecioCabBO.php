<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Doctrine\ORM\EntityManager;
use Dispo\DAO\GrupoPrecioCabDAO;
use Dispo\DAO\GrupoPrecioDetDAO;
use Dispo\DAO\DispoDAO;
use Dispo\Data\GrupoPrecioCabData;

class GrupoPrecioCabBO extends Conexion
{

	
	/**
	 * 
	 * @param string $grupoprecio
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboGrupoPrecio($grupoprecio, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());

		$result = $GrupoPrecioCabDAO->consultarTodos();
		
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'id', 'nombre', $grupoprecio, $texto_1er_elemento, $color_1er_elemento);
		 
		return $opciones;
	}//end function getComboGrupoPrecio

	
	
	
	/**
	 *
	 * @param string $tipo_precio
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */	
	function getComboTipoPrecio($tipo_precio, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{	
		$data = array(
				'NORMAL' 	=> 'PRECIO NORMAL',
				'OFERTA'	=> 'PRECIO OFERTA'		
		);
		$opciones = \Application\Classes\Combo::getComboDataArray($data, $tipo_precio, $texto_1er_elemento);		 
		return $opciones;
	}//end function getComboTipoPrecio
	
	
	
	
	/**
	 * @param string
	 * @param array $condiciones  (grupo_precio_cab_id)
	 * @return array
	 */
	public function listado($tipo_precio, $condiciones)
	{
		$GrupoPrecioCabDAO 	= new GrupoPrecioCabDAO();
		$GrupoDispoDetDAO	= new GrupoPrecioDetDAO();
		$DispoDAO 			= new DispoDAO();
		
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
		$DispoDAO->setEntityManager($this->getEntityManager());
		
		/**
		 * Se obtiene el registro CABECERA de la DISPO X GRUPO
		 */
		$reg_grupoPrecioCab = $GrupoPrecioCabDAO->consultarArray($condiciones['grupo_precio_cab_id']);
		if (empty($reg_grupoPrecioCab))
		{
			return null;
		}//end if
		
		
		/**
		 * Se obtiene los registro de la DISPO GENERAL  (UNIVERSO)
		 */
		$condiciones2 = array(
				"inventario_id"	=> $reg_grupoPrecioCab['inventario_id'],
				"proveedor_id"	=> null,
				"clasifica"		=> $reg_grupoPrecioCab['clasifica_fox'],
		);
		$result_dispo = $DispoDAO->listado($condiciones2);
		
		/**
		 * Se obtiene los registros de el PRECIO POR GRUPO
		 */
		$condiciones2 = array(
				"grupo_precio_cab_id"	=> $condiciones['grupo_precio_cab_id'],
		);		
		$result_dispo_grupo = $GrupoDispoDetDAO->listado($tipo_precio, $condiciones2);
		

		/**
		 * Se realizar el proceso de consolidacion de informacion
		 */
		//Indexar el RESULT de la DISPO GENERAL
		$result = null;
		foreach($result_dispo as $reg)
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
			$reg_new['existe']		= 0;
			$result[$reg['variedad_id']] = $reg_new;
		}//end foreach
		
		//Completa los campos del RESULT con la DISPO POR GRUPO
		foreach($result_dispo_grupo as $reg)
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
	 * @param  \Application\Dispo\Data\GrupoPrecioDetData $GrupoPrecioDetData
	 * @throws Exception
	 * @return string
	 */
	public function registrarPrecio($tipo_precio, $GrupoPrecioDetData)
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
	
	}//end function registrarStock
	
	
	/**
	 * 
	 * @param string $accion
	 * @param GrupoPrecioCabData $GrupoPrecioCabData
	 * @throws Exception
	 * @return array
	 */
	function registrarPorAccion($accion, GrupoPrecioCabData $GrupoPrecioCabData)
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
	
	
	/**
	 * 
	 * @param int $id
	 * @param int $resultType
	 * @return Ambigous <\Dispo\Data\GrupoPrecioCabData, NULL, multitype:>
	 */
	public function consultarCabecera($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$reg = $GrupoPrecioCabDAO->consultar($id, $resultType);
		return $reg;		
	}//end function consultarCabecera
	
	
	
	function listadoGrupoPrecioNoAsignadas()
	{
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoPrecioCabDAO->listadoGrupoPrecioNoAsignadas();
		return $result;
	}//end function listadoNoAsignadas
	
	
	/**
	 *
	 * @param array $condiciones (grupo_precio_cab_id);
	 * @return array
	 */
	function listadoAsignadas($condiciones)
	{
		$GrupoPrecioCabDAO = new GrupoPrecioCabDAO();
		$GrupoPrecioCabDAO->setEntityManager($this->getEntityManager());
		$result = $GrupoPrecioCabDAO->listadoAsignadas($condiciones);
		return $result;
	}//end function listadoAsignadas
	
	
}//end class
