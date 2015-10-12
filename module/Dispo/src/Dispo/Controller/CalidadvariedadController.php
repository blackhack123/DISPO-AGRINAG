<?php
namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\CalidadVariedadBO;



class CalidadVariedadController extends AbstractActionController
{

		
	public function listadodataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
		
			$CalidadVariedadBO = new CalidadVariedadBO();
			$CalidadVariedadBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 			= $this->getRequest();
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			$CalidadVariedadBO->setPage($page);
			$CalidadVariedadBO->setLimit($limit);
			$CalidadVariedadBO->setSidx($sidx);
			$CalidadVariedadBO->setSord($sord);
			$condiciones = array(
				//	"criterio_busqueda"	=> $criterio_busqueda,
				//	"estado"	=> $estado
			);
			$result = $CalidadVariedadBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				//$row['variedad'] = trim($row['variedad']);
				$row2['id'] 				= $row['id'];
				$row2['nombre'] 			= trim($row['nombre']);
				$response->rows[$i] = $row2;
				$i++;
			}//end foreach
			$tot_reg = $i;
			//$response->total 	= ceil($tot_reg/$limit);
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