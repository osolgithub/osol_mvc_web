<?php
//site name
/* define('SITE_NAME', 'your-site-name');

//App Root
define('APP_ROOT', dirname(dirname(__FILE__)));
define('URL_ROOT', '/');
define('URL_SUBFOLDER', '');

//DB Params
define('DB_HOST', 'your-host');
define('DB_USER', 'your-username');
define('DB_PASS', 'your-password');
define('DB_NAME', 'your-db-name'); */

namespace OsolMVC\Core\Config;
class ClassSiteConfig extends \OsolMVC\Core\CoreParent
{
    
    private $defaultLang = "en-US";
    private $selectedLang = "en-US";
    private $template = "default";    
    private $currentViewIsAdmin = false;//set dynamically in a session to identify folder from which template is to be got    
    /*LOGGING ATTRIBUTES STARTS HERE */
    private $doLog = false;
    private $allLogFile = "../logs/allLogs.txt";//to make is relative to 'private' folder, it is prepended with OSOLMVC_APP_ROOT, which is the path to private/Core folder
    /*LOGGING ATTRIBUTES ENDS HERE */
    private $siteBase = OSOLMVC_URL_ROOT;//"/pjt/PMUtilities/projectBase/PR11/";
    private $googleAppSettings =  null;
    
    private  $dbSettings = null;
   
    //"timezone(for time during gc),session_life_time,idle_allowed"
    private $sessionSettings =  null;
    private $siteSettings = null;
    private $ajaxPages = array();
	
    protected function __construct()
    {
        $this->selectedLang =  $this->defaultLang;
		
    }
	public function initialize()
	{
		/* require_once(__DIR__."/CoreRoutes.php");
		require_once(__DIR__."/EmailConfig.php");
		require_once(__DIR__."/DBConfig.php");
		require_once(__DIR__."/SessionConfig.php");
		require_once(__DIR__."/SiteConfig.php"); */
		/* $configFiles = array_diff(scandir(__DIR__), array('.', '..', 'index.html', basename(__FILE__)));
		foreach($configFiles as $configFile) */
		if(!isset($this->googleAppSettings['redirectURL']))// $this->getSiteConfig() is called in many classes. we only need to load this once
		{
			foreach (glob(__DIR__."/*.php") as $configFile)
			{
				$file2Include = $configFile;
				//echo $file2Include."<br />";
				if(!is_dir($file2Include) &&  ($configFile != basename(__FILE__)))require_once($file2Include);
			}//foreach($configFiles as $configFile)
			$this->sessionSettings['site_time_zone'] = $this->siteSettings['site_time_zone'];
			//echo $this->googleAppSettings['redirectURL']."<br/>";
		}//if(!isset($this->googleAppSettings['redirectURL']))
		return $this;
	}//public function initialize()
	public function getFullURL2Root()
	{
		// Program to display URL of current page.
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
			$link = "https";
		else $link = "http";
		  
		// Here append the common URL characters.
		$link .= "://";
		  
		// Append the host(domain name, ip) to the URL.
		$link .= $_SERVER['HTTP_HOST'];
		  
		// Append the requested resource location to the URL
		$link .= OSOLMVC_URL_ROOT;//$_SERVER['REQUEST_URI'];
		  
		// Print the link
		return $link;
	}//public function getFullURL2Root()
	public function getAppRoot()
	{
		return OSOLMVC_APP_ROOT;//constant set in private/Core/bootstrap.php
	}//public function getAppRoot()
	public function getPrivateFolderAbsolutePath()
	{
		return OSOLMVC_APP_ROOT."/".OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH;//constants set in index.php
	}//public function getAppRoot()
	public function getProjectRootUri()
	{
		return OSOLMVC_URL_ROOT;//constant set in index.php
	}//public function getProjectRootUri()
	public function getFrontendTemplate()
	{
		$templateFolder =  $this->siteSettings['frontendTemplate'];
		$isadmin = $this->getCurrentViewIsAdmin();
		if($isadmin)
		{
			$templateFolder =  $this->siteSettings['adminTemplate'];
		}//if($isadmin)
		return $templateFolder;//"default";
	}//public function getFrontendTemplate()
	public function isCoreController($contollerName)
	{
		return (in_array($contollerName,$this->coreRoutes));
	}//public function isCoreController($contollerName)
    public function getRecordedAddons()
    {
        return $this->addonRoutes;
    }//public function getRecordedAddons()
    /* public function add2AjaxList($ajaxURLCrumb)
    {
        $this->getTags2AutoLoad[] = $ajaxURLCrumb;
        ob_end_clean();
    }//public function add2AjaxList($ajaxURLCrumb) */
    /*LOGGING RELATED METHODS  STARTS HERE */
    /* private $doLog = false;*/
    public function setDoLog($trueOrFalse)
    {
        $this->doLog = $trueOrFalse;
    }//public function setDoLog($trueOrFalse)
    /* private $doLog = false;*/
    public function getDoLog()
    {
       
        return $this->doLog;
    }//public function setDoLog($trueOrFalse)
    public function clearLog()
    {
        if($this->doLog)
        {
            $logFile  = $this->getAllLogFile();
            file_put_contents($logFile,'');

        }//if($this->doLog)
        
    }//public function clearLog()
    /*private $allLogFile = "logs/allLogs.txt"; */
    public function getAllLogFile(){
		
        return OSOLMVC_APP_ROOT."/".$this->allLogFile;//.OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH."/"
    }//public function getAllLogFile(){
    /*LOGGING RELATED METHODS ENDS HERE */
    public function getLangInConfig()
    {
        return $this->selectedLang;
    }//public function getLangInConfig()
    public function getSelectedLangInConfig()
    {
        return $this->selectedLang;
    }//public function getSelectedLangInConfig()
    public function getTemplateURLPath()
    {
        return "php/views/templates/" . $this->template."/";
    }//public function getTemplateURLPath()
    
    public function getTemplate()
    {
        return $this->template;
    }//public function getTemplate()

    public function getTemplateFolderPath()
    {
        return __DIR__."/views/templates/".$this->template;
    }//public function getTemplateFolderPath()
    
    public function getSiteSettings()
    {
        return $this->siteSettings ;
    }//public function getSiteSettings()
   
    public function getGoogleAppSettings()
    {
        return $this->googleAppSettings ;
    }//public function getGoogleAppSettings()
	public function getFacebookAppSettings()
    {
        return $this->facebookAppSettings ;
    }//public function getFacebookAppSettings()
   
    public function getDBSettings()
    {
        return $this->dbSettings ;
    }//public function getDBSettings(
	
    public function getSMTPSettings()
    {
        return $this->smtpSettings ;
    }//public function getSMTPSettings()
   
    public function getSessionSettings()
    {
        return $this->sessionSettings ;
    }//public function getDBSettings()
    
	public function setLogQueries($enable)
	{
		$this->dbSettings['log_queries'] = $enable;
    }//public function setLogQueries($enable)
    public function getSiteBase()
    {
       return $this->siteBase; 
    }//public function getSiteBase()
    public function isAJAXPage($redirect_to)
    {
        $isAjax = false;
        /* echo "<br /> in_array for ajax check is $redirect_to <pre />".
                print_r($this->ajaxPages,true)."</pre>".
                in_array($redirect_to,$this->ajaxPages)."<br />";//submitLinkFromChromeExt */
        if(in_array($redirect_to,$this->ajaxPages) )
        {
            $isAjax = true;
        }//if(in_array($redirect_to,$htis->ajaxPages))
        return $isAjax ;
    }//public function isAJAXPage($redirect_to)

    public function getBaseURL() 
    {
        // output: /myproject/index.php
       // $currentPath = $_SERVER['PHP_SELF']; 

        // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
        //$pathInfo = pathinfo($currentPath); 

        // output: localhost
        $hostName = $_SERVER['HTTP_HOST']; 

        // output: http://
        //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
        $protocol = "http";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') 
        { 
            //echo "https is ON"; 
             $protocol = "https";
        }
       

        // return: http://localhost/myproject/
        return $protocol.'://'.$hostName.$this->siteBase;
    }//public function getBaseURL() 

    public function getLoginURL()
    {
        return $this->getBaseURL(). "login";
    }//public getLoginUrl()

    public function getCurrentTemplateURL()
    {
        return $this->getBaseURL(). $this->getTemplateURLPath();
    }//public getLoginUrl()
    public function getSiteUrl($page2go)
    {
        $baseURL = $this->getBaseURL();
        //echo $page2go."<br />";
        if(substr($page2go, 0, strlen($baseURL)) !== $baseURL)
        {
            $baseURL .= $page2go;
        }
        return $baseURL;
    }//public function getSiteUrl($page2go)
    public function getSiteTitle():String
    {
        return $this->getSelectedLangClass()
                    ->getLangText($this->siteSettings['site_title']);
    }//public function getSiteTitle():String
    
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
	public function getAutoRegistrationEnabled()
	{
		return $this->siteSettings['autoRegistrationEnabled'];
	}//public function getAutoRegistrationEnabled()
	public function setCurrentViewIsAdmin($isAdmin)
	{
		$this->currentViewIsAdmin = $isAdmin;
	}//public function setCurrentViewIsAdmin($isAdmin)
	
	public function getCurrentViewIsAdmin()
	{
		return $this->currentViewIsAdmin;
	}//public function setCurrentViewIsAdmin($isAdmin)
}//class ClassSiteConfig
?>