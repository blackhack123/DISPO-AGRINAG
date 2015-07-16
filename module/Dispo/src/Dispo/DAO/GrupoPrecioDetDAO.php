<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoPrecioDetData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoPrecioDetDAO extends Conexion 
{
	private $table_name	= 'grupo_precio_det';

	/**
	 * Ingresar
	 *
	 * @param GrupoPrecioDetData $GrupoPrecioDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoPrecioDetData $GrupoPrecioDetData)
	{
		$key    = array(
				'grupo_precio_cab_id'				=> $GrupoPrecioDetData->getGrupoPrecioCabId(),
				'variedad_id'						=> $GrupoPrecioDetData->getVariedadId(),
				'grado_id'							=> $GrupoPrecioDetData->getGradoId()
				
		);
		$record = array(
				'grupo_precio_cab_id'				=> $GrupoPrecioDetData->getGrupoPrecioCabId(),
				'variedad_id'		                => $GrupoPrecioDetData->getVariedadId(),
				'grado_id'		            		=> $GrupoPrecioDetData->getGradoId(),
				'precio'                			=> $GrupoPrecioDetData->getPrecio(),
				'precio_oferta'        				=> $GrupoPrecioDetData->getPrecioOferta()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $GrupoPrecioCabId;
		return $VariedadId;
		return $GradoId;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoPrecioDetData $GrupoPrecioDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoPrecioDetData $GrupoPrecioDetData)
	{
		$key    = array(
				'grupo_precio_cab_id'		      		  => $GrupoPrecioDetData->getGrupoPrecioCabId(),
				'variedad_id'						      => $GrupoPrecioDetData->getVariedadId(),
				'grado_id'						       	  => $GrupoPrecioDetData->getGradoId(),
		);
		$record = array(
				'grupo_precio_cab_id'				=> $GrupoPrecioDetData->getGrupoPrecioCabId(),
				'variedad_id'		                => $GrupoPrecioDetData->getVariedadId(),
				'grado_id'		            		=> $GrupoPrecioDetData->getGradoId(),
				'precio'                			=> $GrupoPrecioDetData->getPrecio(),
				'precio_oferta'        				=> $GrupoPrecioDetData->getPrecioOferta()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GrupoPrecioDetData->getGrupoPrecioCabId();
		return $GrupoPrecioDetData->getVariedadId();
		return $GrupoPrecioDetData->getGradoId();
	}//end function modificar


	/**
	 * 
	 * @param int $grupo_precio_cab_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return \Dispo\Data\GrupoPrecioDetData|NULL
	 */
	public function consultar($grupo_precio_cab_id, $variedad_id, $grado_id)
	{
		$GrupoPrecioDetData 		    = new GrupoPrecioDetData();

		$sql = 	' SELECT grupo_precio_det.* '.
				' FROM grupo_precio_det '.
				' WHERE grupo_precio_cab_id = :grupo_precio_cab_id '.
				'   and variedad_id		= :variedad_id'.
				'   and grado_id		= :grado_id';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':grupo_precio_cab_id',$grupo_precio_cab_id);
		$stmt->bindValue(':variedad_id',$variedad_id);
		$stmt->bindValue(':grado_id',$grado_id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoPrecioDetData->setGrupoPrecioCabId			($row['grupo_precio_cab_id']);
			$GrupoPrecioDetData->setVariedadId					($row['variedad_id']);
			$GrupoPrecioDetData->setGradoId		    			($row['grado_id']);
			$GrupoPrecioDetData->setPrecio						($row['precio']);
			$GrupoPrecioDetData->setPrecioOferta				($row['precio_oferta']);
			return $GrupoPrecioDetData;
		}else{
			return null;
		}//end if

	}//end function consultar

	

	/**
	 * 
	 * @param string $cliente_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return \Dispo\Data\GrupoPrecioDetData|NULL
	 */
	public function consultarPorClienteIdPorVariedadIdPorGradoId($cliente_id, $variedad_id, $grado_id)
	{
		$GrupoPrecioDetData 		    = new GrupoPrecioDetData();
		
		$sql = 	' SELECT grupo_precio_det.*, variedad.nombre variedad_nombre '.
				' FROM grupo_precio_det INNER JOIN cliente '.
				"                               ON cliente.id 	= '".$cliente_id."'".
				'						INNER JOIN variedad '.
				'								ON variedad.id	= grupo_precio_det.variedad_id '. 
				' WHERE grupo_precio_det.grupo_precio_cab_id 	= cliente.grupo_precio_cab_id '.
				"   and grupo_precio_det.variedad_id		 	= '".$variedad_id."'".
				"   and grupo_precio_det.grado_id				= '".$grado_id."'";

		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		return $row;
	}//end function consultarPorClienteIdPorVariedadIdPorGradoId

}//end class
?>
