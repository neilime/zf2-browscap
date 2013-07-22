<?php
namespace Neilime\Browscap;
class BrowscapService{
	/**
	 * @var string
	 */
	private static $cacheId = 'Neilime_Browscap';

	/**
	 * @var string
	 */
	protected $browscapIniPath;

	/**
	 * @var \Zend\Cache\Storage\StorageInterface
	 */
	protected $cache;

	/**
	 * @var boolean
	 */
	protected $allowsNativeGetBrowser;

	/**
	 * @var boolean
	 */
	protected $canUseNativeGetBrowser;

	/**
	 * @var array
	 */
	protected $browscap;

	/**
	 * Instantiate AccessControl Authentication Service
	 * @param array|Traversable $aConfiguration
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @throws \InvalidArgumentException
	 * @return \BoilerAppAccessControl\Authentication\AccessControlAuthenticationService
	 */
	public static function factory($aOptions, \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		if($aOptions instanceof \Traversable)$aOptions = \Zend\Stdlib\ArrayUtils::iteratorToArray($aOptions);
		elseif(!is_array($aOptions))throw new \InvalidArgumentException(__METHOD__.' expects an array or Traversable object; received "'.(is_object($aOptions)?get_class($aOptions):gettype($aOptions)).'"');

		$oBrowscapService = new static();

		//Browscap.ini file path
		if(isset($aOptions['browscap_ini_path']))$oBrowscapService->setBrowscapIniPath($aOptions['browscap_ini_path']);

		//Cache
		if(isset($aOptions['cache'])) {
		    if ($aOptions['cache'] instanceof \Zend\Cache\Storage\StorageInterface) {
		        $cache = $aOptions['cache'];
		    } elseif (is_array($aOptions['cache'])) {
		        $cache = \Zend\Cache\StorageFactory::factory($aOptions['cache']);
		    } elseif (is_string($aOptions['cache']) && $oServiceLocator->has($aOptions['cache'])) {
		        $cache = $oServiceLocator->get($aOptions['cache']);
		    }
		    
		    $oBrowscapService->setCache($cache);
		}

		if(isset($aOptions['allows_native_get_browser']))$oBrowscapService->setAllowsNativeGetBrowser(!!$aOptions['allows_native_get_browser']);

		return $oBrowscapService;
	}

	public function setAllowsNativeGetBrowser($bAllowsNativeGetBrowser){
		if(!is_bool($bAllowsNativeGetBrowser))throw new \InvalidArgumentException('"AllowsNativeGetBrowser" option expects a boolean, "'.gettype($sBrowscapIniPath).'" given');
		$this->allowsNativeGetBrowser = $bAllowsNativeGetBrowser;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return string
	 */
	public function getAllowsNativeGetBrowser(){
		if(is_bool($this->allowsNativeGetBrowser))return $this->allowsNativeGetBrowser;
		throw new \LogicException('"AllowsNativeGetBrowser" is undefined');
	}

	/**
	 * @return boolean
	 */
	public function canUseNativeGetBrowser(){
		return is_bool($this->canUseNativeGetBrowser)?$this->canUseNativeGetBrowser:$this->canUseNativeGetBrowser = file_exists(get_cfg_var('browscap'));
	}

	/**
	 * @param string $sBrowscapIniPath
	 * @throws \InvalidArgumentException
	 * @return \Neilime\Browscap\BrowscapService
	 */
	public function setBrowscapIniPath($sBrowscapIniPath){
		if(!is_string($sBrowscapIniPath))throw new \InvalidArgumentException('Browscap.ini Path expects a string, "'.gettype($sBrowscapIniPath).'" given');
		$this->browscapIniPath = $sBrowscapIniPath;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return string
	 */
	public function getBrowscapIniPath(){
		if(is_string($this->browscapIniPath))return $this->browscapIniPath;
		throw new \LogicException('Browscap.ini path is undefined');
	}

	/**
	 * @param \Zend\Cache\Storage\StorageInterface $oCache
	 * @return \Neilime\Browscap\BrowscapService
	 */
	public function setCache(\Zend\Cache\Storage\StorageInterface $oCache){
		$this->cache = $oCache;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \Zend\Cache\Storage\StorageInterface
	 */
	public function getCache(){
		if($this->hasCache())return $this->cache;
		throw new \LogicException('Cache is undefined');
	}

	/**
	 * @param array $aBrowscap
	 * @return \Neilime\Browscap\BrowscapService
	 */
	public function setBrowscap(array $aBrowscap){
		$this->browscap = $aBrowscap;
		if($this->hasCache())$this->getCache()->setItem(self::$cacheId, json_encode($aBrowscap));
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function hasCache(){
		return $this->cache instanceof \Zend\Cache\Storage\StorageInterface;
	}

	/**
	 * @return array
	 */
	public function getBrowscap(){
		if(is_array($this->browscap))return $this->browscap;
		//Try to retrieve from cache if available
		if(
			$this->hasCache()
			&& (null !== $sCachedBrowscap = $this->getCache()->getItem(self::$cacheId))
			&& is_array($aBrowscap = json_decode($sCachedBrowscap,true))
		)return $this->browscap = $aBrowscap;
		return $this->loadBrowscapIni()->browscap;
	}

	/**
	 * @param \Zend\Http\Header\UserAgent $oUserAgent
	 * @param boolean $bReturnArray : if set to TRUE, this will return an array instead of an object
	 * @return object|array
	 */
	public function getBrowser(\Zend\Http\Header\UserAgent $oUserAgent = null, $bReturnArray = false){

		if($oUserAgent)$sUserAgent = $oUserAgent->getFieldValue();
		elseif(isset($_SERVER['HTTP_USER_AGENT']))$sUserAgent = $_SERVER['HTTP_USER_AGENT'];
		else throw new \LogicException('"HTTP_USER_AGENT" server var is undefined');

		//Native function
		if($this->getAllowsNativeGetBrowser() && $this->canUseNativeGetBrowser())return get_browser($sUserAgent,$bReturnArray);
		$aReturn = array();
		foreach($aBrowsecap = $this->getBrowscap() as $sUserAgentKey => $aUserAgentInfos){
			if($sUserAgentKey != '*' && !array_key_exists('parent',$aUserAgentInfos))continue;
			if(preg_match('%'.$aUserAgentInfos['browser_name_regex'].'%i',$sUserAgent)){
				$aReturn = array('browser_name_pattern' => $sUserAgentKey) + $aUserAgentInfos;
				$iMaxDeep = 8;
				while(array_key_exists('parent',$aUserAgentInfos) && array_key_exists($sParentKey = $aUserAgentInfos['parent'],$aBrowsecap) && (--$iMaxDeep > 0)){
					$aReturn += ($aUserAgentInfos = $aBrowsecap[$sParentKey]);
				}
				break;
			}
		}
		return $bReturnArray?$aReturn:(object)$aReturn;
	}

	/**
	 * Load and parse browscap.ini file
	 * @throws \RuntimeException
	 * @return \Neilime\Browscap\BrowscapService
	 */
	public function loadBrowscapIni(){
		$sBrowscapIniPath = $this->getBrowscapIniPath();

		//Local file
		if(is_readable($sBrowscapIniPath)){
			$aBrowscap = parse_ini_file($this->getBrowscapIniPath(), true, INI_SCANNER_RAW);
			if($aBrowscap === false)throw new \RuntimeException('Error appends while parsing browscap.ini file "'.$sBrowscapIniPath.'"');
		}
		//Remote file
		else{
			if(($oFileHandle = @fopen($sBrowscapIniPath, 'r')) === false)throw new \InvalidArgumentException('Unable to load browscap.ini file "'.$sBrowscapIniPath.'"');
			$sBrowscapIniContents = '';
			while(($sContent = fgets($oFileHandle)) !== false) {
				$sBrowscapIniContents .= $sContent.PHP_EOL;
			}
			if(!feof($oFileHandle))throw new \RuntimeException('Unable to retrieve contents from browscap.ini file "'.$sBrowscapIniPath.'"');
			fclose($oFileHandle);
			$aBrowscap = parse_ini_string($sBrowscapIniContents, true, INI_SCANNER_RAW);
			if($aBrowscap === false)throw new \RuntimeException('Error appends while parsing browscap.ini file "'.$sBrowscapIniPath.'"');
		}

		$aBrowscapKeys = array_keys($aBrowscap);
		$aBrowscap = array_combine($aBrowscapKeys, array_map(function($aUserAgentInfos, $sUserAgent){
			$aUserAgentInfos = array_map(function($sValue){
				if($sValue === 'true')return 1;
				elseif($sValue === 'false')return '';
				else return $sValue;
			}, $aUserAgentInfos);

			//Define browser name regex
			$aUserAgentInfos['browser_name_regex'] = '^'.str_replace(
				array('\\','.','?','*','^','$','[',']','|','(',')','+','{','}','%'),
				array('\\\\','\\.','.','.*','\\^','\\$','\\[','\\]','\\|','\\(','\\)','\\+','\\{','\\}','\\%'),
				$sUserAgent
			).'$';


			return array_change_key_case($aUserAgentInfos,CASE_LOWER);
		},$aBrowscap,$aBrowscapKeys));

		uksort($aBrowscap,function($sUserAgentA,$sUserAgentB){
			if(($sUserAgentALength = strlen($sUserAgentA)) > ($sUserAgentBLength = strlen($sUserAgentB)))return -1;
			elseif($sUserAgentALength < $sUserAgentBLength)return 1;
			else return strcasecmp($sUserAgentA,$sUserAgentB);
		});
		return $this->setBrowscap($aBrowscap);
	}
}