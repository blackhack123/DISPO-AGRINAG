<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GradoData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GradoDAO extends Conexion 
{
	private $table_name	= 'grado';

	/**
	 * Ingresar
	 *
	 * @param GradoData $GradoData
	 * @return array Retorna un Array $key el cual contiene el grado
	 */
	public function ingresar(GradoData $GradoData)
	{
		$key    = array(
				'id'						        => $GradoData->getId(),
		);
		$record = array(
				'id'		   		                => $GradoData->getId(),
				'nombre'		                    => $GradoData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$grado = $this->getEntityManager()->getConnection()->lastInsertGrado();
		return $grado;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GradoData $GradoData
	 * @return array Retorna un Array $key el cual contiene el grado
	 */
	public function modificar(GradoData $GradoData)
	{
		$key    = array(
				'id'						        => $GradoData->getId(),
		);
		$record = array(
				'id'								=> $GradoData->getId(),
				'nombre'		                    => $GradoData->getNombre()
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GradoData->getGrado();
	}//end function modificar

	/**
	 * Consultar
	 *
	 * @param string $grado
	 * @return GradoData|null
	 */	
	public function consultar($grado)
	{
		$AgenciaCargaData 		    = new GradoData();

		$sql = 	' SELECT grado.* '.
				' FROM grado '.
				' WHERE grado.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$grado);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){

				$GradoData->getId							($row['id']);				
				$GradoData->getNombre						($row['nombre']);
			return $GradoData;
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
		$sql = 	' SELECT grado.* '.
				' FROM grado '.
				' ORDER BY grado.orden';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		//return new ViewModel(array(result'=>$result));
		return $result;
	}//end function consultarTodos	
	
}//end class

?>