<?php
return array(
	'zf2_browscap' => array(
		'allows_native_get_browser' => false,
		'cache' => array('adapter' => 'filesystem','options' => array('cache_dir' => __DIR__.'/BrowscapTest/_file/cache'))
	),
	'service_manager' => array(
        'factories' => array(
            'CacheTest' => function(){
            	return \Zend\Cache\StorageFactory::factory(array('adapter' => 'filesystem','options' => array('cache_dir' => __DIR__.'/BrowscapTest/_file/cache')));
            }
        )
    ),
);