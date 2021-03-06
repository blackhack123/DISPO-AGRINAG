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
        	'Dispo\Controller\Transportadora'		=> 'Dispo\Controller\TransportadoraController',
        	'Dispo\Controller\Grupodispo'			=> 'Dispo\Controller\GrupodispoController',
        	'Dispo\Controller\Grupoprecio'			=> 'Dispo\Controller\GrupoprecioController',
        	'Dispo\Controller\Tipocajamatriz'		=> 'Dispo\Controller\TipocajamatrizController',
        	'Dispo\Controller\Clienteagenciacarga' 	=> 'Dispo\Controller\ClienteagenciacargaController',
        	'Dispo\Controller\Tipocajamarcacion'	=> 'Dispo\Controller\TipocajamarcacionController',
        	'Dispo\Controller\Tipocaja'				=> 'Dispo\Controller\TipocajaController',
        	'Dispo\Controller\Inventario'			=> 'Dispo\Controller\InventarioController',
        	'Dispo\Controller\Tipocajamarcacion'	=> 'Dispo\Controller\TipocajamarcacionController',
        	'Dispo\Controller\Tipocaja'				=> 'Dispo\Controller\TipocajaController',
        	'Dispo\Controller\Inventario'			=> 'Dispo\Controller\InventarioController',
        	'Dispo\Controller\Grado'				=> 'Dispo\Controller\GradoController',
        	'Dispo\Controller\Precio'				=> 'Dispo\Controller\PrecioController',
        	'Dispo\Controller\Parametrizar'			=> 'Dispo\Controller\ParametrizarController',
        	'Dispo\Controller\Colorventas'			=> 'Dispo\Controller\ColorventasController',
        	'Dispo\Controller\Calidadvariedad'		=> 'Dispo\Controller\CalidadvariedadController',
        	'Dispo\Controller\Prueba'				=> 'Dispo\Controller\PruebaController',
        	'Dispo\Controller\Proveedor'			=> 'Dispo\Controller\ProveedorController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
        		
       		'dispo-panel' => array(
       				'type'    => 'segment',
       				'options' => array(
       						'route'    => '/dispo/disponibilidad/panel[/:id]',
       						'constraints' => array(
       								'id'     => '[0-9]+',
       						),
       						'defaults' => array(
       								'controller' => 'Dispo\Controller\Disponibilidad',
       								'action'     => 'panel',
       						),
       				),
       		),   
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

        		
        	'dispo-grupodispo' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/grupodispo[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Grupodispo',
        							'action'     => 'index',
        					),
        			),
        	), 

        	'dispo-grupoprecio' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/grupoprecio[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Grupoprecio',
        							'action'     => 'index',
        					),
        			),
        	), 

        	'dispo-tipocajamatriz' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/tipocajamatriz[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Tipocajamatriz',
        							'action'     => 'index',
        					),
        			),
        	), 
        		        		
        	'dispo-clienteagenciacarga' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/clienteagenciacarga[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Clienteagenciacarga',
        							'action'     => 'index',
        					),
        			),
        	),
        		
 
        	'dispo-tipocajamarcacion' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/tipocajamarcacion[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Tipocajamarcacion',
        							'action'     => 'index',
        					),
        			),
        	), 
        
        	'dispo-tipocaja' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/tipocaja[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Tipocaja',
        							'action'     => 'index',
        					),
        			),
        	), 
        
        	'dispo-inventario' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/inventario[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Inventario',
        							'action'     => 'index',
        					),
        			),
        	), 
        

        	'dispo-grado' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/dispo/grado[/:action][/:id]',
        					'constraints' => array(
        							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'controller' => 'Dispo\Controller\Grado',
        							'action'     => 'index',
        					),
        			),
        	), 

       		'dispo-precio' => array(
       				'type'    => 'segment',
       				'options' => array(
       						'route'    => '/dispo/precio[/:action][/:id]',
       						'constraints' => array(
       								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
       								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
       						),
       						'defaults' => array(
       								'controller' => 'Dispo\Controller\Precio',
       								'action'     => 'index',
       						),
       				),
       		),
        		
        		
        		'dispo-parametrizar' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/dispo/parametrizar[/:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        						),
        						'defaults' => array(
        								'controller' => 'Dispo\Controller\Parametrizar',
        								'action'     => 'index',
        						),
        				),
        		),
        		'dispo-colorventas' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/dispo/colorventas[/:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        						),
        						'defaults' => array(
        								'controller' => 'Dispo\Controller\Colorventas',
        								'action'     => 'index',
        						),
        				),
        		),
        		'dispo-calidadvariedad' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/dispo/calidadvariedad[/:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        						),
        						'defaults' => array(
        								'controller' => 'Dispo\Controller\Calidadvariedad',
        								'action'     => 'index',
        						),
        				),
        		),
        		'dispo-prueba' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/dispo/prueba[/:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        						),
        						'defaults' => array(
        								'controller' => 'Dispo\Controller\Prueba',
        								'action'     => 'index',
        						),
        				),
        		),
        		'dispo-proveedor' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/dispo/proveedor[/:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        						),
        						'defaults' => array(
        								'controller' => 'Dispo\Controller\Proveedor',
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
