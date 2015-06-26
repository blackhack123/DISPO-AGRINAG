<?php

namespace DispoHistorico\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\DispoHistoricoData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DispoHistoricoDAO extends Conexion 
{
	private $table_name	= 'dispo_historico';

	/**
	 * Ingresar
	 *
	 * @param DispoHistoricoData $DispoHistoricoData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(DispoHistoricoData $DispoHistoricoData)
	{
		$key    = array(
				'id'						        => $DispoHistoricoData->getId(),
		);
		$record = array(
				'id'								=> $DispoHistoricoData->getId(),
				'fecha'		                  				=> $DispoHistoricoData->getFecha(),
				'inventario_id'		            			=> $DispoHistoricoData->getInventarioId(),
				'fecha_bunch'		            			=> $DispoHistoricoData->getFechaBunch,
				'proveedor_id'		            			=> $DispoHistoricoData->getProveedorId(),
				'producto'		            				=> $DispoHistoricoData->getProducto(),
				'variedad_id'		            			=> $DispoHistoricoData->getVariedadId(),
				'grado_id'		            				=> $DispoHistoricoData->getGradoId(),
				'tallos_x_bunch'	         				=> $DispoHistoricoData->getgetTallosxBunch(),
				'clasifica'		            				=> $DispoHistoricoData->getClasifica(),
				'cantidad_bunch'		            		=> $DispoHistoricoData->getgetCantidadBunch(),
				'cantidad_bunch_DispoHistoriconible'		=> $DispoHistoricoData->getCantidadBunchDispoHistoriconible(),
				'edad'										=> $DispoHistoricoData->getEdad()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param DispoHistoricoData $DispoHistoricoData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(DispoHistoricoData $DispoHistoricoData)
	{
		$key    = array(
				'id'						        => $DispoHistoricoData->getId(),
		);
		$record = array(
				'id'									=> $DispoHistoricoData->getId(),
				'fecha'		                  			=> $DispoHistoricoData->getFecha(),
				'inventario_id'		            		=> $DispoHistoricoData->getInventarioId(),
				'fecha_bunch'		            		=> $DispoHistoricoData->getFechaBunch(),
				'proveedor_id'		            		=> $DispoHistoricoData->getProveedorId(),
				'producto'		            			=> $DispoHistoricoData->getProducto(),
				'variedad_id'		            		=> $DispoHistoricoData->getVariedadId(),
				'grado_id'		            			=> $DispoHistoricoData->getGradoId(),
				'tallos_x_bunch'	         			=> $DispoHistoricoData->getgetTallosxBunch(),
				'clasifica'		            			=> $DispoHistoricoData->getClasifica(),
				'cantidad_bunch'		            	=> $DispoHistoricoData->getgetCantidadBunch(),
				'cantidad_bunch_DispoHistoriconible'	=> $DispoHistoricoData->getCantidadBunchDispoHistoriconible(),
				'edad'									=> $DispoHistoricoData->getEdad()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $DispoHistoricoData->getId();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return DispoHistoricoData|null
	 */	
	public function consultar($id)
	{
		$AgenciaCargaData 		    = new DispoHistoricoData();

		$sql = 	' SELECT dispo_historico.* '.
				' FROM dispo__historico '.
				' WHERE dispo_historico.id = :id ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$DispoHistoricoData->getId										($row['id']);				
				$DispoHistoricoData->getFecha									($row['Fecha']);
				$DispoHistoricoData->getInventarioId							($row['inventario_id']);
				$DispoHistoricoData->getFechaBunch								($row['fecha_bunch']);
				$DispoHistoricoData->getProveedorId								($row['proveedor_id']);
				$DispoHistoricoData->getProducto								($row['producto']);
				$DispoHistoricoData->getVariedadId								($row['variedad_id']);
				$DispoHistoricoData->getGradoId									($row['grado_id']);
	      	    $DispoHistoricoData->getTallosxBunch							($row['tallos_x_bunch']);
		        $DispoHistoricoData->getClasifica								($row['clasifica']);
		        $DispoHistoricoData->getCantidadBunch					    	($row['cantidad_bunch']);
				$DispoHistoricoData->getCantidadBunchDispoHistoriconible	  	($row['cantidad_bunch_DispoHistoriconible']);
				$DispoHistoricoData->getEdad	 								($row['edad']);
				
			return $DispoHistoricoData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

?>`