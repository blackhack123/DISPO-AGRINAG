<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GrupoPrecioCabBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoPrecioDetData;

class GrupoprecioController extends AbstractActionController
{
	
	public function initcontrolsAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoPrecioCabBO 	= new GrupoPrecioCabBO();
	
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$opcion						= $json['opcion'];

			switch ($opcion)
			{
				case 'panel-control-disponibilidad':			
					$grupo_dispo_1er_elemento	= $json['grupo_dispo_1er_elemento'];
					$grupo_precio_cab_id		= null;
					$tipo_precio_1er_elemento	= '';
			
					$grupo_precio_opciones 	= $GrupoPrecioCabBO->getComboGrupoPrecio($grupo_precio_cab_id, $grupo_dispo_1er_elemento);
					$tipo_precio_opciones	= $GrupoPrecioCabBO->getComboTipoPrecio('', $tipo_precio_1er_elemento);

					$response = new \stdClass();
					$response->grupo_precio_opciones	= $grupo_precio_opciones;
					$response->tipo_precio_opciones		= $tipo_precio_opciones;
					$response->respuesta_code 			= 'OK';
					break;
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

			$GrupoPrecioCabBO = new GrupoPrecioCabBO();
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 		= $this->getRequest();
			$grupo_precio_cab_id  	= $request->getQuery('grupo_precio_cab_id');
			$tipo_precio  	= $request->getQuery('tipo_precio');
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$GrupoPrecioCabBO->setPage($page);
			$GrupoPrecioCabBO->setLimit($limit);
			$GrupoPrecioCabBO->setSidx($sidx);
			$GrupoPrecioCabBO->setSord($sord);
			$condiciones = array(
					"grupo_precio_cab_id"	=> $grupo_precio_cab_id,					
			);
			$result = $GrupoPrecioCabBO->listado($tipo_precio, $condiciones);
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
	}//end function disponibilidaddataAction

	
	
	function grabarAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$GrupoPrecioCabBO 			= new GrupoPrecioCabBO();
		
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$GrupoPrecioDetData = new GrupoPrecioDetData();
			$GrupoPrecioDetData->setGrupoPrecioCabId 		($json['grupo_precio_cab_id']);
			$GrupoPrecioDetData->setVariedadId				($json['variedad_id']);
			$GrupoPrecioDetData->setGradoId					($json['grado_id']);
			$GrupoPrecioDetData->setPrecio  				($json['precio']);
			$GrupoPrecioDetData->setPrecioOferta 			($json['precio']);  
			
			$result = $GrupoPrecioCabBO->registrarPrecio($json['tipo_precio'], $GrupoPrecioDetData);
		
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
			$GrupoPrecioCabBO 		= new GrupoPrecioCabBO();
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$grupo_precio_cab_id		= $json['grupo_precio_cab_id'];

			$row					= $GrupoPrecioCabBO->consultarCabecera($grupo_precio_cab_id, \Application\Constants\ResultType::MATRIZ);

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
	
}