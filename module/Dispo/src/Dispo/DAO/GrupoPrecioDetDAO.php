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
		return $key;
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
		return $key;
	}//end function modificar

	
	/**
	 * Modificar
	 *
	 * @param GrupoPrecioDetData $GrupoPrecioDetData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function actualizarPrecio($tipo_precio, GrupoPrecioDetData $GrupoPrecioDetData)
	{
		$key    = array(
				'grupo_precio_cab_id'		      	=> $GrupoPrecioDetData->getGrupoPrecioCabId(),
				'variedad_id'						=> $GrupoPrecioDetData->getVariedadId(),
				'grado_id'						    => $GrupoPrecioDetData->getGradoId(),
		);
		
		switch($tipo_precio)
		{
			case 'NORMAL':
				$record = array(
						'precio'           	=> $GrupoPrecioDetData->getPrecio(),
				);
				
				break;
				
			case 'OFERTA':
				$record = array(
						'precio_oferta' 	=> $GrupoPrecioDetData->getPrecioOferta()
				);
				break;
		}//end switch
		
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $key;
	}//end function actualizarPrecio

	

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

	
	
	/**
	 *
	 * @param string $tipo_precio
	 * @param array $condiciones (grupo_precio_cab_id)
	 * @return array
	 */
	public function listado($tipo_precio, $condiciones)
	{
		if ($tipo_precio=='OFERTA')
		{
			$sql = 	' SELECT variedad.nombre as variedad, grupo_precio_det.variedad_id, '.
					" 		 sum(if(grupo_precio_det.grado_id=40,  grupo_precio_det.precio_oferta, 0)) as '40',".
					" 		 sum(if(grupo_precio_det.grado_id=50,  grupo_precio_det.precio_oferta, 0)) as '50',".
					" 		 sum(if(grupo_precio_det.grado_id=60,  grupo_precio_det.precio_oferta, 0)) as '60',".
					" 		 sum(if(grupo_precio_det.grado_id=70,  grupo_precio_det.precio_oferta, 0)) as '70',".
					" 		 sum(if(grupo_precio_det.grado_id=80,  grupo_precio_det.precio_oferta, 0)) as '80',".
					" 		 sum(if(grupo_precio_det.grado_id=90,  grupo_precio_det.precio_oferta, 0)) as '90',".
					" 		 sum(if(grupo_precio_det.grado_id=100, grupo_precio_det.precio_oferta, 0)) as '100',".
					" 		 sum(if(grupo_precio_det.grado_id=110, grupo_precio_det.precio_oferta, 0)) as '110'".
					' FROM grupo_precio_det INNER JOIN variedad '.
					'		                      ON variedad.id = grupo_precio_det.variedad_id '.
					' WHERE grupo_precio_det.grupo_precio_cab_id = '.$condiciones['grupo_precio_cab_id'].
					' GROUP BY variedad.nombre, variedad.id'.
					" ORDER BY variedad.nombre ";
		}else{
			$sql = 	' SELECT variedad.nombre as variedad, grupo_precio_det.variedad_id, '.
					" 		 sum(if(grupo_precio_det.grado_id=40,  grupo_precio_det.precio, 0)) as '40',".
					" 		 sum(if(grupo_precio_det.grado_id=50,  grupo_precio_det.precio, 0)) as '50',".
					" 		 sum(if(grupo_precio_det.grado_id=60,  grupo_precio_det.precio, 0)) as '60',".
					" 		 sum(if(grupo_precio_det.grado_id=70,  grupo_precio_det.precio, 0)) as '70',".
					" 		 sum(if(grupo_precio_det.grado_id=80,  grupo_precio_det.precio, 0)) as '80',".
					" 		 sum(if(grupo_precio_det.grado_id=90,  grupo_precio_det.precio, 0)) as '90',".
					" 		 sum(if(grupo_precio_det.grado_id=100, grupo_precio_det.precio, 0)) as '100',".
					" 		 sum(if(grupo_precio_det.grado_id=110, grupo_precio_det.precio, 0)) as '110'".
					' FROM grupo_precio_det INNER JOIN variedad '.
					'		                      ON variedad.id = grupo_precio_det.variedad_id '.
					' WHERE grupo_precio_det.grupo_precio_cab_id = '.$condiciones['grupo_precio_cab_id'].
					' GROUP BY variedad.nombre, variedad.id'.
					" ORDER BY variedad.nombre ";
		}//end if
			
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		foreach($result as &$reg)
		{
			$reg['variedad'] = trim($reg['variedad']);
		}
	
		return $result;
	}//end function listado

	
	
	/**
	 *
	 * @param GrupoPrecioDetData $GrupoPrecioDetData
	 * @return multitype:number Ambigous <multitype:, multitype:number string , number>
	 */
	public function registrarPrecio($tipo_precio, GrupoPrecioDetData $GrupoPrecioDetData)
	{
		$GrupoPrecioDetData2 = $this->consultar($GrupoPrecioDetData->getGrupoPrecioCabId(), $GrupoPrecioDetData->getVariedadId(), $GrupoPrecioDetData->getGradoId());
		if ($GrupoPrecioDetData2)
		{
			$accion = \Application\Constants\Accion::MODIFICAR;
			$count = $this->actualizarPrecio($tipo_precio, $GrupoPrecioDetData);
			$result = $count;
		}else{
			$accion = \Application\Constants\Accion::INGRESAR;
			switch ($tipo_precio){
				case 'OFERTA':
					$GrupoPrecioDetData->setPrecio(0);
					break;
				case 'NORMAL':
					$GrupoPrecioDetData->setPrecioOferta(0);
					break;
					
				default:
					$GrupoPrecioDetData->setPrecioOferta(0);
					$GrupoPrecioDetData->setPrecio(0);
					break;
			}//end switch
			$key = $this->ingresar($GrupoPrecioDetData);
			$result = $key;
		}//end if
	
		return array($accion, $result);
	}//end function registrarPrecio	
	
	
	
}//end class
?>
