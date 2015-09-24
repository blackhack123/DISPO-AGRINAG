<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoPrecioOfertaData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoPrecioOfertaDAO extends Conexion 
{
	private $table_name	= 'grupo_precio_oferta';

	
	/**
	 * 
	 * @param GrupoPrecioOfertaData $GrupoPrecioOfertaData
	 * @return array
	 */
	public function registrar(GrupoPrecioOfertaData $GrupoPrecioOfertaData)
	{
		$GrupoPrecioOfertaData2 = $this->consultar($GrupoPrecioOfertaData);
		if ($GrupoPrecioOfertaData2)
		{
			$key = $this->modificar($GrupoPrecioOfertaData);
		}else{
			$key = $this->ingresar($GrupoPrecioOfertaData);
		}//end if
		return $key;
	}//end function registrar
	
	
	/**
	 * Ingresar
	 *
	 * @param GrupoPrecioOfertaData $GrupoPrecioOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoPrecioOfertaData $GrupoPrecioOfertaData)
	{
		$key    = array(
				'grupo_precio_cab_id'	=> $GrupoPrecioOfertaData->getGrupoPrecioCabId(),
				'variedad_id'			=> $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'				=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'		=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'		=> $GrupoPrecioOfertaData->getGradoComboId(),
		);
		$record = array(
				'grupo_precio_cab_id'	=> $GrupoPrecioOfertaData->getGrupoPrecioCabId(),
				'variedad_id'			=> $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'				=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'		=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'		=> $GrupoPrecioOfertaData->getGradoComboId(),
				'factor_combo'			=> $GrupoPrecioOfertaData->getFactorCombo()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		return $key;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoPrecioOfertaData $GrupoPrecioOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoPrecioOfertaData $GrupoPrecioOfertaData)
	{
		$key    = array(
				'grupo_precio_cab_id'	=> $GrupoPrecioOfertaData->getGrupoPrecioCabId(),
				'variedad_id'			=> $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'				=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'		=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'		=> $GrupoPrecioOfertaData->getGradoComboId(),
		);
		$record = array(
				'factor_combo'			=> $GrupoPrecioOfertaData->getFactorCombo()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
	}//end function modificar


	public function eliminar(GrupoPrecioOfertaData $GrupoPrecioOfertaData)
	{
		$key    = array(
				'grupo_precio_cab_id'	=> $GrupoPrecioOfertaData->getGrupoPrecioCabId(),
				'variedad_id'			=> $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'				=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'		=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'		=> $GrupoPrecioOfertaData->getGradoComboId(),
		);

		$this->getEntityManager()->getConnection()->delete($this->table_name, $key);
		return $key;
	}//end function modificar
	
	
	
	/**
	 * 
	 * @param GrupoPrecioOfertaData $GrupoPrecioOfertaData
	 * @return \Dispo\Data\GrupoPrecioOfertaData|NULL
	 */
	public function consultar(GrupoPrecioOfertaData $GrupoPrecioOfertaData)
	{
		$GrupoPrecioOfertaData2 		    = new GrupoPrecioOfertaData();

		$sql = 	' SELECT grupo_precio_oferta.* '.
				' FROM grupo_precio_oferta '.
				' WHERE grupo_precio_cab_id = :grupo_precio_cab_id '.
				'   and variedad_id			= :variedad_id'.
				'   and grado_id			= :grado_id'.
				'   and variedad_combo_id	= :variedad_combo_id'.
				'   and grado_combo_id		= :grado_combo_id';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_precio_cab_id',$GrupoPrecioOfertaData->getGrupoPrecioCabId());
		$stmt->bindValue(':variedad_id',$GrupoPrecioOfertaData->getVariedadId());
		$stmt->bindValue(':grado_id',$GrupoPrecioOfertaData->getGradoId());
		$stmt->bindValue(':variedad_combo_id',$GrupoPrecioOfertaData->getVariedadComboId());
		$stmt->bindValue(':grado_combo_id',$GrupoPrecioOfertaData->getGradoComboId());
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoPrecioOfertaData2->setGrupoPrecioCabId 	($row['grupo_precio_cab_id']);
			$GrupoPrecioOfertaData2->setVariedadId 			($row['variedad_id']);
			$GrupoPrecioOfertaData2->setGradoId 			($row['grado_id']);
			$GrupoPrecioOfertaData2->setVariedadComboId 	($row['variedad_combo_id']);
			$GrupoPrecioOfertaData2->setGradoComboId		($row['grado_combo_id']);
			$GrupoPrecioOfertaData2->setFactorCombo			($row['factor_combo']);
			$GrupoPrecioOfertaData2->setFecIngreso 			($row['fec_ingreso']);
			$GrupoPrecioOfertaData2->setFecModifica 		($row['fec_modifica']);
			$GrupoPrecioOfertaData2->setUsuarioIngId 		($row['usuario_ing_id']);
			$GrupoPrecioOfertaData2->setUsuarioModId 		($row['usuario_mod_id']);
			return $GrupoPrecioOfertaData2;
		}else{
			return null;
		}//end if

	}//end function consultar


	
	/**
	 * 
	 * @param int $grupo_precio_cab_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return array
	 */
	public function consultarPorGrupoPrecioCabPorVariedadIdPorGradoId($grupo_precio_cab_id, $variedad_id, $grado_id)
	{
		$sql = 	' SELECT grupo_precio_oferta.*, variedad.nombre as variedad_combo_nombre, grupo_precio_det.precio as precio_combo '.
				' FROM grupo_precio_oferta INNER JOIN grupo_precio_det '.
				'							       ON grupo_precio_det.grupo_precio_cab_id 		= grupo_precio_oferta.grupo_precio_cab_id '.
				'								  AND grupo_precio_det.variedad_id				= grupo_precio_oferta.variedad_combo_id  '.
				'								  AND grupo_precio_det.grado_id					= grupo_precio_oferta.grado_combo_id  '.
				'						   INNER JOIN variedad '.
				'								   ON variedad.id = grupo_precio_det.variedad_id '.
				' WHERE grupo_precio_oferta.grupo_precio_cab_id	= :grupo_precio_cab_id '.
				'   and grupo_precio_oferta.variedad_id			= :variedad_id'.
				'   and grupo_precio_oferta.grado_id			= :grado_id'.
				' ORDER BY variedad.nombre, grupo_precio_oferta.grado_combo_id ';
		
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_precio_cab_id',$grupo_precio_cab_id);
		$stmt->bindValue(':variedad_id',$variedad_id);
		$stmt->bindValue(':grado_id',$grado_id);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarPorGrupoPrecioCabPorVariedadIdPorGradoId
	
	
	/**
	 * 
	 * @param array $condiciones  (grupo_precio_cab_id, $variedad_id, $grado_id)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT grupo_precio_oferta.*, variedad.nombre as variedad_combo_nombre '.
				' FROM grupo_precio_oferta INNER JOIN variedad '.
				' 								   ON variedad.id = grupo_precio_oferta.variedad_combo_id'.
				' WHERE 1 = 1';
		
		if (!empty($condiciones['grupo_precio_cab_id']))
		{
			$sql = $sql." and grupo_precio_oferta.grupo_precio_cab_id = ".$condiciones['grupo_precio_cab_id'];
		}//end if
				
		if (!empty($condiciones['variedad_id']))
		{
			$sql = $sql." and grupo_precio_oferta.variedad_id = '".$condiciones['variedad_id']."'";
		}//end if

		if (!empty($condiciones['grado_id']))
		{
			$sql = $sql." and grupo_precio_oferta.grado_id = '".$condiciones['grado_id']."'";
		}//end if

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro

		return $result;		
	}//end function listado

}//end class
?>
