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
				'abreviatura'		  	            => $ClienteData->getAbreviatura(),
				//'grupo_precio_cab_id'		        => $ClienteData->getGrupoPrecioCabId(),
				'direccion'		            		=> $ClienteData->getDireccion(),
				'ciudad'		            		=> $ClienteData->getCiudad(),
				'estados_id'	            		=> $ClienteData->getEstadosId(),
				'estado_nombre'		            	=> $ClienteData->getEstadoNombre(),
				'pais_id'		            		=> $ClienteData->getPaisId(),
				'codigo_postal'		           		=> $ClienteData->getCodigoPostal(),
				'comprador'		           			=> $ClienteData->getComprador(),
				'telefono1'		            		=> $ClienteData->getTelefono1(),
				'telefono1_ext'		            	=> $ClienteData->getTelefono1Ext(),
				'telefono2'		            		=> $ClienteData->getTelefono2(),
				'telefono2_ext'		            	=> $ClienteData->getTelefono2Ext(),
				'fax1'		            			=> $ClienteData->getFax1(),
				'fax1_ext'		            		=> $ClienteData->getFax1Ext(),
				'fax2'		            			=> $ClienteData->getFax2(),
				'fax2_ext'		            		=> $ClienteData->getFax2Ext(),
				//'email'		            			=> $ClienteData->getEmail(),
				'usuario_vendedor_id'				=> $ClienteData->getUsuarioVendedorId(),
				'tc_limite_credito'					=> $ClienteData->getTcLimiteCredito(),
				'tc_interes'						=> $ClienteData->getTcInteres(),
				'est_credito_suspendido'			=> $ClienteData->getEstCreditoSuspendido(),
				'credito_suspendido_razon'			=> $ClienteData->getCreditoSuspendidoRazon(),
				'contacto'		            		=> $ClienteData->getContacto(),
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
				'pais_fue'		        			=> $ClienteData->getPaisFUE(),
				'facturacion_sri'		        	=> $ClienteData->getFacturacionSRI(),
				'porc_iva'							=> $ClienteData->getPorcIva(),
				'estado'                			=> $ClienteData->getEstado(),
				'incobrable'						=> $ClienteData->getIncobrable(),
				'cliente_especial'					=> $ClienteData->getClienteEspecial(),
				'envia_estadocta'		        	=> $ClienteData->getEnviaEstadoCta(),
				'formato_estado_cta'				=> $ClienteData->getFormatoEstadoCta(),
				'tipo_envio_estcta'					=>$ClienteData->getTipoEnvioEstCta(),
				'dia_semana'		        		=> $ClienteData->getDiaSemana(),
				'diacal_fecha1'		        		=> $ClienteData->getDiaCalFecha1(),
				'diacal_fecha2'		        		=> $ClienteData->getDiaCalFecha2(),
				'inmediato'		        			=> $ClienteData->getInmediato(),
				
				'tipo_cartera'		        		=> $ClienteData->getTipoCartera(),
				//'est_incobrable'					=> $ClienteData->getIncobrable(),
				'tipo_persona'		        		=> $ClienteData->getTipoPersona(),
				'ruc'		        				=> $ClienteData->getRuc(),
				'ciaf'		        				=> $ClienteData->getCiaf(),
				'moneda'		 			    	=> $ClienteData->getMoneda(),
				'tc_forma_pago'						=> $ClienteData->getTcFormaPago(),
				'tc_nro_cuotas'						=> $ClienteData->getTcNroCuotas(),
				'tc_plazo'							=> $ClienteData->getTcPlazo(),
				'tc_1er_cierre'						=> $ClienteData->getTc1erCierre(),
				'tc_1er_cierre_pago'				=> $ClienteData->getTc1erCierrePago(),
				'tc_2do_cierre'						=> $ClienteData->getTc2doCierre(),
				'tc_2do_cierre_pago'				=> $ClienteData->getTc2doCierrePago(),
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
				'abreviatura'		  	            => $ClienteData->getAbreviatura(),
				'grupo'		  	         		    => $ClienteData->getGrupo(),
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
				'tipo_cartera'		        		=> $ClienteData->getTipoCartera(),
				'usuario_vendedor_id'				=> $ClienteData->getUsuarioVendedorId(),
				'est_credito_suspendido'			=> $ClienteData->getEstCreditoSuspendido(),
				'credito_suspendido_razon'			=> $ClienteData->getCreditoSuspendidoRazon(),
				//'est_incobrable'					=> $ClienteData->getEstIncobrable(),
				'tipo_persona'		        		=> $ClienteData->getTipoPersona(),
				'ruc'		        				=> $ClienteData->getRuc(),
				'ciaf'		        				=> $ClienteData->getCiaf(),
				'moneda'		 			    	=> $ClienteData->getMoneda(),
				'facturacion_sri'		        	=> $ClienteData->getFacturacionSRI(),
				'pais_fue'		        			=> $ClienteData->getPaisFUE(),
				'tc_interes'						=> $ClienteData->getTcInteres(),
				'tc_limite_credito'					=> $ClienteData->getTcLimiteCredito(),
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
				'envia_estadocta'		        	=> $ClienteData->getEnviaEstadoCta(),
				'tipo_envio_estcta'					=>$ClienteData->getTipoEnvioEstCta(),
				'dia_semana'		        		=> $ClienteData->getDiaSemana(),
				'diacal_fecha2'		        		=> $ClienteData->getDiaCalFecha2(),
				'diacal_fecha1'		        		=> $ClienteData->getDiaCalFecha1(),
				'inmediato'		        			=> $ClienteData->getInmediato(),
				//'grupo_precio_cab_id'		        => $ClienteData->getGrupoPrecioCabId(),
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
				 	$ClienteData->setId                     ($row['id']);               
               	  	$ClienteData->setNombre                 ($row['nombre']);
               	  	$ClienteData->setAbreviatura            ($row['abreviatura']);
               	  	//$ClienteData->setGrupoPrecioCabId		($row['grupo_precio_cab_id']);
                  	$ClienteData->setDireccion              ($row['direccion']);
                  	$ClienteData->setCiudad                 ($row['ciudad']);
                  	$ClienteData->setEstadosId              ($row['estados_id']);
                  	$ClienteData->setEstadoNombre           ($row['estado_nombre']);
                  	$ClienteData->setPaisId                 ($row['pais_id']);
                  	$ClienteData->setCodigoPostal           ($row['codigo_postal']);
                  	$ClienteData->setComprador              ($row['comprador']);
                  	$ClienteData->setTelefono1              ($row['telefono1']);
                  	$ClienteData->setTelefono1Ext           ($row['telefono1_ext']);
                  	$ClienteData->setTelefono2              ($row['telefono2']);
                  	$ClienteData->setTelefono2Ext           ($row['telefono2_ext']);
                  	$ClienteData->setFax1                   ($row['fax1']);
                  	$ClienteData->setFax1Ext                ($row['fax1_ext']);
                 	$ClienteData->setFax2                   ($row['fax2']);
                 	$ClienteData->setFax2Ext                ($row['fax2_ext']);
                  	//$ClienteData->setEmail                  ($row['email']);
                 	$ClienteData->setUsuarioVendedorId      ($row['usuario_vendedor_id']);
                 	$ClienteData->setTcLimiteCredito    	($row['tc_limite_credito']);
                 	$ClienteData->setTcInteres      		($row['tc_interes']);
                 	$ClienteData->setEstCreditoSuspendido   ($row['est_credito_suspendido']);
                 	$ClienteData->setCreditoSuspendidoRazon ($row['credito_suspendido_razon']);
                  	$ClienteData->setContacto               ($row['contacto']);
                  	$ClienteData->setClienteFacturaId       ($row['cliente_factura_id']);
                  	$ClienteData->setTelefonoFact1      	($row['telefono_fact1']);
                  	$ClienteData->setTelefonoFact1Ext      	($row['telefono_fact1_ext']);
                  	$ClienteData->setTelefonoFact2      	($row['telefono_fact2']);
                  	$ClienteData->setTelefonoFact2Ext      	($row['telefono_fact2_ext']);
                  	$ClienteData->setFaxFact1      			($row['fax_fact1']);
                  	$ClienteData->setFaxFact1Ext      		($row['fax_fact1_ext']);
                  	$ClienteData->setFaxFact2      			($row['fax_fact2']);
                  	$ClienteData->setFaxFact2Ext      		($row['fax_fact2_ext']);
                  	$ClienteData->setEmailFactura      		($row['email_factura']);
                  	$ClienteData->setPaisFUE				($row['pais_fue']);
                  	$ClienteData->setFacturacionSRI         ($row['facturacion_sri']);
                  	$ClienteData->setPorcIva     			($row['porc_iva']);
                  	$ClienteData->setEstado    				($row['estado']);
                  	$ClienteData->setIncobrable      		($row['incobrable']);
                  	$ClienteData->setClienteEspecial      	($row['cliente_especial']);
                  	$ClienteData->setEnviaEstadoCta         ($row['envia_estadocta']);
                  	$ClienteData->setFormatoEstadoCta      	($row['formato_estado_cta']);
                  	$ClienteData->setTipoEnvioEstCta	    ($row['tipo_envio_estcta']);
                  	$ClienteData->setDiaSemana			    ($row['dia_semana']);
                  	$ClienteData->setDiaCalFecha1		    ($row['diacal_fecha1']);
                  	$ClienteData->setDiaCalFecha2		    ($row['diacal_fecha2']);
                  	$ClienteData->setInmediato		       	($row['inmediato']);
                  	$ClienteData->setTipoCartera            ($row['tipo_cartera']);
                  //$ClienteData->setEstIncobrable      	($row['est_incobrable']);
                  	$ClienteData->setTipoPersona            ($row['tipo_persona']);
                  	$ClienteData->setRuc           			($row['ruc']);
                  	$ClienteData->setCiaf            		($row['ciaf']);
                  	$ClienteData->setMoneda		            ($row['moneda']);
                  	$ClienteData->setTcFormaPago      		($row['tc_forma_pago']);
                  	$ClienteData->setTcNroCuotas      		($row['tc_nro_cuotas']);
                  	$ClienteData->setTcPlazo	      		($row['tc_plazo']);
                  	$ClienteData->setTc1erCierre      		($row['tc_1er_cierre']);
                  	$ClienteData->setTc1erCierrePago      	($row['tc_1er_cierre_pago']);
                  	$ClienteData->setTc2doCierre      		($row['tc_2do_cierre']);
                  	$ClienteData->setTc2doCierrePago      	($row['tc_2do_cierre_pago']);
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
		
			$sql = 	' SELECT cliente.*, pais.nombre as pais_nombre, estados.nombre as nombre_estados '.
							' FROM cliente  LEFT JOIN pais '.
							'		               ON pais.id      = cliente.pais_id '.	
							'				LEFT JOIN estados '.
							'						ON cliente.estados_id = estados.id '.
							' WHERE 1 = 1 ';
		
		if (!empty($condiciones['criterio_busqueda']))
		{
			$sql = $sql." and pais.nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.nombre like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.id like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.direccion like '%".$condiciones['criterio_busqueda']."%'".
					"      or cliente.ciudad like '%".$condiciones['criterio_busqueda']."%'";
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
	
	
	/**
	 *
	 * 	En las condiciones se puede pasar los siguientes criterios de busqueda:
	 *   1) criterio_busqueda,  utilizado para buscar en nombre, id, direccion, telefono
	 *   2) estado
	 *   3) sincronizado
	 *
	 * @param array $condiciones
	 * @return array
	 */
	public function ConsultarClienteFactura($condiciones)
	{
	
		$sql = 	' SELECT cliente.id, cliente.nombre '.
				' FROM cliente   '.
				" WHERE nombre like '%".$condiciones['cliente_factura_id']."%'".
				" or id like '%".$condiciones['cliente_factura_id']."%'";
	
		$stmt = $this->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();  //Se utiliza el fecth por que es un registro
		
		return $result;
	}
	
	
	
}//end class

?>