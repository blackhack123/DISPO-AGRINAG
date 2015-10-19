<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\VariedadData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class VariedadDAO extends Conexion 
{
	private $table_name	= 'variedad';

	/**
	 * Ingresar
	 *
	 * @param VariedadData $VariedadData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(VariedadData $VariedadData)
	{
		$key    = array(
				'id'						        => $VariedadData->getId()
		);
		$record = array(
				'id'								=> $VariedadData->getId(),
				'nombre'		                    => $VariedadData->getNombre(),
				'nombre_tecnico'		            => $VariedadData->getNombreTecnico(),
				'calidad_variedad_id'	            => $VariedadData->getCalidadVariedadId(),
				'color'		   		                => $VariedadData->getColor(),
				'color2'		   		            => $VariedadData->getColor2(),
				'grupo_color_id'	                => $VariedadData->getGrupoColorId(),
				'colorbase'	                   		=> $VariedadData->getColorBase(),
				'solido'	                   		=> $VariedadData->getSolido(),
				'es_real'		                    => $VariedadData->getEsReal(),
				'est_producto_especial'	            => $VariedadData->getEstProductoEspecial(),
				'mensaje'	        			    => $VariedadData->getMensaje(),
				'cultivada'	    			        => $VariedadData->getCultivada(),
				'ciclo_prod'	                    => $VariedadData->getCicloProd(),
				'obtentor_id'	                    => $VariedadData->getObtentorId(),
				'tamano_bunch_id'	                => $VariedadData->getTamanoBunchId(),
				'color_ventas_id'	                => $VariedadData->getColorVentasId(),
				'url_ficha'	              			=> $VariedadData->getUrlFicha(),
				'producto_id'	        		    => $VariedadData->getProductoId(),
				'estado'		                    => $VariedadData->getEstado(),
				'fec_ingreso'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'	                => $VariedadData->getUsuarioIngId(),
				'sincronizado'	                    => 0
	);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $key;
	}//end function ingresar

	/**
	 * Modificar
	 *
	 * @param VariedadData $VariedadData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(VariedadData $VariedadData)
	{
		$key    = array(
				'id'						        => $VariedadData->getId()
		);
		$record = array(
				'nombre'		                    => $VariedadData->getNombre(),
				'nombre_tecnico'		            => $VariedadData->getNombreTecnico(),
				'calidad_variedad_id'	            => $VariedadData->getCalidadVariedadId(),
				'color'		   		                => $VariedadData->getColor(),
				'color2'		   		            => $VariedadData->getColor2(),
				'grupo_color_id'	                => $VariedadData->getGrupoColorId(),
				'colorbase'	                   		=> $VariedadData->getColorBase(),
				'solido'	                   		=> $VariedadData->getSolido(),
				'es_real'		                    => $VariedadData->getEsReal(),
				'est_producto_especial'	            => $VariedadData->getEstProductoEspecial(),
				'mensaje'	        			    => $VariedadData->getMensaje(),
				'cultivada'	    			        => $VariedadData->getCultivada(),
				'ciclo_prod'	                    => $VariedadData->getCicloProd(),
				'obtentor_id'	                    => $VariedadData->getObtentorId(),
				'tamano_bunch_id'	                => $VariedadData->getTamanoBunchId(),
				'color_ventas_id'	                => $VariedadData->getColorVentasId(),
				'url_ficha'	                		=> $VariedadData->getUrlFicha(),
				'estado'		                    => $VariedadData->getEstado(),
				'fec_modifica'	                    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                    => $VariedadData->getUsuarioModId(),
				'sincronizado'	                    => 0
				//'fec_sincronizado'                  => \Application\Classes\Fecha::getFechaHoraActualServidor()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $VariedadData->getId();
	}//end function modificar


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
				$VariedadData	    = new VariedadData();
		
				$sql = 	' SELECT variedad.* '.
						' FROM variedad '.
						' WHERE variedad.id = :id ';
						
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$VariedadData->setId							($row['id']);				
					$VariedadData->setNombre 						($row['nombre']);
					$VariedadData->setNombreTecnico					($row['nombre_tecnico']);
					$VariedadData->setCalidadVariedadId				($row['calidad_variedad_id']);
					$VariedadData->setColor							($row['color']);
					$VariedadData->setColor2 						($row['color2']);
					$VariedadData->setGrupoColorId					($row['grupo_color_id']);
					$VariedadData->setColorBase						($row['colorbase']);
					$VariedadData->setSolido						($row['solido']);
					$VariedadData->setEsReal						($row['es_real']);
					$VariedadData->setEstProductoEspecial			($row['est_producto_especial']);
					$VariedadData->setMensaje						($row['mensaje']);
					$VariedadData->setCultivada						($row['cultivada']);
					$VariedadData->setCicloProd						($row['ciclo_prod']);
					$VariedadData->setObtentorId					($row['obtentor_id']);
					$VariedadData->setTamanoBunchId					($row['tamano_bunch_id']);
					$VariedadData->setColorVentasId					($row['color_ventas_id']);
					$VariedadData->setUrlFicha						($row['url_ficha']);
					$VariedadData->setEstado    					($row['estado']);
					$VariedadData->setFecIngreso 					($row['fec_ingreso']);
					$VariedadData->setFecModifica 					($row['fec_modifica']);
					$VariedadData->setUsuarioIngId 					($row['usuario_ing_id']);
					$VariedadData->setUsuarioModId 					($row['usuario_mod_id']);
					$VariedadData->setSincronizado 					($row['sincronizado']);
					$VariedadData->setFecSincronizado				($row['fec_sincronizado']);
					return $VariedadData;
				}else{
					return null;
				}//end if
				break;
		
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT variedad.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM variedad LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = variedad.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = variedad.usuario_mod_id '.
						' WHERE variedad.id = :id ';
		
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch
		
		
		
	}//end function consultar

	
	/**
	 * consultarTodos
	 *
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT variedad.* '.
				' FROM variedad ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		//return new ViewModel(array(result'=>$result));
		return $result;
	}//end function consultarTodos
	
	
	

	/**
	 *
	 * @param string $id
	 * @param string $nombre
	 * @return array
	 */
	public function consultarDuplicado($accion, $id, $nombre)
	{
		$sql = 	' SELECT variedad.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
				' FROM variedad LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = variedad.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = variedad.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
				$sql = $sql." WHERE variedad.id 	 = '".$id."'".
						"    or variedad.nombre = '".$nombre."'";
				break;
	
			case 'M':
				$sql = $sql." WHERE variedad.id 	!= '".$id."'".
						"   and variedad.nombre = '".$nombre."'";
				break;
		}//end switch
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarDuplicado
	

	
	
	public function listado($condiciones)
	{
		$sql = 	' SELECT variedad.id, variedad.nombre, variedad.colorbase,variedad.solido, variedad.es_real, variedad.sincronizado, variedad.fec_sincronizado, color_ventas.nombre as color_venta, variedad.estado,
						 variedad.url_ficha '.
				' FROM variedad '.
				' 			LEFT JOIN color_ventas '.
				' 					ON variedad.color_ventas_id = color_ventas.id';
	
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (variedad.nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or variedad.id like '%".$condiciones['criterio_busqueda']."%'".
					"      or variedad.colorbase like '%".$condiciones['criterio_busqueda']."%')";
					
		}//end if
	
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and variedad.estado = '".$condiciones['estado']."'";
		}//end if 
		
		
		if (!empty($condiciones['color_ventas_id']))
		{
			$sql = $sql." and variedad.color_ventas_id = '".$condiciones['color_ventas_id']."' ";
		}//end if

		
		if (isset($condiciones['sincronizado']))
		{
			if ($condiciones['sincronizado']!='')
			{
				$sql = $sql." and sincronizado = ".$condiciones['sincronizado'];
			}//end if
		}//end if
		$sql= $sql. " order by variedad.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado
	
	
	public function listadoExcel($condiciones)
	{
		$sql = ' SELECT 	variedad.id, variedad.nombre, variedad.url_ficha, calidad_variedad.nombre as calidad, obtentor.nombre as nombre_obtentor,
						tamano_bunch.nombre as nombre_bunch, colores.nombre AS color_base, color_ventas.nombre AS color_venta, 	variedad.solido,
						variedad.es_real, variedad.estado '.
				' FROM 	variedad'.
				'				LEFT JOIN color_ventas '.
				' 						ON variedad.color_ventas_id = color_ventas.id '.
				' 				LEFT JOIN tamano_bunch '.
				' 						ON variedad.tamano_bunch_id = tamano_bunch.id '.
				'				LEFT JOIN calidad_variedad '.
				' 						ON	variedad.calidad_variedad_id = calidad_variedad.id '.
				'				LEFT JOIN obtentor '.
				' 						ON variedad.obtentor_id = obtentor.id '.
				' 				LEFT JOIN colores '.
				'						ON variedad.colorbase = colores.color ';
				
		
		
		
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (variedad.nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or variedad.id like '%".$condiciones['criterio_busqueda']."%'".
					"      or variedad.colorbase like '%".$condiciones['criterio_busqueda']."%')";
				
		}//end if
		
		if (!empty($condiciones['busqueda_estado']))
		{
			$sql = $sql." and variedad.estado = '".$condiciones['busqueda_estado']."'";
		}//end if
		
		
		if (!empty($condiciones['busqueda_color']))
		{
			$sql = $sql." and variedad.color_ventas_id = '".$condiciones['busqueda_color']."' ";
		}//end if
		
		
		$sql= $sql. " order by variedad.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		return $result;
		
	}
	
	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return VariedadData|null
	 */
	public function consultarVariedad($id)
	{
		$VariedadData 		    = new VariedadData();
	
		$sql = 	' SELECT variedad.* '.
				' FROM variedad '.
				' WHERE variedad.id = :id ';
	
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
	
			$VariedadData->setId					($row['id']);
			$VariedadData->setNombre 				($row['nombre']);
			return $VariedadData;
		}else{
			return null;
		}//end if
	
	}//end function consultar

	
	public function listadoDispo($condiciones)
	{
		$sql = 	' SELECT * '.
				' FROM variedad '.
				' WHERE 1 = 1';
	
	}//end function listadoDispo
	
}//end class

