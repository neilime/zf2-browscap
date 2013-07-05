<?php
namespace Neilime\Browscap\Controller;
class ToolsController extends \Zend\Mvc\Controller\AbstractActionController{
	public function loadBrowscapIniAction(){
    	//Retrieve configuration
    	$aConfiguration = $this->getServiceLocator()->get('Config');
    	if(!isset($aConfiguration['zf2_browscap'])){
    		$oView = new \Zend\View\Model\ConsoleModel();
    		$oView->setErrorLevel(1);
    		return $oView->setResult('ZF2 Browscap configuration is undefined'.PHP_EOL);
    	}
    	$aConfiguration = $aConfiguration['zf2_browscap'];

    	$oServiceLocator = $this->getServiceLocator();
        $oConsole = $this->getServiceLocator()->get('console');

        //Initialize Browscap service
        $oBrowscapService = $oServiceLocator->get('BrowscapService');

        if($oBrowscapService->getAllowsNativeGetBrowser() && $oBrowscapService->canUseNativeGetBrowser()){
        	$oPrompt = new \Zend\Console\Prompt\Confirm('Native function "get_browser" is available, it is useless to load "browscap.ini" file. Continue anyway ?','y','n');
        	$oPrompt->setConsole($oConsole);
        	if(!$oPrompt->show()){
				$oConsole->writeLine();
				$oConsole->writeLine('"browscap.ini" load canceled', \Zend\Console\ColorInterface::LIGHT_RED);
				$oConsole->writeLine();
				return;
			}
        }



        //Start process
        $oConsole->writeLine('');
        $oConsole->writeLine('======================================================================', \Zend\Console\ColorInterface::GRAY);
        $oConsole->writeLine('Load "browscap.ini" file from "'.$oBrowscapService->getBrowscapIniPath().'"', \Zend\Console\ColorInterface::GREEN);
        $oConsole->writeLine('======================================================================', \Zend\Console\ColorInterface::GRAY);
        $oConsole->writeLine('');

        $oBrowscapService->loadBrowscapIni();

        $oConsole->writeLine('');
        $oConsole->writeLine('---------------', \Zend\Console\ColorInterface::GRAY);
        $oConsole->writeLine('"Browscap.ini" file loaded', \Zend\Console\ColorInterface::GREEN);
        $oConsole->writeLine('');
    }
}