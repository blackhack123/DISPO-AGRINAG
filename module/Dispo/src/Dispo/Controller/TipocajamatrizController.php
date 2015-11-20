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
use Dispo\BO\TamanoBunchBO;
use Dispo\BO\GradoBO;
use Dispo\BO\DispoBO;
use Dispo\BO\Dispo\BO;

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
					$TipoCajaMatrizBO	= new TipoCajaMatrizBO();
					$GradoBO			= new GradoBO();
					
					$TipoCajaMatrizBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$GradoBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					
					$variedad_1er_elemento		= $json['variedad_1er_elemento'];
					$variedad_id	= null;
					$grado_1er_elemento			= $json['grado_1er_elemento'];
					$grado_id		= null;
					$inventario_id				= $json['inventario_id'];
					$tipo_caja_id				= $json['tipo_caja_id'];
						
					//Se debe de obtener las variedades de acuerdo al tipo de inventario  $inventario_id, $variedad_id, $variedad_1er_elemento
					$variedad_opciones		= $TipoCajaMatrizBO->getComboVariedad($tipo_caja_id, $inventario_id, $variedad_id, $variedad_1er_elemento);
					$grado_opciones			= $GradoBO->getCombo($grado_id, $grado_1er_elemento);

					$response = new \stdClass();
					$response->variedad_opciones		= $variedad_opciones;
					$response->grado_opciones			= $grado_opciones;
					$response->respuesta_code 			= 'OK';
					break;
					
				case 'caja-matriz':
					
					$TipoCajaBO		= new TipoCajaBO();
					$InventarioBO 	= new InventarioBO();
					$TamanoBunchBO	= new TamanoBunchBO();
					
					$TipoCajaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$TamanoBunchBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					
					$tipo_caja_id		= $json['tipo_caja_id'];
					$inventario_id		= $json['inventario_id'];
					$tamano_bunch_id	= $json['tamano_bunch_id'];
					
					$inventario_opciones 	= $InventarioBO->getCombo($inventario_id);
					$tipocaja_opciones 		= $TipoCajaBO->getCombo($tipo_caja_id);
					$tamano_bunch_opciones	= $TamanoBunchBO->getCombo($tamano_bunch_id);	
					
					$response = new \stdClass();
					$response->tipocaja_opciones		= $tipocaja_opciones;
					$response->inventario_opciones		= $inventario_opciones;
					$response->tamano_bunch_opciones	= $tamano_bunch_opciones;
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
			//$tipo_caja_id  	= $request->getQuery('tipo_caja_id');
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
					//"tipo_caja_id"	=> $tipo_caja_id,
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
	
	
	
	public function registrarCajaMatrizAction()
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
			
			
			$tipo_caja_id 		= $json['tipo_caja_id'];
			$inventario_id 		= $json['inventario_id'];
			$tallos_x_bunch		= $json['tallos_x_bunch'];
			$tamano_bunch_id	= $json['tamano_bunch_id'];
			
			$grid_data 		= $json['grid_data'];
			
			$arr_grados = array('40','50','60','70','80','90','100','110');
			$ArrTipoCajaMatrizData= array();
			foreach ($grid_data	as $reg){
				
				foreach($arr_grados as $grado_id)
				{
					$TipoCajaMatrizData = new TipoCajaMatrizData();
					
					$TipoCajaMatrizData->setInventarioId($inventario_id);
					$TipoCajaMatrizData->setTipoCajaId($tipo_caja_id);
					$TipoCajaMatrizData->setTallosxBunch($tallos_x_bunch);
					$TipoCajaMatrizData->setTamanoBunchId($tamano_bunch_id);
					$TipoCajaMatrizData->setGradoId($grado_id);
					$TipoCajaMatrizData->setUndsBunch($reg[$grado_id]);
					$TipoCajaMatrizData->setUsuarioIngId($usuario_id);
					$TipoCajaMatrizData->setUsuarioModId($usuario_id);
					
					$ArrTipoCajaMatrizData[] = $TipoCajaMatrizData;
					unset($TipoCajaMatrizData);
				}//end foreach
			}//end foreach
			
			$respuesta = $TipoCajaMatrizBO->registrarCajaMatriz($ArrTipoCajaMatrizData);
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$json = new JsonModel(get_object_vars($response));
			return $json;
		}catch(\Exception $e){
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function registrarCajaMatrizAction
	
	
	public function consultarPorClaveAlternaListadoAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();

			$TipoCajaMatrizBO = new TipoCajaMatrizBO();
			$TipoCajaMatrizBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);

			//recibo los paramtros para el listado
	  /*	$inventario_id		= $json['inventario_id'];
			$tipo_caja_id		= $json['tipo_caja_id'];
			$tamano_bunch_id	= $json['tamano_bunch_id'];
			$tallos_x_bunch		= $json['tallos_x_bunch'];
		*/
			//paguineo de la grilla
			$request 		= $this->getRequest();
			
			$inventario_id		= $request->getQuery('inventario_id');
			$tipo_caja_id		= $request->getQuery('tipo_caja_id');
			$tamano_bunch_id	= $request->getQuery('tamano_bunch_id');
			$tallos_x_bunch		= $request->getQuery('tallos_x_bunch');
			
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$TipoCajaMatrizBO->setPage($page);
			$TipoCajaMatrizBO->setLimit($limit);
			$TipoCajaMatrizBO->setSidx($sidx);
			$TipoCajaMatrizBO->setSord($sord);
			//se establece las condiciones o parametros
			
			$condiciones = array(
					"inventario_id"		=> $inventario_id,
					"tipo_caja_id"		=> $tipo_caja_id,
					"tamano_bunch_id"	=> $tamano_bunch_id,
					"tallos_x_bunch"	=> $tallos_x_bunch,
			);
			
			
			//consulta el listado con parametros $condiciones
			$result				= $TipoCajaMatrizBO->consultarPorClaveAlternaListado($condiciones);
			
	
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$i=0;
			if ($result)
			{
				foreach($result as $row){
					$response->rows[$i] = $row;
					$i++;
				}//end foreach
			}//end if
			$i = 1;
			$tot_reg = $i;
			$response->total 	= ceil($i);
			$response->page 	= $i;
			$response->records 	= $tot_reg;
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
	}//end function consultarPorClaveAlternaListadoAction
	
	
	
}// end Class TipocajamatrizController