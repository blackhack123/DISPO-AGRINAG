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
				'id'						        => $VariedadData->getId(),
		);
		$record = array(
				'id'								=> $VariedadData->getId(),
				'nombre'		                    => $VariedadData->getNombre(),
				'colorbase'		                    => $VariedadData->getColorBase()
				
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
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
				'id'						        => $VariedadData->getId(),
		);
		$record = array(
				'id'								=> $VariedadData->getId(),
				'nombre'		                    => $VariedadData->getNombre(),
				'colorbase'		                    => $VariedadData->getColorBase()
				
				
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $VariedadData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return VariedadData|null
	 */	
	public function consultar($id)
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

				$VariedadData->setId							($row['id']);				
				$VariedadData->setNombre 						($row['nombre']);
				$VariedadData->setColorBase 					($row['colorbase']);
			return $VariedadData;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class

?>