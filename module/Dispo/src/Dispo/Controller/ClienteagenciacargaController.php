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
	
			$request 		= $this->getRequest();
			//$cliente_id    	= $request->getQuery('cliente_id', "");
			$nombre      	= $request->getQuery('nombre', "");
			$estado 		= $request->getQuery('estado', "");
			$page 			= $request->getQuery('page');
			$limit 			= $request->getQuery('rows');
			$sidx			= $request->getQuery('sidx',1);
			$sord 			= $request->getQuery('sord', "");
			$ClienteAgenciaCargaBO->setPage($page);
			$ClienteAgenciaCargaBO->setLimit($limit);
			$ClienteAgenciaCargaBO->setSidx($sidx);
			$ClienteAgenciaCargaBO->setSord($sord);
			$condiciones = array(
					//"id"			=> $id,
					"criterio_busqueda"		=> $nombre,
					"estado" 		=> $estado,
			);
			$result = $ClienteAgenciaCargaBO->listado($condiciones);
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
	
	
	
}//end controller