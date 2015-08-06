<?php
namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ClienteBO;
use Dispo\BO\MarcacionBO;
use Dispo\BO\AgenciaCargaBO;
use Seguridad\BO\UsuarioBO;
use Seguridad\BO\PerfilBO;
use Dispo\DATA\AgenciaCargaDATA;
use Seguridad\DATA\UsuarioDATA;
use Dispo\BO\PaisBO;
use Dispo\Data\ClienteData;
use Dispo\Data\MarcacionData;
use Dispo\BO\GrupoPrecioCabBO;


class ClienteController extends AbstractActionController
{

	public function getcomboAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
			
			$ClienteBO = new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginVentas();  //Solo el Vendedor Puede hacer este procedimiento

			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;			
			$texto_primer_elemento		= $json['texto_primer_elemento'];
			$cliente_id 				= null;

			$opciones = $ClienteBO->getCombo($cliente_id, $texto_primer_elemento);

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
	
			$ClienteBO				= new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$condiciones['criterio_busqueda']		= $this->params()->fromPost('criterio_busqueda','');
			$condiciones['estado']					= $this->params()->fromPost('busqueda_estado','');
			$condiciones['sincronizado']			= $this->params()->fromPost('busqueda_sincronizado','');
	
			$result 		= $ClienteBO->listado($condiciones);
				
			$viewModel->criterio_busqueda	= $condiciones['criterio_busqueda'];
			$viewModel->busqueda_estado				=  \Application\Classes\ComboGeneral::getComboEstado($condiciones['estado'],"&lt;ESTADO&gt;");
			$viewModel->busqueda_sincronizado		= \Application\Classes\ComboGeneral::getComboSincronizado($condiciones['sincronizado'],"&lt;SINCRONIZADO&gt;");
			$viewModel->result				= $result;
			$this->layout($SesionUsuarioPlugin->getUserLayout());
			$viewModel->setTemplate('dispo/cliente/mantenimiento.phtml');
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
			$ClienteBO 				= new ClienteBO();
			$PaisBO 				= new PaisBO();
			$GrupoPrecioCabBO		= new GrupoPrecioCabBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PaisBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			
			//$cliente_id		= $json['cliente_id'];
			//$row			= $ClienteBO->consultar($cliente_id, \Application\Constants\ResultType::MATRIZ);
			
			$response 		= new \stdClass();
			$pais 			= null;
			$grupoprecio	= null;
			$response->cbo_tipo				= $ClienteBO->getCombo("", " ");
			$response->cbo_pais_id			= $PaisBO->getComboPais($pais, "&lt;Seleccione&gt;");
			$response->cbo_grupo_precio		= $GrupoPrecioCabBO->getComboGrupoPrecio($grupoprecio, "&lt;Seleccione&gt;");
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
			$ClienteBO 				= new ClienteBO();
			$PaisBO 				= new PaisBO();
			$GrupoPrecioCabBO		= new GrupoPrecioCabBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PaisBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$GrupoPrecioCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$cliente_id		= $json['cliente_id'];
			
			$row					= $ClienteBO->consultar($cliente_id, \Application\Constants\ResultType::MATRIZ);
	
			$response = new \stdClass();
			$response->row					= $row;
			$response->cbo_pais_id			= $PaisBO->getComboPais($row['pais_id'], "&lt;Seleccione&gt;");
			$response->cbo_grupo_precio		= $GrupoPrecioCabBO->getComboGrupoPrecio($row['grupo_precio_cab_id'], "&lt;Seleccione&gt;");
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
			$ClienteData			= new ClienteData();
			$ClienteBO 				= new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$ClienteData->setId					($json['cliente_id']);
			$ClienteData->setNombre				($json['nombre']);
			$ClienteData->setDireccion			($json['direccion']);
			$ClienteData->setPaisId				($json['pais_id']);
			$ClienteData->setCiudad				($json['ciudad']);
			$ClienteData->setTelefono1			($json['telefono1']);
			$ClienteData->setTelefono2			($json['telefono2']);
			$ClienteData->setFax1				($json['fax1']);
			$ClienteData->setFax2				($json['fax2']);
			$ClienteData->setEmail				($json['email']);
			$ClienteData->setGrupoPrecioCabId	($json['grupo_precio_cab_id']);
			$ClienteData->setEstado				($json['estado']);
	
			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$ClienteData->setUsuarioIngId($usuario_id);
					$result = $ClienteBO->ingresar($ClienteData);
					break;
						
				case 'M':
					$ClienteData->setUsuarioModId($usuario_id);
					$result = $ClienteBO->modificar($ClienteData);
					break;
						
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $ClienteBO->consultar($json['cliente_id'], \Application\Constants\ResultType::MATRIZ);
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
	
	
	
/*
 * *************************************************************************************************
 * 				FUNCION MARCACION
 * *************************************************************************************************
 */	
	
	
	public function marcacionnuevodataAction()
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
	
	
	
	public function marcaciongrabardataAction()
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
	
	
	
	
/*
 * ***********************************************************************************************************
 * 			FUNCIONES	AGENCIA CARGA
 * ***********************************************************************************************************
 */
	
	
	
	public function agenciacarganuevodataAction()
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
	
	
	
	public function agenciacargagrabardataAction()
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
	
	
	
	
/*
 * **********************************************************************************************************
 * 						FUNCIONES			USUARIO
 * **********************************************************************************************************
 */	
	
	
	
	
	public function usuarionuevodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO 				= new PerfilBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$response = new \stdClass();
			$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido("","");
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
	
	
	
	
	public function usuariograbardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
	
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$UsuarioData			= new UsuarioData();
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO 				= new PerfilBO();
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$accion						= $json['accion'];  //I, M
			$UsuarioData->setId			($json['id']);
			$UsuarioData->setNombre		($json['nombre']);
			$UsuarioData->setUsername	($json['username']);
			$UsuarioData->setPassword	($json['password']);
			$UsuarioData->setEmail		($json['email']);
			$UsuarioData->setPerfilId	($json['perfil_id']);
			$UsuarioData->setEstado		($json['estado']);
	
			$response = new \stdClass();
			switch ($accion)
			{
				case 'I':
					$UsuarioData->setUsuarioIngId($usuario_id);
					$result = $UsuarioBO->ingresar($UsuarioData);
					break;
	
				case 'M':
					$UsuarioData->setUsuarioModId($usuario_id);
					$result = $UsuarioBO->modificar($UsuarioData);
					break;
	
				default:
					$result['validacion_code'] 	= 'ERROR';
					$result['respuesta_mensaje']= 'ACCESO NO VALIDO';
					break;
			}//end switch
	
			//Se consulta el registro siempre y cuando el validacion_code sea OK
			if ($result['validacion_code']=='OK')
			{
				$row	= $UsuarioBO->consultar($json['id'], \Application\Constants\ResultType::MATRIZ);
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
				$response->cbo_perfil_id		= $PerfilBO->getComboPerfilRestringido($row['perfil_id'], " ");
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