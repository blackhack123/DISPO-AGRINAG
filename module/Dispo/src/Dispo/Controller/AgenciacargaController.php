<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\MarcacionBO;
use Dispo\BO\AgenciaCargaBO;
use Dispo\Data\AgenciaCargaData;


class AgenciacargaController extends AbstractActionController
{
	

	/*-----------------------------------------------------------------------------*/
	public function agencialistadodataAction()
	/*-----------------------------------------------------------------------------*/
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$AgenciaCargaBO = new AgenciaCargaBO();
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
				
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 		= $this->getRequest();
			//$cliente_id    	= $request->getQuery('cliente_id', "");
			$nombre      	= $request->getQuery('nombre', "");
			$estado 		= $request->getQuery('estado', "");
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$AgenciaCargaBO->setPage($page);
			$AgenciaCargaBO->setLimit($limit);
			$AgenciaCargaBO->setSidx($sidx);
			$AgenciaCargaBO->setSord($sord);
			$condiciones = array(
					//"id"			=> $id,
					"criterio_busqueda"		=> $nombre,
					"estado" 		=> $estado,
			);
			$result = $AgenciaCargaBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["id"] 				= $row["id"];
				$row2["nombre"] 			= trim($row["nombre"]);
				$row2["telefono"] 			= trim($row["telefono"]);
				$row2["tipo"] 				= trim($row["tipo"]);
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
			
			$AgenciaCargaBO = new AgenciaCargaBO();
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;			
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
			$agencia_carga_id = null;

			$opciones = $AgenciaCargaBO->getComboTodos($agencia_carga_id, $texto_primer_elemento);

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

	
	
	public function mantenimientoAction()
	{
		try
		{
			$viewModel 				= new ViewModel();
				
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			//Controla el acceso a la informacion, solo accedera si es administrador
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$respuesta =  $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$AgenciaCargaBO				= new AgenciaCargaBO();
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$condiciones['criterio_busqueda']		= $this->params()->fromPost('criterio_busqueda','');
			$condiciones['estado']					= $this->params()->fromPost('busqueda_estado','');
			$condiciones['sincronizado']			= $this->params()->fromPost('busqueda_sincronizado','');			

			$result 		= $AgenciaCargaBO->listado($condiciones);
			
			$viewModel->criterio_busqueda	= $condiciones['criterio_busqueda'];
			$viewModel->busqueda_estado				=  \Application\Classes\ComboGeneral::getComboEstado($condiciones['estado'],"&lt;ESTADO&gt;");
			$viewModel->busqueda_sincronizado		= \Application\Classes\ComboGeneral::getComboSincronizado($condiciones['sincronizado'],"&lt;SINCRONIZADO&gt;");
			$viewModel->result				= $result;
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/agencia_carga/mantenimiento.phtml');
			return $viewModel;			
			
		
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function 
	

	
	
	public function nuevodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
		
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$AgenciaCargaBO 				= new AgenciaCargaBO();
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$response = new \stdClass();
			$response->cbo_tipo				= $AgenciaCargaBO->getComboTipo("", " ");
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
	
	
	
	public function consultardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$AgenciaCargaBO 				= new AgenciaCargaBO();
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$agencia_carga_id		= $json['agencia_carga_id'];

			$row					= $AgenciaCargaBO->consultar($agencia_carga_id, \Application\Constants\ResultType::MATRIZ);

			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_tipo				= $AgenciaCargaBO->getComboTipo($row['tipo'], " ");
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
	}//end function consultarAction
	
	

	
	public function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$AgenciaCargaData		= new AgenciaCargaData();
			$AgenciaCargaBO 		= new AgenciaCargaBO();
			$AgenciaCargaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$AgenciaCargaData->setId		($json['id']); 
			$AgenciaCargaData->setNombre	($json['nombre']);
			$AgenciaCargaData->setDireccion	($json['direccion']);
			$AgenciaCargaData->setTelefono	($json['telefono']);
			$AgenciaCargaData->setTipo		($json['tipo']);
			$AgenciaCargaData->setEstado	($json['estado']);

			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$AgenciaCargaData->setUsuarioIngId($usuario_id);
					$result = $AgenciaCargaBO->ingresar($AgenciaCargaData);
					break;
					
				case 'M':
					$AgenciaCargaData->setUsuarioModId($usuario_id);
					$result = $AgenciaCargaBO->modificar($AgenciaCargaData);					
					break;
					
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $AgenciaCargaBO->consultar($json['id'], \Application\Constants\ResultType::MATRIZ);
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
				$response->cbo_tipo				= $AgenciaCargaBO->getComboTipo($row['tipo'], " ");
				$response->cbo_estado			= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			}else{
				$response->row					= null;
				$response->cbo_tipo				= '';
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
	}//end function consultarAction	
	
	
}//end controller