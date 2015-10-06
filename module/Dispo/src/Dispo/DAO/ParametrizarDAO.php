<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ParametrizarData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ParametrizarDAO extends Conexion 
{
	private $table_name	= 'parametro';


	
	/**
	 * consultarTodos
	 *
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT parametro.* '.
				' FROM parametro ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		//return new ViewModel(array(result'=>$result));
		return $result;
	}//end function consultarTodos
	

	
	
	public function listado($condiciones)
	{
		$sql = 	' SELECT parametro.id, parametro.descripcion, parametro.tipo, parametro.valor_texto, parametro.valor_numerico, 
					parametro.observacion, usuario.username, parametro.fec_modifica'.
				' FROM parametro '.
				' LEFT JOIN usuario '.
				' ON usuario.id = parametro.usuario_mod_id ';
	
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or id like '%".$condiciones['criterio_busqueda']."%'".
					"      or descripcion like '%".$condiciones['criterio_busqueda']."%')";
					
		}//end if

		$sql= $sql. " order by descripcion";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado
	
	
	
	
	/**
	 *
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\VariedadData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$ParametrizarData	    = new ParametrizarData();
	
				$sql = 	' SELECT parametro.* '.
						' FROM parametro '.
						' WHERE parametro.id = :id ';
	
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$VariedadData->setId							($row['id']);
					$VariedadData->setDescripcion 					($row['descripcion']);
					$VariedadData->setTipo							($row['tipo']);
					$VariedadData->setValorTexto					($row['valor_texto']);
					$VariedadData->setValorNumerico					($row['valor_numerico']);
					$VariedadData->setObservacion 					($row['observacion']);
					$VariedadData->setFecIngreso 					($row['fec_ingreso']);
					$VariedadData->setFecModifica 					($row['fec_modifica']);
					$VariedadData->setUsuarioIngId 					($row['usuario_ing_id']);
					$VariedadData->setUsuarioModId 					($row['usuario_mod_id']);
					return $ParametrizarData;
				}else{
					return null;
				}//end if
				break;
	
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT parametro. *, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM parametro LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = parametro.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = parametro.usuario_mod_id '.
						' WHERE parametro.id = :id ';
	
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch
		
	}
	
}//end class