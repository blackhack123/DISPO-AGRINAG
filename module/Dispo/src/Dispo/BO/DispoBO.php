<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Seguridad\DAO\UsuarioDAO;
use Dispo\DAO\DispoDAO;
use Dispo\DAO\TipoCajaMatrizDAO;
use Dispo\DAO\PedidoCabDAO;
use Dispo\DAO\PedidoDetDAO;
use Dispo\DAO\VariedadDAO;
use Dispo\DAO\GrupoPrecioDetDAO;
use Dispo\DAO\GrupoPrecioOfertaDAO;



class DispoBO extends Conexion
{


	/**
	 * Obtiene la dispo
	 * 
	 * @param string $cliente_id
	 * @param int $usuario_id
	 * @param int $marcacion_sec
	 * @param string $tipo_caja_id
	 * @param string $variedad_id
	 * @param int $grado_id
	 * @param boolean $get_fincas
	 * @param boolean $rebajar_cajas_pedido
	 * @return array
	 */
	function getDispo($cliente_id, $usuario_id, $marcacion_sec, $tipo_caja_id = null, $variedad_id = null, $grado_id = null, 
					  $get_fincas = false, $rebajar_cajas_pedido = true, $validar_variedad_faltante = false)
	{	
		$UsuarioDAO				= new UsuarioDAO();
		$DispoDAO				= new DispoDAO();
		$TipoCajaMatrizDAO 		= new TipoCajaMatrizDAO();
		$PedidoDetDAO			= new PedidoDetDAO();
		$VariedadDAO			= new VariedadDAO();
		
/*		$this->getEntityManager()->getConnection()->beginTransaction();
		try 
		{
*/			$UsuarioDAO->setEntityManager				($this->getEntityManager());
			$DispoDAO->setEntityManager					($this->getEntityManager());
			$TipoCajaMatrizDAO->setEntityManager		($this->getEntityManager());
			$PedidoDetDAO->setEntityManager				($this->getEntityManager());
			$VariedadDAO->setEntityManager				($this->getEntityManager());
			
			/**
			 * Consulta el GRUPO_DISPO_CAB_ID del usuario de cliente
			 */
			$row_usuario = $UsuarioDAO->consultarGrupoDispoCab($usuario_id);
			if (empty($row_usuario))
			{
				$result = array('respuesta_code' 	=> '01',
								'respuesta_msg'		=> 'Usuario no tiene asignado un grupo'
				);
				return $result;
			}else{
				$grupo_dispo_cab_id = $row_usuario['grupo_dispo_cab_id'];
				$inventario_id 		= $row_usuario['inventario_id'];
			}//end if
			
			
			/**
			 * Se obtiene el inventario para el cliente por proveedor y tipo de inventario especifico,
			 * por lo que dara los bunches disponibles, y posteriormente si existe una
			 * RESTRICCION en GRUPO_DISPO se aplica la regla para la dispo por grupo
			 */
			if (empty($inventario_id))
			{
				$result = array('respuesta_code' 	=> '02',
						'respuesta_msg'		=> 'Usuario no tiene inventario, comuniquese con su asesor'
				);
				return $result;
			}//end if			
			
			$result = $DispoDAO->consultarInventarioPorCliente($cliente_id, $inventario_id, $grupo_dispo_cab_id, $variedad_id, $grado_id);			
			
			/**
			 *Ajusta el stock de los bunch de las fincas para mostrar la dispo de acuerdo al GRUPO_DISPO_DET
			 *se debe de hacer un quiebre por variedad, grado y  finca, para ajustar la dispo segun las restricciones
			 */
			$variedad_id_ant 	= null;
			$grado_id_ant		= null;
			$grupo_dispo_det_cantidad_bunch_disponible = 0;
			//while($row = $result->fetch_array(MYSQLI_ASSOC))
			
			if (empty($result))
			{
				if ($validar_variedad_faltante)
				{
					$VariedadData = $VariedadDAO->consultar($variedad_id);
					$result = array('respuesta_code' 	=> '12',
									'respuesta_msg'		=> 'No existen cajas '.$tipo_caja_id .' para la variedad '.$VariedadData->getNombre().' grado '.$grado_id
									);
					unset($VariedadData);
					return $result;
				}//end if
			}//end if
			
			foreach($result as $row)
			{
				//$porcentaje = 100/100;
				if (empty($row['grupo_dispo_det_cantidad_bunch_disponible']))
				{
					$tot_bunch_disponibles          = 0;
					
					/*------------------------------------*/
					if (empty($tot_bunch_disponibles))
					{
						if ($validar_variedad_faltante)
						{						
							//$VariedadData = $VariedadDAO->consultar($variedad_id);
							$result = array('respuesta_code' 	=> '13',
									'respuesta_msg'		=> '.No existen cajas '.$tipo_caja_id .' para la variedad '.$row['variedad_nombre'].' grado '.$grado_id
							);
							//unset($VariedadData);
							return $result;
						}//end if
					}//end if
					/*------------------------------------*/					
					//$porcentaje = $row['grupo_dispo_det_cantidad_bunch_disponible']/100;
				}else{
					if ($row['grupo_dispo_det_cantidad_bunch_disponible'] > $row['tot_bunch_disponible'])
					{
						$tot_bunch_disponibles		= $row['tot_bunch_disponible'];
					}else{
						$tot_bunch_disponibles		= $row['grupo_dispo_det_cantidad_bunch_disponible'];
					}
				}//end if
			
				//Si varia la variedad_id y el grado_id se indica la cantidad de bunch disponibles del GRUPO DISPO
				if (($variedad_id_ant != $row['variedad_id'])||($grado_id_ant != $row['grado_id']))
				{
					if (empty($row['grupo_dispo_det_cantidad_bunch_disponible']))
					{
						$grupo_dispo_det_cantidad_bunch_disponible = 0;
					}else{
						$grupo_dispo_det_cantidad_bunch_disponible = $row['grupo_dispo_det_cantidad_bunch_disponible'];
					}//end if
			
					$variedad_id_ant	= $row['variedad_id'];
					$grado_id_ant 		= $row['grado_id'];
				}//end if
			
				$grupo_dispo_det_cantidad_bunch_disponible_ant = $grupo_dispo_det_cantidad_bunch_disponible;
			
				//configura la cantidad de bunch de la FINCA en relacion del GRUPO DISPO
				if ($grupo_dispo_det_cantidad_bunch_disponible > $row['tot_bunch_disponible'])
				{
					$tot_bunch_disponibles = $row['tot_bunch_disponible'];
					$grupo_dispo_det_cantidad_bunch_disponible = $grupo_dispo_det_cantidad_bunch_disponible - $row['tot_bunch_disponible'];
				}else{
					$tot_bunch_disponibles = $grupo_dispo_det_cantidad_bunch_disponible;
					$grupo_dispo_det_cantidad_bunch_disponible = 0;
				}//end if

				if (!empty($tot_bunch_disponibles))
				{
					$row_dispo = $row;
					$row_dispo['variedad_nombre']		= trim($row_dispo['variedad_nombre']);
					$row_dispo['proveedor_id']			= $row_dispo['proveedor_id'];
					$row_dispo['variedad_id']			= $row_dispo['variedad_id'];
					$row_dispo['grado_id']				= $row_dispo['grado_id'];
					$row_dispo['precio']				= $row_dispo['precio'];
					$row_dispo['precio_oferta']			= $row_dispo['precio_oferta'];					
					$row_dispo['grupo_dispo_det_cantidad_bunch_disponible']	= $grupo_dispo_det_cantidad_bunch_disponible_ant;
			
					//$row_dispo['tot_bunch_disponible'] 	= floor($row_dispo['tot_bunch_disponible']*$porcentaje);
					$row_dispo['tot_bunch_disponible'] 	= $tot_bunch_disponibles;
					$row_dispo['tallos_x_bunch']		= $row_dispo['tot_tallos_x_bunch'] / $row_dispo['veces_tallos_x_bunch'];
			
					//Los siguientes se llenaran mÃ¡s adelante
					$row_dispo['tipo_caja_origen_estado'] 	= NULL;
					$row_dispo['tipo_caja_origen_id']		= NULL;
					$row_dispo['tipo_caja_unds_bunch']		= NULL;
					$row_dispo['nro_cajas']					= NULL;
			
					$result_dispo[] = $row_dispo;
				}//end if
			}//end foreach
			
			foreach($result_dispo as &$row_dispo)
			{
				$row = $TipoCajaMatrizDAO->consultaPorInventarioPorMarcacionPorVariedadPorGrado($inventario_id, $marcacion_sec, $row_dispo['variedad_id'], $row_dispo['grado_id'], $tipo_caja_id);
				
				if(!empty($row)) {
					//echo("<pre>");var_dump($row);echo("</pre>"); //DEBUG
					$row_dispo['tipo_caja_id'] 				= $row['tipo_caja_id'];
					$row_dispo['tipo_caja_origen_estado'] 	= $row['tipo_caja_origen_estado'];
					$row_dispo['tipo_caja_origen_id']		= $row['tipo_caja_origen_id'];
					$row_dispo['tipo_caja_unds_bunch']		= $row['tipo_caja_unds_bunch'];
					$row_dispo['nro_cajas']					= floor($row_dispo['tot_bunch_disponible']/$row['tipo_caja_unds_bunch']);
				
					//obtiene la dispo por proveedores
					if ($get_fincas == true)
					{
						$row_dispo_temp								= null;
						$row_dispo_temp['proveedor_id'] 			= $row_dispo['proveedor_id'];
						$row_dispo_temp['tot_bunch_disponible'] 	= $row_dispo['tot_bunch_disponible'];
						$row_dispo_temp['nro_cajas'] 				= $row_dispo['nro_cajas'];
						$proveedores_dispo[] = $row_dispo_temp;
					}//end if
				}else{
					$result = array('respuesta_code' 	=> '03',
							'respuesta_msg'			=> 'Error, no tiene caja disponible'
					);
					//throw new Exception('Error, no tiene caja disponible');
					return $result;
				}//end if				
			}//end foreach

			
			/**
			 * Consolida las cajas de los proveedores (a nivel FINCAS)
			 */
			$result_consolidado = NULL;
			$bd_1era_vez		= true;
			$variedad_id_ant	= NULL;
			$grado_id_ant		= NULL;
			
			//echo("<pre>");var_dump($result_dispo);echo("</pre>"); exit
			foreach($result_dispo as $row)
			{
				if (($bd_1era_vez == true)||($variedad_id_ant!=$row['variedad_id'])||($grado_id_ant!=$row['grado_id']))
				{
					if ($bd_1era_vez == false)
					{
						if (!empty($row_new['nro_cajas']))   //SALTAR CAJAS EN CERO
						{
							$key = $variedad_id_ant.'-'.trim($grado_id_ant);
							$result_consolidado[$key] = $row_new;
						}//end if
					}//end if
			
					$bd_1era_vez 							= false;
			
					//Inicializa los campos totalizadores
					$row_new								= NULL;
					$row_new['nro_cajas'] 					= 0;
					$row_new['tot_bunch_disponible']		= 0;
			
				}//end if
			
				//Se totaliza el registro
				$row_new['inventario_id']				= $inventario_id;
				$row_new['grado_id']					= $row['grado_id'];
				$row_new['variedad_id']					= $row['variedad_id'];
				$row_new['variedad_nombre']				= $row['variedad_nombre'];
				$row_new['tipo_caja_id']				= $row['tipo_caja_id'];
				$row_new['tipo_caja_origen_estado']		= $row['tipo_caja_origen_estado'];
				$row_new['tipo_caja_origen_id']			= $row['tipo_caja_origen_id'];
				$row_new['cantidad_bunch'] 				= $row['tipo_caja_unds_bunch'];
				$row_new['nro_cajas'] 					= $row_new['nro_cajas'] +  $row['nro_cajas'];
				$row_new['tot_bunch_disponible']		= $row_new['tot_bunch_disponible'] + $row['tot_bunch_disponible'];
				$row_new['tallos_x_bunch'] 				= $row['tallos_x_bunch'];
				$row_new['precio'] 						= $row['precio'];
				$row_new['precio_oferta']				= $row['precio_oferta'];				
			
				//Control para el quiebre
				$variedad_id_ant						= $row['variedad_id'];
				$grado_id_ant							= $row['grado_id'];
			}//end foreach
			
			if ($bd_1era_vez == false)
			{
				if (!empty($row_new['nro_cajas']))   //SALTAR CAJAS EN CERO
				{
					$key = $variedad_id_ant.'-'.trim($grado_id_ant);
					$result_consolidado[$key] = $row_new;
				}//end if
			}//end if			

			
			/**
			 * Consulta todos los pedido del cliente en estado comprando
			 * para poder homologar las cajas para poderlas restar del stock
			 */			
			$result = $PedidoDetDAO->consultarPedidosEstadoComprando($cliente_id, $inventario_id, $variedad_id, $grado_id);
			//Ajusta el porcentaje de acuerdo a la restriccion de GRUPO_DISPO
			//var_dump($result_consolidado);
			$nro_cajas = 0;
			foreach($result as $row)
			{
				$key = $row['variedad_id'].'-'.trim($row['grado_id']);
				$row_consolidado = &$result_consolidado[$key];
					
				if ($rebajar_cajas_pedido == true)
				{
					$nro_cajas_homologada = $PedidoDetDAO->getCajasHomologadaPedido($inventario_id, $cliente_id, $marcacion_sec, $row['variedad_id'], $row['grado_id'], "C", $row_consolidado['tipo_caja_id']);
				}else{
					$nro_cajas_homologada = 0;
				}
					
				$row_consolidado['nro_cajas'] = $row_consolidado['nro_cajas'] - $nro_cajas_homologada;
				if ($row_consolidado['nro_cajas']<0)
				{
					$row_consolidado['nro_cajas'] = 0;
				}//end if
			}//end foreach
					
			
			/**
			 * Se quita el key del array, para que no afecte al pasarlo por JSON
			 */
			$result_consolidado2 = null;
			
			if (!empty($result_consolidado))
			{
				foreach($result_consolidado as $reg)
				{
					if ($reg['nro_cajas']>0)
					{
						$result_cajas = $TipoCajaMatrizDAO->consultarTipoCajaPorInventarioPorVariedadPorGrado($inventario_id, $reg['variedad_id'], $reg['grado_id']);
						$cajas = null;
						foreach($result_cajas as $row_caja)
						{
							$cajas[] = $row_caja['tipo_caja_id'];
						}//end while
						$reg['cajas'] = $cajas;
			
						//obtiene la dispo por proveedores
						if ($get_fincas == true)
						{
							$reg['proveedores_dispo']  = $proveedores_dispo;
						}//end if
			
						$result_consolidado2[] = $reg;
					}//end if
				}//end foreach
			}//end if			
			
			//$this->getEntityManager()->getConnection()->commit();
			$result = array('respuesta_code' 	=> 'OK',
					'respuesta_msg'		=> '',
					'result_dispo'		=> $result_consolidado2
			);
						
			//return $result_consolidado2;
			return $result;

/*		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
*/		
	}//end function getDispo

	

	/**
	 * 
	 * @param string $cliente_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @return multitype:Ambigous <\Dispo\Data\GrupoPrecioDetData, NULL> multitype:
	 */
	public function consultarPrecioOfertaPorCliente ($cliente_id, $variedad_id, $grado_id)
	{
		$GrupoPrecioDetDAO			= new GrupoPrecioDetDAO();
		$GrupoPrecioOfertaDAO		= new GrupoPrecioOfertaDAO();
		
		$GrupoPrecioDetDAO->setEntityManager			($this->getEntityManager());
		$GrupoPrecioOfertaDAO->setEntityManager			($this->getEntityManager());
		
		
		//AQUI SE DEBE DE CONSULTAR LA GET DISPO PERO PARA ESTE REGISTRO ESPECIFICO Y REPLICAR EL FUNCIONAMIENTO DE LA GRILLA
		$reg_grupo_precio_det	= $GrupoPrecioDetDAO->consultarPorClienteIdPorVariedadIdPorGradoId($cliente_id, $variedad_id, $grado_id);
		$result_precio_oferta 	= $GrupoPrecioOfertaDAO->consultarPorGrupoPrecioCabPorVariedadIdPorGradoId($GrupoPrecioDetData->getGrupoPrecioCab(), 
														$GrupoPrecioDetData->getVariedadId(), $GrupoPrecioDetData->getGradoId());

		return array($reg_grupo_precio_det, $result_precio_oferta);
		
	}//end function consultarPrecio
	
}//end class DispoBO
