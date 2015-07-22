<?php
namespace Dispo;

return array(

    'controller_plugins' => array(
        'invokables' => array(
        ),
	),
    'controllers' => array(
        'invokables' => array(
            'Dispo\Controller\Cds' 					=> 'Dispo\Controller\CdsController',
        	'Dispo\Controller\Disponibilidad' 		=> 'Dispo\Controller\DisponibilidadController',
       		'Dispo\Controller\Marcacion' 			=> 'Dispo\Controller\MarcacionController',
        	'Dispo\Controller\Agenciacarga' 		=> 'Dispo\Controller\AgenciacargaController',
        	'Dispo\Controller\Pedido'		 		=> 'Dispo\Controller\PedidoController',
        	'Dispo\Controller\Cliente'		 		=> 'Dispo\Controller\ClienteController',
        	'Dispo\Controller\Variedad'		 		=> 'Dispo\Controller\VariedadController',
        	'Dispo\Controller\transportadora'		=> 'Dispo\Controller\TransportadoraController',
        	'Dispo\Controller\usuario'				=> 'Dispo\Controller\UsuarioController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
       		'dispo-disponibilidad' => array(
       				'type'    => 'segment',
       				'options' => array(
       						'route'    => '/dispo/disponibilidad[/:action][/:id]',
       						'constraints' => array(
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
       								'id'     => '[0-9]+',
       						),
       						'defaults' => array(
       								'controller' => 'Dispo\Controller\Disponibilidad',
       								'action'     => 'listado',
       						),
       				),
       		),   
        	'dispo-disponibilidad-listado' => array(
        			'type'    => 'segment',
       				'options' => array(
       						'route'    => '/dispo/disponibilidad/listado[/:id]',
       						'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[0-9]+',
        					),
       						'defaults' => array(
       								'controller' => 'Dispo\Controller\Disponibilidad',
       								'action'     => 'listado',
       						),
        			),
       		),
        	'dispo-disponibilidad-seleccionar-marcacion-agencia' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/disponibilidad[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Disponibilidad',
        							'action'     => 'seleccionarMarcacionAgencia',
        					),
        			),
        	),        		
       		'dispo-marcacion' => array(
       				'type'    => 'segment',
        				'options' => array(
       						'route'    => '/dispo/marcacion[/:action][/:id]',
       						'constraints' => array(
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
       								'id'     => '[0-9]+',
       						),
       						'defaults' => array(
       								'controller' => 'Dispo\Controller\Marcacion',
       								'action'     => 'index',
       						),
       				),
        	),
        	'dispo-agenciacarga' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/agenciacarga[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Agenciacarga',
        							'action'     => 'index',
        					),
        			),
        	),
        	'dispo-pedido' => array(
        			'type'    => 'segment',
       				'options' => array(
       						'route'    => '/dispo/pedido[/:action][/:pedido_cab_id][/:pedido_det_id]',
       						'constraints' => array(
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'pedido_cab_id'     => '[0-9]+',
       								'pedido_det_id'     => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Pedido',
        							'action'     => 'index',
       						),
       				),
       		),
       		'dispo-cliente' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/cliente[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Cliente',
        							'action'     => 'index',
        					),
        			),
        	),
        	'dispo-variedad' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/variedad[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Variedad',
        							'action'     => 'index',
        					),
        			),
        	),
        		
        		
        		'dispo-transportadora' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/dispo/transportadora[/:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        						),
        						'defaults' => array(
        								'controller' => 'Dispo\Controller\Transportadora',
        								'action'     => 'index',
        						),
        				),
        		),        		
		),
    ),
    'view_manager' => array(
    		'template_map' => array(
    				'dispo/dialog/seleccioncliente'		  => __DIR__ . '/../view/partial/dialogseleccioncliente.phtml',
    		),    		
        'template_path_stack' => array(
            'dispo' => __DIR__ . '/../view',
        ),
    ),
  
	// Doctrine config
/*	'doctrine' => array(
			'driver' => array(
					__NAMESPACE__ . '_driver' => array(
							'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
							'cache' => 'array',
							'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
					),
					'orm_default' => array(
							'drivers' => array(
									__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
							)
					),
			),
	)
		*/
);

