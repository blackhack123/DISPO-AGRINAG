<?php
namespace Seguridad;

return array(

    'controller_plugins' => array(
        'invokables' => array(
            'ExcepcionPlugin' 		=> 'Seguridad\Controller\Plugin\ExcepcionPlugin',
			'SesionUsuarioPlugin' 	=> 'Seguridad\Controller\Plugin\SesionUsuarioPlugin',
        ),
	),
    'controllers' => array(
        'invokables' => array(
//            'Seguridad\Controller\Seguridad' 	=> 'Seguridad\Controller\SeguridadController',
			'Seguridad\Controller\Login' 	 	=> 'Seguridad\Controller\LoginController',
            'Seguridad\Controller\Usuario' 		=> 'Seguridad\Controller\UsuarioController',
        	'Seguridad\Controller\usuario'		=> 'Seguridad\Controller\UsuarioController',        		
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(

            'seguridad' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/seguridad[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Seguridad\Controller\Seguridad',
                        'action'     => 'index',
                    ),
                ),
			),
			'seguridad-login' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/seguridad/login[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Seguridad\Controller\Login',
                        'action'     => 'autenticar',
                    ),
				),
			),
        	'seguridad-usuario' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/seguridad/usuario[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Seguridad\Controller\Usuario',
                        'action'     => 'index',
                    ),
				),
			),
		),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'seguridad' => __DIR__ . '/../view',
        ),
    ),

/*    // Doctrine config
    'doctrine' => array(
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
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Seguridad\Entity\Usuario',
                'identity_property' => 'nombre_usuario',
                'credential_property' => 'clave',
                'credential_callable' => function($usuario, $passwordGiven) {
					return my_comprobar_password($usuario->clave, $passwordGiven);
                    //return my_comprobar_password($user->getPassword(), $passwordGiven);
                },				
            ),
        ),		
    )	*/	
);

/*
	Este procedimiento es utilizado unicamente para poder validar claves encriptadas,
	cabe indicar que este procedimiento es opcional
*/
/*function my_comprobar_password($clave_db, $clave_app)
{
	if ($clave_db == $clave_app){
		return true;
	}else{
		return false;
	}
}*/