<?php

namespace Dispo\DAO;
use Doctrine\ORM\EntityManager,
	Application\Classes\Conexion;
use Dispo\Data\ClienteData;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ClienteDAO extends Conexion 
{
	private $table_name	= 'cliente';

	/**
	 * Ingresar
	 *
	 * @param ClienteData $ClienteData
	 * @return array Retorna un Array $id el cual contiene el id
	 */
	public function ingresar(ClienteData $ClienteData)
	{
		$id   = array(
				'id'						        => $ClienteData->getId(),
		);
		$record = array(
				'id'								=> $ClienteData->getId(),
				'nombre'		                    => $ClienteData->getNombre(),
				'direccion'		            		=> $ClienteData->getDireccion(),
				'ciudad'		            		=> $ClienteData->getCiudad(),
				'estados_id'	            		=> $ClienteData->getEstadosId(),
				'pais_id'		            		=> $ClienteData->getPaisId(),
				'codigo_postal'		           		=> $ClienteData->getCodigoPostal(),
				'estado_nombre'		            	=> $ClienteData->getEstadoNombre(),
				'telefono1'		            		=> $ClienteData->getTelefono1(),
				'telefono1_ext'		            	=> $ClienteData->getTelefono1Ext(),
				'telefono2'		            		=> $ClienteData->getTelefono2(),
				'telefono2_ext'		            	=> $ClienteData->getTelefono2Ext(),
				'fax1'		            			=> $ClienteData->getFax1(),
				'fax1_ext'		            		=> $ClienteData->getFax1Ext(),
				'fax2'		            			=> $ClienteData->getFax2(),
				'fax2_ext'		            		=> $ClienteData->getFax2Ext(),
				'email'		            			=> $ClienteData->getEmail(),
				'contacto'		            		=> $ClienteData->getContacto(),
				'comprador'		           			=> $ClienteData->getComprador(),
				'cliente_factura_id'		        => $ClienteData->getClienteFacturaId(),
				'telefono_fact1'		            => $ClienteData->getTelefonoFact1(),
				'telefono_fact1_ext'		        => $ClienteData->getTelefonoFact1Ext(),
				'telefono_fact2'		            => $ClienteData->getTelefonoFact2(),
				'telefono_fact2_ext'		        => $ClienteData->getTelefonoFact2Ext(),
				'fax_fact1'		   			        => $ClienteData->getFaxFact1(),
				'fax_fact1_ext'		        		=> $ClienteData->getFaxFact1Ext(),
				'fax_fact2'		            		=> $ClienteData->getTelefonoFact2(),
				'fax_fact2_ext'		        		=> $ClienteData->getTelefonoFact2Ext(),
				'email_factura'		        		=> $ClienteData->getEmailFactura(),
				'usuario_vendedor_id'				=> $ClienteData->getUsuarioVendedorId(),
				'est_credito_suspendido'			=> $ClienteData->getEstCreditoSuspendido(),
				'credito_suspendido_razon'			=> $ClienteData->getCreditoSuspendidoRazon(),
				'est_incobrable'					=> $ClienteData->getEstIncobrable(),
				'tc_interes'						=> $ClienteData->getTcInteres(),
				'tc_ limite_credito'				=> $ClienteData->getTcLimiteCredito(),
				'tc_forma_pago'						=> $ClienteData->getTcFormaPago(),
				'tc_nro_cuotas'						=> $ClienteData->getTcNroCuotas(),
				'tc_plazo'							=> $ClienteData->getTcPlazo(),
				'tc_1er_cierre'						=> $ClienteData->getTc1erCierre(),
				'tc_1er_cierre_pago'				=> $ClienteData->getTc1erCierrePago(),
				'tc_2do_cierre'						=> $ClienteData->getTc2doCierre(),
				'tc_2do_cierre_pago'				=> $ClienteData->getTc2doCierrePago(),
				'formato_estado_cta'				=> $ClienteData->getFormatoEstadoCta(),
				'porc_iva'							=> $ClienteData->getPorcIva(),
				'cliente_especial'					=> $ClienteData->getClienteEspecial(),
				'incobrable'						=> $ClienteData->getIncobrable(),
				'grupo_precio_cab_id'		        => $ClienteData->getGrupoPrecioCabId(),
				'estado'                			=> $ClienteData->getEstado(),
				'fec_ingreso'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_ing_id'                	=> $ClienteData->getUsuarioIngId(),
				'sincronizado'                		=> 0
		);
		$this->getEntityManager()->getConnection()->insert($this->table_name, $record);
		//$id = $this->getEntityManager()->getConnection()->lastInsertId();
		return $id;
	}//end function ingresar



	/**
	 * Modificar
	 *
	 * @param ClienteData $ClienteData
	 * @return array Retorna un Array $key el cual contiene el id
	 */
	public function modificar(ClienteData $ClienteData)
	{
		$key    = array(
				'id'						        => $ClienteData->getId(),
		);
		$record = array(
						'id'								=> $ClienteData->getId(),
				'nombre'		                    => $ClienteData->getNombre(),
				'direccion'		            		=> $ClienteData->getDireccion(),
				'ciudad'		            		=> $ClienteData->getCiudad(),
				'estados_id'	            		=> $ClienteData->getEstadosId(),
				'pais_id'		            		=> $ClienteData->getPaisId(),
				'codigo_postal'		           		=> $ClienteData->getCodigoPostal(),
				'estado_nombre'		            	=> $ClienteData->getEstadoNombre(),
				'telefono1'		            		=> $ClienteData->getTelefono1(),
				'telefono1_ext'		            	=> $ClienteData->getTelefono1Ext(),
				'telefono2'		            		=> $ClienteData->getTelefono2(),
				'telefono2_ext'		            	=> $ClienteData->getTelefono2Ext(),
				'fax1'		            			=> $ClienteData->getFax1(),
				'fax1_ext'		            		=> $ClienteData->getFax1Ext(),
				'fax2'		            			=> $ClienteData->getFax2(),
				'fax2_ext'		            		=> $ClienteData->getFax2Ext(),
				'email'		            			=> $ClienteData->getEmail(),
				'contacto'		            		=> $ClienteData->getContacto(),
				'comprador'		           			=> $ClienteData->getComprador(),
				'cliente_factura_id'		        => $ClienteData->getClienteFacturaId(),
				'telefono_fact1'		            => $ClienteData->getTelefonoFact1(),
				'telefono_fact1_ext'		        => $ClienteData->getTelefonoFact1Ext(),
				'telefono_fact2'		            => $ClienteData->getTelefonoFact2(),
				'telefono_fact2_ext'		        => $ClienteData->getTelefonoFact2Ext(),
				'fax_fact1'		   			        => $ClienteData->getFaxFact1(),
				'fax_fact1_ext'		        		=> $ClienteData->getFaxFact1Ext(),
				'fax_fact2'		            		=> $ClienteData->getTelefonoFact2(),
				'fax_fact2_ext'		        		=> $ClienteData->getTelefonoFact2Ext(),
				'email_factura'		        		=> $ClienteData->getEmailFactura(),
				'usuario_vendedor_id'				=> $ClienteData->getUsuarioVendedorId(),
				'est_credito_suspendido'			=> $ClienteData->getEstCreditoSuspendido(),
				'credito_suspendido_razon'			=> $ClienteData->getCreditoSuspendidoRazon(),
				'est_incobrable'					=> $ClienteData->getEstIncobrable(),
				'tc_interes'						=> $ClienteData->getTcInteres(),
				'tc_ limite_credito'				=> $ClienteData->getTcLimiteCredito(),
				'tc_forma_pago'						=> $ClienteData->getTcFormaPago(),
				'tc_nro_cuotas'						=> $ClienteData->getTcNroCuotas(),
				'tc_plazo'							=> $ClienteData->getTcPlazo(),
				'tc_1er_cierre'						=> $ClienteData->getTc1erCierre(),
				'tc_1er_cierre_pago'				=> $ClienteData->getTc1erCierrePago(),
				'tc_2do_cierre'						=> $ClienteData->getTc2doCierre(),
				'tc_2do_cierre_pago'				=> $ClienteData->getTc2doCierrePago(),
				'formato_estado_cta'				=> $ClienteData->getFormatoEstadoCta(),
				'porc_iva'							=> $ClienteData->getPorcIva(),
				'cliente_especial'					=> $ClienteData->getClienteEspecial(),
				'incobrable'						=> $ClienteData->getIncobrable(),
				'grupo_precio_cab_id'		        => $ClienteData->getGrupoPrecioCabId(),
				'estado'                			=> $ClienteData->getEstado(),
				'fec_modifica'                		=> \Application\Classes\Fecha::getFechaHoraActualServidor(),
				'usuario_mod_id'                	=> $ClienteData->getUsuarioModId()
				
				);
		$this->getEntityManager()->getConnection()->update($this->table_name, $record, $key);
		return $ClienteData->getId();
	}//end function modificar


	/**
	 * Consultar
	 * 
	 * @param string $id
	 * @param int $resultType
	 * @return \Dispo\Data\ClienteData|NULL|array
	 */
	public function consultar($id, $resultType = \Application\Constants\ResultType::OBJETO)
	{
		switch ($resultType)
		{
			case \Application\Constants\ResultType::OBJETO:
				$ClienteData 		    = new ClienteData();
				
				$sql = 	' SELECT cliente.* '.
						' FROM cliente '.
						' WHERE cliente.id = :id ';
				
				$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
				$stmt->bindValue(':id',$id);
				$stmt->execute();
				$row = $stmt->fetch();  //Se utiliza el fecth por que es un registro
				if($row){
				 	$ClienteData->getId                     ($row['id']);               
               	  	$ClienteData->getNombre                 ($row['nombre']);
                  	$ClienteData->getDireccion              ($row['direccion']);
                  	$ClienteData->getCiudad                 ($row['ciudad']);
                  	$ClienteData->getEstadosId              ($row['estados_id']);
                  	$ClienteData->getPaisId                 ($row['pais_id']);
                  	$ClienteData->getCodigoPostal           ($row['codigo_postal']);
                  	$ClienteData->getEstadoNombre           ($row['estado_nombre']);
                  	$ClienteData->getTelefono1              ($row['telefono1']);
                  	$ClienteData->getTelefono1Ext           ($row['telefono1_ext']);
                  	$ClienteData->getTelefono2              ($row['telefono2']);
                  	$ClienteData->getTelefono2Ext           ($row['telefono2_ext']);
                  	$ClienteData->getFax1                   ($row['fax1']);
                  	$ClienteData->getFax1Ext                ($row['fax1_ext']);
                 	$ClienteData->getFax2                   ($row['fax2']);
                 	$ClienteData->getFax2Ext                ($row['fax2_ext']);
                  	$ClienteData->getEmail                  ($row['email']);
                  	$ClienteData->getContacto               ($row['contacto']);
                  	$ClienteData->getComprador              ($row['comprador']);
                  	$ClienteData->getClienteFacturaId       ($row['cliente_factura_id']);
                  	$ClienteData->getTelefonoFact1      	($row['telefono_fact1']);
                  	$ClienteData->getTelefonoFact1Ext      	($row['telefono_fact1_ext']);
                  	$ClienteData->getTelefonoFact2      	($row['telefono_fact2']);
                  	$ClienteData->getTelefonoFact2Ext      	($row['telefono_fact2_ext']);
                  	$ClienteData->getFaxFact1      			($row['fax_fact1']);
                  	$ClienteData->getFaxFact1Ext      		($row['fax_fact1_ext']);
                  	$ClienteData->getFaxFact2      			($row['fax_fact2']);
                  	$ClienteData->getFaxFact2Ext      		($row['fax_fact2_ext']);
                  	$ClienteData->getEmailFactura      		($row['email_factura']);
                  	$ClienteData->getUsuarioVendedorId      ($row['usuario_vendedor_id']);
                  	$ClienteData->getEstCreditoSuspendido   ($row['est_credito_suspendido']);
                  	$ClienteData->getCreditoSuspendidoRazon ($row['credito_suspendido_razon']);
                  	$ClienteData->getEstIncobrable      	($row['est_incobrable']);
                  	$ClienteData->getTcInteres      		($row['tc_interes']);
                  	$ClienteData->getTcLimiteCredito    	($row['tc_ limite_credito']);
                  	$ClienteData->getTcFormaPago      		($row['tc_forma_pago']);
                  	$ClienteData->getTcNroCuotas      		($row['tc_nro_cuotas']);
                  	$ClienteData->getTcPlazo	      		($row['tc_plazo']);
                  	$ClienteData->getTc1erCierre      		($row['tc_1er_cierre']);
                  	$ClienteData->getTc1erCierrePago      	($row['tc_1er_cierre_pago']);
                  	$ClienteData->getTc2doCierre      		($row['tc_2do_cierre']);
                  	$ClienteData->getTc2doCierrePago      	($row['tc_2do_cierre_pago']);
                  	$ClienteData->getFormatoEstadoCta      	($row['formato_estado_cta']);
                  	$ClienteData->getPorcIva     			($row['porc_iva']);
                  	$ClienteData->getClienteEspecial      	($row['cliente_especial']);
                  	$ClienteData->getIncobrable      		($row['incobrable']);
                  	$ClienteData->getGrupoPrecioCabId       ($row['grupo_precio_cab_id']);
					$ClienteData->setEstado    				($row['estado']);
					$ClienteData->setFecIngreso   			($row['fec_ingreso']);
					$ClienteData->setFecModifica  			($row['fec_modifica']);
					$ClienteData->setUsuarioIngId			($row['usuario_ing_id']);
					$ClienteData->setUsuarioModId			($row['usuario_mod_id']);
					$ClienteData->setSincronizado			($row['sincronizado']);
					$ClienteData->setFecSincronizado		($row['fec_sincronizado']);
				
					return $ClienteData;
				}else{
					return null;
				}//end if				
				break;
				
			case \Application\Constants\ResultType::MATRIZ:
				$sql = 	' SELECT cliente.*, usuario_ing.username as usuario_ing_user_name, usuario_mod.username as usuario_mod_user_name  '.
						' FROM cliente LEFT JOIN usuario as usuario_ing '.
						'                           ON usuario_ing.id = cliente.usuario_ing_id '.
						'					 LEFT JOIN usuario as usuario_mod '.
						'                           ON usuario_mod.id = cliente.usuario_mod_id '.						
						' WHERE cliente.id = :id ';
				
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
	 * @return array
	 */
	public function consultarTodo()
	{
		$sql = 	' SELECT cliente.* '.
				' FROM cliente ';

		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}//end function consultarTodo

	
	
	public function consultarUsuarioAsignado()
	{
		$sql = 	' SELECT distinct cliente.id, TRIM(cliente.nombre) as nombre '.
				' FROM cliente INNER JOIN usuario '.
				'                 ON usuario.cliente_id = cliente.id '.
				' ORDER BY cliente.nombre';
		
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}//end function consultarUsuarioAsignado
	
	
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
		
			$sql = 	' SELECT cliente.*, pais.nombre as pais_nombre '.
							' FROM cliente  LEFT JOIN pais '.
							'		               ON pais.id      = cliente.pais_id '.	
							' WHERE 1 = 1 ';
		
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and (pais.nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.id like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.direccion like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.ciudad like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.telefono1 like '%".$condiciones['criterio_busqueda']."%')";
		}//end if
		
		
	
		if (!empty($condiciones['estado']))
		{
			$sql = $sql." and estado = '".$condiciones['estado']."'";
		}//end if
	
	
		if (isset($condiciones['sincronizado']))
		{
			if ($condiciones['sincronizado']!='')
			{
				$sql = $sql." and sincronizado = ".$condiciones['sincronizado'];
			}//end if
		}//end if
		$sql=$sql." order by cliente.nombre";
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
	
		return $result;
	}//end function listado

}//end class

?>