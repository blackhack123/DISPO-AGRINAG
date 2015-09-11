<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ClienteAgenciaCargaBO;
use Dispo\Data\ClienteAgenciaCargaData;


class ClienteagenciacargaController extends AbstractActionController
{
	

	/*-----------------------------------------------------------------------------*/
	public function listadodataAction()
	/*-----------------------------------------------------------------------------*/
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$ClienteAgenciaCargaBO = new ClienteAgenciaCargaBO();
			$ClienteAgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 					= $this->getRequest();
			$cliente_id    				= $request->getQuery('cliente_id', "");
			$page 						= $request->getQuery('page');
			$limit 						= $request->getQuery('rows');
			$sidx						= $request->getQuery('sidx',1);
			$sord 						= $request->getQuery('sord', "");
			$ClienteAgenciaCargaBO->setPage($page);
			$ClienteAgenciaCargaBO->setLimit($limit);
			$ClienteAgenciaCargaBO->setSidx($sidx);
			$ClienteAgenciaCargaBO->setSord($sord);
			$condiciones = array(
					"cliente_id"			=> $cliente_id,
					//"criterio_busqueda"		=> $nombre,
					//"estado" 		=> $estado,
			);
			$result = $ClienteAgenciaCargaBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["id"] 				= $row["id"];
				$row2["nombre"] 			= trim($row["nombre"]);
				$row2["tipo"] 				= trim($row["tipo"]);
//				$row2["estado"] 			= $row["estado"];
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
	}//end function listadodataAction
	
	
	
	
	public function asignarAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$ClienteAgenciaCargaData= new ClienteAgenciaCargaData();
			$ClienteAgenciaCargaBO 	= new ClienteAgenciaCargaBO();
		
			$ClienteAgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			//Recibe las variables del Json
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			
			$formData 			= $json['formData'];
			$cliente_id			= $formData['cliente_id'];
			$grid_data 			= $json['grid_data'];
		
			//Prepara el Buffer de datos antes de llamar al BO
			$ArrClienteAgenciaCargaData   	= array();
			foreach ($grid_data as $reg)
			{
				$ClienteAgenciaCargaData->setClienteId 		($cliente_id);
				$ClienteAgenciaCargaData->setAgenciaCargaId ($reg['agencia_carga_id']);
				
				$ArrClienteAgenciaCargaData[] = $ClienteAgenciaCargaData;
			}//end foreach

			//Graba
			$result = $ClienteAgenciaCargaBO->grabar($ArrClienteAgenciaCargaData);
			
			
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
	}//end function asignarAction
	
	
	
	public function eliminarAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$ClienteAgenciaCargaData= new ClienteAgenciaCargaData();
			$ClienteAgenciaCargaBO 	= new ClienteAgenciaCargaBO();
	
			$ClienteAgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			//Recibe las variables del Json
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
				
			$formData 			= $json['formData'];
			$cliente_id			= $formData['cliente_id'];
			$grid_data 			= $json['grid_data'];
	
			//Prepara el Buffer de datos antes de llamar al BO
			$ArrClienteAgenciaCargaData   	= array();
			foreach ($grid_data as $reg)
			{
				$ClienteAgenciaCargaData->setClienteId 		($cliente_id);
				$ClienteAgenciaCargaData->setAgenciaCargaId ($reg['agencia_carga_id']);
	
				$ArrClienteAgenciaCargaData[] = $ClienteAgenciaCargaData;
			}//end foreach
	
			//Graba
			$result = $ClienteAgenciaCargaBO->eliminar($ArrClienteAgenciaCargaData);
			
				
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
	}//end function asignarAction
	
	
	
	
}//end controller