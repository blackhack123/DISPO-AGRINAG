<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GrupoPrecioCabBO;
use Dispo\BO\InventarioBO;
use Dispo\BO\CalidadBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoPrecioDetData;
use Dispo\Data\GrupoPrecioCabData;


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
	
	
	function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoPrecioCabBO 		= new GrupoPrecioCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
	
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
				
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$accion = ($json['accion']);
			$id		= $json['id'];
				
			$GrupoPrecioCabData 		= new GrupoPrecioCabData();
			$GrupoPrecioCabData->setId 				($id);
			$GrupoPrecioCabData->setNombre 			($json['nombre']);
			$GrupoPrecioCabData->setInventarioId 	($json['inventario_id']);
			$GrupoPrecioCabData->setCalidadId 		($json['calidad_id']);
			$GrupoPrecioCabData->setUsuarioIngId	($usuario_id);
			$GrupoPrecioCabData->setUsuarioModId	($usuario_id);
	
			switch ($accion)
			{
				case 'I': //Ingreso
					$result = $GrupoPrecioCabBO->registrarPorAccion('I', $GrupoPrecioCabData);
					$id	    = $result['id'];
					break;
						
				case 'M': //Modificar
					$result = $GrupoPrecioCabBO->registrarPorAccion('M', $GrupoPrecioCabData);
					$id	    = $result['id'];
					break;
			}//end switch
				
			$row	= $GrupoPrecioCabBO->consultarCabecera($id, \Application\Constants\ResultType::MATRIZ);
	
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
	}//end function grabardataAction
	
	
	
	public function consultarregistrodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoPrecioCabBO 		= new GrupoPrecioCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
	
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$grupo_precio_cab_id	= $json['grupo_precio_cab_id'];
	
			$row					= $GrupoPrecioCabBO->consultarCabecera($grupo_precio_cab_id, \Application\Constants\ResultType::MATRIZ);
	
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
	
	

	/*
	 * *****************************************************************
	 * 				FUNCIONES GRUPO PRECIO
	 * *****************************************************************
	 *
	 */
	
	/*-----------------------------------------------------------------------------*/
	public function listadogrupoprecionoasignadosdataAction()
	/*-----------------------------------------------------------------------------*/
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoPrecioCabBO = new GrupoPrecioCabBO();
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 					= $this->getRequest();
			//$cliente_id    				= $request->getQuery('cliente_id', "");
			$page 						= $request->getQuery('page');
			$limit 						= $request->getQuery('rows');
			$sidx						= $request->getQuery('sidx',1);
			$sord 						= $request->getQuery('sord', "");
			$GrupoPrecioCabBO->setPage($page);
			$GrupoPrecioCabBO->setLimit($limit);
			$GrupoPrecioCabBO->setSidx($sidx);
			$GrupoPrecioCabBO->setSord($sord);
			//$condiciones = array(
			//"cliente_id"			=> $cliente_id,
			//"estado"				=> "A"
			//);
			$result = $GrupoPrecioCabBO->listadoGrupoPrecioNoAsignadas();
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["id"] 	= trim($row["id"]);
				$row2["nombre"] 	= trim($row["nombre"]);
				//$row2["usuario_nombre"] 	= trim($row["usuario_nombre"]);
	
				$response->rows[$i] = $row2;
				$i++;
			}//end foreach
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
	}//end function listadogrupodisponoasignadosdataAction
	
	
	
	public function listadogrupoprecioasignadosdataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoPrecioCabBO = new GrupoPrecioCabBO();
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 					= $this->getRequest();
			$grupo_precio_cab_id 		= $request->getQuery('grupo_precio_cab_id', "");
			$page 						= $request->getQuery('page');
			$limit 						= $request->getQuery('rows');
			$sidx						= $request->getQuery('sidx',1);
			$sord 						= $request->getQuery('sord', "");
			$GrupoPrecioCabBO->setPage($page);
			$GrupoPrecioCabBO->setLimit($limit);
			$GrupoPrecioCabBO->setSidx($sidx);
			$GrupoPrecioCabBO->setSord($sord);
	
			$condiciones = array(
					"grupo_precio_cab_id"	=> $grupo_precio_cab_id,
			);
			$result = $GrupoPrecioCabBO->listadoAsignadas($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["id"] 	= trim($row["id"]);
				$row2["nombre"] 	= trim($row["nombre"]);
				//$row2["usuario_nombre"] 	= trim($row["usuario_nombre"]);
				$row2["estado"] 			= trim($row["estado"]);
	
				$response->rows[$i] = $row2;
				$i++;
			}//end foreach
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
	}//end function listadogrupodispoasignadosdataAction
	
	
	
	function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoPrecioCabBO = new GrupoPrecioCabBO();
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$grupo_precio_cab_id		= $json['grupo_precio_cab_id'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
	
			$opciones 	= $GrupoPrecioCabBO->getComboGrupoPrecio($grupo_precio_cab_id, $texto_primer_elemento);
				
			$response = new \stdClass();
			$response->opciones				= $opciones;
			$response->respuesta_code 		= 'OK';
	
			$json = new JsonModel(get_object_vars($response));
			return $json;
	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	
	}//end function getcomboAction
	
	
}