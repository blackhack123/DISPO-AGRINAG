<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\Data\MarcacionData;
use Dispo\BO\MarcacionBO;
use Dispo\BO\PaisBO;


class MarcacionController extends AbstractActionController
{

	
	/*-----------------------------------------------------------------------------*/
	public function listadodataAction()
	/*-----------------------------------------------------------------------------*/
	{
		try
		{		
			$EntityManagerPlugin = $this->EntityManagerPlugin();
				
			$MarcacionBO = new MarcacionBO();
			$MarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
		
			$request 		= $this->getRequest();
			$cliente_id    	= $request->getQuery('cliente_id', "");			
			$nombre      	= $request->getQuery('nombre', "");
			$estado 		= $request->getQuery('estado', "");
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$MarcacionBO->setPage($page);
			$MarcacionBO->setLimit($limit);
			$MarcacionBO->setSidx($sidx);
			$MarcacionBO->setSord($sord);
			$condiciones = array(
					"cliente_id"	=> $cliente_id,
					"nombre"		=> $nombre,
					"estado" 		=> $estado,
			);
			$result = $MarcacionBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["marcacion_sec"] 		= $row["marcacion_sec"];
				$row2["nombre"] 			= trim($row["nombre"]);
				$row2["pais_nombre"] 		= trim($row["pais_nombre"]);
				$row2["ciudad"] 			= trim($row["ciudad"]);
				$row2["sincronizado"] 		= $row["sincronizado"];
				$row2["fec_sincronizado"] 	= $row["fec_sincronizado"];
				$row2["estado"] 			= $row["estado"];
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
	
	
	
	public function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
			
			$MarcacionBO = new MarcacionBO();
			$MarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;			
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();

			$opciones = $MarcacionBO->getComboPorClienteId($cliente_id, 0, $texto_primer_elemento);

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
	}//end function indexAction	
	
	
	public function nuevodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$PaisBO 				= new PaisBO();
			$PaisBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
	
	
			$response 		= new \stdClass();
			$pais 			= null;
			$cliente_id 			= null;
			$response->cbo_pais_id			= $PaisBO->getComboPais($pais, "&lt;Seleccione&gt;");
			//	$response->cliente_id			=ClienteBO->
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado("","");
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
	}//end function nuevodataAction
	
	
	
	
	public function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$MarcacionData			= new MarcacionData();
			$MarcacionBO 			= new MarcacionBO();
			$MarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$MarcacionData->setMarcacionSec			($json['marcacion_sec']);
			$MarcacionData->setClienteId			($json['cliente_id']);
			$MarcacionData->setNombre				($json['nombre']);
			$MarcacionData->setDireccion			($json['direccion']);
			$MarcacionData->setPaisId				($json['pais_id']);
			$MarcacionData->setCiudad				($json['ciudad']);
			$MarcacionData->setContacto				($json['contacto']);
			$MarcacionData->setTelefono				($json['telefono']);
			$MarcacionData->setZip					($json['zip']);
			$MarcacionData->setPuntoCorte			($json['punto_corte']);
			$MarcacionData->setEstado				($json['estado']);
	
			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$MarcacionData->setUsuarioIngId($usuario_id);
					$result = $MarcacionBO->ingresar($MarcacionData);
					break;
	
				case 'M':
					$MarcacionData->setUsuarioModId($usuario_id);
					$result = $MarcacionBO->modificar($MarcacionData);
					break;
	
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $MarcacionBO->consultar($json['marcacion_sec'], \Application\Constants\ResultType::MATRIZ);
			}else{
				$row	= null;
			}//end if
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->validacion_code 		= $result['validacion_code'];
			$response->respuesta_mensaje	= $result['respuesta_mensaje'];
			if ($row)
			{
				$response->row					= $row;
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			}else{
				$response->row					= null;
				$response->cbo_estado			= '';
			}//end if
	
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
	
	
	
	public function consultardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$MarcacionBO 			= new MarcacionBO();
			$PaisBO 				= new PaisBO();
			$MarcacionBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PaisBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$marcacion_sec		= $json['marcacion_sec'];
	
			$row					= $MarcacionBO->consultar($marcacion_sec, \Application\Constants\ResultType::MATRIZ);
	
			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_pais_id			= $PaisBO->getComboPais($row['pais_id'], "&lt;Seleccione&gt;");
			$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
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
	}//end function consultardataAction

}//end controller