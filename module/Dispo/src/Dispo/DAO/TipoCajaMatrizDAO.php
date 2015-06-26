<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TipoCajaMatrizDAO extends Conexion 
{
	private $table_name	= 'tipo_caja_matriz';


	/**
	 * 
	 * @param unknown $inventario_id
	 * @param unknown $marcacion_sec
	 * @param unknown $variedad_id
	 * @param unknown $grado_id
	 * @param unknown $tipo_caja_id
	 * @return unknown
	 */
	public function consultaPorInventarioPorMarcacionPorVariedadPorGrado($inventario_id, $marcacion_sec, $variedad_id, $grado_id, $tipo_caja_id)
	{
		$sql =	' SELECT (CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  'MAR' ".
				"           ELSE 'MAT'".
				'       END) as tipo_caja_origen_estado, '.
				'   	(CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  tipo_caja_marcacion.id ".
				"           ELSE tipo_caja_matriz.id".
				'       END) as tipo_caja_origen_id, '.
				'   	(CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  tipo_caja_marcacion.unds_bunch ".
				"           ELSE tipo_caja_matriz.unds_bunch".
				'       END) as tipo_caja_unds_bunch, '.
				'   	(CASE '.
				"       	WHEN tipo_caja_marcacion.id IS NOT NULL THEN  tipo_caja_marcacion.tipo_caja_id ".
				"           ELSE tipo_caja_matriz.tipo_caja_id".
				'       END) as tipo_caja_id '.
				' FROM tipo_caja_matriz '.
				'            LEFT JOIN tipo_caja_marcacion '.
				'                   ON tipo_caja_marcacion.marcacion_sec = '.$marcacion_sec.
				"				   AND tipo_caja_marcacion.tipo_caja_id	 = tipo_caja_matriz.tipo_caja_id".
				'				   AND tipo_caja_marcacion.inventario_id = tipo_caja_matriz.inventario_id'.
				'				   AND tipo_caja_marcacion.variedad_id   = tipo_caja_matriz.variedad_id'.
				'				   AND tipo_caja_marcacion.grado_id    	 = tipo_caja_matriz.grado_id'.
				'             LEFT JOIN tipo_caja as tipo_caja_maestro_mat '.
				'                   ON tipo_caja_maestro_mat.id			 = tipo_caja_matriz.tipo_caja_id '.
				'             LEFT JOIN tipo_caja as tipo_caja_maestro_mar '.
				'                   ON tipo_caja_maestro_mar.id			 = tipo_caja_matriz.tipo_caja_id '.
				" WHERE tipo_caja_matriz.inventario_id 	= '".$inventario_id."'".
				"   AND tipo_caja_matriz.variedad_id	= '".$variedad_id."'".
				"   AND tipo_caja_matriz.grado_id		= '".$grado_id."'";
		if(!empty($tipo_caja_id))
		{
			$sql = $sql . " AND tipo_caja_matriz.tipo_caja_id 	= '".$tipo_caja_id."'";
		}//end if
		$sql = $sql." LIMIT 1";
				
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$reg = $stmt->fetch();
		return $reg;
	}//end function consultaPorInventarioPorMarcacionPorVariedadPorGrado
	
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return array
	 */
	public function consultarTipoCajaPorInventarioPorVariedadPorGrado($inventario_id, $variedad_id, $grado_id)
	{
		$sql = ' SELECT tipo_caja_id '.
								' FROM tipo_caja_matriz'.
								" WHERE inventario_id = '".$inventario_id."'".
								"   and variedad_id   = '".$variedad_id."'".
								"   and grado_id      = '".$grado_id."'";
			
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
	
		return $result;
	}//end class consultarPedidosEstadoComprando	
}//end class

?>