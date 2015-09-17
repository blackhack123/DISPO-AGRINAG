<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\TipoCajaMarcacionBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoPrecioDetData;
use Dispo\BO\InventarioBO;
use Dispo\BO\TipoCajaBO;
use Dispo\Data\TipoCajaMarcacionData;
use Dispo\BO\VariedadBO;
use Dispo\BO\GradoBO;
use Dispo\BO\DispoBO;
use Dispo\BO\Dispo\BO;

class TipocajamarcacionController extends AbstractActionController
{
	
	public function mantenimientoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$viewModel 				= new ViewModel();
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/tipocajamarcacion/mantenimiento.phtml');
			return $viewModel;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function mantenimientoAction
	
	
	public function initcontrolsAction()
	{
		//No hay nada
	}//end function initcontrolsAction
	
	

	
	public function listadodataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$TipoCajaMarcacionBO = new TipoCajaMarcacionBO();
			$TipoCajaMarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 			= $this->getRequest();
			$cliente_nombre 	= $request->getQuery('cliente_nombre');
			$marcacion_nombre  	= $request->getQuery('marcacion_nombre');
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			$TipoCajaMarcacionBO->setPage($page);
			$TipoCajaMarcacionBO->setLimit($limit);
			$TipoCajaMarcacionBO->setSidx($sidx);
			$TipoCajaMarcacionBO->setSord($sord);
			$condiciones = array(
					"cliente_nombre"	=> $cliente_nombre,
					"marcacion_nombre"	=> $marcacion_nombre,
			);
			$result = $TipoCajaMarcacionBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			if ($result)
			{
				foreach($result as $row){
					$response->rows[$i] = $row;
					$i++;
				}//end foreach
				$response->userdata['sec_maximo'] = $i;
			}else{
				$response->rows = null;
				$response->userdata['sec_maximo'] = 0;
			}//end if
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
	}//end function listadodataAction

	
	public function grabarAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();

			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
				
			$TipoCajaMarcacionBO 	= new TipoCajaMarcacionBO();
			$TipoCajaMarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$request 		= $this->getRequest();
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$gridTipoCaja_ingresar 		= $json['gridTipoCaja_ingresar'];
			$gridTipoCaja_modificar		= $json['gridTipoCaja_modificar'];
			$gridTipoCaja_eliminar		= $json['gridTipoCaja_eliminar'];

			//Detalle de la Caja Marcacion (ELIMINAR)
			$ArrTipoCaja	= array();
			foreach ($gridTipoCaja_eliminar	as $reg){
				$TipoCajaMarcacionData = new TipoCajaMarcacionData();
			
				$TipoCajaMarcacionData->setAccion('E');
				$TipoCajaMarcacionData->setId($reg['id']);
			
				$ArrTipoCaja[] = $TipoCajaMarcacionData;
					
				unset($TipoCajaMarcacionData);
			}//end foreach			
			
			
			//Detalle de la Caja Marcacion (INGRESAR)
			foreach ($gridTipoCaja_ingresar	as $reg){
				$TipoCajaMarcacionData = new TipoCajaMarcacionData();
					
				$TipoCajaMarcacionData->setAccion('I');
				$TipoCajaMarcacionData->setId($reg['id']);
				$TipoCajaMarcacionData->setMarcacionSec($reg['marcacion_sec']);
				$TipoCajaMarcacionData->setTipoCajaId($reg['tipo_caja_id']);
				$TipoCajaMarcacionData->setInventarioId($reg['inventario_id']);
				$TipoCajaMarcacionData->setVariedadId($reg['variedad_id']);
				$TipoCajaMarcacionData->setGradoId($reg['grado_id']);
				$TipoCajaMarcacionData->setUndsBunch($reg['unds_bunch']);
				$TipoCajaMarcacionData->setUsuarioIngId($usuario_id);
				$TipoCajaMarcacionData->setUsuarioModId($usuario_id);
			
				$ArrTipoCaja[] = $TipoCajaMarcacionData;
					
				unset($TipoCajaMarcacionData);
			}//end foreach			
			
			
			//Detalle de la Caja Marcacion (MODIFICAR)
			foreach ($gridTipoCaja_modificar	as $reg){
				$TipoCajaMarcacionData = new TipoCajaMarcacionData();
					
				$TipoCajaMarcacionData->setAccion('M');
				$TipoCajaMarcacionData->setId($reg['id']);
				$TipoCajaMarcacionData->setMarcacionSec($reg['marcacion_sec']);
				$TipoCajaMarcacionData->setTipoCajaId($reg['tipo_caja_id']);
				$TipoCajaMarcacionData->setInventarioId($reg['inventario_id']);
				$TipoCajaMarcacionData->setVariedadId($reg['variedad_id']);
				$TipoCajaMarcacionData->setGradoId($reg['grado_id']);
				$TipoCajaMarcacionData->setUndsBunch($reg['unds_bunch']);
				$TipoCajaMarcacionData->setUsuarioIngId($usuario_id);
				$TipoCajaMarcacionData->setUsuarioModId($usuario_id);
					
				$ArrTipoCaja[] = $TipoCajaMarcacionData;
					
				unset($TipoCajaMarcacionData);
			}//end foreach			
			
			$respuesta = $TipoCajaMarcacionBO->grabar($ArrTipoCaja);
			
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
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
	}//end function grabarAction
	
	
	
	public function getCombosDataGridAction()
	{
		try{
			$EntityManagerPlugin= $this->EntityManagerPlugin();
			$TipoCajaBO 		= new TipoCajaBO();
			$InventarioBO		= new InventarioBO();
			$GradoBO			= new GradoBO();
			
			$TipoCajaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GradoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$request 				= $this->getRequest();
			$opciones_tipo_caja		= utf8_encode($TipoCajaBO->getComboDataGrid());
			$opciones_inventario	= utf8_encode($TipoCajaBO->getComboDataGrid());
			$opciones_grado			= utf8_encode($GradoBO->getComboDataGrid());
			
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->opciones_tipo_caja 		= $opciones_tipo_caja;
			$response->opciones_inventario 		= $opciones_inventario;
			$response->opciones_grado 			= $opciones_grado;
			$json = new JsonModel(get_object_vars($response));
			return $json;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end  function getComboDataGridAction
}