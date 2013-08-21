<?php
namespace Neilime\BrowscapTest\Factory;
class BrowscapServiceFactoryTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var array
	 */
	protected $configuration;


	/**
	 * @var \Neilime\Browscap\Factory\BrowscapServiceFactory
	 */
	protected $browscapServiceFactory;

	/**
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	protected function setUp(){
		$this->browscapServiceFactory = new \Neilime\Browscap\Factory\BrowscapServiceFactory();
		$this->configuration = \Neilime\BrowscapTest\Bootstrap::getServiceManager()->get('Config');
	}

	public function testCreateServiceWithCacheAsService(){
		$aConfiguration = $this->configuration;
		$aConfiguration['zf2_browscap']['cache'] = 'CacheTest';

		$oServiceManager = \Neilime\BrowscapTest\Bootstrap::getServiceManager();
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$aConfiguration)->setAllowOverride($bAllowOverride);

		//Test browscap service instance
		$this->assertInstanceOf('\Neilime\Browscap\BrowscapService',$oBrowscapServiceFactory = $this->browscapServiceFactory->createService($oServiceManager));

		//Test cache instance
		$this->assertInstanceOf('\Zend\Cache\Storage\StorageInterface',$oBrowscapServiceFactory->getCache());
	}

	public function testCreateServiceWithCacheAsClassName(){
		$aConfiguration = $this->configuration;
		$aConfiguration['zf2_browscap']['cache'] = '\Zend\Cache\Storage\Adapter\Filesystem';

		$oServiceManager = \Neilime\BrowscapTest\Bootstrap::getServiceManager();
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$aConfiguration)->setAllowOverride($bAllowOverride);

		//Test browscap service instance
		$this->assertInstanceOf('\Neilime\Browscap\BrowscapService',$oBrowscapServiceFactory = $this->browscapServiceFactory->createService($oServiceManager));

		//Test cache instance
		$this->assertInstanceOf('\Zend\Cache\Storage\StorageInterface',$oBrowscapServiceFactory->getCache());
	}

	public function tearDown(){
		$oServiceManager = \Neilime\BrowscapTest\Bootstrap::getServiceManager();
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$this->configuration)->setAllowOverride($bAllowOverride);
	}
}