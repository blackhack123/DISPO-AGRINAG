<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\GrupoPrecioAuditoria;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GrupoPrecioAuditoriaDAO extends Conexion 
{
	private $table_name	= 'grupo_precio_auditoria';

	/**
	 * Ingresar
	 *
	 * @param GrupoPrecioAuditoria $GrupoPrecioAuditoria
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function ingresar(GrupoPrecioAuditoria $GrupoPrecioAuditoria)
	{
		$key    = array(
				'id'				=> $GrupoPrecioAuditoria->getId(),
		);
		$record = array(
				'id'											=> $GrupoPrecioAuditoria->getId(),
				'grupo_dispo_cab'		                		=> $GrupoPrecioAuditoria->getGrupoPrecioCab(),
				'variedad_id'		            				=> $GrupoPrecioAuditoria->geVariedadId(),
				'grado_id'                						=> $GrupoPrecioAuditoria->getGradoId(),
				'precio'        							 	=> $GrupoPrecioAuditoria->getPrecio(),
				'precio_oferta' 			               		=> $GrupoPrecioAuditoria->getPrecioOferta(),
				'precio_anterior'                				=> $GrupoPrecioAuditoria->getPrecioAnterior(),
				'precio_oferta_anterior'                		=> $GrupoPrecioAuditoria->getPrecioOfertaAnterior(),
				'fec_mod'                						=> $GrupoPrecioAuditoria->getFechaMod(),
				'usuario_id'                					=> $GrupoPrecioAuditoria->getUsuarioId()
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $Id;
		
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param GrupoPrecioAuditoria $GrupoPrecioAuditoria
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(GrupoPrecioAuditoria $GrupoPrecioAuditoria)
	{
		$key    = array(
				'id'		      		  			=> $GrupoPrecioAuditoria->getId()
				
		);
		$record = array(
				'id'											=> $GrupoPrecioAuditoria->getId(),
				'grupo_precio_cab'		                		=> $GrupoPrecioAuditoria->getGrupoPrecioCab(),
				'variedad_id'		            				=> $GrupoPrecioAuditoria->geVariedadId(),
				'grado_id'                						=> $GrupoPrecioAuditoria->getGradoId(),
				'precio'        							 	=> $GrupoPrecioAuditoria->getPrecio(),
				'precio_oferta' 			               		=> $GrupoPrecioAuditoria->getPrecioOferta(),
				'precio_anterior'                				=> $GrupoPrecioAuditoria->getPrecioAnterior(),
				'precio_oferta_anterior'                		=> $GrupoPrecioAuditoria->getPrecioOfertaAnterior(),
				'fec_mod'                						=> $GrupoPrecioAuditoria->getFechaMod(),
				'usuario_id'                					=> $GrupoPrecioAuditoria->getUsuarioId()
		);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $GrupoPrecioAuditoria->getId();
	}//end function modificar


	/**
	 * 
	 * @param int $id
	 * @return \Dispo\Data\GrupoPrecioAuditoria|NULL
	 */
	public function consultar($grupo_dispo_det, $variedad_id, $grado_id)
	{
		$GrupoPrecioAuditoria 		    = new GrupoPrecioAuditoria();

		$sql = 	' SELECT grupo_precio_auditoria.* '.
				' FROM grupo_pre '.
				' WHERE grupo_precio_auditoria.id = :id ';


		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
		if($row){
			$GrupoPrecioAuditoria->setId										($row['id']);
			$GrupoPrecioAuditoria->setGrupoPrecioCab							($row['grupo_precio_cab']);
			$GrupoPrecioAuditoria->setVariedadId		    					($row['variedad_id']);
			$GrupoPrecioAuditoria->setGradoId									($row['grado_id']);
			$GrupoPrecioAuditoria->setPrecio									($row['precio']);
			$GrupoPrecioAuditoria->setPrecioOferta    							($row['precio_oferta']);
			$GrupoPrecioAuditoria->setPrecioOfertaAnterior						($row['precio_oferta_anterior']);
			$GrupoPrecioAuditoria->setFecMod									($row['fec_mod']);
			$GrupoPrecioAuditoria->setUsuarioId									($row['usuario_id']);
			
			return $GrupoPrecioAuditoria;
		}else{
			return null;
		}//end if

	}//end function consultar


}//end class
?>