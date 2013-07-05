<?php
return array(
	'zf2_browscap' => array(
		'browscap_ini_path' => 'http://browsers.garykeith.com/stream.asp?BrowsCapINI',
		'cache' => array('adapter' => 'filesystem','options' => array('cache_dir' => __DIR__.'/../data/cache')),
		'allows_native_get_browser' => true
	),
	'controllers' => array(
		'invokables' => array(
			'Neilime\Browscap\Controller\Tools' => 'Neilime\Browscap\Controller\ToolsController'
		)
	),
	'console' => array(
		'router' => array(
			'routes' => array(
				'load-browscap' => array(
					'options' => array(
						'route'    => 'load-browscap',
						'defaults' => array(
							'controller' => 'Neilime\Browscap\Controller\Tools',
							'action' => 'loadBrowscapIni'
						)
					)
				)
			)
		)
	),
	'service_manager' => array(
        'factories' => array(
            'BrowscapService' => 'Neilime\Browscap\Factory\BrowscapServiceFactory'
        )
    ),
	'controller_plugins' => array(
    	'invokables' => array(
    		'get_browser' => 'Neilime\Browscap\Mvc\Controller\Plugin\BrowscapPlugin'
    	)
    ),
	'view_helpers' => array(
		'invokables' => array(
			'get_browser' => 'Neilime\Browscap\View\Helper\BrowscapHelper'
		)
	)
);