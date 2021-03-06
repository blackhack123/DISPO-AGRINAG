<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\TipoCajaMarcacionData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TipoCajaMarcacionDAO extends Conexion 
{
	private $table_name	= 'tipo_caja_marcacion';

	
	
	/**
	 * 
	 * @param TipoCajaMarcacionData $TipoCajaMarcacionData
	 * @return number
	 */
	public function registrar(TipoCajaMarcacionData $TipoCajaMarcacionData)
	{
		$TipoCajaMarcacionData2 = $this->consultarKeyAlterno($TipoCajaMarcacionData);
		
		if ($TipoCajaMarcacionData2)
		{
			//Elimina primero el repetido - registro actual
			if ($TipoCajaMarcacionData->getAccion()=='M')
			{
				$this->eliminar($TipoCajaMarcacionData->getId());
			}//end if

			//Actualiza el registro que encontro alterno
			$TipoCajaMarcacionData->setId($TipoCajaMarcacionData2->getId());
			$id = $this->modificar($TipoCajaMarcacionData);
			
		}
		else
		{
			if ($TipoCajaMarcacionData->getAccion()=='M')
			{
				$id = $this->modificar($TipoCajaMarcacionData);
			}else{
				$id = $this->ingresar($TipoCajaMarcacionData);
			}
		}//end if
		
		return $id;		
	}//end function registrar
	
	
	
	
	/**
	 * Ingresar
	 *
	 * @param TipoCajaMarcacionData $TipoCajaMarcacionData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(TipoCajaMarcacionData $TipoCajaMarcacionData)
	{
		$key    = array(
				'id'				=> $TipoCajaMarcacionData->getId(),
		);
		$record = array(
				'marcacion_sec'		=> $TipoCajaMarcacionData->getMarcacionSec(),
				'tipo_caja_id'	    => $TipoCajaMarcacionData->getTipoCajaId(),
				'inventario_id'		=> $TipoCajaMarcacionData->getInventarioId(),
				'variedad_id'		=> $TipoCajaMarcacionData->getVariedadId(),
				'grado_id'		    => $TipoCajaMarcacionData->getGradoId(),
				'unds_bunch'		=> $TipoCajaMarcacionData->getUndsBunch(),
				'fec_ingreso'		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'fec_modifica'		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'	=> $TipoCajaMarcacionData->getUsuarioModId(),
				'usuario_mod_id'	=> $TipoCajaMarcacionData->getUsuarioModId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param TipoCajaMarcacionData $TipoCajaMarcacionData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(TipoCajaMarcacionData $TipoCajaMarcacionData)
	{
		$key    = array(
				'id'				=> $TipoCajaMarcacionData->getId(),
		);
		$record = array(
				'marcacion_sec'		=> $TipoCajaMarcacionData->getMarcacionSec(),
				'tipo_caja_id'	    => $TipoCajaMarcacionData->getTipoCajaId(),
				'inventario_id'		=> $TipoCajaMarcacionData->getInventarioId(),
				'variedad_id'		=> $TipoCajaMarcacionData->getVariedadId(),
				'grado_id'		    => $TipoCajaMarcacionData->getGradoId(),
				'unds_bunch'	    => $TipoCajaMarcacionData->getUndsBunch(),
				'fec_modifica'		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),				
				'usuario_mod_id'	=> $TipoCajaMarcacionData->getUsuarioModId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $TipoCajaMarcacionData->getid();
	}//end function modificar

	
	
	/**
	 * 
	 * @int unknown $id
	 * @return boolean
	 */
	public function eliminar($id)
	{
		$key    = array(
			'id'	=>$id
		);
		
		$this->getEntityManager()->getConnection()->delete($this->table_name, $key);
		return true;		
	}//end function eliminar
	
	
	
	/**
	 * 
	 * @param TipoCajaMarcacionData $TipoCajaMarcacionData
	 * @return \Dispo\Data\TipoCajaMarcacionData|NULL
	 */
	public function consultarKeyAlterno($TipoCajaMarcacionData)
	{
		$TipoCajaMarcacionData2 		    = new TipoCajaMarcacionData();
		
		$sql = 	' SELECT tipo_caja_marcacion.* '.
				' FROM tipo_caja_marcacion '.
				' WHERE tipo_caja_marcacion.marcacion_sec 	= :marcacion_sec '.
				'   and tipo_caja_marcacion.tipo_caja_id 	= :tipo_caja_id '.
				'   and tipo_caja_marcacion.inventario_id 	= :inventario_id '.
				'   and tipo_caja_marcacion.variedad_id 	= :variedad_id '.
				'   and tipo_caja_marcacion.grado_id 		= :grado_id '.
				'   and tipo_caja_marcacion.id				<> :id';
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':marcacion_sec',	$TipoCajaMarcacionData->getMarcacionSec());
		$stmt->bindValue(':tipo_caja_id',	$TipoCajaMarcacionData->getTipoCajaId());
		$stmt->bindValue(':inventario_id',	$TipoCajaMarcacionData->getInventarioId());
		$stmt->bindValue(':variedad_id',	$TipoCajaMarcacionData->getVariedadId());
		$stmt->bindValue(':grado_id',		$TipoCajaMarcacionData->getGradoId());
		$stmt->bindValue(':id',				$TipoCajaMarcacionData->getId());
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
		
			$TipoCajaMarcacionData2->setId				($row['id']);
			$TipoCajaMarcacionData2->setMarcacionSec 	($row['marcacion_sec']);
			$TipoCajaMarcacionData2->setTipoCajaId		($row['tipo_caja_id']);
			$TipoCajaMarcacionData2->setInventarioId 	($row['inventario_id']);
			$TipoCajaMarcacionData2->setVariedadId 		($row['variedad_id']);
			$TipoCajaMarcacionData2->setGradoId 			($row['grado_id']);
			$TipoCajaMarcacionData2->setUndsBunch 		($row['unds_bunch']);
			$TipoCajaMarcacionData2->setFecIngreso		($row['fec_ingreso']);
			$TipoCajaMarcacionData2->setFecModifica		($row['fec_modifica']);
			$TipoCajaMarcacionData2->setUsuarioIngId		($row['usuario_ing_id']);
			$TipoCajaMarcacionData2->setUsuarioModId		($row['usuario_mod_id']);
		
			return $TipoCajaMarcacionData2;
		}else{
			return null;
		}//end if		
	}//end function consultarKeyAlterno
	
	

	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return TipoCajaMarcacionData|null
	 */	
/*	public function consultar($id)
	{
		$TipoCajaMarcacionData 		    = new TipoCajaMarcacionData();

		$sql = 	' SELECT tipo_caja_marcacion.* '.
				' FROM tipo_caja_marcacion '.
				' WHERE tipo_caja_marcacion.id = :id ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$TipoCajaMarcacionData->setId											($row['id']);				
				$TipoCajaMarcacionData->setMarcacionSec 								($row['marcacion_sec']);
				$TipoCajaMarcacionData->setTipoCajaId									($row['tipo_caja_id']);
				$TipoCajaMarcacionData->setInventarioId 								($row['inventario_id']);
				$TipoCajaMarcacionData->setVariedadId 									($row['variedad_id']);
				$TipoCajaMarcacionData->setGradoId 										($row['grado_id']);
				$TipoCajaMarcacionData->setUndsBunch 									($row['unds_bunch']);
				$TipoCajaMarcacionData->setTipoCajaId1 									($row['tipo_caja_id1']);

			return $TipoCajaMarcacionData;
		}else{
			return null;
		}//end if

	}//end function consultar
*/

	
	/**
	 * @param array $condiciones  (cliente_nombre, marcacion_nombre)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql =  " SELECT tipo_caja_marcacion.*, cliente.nombre as cliente_nombre, marcacion.cliente_id, ".
				" 	     marcacion.nombre as marcacion_nombre, variedad.nombre as variedad_nombre ".
				" FROM tipo_caja_marcacion INNER JOIN marcacion".
				"                                  ON marcacion.marcacion_sec = tipo_caja_marcacion.marcacion_sec ".
				"                          INNER JOIN cliente".
				"                                  ON cliente.id 	= marcacion.cliente_id ".
				"                          INNER JOIN variedad".
				"                                  ON variedad.id 	= tipo_caja_marcacion.variedad_id".
				" WHERE 1 = 1";
		
		if (!empty($condiciones['cliente_nombre']))
		{
			$sql = $sql." AND cliente.nombre like '%".$condiciones['cliente_nombre']."%'";
		}//end if

		if (!empty($condiciones['marcacion_nombre']))
		{
			$sql = $sql." AND marcacion.nombre like '%".$condiciones['marcacion_nombre']."%'";
		}//end if
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro

		foreach($result as &$reg)
		{
			$reg['variedad_nombre'] = trim($reg['variedad_nombre']);
		}//end foreach

		return $result;		
	}//end function listado
}//end class

?>