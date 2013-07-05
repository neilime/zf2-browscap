<?php
namespace Neilime\Browscap\Mvc\Controller\Plugin;
class BrowscapPlugin extends \Zend\Mvc\Controller\Plugin\AbstractPlugin{

	/**
	 * @param \Zend\Http\Header\UserAgent $oUserAgent
	 * @param boolean $bReturnArray : if set to TRUE, this will return an array instead of an object
	 * @throws \LogicException
	 * @return object|array
	 */
	public function __invoke(\Zend\Http\Header\UserAgent $oUserAgent = null, $bReturnArray = false){
		$oController = $this->getController();
		if($oController)return $oController->getServiceLocator()->get('BrowscapService')->getBrowser($oUserAgent,$bReturnArray);
		throw new \LogicException('Controller is undefined for Browscap controller plugin');
	}
}