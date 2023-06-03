<?php
namespace OsolMVC\Core;
class CoreParent
{
	protected static $inst =  null;
	protected static $instances =  array();// to solve  the issue https://stackoverflow.com/questions/17632848/php-sub-class-static-inheritance-children-share-static-variables
    /**
     *  @brief Singleton Constructor
     *  
     *  @return ClassInstance
     *  
     *  @details Caution: never call Class::getInstance() in another class's constructor, that instance will be discarded from $instances array 
	 *  @warning never call Class::getInstance() in another class's constructor
     */
    public static function getInstance()
	{
        
        //https://www.php.net/manual/en/reflectionclass.newinstancewithoutconstructor.php &
        //https://refactoring.guru/design-patterns/singleton/php/example
        $ref  = new \ReflectionClass( get_called_class() ) ;
         
        $reflectionProperty = new \ReflectionProperty(static::class, 'instances');
        $reflectionProperty->setAccessible(true);
        //echo $reflectionProperty->getValue();
        $instances =   $reflectionProperty->getValue();;//$reflectedClass->getStaticPropertyValue('inst');
		$intentedClass = static::class;
        //if (  $instances[static::class] == null)
        if (  !isset($instances[$intentedClass]))
        {
            
             
 
            // The magic.
            //$ctor->setAccessible( true ) ;
            //$inst = new static();
            $instances[$intentedClass] = new static();
            //echo "INSTANTIATED ".print_r($inst,true) ."<br />";
             
            $reflectionProperty->setValue(null/* null for static var */, $instances);
            //echo "<pre>". print_r(array_keys($instances),true)."</pre>";
        }
		
        return $instances[$intentedClass] ;
    }//public static function getInstance()
	protected function getNamespaceAndShortName($calledClass)
	{
		//$ref  = new \ReflectionClass( get_called_class() ) ;
		$ref  = new \ReflectionClass( $calledClass ) ;
		/* var_dump($function->inNamespace());
		var_dump($function->getName());
		var_dump($function->getNamespaceName());
		var_dump($function->getShortName()); */
		$namespaceAndShortName  = new \stdClass();
		$namespaceAndShortName->namespace = $ref->getNamespaceName();
		$namespaceAndShortName->shortName = $ref->getShortName();
		return $namespaceAndShortName;
	}//private function getNamespaceAndShortName()
	private function getReplacedClassNameForMVC($calledClass, $intendedClassType)
	{
		$namespaceAndShortName = $this->getNamespaceAndShortName($calledClass);
		$replacedNamespaceName = preg_replace("@Controller|Model|View@",$intendedClassType, $namespaceAndShortName->namespace);
		$replacedShortName = preg_replace("@Controller|Model|View@",$intendedClassType, $namespaceAndShortName->shortName);
		return "\\".$replacedNamespaceName."\\".$replacedShortName;
	}//private getReplacedClassNameForMVC($shortName)
	
	// get namespace &  shortName
	// get strippedShortName , stripping 'Controller','View' from shortName
	// get appendedShortName by appending 'Model' to strippedShortName
	//return concatted namspace & appendedShortName; 
	private function getModelViewController($calledClass, $intendedClassType)
	{
		$modelClass = $this->getReplacedClassNameForMVC($calledClass, $intendedClassType);	
		//$modelClass = $strippedClassNameForMVC."Model";
		//echo "Class $modelClass ";
		if(class_exists($modelClass))
		{
			//echo "exists"."<br />";
			return $modelClass::getInstance();
		}
		else
		{
			echo "Class $modelClass does not exist"."<br /> 
			This message is in line : " . __LINE__. " of " .__FILE__."<br /><br />";
			return null;
		}
		
		
	}//public function getModelViewController($calledClass, $intendedClassType)
	
	protected function getModel()
	{
		return $this->getModelViewController(get_called_class(),"Model");	
		
		
	}//public function getModel()
	protected function getView()
	{
		return $this->getModelViewController(get_called_class(),"View");	
		
		
	}//public function getView()
	
	protected function getController()
	{
		
		return $this->getModelViewController(get_called_class(),"Controller");	
	}//public function getController()
	public function getDefaultController()
	{
		$defaultController = \OsolMVC\Core\Controller\DefaultController::getInstance();
		return $defaultController;
	}//public function getDefaultController()
	protected function getDB()
	{
		if(!$this->doesDependencyClassExist("\OSOLUtils\Helpers\OSOLMySQL"))return null;
		$db = \OSOLUtils\Helpers\OSOLMySQL::getInstance();
		return $db;
	}//protected function getDB()
	protected function getCoreHelper($helperClass) 
	{
		$coreHelperClass = "\OsolMVC\Core\Helper\\".$helperClass."Helper";
		//die( "addonHelperClass is {$coreHelperClass}<br />");
		$helperClassInst =  null;
		if(class_exists($coreHelperClass))
		{
			$helperClassInst = $coreHelperClass::getInstance();
			//die( "addonHelperClass is {$helperClassInst}<br />");
		}//if(class_exists($addonHelperClass))
		return $helperClassInst;
	}//protected function selectPS($stmt,$types="",...$bindArgsRecieved) 
	protected function getSiteConfig()
	{
		$siteConfig = \OsolMVC\Core\Config\ClassSiteConfig::getInstance()
						->initialize();
		return $siteConfig;
	}//protected function getConfig()
	
	
    public function getAccessingOnDevice()
    {
        //https://developer.chrome.com/docs/multidevice/user-agent/#webview_user_agent
        //if (strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false)
        //if (strpos($_SERVER['HTTP_USER_AGENT'], 'YourApp/') !== false)

        $isWebView = false;
        
        $device = "web";
        switch(true)
        {
            case (strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false):
                $device = "android";
                break;
            case (strpos($_SERVER['HTTP_USER_AGENT'], 'YourApp/') !== false):
                $device = "iphone";
                break;
        }//switch(true)
        return $device;
        
    }//public function getAccessingOnDevice
	
	//------------------------------GET HELPER METHODS ---------------------------------------
	protected function getEmailHelper()
	{
		$emailHelper = \OsolMVC\Core\Helper\EmailHelper::getInstance();
		return $emailHelper;
	}//protected function getEmailHelper()
	protected function getGoogleLoginHelper()
	{
		$emailHelper = \OsolMVC\Core\Helper\GoogleLoginHelper::getInstance();
		return $emailHelper;
	}//protected function getGoogleLoginHelper()
	protected function getLogHelper()
	{
		$emailHelper = \OsolMVC\Core\Helper\LogHelper::getInstance();
		return $emailHelper;
	}//protected function getLogHelper()
	
	
	public function getSessionHandlerHelper()
	{
		$googleLoginHelper = \OsolMVC\Core\Helper\SessionHandlerHelper::getInstance();
		return $googleLoginHelper;
	}//protected function getSessionHandlerHelper()Class = "")
	//-------------------------------TEMPLATE METHODS
	protected function getTemplateMainSubFolder($calledClass = "")
	{
		if($calledClass == "")$calledClass = get_called_class();
		$namespaceAndShortName = $this->getNamespaceAndShortName($calledClass);
		$strippedShortName = strtolower(preg_replace("@Controller|Model|View@","", $namespaceAndShortName->shortName));
		//echo "strippedShortName is $strippedShortName<br />";
		return $strippedShortName;
		
	}//protected function getTemplateMainSubFolder()

	public function  getSelectedLangClass($addonName = "")
	{
		if($addonName !="")
		{
			return $this->getAddonHelper($addonName)
					->getSelectedLangClass($addonName);
		}
		else
		{
			$selectedLang = $this->getSelectedLang();
			$langclass = "\OsolMVC\Core\Lang\LangClass".str_replace("-","",strtoupper( $selectedLang));
			return $langclass::getInstance();
		}
	}//protected function  getSelectedLangClass($addonName = "")
	protected function  getSelectedLang(/* $addonName = "" */)
	{
		$siteConfig = $this->getSiteConfig();
		return $siteConfig->getLangInConfig();
	}//protected function  getSelectedLang()
	
	protected function  getSiteBase()
	{
		$siteConfig = $this->getSiteConfig();
		return $siteConfig->getSiteBase();
	}//protected function  getSiteBase()
	public function  getRequestVarHelper()
	{
		return \OsolMVC\Core\Helper\RequestVarHelper::getInstance();
	}//protected function  getRequestVarHelper()
	
	public function  getGeneralAddonHelper()
	{
		return \OsolMVC\Core\Helper\AddonHelper::getInstance();
	}//protected function  getGeneralAddonHelper()
	
	public function  getFilesHelper()
	{
		return \OsolMVC\Core\Helper\FilesHelper::getInstance();
	}//protected function  getFilesHelper()
	protected function getLangClass($lang = '')
	{
		$langClass = $this->getLangPHPClassName($lang );	
		return $langClass::getInstance();
	}//protected function getLangClass($lang = 'en-US')
	protected function getLangPHPClassName($lang = '')
	{
		$selectedLang = $lang;
		if($selectedLang == '' )$selectedLang =  $this->getSelectedLang();
		
		return '\OsolMVC\Core\Lang\LangClass'.strtoupper(str_replace("-","",$selectedLang));
	}//protected function getLangPHPClassName($lang = 'en-US')
	protected function getLangJsFullPath($lang = '')
	{
		$selectedLang = $lang;
		if($selectedLang == '' )$selectedLang =  $this->getSelectedLang();
		$langFileRelativePath  = "public/js/lang/classLang." . $selectedLang . ".js";
		//echo "langFilePath is ".$this->getSiteBase().$langFileRelativePath."<br />";
		return $this->getSiteBase().$langFileRelativePath;
	}//protected function getLangJsFullPath($lang = 'en-US')
	protected function getLangJsClass($lang = '')
	{
		$selectedLang = $lang;
		if($selectedLang == '' )$selectedLang =  $this->getSelectedLang();
		
		return "OSOLMVCls_".str_replace("-","_",$selectedLang);
	}//protected function getLangJsClass($lang = 'en-US')
	protected function getTemplateMainSubFolderFullPath($fileName)
	{
		$siteConfig = $this->getSiteConfig();
		//$privateFolderAbsPAth = $siteConfig->getPrivateFolderAbsolutePath()
		//$frontendTemplate = $siteConfig->getFrontendTemplate();
		return __DIR__."/templates/".$siteConfig->getFrontendTemplate()."/".$fileName;
	}//protected function getTemplateMainSubFolderFullPath($fileName)
	
    protected function getAddonBasePath()
	{
		return realpath(__DIR__."/../")."/Addons";
	}//protected function getAddonBasePath()

    protected function getAddonName($calledClass)
    {
        /* $namespaceAndShortName = $this->getNamespaceAndShortName($calledClass);
        $namespace = $namespaceAndShortName->namespace;//OsolMVC\Addons\Basic
        $meAddonName = strtolower(array_pop(preg_split("@\\\@",$namespace))); */
		
		$namespaceAndShortName = $this->getNamespaceAndShortName($calledClass);
		$namespace = $namespaceAndShortName->namespace;//OsolMVC\Addons\Basic
		//echo "namespace is {$namespace}<br />";
		$addonName = "";
		if(preg_match("@^OsolMVC\\\Addons\\\([^\\\]+)(\\\.+)?@",$namespace,$matches))
		{
			$addonName = $matches[1];
		}//if(preg_match("@^OsolMVC\\Addons\\(.+)(\\.+)?\@",$namespace,$matches))
        return $addonName;
    }//protected function getAddonName()
	
	public function getAddonHelper($addonName =  "Basic")
	{
		$namespaceAndShortName = $this->getNamespaceAndShortName(get_called_class());
		//$addonHelperClass = "\\" . $namespaceAndShortName->namespace."\Helper";
		$addonHelperClass = "\OsolMVC\Addons\\".$addonName."\Helper";
		//echo "addonHelperClass is {$addonHelperClass}<br />";
		$helperClassInst =  null;
		if(class_exists($addonHelperClass))
		{
			$helperClassInst = $addonHelperClass::getInstance();
		}//if(class_exists($addonHelperClass))
		return $helperClassInst;
	}//public function getAddonHelper()
	protected function getTemplateSubFolderFullPathOfAddon($addonName,$fileName, $isAdmin = false)
	{
		$siteConfig = $this->getSiteConfig();
		//$privateFolderAbsPAth = $siteConfig->getPrivateFolderAbsolutePath()
		//$frontendTemplate = $siteConfig->getFrontendTemplate();
        $addonBasePath = $this->getAddonBasePath();
        
        $addonSubFolder = $addonBasePath."/".$addonName;
		$adminPrefix = "";
		if($isAdmin)
		{
			$adminPrefix = "Admin/";
		}//if($isAdmin)
		return $addonSubFolder."/templates/".$siteConfig->getFrontendTemplate()."/".$adminPrefix.$fileName;
	}//protected function getTemplateMainSubFolderFullPath($fileName)
	protected function setCurrentViewIsAdmin($isAdmin)
	{
		$this->getSiteConfig()
				->setCurrentViewIsAdmin($isAdmin);
	}//protected function setCurrentViewIsAdmin($isAdmin)
	protected function getCurrentViewIsAdmin()
	{
		return $this->getSiteConfig()
							->getCurrentViewIsAdmin();
	}//protected function setCurrentViewIsAdmin($isAdmin)
	protected function getTemplateFileURL($scriptFile)
	{
		$siteConfig = $this->getSiteConfig();
		return "public/templates/".$siteConfig->getFrontendTemplate()."/".$scriptFile;
	}//protected function getTemplateFileURL($scriptFile)
	protected function doesDependencyClassExist($dependencyClass)
	{
		$returnVal = class_exists($dependencyClass);
		if(!$returnVal)
		{
			die( "Class ".$dependencyClass."  doen't exist. Load dependency using the command 'composer require osolutils/helpers'");
		}//if(!$returnVal)
		return $returnVal;
		
	}//protected function doesDependencyClassExist($dependencyClass)
	public function isCLI()
	{
		if ( defined('STDIN') )
		{
			return true;
		}

		if ( php_sapi_name() === 'cli' )
		{
			return true;
		}

		if ( array_key_exists('SHELL', $_ENV) ) {
			return true;
		}

		if ( empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) 
		{
			return true;
		} 

		if ( !array_key_exists('REQUEST_METHOD', $_SERVER) )
		{
			return true;
		}

		return false;
	}
    
}//class CoreParent
?>