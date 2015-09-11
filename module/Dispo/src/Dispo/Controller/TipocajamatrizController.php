<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\TipoCajaMatrizBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoPrecioDetData;
use Dispo\BO\InventarioBO;
use Dispo\BO\TipoCajaBO;
use Dispo\Data\TipoCajaMatrizData;
use Dispo\BO\VariedadBO;
use Dispo\BO\GradoBO;

class TipocajamatrizController extends AbstractActionController
{
	
	public function mantenimientoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$viewModel 				= new ViewModel();
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/tipocajamatriz/mantenimiento.phtml');
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
				case 'mantenimiento':			
					$InventarioBO 	= new InventarioBO();
					$TipoCajaBO		= new TipoCajaBO();
								
					$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$TipoCajaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						
					$inventario_1er_elemento	= $json['inventario_1er_elemento'];
					$inventario_id	= null;
					$tipo_caja_1er_elemento		= $json['tipo_caja_1er_elemento'];
					$tipo_caja_id	= null;
			
					$inventario_opciones 	= $InventarioBO->getCombo($inventario_id, $inventario_1er_elemento);
					$tipocaja_opciones 		= $TipoCajaBO->getCombo($tipo_caja_id, $tipo_caja_1er_elemento);

					$response = new \stdClass();
					$response->inventario_opciones		= $inventario_opciones;
					$response->tipocaja_opciones		= $tipocaja_opciones;
					$response->respuesta_code 			= 'OK';
					break;
					
				case 'actualizacion-masiva':
					$VariedadBO		= new VariedadBO();
					$GradoBO		= new GradoBO();
					
					$VariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$GradoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					
					$variedad_1er_elemento		= $json['variedad_1er_elemento'];
					$variedad_id	= null;
					$grado_1er_elemento			= $json['grado_1er_elemento'];
					$grado_id		= null;
					
					$variedad_opciones		= $VariedadBO->getCombo($variedad_id, $variedad_1er_elemento);
					$grado_opciones			= $GradoBO->getCombo($grado_id, $grado_1er_elemento);

					$response = new \stdClass();
					$response->variedad_opciones		= $variedad_opciones;
					$response->grado_opciones			= $grado_opciones;
					$response->respuesta_code 			= 'OK';
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

			$TipoCajaMatrizBO = new TipoCajaMatrizBO();
			$TipoCajaMatrizBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 		= $this->getRequest();
			$inventario_id 	= $request->getQuery('inventario_id');
			$tipo_caja_id  	= $request->getQuery('tipo_caja_id');
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$TipoCajaMatrizBO->setPage($page);
			$TipoCajaMatrizBO->setLimit($limit);
			$TipoCajaMatrizBO->setSidx($sidx);
			$TipoCajaMatrizBO->setSord($sord);
			$condiciones = array(
					"inventario_id"	=> $inventario_id,
					"tipo_caja_id"	=> $tipo_caja_id,
			);
			$result = $TipoCajaMatrizBO->listado($condiciones);
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
			$TipoCajaMatrizBO 		= new TipoCajaMatrizBO();
		
			$TipoCajaMatrizBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$TipoCajaMatrizData = new TipoCajaMatrizData();
			$TipoCajaMatrizData->setTipoCajaId 		($json['tipo_caja_id']);
			$TipoCajaMatrizData->setInventarioId 	($json['inventario_id']);
			$TipoCajaMatrizData->setVariedadId 		($json['variedad_id']);
			$TipoCajaMatrizData->setGradoId 		($json['grado_id']);
			$TipoCajaMatrizData->setUndsBunch 		($json['nro_bunches']);  
			$TipoCajaMatrizData->setUsuarioIngId	($usuario_id);
			$TipoCajaMatrizData->setUsuarioModId	($usuario_id);
			
			$result = $TipoCajaMatrizBO->registrarBunchs($TipoCajaMatrizData);
		
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
			$TipoCajaMatrizBO 		= new TipoCajaMatrizBO();
		
			$TipoCajaMatrizBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
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

			$result = $TipoCajaMatrizBO->actualizacionMasiva($parametros);

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