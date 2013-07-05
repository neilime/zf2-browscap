<?php
namespace Neilime\BrowscapTest\Controller;
class ToolsControllerTest extends \Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase{

	/**
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
    public function setUp(){
        $this->setApplicationConfig(\Neilime\BrowscapTest\Bootstrap::getConfig());
        parent::setUp();
    }

    public function testLoadBrowscapIniActionWithoutConfiguration(){
    	$oServiceLocator = $this->getApplicationServiceLocator();

    	$aConfiguration = $oServiceLocator->get('Config');
    	unset($aConfiguration['zf2_browscap']);

    	$bAllowOverride = $oServiceLocator->getAllowOverride();
    	if(!$bAllowOverride)$oServiceLocator->setAllowOverride(true);
    	$oServiceLocator->setService('Config',$aConfiguration)->setAllowOverride($bAllowOverride);

    	$this->dispatch('load-browscap');
    	$this->assertResponseStatusCode(1);
    	$this->assertModuleName('neilime');
    	$this->assertControllerName('neilime\browscap\controller\tools');
    	$this->assertControllerClass('ToolsController');
    	$this->assertMatchedRouteName('load-browscap');
    }

   	public function testLoadBrowscapIniAction(){
   		//Empty cache directory except .gitignore
   		foreach(new \RecursiveIteratorIterator(
   			new \RecursiveDirectoryIterator(__DIR__.'/../_file/cache', \RecursiveDirectoryIterator::SKIP_DOTS),
   			\RecursiveIteratorIterator::CHILD_FIRST
   		) as $oFileinfo){
   			if($oFileinfo->isDir())rmdir($oFileinfo->getRealPath());
   			elseif($oFileinfo->getBasename() !== '.gitignore')unlink($oFileinfo->getRealPath());
   		}

    	$this->dispatch('load-browscap');
    	$this->assertResponseStatusCode(0);
    	$this->assertModuleName('neilime');
    	$this->assertControllerName('neilime\browscap\controller\tools');
    	$this->assertControllerClass('ToolsController');
    	$this->assertMatchedRouteName('load-browscap');
    	$this->assertFileExists(__DIR__.'/../_file/cache/zfcache-7d/zfcache-Neilime_Browscap.dat');
    }

    public function testLoadBrowscapIniActionWithDefinedBrowscapForceLoad(){
    	$oServiceLocator = $this->getApplicationServiceLocator();
    	$oConsole = new \Neilime\BrowscapTest\Console\ConsoleAdapter();
    	$oConsole->stream = fopen('php://memory', 'w+');

    	$aConfiguration = $oServiceLocator->get('Config');
    	$aConfiguration['zf2_browscap']['allows_native_get_browser'] = true;

    	$bAllowOverride = $oServiceLocator->getAllowOverride();
    	if(!$bAllowOverride)$oServiceLocator->setAllowOverride(true);
    	$oServiceLocator
    		->setService('Config',$aConfiguration)
    		->setService('console',$oConsole)
    		->setAllowOverride($bAllowOverride);

    	fwrite($oConsole->stream,'y');
    	$this->dispatch('load-browscap');
    	$this->assertResponseStatusCode(0);
    	$this->assertModuleName('neilime');
    	$this->assertControllerName('neilime\browscap\controller\tools');
    	$this->assertControllerClass('ToolsController');
    	$this->assertMatchedRouteName('load-browscap');
    	fclose($oConsole->stream);
    	$this->assertFileExists(__DIR__.'/../_file/cache/zfcache-7d/zfcache-Neilime_Browscap.dat');
    }

    public function testLoadBrowscapIniActionWithDefinedBrowscap(){
    	$oServiceLocator = $this->getApplicationServiceLocator();
    	$oConsole = new \Neilime\BrowscapTest\Console\ConsoleAdapter();
    	$oConsole->stream = fopen('php://memory', 'w+');

    	$aConfiguration = $oServiceLocator->get('Config');
    	$aConfiguration['zf2_browscap']['allows_native_get_browser'] = true;

    	$bAllowOverride = $oServiceLocator->getAllowOverride();
    	if(!$bAllowOverride)$oServiceLocator->setAllowOverride(true);
    	$oServiceLocator
    	->setService('Config',$aConfiguration)
    	->setService('console',$oConsole)
    	->setAllowOverride($bAllowOverride);

    	fwrite($oConsole->stream,'n');
    	$this->dispatch('load-browscap');
    	$this->assertResponseStatusCode(0);
    	$this->assertModuleName('neilime');
    	$this->assertControllerName('neilime\browscap\controller\tools');
    	$this->assertControllerClass('ToolsController');
    	$this->assertMatchedRouteName('load-browscap');
    	fclose($oConsole->stream);
    	$this->assertFileNotExists(__DIR__.'/../_file/cache/zfcache-7d/zfcache-Neilime_Browscap.dat');
    }

    public function tearDown(){
    	//Empty cache directory except .gitignore
    	foreach(new \RecursiveIteratorIterator(
    			new \RecursiveDirectoryIterator(__DIR__.'/../_file/cache', \RecursiveDirectoryIterator::SKIP_DOTS),
    			\RecursiveIteratorIterator::CHILD_FIRST
    	) as $oFileinfo){
    		if($oFileinfo->isDir())rmdir($oFileinfo->getRealPath());
    		elseif($oFileinfo->getBasename() !== '.gitignore')unlink($oFileinfo->getRealPath());
    	}
    }
}