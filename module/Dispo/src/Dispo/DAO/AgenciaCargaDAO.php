<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\AgenciaCargaData;
use Zend\View\Model\JsonModel;
use Dispo\BO\AgenciaCargaBO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AgenciaCargaDAO extends Conexion 
{
	private $table_name	= 'agencia_carga';

	/**
	 * Ingresar
	 *
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(AgenciaCargaData $AgenciaCargaData)
	{
		$key    = array(
				'id'						        => $AgenciaCargaData->getId(),
		);
		$record = array(
				'id'								=> $AgenciaCargaData->getId(),
				'nombre'		                    => $AgenciaCargaData->getNombre(),
				'direccion'		            		=> $AgenciaCargaData->getDireccion(),
				'telefono'                			=> $AgenciaCargaData->getTelefono(),
				'tipo'                				=> $AgenciaCargaData->getTipo(),
				'estado'                			=> $AgenciaCargaData->getEstado(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'                	=> $AgenciaCargaData->getUsuarioIngId(),
				'sincronizado'                		=> 0
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $key;
	}//end function ingresar

	
	
	/**
	 * Modificar
	 *
	 * @param AgenciaCargaData $AgenciaCargaData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(AgenciaCargaData $AgenciaCargaData)
	{
		$key    = array(
				'id'						        => $AgenciaCargaData->getId(),
		);
		$record = array(
				'nombre'		                    => $AgenciaCargaData->getNombre(),
				'direccion'		            		=> $AgenciaCargaData->getDireccion(),
				'telefono'                			=> $AgenciaCargaData->getTelefono(),
				'tipo'                				=> $AgenciaCargaData->getTipo(),
				'estado'                			=> $AgenciaCargaData->getEstado(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $AgenciaCargaData->getUsuarioModId(),
				'sincronizado'       	         	=> 0
				//'fecha_mod'							=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $AgenciaCargaData->getId();
	}//end function modificar


	/**
	 * Consultar
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\AgenciaCargaData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$AgenciaCargaData 		    = new AgenciaCargaData();
				
				$sql = 	' SELECT agencia_carga.* '.
						' FROM agencia_carga '.
						' WHERE agencia_carga.id = :id ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
					$AgenciaCargaData->setId			    ($row['id']);
					$AgenciaCargaData->setNombre		    ($row['nombre']);
					$AgenciaCargaData->setDireccion		    ($row['direccion']);
					$AgenciaCargaData->setTelefono    		($row['telefono']);
					$AgenciaCargaData->setTipo				($row['tipo']);
					$AgenciaCargaData->setEstado    		($row['estado']);
					$AgenciaCargaData->setFecIngreso   		($row['fec_ingreso']);
					$AgenciaCargaData->setFecModifica  		($row['fec_modifica']);
					$AgenciaCargaData->setUsuarioIngId		($row['usuario_ing_id']);
					$AgenciaCargaData->setUsuarioModId		($row['usuario_mod_id']);
					$AgenciaCargaData->setSinronizado		($row['sincronizado']);
					$AgenciaCargaData->setFecSincronizado	($row['fec_sincronizado']);
				
					return $AgenciaCargaData;
				}else{
					return null;
				}//end if				
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT agencia_carga.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM agencia_carga LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = agencia_carga.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = agencia_carga.usuario_mod_id '.						
						' WHERE agencia_carga.id = :id ';
				
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
		$sql = 	' SELECT agencia_carga.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
				' FROM agencia_carga LEFT JOIN usuario as usuario_ing '.
				'                           ON usuario_ing.id = agencia_carga.usuario_ing_id '.
				'					 LEFT JOIN usuario as usuario_mod '.
				'                           ON usuario_mod.id = agencia_carga.usuario_mod_id ';
		switch ($accion)
		{
			case 'I':
					$sql = $sql." WHERE agencia_carga.id 	 = '".$id."'".
					            "    or agencia_carga.nombre = '".$nombre."'";
				break;
				
			case 'M':
				$sql = $sql." WHERE agencia_carga.id 	!= '".$id."'".
						    "   and agencia_carga.nombre = '".$nombre."'";
				break;
		}//end switch
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		return $result;
	}//end function consultarDuplicado


	/**
	 * 
	 * @param string $estado
	 * @return array
	 */
	public function consultarTodos($estado = NULL)
	{
		$sql = 	' SELECT agencia_carga.* '.
				' FROM agencia_carga '.
				' WHERE 1=1';
	
		if (!empty($estado)){
			$sql = $sql." and estado = '".$estado."'";
		}//end if

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function consultarTodos


	
	/**
	 * 
	 * @param string $cliente_id
	 * @return array
	 */
	public function consultarPorCliente($cliente_id)
	{
		$sql = 	' SELECT agencia_carga.* '.
				' FROM  cliente_agencia_carga INNER JOIN agencia_carga  '.
				'									  ON agencia_carga.id = cliente_agencia_carga.agencia_carga_id '.
				' WHERE cliente_agencia_carga.cliente_id = :cliente_id ';
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':cliente_id',$cliente_id);
		$stmt->execute();
		$result = $stmt->fetchAll(); 
		return $result;
	}//end function consultarPorcLIENTE
	
	
	/**
	 * 
	 * @param array $condiciones
	 * @return array
	 */
	public function listado($condiciones)
	{
		$sql = " SELECT agencia_carga.id, agencia_carga.nombre, agencia_carga.direccion, agencia_carga.telefono,
						agencia_carga.tipo, agencia_carga.sincronizado, agencia_carga.fec_sincronizado, agencia_carga.estado ".
				" FROM agencia_carga ".
				" WHERE 1= 1";
		
		if (!empty($condiciones['criterio_busqueda'])){
			$sql = $sql." and nombre	= '".$condiciones['criterio_busqueda']."'";
		}//end if
		
		if (!empty($condiciones['criterio_busqueda'])){
			$sql = $sql." or direccion = '".$condiciones['criterio_busqueda']."'";
		}//end if
		
		if (!empty($condiciones['criterio_busqueda'])){
			$sql = $sql." or telefono	= '".$condiciones['criterio_busqueda']."'";
		}//end if
		
		if (!empty($condiciones['estado'])){
			$sql = $sql." and estado		= '".$condiciones['estado']."'";
		}//end if
		
		$stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
		$result = $stmt->fetchAll();
		return $result;
		
	}//end function listado
	
	
	
}//end class

