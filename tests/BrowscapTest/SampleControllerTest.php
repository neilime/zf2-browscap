<?php
namespace Neilime\BrowscapTest\Mvc\Controller;
class SampleControllerTest extends \Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase{

	protected $results = array(
		'Mozilla/5.0 (Linux; Android 4.0.4; Desire HD Build/IMM76D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19' => array(
			'browser_name_pattern' => 'Mozilla/5.0 (Linux*Android 4.0*)*AppleWebKit/*(*KHTML, like Gecko*)*Chrome/18.*Safari/*',
			'parent' => 'Chrome 18.0','platform' => 'Android','platform_version' => '4.0','win32' => '','ismobiledevice' => 1,
			'browser_name_regex' => '^Mozilla/5\.0 \(Linux.*Android 4\.0.*\).*AppleWebKit/.*\(.*KHTML, like Gecko.*\).*Chrome/18\..*Safari/.*$',
			'comment' => 'Chrome 18.0','browser' => 'Chrome','version' => '18.0','majorver' => '18','minorver' => '0','frames' => 1,'iframes' => 1,'tables' => 1,
			'cookies' => 1,'javascript' => 1,'javaapplets' => 1,'cssversion' => '3','alpha' => '','beta' => '','win16' => '','win64' => '',
			'backgroundsounds' => '','vbscript' => '','activexcontrols' => '','issyndicationreader' => '','crawler' => '','aolversion' => '0'
		),
		'Mozilla/5.0 (iPod; U; CPU iPhone OS 2_1 like Mac OS X; fr-fr) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5F137 Safari/525.20' => array(
			'browser_name_pattern' => 'Mozilla/5.0 (iPod*CPU*OS 2_1* like Mac OS X*)*AppleWebKit/*(*KHTML, like Gecko*)*Version/3.1*Mobile/*Safari/*',
			'parent' => 'Mobile Safari 3.1','platform' => 'iOS','platform_version' => '2.1','win32' => '','ismobiledevice' => 1,
			'browser_name_regex' => '^Mozilla/5\.0 \(iPod.*CPU.*OS 2_1.* like Mac OS X.*\).*AppleWebKit/.*\(.*KHTML, like Gecko.*\).*Version/3\.1.*Mobile/.*Safari/.*$',
			'comment' => 'Mobile Safari 3.1','browser' => 'Safari','version' => '3.1','majorver' => '3','minorver' => '1','frames' => 1,'iframes' => 1,'tables' => 1,
			'cookies' => 1,'javascript' => 1,'javaapplets' => '','cssversion' => '3','alpha' => '','beta' => '','win16' => '','win64' => '',
			'backgroundsounds' => '','vbscript' => '','activexcontrols' => '','issyndicationreader' => '','crawler' => '','aolversion' => '0'
		),
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0' => array(
			'browser_name_pattern' => 'Mozilla/5.0 (*Windows NT 6.1*WOW64*)*Gecko/*Firefox/22.*',
			'parent' => 'Firefox 22.0','platform' => 'Win7','platform_version' => '6.1','win32' => '','ismobiledevice' => '',
			'browser_name_regex' => '^Mozilla/5\.0 \(.*Windows NT 6\.1.*WOW64.*\).*Gecko/.*Firefox/22\..*$',
			'comment' => 'Firefox 22.0','browser' => 'Firefox','version' => '22.0','majorver' => '22','minorver' => '0','frames' => 1,'iframes' => 1,'tables' => 1,
			'cookies' => 1,'javascript' => 1,'javaapplets' => 1,'cssversion' => '3','alpha' => '','beta' => '','win16' => '','win64' => 1,
			'backgroundsounds' => '','vbscript' => '','activexcontrols' => '','issyndicationreader' => '','crawler' => '','aolversion' => '0'
		)
	);

	/**
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$this->setApplicationConfig(\Neilime\BrowscapTest\Bootstrap::getConfig());
		parent::setUp();
	}

	/**
	 * @expectedException LogicException
	 */
	public function testBrowscapControllerPluginWithServiceLocatorUndefined(){
		$this->assertInstanceOf('\Neilime\Browscap\Mvc\Controller\Plugin\BrowscapPlugin',$oBrowscapPlugin = $this->getApplicationServiceLocator()->get('ControllerPluginManager')->get('get_browser'));
		$oBrowscapPlugin->__invoke();
	}

	/**
	 * @expectedException LogicException
	 */
	public function testBrowscapControllerPluginWithUserAgentUndefined(){
		$this->assertInstanceOf('\Neilime\Browscap\Mvc\Controller\Plugin\BrowscapPlugin',$oBrowscapPlugin = $this->getApplicationServiceLocator()->get('ControllerPluginManager')->get('get_browser'));
		$oSampleController = new \Neilime\BrowscapTest\Mvc\Controller\SampleController();
		$oSampleController->setServiceLocator(\Neilime\BrowscapTest\Bootstrap::getServiceManager());
		$oBrowscapPlugin->setController($oSampleController);
		$oBrowscapPlugin->__invoke();
	}

	public function testBrowscapControllerPlugin(){
		$this->assertInstanceOf('\Neilime\Browscap\Mvc\Controller\Plugin\BrowscapPlugin',$oBrowscapPlugin = $this->getApplicationServiceLocator()->get('ControllerPluginManager')->get('get_browser'));
		$oSampleController = new \Neilime\BrowscapTest\Mvc\Controller\SampleController();
		$oSampleController->setServiceLocator(\Neilime\BrowscapTest\Bootstrap::getServiceManager());
		$oBrowscapPlugin->setController($oSampleController);
		foreach($this->results as $sUserAgent => $aResult){
			$_SERVER['HTTP_USER_AGENT'] = $sUserAgent;
			$this->assertEquals((object)$aResult,$oBrowscapPlugin->__invoke());
		}
	}

	public function testBrowscapControllerPluginWithCustomUserAgent(){
		$this->assertInstanceOf('\Neilime\Browscap\Mvc\Controller\Plugin\BrowscapPlugin',$oBrowscapPlugin = $this->getApplicationServiceLocator()->get('ControllerPluginManager')->get('get_browser'));
		$oSampleController = new \Neilime\BrowscapTest\Mvc\Controller\SampleController();
		$oSampleController->setServiceLocator(\Neilime\BrowscapTest\Bootstrap::getServiceManager());
		$oBrowscapPlugin->setController($oSampleController);
		foreach($this->results as $sUserAgent => $aResult){
			$this->assertEquals((object)$aResult,$oBrowscapPlugin->__invoke(
				\Zend\Http\Header\UserAgent::fromString('User-Agent: '.$sUserAgent)
			));
		}
	}

	/**
	 * @expectedException LogicException
	 */
	public function testBrowscapViewHelperWithServiceLocatorUndefined(){
		$oBrowscap = new \Neilime\Browscap\View\Helper\BrowscapHelper();
		$oBrowscap->__invoke();
	}

	public function testBrowscapViewHelper(){
		$this->assertInstanceOf('\Neilime\Browscap\View\Helper\BrowscapHelper',$oBrowscapHelper = $this->getApplicationServiceLocator()->get('ViewHelperManager')->get('get_browser'));
		foreach($this->results as $sUserAgent => $aResult){
			$_SERVER['HTTP_USER_AGENT'] = $sUserAgent;
			$this->assertEquals((object)$aResult,$oBrowscapHelper->__invoke());
		}
	}

	public function testBrowscapViewHelperWithCustomUserAgent(){
		$this->assertInstanceOf('\Neilime\Browscap\View\Helper\BrowscapHelper',$oBrowscapHelper = $this->getApplicationServiceLocator()->get('ViewHelperManager')->get('get_browser'));
		foreach($this->results as $sUserAgent => $aResult){
			$this->assertEquals((object)$aResult,$oBrowscapHelper->__invoke(
				\Zend\Http\Header\UserAgent::fromString('User-Agent: '.$sUserAgent)
			));
		}
	}

	public function tearDown(){
		//Empty cache directory except .gitignore
		foreach(new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator(__DIR__.'/_file/cache', \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::CHILD_FIRST
		) as $oFileinfo){
			if($oFileinfo->isDir())rmdir($oFileinfo->getRealPath());
			elseif($oFileinfo->getBasename() !== '.gitignore')unlink($oFileinfo->getRealPath());
		}
	}
}