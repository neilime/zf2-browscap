<?php
namespace Neilime\BrowscapTest;
use Zend\Http\Client\Adapter\Test;
class BrowscapServiceTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \Neilime\Browscap\BrowscapService
	 */
	private $browscapService;

	/**
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
    protected function setUp(){
    	$this->browscapService = \Neilime\Browscap\BrowscapService::factory(array());
    }

    public function testCanUseNativeGetBrowser(){
    	$this->assertTrue($this->browscapService->canUseNativeGetBrowser(),'"browscap" need to be defined in php.ini for tests');
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetBrowscapIniPathUnset(){
    	$this->browscapService->getBrowscapIniPath();
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetAllowsNativeGetBrowser(){
    	$this->browscapService->getAllowsNativeGetBrowser();
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetCacheUnset(){
    	$this->browscapService->getCache();
    }

    public function testLoadLocalBrowscapIni(){
    	//Empty cache directory except .gitignore
    	foreach(new \RecursiveIteratorIterator(
    			new \RecursiveDirectoryIterator(__DIR__.'/_file/cache', \RecursiveDirectoryIterator::SKIP_DOTS),
    			\RecursiveIteratorIterator::CHILD_FIRST
    	) as $oFileinfo){
    		if($oFileinfo->isDir())rmdir($oFileinfo->getRealPath());
    		elseif($oFileinfo->getBasename() !== '.gitignore')unlink($oFileinfo->getRealPath());
    	}
    	$this->browscapService->setBrowscapIniPath(__DIR__.'/_file/browscap.ini');

    	$this->assertEquals($this->browscapService,$this->browscapService->loadBrowscapIni());
    }
}