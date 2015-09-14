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
			$viewModel->setTemplate('dispo/TipoCajaMarcacion/mantenimiento.phtml');
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
			$TipoCajaMarcacionBO 		= new TipoCajaMarcacionBO();
		
			$TipoCajaMarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$TipoCajaMarcacionData = new TipoCajaMarcacionData();
			$TipoCajaMarcacionData->setTipoCajaId 		($json['tipo_caja_id']);
			$TipoCajaMarcacionData->setInventarioId 	($json['inventario_id']);
			$TipoCajaMarcacionData->setVariedadId 		($json['variedad_id']);
			$TipoCajaMarcacionData->setGradoId 		($json['grado_id']);
			$TipoCajaMarcacionData->setUndsBunch 		($json['nro_bunches']);  
			$TipoCajaMarcacionData->setUsuarioIngId	($usuario_id);
			$TipoCajaMarcacionData->setUsuarioModId	($usuario_id);
			
			$result = $TipoCajaMarcacionBO->registrarBunchs($TipoCajaMarcacionData);
		
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			/*$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
			*/
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


	public function actualizarmasivoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
				
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$TipoCajaMarcacionBO 		= new TipoCajaMarcacionBO();
		
			$TipoCajaMarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			$parametros['tipo_caja_id'] = $json['tipo_caja_id'];
			$parametros['inventario_id']= $json['inventario_id'];
			$parametros['variedad_id'] 	= $json['variedad_id'];
			$parametros['grado_id'] 	= $json['grado_id'];
			$parametros['unds_bunch'] 	= $json['unds_bunch'];
			$parametros['usuario_id'] 	= $usuario_id;

			$result = $TipoCajaMarcacionBO->actualizacionMasiva($parametros);

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			/*$response->validacion_code 		= $result['validacion_code'];
			 $response->respuesta_mensaje	= $result['respuesta_mensaje'];
			 */
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
	}//end funcion actualizarmasivoAction
	
	
}