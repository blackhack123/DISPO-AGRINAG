<?php
namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ClienteBO;
use Seguridad\BO\UsuarioBO;
use Seguridad\DATA\UsuarioDATA;
use Dispo\BO\PaisBO;
use Dispo\BO\EstadosBO;
use Dispo\Data\ClienteData;
use Dispo\BO\GrupoDispoCabBO;


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
			$UsuarioBO 				= new UsuarioBO();
			$PaisBO 				= new PaisBO();
			$EstadosBO 				= new EstadosBO();
			//$GrupoDispoCabBO		= new GrupoDispoCabBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PaisBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$EstadosBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			//$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			
			//$cliente_id		= $json['cliente_id'];
			//$row			= $ClienteBO->consultar($cliente_id, \Application\Constants\ResultType::MATRIZ);
			
			$response 				= new \stdClass();
			$pais 					= null;
			$estados 				= null;
			$grupodispo				= null;
			$usuario_vendedor_id	=null;
			$response->cbo_tipo						= $ClienteBO->getCombo("", " ");
			$response->cbo_pais_id					= $PaisBO->getComboPais($pais, "&lt;Seleccione&gt;");
			$response->cbo_usuario_vendedor_id		= $UsuarioBO->getComboVendedoresAdmin($usuario_vendedor_id, "&lt;Seleccione&gt;");
			$response->cbo_estado_id				= $EstadosBO->getComboEstados($estados, "&lt;Seleccione&gt;");
			//$response->cbo_grupo_dispo				= $GrupoDispoCabBO->getComboGrupoDispo($grupodispo, "&lt;Seleccione&gt;");
			$response->cbo_estado					= \Application\Classes\ComboGeneral::getComboEstado("","");
			$response->cbo_formato_estado_cta		= \Application\Classes\ComboGeneral::getComboFormatoEnvio("","");
			$response->respuesta_code 				= 'OK';
			$response->respuesta_mensaje			= '';
	
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
			$EstadosBO 				= new EstadosBO();
			$PaisBO 				= new PaisBO();
			$UsuarioBO 				= new UsuarioBO();
			//$GrupoDispoCabBO		= new GrupoDispoCabBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$PaisBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$UsuarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$EstadosBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			//$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$cliente_id		= $json['cliente_id'];
			
			$row					= $ClienteBO->consultar($cliente_id, \Application\Constants\ResultType::MATRIZ);
	
			$response = new \stdClass();
			$response->row						= $row;
			$response->cbo_pais_id				= $PaisBO->getComboPais($row['pais_id'], "&lt;Seleccione&gt;");
			//$response->cbo_usuario_vendedor_id	= $UsuarioBO->getComboTodosVendedores($row['usuario_vendedor_id'], "&lt;Seleccione&gt;");
			$response->cbo_usuario_vendedor_id	= $UsuarioBO->getComboVendedoresAdmin($row['usuario_vendedor_id'], "&lt;Seleccione&gt;");
			//$response->cbo_grupo_dispo			= $GrupoDispoCabBO->getComboGrupoDispo($row['grupo_precio_cab_id'], "&lt;Seleccione&gt;");
			$response->cbo_estado				= \Application\Classes\ComboGeneral::getComboEstado($row['estado'],"");
			$response->cbo_estado_id			= $EstadosBO->getComboEstados($row['estados_id'], "&lt;Seleccione&gt;");
			$response->cbo_formato_estado_cta	= \Application\Classes\ComboGeneral::getComboFormatoEnvio($row['formato_estado_cta'],"");
			$response->respuesta_code 			= 'OK';
			$response->respuesta_mensaje		= '';
	
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
			$ClienteData->setAbreviatura		($json['abreviatura']);
			//$ClienteData->setGrupoPrecioCabId	($json['grupo_precio_cab_id']);
			$ClienteData->setDireccion			($json['direccion']);
			$ClienteData->setCiudad				($json['ciudad']);
			$ClienteData->setEstadosId			($json['estados_id']);
			$ClienteData->setEstadoNombre		($json['estado_nombre']);
			$ClienteData->setPaisId				($json['pais_id']);
			$ClienteData->setCodigoPostal		($json['codigo_postal']);
			$ClienteData->setComprador			($json['comprador']);
			$ClienteData->setTelefono1			($json['telefono1']);
			$ClienteData->setTelefono1Ext		($json['telefono1_ext']);
			$ClienteData->setTelefono2			($json['telefono2']);
			$ClienteData->setTelefono2Ext		($json['telefono2_ext']);
			$ClienteData->setFax1				($json['fax1']);
			$ClienteData->setFax1Ext			($json['fax1_ext']);
			$ClienteData->setFax2				($json['fax2']);
			$ClienteData->setFax2Ext			($json['fax2_ext']);
			$ClienteData->setUsuarioVendedorId	($json['usuario_vendedor_id']);
			$ClienteData->setTcLimiteCredito	($json['tc_limite_credito']);
			$ClienteData->setTcInteres			($json['tc_interes']);
			$ClienteData->setEstCreditoSuspendido($json['est_credito_suspendido']);
			$ClienteData->setCreditoSuspendidoRazon($json['credito_suspendido_razon']);
			//$ClienteData->setEmail				($json['email']);
			$ClienteData->setContacto			($json['contacto']);
			$ClienteData->setClienteFacturaId	($json['cliente_factura_id']);
			$ClienteData->setTelefonoFact1		($json['telefono_fact1']);
			$ClienteData->setTelefonoFact1Ext	($json['telefono_fact1_ext']);
			$ClienteData->setTelefonoFact2		($json['telefono_fact2']);
			$ClienteData->setTelefonoFact2Ext	($json['telefono_fact2_ext']);
			$ClienteData->setFaxFact1			($json['fax_fact1']);
			$ClienteData->setFaxFact1Ext		($json['fax_fact1_ext']);
			$ClienteData->setFaxFact2			($json['fax_fact2']);
			$ClienteData->setFaxFact2Ext		($json['fax_fact2_ext']);
			$ClienteData->setEmailFactura		($json['email_factura']);
			$ClienteData->setPaisFUE			($json['pais_fue']);
			$ClienteData->setFacturacionSRI		($json['facturacion_sri']);
			$ClienteData->setPorcIva			($json['porc_iva']);
			$ClienteData->setEstado				($json['estado']);
			$ClienteData->setIncobrable			($json['incobrable']);
			$ClienteData->setClienteEspecial	($json['cliente_especial']);
			$ClienteData->setEnviaEstadoCta		($json['envia_estadocta']);
			$ClienteData->setFormatoEstadoCta	($json['formato_estado_cta']);
			$ClienteData->setTipoEnvioEstCta	($json['tipo_envio_estcta']);
			$ClienteData->setDiaSemana			($json['dia_semana']);
			$ClienteData->setDiaCalFecha1		($json['diacal_fecha1']);
			$ClienteData->setDiaCalFecha2		($json['diacal_fecha2']);
			$ClienteData->setInmediato			($json['inmediato']);
			
			
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
	
	

		
	public function listadodataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
		
			$ClienteBO = new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 			= $this->getRequest();
			$criterio_busqueda  = $request->getQuery('criterio_busqueda', "");
			$estado  			= $request->getQuery('estado', "");
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			$ClienteBO->setPage($page);
			$ClienteBO->setLimit($limit);
			$ClienteBO->setSidx($sidx);
			$ClienteBO->setSord($sord);
			$condiciones = array(
					"criterio_busqueda"	=> $criterio_busqueda,
					"estado"	=> $estado
			);
			$result = $ClienteBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				//$row['variedad'] = trim($row['variedad']);
				$row2['id'] 				= $row['id'];
				$row2['nombre'] 			= trim($row['nombre']);
				$row2['direccion'] 			= trim($row['direccion']);
				$row2['pais_nombre']		= trim($row['pais_nombre']);
				$row2['sincronizado'] 		= trim($row['sincronizado']);
				$row2['fec_sincronizado'] 	= trim($row['fec_sincronizado']);
				$row2['estado'] 			= trim($row['estado']);
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
	

	
	public function listadodialogdataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
		
			$ClienteBO = new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
		
			$request 			= $this->getRequest();
			$criterio_busqueda  = $request->getQuery('criterio_busqueda', "");
			$estado  			= $request->getQuery('estado', "");
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			$ClienteBO->setPage($page);
			$ClienteBO->setLimit($limit);
			$ClienteBO->setSidx($sidx);
			$ClienteBO->setSord($sord);
			$condiciones = array(
					"criterio_busqueda"	=> $request->getQuery('term', ""),
					//"estado"			=> $request->getQuery('estado', null), 
			);
			$result = $ClienteBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				//$row['variedad'] = trim($row['variedad']);
				$row2['id'] 			= $row['id'];
				$row2['nombre'] 		= trim($row['nombre']);
				$response->rows[$i] 	= $row2;
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
	}//end function listadodialogdataAction
	
	
	
	
	public function consultarclientefacturaAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
		
			$ClienteBO = new ClienteBO();
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
		
			$request 			= 	$this->    getRequest();
			$cliente_factura_id	= 	$request-> getQuery('term');
					
			$condiciones = array(
					"cliente_factura_id"	=> $cliente_factura_id,
			);
			$result = $ClienteBO->ConsultarClienteFactura($condiciones);
			//$response = new \stdClass();
			$i=0;
			$result2 = null;
			foreach($result as $row){
				//$row['variedad'] = trim($row['variedad']);
				$row2['id'] 			= $row['id'];
				$row2['value'] 			= trim($row['nombre']);
				//$row2['nombre'] 		= trim($row['nombre']);
				$result2[] 				= $row2;
			}//end foreach
			$data = new JsonModel($result2);
			return $data;
			
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
		
		
	}//end function consultarClienteFacturaAction 
	
	
	function exportarexcelAction()
	{
		try
		{
			$viewModel 			= new ViewModel();
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$ClienteBO 		= new ClienteBO();
	
				
			$ClienteBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 				= $this->getRequest();
			$criterio_busqueda 	 	= $request->getQuery('criterio_busqueda', "");
			$busqueda_estado  		= $request->getQuery('busqueda_estado', "");
	
			$condiciones = array(
						
					"criterio_busqueda"		=> $criterio_busqueda,
					"busqueda_estado"		=> $busqueda_estado
					
			);
			$result = $ClienteBO->generarExcel($condiciones);
	
			exit;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;
		}
	}//end function exportarexcel2Action
	
	
	
}//end controller