<?php
namespace Neilime\BrowscapTest;
class ModuleTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \Neilime\Browscap\Module
	 */
	protected $module;

	public function setUp(){
		$this->module = new \Neilime\Browscap\Module();
	}

	public function testGetAutoloaderConfig(){
        $this->assertEquals(
        	array('Zend\Loader\ClassMapAutoloader' => array(realpath(getcwd().'/../autoload_classmap.php'))),
        	$this->module->getAutoloaderConfig()
        );
    }

    public function testGetConfig(){
    	$this->assertTrue(is_array($this->module->getConfig()));
    }

    public function testGetConsoleBanner(){
    	$this->assertEquals('ZF2 Browscap - Command line Tool',$this->module->getConsoleBanner(\Neilime\BrowscapTest\Bootstrap::getServiceManager()->get('console')));
    }

    public function testGetConsoleUsager(){
    	$this->assertTrue(is_array($this->module->getConsoleUsage(\Neilime\BrowscapTest\Bootstrap::getServiceManager()->get('console'))));
    }
}