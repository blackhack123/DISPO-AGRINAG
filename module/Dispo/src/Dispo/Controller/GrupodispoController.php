<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use	Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\GrupoDispoCabBO;
use Zend\Http\Client;
use Zend\Http\Request;
use Dispo\Data\GrupoDispoDetData;

class GrupodispoController extends AbstractActionController
{
	
	public function initcontrolsAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
	
			$GrupoDispoCabBO 	= new GrupoDispoCabBO();
	
			$GrupoDispoCabBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();
	
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
	
			$opcion						= $json['opcion'];
			$grupo_dispo_1er_elemento	= $json['grupo_dispo_1er_elemento'];
			$grupo_dispo_cab_id		= null;
	
			$grupo_dispo_opciones 	= $GrupoDispoCabBO->getComboGrupoDispo($grupo_dispo_cab_id, $grupo_dispo_1er_elemento);
	
			$response = new \stdClass();
			$response->grupo_dispo_opciones		= $grupo_dispo_opciones;
			$response->respuesta_code 			= 'OK';
	
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
			);
			$result = $GrupoDispoCabBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
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
		
			$body = $this->getRequest()->getContent();
			$json = json_decode($body, true);
		
			$GrupoDispoDetData 		= new GrupoDispoDetData();
			$GrupoDispoDetData->setGrupoDispoCabId			($json['grupo_dispo_cab_id']);
			$GrupoDispoDetData->setVariedadId				($json['variedad_id']);
			$GrupoDispoDetData->setGradoId					($json['grado_id']);
			$GrupoDispoDetData->setCantidadBunchDisponible	($json['cantidad_bunch_disponible']);
			
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
	
}