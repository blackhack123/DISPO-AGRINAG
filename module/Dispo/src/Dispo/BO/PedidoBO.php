<?php

namespace Dispo\BO;

use Application\Classes\Conexion;
use Dispo\DAO\PedidoCabDAO;
use Dispo\DAO\PedidoDetDAO;
use Dispo\Data\PedidoCabData;
use Dispo\Data\PedidoDetData;
use Dispo\DAO\GrupoDispoCabDAO;
use Dispo\DAO\PedidoProveedorDAO;
use Dispo\Data\PedidoProveedorData;
use Dispo\DAO\DispoDAO;
use Dispo\Data\DispoData;
use Dispo\Data\GrupoDispoDetData;
use Dispo\DAO\GrupoDispoDetDAO;
use Dispo\Exception\PedidoException;
use Seguridad\DAO\UsuarioDAO;
use Dispo\DAO\ParametrizarDAO;
use Application\Classes\Imagen;
use Application\Classes\MPDFApp;
use Application\Classes\CorreoElectronico;
use Dispo\DAO\ClienteDAO;


class PedidoBO extends Conexion
{
	private $calidad_id 	= null;
	private $punto_corte 	= null;
	
	
	public function getCalidadId()	{return $this->calidad_id;}
	public function getPuntoCorte()	{return $this->punto_corte;}
	
	public function setCalidadId($valor)	{$this->calidad_id 		= $valor;}
	public function setPuntoCorte($valor)	{$this->punto_corte		= $valor;}
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @param string $cliente_id
	 * @param int $usuario_cliente_id
	 * @param int $usuario_vendedor_id
	 * @param int $marcacion_sec
	 * @param int $agencia_carga_id
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param int $tallos_x_bunch
	 * @param string $tipo_caja_id
	 * @param int $nro_cajas_seleccionada
	 * @param string $hueso_producto_id
	 * @param string $hueso_variedad_id
	 * @param string $hueso_grado_id
	 * @param int $hueso_tallos_x_bunch
	 * @param string $hueso_tipo_caja_id
	 * @param int $hueso_nro_cajas_seleccionada
	 * @throws Exception
	 * @return array
	 */
	public function addItemOferta(	$pedido_cab_id, $cliente_id, $usuario_cliente_id, $usuario_vendedor_id, $marcacion_sec, $agencia_carga_id, 
								    $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id, $nro_cajas_seleccionada,
									$hueso_producto_id, $hueso_variedad_id, $hueso_grado_id, $hueso_tallos_x_bunch, $hueso_tipo_caja_id, $hueso_nro_cajas_seleccionada)
	{
		$DispoBO		= new DispoBO();
				
		try
		{
			$this->getEntityManager()->getConnection()->beginTransaction();
						
			$DispoBO->setEntityManager($this->getEntityManager());	

			//Se registra la oferta
			$result_oferta 	= $this->addItem($pedido_cab_id, $cliente_id, $usuario_cliente_id, $usuario_vendedor_id, $marcacion_sec, $agencia_carga_id, 
											 $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id, $nro_cajas_seleccionada, false, 'oferta_carne');
			if ($result_oferta['respuesta_code']!='OK')
			{
				$this->getEntityManager()->getConnection()->rollback();
				return $result_oferta;
			}//end if
			
			if (empty($pedido_cab_id))
			{
				$pedido_cab_id = $result_oferta['pedido_cab_id'];
			}//end if
			
			//Se registra el hueso
			$result_hueso 	= $this->addItem($pedido_cab_id, $cliente_id, $usuario_cliente_id, $usuario_vendedor_id, $marcacion_sec, $agencia_carga_id, 
										 $hueso_producto_id, $hueso_variedad_id, $hueso_grado_id, $hueso_tallos_x_bunch, $hueso_tipo_caja_id, $hueso_nro_cajas_seleccionada,
										 false, 'oferta_hueso', $result_oferta['pedido_cab_sec']);
			if ($result_hueso['respuesta_code']!='OK')
			{
				$this->getEntityManager()->getConnection()->rollback();
				return $result_hueso;
			}//end if

			$this->getEntityManager()->getConnection()->commit();
			return array($result_oferta, $result_hueso);

		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}//end try
	}//end function addItemOferta
	

	/**
	 * Permite crear un Pedido o agregar detalles a un Pedido ya Existente
	 * 
	 * @param int|null $pedido_cab_id
	 * @param string $cliente_id
	 * @param int $usuario_cliente_id
	 * @param int $usuario_vendedor_id
	 * @param int $marcacion_sec
	 * @param string $agencia_carga_id
	 * @param string $producto_id
	 * @param string $variedad_id
	 * @param string $grado_id
	 * @param string $tallos_x_bunch
	 * @param string $tipo_caja_id
	 * @param int $nro_cajas_seleccionada
	 * @return array
	 */
	public function addItem($pedido_cab_id, $cliente_id, $usuario_cliente_id, $usuario_vendedor_id, $marcacion_sec, $agencia_carga_id, 
						    $producto_id, $variedad_id, $grado_id, $tallos_x_bunch, $tipo_caja_id, $nro_cajas_seleccionada,
							$control_transaccion = true, $tipo_oferta = null, $carne_pedido_cab_sec = null)
	{
		$DispoBO		= new DispoBO();		
		$PedidoCabDAO 	= new PedidoCabDAO();
		$PedidoDetDAO 	= new PedidoDetDAO();
		$PedidoCabData	= new PedidoCabData();
		$PedidoDetData	= new PedidoDetData();

		if ($control_transaccion){
			$this->getEntityManager()->getConnection()->beginTransaction();
		}//end if
		try
		{
			$DispoBO->setEntityManager($this->getEntityManager());		
			$PedidoCabDAO->setEntityManager($this->getEntityManager());
			$PedidoDetDAO->setEntityManager($this->getEntityManager());
			

			/*
			 * 1. Se establece cual es el usuario que va ingresar el pedido o anadir items al detalle
			 */
			if (empty($usuario_vendedor_id)){
				$usuario_ing_id = $usuario_cliente_id;
			}else{
				$usuario_ing_id = $usuario_vendedor_id;
			}


			/*
			 * 2. Se obtiene la DISPO ACTUAL
			 */
			$result_dispo = $DispoBO->getDispo($cliente_id, $usuario_cliente_id, $marcacion_sec, $tipo_caja_id, $variedad_id, $grado_id, 
											   false, true, true, $producto_id, $tallos_x_bunch);
			if ($result_dispo['respuesta_code']!='OK'){
				if ($control_transaccion){
					$this->getEntityManager()->getConnection()->rollback();
					$this->getEntityManager()->close();
				}//end if
				return $result_dispo;
			}//end if			
			$reg_dispo	  = $result_dispo['result_dispo'][0];
			
			/*
			 * 3. Revisar si tiene un pedido activo 
			 */
			if (empty($pedido_cab_id))
			{
				//Se llena los campos
				//$PedidoCabData->setId				($pedido_cab_id);
				$PedidoCabData->setFecha			(\Application\Classes\Fecha::getFechaHoraActualServidor());
				$PedidoCabData->setClienteId		($cliente_id);
				$PedidoCabData->setMarcacionSec		($marcacion_sec);
				$PedidoCabData->setAgenciaCargaId	($agencia_carga_id);
				$PedidoCabData->setTotal			(0);
				$PedidoCabData->setComentario		('');
				$PedidoCabData->setEstado			(\Application\Constants\Pedido::ESTADO_COMPRANDO);
				$PedidoCabData->setUsuarioClienteId	($usuario_cliente_id);
				$PedidoCabData->setUsuarioIngId		($usuario_ing_id);
	
				$pedido_cab_id 		= $PedidoCabDAO->ingresar($PedidoCabData);
			}//end if
			
			
			/*
			 * 4. Se registra el detalle
			 */
			$pedido_cab_sec = $PedidoDetDAO->consultarMaximaSecuencia($pedido_cab_id);
			$pedido_cab_sec++;

			$bunch_total 	= $nro_cajas_seleccionada * $reg_dispo['cantidad_bunch']; //Se multiplica por la cantidad de bunch que tiene una caja
			$tallos_total	= $bunch_total * $reg_dispo['tallos_x_bunch'];
			if ($tipo_oferta=='oferta_carne')
			{
				$precio 		= $reg_dispo['precio_oferta'];
			}else{
				$precio 		= $reg_dispo['precio'];
			}//end if
			$precio_total	= $tallos_total *  $precio; //Se multiplica el precio del tallo		

			//Se define el total x caja
			if (empty($precio))
			{
				$total_x_caja = 0;
			}else{
				$total_x_caja = $reg_dispo['tallos_x_bunch'] * $bunch_total/$nro_cajas_seleccionada * $precio;
			}//end if

			$PedidoDetData->setPedidoCabId			($pedido_cab_id);
			$PedidoDetData->setPedidoDetSec 		($pedido_cab_sec);
//			$PedidoDetData->setMarcacionSec			($marcacion_sec);
			$PedidoDetData->setInventarioId			($reg_dispo['inventario_id']);
			$PedidoDetData->setVariedadId			($variedad_id);
			$PedidoDetData->setGradoId				($grado_id);
			$PedidoDetData->setTipoCajaId			($tipo_caja_id);
			$PedidoDetData->setTipoCajaOrigenEstado	($reg_dispo['tipo_caja_origen_estado']);
			$PedidoDetData->setTipoCajaOrigenId		($reg_dispo['tipo_caja_origen_id']);
			$PedidoDetData->setNroCajas				($nro_cajas_seleccionada);
			$PedidoDetData->setCantidadBunch		($bunch_total);
			$PedidoDetData->setTallosxBunch			($reg_dispo['tallos_x_bunch']);
			$PedidoDetData->setTallosTotal			($tallos_total);
			$PedidoDetData->setPrecio				($precio);
			$PedidoDetData->setTotalXCaja 			($total_x_caja);
			$PedidoDetData->setTotal				($precio_total);
//			$PedidoDetData->setAgenciaCargaId		($agencia_carga_id);
//			$PedidoDetData->setComentario			('');
			if ($tipo_oferta=='oferta_hueso')
			{
				$PedidoDetData->setPedidoCabOfertaId 	($pedido_cab_id);
				$PedidoDetData->setPedidoDetOfertaSec 	($carne_pedido_cab_sec);
			}else{
				$PedidoDetData->setPedidoCabOfertaId 	(null);
				$PedidoDetData->setPedidoDetOfertaSec 	(null);
			}//end if
			if ($tipo_oferta=='oferta_carne')
			{
				$PedidoDetData->setEstadoRegOferta		(1);
			}else{
				$PedidoDetData->setEstadoRegOferta		(0);				
			}//end if
			$PedidoDetData->setCalidadId			($this->calidad_id);
			$PedidoDetData->setPuntoCorte			($this->punto_corte);  //MORONITOR

			//conversion a caja FB
			$cajas_fb = \Application\Classes\CajaConversion::equivalenciaFB($tipo_caja_id, $nro_cajas_seleccionada);
			$PedidoDetData->setEqFb					($cajas_fb);
			
			$PedidoDetData->setUsuarioIngId			($usuario_ing_id);
			$PedidoDetData->setUsuarioModId			($usuario_ing_id);
			$key_det =  $PedidoDetDAO->ingresar($PedidoDetData);
			
			/*
			 * 5. Actualizar el total de la CABECERA de PEDIDO
			 */
			$PedidoCabDAO->actualizarTotal($pedido_cab_id);

			/*
			 * 6. Devuelve el resultado del registro del PEDIDO
			 */
			$result['respuesta_code']	= 'OK';
			$result['respuesta_msg']	= '';
			$result['pedido_cab_id'] 	= $pedido_cab_id;
			$result['pedido_cab_sec'] 	= $pedido_cab_sec;
			$result['variedad_nombre']	= $reg_dispo['variedad_nombre'];
			
			if ($control_transaccion){
				$this->getEntityManager()->getConnection()->commit();
			}//end if
			return $result;
			
		} catch (Exception $e) {
			if ($control_transaccion){
				$this->getEntityManager()->getConnection()->rollback();
				$this->getEntityManager()->close();				
			}//end if
			throw $e;
		}			
	}//end function addItem


	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return number
	 */
	function consultarNroItemsPorPedido($pedido_cab_id, $estado = null)
	{
		$PedidoDetDAO 	= new PedidoDetDAO();
		
		$PedidoDetDAO->setEntityManager($this->getEntityManager());
		
		$nro_items = $PedidoDetDAO->consultarNroItemsPorPedido($pedido_cab_id, $estado);
		
		return $nro_items;
	}//end function consultarNroItemsPorPedido
	
	

	/**
	 * 
	 * @param string $cliente_id
	 * @return array
	 */
	function consultarUltimoPedidoComprando($cliente_id)
	{
		$PedidoCabDAO 	= new PedidoCabDAO();
		
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
		
		$reg = $PedidoCabDAO->consultarUltimoPedidoComprando($cliente_id);
		
		return $reg;
	}//end function consultarUltimoPedidoComprando
	
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return array  (Registro Cabecera y Registros Detalles)
	 */
	public function consultarPedido($pedido_cab_id)
	{
		$PedidoCabDAO			= new PedidoCabDAO();
		$PedidoDetDAO			= new PedidoDetDAO();
		
		$PedidoCabDAO->setEntityManager				($this->getEntityManager());
		$PedidoDetDAO->setEntityManager				($this->getEntityManager());
		
		$reg_pedido_cab 	= $PedidoCabDAO->consultar($pedido_cab_id, \Application\Constants\ResultType::MATRIZ);
		$rs_pedido_det 		= $PedidoDetDAO->consultarPorPedidoCabId($pedido_cab_id);
/*		$marcacion_nombre 	= '';
		if ($rs_pedido_det){
			if ($rs_pedido_det[0]['marcacion_nombre'])
			{
				$marcacion_nombre = $rs_pedido_det[0]['marcacion_nombre'];
			}
		}//end if
		$reg_pedido_cab['marcacion_nombre']	= $marcacion_nombre;
*/		return array($reg_pedido_cab, $rs_pedido_det);
	}//end function consultarPedido	

	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return array
	 */
	public function consultarPedidoCabecera($pedido_cab_id)
	{
		$PedidoCabDAO			= new PedidoCabDAO();
		
		$PedidoCabDAO->setEntityManager				($this->getEntityManager());
		
		$reg_pedido_cab 	= $PedidoCabDAO->consultar($pedido_cab_id, \Application\Constants\ResultType::MATRIZ);
		return $reg_pedido_cab;
	}//end function consultarPedidoCabecera	
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @param int $type_result
	 * @return Ambigous <\Dispo\Data\PedidoDetData, NULL>|array
	 */
	public function consultarDetallePedido($pedido_cab_id, $pedido_det_sec, $type_result = \Application\Constants\ResultType::OBJETO)
	{
		$PedidoDetDAO			= new PedidoDetDAO();
		
		$PedidoDetDAO->setEntityManager				($this->getEntityManager());
		
		switch ($type_result){
			case \Application\Constants\ResultType::OBJETO:
				break;
				$PedidoDetData 	= $PedidoDetDAO->consultar($pedido_cab_id, $pedido_det_sec);
				return $PedidoDetData;
				
			case \Application\Constants\ResultType::MATRIZ:
				$reg 	= $PedidoDetDAO->consultarArray($pedido_cab_id, $pedido_det_sec);
				return $reg;
		}//end switch
	}//end function consultarDetallePedido
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @return array
	 */
	public function consultarPedidoDetUltimoRegistro($pedido_cab_id)
	{
		$PedidoDetDAO	= new PedidoDetDAO();
		
		$PedidoDetDAO->setEntityManager				($this->getEntityManager());
		$reg 		= $PedidoDetDAO->consultarPorPedidoCabIdUltimoRegistro($pedido_cab_id);	

		return $reg;
	}//end function consultarPedidoDetUltimoRegistro	
	
	
	
	/**
	 * 
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @throws Exception
	 * @return number
	 */
	public function eliminarPorPedidoCabIdPorPedidoDetSec($pedido_cab_id, $pedido_det_sec)
	{
		$PedidoDetDAO 	= new PedidoDetDAO();
		$PedidoCabDAO	= new PedidoCabDAO();
		
		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$PedidoCabDAO->setEntityManager($this->getEntityManager());
			$PedidoDetDAO->setEntityManager($this->getEntityManager());		
			
			//Se elimina primera la oferta vinculada
			$respuesta 		= $PedidoDetDAO->eliminarOferta($pedido_cab_id, $pedido_det_sec);
			
			//Se elimina el detalle del pedido
			$respuesta 		= $PedidoDetDAO->eliminar($pedido_cab_id, $pedido_det_sec);
			$nro_regs_det	= $PedidoDetDAO->consultarNroItemsPorPedido($pedido_cab_id);
			
			//Si no tiene registro borra la cabecera del pedido
			if (empty($nro_regs_det)){
				$PedidoCabDAO = $PedidoCabDAO->eliminar($pedido_cab_id);
			}//end if
			
			$this->getEntityManager()->getConnection()->commit();
			return $nro_regs_det;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function eliminarPorPedidoCabIdPorPedidoDetSec
	
	
	
	

	/**
	 * 
	 * @param PedidoCabData $PedidoCabData
	 * @return boolean
	 */
	public function grabarComentario(PedidoCabData $PedidoCabData)
	{
		$PedidoCabDAO	= new PedidoCabDAO();
		
		$PedidoCabDAO->setEntityManager($this->getEntityManager());		
		$PedidoCabDAO->actualizarComentario($PedidoCabData);
		return true;
	}//end function grabarComentario


	
	/**
	 * 
	 * @param PedidoCabData $PedidoCabData
	 */
	public function cambiarAgenciaCarga(PedidoCabData $PedidoCabData)
	{
		//$PedidoDetDAO	= new PedidoDetDAO();
		$PedidoCabDAO	= new PedidoCabDAO();

		//$PedidoDetDAO->setEntityManager($this->getEntityManager());
		//$PedidoDetDAO->cambiarAgenciaCarga($PedidoDetData);
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
		$PedidoCabDAO->cambiarAgenciaCarga($PedidoCabData);
		return true;
	}//end function cambiarAgenciaCarga
	

	
	
	/**
	 * Confirma el pedido y rebaja el inventario correspondiente de las fincas
	 * con sus respectivas validaciones
	 * 
	 * @param int $pedido_cab_id
	 * @param int $usuario_id
	 * @throws PedidoException
	 * @throws Exception
	 * @return array>
	 */
	public function confirmar($pedido_cab_id, $usuario_cliente_id, $usuario_vendedor_id)
	{
		$PedidoDetDAO 			= new PedidoDetDAO();
		$PedidoCabDAO			= new PedidoCabDAO();
		$PedidoProveedorDAO		= new PedidoProveedorDAO();
		$GrupoDispoCabDAO  		= new GrupoDispoCabDAO();
		$DispoDAO				= new DispoDAO();
		$GrupoDispoDetDAO 		= new GrupoDispoDetDAO();
		$DispoBO				= new DispoBO();
		$UsuarioDAO				= new UsuarioDAO();
		$ParametrizarDAO 		= new ParametrizarDAO();
		
		$PedidoProveedorData	= new PedidoProveedorData();

		
		$this->getEntityManager()->getConnection()->beginTransaction();

		try
		{
			if (empty($usuario_vendedor_id))
			{
				$usuario_procesa_id = $usuario_cliente_id;
			}else{
				$usuario_procesa_id = $usuario_vendedor_id;
			}//end if
			
			$PedidoCabDAO->setEntityManager($this->getEntityManager());
			$PedidoDetDAO->setEntityManager($this->getEntityManager());
			$GrupoDispoCabDAO->setEntityManager($this->getEntityManager());
			$PedidoProveedorDAO->setEntityManager($this->getEntityManager());
			$DispoDAO->setEntityManager($this->getEntityManager());
			$GrupoDispoDetDAO->setEntityManager($this->getEntityManager());
			$DispoBO->setEntityManager($this->getEntityManager());
			$UsuarioDAO->setEntityManager($this->getEntityManager());
			$ParametrizarDAO->setEntityManager($this->getEntityManager());
			
			/**
			 * Consulta datos relacionados al usuario
			 */
			$row_usuario = $UsuarioDAO->consultar($usuario_cliente_id, \Application\Constants\ResultType::MATRIZ);
			if (empty($row_usuario))
			{
				throw new PedidoException('Usuario no tiene asignado un grupo!!');
			}else{
/*				$grupo_precio_cab_id= $row_usuario['grupo_precio_cab_id']; //MORONITOR
				$grupo_dispo_cab_id = $row_usuario['grupo_dispo_cab_id'];
				$inventario_id 		= $row_usuario['inventario_id'];
*/				$clasifica_fox		= $row_usuario['clasifica_fox'];
				$calidad_id			= $row_usuario['calidad_id'];
			}//end if
					
			
			
			$PedidoCabData = $PedidoCabDAO->consultar($pedido_cab_id);
			if ($PedidoCabData->getEstado()==\Application\Constants\Pedido::ESTADO_ACTIVO){
				throw new PedidoException('El pedido ya fue procesado anteriormente!!');
			}//end if
			
			/**
			 * Consulta el GRUPO_DISPO_CAB_ID del usuario del cliente
			 */
			$GrupoDispoCabData = $GrupoDispoCabDAO->consultarPorUsuarioId($usuario_cliente_id);
			if(empty($GrupoDispoCabData)){
				throw new PedidoException('Usuario no tiene asignado un grupo de dispo!!');
			}//end foreach

			/**
			 * Se obtiene los detalles de los pedidos, siempre y cuando el estado de la CABECERA DEL PEDIDO
			 * este en estado COMPRANDO, por tema de seguridad se pregunta este estado en el QUERY
			 */
			$result_pedido =  $PedidoDetDAO->consultarPorPedidoCabId($pedido_cab_id, \Application\Constants\Pedido::ESTADO_COMPRANDO);
			
			if (empty($result_pedido)){
				throw new PedidoException('No existe detalle para el pedido!!');
			}//end if
			
			/*
			 * 2. Verificar si existe el stock en las fincas,
			 *    en caso de estar incompleto el stock devolver lo que este disponible sin grabar
			 */
			$arr_novedad_pedido_det 	= array();  //En caso de tener registro en este array, significa que no existe stock para ese detalle
			$bd_rollback 				= false;
			foreach($result_pedido as $reg_pedido){
				/**
				 * Obtiene la dispo Actual
				 */				
				$result_dispo_actual = $DispoBO->getDispo(	$reg_pedido['cliente_id'], $usuario_cliente_id, $reg_pedido['marcacion_sec'],
															$reg_pedido['tipo_caja_id'], $reg_pedido['variedad_id'], $reg_pedido['grado_id'],
															true, false, true, $reg_pedido['variedad_producto_id'], $reg_pedido['tallos_x_bunch']);
				if (empty($result_dispo_actual)){
					throw new PedidoException('Dispo Vacia, no es posible confirmar el pedido');
				}//end if

				switch($result_dispo_actual['respuesta_code'])
				{
					case 'OK':
						$reg_dispo_actual	= $result_dispo_actual["result_dispo"][0];
						break;
						
					case 12: //Sin registro en el inventario del grupo del cliente
					case 13: //Sin stock en el grupo del cliente
						$reg_dispo_actual['nro_cajas']	= 0; //Se emula que la dispo Actual tiene CERO cajas.
						break;
						
					default:
						$this->getEntityManager()->getConnection()->rollback();
						$result = array(
								'respuesta' => $result_dispo_actual['respuesta_code'],
								'respuesta_descripcion' => $result_dispo_actual['respuesta_msg'],
								'novedades_pedido_det'	=> null
						);
						return $result;						
				}//end switch
				
								
				if ($reg_pedido['nro_cajas'] > $reg_dispo_actual['nro_cajas'])
				{
					$bd_rollback						= true;					
					$reg_pedido['nro_cajas_en_stock']	= $reg_dispo_actual['nro_cajas'];
					$arr_novedad_pedido_det[]			= $reg_pedido;
				}else{
					/*
					 * 2. Grabar el Pedido de las Fincas
					 */
					$pedido_nro_cajas = $reg_pedido['nro_cajas'];
						
					foreach($reg_dispo_actual['proveedores_dispo'] as $proveedor_dispo)
					{
						if ($pedido_nro_cajas > $proveedor_dispo['nro_cajas'])
						{
							$nro_cajas 			= $proveedor_dispo['nro_cajas'];
							$pedido_nro_cajas 	= $pedido_nro_cajas - $nro_cajas;
						}else{
							$nro_cajas 			= $pedido_nro_cajas;
							$pedido_nro_cajas 	=  0;
						}//end if

						$cantidad_bunchs	= $nro_cajas * $reg_dispo_actual['cantidad_bunch'];  
						$tallos_total		= $cantidad_bunchs * $reg_dispo_actual['tallos_x_bunch'];
						$total				= $tallos_total * $reg_dispo_actual['precio'];

						//Se crea la Data para PedidoProveedorData
						$PedidoProveedorData	= new PedidoProveedorData();
						$PedidoProveedorData->setPedidoCabId	($reg_pedido['pedido_cab_id']);
						$PedidoProveedorData->setPedidoDetSec	($reg_pedido['pedido_det_sec']);
						$PedidoProveedorData->setProveedorId	($proveedor_dispo['proveedor_id']);
						$PedidoProveedorData->setNroCajas		($nro_cajas);
						$PedidoProveedorData->setCantidadBunch	($cantidad_bunchs);
						$PedidoProveedorData->setTallosxBunch	($reg_pedido['tallos_x_bunch']);
						$PedidoProveedorData->setTallosTotal	($tallos_total);
						$PedidoProveedorData->setVariedadId		($reg_pedido['variedad_id']);
						$PedidoProveedorData->setGradoId		($reg_pedido['grado_id']);
						$PedidoProveedorData->setPrecio			($reg_pedido['precio']);
						$PedidoProveedorData->setTotal			($total);

						//Equivalencias de Caja FB
						$cajas_fb	= \Application\Classes\CajaConversion::equivalenciaFB($reg_pedido['tipo_caja_id'], $nro_cajas) ;
						$PedidoProveedorData->setEqFb			($cajas_fb);

						$key_pedidoproveedor = $PedidoProveedorDAO->ingresar($PedidoProveedorData);
							 	
						/*
						 * 3. Rebaja de la DISPO GENERAL
						 */
						$result_dispo = $DispoDAO->consultarInventarioPorProveedor(
																					$proveedor_dispo['proveedor_id'], 
																					$reg_dispo_actual['inventario_id'],
																					$reg_dispo_actual['producto_id'],
																					$reg_dispo_actual['variedad_id'], 
																					$reg_dispo_actual['grado_id'],
																					$reg_dispo_actual['tallos_x_bunch'],
																					$clasifica_fox
																					);
							
						//$result_dispo = null;
						if (empty($result_dispo))
						{
							throw new PedidoException('No hay bunches disponibles en la DISPO - FINCA');
						}//end if

						$total_bunch_pedido = $cantidad_bunchs;
						foreach($result_dispo as $row_dispo)
						{
							if ($row_dispo['cantidad_bunch_disponible'] > $total_bunch_pedido)
							{
								$cantidad_descontar =  $total_bunch_pedido;
								$total_bunch_pedido	=  0;
							}else{
								$cantidad_descontar = $row_dispo['cantidad_bunch_disponible'];
								$total_bunch_pedido	= $total_bunch_pedido - $row_dispo['cantidad_bunch_disponible'];
							}//end if
								
						
							//REBAJA DE LA DISPO
							$DispoData = new DispoData();
							$DispoData->setFecha			($row_dispo['fecha']);
							$DispoData->setInventarioId		($row_dispo['inventario_id']);
							$DispoData->setFechaBunch		($row_dispo['fecha_bunch']);
							$DispoData->setProveedorId		($row_dispo['proveedor_id']);
							$DispoData->setProducto			($row_dispo['producto']);
							$DispoData->setVariedadId		($row_dispo['variedad_id']);
							$DispoData->setGradoId			($row_dispo['grado_id']);
							$DispoData->setTallosxBunch 	($row_dispo['tallos_x_bunch']);
							$DispoData->setClasifica		($clasifica_fox); //NUEVO

							$DispoDAO->rebajar($DispoData, $cantidad_descontar);
							
							//REBAJA DE LA DISPO POR GRUPO
							$DispoGrupoDetData 	= new GrupoDispoDetData();
							$DispoGrupoDetData->setGrupoDispoCabId		($GrupoDispoCabData->getId());
							$DispoGrupoDetData->setProductoId 			($row_dispo['producto']);
							$DispoGrupoDetData->setVariedadId			($row_dispo['variedad_id']);
							$DispoGrupoDetData->setGradoId				($row_dispo['grado_id']);
							$DispoGrupoDetData->setTallosXBunch 		($row_dispo['tallos_x_bunch']);
							
							$GrupoDispoDetDAO->rebajar($DispoGrupoDetData, $cantidad_descontar);
							
							if ($total_bunch_pedido==0)
							{
								break;  //SALE DEL WHILE
							}//end if
						}//end while
				
							
						if ($pedido_nro_cajas==0)
						{
							break;  //Sale del ciclo foreach
						}
					}//end foreach

					list($key_pedidocab, $reg_pedidocab) = $PedidoCabDAO->actualizarEstado($pedido_cab_id, \Application\Constants\Pedido::ESTADO_ACTIVO, $usuario_procesa_id);
				}//end if  //CONTROL DE bd_rollback
			}//end foreach
			
			if ($bd_rollback==true)
			{
				$this->getEntityManager()->getConnection()->rollback();
				$result = array(
									'respuesta' 			=> 'NOVEDAD',
									'novedades_pedido_det'	=> $arr_novedad_pedido_det,
									'nro_dias_procesa'		=> '',
								    'dia_semana_procesa'	=> ''
								);					
			}else{
				/*-------------Indica los dias que debe que se procesan---*/
				$reg_despacho = $ParametrizarDAO->getDiaDespacho($reg_pedidocab['fec_confirmado']);
								
				$this->getEntityManager()->getConnection()->commit();
				
				$this->enviarEmailAdjuntoPDF($pedido_cab_id);
				
				/*--------------Devuelve el resultado --------------------*/
				$result = array	(
								'respuesta' 			=> 'OK',
								'novedades_pedido_det'	=> '',
								'nro_dias_procesa'		=> $reg_despacho['nro_dias_procesa'],
								'dia_semana_procesa'	=> $reg_despacho['dia_semana_procesa']
								);
			}//end if
			
			return $result;
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}//end try	
	}//end function confirmarPedido

	
	/**
	 * Actualiza el numero de cajas del pedido
	 * 
	 * @param int $pedido_cab_id
	 * @param int $pedido_det_sec
	 * @param int $nro_cajas_new
	 * @param int $usuario_id
	 * @throws Exception
	 * @return array
	 */
	public function actualizarNroCajas($pedido_cab_id, $pedido_det_sec, $nro_cajas_new, $usuario_id)
	{
		$DispoBO		= new DispoBO();
		$PedidoCabDAO 	= new PedidoCabDAO();
		$PedidoDetDAO 	= new PedidoDetDAO();
		$PedidoCabData	= new PedidoCabData();
		$PedidoDetData	= new PedidoDetData();

		$this->getEntityManager()->getConnection()->beginTransaction();
		try
		{
			$DispoBO->setEntityManager($this->getEntityManager());
			$PedidoCabDAO->setEntityManager($this->getEntityManager());
			$PedidoDetDAO->setEntityManager($this->getEntityManager());
				
	
			/*
			 * 1. Actualiza el detalle del Pedido
			 */
			$PedidoDetData 	= $PedidoDetDAO->consultar($pedido_cab_id, $pedido_det_sec);
			
			if (empty($PedidoDetData))
			{
				$result['respuesta_code']	= 'NO-EXISTE-DET';
				$result['respuesta_msg']	= 'No existe detalle del Pedido';
				$this->getEntityManager()->getConnection()->rollback();
				return $result;
			}//end if

			$cantidad_bunch_x_caja = $PedidoDetData->getCantidadBunch()/$PedidoDetData->getNroCajas();
			$cantidad_bunch  = $nro_cajas_new * $cantidad_bunch_x_caja;
			$tallos_total	 = $cantidad_bunch * $PedidoDetData->getTallosxBunch();
			$total			 = $tallos_total * $PedidoDetData->getPrecio();

			$PedidoDetData->setPedidoCabId			($pedido_cab_id);
			$PedidoDetData->setPedidoDetSec 		($pedido_det_sec);
			$PedidoDetData->setNroCajas				($nro_cajas_new);
			$PedidoDetData->setCantidadBunch		($cantidad_bunch);
			$PedidoDetData->setTallosTotal			($tallos_total);
			$PedidoDetData->setTotal				($total);
			$PedidoDetData->setUsuarioModId			($usuario_id);
			$key_det =  $PedidoDetDAO->actualizarNroCajas($PedidoDetData);			

			
			/*
			 * 2. Se actualiza la cabecera del Pedido
			 */
			$PedidoCabDAO->actualizarTotal($pedido_cab_id);
				
				
			/*
			 * 3. Devuelve el resultado del registro del PEDIDO
			*/
			$result['respuesta_code']	= 'OK';
			$result['respuesta_msg']	= '';
				
			$this->getEntityManager()->getConnection()->commit();
			return $result;
				
		} catch (Exception $e) {
			$this->getEntityManager()->getConnection()->rollback();
			$this->getEntityManager()->close();
			throw $e;
		}
	}//end function actualizarNroCajas
	
	
	/**
	 * 
	 * @param array $condiciones
	 * @return array
	 */
	public function listado($condiciones)
	{
		$PedidoCabDAO 	= new PedidoCabDAO();
		
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
		
		$result = $PedidoCabDAO->listado($condiciones);
		
		return $result;
	}//end functino listado
	
	
	/**
	 * 
	 * @param array $condiciones  array(pedido_cab_id, cliente_id)
	 * @return array
	 */
	public function consultarPedidoDetalle($condiciones)
	{
		$PedidoDetDAO 	= new PedidoDetDAO();
		
		$PedidoDetDAO->setEntityManager($this->getEntityManager());

		$result = $PedidoDetDAO->listado($condiciones);
		
		return $result;		
	}//end function consultarPedidoDetalle
	
	
	/**
	 *
	 * @param array $condiciones
	 * @return array
	 */
	public function listadoVendedor($condiciones)
	{
		$PedidoCabDAO 	= new PedidoCabDAO();
	
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
	
		$result = $PedidoCabDAO->listadoVendedor($condiciones);
	
		return $result;
	}//end functino listado

	
	/**
	 *
	 * @param PedidoDetData $PedidoDetData
	 * @return boolean
	 */
	public function grabarMarca(PedidoDetData $PedidoDetData)
	{
		$PedidoDetDAO	= new PedidoDetDAO();
	
		$PedidoDetDAO->setEntityManager($this->getEntityManager());
		$PedidoDetDAO->actualizarMarca($PedidoDetData);
		return true;
	}//end function grabarComentario

	
	
	public function generarPDF($pedido_cab_id, $salida_archivo_pdf)
	{
		$MPDFApp					= new MPDFApp();
		$PedidoCabDAO 				= new PedidoCabDAO();
		$PedidoDetDAO				= new PedidoDetDAO();
		
		$reader = new \Zend\Config\Reader\Ini();
		$config  = $reader->fromFile('ini/config.ini');

		$plantilla_pdf	= $config['plantilla_pdf']['dispo']['ruta'] . "pdf_pedido.html";
		$plantilla_html	= utf8_decode(file_get_contents($plantilla_pdf));
		
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
		$PedidoDetDAO->setEntityManager($this->getEntityManager());
		
		//Consulta la cabecera del pedido
		$reg_pedido_cab = $PedidoCabDAO->consultar($pedido_cab_id, \Application\Constants\ResultType::MATRIZ);

		//Consulta el detalle del pedido
		$condiciones = array(
				'pedido_cab_id'  => $pedido_cab_id,
				'cliente_id'	 => null
		);
		$result_det = $PedidoDetDAO->listado($condiciones);
		
		//Inicializa la configuracion
		//set_time_limit ( 0 );
		ini_set('memory_limit','-1');
		
		//$mpdf=new \mPDF('c', array(210, 297),10,'serif',2,2,30,13,9,9,'L');
		$mpdf=new \mPDF('c', array(210, 297),10,'serif',10,10,40,13,9,9,'P');
		
		$mpdf->useSubstitutions=false;
		$mpdf->simpleTables = true;
		$mpdf->SetDisplayMode('fullpage');
		
		$html = $plantilla_html;
		
		//Fecha Impresion
		$fec_impresion = \Application\Classes\Fecha::getFechaImpresion(\Application\Classes\Fecha::getFechaHoraActualServidor(),  \Application\Classes\Fecha::IMPRESION_FECHA_LARGA);
		$html = str_replace('$$fec_impresion$$', $fec_impresion, $html);
		//Numero de Pedido
		$html = str_replace('$$pedido_cab_id$$', \Application\Classes\Mascara::getNroPedidoFormateado($pedido_cab_id, $config['pedido']['mascara']), $html);
		//Nombre del Cliente
		$html = str_replace('$$nombre_cliente$$', $reg_pedido_cab['cliente_nombre'], $html);
		//Direccion
		$html = str_replace('$$direccion_cliente$$', $reg_pedido_cab['cliente_direccion'], $html);
		//Telefono 1
		$html = str_replace('$$telefono1_cliente$$', $reg_pedido_cab['cliente_telefono1'], $html);
		//Fax 1
		$html = str_replace('$$fax1_cliente$$', $reg_pedido_cab['cliente_fax1'], $html);
		//Nombre Marcacion
		$html = str_replace('$$nombre_marcacion$$', $reg_pedido_cab['marcacion_nombre'], $html);
		//Nombre Marcacion
		$html = str_replace('$$direccion_marcacion$$', $reg_pedido_cab['marcacion_direccion'], $html);
		//Telefono Marcacion
		$html = str_replace('$$telefono_marcacion$$', $reg_pedido_cab['marcacion_telefono'], $html);
		//Fecha Confirmado		
		$fec_ingreso = \Application\Classes\Fecha::getFechaImpresion($reg_pedido_cab['fec_ingreso'],  \Application\Classes\Fecha::IMPRESION_FECHA_LARGA);
		$html = str_replace('$$fec_creado$$', $fec_ingreso, $html);
		//Fecha Confirmado
		$fec_confirmado = \Application\Classes\Fecha::getFechaImpresion($reg_pedido_cab['fec_confirmado'],  \Application\Classes\Fecha::IMPRESION_FECHA_LARGA);
		$html = str_replace('$$fec_confirmado$$', $fec_confirmado, $html);
		
		$html_det 	= '';
		$total    	= 0;
		$tot_eq_fb	= 0;
		
		foreach($result_det as $reg)
		{
			$format_price_item 	= '$'.number_format($reg['precio'], 2);
			$format_total_box  	= '$'.number_format($reg['total_x_caja'], 2);
			$format_total		= '$'.number_format($reg['total'], 2);			
			$html_det = $html_det.'
				<tr>
				<td>'.$reg['variedad_nombre'].'</td>
				<td align="center">'.$reg['grado_id'].'</td>
				<td align="center">'.$reg['tallos_x_bunch'].'</td>
				<td align="center">'.$reg['tipo_caja_nombre'].'</td>
				<td align="center">'.$reg['cantidad_bunch']/$reg['nro_cajas'].'</td>
				<td align="center">'.$reg['nro_cajas'].'</td>
				<td align="center">'.$reg['tallos_total'].'</td>
				<td align="right">'.$format_price_item.'</td>
				<td align="right">'.$format_total_box.'</td>
				<td align="right" >'.$format_total.'</td>
				<td>'.$reg['marca'].'</td>
				</tr>
			';
			
			$total= $total + $reg['total'];
			$tot_eq_fb = $tot_eq_fb + $reg['eq_fb'];
		}//end foreach
		
		//Detalles del Pedido
		$html = str_replace('$$detalle_factura$$', $html_det, $html);
		//Total Eq FB
		$html = str_replace('$$eq_fb$$', $tot_eq_fb, $html);
		//Total del Pedido
		$html = str_replace('$$total$$', $total, $html);
		
		$mpdf->WriteHTML($html);

		if (empty($salida_archivo_pdf))
		{
			$mpdf->Output();
		}else{
			$mpdf->Output($salida_archivo_pdf,'F');
		}//end if
		
		return true;
	}//end function generarPDF



	function enviarEmailAdjuntoPDF($pedido_cab_id)
	{
		$reader = new \Zend\Config\Reader\Ini();
		$config  = $reader->fromFile('ini/config.ini');
/*		var_Dump($config);
		die();
*/		
		$PedidoCabDAO 	= new PedidoCabDAO();		
		$ClienteDAO		=	new ClienteDAO();
		$PedidoCabDAO->setEntityManager($this->getEntityManager());
		$ClienteDAO->setEntityManager($this->getEntityManager());

		//Consulta el registro de la cabecera del Pedido
		$PedidoCabData = $PedidoCabDAO->consultar($pedido_cab_id);
		
		//Consultar Registro Cliente
		$reg_cliente = $ClienteDAO->consultar($PedidoCabData->getClienteId(), \Application\Constants\ResultType::MATRIZ);

		//Generar PDF
		$salida_archivo_pdf = $config['ruta_archivos']['tmp'].'order'.$pedido_cab_id.".pdf"; 
		$this->generarPDF($pedido_cab_id, $salida_archivo_pdf);

		//Envia Email
		$CorreoElectronico = new CorreoElectronico();
		$destinatario 	= $reg_cliente['email'];	
		$cc 			= $reg_cliente['usuario_vendedor_email'];
		$titulo			= 'Agrinag Order #'.
						  \Application\Classes\Mascara::getNroPedidoFormateado($pedido_cab_id, $config['pedido']['mascara']).
						  ' - '.ucwords($reg_cliente['nombre']);
		$html			= 'Processed by: Web';

		$resultadoEnvio = $CorreoElectronico->SendMail($destinatario, $cc, $titulo, $html, $cc, $salida_archivo_pdf);
		
		//elimina el archivo
		unlink($salida_archivo_pdf);		
	}//end function enviarEmailAdjuntoPDF
	
	
	
}//end class PedidoBO
