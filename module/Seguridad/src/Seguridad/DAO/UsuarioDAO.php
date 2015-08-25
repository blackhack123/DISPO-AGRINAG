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
				'password'                			=> $UsuarioData->getPassword(),
				'email'                				=> $UsuarioData->getEmail(),
				'perfil_id'                			=> $UsuarioData->getPerfilId(),
				'cliente_id'                		=> $UsuarioData->getClienteId(),
				'estado'                			=> $UsuarioData->getEstado(),
				'grupo_dispo_cab_id'                => $UsuarioData->getGrupoDispoCabId(),
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
				'estado'                			=> $UsuarioData->getEstado(),
				'grupo_dispo_cab_id'                => $UsuarioData->getGrupoDispoCabId(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $UsuarioData->getUsuarioModId()
		);
		if (!empty($UsuarioData->getPassword()))
		{
			$record['password'] = $UsuarioData->getPassword();
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
					$UsuarioData->setEstado				 ($row['estado']);
					$UsuarioData->setGrupoDispoCabId	 ($row['grupo_dispo_cab_id']);
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
						'        cliente.nombre cliente_nombre'.
						' FROM usuario LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = usuario.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = usuario.usuario_mod_id '.	
						'				LEFT JOIN cliente '.
						'              				ON cliente.id	= usuario.cliente_id'.				
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
	 * @param string $id
	 * @param string $nombre
	 * @return array
	 */
	public function consultarDuplicado($accion, $id, $nombre)
	{
		$sql = 	' SELECT usuario.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
				' FROM usuario LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = usuario.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = usuario.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
					$sql = $sql." WHERE usuario.id 	 = '".$id."'".
					            "    or usuario.nombre = '".$nombre."'";
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
		$sql = 	' SELECT usuario.*, perfil.nombre as perfil_nombre '.
				' FROM usuario  LEFT JOIN perfil '.
				'		               ON perfil.id      = usuario.perfil_id '.	
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

		if (!empty($condiciones['solo_vendedor_administrador']))
		{
			$sql = $sql." and usuario.perfil_id in (2,3)";
		}	
		if (isset($condiciones['perfil_id']))
		{
		if ($condiciones['perfil_id']!='')
		{
				$sql = $sql." and perfil_id = ".$condiciones['perfil_id'];
			}//end if
		}//end if
		$sql=$sql."order by usuario.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado


	 		
	function login($usuario, $clave, $ipAcceso, $nombreHost, $AgenteUsuario)
	{		
		$sql = 	" SELECT usuario.id, usuario.nombre as usuario_nombre, usuario.username, usuario.password, ".
				"        usuario.email, usuario.perfil_id, ".
				"        usuario.cliente_id, usuario.estado, usuario.grupo_dispo_cab_id,".
				"        perfil.nombre as perfil_nombre, ".
				"        cliente.nombre as cliente_nombre ".
				" FROM usuario INNER JOIN perfil ".
				"                      ON perfil.id		= usuario.perfil_id".
				"              LEFT JOIN cliente ".
				"                      ON cliente.id	= usuario.cliente_id".
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
		}else{
			if ($reg['estado']=='A')
			{
				//ACTIVO
				$clave_encriptada = $this->encriptar($clave);
				if ($reg['password'] == $clave_encriptada)
				{
					$reg['respuesta_codigo']	='OK';
					$reg['respuesta_mensaje']	='';
				}else{
					$reg['respuesta_codigo']	='CEU';
					$reg['respuesta_mensaje']	='CLAVE ERRADA PARA EL USUARIO INGRESADO';						
				}
			}else{
				//INACTIVO
				$reg['respuesta_codigo']	='UI';
				$reg['respuesta_mensaje']	='EL USUARIO ESTA INACTIVO, POR FAVOR CONSULTE CON SOPORTE A USUARIO';				
			}//end if
		}//end if

		return $reg;		
	}//end function login


	
	/**
	 * 
	 * @param int $usuario_id
	 * @return array
	 */
	function consultarGrupoDispoCab($usuario_id)
	{
		$sql = 	" SELECT usuario.grupo_dispo_cab_id, grupo_dispo_cab.inventario_id, calidad.clasifica_fox ".
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
}//end class