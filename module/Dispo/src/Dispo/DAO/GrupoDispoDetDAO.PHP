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
	 * Ingresar
	 *
	 * @param GrupoDispoDetData $GrupoDispoDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoDispoDetData $GrupoDispoDetData)
	{
		$key    = array(
				'grupo_dispo_cab_id'				=> $GrupoDispoDetData->getGrupoDispoCabId(),
				'variedad_id'						=> $GrupoDispoDetData->getVariedadId(),
				'grado_id'							=> $GrupoDispoDetData->getGradoId()
				
		);
		$record = array(
				'grupo_dispo_cab_id'				=> $GrupoDispoDetData->getGrupoDispoCabId(),
				'variedad_id'		                => $GrupoDispoDetData->getVariedadId(),
				'grado_id'		            		=> $GrupoDispoDetData->getGradoId(),
				'cantidad_bunch'                	=> $GrupoDispoDetData->getCantidadBunch(),
				'cantidad_bunch_disponible'         => $GrupoDispoDetData->getCantidadBunchDisponible(),
				'usuario_mod_id'                	=> $GrupoDispoDetData->getUsuarioModId(),
				'fecha_mod'                			=> $GrupoDispoDetData->getFechaMod()
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
				'variedad_id'						      => $GrupoDispoDetData->getVariedadId(),
				'grado_id'						       	  => $GrupoDispoDetData->getGradoId(),
		);
		$record = array(
				'grupo_dispo_cab_id'				=> $GrupoDispoDetData->getGrupoDispoCabId(),
				'variedad_id'		                => $GrupoDispoDetData->getVariedadId(),
				'grado_id'		            		=> $GrupoDispoDetData->getGradoId(),
				'cantidad_bunch'                	=> $GrupoDispoDetData->getCantidadBunch(),
				'cantidad_bunch_disponible'         => $GrupoDispoDetData->getCantidadBunchDisponible(),
				'usuario_mod_id'                	=> $GrupoDispoDetData->getUsuarioModId(),
				'fecha_mod'                			=> $GrupoDispoDetData->getFechaMod(),haHoraActualServidor()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
	}//end function modificar


	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return \Dispo\Data\GrupoDispoDetData|NULL
	 */
	public function consultar($grupo_dispo_cab_id, $variedad_id, $grado_id)
	{
		$GrupoDispoDetData 		    = new GrupoDispoDetData();

		$sql = 	' SELECT grupo_dispo_det.* '.
				' FROM grupo_dispo_det '.
				' WHERE grupo_dispo_cab_id 	= :grupo_dispo_cab_id '.
				'   and variedad_id			= :variedad_id'.
				'   and grado_id			= :grado_id';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_dispo_cab_id',$grupo_dispo_cab_id);
		$stmt->bindValue(':variedad_id',$variedad_id);
		$stmt->bindValue(':grado_id',$grado_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoDispoDetData->setGrupoDispoCabId 				($row['grupo_dispo_cab_id']);
			$GrupoDispoDetData->setVariedadId					($row['variedad_id']);
			$GrupoDispoDetData->setGradoId		    			($row['grado_id']);
			$GrupoDispoDetData->setCantidadBunch 				($row['cantidad_bunch']);
			$GrupoDispoDetData->setCantidadBunchDisponible 		($row['cantidad_bunch_disponible']);
			$GrupoDispoDetData->setUsuarioModId    				($row['usuario_mod_id']);
			$GrupoDispoDetData->setFechaMod						($row['fecha_mod']);

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
	public function rebajar(GrupoDispoDetData $DispoGrupoDetData, $cantidad_descontar)
	{
		if ($cantidad_descontar==0)
		{
			return 0;
		}//end if
		$sql = 	" UPDATE grupo_dispo_det ".
				" SET cantidad_bunch_disponible = cantidad_bunch_disponible - ".$cantidad_descontar.
				" WHERE grupo_dispo_cab_id	= ".$DispoGrupoDetData->getGrupoDispoCabId().
				"   and variedad_id			= '".$DispoGrupoDetData->getVariedadId()."'".
				"   and grado_id			= '".$DispoGrupoDetData->getGradoId()."'";

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
		$GrupoDispoDetData2 = $this->consultar($GrupoDispoDetData->getGrupoDispoCabId(), $GrupoDispoDetData->getVariedadId(), $GrupoDispoDetData->getGradoId());
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
	 * @param int $grupo_dispo_cab_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $cantidad_bunch_disponible
	 * @return int
	 */
	public function actualizarStock(GrupoDispoDetData $GrupoDispoDetData)
	{
		$sql = 	" UPDATE grupo_dispo_det ".
				" SET cantidad_bunch_disponible =  ".$GrupoDispoDetData->getCantidadBunchDisponible().
				" WHERE grupo_dispo_cab_id	= ".$GrupoDispoDetData->getGrupoDispoCabId().
				"   and variedad_id			= '".$GrupoDispoDetData->getVariedadId()."'".
				"   and grado_id			= '".$GrupoDispoDetData->getGradoId()."'";

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);
		return $count;
	}//end function actualizarStock
	
}//end class
?>
