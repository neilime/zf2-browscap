<?php
namespace Neilime\Browscap\Factory;
class BrowscapServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	/**
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \Neilime\Browscap\BrowscapService
	 */
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$aConfiguration = $oServiceLocator->get('config');
		return \Neilime\Browscap\BrowscapService::factory(empty($aConfiguration['zf2_browscap'])?null:$aConfiguration['zf2_browscap']);
	}
}