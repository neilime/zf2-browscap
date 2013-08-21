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


		//Define cache
		if(isset($aConfiguration['zf2_browscap']['cache']) && !($aConfiguration['zf2_browscap']['cache'] instanceof \Zend\Cache\Storage\StorageInterface)){
			if(is_string($aConfiguration['zf2_browscap']['cache'])){
				if($oServiceLocator->has($aConfiguration['zf2_browscap']['cache']))$aConfiguration['zf2_browscap']['cache'] = $oServiceLocator->get($aConfiguration['zf2_browscap']['cache']);
				elseif(class_exists($aConfiguration['zf2_browscap']['cache']))$aConfiguration['zf2_browscap']['cache'] = new $aConfiguration['zf2_browscap']['cache']();
			}
			else $aConfiguration['zf2_browscap']['cache'] = \Zend\Cache\StorageFactory::factory($aConfiguration['zf2_browscap']['cache']);
		}
		return \Neilime\Browscap\BrowscapService::factory(empty($aConfiguration['zf2_browscap'])?null:$aConfiguration['zf2_browscap']);
	}
}