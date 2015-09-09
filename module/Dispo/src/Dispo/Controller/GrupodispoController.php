<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GrupoDispoCabBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoDispoDetData;
use Dispo\BO\InventarioBO;
use Dispo\BO\CalidadBO;
use Dispo\Data\GrupoDispoCabData;

class GrupodispoController extends AbstractActionController
{
	
	public function initcontrolsAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$opcion						= $json['opcion'];
			
			switch ($opcion)
			{
				case 'panel-control-disponibilidad':
					$GrupoDispoCabBO 	= new GrupoDispoCabBO();
					$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						
					$grupo_dispo_1er_elemento	= $json['grupo_dispo_1er_elemento'];
					$grupo_dispo_cab_id		= null;
					
					$grupo_dispo_opciones 	= $GrupoDispoCabBO->getComboGrupoDispo($grupo_dispo_cab_id, $grupo_dispo_1er_elemento);
					
					$response = new \stdClass();
					$response->grupo_dispo_opciones		= $grupo_dispo_opciones;
					$response->respuesta_code 			= 'OK';
					break;
					
/*				case 'panel-control-mantenimiento':
					$InventarioBO		= new InventarioBO();					
					$CalidadBO			= new CalidadBO();
					
					$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					
					$inventario_1er_elemento	= $json['inventario_1er_elemento'];
					$inventario_id				= null;
					$calidad_1er_elemento	= $json['calidad_1er_elemento'];
					$calidad_id				= null;
					
					$inventario_opciones  	= $InventarioBO->getCombo($inventario_id, $inventario_1er_elemento);
					$calidad_opciones 	 	= $CalidadBO->getComboCalidad($calidad_id, $calidad_1er_elemento);
					
					$response = new \stdClass();
					$response->inventario_opciones		= $inventario_opciones;
					$response->calidad_opciones			= $calidad_opciones;
					$response->respuesta_code 			= 'OK';
					break;
*/					
			}//end switch
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function initcontrolsAction
	
	

	
	public function listadodataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$GrupoDispoCabBO = new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 		= $this->getRequest();
			$grupo_dispo_cab_id  	= $request->getQuery('grupo_dispo_cab_id', "");
			$flag_con_valores		= $request->getQuery('flag_con_valores', 0);
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$GrupoDispoCabBO->setPage($page);
			$GrupoDispoCabBO->setLimit($limit);
			$GrupoDispoCabBO->setSidx($sidx);
			$GrupoDispoCabBO->setSord($sord);
			$condiciones = array(
					"grupo_dispo_cab_id"	=> $grupo_dispo_cab_id,
			);
			$result = $GrupoDispoCabBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			if ($result)
			{	
				foreach($result as $row){		
					/*if ($flag_con_valores == 1)
					{
						if (($row['40']>0) || ($row['50']>0) || ($row['60']>0) || ($row['70']>0) || ($row['80']>0) || ($row['90']>0) || ($row['100']>0) || ($row['110']>0))
						{
							$response->rows[$i] = $row;
							$i++;						
						}//end if
					}else{
						$response->rows[$i] = $row;
						$i++;
					}//end if
					*/
					$response->rows[$i] = $row;
					$i++;
				}//end foreach
			}else{
				$response->rows = null;
			}
			$tot_reg = $i;
			$response->total 	= ceil($tot_reg/$limit);
			$response->page 	= $page;
			$response->records 	= $tot_reg;
			$json = new JsonModel(get_object_vars($response));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function disponibilidaddataAction

	
	
	function grabarstockAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$GrupoDispoCabBO 			= new GrupoDispoCabBO();
		
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$GrupoDispoDetData 		= new GrupoDispoDetData();
			$GrupoDispoDetData->setGrupoDispoCabId			($json['grupo_dispo_cab_id']);
			$GrupoDispoDetData->setVariedadId				($json['variedad_id']);
			$GrupoDispoDetData->setGradoId					($json['grado_id']);
			$GrupoDispoDetData->setCantidadBunchDisponible	($json['cantidad_bunch_disponible']);
			$GrupoDispoDetData->setUsuarioIngId				($usuario_id);
			$GrupoDispoDetData->setUsuarioModId 			($usuario_id);
			
			$result = $GrupoDispoCabBO->registrarStock($GrupoDispoDetData);
		
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];

			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function grabarstockAction

	
	public function consultarcabeceraAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoDispoCabBO 		= new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$grupo_dispo_cab_id		= $json['grupo_dispo_cab_id'];

			$row					= $GrupoDispoCabBO->consultarCabecera($grupo_dispo_cab_id, \Application\Constants\ResultType::MATRIZ);

			$response = new \stdClass();
			$response->row					= $row;
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';

			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function consultarcabAction	
	

	
	public function nuevodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);			
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$InventarioBO		= new InventarioBO();
			$CalidadBO			= new CalidadBO();
			
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$inventario_opciones  	= $InventarioBO->getCombo(null);
			$calidad_opciones 	 	= $CalidadBO->getComboCalidad(null);
				
			$response = new \stdClass();
			$response->inventario_opciones		= $inventario_opciones;
			$response->calidad_opciones			= $calidad_opciones;
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje	= '';
		
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}		
	}//end function nuevodataActtion
	
	
	
	public function consultarregistrodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoDispoCabBO 				= new GrupoDispoCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
						
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$grupo_dispo_cab_id		= $json['grupo_dispo_cab_id'];
		
			$row					= $GrupoDispoCabBO->consultarCabecera($grupo_dispo_cab_id, \Application\Constants\ResultType::MATRIZ);

			$response = new \stdClass();
			$response->row					= $row;
			$response->inventario_opciones	= $InventarioBO->getCombo($row['inventario_id'], "<Seleccione>");
			$response->calidad_opciones 	= $CalidadBO->getComboCalidad($row['calidad_id'], "<Seleccione>");
			$response->respuesta_code 		= 'OK';
			$response->respuesta_mensaje	= '';
				
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
		
	}//end function consultarregistrodataAction
	
	

	function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoDispoCabBO 		= new GrupoDispoCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
	
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());			
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();				
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$accion = ($json['accion']);
			$id		= $json['id'];
			
			$GrupoDispoCabData 		= new GrupoDispoCabData();
			$GrupoDispoCabData->setId 			($id);
			$GrupoDispoCabData->setNombre 		($json['nombre']);
			$GrupoDispoCabData->setInventarioId ($json['inventario_id']);
			$GrupoDispoCabData->setCalidadId 	($json['calidad_id']);
			$GrupoDispoCabData->setUsuarioIngId	($usuario_id);
			$GrupoDispoCabData->setUsuarioModId	($usuario_id);			
				
			switch ($accion)
			{
				case 'I': //Ingreso
					$result = $GrupoDispoCabBO->registrarPorAccion('I', $GrupoDispoCabData);
					$id	    = $result['id'];
					break;
					
				case 'M': //Modificar
					$result = $GrupoDispoCabBO->registrarPorAccion('M', $GrupoDispoCabData);
					$id	    = $result['id'];
					break;
			}//end switch
			
			$row	= $GrupoDispoCabBO->consultarCabecera($id, \Application\Constants\ResultType::MATRIZ);
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->row					= $row;
			$response->inventario_opciones	= $InventarioBO->getCombo($row['inventario_id'], "<Seleccione>");
			$response->calidad_opciones 	= $CalidadBO->getComboCalidad($row['calidad_id'], "<Seleccione>");			
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
			//false
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function grabarstockAction
	
}