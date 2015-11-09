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
use Dispo\DAO\CalidadDAO;
use Dispo\Data\DispoData;
use Application\Classes\PHPExcelApp;
use Dispo\DAO\InventarioDAO;
use Dispo\DAO\ProveedorDAO;
use Dispo\DAO\ColorVentasDAO;
use Dispo\DAO\CalidadVariedadDAO;



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
	 * @param string $producto_id
	 * @param int $tallos_x_bunch
	 * @return array
	 */
	function getDispo($cliente_id, $usuario_id, $marcacion_sec, $tipo_caja_id = null, $variedad_id = null, $grado_id = null, 
					  $get_fincas = false, $rebajar_cajas_pedido = true, $validar_variedad_faltante = false,
					  $producto_id = null, $tallos_x_bunch = null)
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
			$row_usuario = $UsuarioDAO->consultar($usuario_id, \Application\Constants\ResultType::MATRIZ);
			if (empty($row_usuario))
			{
				//'Usuario no tiene asignado un grupo'
				$result = array('respuesta_code' 	=> '01',
								'respuesta_msg'		=> '00 - Availability Unassigned, please contact to your executive'//
				);
				return $result;
			}else{
				$grupo_precio_cab_id= $row_usuario['grupo_precio_cab_id']; //MORONITOR
				$grupo_dispo_cab_id = $row_usuario['grupo_dispo_cab_id'];
				$inventario_id 		= $row_usuario['inventario_id'];
				$clasifica_fox		= $row_usuario['clasifica_fox'];
				$calidad_id			= $row_usuario['calidad_id'];
			}//end if
			
			
			/**
			 * Se obtiene el inventario para el cliente por proveedor y tipo de inventario especifico,
			 * por lo que dara los bunches disponibles, y posteriormente si existe una
			 * RESTRICCION en GRUPO_DISPO se aplica la regla para la dispo por grupo
			 */
			if (empty($inventario_id))
			{
				//Usuario no tiene inventario, comuniquese con su asesor
				$result = array('respuesta_code' 	=> '02',						
						'respuesta_msg'		=> '01 - Stock unloaded, please contact to your executive'  
				);
				return $result;
			}//end if

			if (empty($clasifica_fox))
			{
				//'Calidad no tiene clasifica_fox, comuniquese con su asesor'
				$result = array('respuesta_code' 	=> '02',						
						'respuesta_msg'		=> '02 - Settings unloaded, please contact to your executive'  
				);
				return $result;
			}//end if			

			if (empty($grupo_precio_cab_id))
			{
				//'Usuario no tiene asignado GRUPO PRECIO, comuniquese con su asesor'
				$result = array('respuesta_code' 	=> '02',
						'respuesta_msg'				=> '03 - Price unloaded, please contact to your executive'
				);
				return $result;
			}//end if

			if (empty($grupo_dispo_cab_id))
			{
				//Usuario no tiene asignado GRUPO DISPO, comuniquese con su asesor
				$result = array('respuesta_code' 	=> '02',
						'respuesta_msg'				=> '04 - Stock unloaded, please contact to your executive'
				);
				return $result;
			}//end if
					
			
			if ($row_usuario['inventario_id']!=$row_usuario['grupo_precio_cab_inventario_id'])
			{
				//INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE INVENTARIO (GRUPO PRECIO)
				$result = array('respuesta_code' 	=> 'NO-CONFIG-INV-PRECIO',
						'respuesta_msg'				=> '05 - Stock unloaded, please contact to your executive'
				);
				return $result;
			}//end if			

			if ($row_usuario['inventario_id']!=$row_usuario['grupo_dispo_cab_inventario_id'])
			{
				//INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE INVENTARIO (GRUPO DISPO)
				$result = array('respuesta_code' 	=> 'NO-CONFIG-INV-DISPO',
						'respuesta_msg'				=> '06 - Stock unloaded, please contact to your executive'
				);
				return $result;
			}//end if	

			if ($row_usuario['calidad_id']!=$row_usuario['grupo_precio_cab_calidad_id'])
			{
				//INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE CALIDAD (GRUPO PRECIO)
				$result = array('respuesta_code' 	=> 'NO-CONFIG-CAL-PRECIO',
						'respuesta_msg'				=> '07 - Settings unloaded, please contact to your executive'
				);
				return $result;
			}//end if

			if ($row_usuario['calidad_id']!=$row_usuario['grupo_dispo_cab_calidad_id'])
			{
				//INCOMPATIBILIDAD DE CONFIGURACION DE POLITICA DE CALIDAD (GRUPO DISPO)
				$result = array('respuesta_code' 	=> 'NO-CONFIG-CAL-DISPO',
						'respuesta_msg'				=> '08 - Settings unloaded, please contact to your executive'
				);
				return $result;
			}//end if			

			$result = $DispoDAO->consultarInventarioPorUsuario($usuario_id, $producto_id, $variedad_id, $grado_id, $clasifica_fox);			
			
			/**
			 *Ajusta el stock de los bunch de las fincas para mostrar la dispo de acuerdo al GRUPO_DISPO_DET
			 *se debe de hacer un quiebre por producto, variedad, grado, tallos_x_bunch y  finca, para ajustar la dispo segun las restricciones
			 */
			$producto_id_ant	= null;
			$variedad_id_ant 	= null;
			$grado_id_ant		= null;
			$tallos_x_bunch_ant	= null;
			$grupo_dispo_det_cantidad_bunch_disponible = 0;
			//while($row = $result->fetch_array(MYSQLI_ASSOC))
			
			if (empty($result))
			{
				if ($validar_variedad_faltante)
				{
					$VariedadData = $VariedadDAO->consultar($variedad_id);
					$result = array('respuesta_code' 	=> '12',
									'respuesta_msg'		=> 'Do not exist any box of: '.$tipo_caja_id .' '.$VariedadData->getNombre().' '.$grado_id.' cm'
									);
					unset($VariedadData);
					return $result;
				}//end if
			}//end if
			
			$result_dispo = null;
			foreach($result as $row)
			{
				if (($row['variedad_id']=='ALB')&&($row['grado_id']=='60'))
				{
					$debug = 1;
				}//end if
				
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
									'respuesta_msg'		=> 'Do not exist any box of: '.$tipo_caja_id .' '.$row['variedad_nombre'].' '.$grado_id.' cm'							);
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
				
				if ($row['precio']==0) //Si el precio viene con CERO se salta directamente al FOR
				{
					continue; //MORONITOR
				}
			
				//Si varia la variedad_id y el grado_id se indica la cantidad de bunch disponibles del GRUPO DISPO
				if (($producto_id_ant != $row['producto_id'])||($variedad_id_ant != $row['variedad_id'])||($grado_id_ant != $row['grado_id'])||($tallos_x_bunch_ant != $row['tallos_x_bunch']))
				{
					if (empty($row['grupo_dispo_det_cantidad_bunch_disponible']))
					{
						$grupo_dispo_det_cantidad_bunch_disponible = 0;
					}else{
						$grupo_dispo_det_cantidad_bunch_disponible = $row['grupo_dispo_det_cantidad_bunch_disponible'];
					}//end if

					$producto_id_ant 	= $row['producto_id'];
					$variedad_id_ant	= $row['variedad_id'];
					$grado_id_ant 		= $row['grado_id'];
					$tallos_x_bunch_ant	= $row['tallos_x_bunch'];
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
					$row_dispo['producto_id']			= $row_dispo['producto_id'];
					$row_dispo['variedad_nombre']		= trim($row_dispo['variedad_nombre']);
					$row_dispo['proveedor_id']			= $row_dispo['proveedor_id'];
					$row_dispo['variedad_id']			= $row_dispo['variedad_id'];
					$row_dispo['grado_id']				= $row_dispo['grado_id'];
					//$row_dispo['tallos_x_bunch']		= $row_dispo['tallos_x_bunch'];
					$row_dispo['precio']				= $row_dispo['precio'];
					$row_dispo['precio_oferta']			= $row_dispo['precio_oferta'];					
					$row_dispo['grupo_dispo_det_cantidad_bunch_disponible']	= $grupo_dispo_det_cantidad_bunch_disponible_ant;
			
					//$row_dispo['tot_bunch_disponible'] 	= floor($row_dispo['tot_bunch_disponible']*$porcentaje);
					$row_dispo['tot_bunch_disponible'] 	= $tot_bunch_disponibles;
					$row_dispo['tallos_x_bunch']		= $row_dispo['tot_tallos_x_bunch'] / $row_dispo['veces_tallos_x_bunch'];
					$row_dispo['color_nombre']			= $row_dispo['color_nombre'];
					$row_dispo['url_ficha']				= $row_dispo['url_ficha'];
					
					//Los siguientes se llenaran más adelante
					$row_dispo['tipo_caja_origen_estado'] 	= NULL;
					$row_dispo['tipo_caja_origen_id']		= NULL;
					$row_dispo['tipo_caja_unds_bunch']		= NULL;
					$row_dispo['nro_cajas']					= NULL;

					$result_dispo[] = $row_dispo;
				}//end if
			}//end foreach
			
			
			if (empty($result_dispo))
			{	
				$result = array('respuesta_code' 	=> '08',
					'respuesta_msg'			=> 'Dispo unavailable'
				);
				return $result; //NO HAY DISPO
			}
			
			foreach($result_dispo as &$row_dispo)
			{
				$row = $TipoCajaMatrizDAO->consultaPorInventarioPorMarcacionPorVariedadPorGrado($inventario_id, $marcacion_sec, $row_dispo['variedad_id'], $row_dispo['grado_id'], $tipo_caja_id);
				
				if(!empty($row)) {
					//echo("<pre>");var_dump($row);echo("</pre>"); //DEBUG
					$row_dispo['tipo_caja_id'] 				= $row['tipo_caja_id'];
					$row_dispo['tipo_caja_origen_estado'] 	= $row['tipo_caja_origen_estado'];
					$row_dispo['tipo_caja_origen_id']		= $row['tipo_caja_origen_id'];
					$row_dispo['tipo_caja_unds_bunch']		= $row['tipo_caja_unds_bunch'];
					$row_dispo['tallos_x_bunch']			= $row_dispo['tallos_x_bunch']; //MEJORA POR LOS BUNCHS 
					if (empty($row['tipo_caja_unds_bunch']))
					{
						$row_dispo['nro_cajas']				= 0;
					}else{
						$row_dispo['nro_cajas']				= floor($row_dispo['tot_bunch_disponible']/$row['tipo_caja_unds_bunch']);
					}//end if
				
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
							'respuesta_msg'			=> $row_dispo['variedad_nombre']. ' '.$row_dispo['grado_id']. ' cm variety have not '. $tipo_caja_id.' box available'
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
			$producto_id_ant	= NULL;
			$variedad_id_ant	= NULL;
			$grado_id_ant		= NULL;
			$tallos_x_bunch_ant = NULL;
			
			//echo("<pre>");var_dump($result_dispo);echo("</pre>"); exit
			foreach($result_dispo as $row)
			{
				if (($bd_1era_vez == true)||($producto_id_ant!=$row['producto_id'])||($variedad_id_ant!=$row['variedad_id'])||($grado_id_ant!=$row['grado_id'])||($tallos_x_bunch_ant!=$row['tallos_x_bunch']))
				{
					if ($bd_1era_vez == false)
					{
						if (!empty($row_new['nro_cajas']))   //SALTAR CAJAS EN CERO
						{
							$key = $producto_id_ant.'-'.$variedad_id_ant.'-'.trim($grado_id_ant).'-'.$tallos_x_bunch_ant;
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
				$row_new['producto_id']					= $row['producto_id'];
				$row_new['inventario_id']				= $inventario_id;
				$row_new['grado_id']					= $row['grado_id'];
				$row_new['variedad_id']					= $row['variedad_id'];
				$row_new['variedad_nombre']				= $row['variedad_nombre'];
				$row_new['tipo_caja_id']				= $row['tipo_caja_id'];
				$row_new['tipo_caja_origen_estado']		= $row['tipo_caja_origen_estado'];
				$row_new['tipo_caja_origen_id']			= $row['tipo_caja_origen_id'];
				$row_new['cantidad_bunch'] 				= $row['tipo_caja_unds_bunch'];
				$row_new['tipo_caja_default_id']		= $tipo_caja_id;
				$row_new['nro_cajas'] 					= $row_new['nro_cajas'] +  $row['nro_cajas'];
				$row_new['tot_bunch_disponible']		= $row_new['tot_bunch_disponible'] + $row['tot_bunch_disponible'];
				$row_new['tallos_x_bunch'] 				= $row['tallos_x_bunch'];
				$row_new['color_nombre']				= $row['color_nombre'];
				$row_new['url_ficha']					= $row['url_ficha'];
				$row_new['precio'] 						= $row['precio'];
				$row_new['precio_oferta']				= $row['precio_oferta'];				
			
				//Control para el quiebre
				$producto_id_ant						= $row['producto_id'];
				$variedad_id_ant						= $row['variedad_id'];
				$grado_id_ant							= $row['grado_id'];
				$tallos_x_bunch_ant						= $row['tallos_x_bunch'];
			}//end foreach
			
			if ($bd_1era_vez == false)
			{
				if (!empty($row_new['nro_cajas']))   //SALTAR CAJAS EN CERO
				{
					$key =  $producto_id_ant.'-'.$variedad_id_ant.'-'.trim($grado_id_ant).'-'.$tallos_x_bunch_ant;
					$result_consolidado[$key] = $row_new;
				}//end if
			}//end if			

			
			/**
			 * Consulta todos los pedido del cliente en estado comprando
			 * para poder homologar las cajas para poderlas restar del stock
			 */			
			$result = $PedidoDetDAO->consultarPedidosEstadoComprando($cliente_id, $inventario_id, $producto_id, $variedad_id, $grado_id,$tallos_x_bunch, $calidad_id);
			//Ajusta el porcentaje de acuerdo a la restriccion de GRUPO_DISPO
			//var_dump($result_consolidado);
			$nro_cajas = 0;
			foreach($result as $row)
			{
				$key = $row['producto_id'].'-'.$row['variedad_id'].'-'.trim($row['grado_id']).'-'.$row['tallos_x_bunch'];
				$row_consolidado = &$result_consolidado[$key];
					
				if ($rebajar_cajas_pedido == true)
				{
					$nro_cajas_homologada = $PedidoDetDAO->getCajasHomologadaPedido($inventario_id, $cliente_id, $marcacion_sec, $row['variedad_id'], 
																$row['grado_id'], "C", $row_consolidado['tipo_caja_id'], $row['tallos_x_bunch'],
																$calidad_id);
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
/*			echo('<pre>');
			var_dump($result);
			echo('</pre>');
			die();
*/
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
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @param string $tipo_caja_id
	 * @return multitype:Ambigous <\Dispo\Data\GrupoPrecioDetData, array> multitype:
	 */
	public function consultarPrecioOfertaPorCliente ($cliente_id, $usuario_id, $marcacion_sec, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id)
	{
		$GrupoPrecioDetDAO			= new GrupoPrecioDetDAO();
		$GrupoPrecioOfertaDAO		= new GrupoPrecioOfertaDAO();
		
		$GrupoPrecioDetDAO->setEntityManager			($this->getEntityManager());
		$GrupoPrecioOfertaDAO->setEntityManager			($this->getEntityManager());
		
		
		//AQUI SE DEBE DE CONSULTAR LA GET DISPO PERO PARA ESTE REGISTRO ESPECIFICO Y REPLICAR EL FUNCIONAMIENTO DE LA GRILLA
		$reg_grupo_precio_det	= $GrupoPrecioDetDAO->consultarPorUsuarioIdPorVariedadIdPorGradoId($usuario_id, $producto_id, $variedad_id, $grado_id, $tallos_x_bunch);

		$dispo_precio_oferta = $this->getDispo($cliente_id, $usuario_id, $marcacion_sec, $tipo_caja_id, $variedad_id, $grado_id,
												false, true, false,
					  							$producto_id, $tallos_x_bunch);

		//Obtiene el registro de la carne (Registro Cabecera)		
		/*$rs_precio_oferta 	= $GrupoPrecioOfertaDAO->consultarPorGrupoPrecioCabPorVariedadIdPorGradoId($reg_grupo_precio_det['grupo_precio_cab_id'],
														$variedad_id, $grado_id);
		
		return array($dispo_precio_oferta, $rs_precio_oferta);
		*/
		return $dispo_precio_oferta;
	}//end function consultarPrecioOfertaPorCliente
	
	
	
	
	
	public function consultarPrecioOfertaPorClienteHueso ($cliente_id, $usuario_id, $marcacion_sec, $oferta_producto_id,  $oferta_variedad_id, 
														  $oferta_grado_id, $oferta_tallos_x_bunch,  $oferta_tipo_caja_id, $oferta_nro_caja)
	{
		$GrupoPrecioDetDAO			= new GrupoPrecioDetDAO();
		$GrupoPrecioOfertaDAO		= new GrupoPrecioOfertaDAO();
	
		$GrupoPrecioDetDAO->setEntityManager			($this->getEntityManager());
		$GrupoPrecioOfertaDAO->setEntityManager			($this->getEntityManager());
	
	
		//Nos permite identificar el grupo_precio_cab_id en que se encuentra el cliente para poder saber con que precio va a trabajar
		$reg_grupo_precio_det	= $GrupoPrecioDetDAO->consultarPorUsuarioIdPorVariedadIdPorGradoId($usuario_id, $oferta_producto_id, 
																		$oferta_variedad_id, $oferta_grado_id, $oferta_tallos_x_bunch);
			
		//Se obtiene los registros HUESO (EL COMBO), de acuerdo a la CARNE 
		$rs_precio_oferta 	= $GrupoPrecioOfertaDAO->consultarPorGrupoPrecioCabPorVariedadIdPorGradoId($reg_grupo_precio_det['grupo_precio_cab_id'], $oferta_producto_id, $oferta_variedad_id, $oferta_grado_id, $oferta_tallos_x_bunch);

		//Se pregunta registro por registro la disponibilidad y la conversion de las cajas
		$result_hueso = null;
		foreach($rs_precio_oferta as $reg_precio_oferta)
		{
			$rs_dispo_precio_oferta = $this->getDispo($cliente_id, $usuario_id, $marcacion_sec, $oferta_tipo_caja_id, 
													  $reg_precio_oferta['variedad_combo_id'], $reg_precio_oferta['grado_combo_id'],
													  false, true, false, $oferta_producto_id, $oferta_tallos_x_bunch);			
			if ($rs_dispo_precio_oferta) {	
				if (array_key_exists('result_dispo',$rs_dispo_precio_oferta))
				{			
					$reg_dispo_precio_oferta = $rs_dispo_precio_oferta['result_dispo'][0];
					
					$hueso_cajas_minima = $reg_precio_oferta['factor_combo'] *  $oferta_nro_caja;
					
					if ($reg_dispo_precio_oferta['nro_cajas'] >=  $hueso_cajas_minima)
					{
						$reg_hueso = array();
						$reg_hueso['producto_id']			= $reg_dispo_precio_oferta['producto_id'];
						$reg_hueso['variedad_id'] 			= $reg_dispo_precio_oferta['variedad_id'];
						$reg_hueso['variedad_nombre'] 		= $reg_dispo_precio_oferta['variedad_nombre'];
						$reg_hueso['grado_id'] 				= $reg_dispo_precio_oferta['grado_id'];
						$reg_hueso['tallos_x_bunch']		= $reg_dispo_precio_oferta['tallos_x_bunch'];
						$reg_hueso['tipo_caja_id'] 			= $reg_dispo_precio_oferta['tipo_caja_id'];
						$reg_hueso['precio'] 				= $reg_dispo_precio_oferta['precio'];
						$reg_hueso['nro_cajas'] 			= $reg_dispo_precio_oferta['nro_cajas'];
						$reg_hueso['nro_cajas_requeridas'] 	= $hueso_cajas_minima;
	
						$result_hueso[] = $reg_hueso;
					}//end if
				}//end if
			}//end if			
		}//end foreach	
		
		return $result_hueso;
	}//end function consultarPrecioOfertaPorClienteHueso	

	
	
	/**
	 * 
	 * @param array $condiciones (inventario_id, proveedor_id, clasifica, color_ventas_id, calidad_variedad_id, nro_tallos)
	 * @return array
	 */
	public function listado($condiciones)
	{
		$DispoDAO = new DispoDAO();
		$DispoDAO->setEntityManager($this->getEntityManager());
		$result = $DispoDAO->listado($condiciones);
		return $result;		
	}//end function listado
	

	/**
	 * 
	 * @param unknown $condiciones
	 */
	public function listadoSinVacios($condiciones)
	{
		$result = $this->listado($condiciones);
		$result2 = null;
		foreach ($result as $reg)
		{
			if ((empty($reg['40']))&&(empty($reg['50']))&&(empty($reg['60']))&&(empty($reg['70']))
				&&(empty($reg['80']))&&(empty($reg['90']))&&(empty($reg['100']))&&(empty($reg['110'])))
			{
				//Registro VACIO
			}else{
				$result2[] = $reg;
			}//end if
		}//end foreach
		return $result2;
	}//end function listadoSinVacios
	

	/**
	 *
	 * @param unknown $condiciones
	 */
	public function listadoVacios($condiciones)
	{
		$result = $this->listado($condiciones);
		$result2 = null;
		foreach ($result as $reg)
		{
			if ((empty($reg['40']))&&(empty($reg['50']))&&(empty($reg['60']))&&(empty($reg['70']))
					&&(empty($reg['80']))&&(empty($reg['90']))&&(empty($reg['100']))&&(empty($reg['110'])))
			{
				$result2[] = $reg;
			}
		}//end foreach
		return $result2;
	}//end function listadoSinVacios
	
	
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $clasifica_fox
	 * @param string $proveedor_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @return Ambigous <NULL, array>
	 */
	public function consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch)
	{
		$DispoDAO = new DispoDAO();
		$DispoDAO->setEntityManager($this->getEntityManager());
		$result = $DispoDAO->consultarPorInventarioPorCalidadPorProveedorPorGradoPorTallo($inventario_id, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch);
		
		$row = null;
		foreach($result as $reg)
		{
			$row[$reg['proveedor_id']]['tot_bunch_disponible'] = $reg['tot_bunch_disponible'];
		}//end if

		return $row;
	}//end function consultarPorInventarioPorCalidadPorProveedorPorGrado
	
	
	/**
	 * 
	 * @param string $inventario_id
	 * @param string $producto
	 * @param string $clasifica_fox
	 * @param string $proveedor_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param array $stock
	 * @throws Exception
	 * @return array
	 */
	public function actualizarStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch, $stock)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$DispoDAO = new DispoDAO();
			$DispoDAO->setEntityManager($this->getEntityManager());

			if (empty($proveedor_id))
			{
				//TODAS LAS FINCAS
				foreach($stock as $clave => $valor)
				{
					$valor = (empty($valor)?0:$valor);
					$DispoDAO->actualizarStock($inventario_id, $producto, $clasifica_fox, $clave, $variedad_id, $grado_id, $tallos_x_bunch, $valor);
				}//end foreach
			}else{

				//UNA SOLA FINCA
				$stock = $stock[$proveedor_id];
				$DispoDAO->actualizarStock($inventario_id, $producto, $clasifica_fox, $proveedor_id, $variedad_id, $grado_id, $tallos_x_bunch, $stock);
			}//end if

			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
	}//end function actualizarStock
	
	
	
	/**
	 *
	 * @param string $inventario_id
	 * @param string $variedad_id
	 * @param string $texto_1er_elemento
	 * @param string $color_1er_elemento
	 * @return string
	 */
	function getComboVariedadPorInventario($inventario_id, $variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$DispoDAO = new DispoDAO();
	
		$DispoDAO->setEntityManager($this->getEntityManager());
	
		$result = $DispoDAO->agrupadoPorInventarioPorVariedad($inventario_id, null, "variedad.nombre");
	
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'variedad_id', 'variedad_nombre',$variedad_id, $texto_1er_elemento, $color_1er_elemento);
			
		return $opciones;
	}//end function getComboVariedadPorInventario
	
	
	
	function listadoVariedadPorInventario($inventario_id, $variedad_nombre = null)
	{
		$DispoDAO = new DispoDAO();

		$DispoDAO->setEntityManager($this->getEntityManager());

		$result = $DispoDAO->agrupadoPorInventarioPorVariedad($inventario_id, null, "variedad.nombre", $variedad_nombre);

		return $result;		
	}//end function listadoVariedadPorInventario

	
	

	
	function getComboVariedadNoExiste($inventario_id, $calidad_id, $variedad_id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "#FFFFAA")
	{
		$DispoDAO 	= new DispoDAO();
		$CalidadDAO = new CalidadDAO();
		
		$DispoDAO->setEntityManager($this->getEntityManager());
		$CalidadDAO->setEntityManager($this->getEntityManager());		

		$CalidadData = $CalidadDAO->consultar($calidad_id);
		$condiciones = array(
				"inventario_id"		=> $inventario_id,
				"proveedor_id"		=> null,
				"clasifica"			=> $CalidadData->getClasificaFox(),
				"color_ventas_id"	=> null,
				"calidad_variedad_id"=> null,
				"nro_tallos"		=> 25
		);
		$result_dispo = $this->listadoVacios($condiciones);
		
		$result_variedad = $DispoDAO->variedadesNoExiste($inventario_id, $CalidadData->getClasificaFox());
			
		$result = null;
		foreach($result_dispo as $row)
		{
			$reg['variedad_id'] 	= $row['variedad_id'];
			$reg['variedad_nombre'] = $row['variedad'];
			$result[$reg['variedad_id']] = $reg;
		}//end foreach
		
		foreach($result_variedad as $row)
		{
			$reg['variedad_id'] 	= $row['variedad_id'];
			$reg['variedad_nombre'] = $row['variedad_nombre'];
			$result[$reg['variedad_id']] = $reg;
		}//end foreach
		
				
		$opciones = \Application\Classes\Combo::getComboDataResultset($result, 'variedad_id', 'variedad_nombre',$variedad_id, $texto_1er_elemento, $color_1er_elemento);

		return $opciones;
	}//end function getComboVariedadPorInventario	
	
	
	
	public function consultarPorInventarioPorCalidadPorVariedadPorGrado($inventario_id, $calidad_id, $variedad_id, $grado_id)
	{
		$ProveedorDAO	= new ProveedorDAO();
		$DispoDAO 		= new DispoDAO();
		$CalidadDAO		= new CalidadDAO();

		$DispoDAO->setEntityManager($this->getEntityManager());
		$CalidadDAO->setEntityManager($this->getEntityManager());
		$ProveedorDAO->setEntityManager($this->getEntityManager());

		$CalidadData =  $CalidadDAO->consultar($calidad_id);
		$clasifica_fox = null;
		if ($CalidadData)
		{
			$clasifica_fox = $CalidadData->getClasificaFox();
		}//end if

		
		$row = null;
		$result_proveedor = $ProveedorDAO->consultarTodos();
		foreach($result_proveedor as $reg)
		{
			$row[$reg['id']]['tot_bunch_disponible'] = 0;
		}//end foreach

		
		$result = $DispoDAO->consultarPorInventarioPorCalidadPorVariedadPorGrado($inventario_id, $clasifica_fox, $variedad_id, $grado_id);
		foreach($result as $reg)
		{
			$row[$reg['proveedor_id']]['tot_bunch_disponible'] = $reg['tot_bunch_disponible'];
		}//end if
	
		return $row;
	}//end function consultarPorInventarioPorCalidadPorVariedad


	
	public function registrarStockNuevo($inventario_id, $producto, $calidad_id, $variedad_id, $grado_id, $tallos_x_bunch, $stock)
	{		
		try
		{
			$DispoDAO 	= new DispoDAO();
			$CalidadDAO = new CalidadDAO();
			
			$DispoDAO->setEntityManager($this->getEntityManager());
			$CalidadDAO->setEntityManager($this->getEntityManager());
			
			$CalidadData = $CalidadDAO->consultar($calidad_id);
			if (empty($CalidadData))
			{
				$result['validacion_code'] 	= 'CALIDAD';
				$result['respuesta_mensaje']= 'ID de Calidad no existe';
				return $result;
			}else{
				$clasifica_fox = $CalidadData->getClasificaFox();
			}//end if
		
			$this->getEntityManager()->getConnection()->beginTransaction();			
			//TODAS LAS FINCAS
			foreach($stock as $clave => $valor)
			{
				$valor = (empty($valor)?0:$valor);
				$DispoDAO->actualizarStock($inventario_id, $producto, $clasifica_fox, $clave, $variedad_id, $grado_id, $tallos_x_bunch, $valor);
			}//end foreach
		
			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
	}//end function 	

	
	
	public function grabarMasivoStock($inventario_id, $clasifica, $proveedor_id, $grado_id, 
									 $cadena_color_ventas_ids, $cadena_calidad_variedad_ids, 
									 $porcentaje, $valor, $usuario_id)
	{
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$DispoDAO			= new DispoDAO();
			//$GrupoDispoDetDAO 	= new GrupoDispoDetDAO();
			
			$DispoData 			= new DispoData();
			//$GrupoDispoDetData 	= new GrupoDispoDetData();

			//$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
			$DispoDAO->setEntityManager($this->getEntityManager());
			

			$campo_grado_dispogen = $grado_id;
			
			if ($porcentaje!=0)
			{
				$condiciones = array(
						"sin_fecha"						=> false,
						'sin_fecha_bunch'				=> false,
						"inventario_id"					=> $inventario_id,
						"proveedor_id"					=> $proveedor_id,
						"clasifica"						=> $clasifica,
						"cadena_color_ventas_ids"		=> $cadena_color_ventas_ids,
						"cadena_calidad_variedad_ids"	=> $cadena_calidad_variedad_ids
				);
				$result = $DispoDAO->consultarDetallado($condiciones);
				
				foreach($result as $reg)
				{
					/*$DispoData->setId				($reg['id']);*/
					$DispoData->setFecha			($reg['fecha']);
					$DispoData->setInventarioId		($reg['inventario_id']);
					$DispoData->setFechaBunch		($reg['fecha_bunch']);
					$DispoData->setProveedorId		($reg['proveedor_id']);
					$DispoData->setProducto			($reg['producto']);
					$DispoData->setVariedadId		($reg['variedad_id']);
					$DispoData->setGradoId			($campo_grado_dispogen);
					$DispoData->setTallosxBunch		($reg['tallos_x_bunch']);
					$DispoData->setClasifica		($reg['clasifica']);

					$cantidad_bunch = floor($reg[$campo_grado_dispogen]*$porcentaje/100);
					$DispoData->setCantidadBunch			($cantidad_bunch);
					$DispoData->setCantidadBunchDisponible	($cantidad_bunch);

					list($accion, $key) = $DispoDAO->registrarBunchDisponibles($DispoData);

				}//end foreach
			}
			else  //CASO CONTRARIO ES POR VALOR
			{
				/**
				 * Debemos de realizar la consulta de manera agrupada sin la fecha y fecha_bunch,
				 * para saber si disminuimos o aumentamos el stock de acuerdo al valor
				 */
				$condiciones = array(
						"sin_fecha"						=> true,
						'sin_fecha_bunch'				=> true,
						"inventario_id"					=> $inventario_id,
						"proveedor_id"					=> $proveedor_id,
						"clasifica"						=> $clasifica,
						"cadena_color_ventas_ids"		=> $cadena_color_ventas_ids,
						"cadena_calidad_variedad_ids"	=> $cadena_calidad_variedad_ids
				);
				$result = $DispoDAO->consultarDetallado($condiciones);

				foreach($result as $reg)
				{					
					$saldo_valor = $valor;
					
					$condiciones = array(
						'sin_fecha'					=> false,
						'sin_fecha_bunch'			=> false,
						'inventario_id'				=> $reg['inventario_id'],
						'proveedor_id'				=> $reg['proveedor_id'],
						'producto'					=> $reg['producto'],
						'variedad_id'				=> $reg['variedad_id'],
						/*'grado_id'				=> $reg[$campo_grado_dispogen],*/
						'tallos_x_bunch'			=> $reg['tallos_x_bunch'],
						'clasifica'					=> $clasifica,
					);
					$result_detalle = $DispoDAO->consultarDetallado($condiciones);
					foreach($result_detalle as $reg_detalle)
					{
						if ($reg[$campo_grado_dispogen]<$valor)  //SUMAR
						{
							$DispoData->setFecha			($reg_detalle['fecha']);
							$DispoData->setInventarioId		($reg_detalle['inventario_id']);
							$DispoData->setFechaBunch		($reg_detalle['fecha_bunch']);
							$DispoData->setProveedorId		($reg_detalle['proveedor_id']);
							$DispoData->setProducto			($reg_detalle['producto']);
							$DispoData->setVariedadId		($reg_detalle['variedad_id']);
							$DispoData->setGradoId			($campo_grado_dispogen);
							$DispoData->setTallosxBunch		($reg_detalle['tallos_x_bunch']);
							$DispoData->setClasifica		($reg_detalle['clasifica']);
							
							$diferencia		= $saldo_valor - $reg[$campo_grado_dispogen];
							$cantidad_bunch = $reg_detalle[$campo_grado_dispogen]+$diferencia;
							$DispoData->setCantidadBunch			($cantidad_bunch);
							$DispoData->setCantidadBunchDisponible	($cantidad_bunch);
							
							list($accion, $key) = $DispoDAO->registrarBunchDisponibles($DispoData);
							
							break;  //SALE DEL FOR, ya que lo incremento
						}
						else if ($reg[$campo_grado_dispogen]>$valor) //RESTAR
						{  
							$DispoData->setFecha			($reg_detalle['fecha']);
							$DispoData->setInventarioId		($reg_detalle['inventario_id']);
							$DispoData->setFechaBunch		($reg_detalle['fecha_bunch']);
							$DispoData->setProveedorId		($reg_detalle['proveedor_id']);
							$DispoData->setProducto			($reg_detalle['producto']);
							$DispoData->setVariedadId		($reg_detalle['variedad_id']);
							$DispoData->setGradoId			($campo_grado_dispogen);
							$DispoData->setTallosxBunch		($reg_detalle['tallos_x_bunch']);
							$DispoData->setClasifica		($reg_detalle['clasifica']);
							
							if ($reg_detalle[$campo_grado_dispogen] > $saldo_valor)
							{
								$cantidad_bunch = $saldo_valor;
								$saldo_valor 	= 0;
							}else{
								$cantidad_bunch = 0;
								$saldo_valor 	= $saldo_valor - $reg_detalle[$campo_grado_dispogen];
							}//end if
							
							$DispoData->setCantidadBunch			($cantidad_bunch);
							$DispoData->setCantidadBunchDisponible	($cantidad_bunch);
							
							list($accion, $key) = $DispoDAO->registrarBunchDisponibles($DispoData);
							
							/*if ($saldo_valor == 0)
							{
								break;  //SALE DEL FOR, porque ya no se tiene saldo para disminuir
							}//end if*/
						}else{
							//NO HACE NADA EN ESTE CASO
						}
					}//end foreach
					
				}//end foreach
			}//end if
			

			$result['validacion_code'] 	= 'OK';
			$result['respuesta_mensaje']= '';
		
			$this->getEntityManager()->getConnection()->commit();
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function grabarMasivoStock

	
	/**
	 * 
	 * @param array $condiciones (inventario_id, proveedor_id, clasifica, color_ventas_id, calidad_variedad_id)
	 */
	public function generarExcel($condiciones)
	{
		set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		
		$InventarioDAO  	= new InventarioDAO();
		$ProveedorDAO		= new ProveedorDAO();
		$CalidadDAO			= new CalidadDAO();
		$ColorVentasDAO 	= new ColorVentasDAO();
		$CalidadVariedadDAO = new CalidadVariedadDAO();
		$DispoDAO			= new DispoDAO();
		
		$InventarioDAO->setEntityManager($this->getEntityManager());		
		$ProveedorDAO->setEntityManager($this->getEntityManager());
		$CalidadDAO->setEntityManager($this->getEntityManager());
		$ColorVentasDAO->setEntityManager($this->getEntityManager());
		$CalidadVariedadDAO->setEntityManager($this->getEntityManager());
		$DispoDAO->setEntityManager($this->getEntityManager());
		
		//----------------Se configura las Etiquetas de Seleccion-----------------
		$texto_inventario 		= 'TODOS';
		$texto_proveedor 		= 'TODOS';
		$texto_calidad 			= 'TODAS';
		$texto_color_ventas 	= 'TODOS';
		$texto_calidad_variedad	= 'TODAS';
		
		if (!empty($condiciones['inventario_id'])){	
			$InventarioData 		= $InventarioDAO->consultar($condiciones['inventario_id']);
			$texto_inventario		= $InventarioData->getNombre();
		}//end if
		
		if (!empty($condiciones['proveedor_id'])){		
			$ProveedorData  		= $ProveedorDAO->consultar($condiciones['proveedor_id']);
			$texto_proveedor		= $ProveedorData->getNombre();
		}//end if
		
		if (!empty($condiciones['clasifica'])){
			$CalidadData			= $CalidadDAO->consultarPorClasificaFox($condiciones['clasifica']);
			$texto_calidad			= $CalidadData->getNombre();
		}//end if
		
		if (!empty($condiciones['color_ventas_id'])){
			$ColorVentasData		= $ColorVentasDAO->consultar($condiciones['color_ventas_id']);
			$texto_color_ventas		= $ColorVentasData->getNombre();
		}//end if

		if (!empty($condiciones['calidad_variedad_id'])){
			$CalidadVariedadData 	= $CalidadVariedadDAO->consultar($condiciones['calidad_variedad_id']);
			$texto_calidad_variedad	= $CalidadVariedadData->getNombre();
		}//end if
		
		
		//----------------Se inicia la configuracion del PHPExcel-----------------
		
		$PHPExcelApp 	= new PHPExcelApp();
		$objPHPExcel 	= new \PHPExcel;

		// Set document properties
		$PHPExcelApp->setUserName('');
		$PHPExcelApp->setMetaDataDocument($objPHPExcel);
		
		$objPHPExcel->setActiveSheetIndex(0);	

		//Configura el tamaÃ±o del Papel
		$objPHPExcel->getActiveSheet()->getPageSetup()
									  ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()
									  ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		
		
		//Se establece la escala de la pagina
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		
		//Se establece los margenes de la pagina
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.1);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.1);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.1);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.1);
		
		
		//------------------------------Registra la cabecera--------------------------------
		$row				= 1;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(13);		

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "DISPONIBILIDAD GENERAL");		
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		

		//------------------------------Registra criterios linea 1--------------------------
		$row				= 2;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(13);
		
		$objRichText = new \PHPExcel_RichText();
		$objRichText->createText('');
		
		$objInventario = $objRichText->createTextRun('Inventario: ');
		$objInventario->getFont()->setBold(true);
		$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));		
		$objRichText->createText($texto_inventario);
		
		$objCalidad = $objRichText->createTextRun('    Calidad: ');
		$objCalidad->getFont()->setBold(true);
		$objCalidad->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_calidad);

		$objProveedor = $objRichText->createTextRun('    Proveedor: ');
		$objProveedor->getFont()->setBold(true);
		$objProveedor->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_proveedor);

		$objColor = $objRichText->createTextRun('    Color: ');
		$objColor->getFont()->setBold(true);
		$objColor->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_color_ventas);

		$objCategoria = $objRichText->createTextRun('    Categoria: ');
		$objCategoria->getFont()->setBold(true);
		$objCategoria->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));
		$objRichText->createText($texto_calidad_variedad);		
		
		$objPHPExcel->getActiveSheet()->getCell($col_ini.$row)->setValue($objRichText);		
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);		
		
		
		//------------------------------ Registro de Fecha de Generacion --------------------------------
		$row				= 3;
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(13);
		
		//$etiqueta = "";
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Generado: ".\Application\Classes\Fecha::getFechaHoraActualServidor());
	
		$objPHPExcel->getActiveSheet()->mergeCells($col_ini.$row.':'.$col_fin.$row);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		
		//---------------------------------------------------------------------------------
		$row = $row + 1;		
		$row_detalle_ini = $row;
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row, "Nro");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row, "Id");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row, "Variedad");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row, " ");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row, "Color");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row, "40");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row, "50");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row, "60");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row, "70");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row, "80");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row, "90");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row, "100");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$row, "110");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$row, "Total");
		
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth(6);

		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));
		
		$objPHPExcel_getActiveSheet=$objPHPExcel->getActiveSheet();
		
		
		$result = $this->listadoSinVacios($condiciones);
		
		$totales['40'] = 0;
		$totales['50'] = 0;
		$totales['60'] = 0;
		$totales['70'] = 0;
		$totales['80'] = 0;
		$totales['90'] = 0;
		$totales['100'] = 0;
		$totales['110'] = 0;
		$totales['total'] = 0;
		
		$cont_linea = 0;
		foreach($result as $reg){
			$reg['variedad'] = trim($reg['variedad']);
			$reg['total']	 = $reg['40'] + $reg['50'] + $reg['60'] + $reg['70'] + $reg['80'] + $reg['90'] + $reg['100'] + $reg['110'];
		
			//Array de Totales
			$totales['40'] 		= $totales['40'] + $reg['40'];
			$totales['50'] 		= $totales['50'] + $reg['50'];
			$totales['60'] 		= $totales['60'] + $reg['60'];
			$totales['70'] 		= $totales['70'] + $reg['70'];
			$totales['80'] 		= $totales['80'] + $reg['80'];
			$totales['90'] 		= $totales['90'] + $reg['90'];
			$totales['100'] 	= $totales['100'] + $reg['100'];
			$totales['110'] 	= $totales['110'] + $reg['110'];
			$totales['total'] 	= $totales['total'] + $reg['total'];
			
			$cont_linea++;
			$row=$row+1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cont_linea);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $reg['variedad_id'] );
			if ($reg['tallos_x_bunch']==25)
			{			
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['variedad'] );
			}else{
				$objRichText = new \PHPExcel_RichText();
				$objRichText->createText($reg['variedad'] );
				
				$objInventario = $objRichText->createTextRun(' ('.$reg['tallos_x_bunch'].')');
				$objInventario->getFont()->setBold(true);
				$objInventario->getFont()->setItalic(true);
								
				$col_variedad 			= $PHPExcelApp->getNameFromNumber(2);
				$objInventario->getFont()->setColor(new \PHPExcel_Style_Color(\Application\Classes\PHPExcelApp::COLOR_ORANGE));
				$objPHPExcel->getActiveSheet()->getCell($col_variedad.$row)->setValue($objRichText);
				//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $reg['variedad'] );
			}//end if
			if (!empty($reg['url_ficha']))
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'FOTO');				
				$objPHPExcel->getActiveSheet()->getCell('D'.$row)->getHyperlink()->setUrl($reg['url_ficha']);
			}else{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
			}//end if
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $reg['color_ventas_nombre'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reg['40'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $reg['50'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $reg['60'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $reg['70'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $reg['80'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $reg['90'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $reg['100'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $reg['110'] );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $reg['total'] );
			//$objPHPExcel->getActiveSheet()->setCellValue('N'.$row,$reg['total'] );
		}//end foreach	

		//Totales
		$row=$row+1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'TOTALES');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $totales['40'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totales['50'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $totales['60'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $totales['70'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $totales['80'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $totales['90'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $totales['100'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $totales['110'] );
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $totales['total'] );		
		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row.':'.$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_NEGRILLA));		
		
		
		//Formato de Numeros
		$col_ini 			= $PHPExcelApp->getNameFromNumber(5);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(13);
		$row_detalle_info_ini = $row_detalle_ini +1;		
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_info_ini.':'.$col_fin.$row)->getNumberFormat()->setFormatCode("#,###");		
		
		//Margenes
		$col_ini 			= $PHPExcelApp->getNameFromNumber(0);
		$col_fin 			= $PHPExcelApp->getNameFromNumber(13);
		$objPHPExcel->getActiveSheet()->getStyle($col_ini.$row_detalle_ini.":".$col_fin.$row)->applyFromArray($PHPExcelApp->getStyleArray($PHPExcelApp::STYLE_ARRAY_BORDE_TODO));		
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Dispo General');

		$PHPExcelApp->save($objPHPExcel, $PHPExcelApp::FORMAT_EXCEL_2007, "Dispo General.xlsx" );
				
	}//end function generarExcel

	
	
	public function actualizarCerosStock($ArrDispoData)
	{
		$DispoDAO		= new DispoDAO();

		$DispoDAO->setEntityManager($this->getEntityManager());

		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrDispoData as $DispoData)
			{
				$DispoDAO->actualizarCeroStock($DispoData);
			}//end foreach
		
			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function actualizarCerosStock

	
	
	public function moverStock($ArrDispoData, $grados, $color_ventas_id, $calidad_variedad_id, $clasifica_destino)
	{
		$DispoDAO		= new DispoDAO();
		
		$DispoDAO->setEntityManager($this->getEntityManager());
		
		$this->getEntityManager()->getConnection()->beginTransaction();
		try{
			foreach($ArrDispoData as $DispoData)
			{
				$DispoDAO->moverStock($DispoData, $grados, $color_ventas_id, $calidad_variedad_id, $clasifica_destino);
			}//end foreach

			$this->getEntityManager()->getConnection()->commit();
			return true;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}		
	}//end function moverStock
	
}//end class DispoBO
