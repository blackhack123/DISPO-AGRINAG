<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 *
 * see more documentation in Application\view\application\dialog\JqGrid.phtml
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;
use Doctrine\ORM\EntityManager;

class JqgridbuscadorController extends AbstractActionController
{

	
    public function dialogAction()		
	{
		$SesionUsuarioPlugin = $this->SesionUsuarioPlugin();
		$EntityManagerPlugin = $this->EntityManagerPlugin();

		//$AreaBO->setEntityManager($EntityManagerPlugin->getEntityManager());
	
        $viewModel = new ViewModel();

		$request 										= $this->getRequest();	
		$viewModel->title 								= $request->getQuery('title', "");			
		$viewModel->term 								= trim($request->getQuery('term', ""));
		$viewModel->grid_url							= $request->getQuery('grid_url', "");
		$viewModel->grid_source_id 						= $request->getQuery('grid_source_id', "");		
		$viewModel->grid_source_rowid					= $request->getQuery('grid_source_rowid', "");		
		$viewModel->link_columns_grid_to_dialog			= $request->getQuery('link_columns_grid_to_dialog', "");	
		$viewModel->callback_fn							= $request->getQuery('callback_fn', "");	
		$filters										= $request->getQuery('filters', "");			
		$grid_dialog_columns 							= $request->getQuery('grid_dialog_columns', "");
		$viewModel->dialog_width						= $request->getQuery('dialog_width', "0"); //Default
			

		/*Se crea la estructura del JqGrid de colNames y el ColModels*/	
		$colNames='';
		$colModels='';
		foreach($grid_dialog_columns as $column){
			$colNames  =  $colNames.'"'.$column['title'].'",';

			//var_dump($column); exit;
			$colModels =  $colModels.'{';
			foreach ($column as $clave => $valor)
			{
				switch($clave){
					case 'hidden':  //No se incluye las dobles comillas en el valor
						$colModels =  $colModels. ' "'.$clave.'":'.$valor.',';
						break;
					case 'title':
						break;
					default: //Se incluye las dobles comillas
						$colModels =  $colModels. ' "'.$clave.'":"'.$valor.'",';
						break;
				}//end switch
			}//end foread
			$colModels =  substr($colModels,0,-1).'},';
		}//end foreach
		$colNames = '['.substr($colNames, 0, -1).']';
		$colModels = '['.substr($colModels, 0, -1).']';		

		$viewModel->colNames 	= $colNames;
		$viewModel->colModels 	= $colModels;


		/*Se establece los filtros*/
		$filter_string = "";
		if (!empty($filters)){
			foreach ($filters as $clave => $valor)
			{
				 $filter_string = $filter_string.'"'.$clave.'": function() { return "'. $valor.'"; },';		
			}//end foreach			
		}//end if
		$viewModel->filters 	= $filter_string;		

		$viewModel->setTerminal(true);
		$viewModel->setTemplate('application/dialog/JqGrid.phtml');
		return $viewModel;
		
    }//end function dialogAction
	
	
}
