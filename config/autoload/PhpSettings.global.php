<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
	'phpSettings'   => array(
			'display_startup_errors'        => true,
			'display_errors'                => true,
			'max_execution_time'            => 60,
			'date.timezone'                 => 'America/Guayaquil',
			//'mbstring.internal_encoding'    => 'UTF-8',
	),

    'mascara_pedido'  			=> '00000000',
	'tallos_x_bunch_default' 	=> 25,

	'formatoFecha'	=> array(
			'corta'=> array(
					'frontend' 	=> 'd-m-Y',//'d-m-Y', //'Y-m-d',
					'servidor'  => 'Y-m-d',
			),
			'larga'=>array(
					'frontend' 	=> 'd-m-Y H:i:s',//'d-m-Y H:i:s',  //'Y-m-d H:i:s',
					'servidor'  => 'Y-m-d H:i:s',
			),
	),

	'url_server_integrador' => 'http://181.198.42.202:8159/sincronizador/public'		
);
