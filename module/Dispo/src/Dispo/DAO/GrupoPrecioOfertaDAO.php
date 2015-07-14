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
	 * Ingresar
	 *
	 * @param GrupoPrecioOfertaData $GrupoPrecioOfertaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoPrecioOfertaData $GrupoPrecioOfertaData)
	{
		$key    = array(
				'grupo_precio_cab'					=> $GrupoPrecioOfertaData->getGrupoPrecioCab(),
				'variedad_id'						=> $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'							=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'					=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'					=> $GrupoPrecioOfertaData->getGradoComboId()

		);
		$record = array(
				'grupo_precio_cab_id'				=> $GrupoPrecioOfertaData->getGrupoPrecioCabId(),
				'variedad_id'		                => $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'		            		=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'                	=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'        			=> $GrupoPrecioOfertaData->getGradoComboId(),
				'factor_combo'        				=> $GrupoPrecioOfertaData->getFactorCombo()		
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $GrupoPrecioCab;
		return $VariedadId;
		return $GradoId;
		return $VariedadComboId;
		return $GradoComboId;
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
				'grupo_precio_cab'			      		  => $GrupoPrecioOfertaData->getGrupoPrecioCab(),
				'variedad_id'						      => $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'						       	  => $GrupoPrecioOfertaData->getGradoId(),
		);
		$record = array(
				'grupo_precio_cab_id'				=> $GrupoPrecioOfertaData->getGrupoPrecioCabId(),
				'variedad_id'		                => $GrupoPrecioOfertaData->getVariedadId(),
				'grado_id'		            		=> $GrupoPrecioOfertaData->getGradoId(),
				'variedad_combo_id'                	=> $GrupoPrecioOfertaData->getVariedadComboId(),
				'grado_combo_id'        			=> $GrupoPrecioOfertaData->getGradoComboId(),
				'factor_combo'        				=> $GrupoPrecioOfertaData->getFactorCombo()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GrupoPrecioOfertaData->getGrupoPrecioCab();
		return $GrupoPrecioOfertaData->getVariedadId();
		return $GrupoPrecioOfertaData->getGradoId();
	}//end function modificar


	/**
	 * 
	 * @param int $grupo_precio_cab
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return \Dispo\Data\GrupoPrecioOfertaData|NULL
	 */
	public function consultar($grupo_precio_cab_id, $variedad_id, $grado_id) //ESTA MAL ESTA FUNCION DEBE DE CONSULTARSE POR LA CLAVE
	{
/*		$GrupoPrecioOfertaData 		    = new GrupoPrecioOfertaData();

		$sql = 	' SELECT grupo_precio_oferta.* '.
				' FROM grupo_precio_oferta '.
				' WHERE grupo_precio_cab = :grupo_precio_cab '.
				'   and variedad_id		= :variedad_id'.
				'   and grado_id		= :grado_id';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_precio_cab',$grupo_precio_cab);
		$stmt->bindValue(':variedad_id',$variedad_id);
		$stmt->bindValue(':grado_id',$grado_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoPrecioOfertaData->setGrupoPrecioCab				($row['grupo_precio_cab']);
			$GrupoPrecioOfertaData->setVariedadId					($row['variedad_id']);
			$GrupoPrecioOfertaData->setGradoId		    			($row['grado_id']);
			$GrupoPrecioOfertaData->setVariedadComboId				($row['variedad_combo_id']);
			$GrupoPrecioOfertaData->setGradoComboId					($row['grado_combo_id']);
			$GrupoPrecioOfertaData->getFactorCombo					($row['factor_combo']);
			return $GrupoPrecioOfertaData;
		}else{
			return null;
		}//end if
*/
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
		$GrupoPrecioOfertaData 		    = new GrupoPrecioOfertaData();
		
		$sql = 	' SELECT grupo_precio_oferta.*, variedad.nombre as variedad_combo_nombre '.
				' FROM grupo_precio_oferta INNER JOIN variedad '.
				'								   ON variedad.id = grupo_precio_oferta.variedad_id '.
				' WHERE grupo_precio_cab 	= :grupo_precio_cab '.
				'   and variedad_id			= :variedad_id'.
				'   and grado_id			= :grado_id';
		
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_precio_cab',$grupo_precio_cab);
		$stmt->bindValue(':variedad_id',$variedad_id);
		$stmt->bindValue(':grado_id',$grado_id);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarPorGrupoPrecioCabPorVariedadIdPorGradoId
}//end class
?>
