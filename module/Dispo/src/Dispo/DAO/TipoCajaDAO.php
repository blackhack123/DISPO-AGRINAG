<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\TipoCajaData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TipoCajaDAO extends Conexion 
{
	private $table_name	= 'tipo_caja';

	/**
	 * Ingresar
	 *
	 * @param TipoCajaData $TipoCajaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(TipoCajaData $TipoCajaData)
	{
		$key    = array(
				'id'						        => $TipoCajaData->getId(),
		);
		$record = array(
				'id'								=> $TipoCajaData->getId(),
				'nombre'		                    => $TipoCajaData->getNombre(),
				'tipo_caja_homologada_id'	        => $TipoCajaData->getTipoCajaHomologadaId(),
				'orden'		                    	=> $TipoCajaData->getOrden()
				
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param TipoCajaData $TipoCajaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(TipoCajaData $TipoCajaData)
	{
		$key    = array(
				'id'						        => $TipoCajaData->getId(),
		);
		$record = array(
				'id'								=> $TipoCajaData->getId(),
				'nombre'		                    => $TipoCajaData->getNombre(),
				'tipo_caja_homologada_id'	        => $TipoCajaData->getTipoCajaHomologadaId(),
				'orden'		                    	=> $TipoCajaData->getOrden()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $TipoCajaData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return TipoCajaData|null
	 */	
	public function consultar($id)
	{
		$TipoCajaData 		    = new TipoCajaData();

		$sql = 	' SELECT tipo_caja.* '.
				' FROM tipo_caja '.
				' WHERE tipo_caja.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$TipoCajaData->setId						($row['id']);				
				$TipoCajaData->setNombre 					($row['nombre']);
				$TipoCajaData->setTipoCajaHomologadaId		($row['tipo_caja_homologada_id']);
				$TipoCajaData->setOrden 					($row['orden']);
				
			return $TipoCajaData;
		}else{
			return null;
		}//end if

	}//end function consultar


	/**
	 * consultarTodos
	 *
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT tipo_caja.* '.
				' FROM tipo_caja '.
				' order by nombre ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		//return new ViewModel(array(result'=>$result));
		return $result;
	}//end function consultarTodos
		
}//end class

?>