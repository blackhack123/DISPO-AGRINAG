<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GrupoDispoCabBO;
use Dispo\BO\GrupoPrecioCabBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoDispoDetData;
use Dispo\BO\InventarioBO;
use Dispo\BO\CalidadBO;
use Dispo\Data\GrupoDispoCabData;
use Dispo\BO\ColorVentasBO;
use Dispo\BO\CalidadVariedadBO;

class GrupodispoController extends AbstractActionController
{
	
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
				case 'panel-grupo-clientes':
				case 'panel-control-disponibilidad':
					$GrupoDispoCabBO 	= new GrupoDispoCabBO();
					$ColorVentasBO  	= new ColorVentasBO();
					$CalidadVariedadBO  = new CalidadVariedadBO();
					
					$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						
					$grupo_dispo_1er_elemento	= $json['grupo_dispo_1er_elemento'];
					$color_ventas_1er_elemento	= $json['color_ventas_1er_elemento'];
					$calidad_variedad_1er_elemento	= $json['calidad_variedad_1er_elemento'];
					$grupo_dispo_cab_id			= null;
					$color_ventas_id			= null;
					$calidad_variedad_id		= null;
					
					$grupo_dispo_opciones 	= $GrupoDispoCabBO->getComboGrupoDispo($grupo_dispo_cab_id, $grupo_dispo_1er_elemento);
					$color_ventas_opciones 	= $ColorVentasBO->getCombo($color_ventas_id, $color_ventas_1er_elemento);
					$calidad_variedad_opciones= $CalidadVariedadBO->getComboCalidadVariedad($calidad_variedad_id, $calidad_variedad_1er_elemento);
					
					$response = new \stdClass();
					$response->grupo_dispo_opciones		= $grupo_dispo_opciones;
					$response->color_ventas_opciones	= $color_ventas_opciones;
					$response->calidad_variedad_opciones= $calidad_variedad_opciones;
					$response->respuesta_code 			= 'OK';
					break;
					
/*				case 'panel-control-mantenimiento':
					$InventarioBO		= new InventarioBO();					
					$CalidadBO			= new CalidadBO();
					
					$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
					
					$inventario_1er_elemento	= $json['inventario_1er_elemento'];
					$inventario_id				= null;
					$calidad_1er_elemento	= $json['calidad_1er_elemento'];
					$calidad_id				= null;
					
					$inventario_opciones  	= $InventarioBO->getCombo($inventario_id, $inventario_1er_elemento);
					$calidad_opciones 	 	= $CalidadBO->getComboCalidad($calidad_id, $calidad_1er_elemento);
					
					$response = new \stdClass();
					$response->inventario_opciones		= $inventario_opciones;
					$response->calidad_opciones			= $calidad_opciones;
					$response->respuesta_code 			= 'OK';
					break;
*/					
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

			$GrupoDispoCabBO = new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());

			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 		= $this->getRequest();
			$grupo_dispo_cab_id  	= $request->getQuery('grupo_dispo_cab_id', "");
			$color_ventas_id		= $request->getQuery('color_ventas_id', "");
			$calidad_variedad_id	= $request->getQuery('calidad_variedad_id', "");
			$flag_con_valores		= $request->getQuery('flag_con_valores', 0);
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$GrupoDispoCabBO->setPage($page);
			$GrupoDispoCabBO->setLimit($limit);
			$GrupoDispoCabBO->setSidx($sidx);
			$GrupoDispoCabBO->setSord($sord);
			$condiciones = array(
					"grupo_dispo_cab_id"	=> $grupo_dispo_cab_id,
					"color_ventas_id"		=> $color_ventas_id,
					"calidad_variedad_id"	=> $calidad_variedad_id
			);
			$result = $GrupoDispoCabBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			if ($result)
			{	
				foreach($result as $row){		
					/*if ($flag_con_valores == 1)
					{
						if (($row['40']>0) || ($row['50']>0) || ($row['60']>0) || ($row['70']>0) || ($row['80']>0) || ($row['90']>0) || ($row['100']>0) || ($row['110']>0))
						{
							$response->rows[$i] = $row;
							$i++;						
						}//end if
					}else{
						$response->rows[$i] = $row;
						$i++;
					}//end if
					*/
					$response->rows[$i] = $row;
					$i++;
				}//end foreach
			}else{
				$response->rows = null;
			}
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

	
	
	function grabarstockAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
		
			$GrupoDispoCabBO 			= new GrupoDispoCabBO();
		
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$GrupoDispoDetData 		= new GrupoDispoDetData();
			$GrupoDispoDetData->setGrupoDispoCabId			($json['grupo_dispo_cab_id']);
			$GrupoDispoDetData->setProductoId 				($json['producto_id']);
			$GrupoDispoDetData->setVariedadId				($json['variedad_id']);
			$GrupoDispoDetData->setGradoId					($json['grado_id']);
			$GrupoDispoDetData->setTallosXBunch				($json['tallos_x_bunch']);
			$GrupoDispoDetData->setCantidadBunchDisponible	($json['cantidad_bunch_disponible']);
			$GrupoDispoDetData->setUsuarioIngId				($usuario_id);
			$GrupoDispoDetData->setUsuarioModId 			($usuario_id);

			$result = $GrupoDispoCabBO->registrarStock($GrupoDispoDetData);

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
			$GrupoDispoCabBO 		= new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$grupo_dispo_cab_id		= $json['grupo_dispo_cab_id'];

			$row					= $GrupoDispoCabBO->consultarCabecera($grupo_dispo_cab_id, \Application\Constants\ResultType::MATRIZ);

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
		
			$inventario_opciones  	= $InventarioBO->getCombo('USA');
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
	
	
	
	public function consultarregistrodataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoDispoCabBO 				= new GrupoDispoCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
						
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
						
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			$grupo_dispo_cab_id		= $json['grupo_dispo_cab_id'];
		
			$row					= $GrupoDispoCabBO->consultarCabecera($grupo_dispo_cab_id, \Application\Constants\ResultType::MATRIZ);

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
	
	

	function grabardataAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoDispoCabBO 		= new GrupoDispoCabBO();
			$InventarioBO			= new InventarioBO();
			$CalidadBO				= new CalidadBO();
	
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$InventarioBO->setEntityManager($EntityManagerPlugin->getEntityManager());
			$CalidadBO->setEntityManager($EntityManagerPlugin->getEntityManager());			
	
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;

			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();				
			
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$accion = ($json['accion']);
			$id		= $json['id'];
			
			$GrupoDispoCabData 		= new GrupoDispoCabData();
			$GrupoDispoCabData->setId 			($id);
			$GrupoDispoCabData->setNombre 		($json['nombre']);
			$GrupoDispoCabData->setInventarioId ($json['inventario_id']);
			$GrupoDispoCabData->setCalidadId 	($json['calidad_id']);
			$GrupoDispoCabData->setUsuarioIngId	($usuario_id);
			$GrupoDispoCabData->setUsuarioModId	($usuario_id);			
				
			switch ($accion)
			{
				case 'I': //Ingreso
					$result = $GrupoDispoCabBO->registrarPorAccion('I', $GrupoDispoCabData);
					$id	    = $result['id'];
					break;
					
				case 'M': //Modificar
					$result = $GrupoDispoCabBO->registrarPorAccion('M', $GrupoDispoCabData);
					$id	    = $result['id'];
					break;
			}//end switch
			
			$row	= $GrupoDispoCabBO->consultarCabecera($id, \Application\Constants\ResultType::MATRIZ);
	
			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
			$response->row					= $row;
			$response->inventario_opciones	= $InventarioBO->getCombo($row['inventario_id'], "<Seleccione>");
			$response->calidad_opciones 	= $CalidadBO->getComboCalidad($row['calidad_id'], "<Seleccione>");
			$response->grupo_opciones		= $GrupoDispoCabBO->getComboGrupoDispo($id);
	
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
	
	
	
	/*
	 * *****************************************************************
	 * 				FUNCIONES GRUPO DISPO
	 * *****************************************************************
	 * 
	 */
	
	
	
	/*-----------------------------------------------------------------------------*/
	public function listadogrupodisponoasignadosdataAction()
	/*-----------------------------------------------------------------------------*/
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoDispoCabBO = new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 					= $this->getRequest();
			$grupo_dispo_cab_id 		= $request->getQuery('grupo_dispo_cab_id', "");
			$page 						= $request->getQuery('page');
			$limit 						= $request->getQuery('rows');
			$sidx						= $request->getQuery('sidx',1);
			$sord 						= $request->getQuery('sord', "");
			$GrupoDispoCabBO->setPage($page);
			$GrupoDispoCabBO->setLimit($limit);
			$GrupoDispoCabBO->setSidx($sidx);
			$GrupoDispoCabBO->setSord($sord);
			//$condiciones = array(
					//"cliente_id"			=> $cliente_id,
					//"estado"				=> "A"
			//);
			$result = $GrupoDispoCabBO->listadoNoAsignadas($grupo_dispo_cab_id);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["cliente_nombre"] 	= trim($row["cliente_nombre"]);
				$row2["usuario_id"] 		= trim($row["usuario_id"]);
				$row2["usuario_nombre"] 	= trim($row["usuario_nombre"]);
	
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
	
	

	public function listadogrupodispoasignadosdataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoDispoCabBO = new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$request 					= $this->getRequest();
			$grupo_dispo_cab_id 		= $request->getQuery('grupo_dispo_cab_id', "");
			$page 						= $request->getQuery('page');
			$limit 						= $request->getQuery('rows');
			$sidx						= $request->getQuery('sidx',1);
			$sord 						= $request->getQuery('sord', "");
			$GrupoDispoCabBO->setPage($page);
			$GrupoDispoCabBO->setLimit($limit);
			$GrupoDispoCabBO->setSidx($sidx);
			$GrupoDispoCabBO->setSord($sord);
			
			$condiciones = array(
				"grupo_dispo_cab_id"	=> $grupo_dispo_cab_id,
			);
			$result = $GrupoDispoCabBO->listadoAsignadas($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				$row2["cliente_nombre"] 	= trim($row["cliente_nombre"]);
				$row2["usuario_id"] 		= trim($row["usuario_id"]);
				$row2["usuario_nombre"] 	= trim($row["usuario_nombre"]);
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
				
			$GrupoDispoCabBO = new GrupoDispoCabBO();
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginClienteVendedor();
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
			//var_dump($json); exit;
			$texto_primer_elemento	= $json['texto_primer_elemento'];
			$grupo_dispo_cab_id		= $json['grupo_dispo_cab_id'];
			$cliente_id = $SesionUsuarioPlugin->getUserClienteId();
		
			$opciones 	= $GrupoDispoCabBO->getComboGrupoDispo($grupo_dispo_cab_id, $texto_primer_elemento);
			
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
	

	function grabarporgrupoporgradoAction()
	{
		try
		{
			$SesionUsuarioPlugin 	= $this->SesionUsuarioPlugin();
			$EntityManagerPlugin 	= $this->EntityManagerPlugin();
			$GrupoDispoCabBO 		= new GrupoDispoCabBO();
		
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$respuesta = $SesionUsuarioPlugin->isLoginAdmin();
			if ($respuesta==false) return false;
		
			$usuario_id				= $SesionUsuarioPlugin->getUsuarioId();
				
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$grupo_dispo_cab_id 	= $json['grupo_dispo_cab_id'];
			$grado_id				= $json['grado_id'];
			$color_ventas_ids		= $json['color_ventas_ids'];
			$calidad_variedad_ids	= $json['calidad_variedad_ids'];
			$porcentaje				= $json['porcentaje'];
			$valor					= $json['valor'];

			//Convierte en cadena el array de color de ventas
			$cadena_color_ventas_id = '';
			$flag_1era_vez = true;
			foreach($color_ventas_ids as $clave => $valor2)
			{
				if ($flag_1era_vez == false)
				{
					$cadena_color_ventas_id = $cadena_color_ventas_id.",";
				}//end if
				$cadena_color_ventas_id = $cadena_color_ventas_id.$valor2;
				$flag_1era_vez = false;
			}//end if
			
			//Convierte en cadena el array de color de ventas
			$cadena_calidad_variedad_ids = '';
			$flag_1era_vez = true;
			foreach($calidad_variedad_ids as $clave => $valor2)
			{
				if ($flag_1era_vez == false)
				{
					$cadena_calidad_variedad_ids = $cadena_calidad_variedad_ids.",";
				}//end if
				$cadena_calidad_variedad_ids = $cadena_calidad_variedad_ids.$valor2;
				$flag_1era_vez = false;
			}//end if
					
			
			
			$result = $GrupoDispoCabBO->grabarPorGrupoPorGrado($grupo_dispo_cab_id, $grado_id, $cadena_color_ventas_id, 
																$cadena_calidad_variedad_ids, $porcentaje, $valor, $usuario_id);			

			//Retorna la informacion resultante por JSON
			$response = new \stdClass();
			$response->respuesta_code 		= 'OK';
		
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
	}//end function grabarporgrupoporgradoAction
	
}