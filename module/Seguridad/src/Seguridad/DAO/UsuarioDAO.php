<?php

namespace Seguridad\DAO;

use Doctrine\ORM\EntityManager,
    Seguridad\Entity\Usuario,
	Application\Classes\Conexion;
use Seguridad\Data\UsuarioData;
use General\Data\PersonaData;
use Doctrine\ORM\Tools\Pagination\Paginator;	


class UsuarioDAO extends Conexion {
	private $table_name	= 'usuario';
	
	private $page		= null;
	private	$limit		= null;
	private $sidx		= null;
	private $sord		= null;

	function setPage($valor)	{$this->page = $valor;}
	function setLimit($valor)	{$this->limit = $valor;}
	function setSidx($valor)	{$this->sidx = $valor;}
	function setSord($valor)	{$this->sord = $valor;}
	
	function getPage()			{return $this->page;}
	function getLimit()			{return $this->limit;}
	function getSidx()			{return $this->sidx;}
	function getSord()			{return $this->sord;}
	
	/*-----------------------------------------------------------------------------*/	 	
	private function getLimitIni(){
	/*-----------------------------------------------------------------------------*/	 
		return ($this->page-1)*$this->limit;
	}//end function getLimitIni
	

	 		
	/**
	 * Ingresar
	 *
	 * @param UsuarioData $UsuarioData
	 * @return array Retorna un Array $id el cual contiene el id
	 */
	public function ingresar(UsuarioData $UsuarioData)
	{
		//$key    = array(
		//		'id'						        => $UsuarioData->getId(),
		//);
		$record = array(
				
				'nombre'		                    => $UsuarioData->getNombre(),
				'username'		            		=> $UsuarioData->getUsername(),
				'password'                			=> $this->encriptar($UsuarioData->getPassword()),
				'email'                				=> $UsuarioData->getEmail(),
				'perfil_id'                			=> $UsuarioData->getPerfilId(),
				'cliente_id'                		=> $UsuarioData->getClienteId(),
				'login_fox'                			=> $UsuarioData->getLoginFox(),
				'estado'                			=> $UsuarioData->getEstado(),
				'grupo_dispo_cab_id'                => $UsuarioData->getGrupoDispoCabId(),
				'grupo_precio_cab_id'               => $UsuarioData->getGrupoPrecioCabId(),
				'inventario_id'                		=> $UsuarioData->getInventarioId(),
				'calidad_id'                		=> $UsuarioData->getCalidadId(),
				'fec_ingreso'           		    => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'                	=> $UsuarioData->getUsuarioIngId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar

	
	
	/**
	 * Modificar
	 *
	 * @param UsuarioData $UsuarioData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(UsuarioData $UsuarioData)
	{
		$key    = array(
				'id'						        => $UsuarioData->getId(),
		);
		$record = array(
				'id'								=> $UsuarioData->getId(),
				'nombre'		                    => $UsuarioData->getNombre(),
				'username'		            		=> $UsuarioData->getUsername(),
				'email'                				=> $UsuarioData->getEmail(),
				'perfil_id'                			=> $UsuarioData->getPerfilId(),
				'cliente_id'                		=> $UsuarioData->getClienteId(),
				'login_fox'              	  		=> $UsuarioData->getLoginFox(),
				'estado'                			=> $UsuarioData->getEstado(),
				'grupo_dispo_cab_id'                => $UsuarioData->getGrupoDispoCabId(),
				'grupo_precio_cab_id'               => $UsuarioData->getGrupoPrecioCabId(),
				'inventario_id'                		=> $UsuarioData->getInventarioId(),
				'calidad_id'                		=> $UsuarioData->getCalidadId(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $UsuarioData->getUsuarioModId()
		);
		if (!empty($UsuarioData->getPassword()))
		{
			$record['password'] = $this->encriptar($UsuarioData->getPassword());
		}//end if
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $UsuarioData->getId();
	}//end function modificar



	/**
	 * Consultar
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\UsuarioData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$UsuarioData 		    = new UsuarioData();
				
				$sql = 	' SELECT usuario.* '.
						' FROM usuario '.
						' WHERE usuario.id = :id ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$UsuarioData->setId			   		 ($row['id']);
					$UsuarioData->setNombre		   		 ($row['nombre']);
					$UsuarioData->setUsername			 ($row['username']);
					$UsuarioData->setPassword			 ($row['password']);
					$UsuarioData->setEmail   			 ($row['email']);
					$UsuarioData->setPerfilId			 ($row['perfil_id']);
					$UsuarioData->setClienteId			 ($row['cliente_id']);
					$UsuarioData->setLoginFox			 ($row['login_fox']);
					$UsuarioData->setEstado				 ($row['estado']);
					$UsuarioData->setGrupoDispoCabId	 ($row['grupo_dispo_cab_id']);
					$UsuarioData->setGrupoPrecioCabId	 ($row['grupo_precio_cab_id']);
					$UsuarioData->setInventarioId		 ($row['inventario_id']);
					$UsuarioData->setCalidadId			 ($row['calidad_id']);
					$UsuarioData->setFecIngreso			 ($row['fec_ingreso']);
					$UsuarioData->setFecModifica		 ($row['fec_modifica']);
					$UsuarioData->setUsuarioIngId		 ($row['usuario_ing_id']);
					$UsuarioData->SetUsuarioModId		 ($row['usuario_mod_id']);
					return $UsuarioData;
				}else{
					return null;
				}//end if				
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT usuario.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name, '.
						'        cliente.nombre cliente_nombre, '.
						'		 calidad.clasifica_fox, '.
						'        inventario.punto_corte, '.
						'        grupo_precio_cab.inventario_id as grupo_precio_cab_inventario_id, '.
						'        grupo_precio_cab.calidad_id as grupo_precio_cab_calidad_id, '.
						'        grupo_dispo_cab.inventario_id as grupo_dispo_cab_inventario_id, '.
						'        grupo_dispo_cab.calidad_id as grupo_dispo_cab_calidad_id '.
						' FROM usuario LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = usuario.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = usuario.usuario_mod_id '.	
						'					 LEFT JOIN cliente '.
						'              				ON cliente.id	= usuario.cliente_id'.	
						'              	     LEFT JOIN grupo_precio_cab '.
						'                     		ON grupo_precio_cab.id  = usuario.grupo_precio_cab_id '.
						'					 LEFT JOIN grupo_dispo_cab '.
						'                           ON grupo_dispo_cab.id	= usuario.grupo_dispo_cab_id '.
						'					 LEFT JOIN inventario '.
						'                           ON inventario.id		= usuario.inventario_id'.
						'			   		 LEFT JOIN calidad '.
						'                           ON calidad.id			= usuario.calidad_id '.						
						' WHERE usuario.id = :id ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				return $row;
				break;
		}//end switch

	}//end function consultar

	
	
	/**
	 * 
	 * @param string $accion
	 * @param string $id
	 * @param string $nombre
	 * @param string $username
	 * @return array
	 */
	public function consultarDuplicado($accion, $id, $nombre, $username)
	{
		$sql = 	' SELECT usuario.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name, usuario.username  '.
				' FROM usuario LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = usuario.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = usuario.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
					$sql = $sql." WHERE usuario.id 	 = '".$id."'".
					            "    or usuario.nombre = '".$nombre."'";
								"    or usuario.username = '".$username."'";
				break;
				
			case 'M':
				$sql = $sql." WHERE usuario.id 	!= '".$id."'".
						    "   and usuario.nombre = '".$nombre."'";
				break;
		}//end switch
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarDuplicado


	/**
	 * consultarTodos
	 * 
	 * @return array
	 */
	public function consultarTodos()
	{
		$sql = 	' SELECT usuario.* '.
				' FROM usuario ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarTodos

	
	
	/**
	 * 
	 * En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado 
	 * 
	 * @param array $condiciones   
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = 	' SELECT usuario.*, perfil.nombre as perfil_nombre, grupo_dispo_cab.nombre AS grupo_dispo, grupo_precio_cab.nombre AS grupo_precio, calidad.nombre AS nombre_calidad, usuario.login_fox'.
				' FROM usuario  LEFT JOIN perfil '.
				'		               ON perfil.id      = usuario.perfil_id '.
				'				LEFT JOIN grupo_dispo_cab '.
				'					 ON grupo_dispo_cab.id = usuario.grupo_dispo_cab_id '.
				'				LEFT JOIN grupo_precio_cab '.
				'					 ON grupo_precio_cab.id = usuario.grupo_precio_cab_id '.
				'				LEFT JOIN calidad '.
				'					ON calidad.id = usuario.calidad_id '.
				' WHERE 1 = 1 ';
		
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (usuario.nombre like '%".$condiciones['criterio_busqueda']."%'".
						"      or usuario.id like '%".$condiciones['criterio_busqueda']."%'".
						"      or usuario.username like '%".$condiciones['criterio_busqueda']."%'".
						"      or usuario.email like '%".$condiciones['criterio_busqueda']."%')";						
		}//end if
		
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and usuario.estado = '".$condiciones['estado']."'";
		}//end if 
		
		if (!empty($condiciones['cliente_id']))
		{
			$sql = $sql." and usuario.cliente_id = '".$condiciones['cliente_id']."'";
		}//end if
		
		if (!empty($condiciones['solo_vendedor_administrador']))
		{
			$sql = $sql." and usuario.perfil_id <> 1";
		}	
		if (isset($condiciones['perfil_id']))
		{
		if ($condiciones['perfil_id']!='')
		{
				$sql = $sql." and perfil_id = ".$condiciones['perfil_id'];
			}//end if
		}//end if
		$sql=$sql." order by usuario.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado

	
 		
	function login($usuario, $clave, $ipAcceso, $nombreHost, $AgenteUsuario)
	{
		$sql = 	" SELECT usuario.id, usuario.nombre as usuario_nombre, usuario.username, usuario.password, ".
				"        usuario.email, usuario.perfil_id, ".
				"        usuario.cliente_id, usuario.estado, ".
				"		 usuario.grupo_dispo_cab_id, usuario.grupo_precio_cab_id,".
				"        perfil.nombre as perfil_nombre, ".
				"        cliente.nombre as cliente_nombre, ".
				"        usuario.inventario_id as usuario_inventario_id,".
				"		 usuario.calidad_id as usuario_calidad_id, ".
				"		 usuario.grupo_dispo_cab_id as usuario_grupo_dispo_cab_id, ".
				"		 usuario.grupo_precio_cab_id as usuario_grupo_precio_cab_id,".
				"        grupo_precio_cab.inventario_id as grupo_precio_cab_inventario_id, grupo_precio_cab.calidad_id as grupo_precio_cab_calidad_id,".
				"        grupo_dispo_cab.inventario_id as grupo_dispo_cab_inventario_id, grupo_dispo_cab.calidad_id as grupo_dispo_cab_calidad_id, ".
				"        inventario.punto_corte as usuario_punto_corte, ".
				"		 usuario.atributos ".
/*				"        grupo_precio_cab.calidad_id ".*/
				" FROM usuario INNER JOIN perfil ".
				"                      ON perfil.id		= usuario.perfil_id".
				"              LEFT JOIN cliente ".
				"                      ON cliente.id	= usuario.cliente_id".
				'              LEFT JOIN grupo_precio_cab '.
				'                      ON grupo_precio_cab.id = usuario.grupo_precio_cab_id '.
				'              LEFT JOIN grupo_dispo_cab '.
				'                      ON grupo_dispo_cab.id  = usuario.grupo_dispo_cab_id'.
				'              LEFT JOIN inventario '.
				'                      ON inventario.id = usuario.inventario_id'.
				" WHERE username = :username";

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		if (!empty($usuario)){
			$stmt->bindValue(":username", $usuario);
		}//end if

		$stmt->execute();
		$reg = $stmt->fetch();
		
		if (empty($reg))
		{
			$reg['respuesta_codigo']	='UNE';			
			$reg['respuesta_mensaje']	='USUARIO NO EXISTE EN SISTEMA';	
			return $reg;
		}//end if

		if ($reg['estado']!='A')
		{
			//INACTIVO
			$reg['respuesta_codigo']	='UI';
			$reg['respuesta_mensaje']	='EL USUARIO ESTA INACTIVO, POR FAVOR CONSULTE CON SOPORTE A USUARIO';
			return $reg;
		}//end if

		//Validaciones extras si el usuario es un cliente
		if ($reg['perfil_id']==\Application\Constants\Perfil::ID_CLIENTE)
		{
			if (empty($reg['usuario_inventario_id']))
			{
				$reg['respuesta_codigo']	='NO-EXISTS-INVENTARIO';
				$reg['respuesta_mensaje']	='USUARIO NO TIENE INVENTARIO ASIGNADO';
				return $reg;
			}//end if		

			if (empty($reg['usuario_calidad_id']))
			{
				$reg['respuesta_codigo']	='NO-EXISTS-CALIDAD';
				$reg['respuesta_mensaje']	='USUARIO NO TIENE CALIDAD ASIGNADA';
				return $reg;
			}//end if
						
			if ($reg['usuario_inventario_id']!=$reg['grupo_precio_cab_inventario_id'])
			{
				$reg['respuesta_codigo']	='NO-CONFIG-INV-PRECIO';
				$reg['respuesta_mensaje']	='INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE INVENTARIO (GRUPO PRECIO)';					
				return $reg;
			}//end if

			if ($reg['usuario_inventario_id']!=$reg['grupo_dispo_cab_inventario_id'])
			{
				$reg['respuesta_codigo']	='NO-CONFIG-INV-DISPO';
				$reg['respuesta_mensaje']	='INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE INVENTARIO (GRUPO DISPO)';
				return $reg;
			}//end if			
	
			if ($reg['usuario_calidad_id']!=$reg['grupo_precio_cab_calidad_id'])
			{
				$reg['respuesta_codigo']	='NO-CONFIG-CAL-PRECIO';
				$reg['respuesta_mensaje']	='INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE PRECIO (GRUPO PRECIO)';
				return $reg;
			}//end if
	
			if ($reg['usuario_calidad_id']!=$reg['grupo_dispo_cab_calidad_id'])
			{
				$reg['respuesta_codigo']	='NO-CONFIG-CAL-DISPO';
				$reg['respuesta_mensaje']	='INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE PRECIO (GRUPO DISPO)';
				return $reg;
			}//end if
			
			if (empty($reg['usuario_punto_corte']))
			{
				$reg['respuesta_codigo']	='NO-PUNTO-CORTE';
				$reg['respuesta_mensaje']	='NO TIENE PUNTO DE CORTE EL INVENTARIO ('.$reg['inventario_id'].') ';
				return $reg;
			}//end if
		}//end if
			
		//ACTIVO
		$clave_encriptada = $this->encriptar($clave);
		if ($reg['password'] == $clave_encriptada)
		{
			$reg['respuesta_codigo']	='OK';
			$reg['respuesta_mensaje']	='';
			return $reg;
		}else{
			$reg['respuesta_codigo']	='CEU';
			$reg['respuesta_mensaje']	='CLAVE ERRADA PARA EL USUARIO INGRESADO';
			return $reg;						
		}//end if
	}//end function login


	
	/**
	 * 
	 * @param int $usuario_id
	 * @return array
	 */
/*	function consultarGrupoDispoCab($usuario_id)
	{
		$sql = 	" SELECT usuario.grupo_dispo_cab_id, grupo_dispo_cab.inventario_id, grupo_precio_cab.calidad_id, calidad.clasifica_fox ".
				" FROM usuario INNER JOIN grupo_dispo_cab ".
				"                      ON grupo_dispo_cab.id   		= usuario.grupo_dispo_cab_id".
				"              LEFT JOIN cliente ".
				"                      ON cliente.id				= usuario.cliente_id".
				"			   LEFT JOIN  grupo_precio_cab ".
				"              		   ON grupo_precio_cab.id		= cliente.grupo_precio_cab_id".
				"			   LEFT JOIN calidad ".
				"                      ON calidad.id				= grupo_precio_cab.calidad_id ".
				" WHERE usuario.id = :usuario_id";

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue('usuario_id', $usuario_id);
		$stmt->execute();
		
		//$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$reg = $stmt->fetch();
		return $reg;		
	}//end function consultarGrupoDispoCab
*/	
	
	
	/**
	 * 
	 * @param int $cliente_id
	 * @return array
	 */
	function consultarPorCliente($cliente_id)
	{
		$sql = 	" SELECT usuario.id, usuario.nombre, usuario.username, CONCAT(usuario.nombre,' (',usuario.username,')') as nombre_completo ".
				" FROM cliente INNER JOIN usuario ".
				"                      ON usuario.cliente_id	= cliente.id ".
				"                     AND usuario.perfil_id		= ".\Application\Constants\Perfil::ID_CLIENTE.
				" WHERE cliente.id = :cliente_id";
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue('cliente_id', $cliente_id);
		$stmt->execute();
		
		//$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;		
	}//end function function consultarPorCliente
	
	
	/**
	 * consultarTodosVendedores
	 *
	 * @return array
	 */
	public function consultarTodosVendedores()
	{
		$sql = 	' SELECT usuario.* '.
				' FROM usuario '.
				' WHERE perfil_id='.\Application\Constants\Perfil::ID_VENTAS.
				' order by nombre';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarTodosVendedores
	
	
	/***
	 * 
	 * @return array
	 */
	public function consultarVendedoresAdmin()
	{
		$sql = 	' SELECT usuario.* '.
				' FROM usuario '.
				' WHERE perfil_id = '.\Application\Constants\Perfil::ID_ADMIN.
				' OR perfil_id = '. \Application\Constants\Perfil::ID_VENTAS.
				' order by nombre';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarTodosVendedores
	
	
	/**
	 * 
	 * @param string $username
	 * @param string $clave_encriptada
	 * @return number
	 */
	function usuarioencriptar($username, $clave_encriptada)
	{
		$sql = 	'UPDATE '.$this->table_name.
				" SET password		 			='".$clave_encriptada."'".
				" WHERE username				='".$username."'";

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);

		return $count;
	}//end function

	
	
	/**
	 * 
	 * @param string $clave
	 * @return string
	 */
	function encriptar($clave)
	{
		$clave_encriptada = sha1(md5($clave));
		return $clave_encriptada;	
	}//end function encriptar
	
	
	
	/**
	 * 
	 * @param UsuarioData $UsuarioData
	 * @return number
	 */
	function desvincularGrupoDispo(UsuarioData $UsuarioData)
	{
		$key    = array(
				'id'						        => $UsuarioData->getId(),
		);
		$record = array(
				'grupo_dispo_cab_id'                => null,
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $UsuarioData->getUsuarioModId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $UsuarioData->getId();
	}//end function desvincularGrupoDispo
	
	
	
	
	/**
	 *
	 * @param UsuarioData $UsuarioData
	 * @return number
	 */
	function vincularGrupoDispo(UsuarioData $UsuarioData)
	{
		$key    = array(
				'id'						        => $UsuarioData->getId(),
		);
		$record = array(
				'grupo_dispo_cab_id'                => $UsuarioData->getGrupoDispoCabId(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $UsuarioData->getUsuarioModId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $UsuarioData->getId();
	}//end function vincularGrupoDispo	
	
	
	

	/**
	 *
	 * @param ClienteData $ClienteData
	 * @return number
	 */
	function vincularGrupoPrecio(UsuarioData $UsuarioData)
	{
		$key    = array(
				'id'						=> $UsuarioData->getId(),
		);
		$record = array(
				'grupo_precio_cab_id'       => $UsuarioData->getGrupoPrecioCabId(),
				'fec_modifica'              => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'            => $UsuarioData->getUsuarioModId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $UsuarioData->getId();
	}//end function vincularGrupoPrecio
	
	
	
	/**
	 *
	 * @param ClienteData $ClienteData
	 * @return number
	 */
	function desvincularGrupoPrecio(UsuarioData $UsuarioData)
	{
		$key    = array(
				'id'						=> $UsuarioData->getId(),
		);
		$record = array(
				'grupo_precio_cab_id'       => null,
				'fec_modifica'              => \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'            => $UsuarioData->getUsuarioModId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $UsuarioData->getId();
	}//end function desvincularGrupoPrecio
	
	
	/**
	 * 
	 * @param int $grupo_dispo_cab_id
	 * @param int $estado_enviar_dispo
	 * @return int
	 */
	function actualizarEstadoEnviarDispoPorGrupoDispo($grupo_dispo_cab_id, $estado_enviar_dispo)
	{
		$sql = 	'UPDATE '.$this->table_name.
				" SET estado_enviar_dispo		=".$estado_enviar_dispo.
				" WHERE grupo_dispo_cab_id		=".$grupo_dispo_cab_id.
				"   and estado = 'A'";

		$count = $this->getEntityManager()->getConnection()->executeUpdate($sql);

		return $count;
	}//end function actualizarEstadoEnviarDispoPorGrupoDispo	
	
}//end class