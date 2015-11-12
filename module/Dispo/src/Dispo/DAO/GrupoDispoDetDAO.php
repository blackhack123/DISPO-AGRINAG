<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoDispoDetData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoDispoDetDAO extends Conexion 
{
	private $table_name	= 'grupo_dispo_det';

	
	/**
	 *
	 * @param GrupoDispoDetData $GrupoDispoDetData
	 * @return multitype:number Ambigous <multitype:, multitype:number string , number>
	 */
	public function registrar(GrupoDispoDetData $GrupoDispoDetData)
	{
		$GrupoDispoDetData2 = $this->consultar(	$GrupoDispoDetData->getGrupoDispoCabId(), $GrupoDispoDetData->getProductoId(),
												$GrupoDispoDetData->getVariedadId(), $GrupoDispoDetData->getGradoId(),
												$GrupoDispoDetData->getTallosXBunch());
		if ($GrupoDispoDetData2)
		{
			$accion = \Application\Constants\Accion::MODIFICAR;
			$count = $this->modificar($GrupoDispoDetData);
			$result = $count;
		}else{
			$accion = \Application\Constants\Accion::INGRESAR;
			$key = $this->ingresar($GrupoDispoDetData);
			$result = $key;
		}//end if
	
		return array($accion, $result);
	}//end function registrar	
	
	
	/**
	 * Ingresar
	 *
	 * @param GrupoDispoDetData $GrupoDispoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoDispoDetData $GrupoDispoDetData)
	{
		$key    = array(
				'grupo_dispo_cab_id'				=> $GrupoDispoDetData->getGrupoDispoCabId(),
				'producto_id'						=> $GrupoDispoDetData->getProductoId(),
				'variedad_id'						=> $GrupoDispoDetData->getVariedadId(),
				'grado_id'							=> $GrupoDispoDetData->getGradoId(),
				'tallos_x_bunch'					=> $GrupoDispoDetData->getTallosXBunch()
		);
		$record = array(
				'grupo_dispo_cab_id'				=> $GrupoDispoDetData->getGrupoDispoCabId(),
				'producto_id'						=> $GrupoDispoDetData->getProductoId(),
				'variedad_id'		                => $GrupoDispoDetData->getVariedadId(),
				'grado_id'		            		=> $GrupoDispoDetData->getGradoId(),
				'tallos_x_bunch'					=> $GrupoDispoDetData->getTallosXBunch(),
				'cantidad_bunch'                	=> $GrupoDispoDetData->getCantidadBunch(),
				'cantidad_bunch_disponible'         => $GrupoDispoDetData->getCantidadBunchDisponible(),
				'usuario_ing_id'                	=> $GrupoDispoDetData->getUsuarioIngId(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $GrupoDispoDetData->getUsuarioModId(),
				'fec_modifica'             			=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $key;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoDispoDetData $GrupoDispoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoDispoDetData $GrupoDispoDetData)
	{
		$key    = array(
				'grupo_dispo_cab_id'		      		  => $GrupoDispoDetData->getGrupoDispoCabId(),
				'producto_id'							  => $GrupoDispoDetData->getProductoId(),
				'variedad_id'						      => $GrupoDispoDetData->getVariedadId(),
				'grado_id'						       	  => $GrupoDispoDetData->getGradoId(),
				'tallos_x_bunch'						  => $GrupoDispoDetData->getTallosXBunch()
		);
		$record = array(
				'cantidad_bunch'                	=> $GrupoDispoDetData->getCantidadBunch(),
				'cantidad_bunch_disponible'         => $GrupoDispoDetData->getCantidadBunchDisponible(),
				'usuario_mod_id'                	=> $GrupoDispoDetData->getUsuarioModId(),
				'fec_modifica'             			=> $GrupoDispoDetData->getFecModifica(),
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
	}//end function modificar


	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return \Dispo\Data\GrupoDispoDetData|NULL
	 */
	public function consultar($grupo_dispo_cab_id, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch)
	{
		$GrupoDispoDetData 		    = new GrupoDispoDetData();

		$sql = 	' SELECT grupo_dispo_det.* '.
				' FROM grupo_dispo_det '.
				' WHERE grupo_dispo_cab_id 	= :grupo_dispo_cab_id '.
				'   and producto_id			= :producto_id'.
				'   and variedad_id			= :variedad_id'.
				'   and grado_id			= :grado_id'.
				'   and tallos_x_bunch		= :tallos_x_bunch';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_dispo_cab_id',$grupo_dispo_cab_id);
		$stmt->bindValue(':producto_id',$producto_id);
		$stmt->bindValue(':variedad_id',$variedad_id);
		$stmt->bindValue(':grado_id',$grado_id);
		$stmt->bindValue(':tallos_x_bunch', $tallos_x_bunch);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoDispoDetData->setGrupoDispoCabId 				($row['grupo_dispo_cab_id']);
			$GrupoDispoDetData->setProductoId 					($row['producto_id']);
			$GrupoDispoDetData->setVariedadId					($row['variedad_id']);
			$GrupoDispoDetData->setGradoId		    			($row['grado_id']);
			$GrupoDispoDetData->setTallosXBunch					($row['tallos_x_bunch']);
			$GrupoDispoDetData->setCantidadBunch 				($row['cantidad_bunch']);
			$GrupoDispoDetData->setCantidadBunchDisponible 		($row['cantidad_bunch_disponible']);
			$GrupoDispoDetData->setUsuarioIngId    				($row['usuario_ing_id']);
			$GrupoDispoDetData->setFecIngreso					($row['fec_ingreso']);
			$GrupoDispoDetData->setUsuarioModId    				($row['usuario_mod_id']);
			$GrupoDispoDetData->setFecModifica					($row['fec_modifica']);

			return $GrupoDispoDetData;
		}else{
			return null;
		}//end if

	}//end function consultar


	
	/**
	 *
	 * @param GrupoDispoDetData $DispoGrupoDetData
	 * @param number $cantidad_descontar
	 */
	public function rebajar(GrupoDispoDetData $DispoGrupoDetData, $cantidad_descontar)  /*MORONITOR*/
	{
		if ($cantidad_descontar==0)
		{
			return 0;
		}//end if
		$sql = 	" UPDATE grupo_dispo_det ".
				" SET cantidad_bunch_disponible = cantidad_bunch_disponible - ".$cantidad_descontar.
				" WHERE grupo_dispo_cab_id	= ".$DispoGrupoDetData->getGrupoDispoCabId().
				"   and producto_id			= '".$DispoGrupoDetData->getProductoId()."'".
				"   and variedad_id			= '".$DispoGrupoDetData->getVariedadId()."'".
				"   and grado_id			= '".$DispoGrupoDetData->getGradoId()."'".
				"   and tallos_x_bunch		= ".$DispoGrupoDetData->getTallosXBunch();

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;
	}//end function rebajar	

	
	
	/**
	 * 
	 * @param GrupoDispoDetData $GrupoDispoDetData
	 * @return multitype:number Ambigous <multitype:, multitype:number string , number>
	 */
	public function registrarStock(GrupoDispoDetData $GrupoDispoDetData) 
	{
		$GrupoDispoDetData2 = $this->consultar(	$GrupoDispoDetData->getGrupoDispoCabId(), $GrupoDispoDetData->getProductoId(), 
												$GrupoDispoDetData->getVariedadId(), $GrupoDispoDetData->getGradoId(), 
												$GrupoDispoDetData->getTallosXBunch());
		if ($GrupoDispoDetData2)
		{
			$accion = \Application\Constants\Accion::MODIFICAR;
			$count = $this->actualizarStock($GrupoDispoDetData);
			$result = $count;
		}else{
			$accion = \Application\Constants\Accion::INGRESAR;
			$key = $this->ingresar($GrupoDispoDetData);
			$result = $key;
		}//end if

		return array($accion, $result);
	}//end function registrarStock
	
	
	
	/**
	 * 
	 * @param GrupoDispoDetData $GrupoDispoDetData
	 * @return int
	 */
	public function actualizarStock(GrupoDispoDetData $GrupoDispoDetData)
	{
		$sql = 	" UPDATE grupo_dispo_det ".
				" SET cantidad_bunch_disponible =  ".$GrupoDispoDetData->getCantidadBunchDisponible().",".
				"	  usuario_mod_id            = ".$GrupoDispoDetData->getUsuarioModId().",".
				"     fec_modifica              = '".\Application\Classes\Fecha::getFechaHoraActualServidor()."'".
				" WHERE grupo_dispo_cab_id	= ".$GrupoDispoDetData->getGrupoDispoCabId().
				"   and producto_id			= '".$GrupoDispoDetData->getProductoId()."'".
				"   and variedad_id			= '".$GrupoDispoDetData->getVariedadId()."'".
				"   and grado_id			= '".$GrupoDispoDetData->getGradoId()."'".
				"   and tallos_x_bunch		= ".$GrupoDispoDetData->getTallosXBunch();

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;
	}//end function actualizarStock


	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $clasifica_fox
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @return int
	 */
	public function actualizarCeroStock($inventario_id, $clasifica_fox, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch)
	{
		$sql = 	" UPDATE grupo_dispo_det ".
				" INNER JOIN grupo_dispo_cab ".
				" 		  ON grupo_dispo_cab.id				= grupo_dispo_det.grupo_dispo_cab_id ".
				"	     AND grupo_dispo_cab.inventario_id 	= '".$inventario_id."'".
				" INNER JOIN calidad ".
				"   	  ON calidad.id						= grupo_dispo_cab.calidad_id".
				"	     AND calidad.clasifica_fox			= '".$clasifica_fox."'".
				" SET grupo_dispo_det.cantidad_bunch 			= 0,".
				"     grupo_dispo_det.cantidad_bunch_disponible = 0".
				" WHERE grupo_dispo_det.producto_id 	= '".$producto_id."'".
				"   and grupo_dispo_det.variedad_id 	= '".$variedad_id."'";
		if (!empty($grado_id))
		{				
				$sql=$sql." and grupo_dispo_det.grado_id= '".$grado_id."'";
		}//end if
		$sql = $sql."  and grupo_dispo_det.tallos_x_bunch = ".$tallos_x_bunch;

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;		
	}//end function actualizarCeroStock

	
	
	
}//end class
?>
