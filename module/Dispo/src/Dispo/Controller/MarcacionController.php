<?php

namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
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
	

}//end controller