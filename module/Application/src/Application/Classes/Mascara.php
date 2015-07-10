<?php

namespace Application\Classes;


class Mascara {
	
	/**
	 * 
	 * @param int $nro_pedido
	 * @param string $mascara
	 * @return string
	 */
	public static function getNroPedidoFormateado($nro_pedido, $mascara)
	{
		$tamano = strlen($mascara);
		$caracter = substr($mascara, 0, 1);
		
		$nro_pedido_formateado = str_pad($nro_pedido, $tamano, $caracter, STR_PAD_LEFT);
		return $nro_pedido_formateado;
	}//end public function getNroPedidoFormateado()
	
}//end class Mascara
