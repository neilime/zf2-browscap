<?php
namespace Neilime\Browscap\View\Helper;
class BrowscapHelper extends \Zend\View\Helper\AbstractHelper implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	/**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
	 * @param \Zend\Http\Header\UserAgent $oUserAgent
	 * @param boolean $bReturnArray : if set to TRUE, this will return an array instead of an object
	 * @return object|array
	 */
    public function __invoke(\Zend\Http\Header\UserAgent $oUserAgent = null, $bReturnArray = false){
    	return $this->getServiceLocator()->getServiceLocator()->get('BrowscapService')->getBrowser($oUserAgent,$bReturnArray);
    }

    /**
     * Set service locator
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
     * @return \Neilime\Browscap\View\Helper\BrowscapHelper
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
        $this->serviceLocator = $oServiceLocator;
        return $this;
    }

    /**
     * Get service locator
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 * @throws \LogicException
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator(){
        if($this->serviceLocator instanceof \Zend\ServiceManager\ServiceLocatorInterface)return $this->serviceLocator;
        throw new \LogicException('Service locator is undefined for Browscap view helper');
    }

}