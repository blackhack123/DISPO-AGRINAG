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
					$ParametrizarData->setId							($row['id']);
					$ParametrizarData->setDescripcion 					($row['descripcion']);
					$ParametrizarData->setTipo							($row['tipo']);
					$ParametrizarData->setValorTexto					($row['valor_texto']);
					$ParametrizarData->setValorNumerico					($row['valor_numerico']);
					$ParametrizarData->setObservacion 					($row['observacion']);
					$ParametrizarData->setFecIngreso 					($row['fec_ingreso']);
					$ParametrizarData->setFecModifica 					($row['fec_modifica']);
					$ParametrizarData->setUsuarioIngId 					($row['usuario_ing_id']);
					$ParametrizarData->setUsuarioModId 					($row['usuario_mod_id']);
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
		
	}//end consultar
	
	
	/**
	 * Modificar
	 *
	 * @param ParametrizarData $ParametrizarData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(ParametrizarData $ParametrizarData)
	{
		$key    = array(
				'id'						        => $ParametrizarData->getId()
		);
		$record = array(
				'valor_texto'		                => $ParametrizarData->getValorTexto(),
				'valor_numerico'		            => $ParametrizarData->getValorNumerico(),
				'observacion'			            => $ParametrizarData->getObservacion(),
				'fec_modifica'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                    => $ParametrizarData->getUsuarioModId(),
	
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ParametrizarData->getId();
	}//end function modificar
	
	
	
	/**
	 *
	 * @param string $id
	 * @return string|int|NULL
	 */
	public function getValorParametro($id)
	{
		$sql = 	' SELECT parametro.* '.
				' FROM parametro '.
				' WHERE id = :id ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			switch ($row['tipo'])
			{
				case 'T':
					return $row['valor_texto'];
					break;
	
				case 'N':
					return $row['valor_numerico'];
					break;
						
				default:
					return null;
			}//end switch
			return $ParametroData;
		}else{
			return null;
		}//end if
	}//end function getValorParametro
	
	
	/**
	 * 
	 * @param string $fecha
	 * @return array (nro_dias_procesa, dia_semana_procesa)
	 */
	public function getDiaDespacho($fecha)
	{
		$fecha_actual	= \Application\Classes\Fecha::convertirFechaPHPToFechaServidor($fecha);
		$hora_actual 	= strtotime(\Application\Classes\Fecha::convertirFechaToHora($fecha));
		
		$parametros['pedido_LV_hoy'] 			= $this->getValorParametro('pedido_LV_hoy');
		$parametros['pedido_LV_dia_sig'] 		= intval($this->getValorParametro('pedido_LV_dia_sig'));
		$parametros['pedido_SAB_hoy'] 			= $this->getValorParametro('pedido_SAB_hoy');
		$parametros['pedido_SAB_dia_sig'] 		= intval($this->getValorParametro('pedido_SAB_dia_sig'));
		$parametros['pedido_DOM_hoy'] 			= $this->getValorParametro('pedido_DOM_hoy');
		$parametros['pedido_DOM_dia_sig'] 		= intval($this->getValorParametro('pedido_DOM_dia_sig'));
		
		
		$fecha_procesa =  new \DateTime($fecha_actual);
		$dia_semana = $fecha_procesa->format('w');

		switch ($dia_semana)
		{
			case 0: //DOMINGO
				$campo_pedido_dia_siguiente = 'pedido_DOM_dia_sig';
				$campo_pedido_hoy			= 'pedido_DOM_hoy';
				break;
		
			case 6: //SABADO
				$campo_pedido_dia_siguiente = 'pedido_SAB_dia_sig';
				$campo_pedido_hoy			= 'pedido_SAB_hoy';
				break;
		
			default: //LUNES A VIERNES
				$campo_pedido_dia_siguiente = 'pedido_LV_dia_sig';
				$campo_pedido_hoy			= 'pedido_LV_hoy';
				break;
		}//end switch	

		$dias_procesa = $parametros[$campo_pedido_dia_siguiente];
		if ($parametros[$campo_pedido_hoy]!='00:00')
		{
			$hora_limite =  strtotime($fecha_actual.' '.$parametros[$campo_pedido_hoy]);
			if ($hora_actual <= $hora_limite)
			{
				$dias_procesa = 0; //SE LO DESPACHA HOY
			}//end if
		}//end if
				
		$fecha_procesa->add(new \DateInterval('P'.$dias_procesa.'D'));  //Se depacha a la fecha
		$fecha_procesa_format = $fecha_procesa->format('w');	

		$result['nro_dias_procesa']		= $dias_procesa;
		$result['dia_semana_procesa'] 	= $fecha_procesa_format;
		
		return $result;
	}//end function getDiaDespacho
	
	
	
}//end class