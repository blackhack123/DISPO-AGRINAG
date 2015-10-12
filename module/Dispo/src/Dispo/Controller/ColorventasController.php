<?php
namespace Dispo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Dispo\BO\ColorVentasBO;



class ColorVentasController extends AbstractActionController
{

		
	public function listadodataAction()
	{
		try
		{
			$EntityManagerPlugin = $this->EntityManagerPlugin();
		
			$ColorVentasBO = new ColorVentasBO();
			$ColorVentasBO->setEntityManager($EntityManagerPlugin->getEntityManager());
		
			$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
			$SesionUsuarioPlugin->isLoginAdmin();

			$request 			= $this->getRequest();
			$page 				= $request->getQuery('page');
			$limit 				= $request->getQuery('rows');
			$sidx				= $request->getQuery('sidx',1);
			$sord 				= $request->getQuery('sord', "");
			$ColorVentasBO->setPage($page);
			$ColorVentasBO->setLimit($limit);
			$ColorVentasBO->setSidx($sidx);
			$ColorVentasBO->setSord($sord);
			$condiciones = array(
				//	"criterio_busqueda"	=> $criterio_busqueda,
				//	"estado"	=> $estado
			);
			$result = $ColorVentasBO->listado($condiciones);
			$response = new \stdClass();
			$i=0;
			foreach($result as $row){
				//$row['variedad'] = trim($row['variedad']);
				$row2['id'] 				= $row['id'];
				$row2['nombre'] 			= trim($row['nombre']);
				$row2['estado'] 			= trim($row['estado']);
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