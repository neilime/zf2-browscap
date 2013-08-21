<?php
namespace Neilime\Browscap;
class Module implements
	\Zend\ModuleManager\Feature\ConfigProviderInterface,
	\Zend\ModuleManager\Feature\AutoloaderProviderInterface,
	\Zend\ModuleManager\Feature\ConsoleUsageProviderInterface{

	/**
	 * @param \Zend\EventManager\EventInterface $oEvent
	 */
	public function onBootstrap(\Zend\EventManager\EventInterface $oEvent){
		//Catch MVC errors
		$oEvent->getApplication()->getEventManager()->attach(
			array(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,\Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR),
			array($this,'consoleError')
		);
	}

	/**
	 * Display errors to the console, if an error appends during a ToolsController action
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 */
	public function consoleError(\Zend\Mvc\MvcEvent $oEvent){
		if(
		($oRequest = $oEvent->getRequest()) instanceof \Zend\Console\Request
		&& $oRequest->getParam('controller') === 'Neilime\Browscap\Controller\Tools'
				){
			$oConsole = $oEvent->getApplication()->getServiceManager()->get('console');
			$oConsole->writeLine(PHP_EOL.'======================================================================', \Zend\Console\ColorInterface::GRAY);
			$oConsole->writeLine('An error occured', \Zend\Console\ColorInterface::RED);
			$oConsole->writeLine('======================================================================', \Zend\Console\ColorInterface::GRAY);

			if(!($oException = $oEvent->getParam('exception')) instanceof \Exception)$oException = new \RuntimeException($oEvent->getError());
			$oConsole->writeLine($oException.PHP_EOL);
		}
	}

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