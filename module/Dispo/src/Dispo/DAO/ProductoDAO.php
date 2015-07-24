<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ProductoData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductoDAO extends Conexion 
{
	private $table_name	= 'producto';

	/**
	 * Ingresar
	 *
	 * @param ProductoData $ProductoData
	 * @return array Retorna un Array $id el cual contiene el id
	 */
	public function ingresar(ProductoData $ProductoData)
	{
		$id    = array(
				'id'						        => $ProductoData->getid(),
		);
		$record = array(
				'id'								=> $ProductoData->getid(),
				'nombre'		                    => $ProductoData->getNombre(),
				'nombre_tec'	                    => $ProductoData->getNombreTec(),
				'nombre_fam'		                => $ProductoData->getNombreFam(),
				'tariff'		                    => $ProductoData->getTariff(),
				'tariff1'		                    => $ProductoData->getTariff1(),
				'precio_adu'	                    => $ProductoData->getPrecioAdu(),
				'precio'		                    => $ProductoData->getPrecio(),
				'unidad_id'		                    => $ProductoData->getUnidadId(),
				'unidad_caj'		                => $ProductoData->getUnidadCaj(),
				'por_dump'		                    => $ProductoData->getPorDump(),
				'por_nacional'		                => $ProductoData->getPorNacional(),
				'secuencia'		                    => $ProductoData->getSecuencia(),
				'estado'		                    => $ProductoData->getEstado(),
				'diasA'		                    	=> $ProductoData->getDiasA(),
				'diasB'		                    	=> $ProductoData->getDiasB(),
				'diasC'		                    	=> $ProductoData->getDiasC(),
				'diasM'		                    	=> $ProductoData->getDiasM(),
				'diasN'		               		    => $ProductoData->getDiasN(),
				'solido'		                    => $ProductoData->getSolido(),
				'fec_ingreso'	                    => $ProductoData->getFecIngreso(),
				'fec_modifica'	                    => $ProductoData->getFecModifica(),
				'usuario_ing_id'                    => $ProductoData->getUsuarioIngId(),
				'usuario_mod_id'		            => $ProductoData->getUsuarioModId(),
				'sincronizado'		                => $ProductoData->getSincronizado(),
				'fec_sincronizado'		            => $ProductoData->getFecSincronizado()
				
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertid();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param ProductoData $ProductoData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(ProductoData $ProductoData)
	{
		$key    = array(
				'id'						        => $ProductoData->getid(),
		);
		$record = array(
				'id'								=> $ProductoData->getid(),
				'nombre'		                    => $ProductoData->getNombre(),
				'nombre_tec'	                    => $ProductoData->getNombreTec(),
				'nombre_fam'		                => $ProductoData->getNombreFam(),
				'tariff'		                    => $ProductoData->getTariff(),
				'tariff1'		                    => $ProductoData->getTariff1(),
				'precio_adu'	                    => $ProductoData->getPrecioAdu(),
				'precio'		                    => $ProductoData->getPrecio(),
				'unidad_id'		                    => $ProductoData->getUnidadId(),
				'unidad_caj'		                => $ProductoData->getUnidadCaj(),
				'por_dump'		                    => $ProductoData->getPorDump(),
				'por_nacional'		                => $ProductoData->getPorNacional(),
				'secuencia'		                    => $ProductoData->getSecuencia(),
				'estado'		                    => $ProductoData->getEstado(),
				'diasA'		                    	=> $ProductoData->getDiasA(),
				'diasB'		                    	=> $ProductoData->getDiasB(),
				'diasC'		                    	=> $ProductoData->getDiasC(),
				'diasM'		                    	=> $ProductoData->getDiasM(),
				'diasN'		               		    => $ProductoData->getDiasN(),
				'solido'		                    => $ProductoData->getSolido(),
				'fec_ingreso'	                    => $ProductoData->getFecIngreso(),
				'fec_modifica'	                    => $ProductoData->getFecModifica(),
				'usuario_ing_id'                    => $ProductoData->getUsuarioIngId(),
				'usuario_mod_id'		            => $ProductoData->getUsuarioModId(),
				'sincronizado'		                => $ProductoData->getSincronizado(),
				'fec_sincronizado'		            => $ProductoData->getFecSincronizado()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ProductoData->getid();
	}//end function modificar


	/**
	 * Consultar
	 *
	 * @param string $id
	 * @return ProductoData|null
	 */	
	public function consultar($id)
	{
		$ProductoData 		    = new ProductoData();

		$sql = 	' SELECT Producto.* '.
				' FROM Producto '.
				' WHERE Producto.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row)
		{
			$ProductoData->getid				($row['id']);				
			$ProductoData->getNombre 			($row['nombre']);
			$ProductoData->getNombreTec 		($row['nombre_tec']);
			$ProductoData->getNombreFam 		($row['nombre_fam']);
			$ProductoData->getTariff 			($row['tariff']);
			$ProductoData->getTariff1 			($row['tariff1']);
			$ProductoData->getPrecioAdu 		($row['precio_adu']);
			$ProductoData->getPrecio 			($row['precio']);
			$ProductoData->getUnidadId 			($row['unidad_id']);
			$ProductoData->getUnidadCaj			($row['unidad_caj']);
			$ProductoData->getPorDump 			($row['por_dump']);
			$ProductoData->getPorNacional 		($row['por_nacional']);
			$ProductoData->getSecuencia 		($row['secuencia']);
			$ProductoData->getEstado 			($row['estado']);
			$ProductoData->getDiasA 			($row['diasA']);
			$ProductoData->getDiasB 			($row['diasB']);
			$ProductoData->getDiasC 			($row['diasC']);
			$ProductoData->getDiasM 			($row['diasM']);
			$ProductoData->getDiasN 			($row['diasN']);
			$ProductoData->getSolido 			($row['solido']);
			$ProductoData->getFecIngreso 		($row['fec_ingreso']);
			$ProductoData->getFecModifica		($row['fec_modifica']);
			$ProductoData->getUsuarioIngId		($row['usuario_ing_id']);
			$ProductoData->getUsuarioModId 		($row['usuario_mod_id']);
			$ProductoData->getSincronizado		($row['sincronizado']);
			$ProductoData->getFecSincronizado 	($row['fec_sincronizado']);
			return $ProductoData;
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
		$sql = 	' SELECT Producto.* '.
				' FROM Producto '.
				' ORDER BY nombre ';
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		//Elimina los espacios
		foreach($result as &$reg)
		{
			$reg['nombre'] = trim($reg['nombre']);
		}//end foreach
	
		return $result;
	}//end function consultarTodos	
}//end class

?>