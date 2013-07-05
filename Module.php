<?php
namespace Neilime\Browscap;
class Module implements
	\Zend\ModuleManager\Feature\ConfigProviderInterface,
	\Zend\ModuleManager\Feature\AutoloaderProviderInterface,
	\Zend\ModuleManager\Feature\ConsoleUsageProviderInterface{

	/**
	 * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
	 * @return array
	 */
	public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__.DIRECTORY_SEPARATOR.'autoload_classmap.php'
            )
        );
    }

    /**
     * @return array
     */
    public function getConfig(){
        return include __DIR__.DIRECTORY_SEPARATOR.'config/module.config.php';
    }

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $oConsole
     * @return string
     */
    public function getConsoleBanner(\Zend\Console\Adapter\AdapterInterface $oConsole){
    	return 'ZF2 Browscap - Command line Tool';
    }

    /**
     * @see \Zend\ModuleManager\Feature\ConsoleUsageProviderInterface::getConsoleUsage()
     * @param \Zend\Console\Adapter\AdapterInterface $oConsole
     * @return array
     */
    public function getConsoleUsage(\Zend\Console\Adapter\AdapterInterface $oConsole){
    	return array(
    		'Load "browscap.ini" file:',
			'load-browscap' => 'Load and parse "browscap.ini" file (or update cached browscap.ini)'
    	);
    }
}