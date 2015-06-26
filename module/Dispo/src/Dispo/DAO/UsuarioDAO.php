<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\UsuarioData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UsuarioDAO extends Conexion 
{
	private $table_name	= 'usuario';

	/**
	 * Ingresar
	 *
	 * @param UsuarioData $UsuarioData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(UsuarioData $UsuarioData)
	{
		$key    = array(
				'id'						        => $UsuarioData->getId(),
		);
		$record = array(
				'id'							=> $UsuarioData->getId(),
				'nombre'		                => $UsuarioData->getNombre(),
				'username'		                => $UsuarioData->getUsername(),
				'password'		                => $UsuarioData->getPassword(),
				'email'		                	=> $UsuarioData->getEmail(),
				'perfil_id'		                => $UsuarioData->getPerfilId(),
				'cliente_id'	                => $UsuarioData->getClienteId(),
				'estado'		                => $UsuarioData->getEstado(),
				'grupo_dispo_cab_id'            => $UsuarioData->getGrupoDispoCabId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
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
				'id'						        => $UsuarioData->getid(),
		);
		$record = array(
				'id'							=> $UsuarioData->getId(),
				'nombre'		                => $UsuarioData->getNombre(),
				'username'		                => $UsuarioData->getUsername(),
				'password'		                => $UsuarioData->getPassword(),
				'email'		                	=> $UsuarioData->getEmail(),
				'perfil_id'		                => $UsuarioData->getPerfilId(),
				'cliente_id'		                => $UsuarioData->getClienteId(),
				'estado'		                => $UsuarioData->getEstado(),
				'grupo_dispo_cab_id'            => $UsuarioData->getGrupoDispoCabId()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $UsuarioData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param int $id
	 * @return UsuarioData|null
	 */	
	public function consultar($id)
	{
		$UsuarioData 		    = new UsuarioData();

		$sql = 	' SELECT usuario.* '.
				' FROM usuario '.
				' WHERE usuario.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$UsuarioData->setId											($row['id']);
				$UsuarioData->setNombre										($row['nombre']);
				$UsuarioData->setUsername									($row['username']);
				$UsuarioData->setPassword									($row['password']);
				$UsuarioData->setEmail										($row['email']);
				$UsuarioData->setPerfilId									($row['perfil_id']);
				$UsuarioData->setClienteId									($row['cliente_id']);
				$UsuarioData->setEstado										($row['estado']);
				$UsuarioData->setGrupoDispoCabId							($row['grupo_dispo_cab_id']);
				
			return $UsuarioData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

?>