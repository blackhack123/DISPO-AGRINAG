<?php

namespace Seguridad\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Seguridad\BO\UsuarioBO;
use Seguridad\Data\UsuarioData;
use Application\Classes\CorreoElectronico;

class UsuarioController extends AbstractActionController
{
	
	public function listadodataAction()
	{	
/*		try
		{		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoAccion($this->opcion_app, \Application\Constants\Accion::CONSULTAR);

			$UsuarioBO = new UsuarioBO();
			$UsuarioBO->setEntityManager($this->getEntityManager());

			$request 		= $this->getRequest();
			$nombre_usuario = $request->getQuery('nombre_usuario', "");
			$estado 		= $request->getQuery('estado', "");		
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");

			$UsuarioBO->setPage($page);
			$UsuarioBO->setLimit($limit);
			$UsuarioBO->setSidx($sidx);
			$UsuarioBO->setSord($sord);

			$condiciones = array("nombre_usuario"	=> $nombre_usuario,
								 "estado" 			=> $estado,
								);	
			$result = $UsuarioBO->listado(1, $condiciones);

			$response = new \stdClass();

			$i=0;
			foreach($result as $row){
				$response->rows[$i] = $row;
				$i++;
			}

			$tot_reg = $UsuarioBO->listado(2, $condiciones);	
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
*/	}//end function listadodataAction
	

		
	public function listadoAction()
	{
/*		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoOpcion($this->opcion_app);
			
			$viewModel = new ViewModel();
			$request   = $this->getRequest();
				
			$viewModel->contenedor_opcion = $request->getQuery('contenedor_opcion', "");
			$viewModel->permisos	  		= $SesionUsuarioPlugin->getArrayPermisoAccion(); //Obtiene todos los permisos del usuario
			$viewModel->habilitarAcciones			= array(\Application\Constants\Accion::INGRESAR 	=> true,
															\Application\Constants\Accion::MODIFICAR	=> true,
															\Application\Constants\Accion::ELIMINAR		=> true
															);
													
			$viewModel->setTerminal(true);		
			return $viewModel;
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}		
*/	}//end function listadoAction
	
	
	public function consultarAction()
	{
/*		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$data = $SesionUsuarioPlugin->getPermisoAccion($this->opcion_app, \Application\Constants\Accion::CONSULTAR);
	
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO  				= new PerfilBO();
			$GrupoEmpresarialBO  	= new GrupoEmpresarialBO();
			
			$UsuarioBO->setEntityManager($this->getEntityManager());
			$PerfilBO->setEntityManager($this->getEntityManager());		
			$GrupoEmpresarialBO->setEntityManager($this->getEntityManager());		
		
			$viewModel 			= new ViewModel();		
			$request 			= $this->getRequest();
			$id					= $this->params('id', "");
			$contenedor_opcion 	= $request->getQuery('contenedor_opcion', "");
			
			list($UsuarioData, $PersonaData)	= $UsuarioBO->consultar($id);
			$cboEstado 	 		= utf8_encode($UsuarioBO->getCboEstado($UsuarioData->getEstado()));
			$cboPerfil 	 		= utf8_encode($PerfilBO->getCombo($UsuarioData->getPerfilId()));
			$cboGrupoEmpresarial= utf8_encode($GrupoEmpresarialBO->getCombo($UsuarioData->getGrupoEmpresarialId()));
	
			$viewModel->UsuarioData 	  	= $UsuarioData;
			$viewModel->PersonaData 	  	= $PersonaData;			
			$viewModel->cboPerfil 		  	= $cboPerfil;
			$viewModel->cboGrupoEmpresarial = $cboGrupoEmpresarial;
			$viewModel->cboEstado  		  	= $cboEstado;
			$viewModel->contenedor_opcion 	= $contenedor_opcion;
			//La variable respuesta nos sirve para identificar si al momento de realizar una consulta de acuerdo a una redireccion
			//de la accion grabar informacion (Ingreso ó Modificaciòn) lo ha realizado correctamente sin que se presente una Excepcion o Error
			$viewModel->respuesta  		  	= 'OK';  
			$viewModel->permisos	  		= $SesionUsuarioPlugin->getArrayPermisoAccion(); //all los permisos
			$viewModel->habilitarAcciones			= array(\Application\Constants\Accion::VIRTUAL_REGRESAR 	=> true,
															\Application\Constants\Accion::INGRESAR 			=> true,
															\Application\Constants\Accion::ELIMINAR 			=> true,															
															\Application\Constants\Accion::VIRTUAL_GRABAR		=> true,															
															);
			
				
			$viewModel->setTerminal(true);
			$viewModel->setTemplate('Seguridad/usuario/mantenimiento.phtml');		
			return $viewModel;	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}//end try
*/	}//end function consultarAction

	/*-----------------------------------------------------------------------------*/
    public function indexAction(){
	/*-----------------------------------------------------------------------------*/
/*		return $this->listadoAction();
*/    }//end function indexAction


	/*-----------------------------------------------------------------------------*/		
	public function nuevoAction(){
	/*-----------------------------------------------------------------------------*/	
/*		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoOpcion(1);
		
			$UsuarioBO 				= new UsuarioBO();
			$PerfilBO  				= new PerfilBO();
			$GrupoEmpresarialBO  	= new GrupoEmpresarialBO();		
			$UsuarioData 			= new UsuarioData();
			$PersonaData			= new PersonaData();
	
			$UsuarioBO->setEntityManager($this->getEntityManager());
			$PerfilBO->setEntityManager($this->getEntityManager());
			$GrupoEmpresarialBO->setEntityManager($this->getEntityManager());

			$viewModel 	= new ViewModel();
			$request 	= $this->getRequest();
			$contenedor_opcion = $request->getQuery('contenedor_opcion', "");

			$cboEstado 							= utf8_encode($UsuarioBO->getCboEstado(''));
			$cboPerfil 	 						= utf8_encode($PerfilBO->getCombo(''));
			$cboGrupoEmpresarial				= utf8_encode($GrupoEmpresarialBO->getCombo(''));

			$viewModel->UsuarioData 	  		= $UsuarioData;		
			$viewModel->PersonaData 	  		= $PersonaData;						
			$viewModel->cboPerfil 		  		= $cboPerfil;
			$viewModel->cboGrupoEmpresarial 	= $cboGrupoEmpresarial;		
			$viewModel->cboEstado  		  		= $cboEstado;
			$viewModel->contenedor_opcion	 	= $contenedor_opcion;
			$viewModel->permisos	  			= $SesionUsuarioPlugin->getArrayPermisoAccion(); //all los permisos
			$viewModel->habilitarAcciones			= array(\Application\Constants\Accion::VIRTUAL_REGRESAR 	=> true,
															\Application\Constants\Accion::INGRESAR 			=> true,
															\Application\Constants\Accion::VIRTUAL_GRABAR		=> true,															
															);

	
			$viewModel->setTerminal(true);
			$viewModel->setTemplate('Seguridad/usuario/mantenimiento.phtml');		
			return $viewModel;	
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}	
*/	}//end function nuevoAction
	
	
	/*-----------------------------------------------------------------------------*/
	private function grabar($opcion)
	/*-----------------------------------------------------------------------------*/
	{
/*		$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
		$id_usuario = $SesionUsuarioPlugin->getUsuarioId();
		$resultado = null;
		$request 	= $this->getRequest();
	
		$request 					= $this->getRequest();
		$body 						= $this->getRequest()->getContent();
		$json 						= json_decode($body, true);
		$formData 					= $json['formData'];
		$gridEmpresaSucursalData	= $json['gridEmpresaSucursalData'];
		$gridExtensionData			= $json['gridExtensionData'];
		$UsuarioBO = new UsuarioBO();
		$UsuarioBO->setEntityManager($this->getEntityManager());
		$contenedor_opcion = $request->getQuery('contenedor_opcion', "");

		$UsuarioData = new UsuarioData();
		$UsuarioData->setId						($formData['codigo_1']);
		$UsuarioData->setPersonaId				($formData['persona_id_1']);
		$UsuarioData->setPerfilId				($formData['perfil_1']);
		$UsuarioData->setGrupoEmpresarialId		($formData['grupo_empresarial_1']);
		$UsuarioData->setNombreUsuario			($formData['usuario_1']);
		
		if(isset($formData['cambio_clave_1']))
		{
			$UsuarioData->setEstadoCambioClave($formData['cambio_clave_1']);
		}
		else
		{
			$UsuarioData->setEstadoCambioClave(0);
		}
		
		if(isset($formData['generar_clave_1']))
		{
			$isGenerarClave	= $formData['generar_clave_1'];
		}
		else
		{
			$isGenerarClave = 0;
		}
		
		$UsuarioData->setNroIntentos			($formData['nro_intentos_1']);
		$UsuarioData->setUsuarioIngId			($id_usuario);
		$UsuarioData->setUsuarioModId			($id_usuario);
		$UsuarioData->setEstado					($formData['estado_1']);
		$UsuarioData->setXmlXmlEmpresaSurcursal($gridEmpresaSucursalData);
		$UsuarioData->setXmlExtension($gridExtensionData);

		switch($opcion){
			case 'ingresar':
				$resultado = $UsuarioBO->ingresar($UsuarioData, $isGenerarClave);	
				break;					
			case 'modificar':
				$resultado = $UsuarioBO->modificar($UsuarioData, $isGenerarClave);	
				break;
		}//end switch

		if($isGenerarClave==1)
		{
			//Enviar Clave por correo electronico
			$emailDestino = $resultado['correoUsuario'];
			$emailTitulo = "Generacion de clave temporal para ingreso al Sistema ERP e-SIG";
			$nombresUsuario = $resultado['nombresUsuario'];
			$claveGenerada = $resultado['variableAleatoria'];
			$cfgArchivos = $this->getServiceLocator()->get('Config');
			$ruta_archivo	= $cfgArchivos['rutaArchivo']['formato_correo_cambio_clave'] . "/cuerpoCorreoCambioClaveTemporal.txt";
			$formatoCuerpoEmail = file_get_contents($ruta_archivo);
			$buscar  = array('vartitulo', 'varnombre', 'varusuario', 'varclave');
			$reemplazar = array($emailTitulo, $nombresUsuario, $UsuarioData->getNombreUsuario(), $claveGenerada);
			$emailCuerpo = str_replace($buscar, $reemplazar, $formatoCuerpoEmail);
			$objEmail = new CorreoElectronico();
			$resultadoEnvio = $objEmail->setEmail($emailDestino, $emailTitulo, $emailCuerpo);
			if($resultadoEnvio!= "OK")
			{
				$response->setStatusCode(500);
				$response->setContent($resultadoEnvio);
			}
		}
		
		$id = $resultado['idTran'];
		
		$this->plugin('redirect')->toRoute('seguridad-usuario', 
											[	'action'=>'consultar',
												'id'=>$id
											], 
											[	'query'=> ['contenedor_opcion'=>$contenedor_opcion]
											]
										 );
*/	}//end function grabar

	/*-----------------------------------------------------------------------------*/
	public function modificarAction()
	/*-----------------------------------------------------------------------------*/
	{
/*		try
		{		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoAccion($this->opcion_app, \Application\Constants\Accion::MODIFICAR);
			
			$request 	= $this->getRequest();
		
			if ($request->isPost()) {
				$this->grabar('modificar');			
			}//end if
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}	
*/	}//end function modificarAction


	/*-----------------------------------------------------------------------------*/
    public function ingresarAction()
	/*-----------------------------------------------------------------------------*/
	{
/*		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoAccion($this->opcion_app, \Application\Constants\Accion::INGRESAR);
		
			$request = $this->getRequest();
	
			if ($request->isPost()) {
				$this->grabar('ingresar');			
			}//end if
		}catch (\Exception $e) {
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}			
*/    }//end function insertarAction


	/*-----------------------------------------------------------------------------*/
	public function eliminarmasivoAction()
	/*-----------------------------------------------------------------------------*/
	{
/*		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoAccion($this->opcion_app, \Application\Constants\Accion::ELIMINAR);
		
			$request 	= $this->getRequest();
		
			if ($request->isPost()) {
				$request 	= $this->getRequest();
			
				$UsuarioBO = new UsuarioBO();
				$UsuarioBO->setEntityManager($this->getEntityManager());
				$request 	= $this->getRequest();		

				$arr_ids = $this->params()->fromPost('ids');
								
				foreach($arr_ids as $reg){
					$UsuarioData = new UsuarioData();
					$UsuarioData->setId($reg);

					$arr_UsuarioData[] = $UsuarioData;
				}//end foreach
				
				$respuesta = $UsuarioBO->eliminarMasivo($arr_UsuarioData);	

				$json = new JsonModel(array('cod_msg'=>'OK'));
				return $json;											 
			}//end if
		}catch (\Exception $e){
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}			
*/	}//end function eliminarmasivoAction

	/*-----------------------------------------------------------------------------*/
	public function eliminarAction()
	/*-----------------------------------------------------------------------------*/
	{
/*		try
		{
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->getPermisoAccion($this->opcion_app, \Application\Constants\Accion::ELIMINAR);
		
			$request 	= $this->getRequest();
		
			if ($request->isPost()) {
				$request 	= $this->getRequest();
			
				$UsuarioBO = new UsuarioBO();
				$UsuarioBO->setEntityManager($this->getEntityManager());
				$request 	= $this->getRequest();		
				
				$contenedor_opcion = $request->getQuery('contenedor_opcion', "");

				$UsuarioData = new UsuarioData();
				$UsuarioData->setId					($this->params()->fromPost('codigo_1'));
				$resultado = $UsuarioBO->eliminar($UsuarioData);	
				$id = $resultado['idTran'];
				//Si tiene asignado un contenedor_opcion se redireccion el ruteo para realizar la consulta del registro
				//en caso de no tener valor se retornará un JSON como OK
				if ($contenedor_opcion!=''){
					$this->plugin('redirect')->toRoute('seguridad-usuario', 
													 ['action'=>'consultar',
													  'id'=>$id
													 ], 
													 ['query'=> ['contenedor_opcion'=>$contenedor_opcion]]
													 );
				}else{
					$json = new JsonModel(array('cod_msg'=>'OK'));
					return $json;
				}//end if
			}//end if
		}catch (\Exception $e){
			$excepcion_msg =  utf8_encode($this->ExcepcionPlugin()->getMessageFormat($e));
			$response = $this->getResponse();
			$response->setStatusCode(500);
			$response->setContent($excepcion_msg);
			return $response;			
		}			
*/	}//end function eliminarAction


}
