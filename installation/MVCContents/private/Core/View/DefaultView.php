<?php 
/*
### Workflow
@note : methods getCoreTemplateFullPath($fileName) and requirePagePart($type) are extended in Admin/DefaultView to use 'Admin/' prefix 
Comes from controller->render

showView():returns void
		$templateFile = $this->getCoreTemplateFullPath($this->page2Show);//"main.html"
					eg:__DIR__."/../templates/default/".(corefeature/defult)."/main.html";
		$this->deliverPage($templateFile);
		------------------------------------
				this is extended to use 'Admin/' prefeix in Admin/DefaultView
				function getCoreTemplateFullPath($fileName)
				{
					$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
					//$templateFile =  __DIR__."/../templates/default/".$templateMainSubFolder."/main.html";
					$templateFile =  $this->getTemplateMainSubFolderFullPath($templateMainSubFolder."/".$fileName);
				------------------------------------
				this is in Core\CoreParent
				returns returns what to suffix 'private/Core/templates/', eg:'contact' for 'Contact'
				protected function getTemplateMainSubFolder($calledClass = "")
				{
					if($calledClass == "")$calledClass = get_called_class();
					$namespaceAndShortName = $this->getNamespaceAndShortName($calledClass);
					$strippedShortName = strtolower(preg_replace("@Controller|Model|View@","", $namespaceAndShortName->shortName));
					//echo "strippedShortName is $strippedShortName<br />";
					return $strippedShortName;
				------------------------------------
				this is in Core\CoreParent
				returns private/Core/templates/$fileName
				protected function getTemplateMainSubFolderFullPath($fileName)				 
				{
					$siteConfig = $this->getSiteConfig();
					//$privateFolderAbsPAth = $siteConfig->getPrivateFolderAbsolutePath()
					//$frontendTemplate = $siteConfig->getFrontendTemplate();
					return __DIR__."/templates/".$siteConfig->getFrontendTemplate()."/".$fileName;
				------------------------------------
		
		function deliverPage($templateFile, $ajax = false)
			$this->requirePagePart('header');
			$this->requirePagePart('breadCrumbs');
			require_once($templateFile);
				-------------------------------------
				this is extended to use 'Admin/' prefeix in Admin/DefaultView
				function requirePagePart($type)
				{
					
					$fileToReturn  = $type;//."LoggedIn";
					$returnFile =$this->getTemplateMainSubFolderFullPath('default')."/pageParts/".$fileToReturn.".html";
				-------------------------------------



### Core Feature Template Structure	

	Page parts(header, breadcrumbs, footer) Will be in 
	private/Core/templates/<chosen template eg: default>/default/pageParts	
	And
	private/Core/templates/<chosen template eg: default>/Admin/default/pageParts

	main page area(default 'main.html') will be in
	private/Core/templates/<chosen template eg: default>/<component>	
	And
	private/Core/templates/<chosen template eg: default>/Admin/<component>


	public assets(js,css,images etc) are in 

	public/templates/<chosen template eg: default>
	coreParent->getTemplateFileURL(, to get the above url
	you may use subfolders in the above folder for each component(core feature)
*/
namespace OsolMVC\Core\View;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class DefaultView extends \OsolMVC\Core\CoreParent
{
    
	protected $page2Show = "main.html";
	protected $pageTitle = "DEFAULT_VIEW";
	protected $scriptTags = array();
    protected $scriptCodes = array();
    protected $onLoadScriptCodes = array();
    protected $cssLinkTags = array();
    protected $cssCodes = array();
	protected $addPreloader = false;
	protected $variables4Template = array();
	protected $message2Template = array();//"message" => "","message_type" => ""
	protected $moduleLinks = array();
	/* protected function __construct()
	{
		
	} */
	public function setPage2Show($page2Show)
	{
		$this->page2Show = $page2Show;
		//echo "page2Show is ".$page2Show."<br />";
		return $this;
	}//public function setPage2Show($page2Show)
	public function addJSScriptTag($filePath)
	{
		//echo "Script added ". $filePath."<br />";
		$this->scriptTags[] = $filePath;
		
	}//public function addJSScriptTag($filePath)
	public function addJSScriptCode($scriptCode2Add, $onLoad = false)
	{
		if($onLoad)
		{
			$this->onLoadScriptCodes[] = $scriptCode2Add;
		}
		else
		{
			$this->scriptCodes[] = $scriptCode2Add;
		}//if($onLoad)
		
		
	}//public function addJSScriptCode($scriptCode2Add, $onLoad = false)
	public function addCSSLinkTag($filePath)
	{
		$this->cssLinkTags[] = $filePath;
		
	}//public function addCSSLinkTag($filePath)
	public function addCSSCode($filePath)
	{
		$this->cssCodes[] = $filePath;
		
	}//public function addCSSCode($filePath)
	
	public function getPublicURL($fileName)
	{
		$siteBaseURL =  $this->getSiteConfig()
							->getFullURL2Root();
		return $siteBaseURL."public/".$fileName;
	}//public function getPublicURL(fileName)
	protected function getCoreTemplateFullPath($fileName)
	{
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		//$templateFile =  __DIR__."/../templates/default/".$templateMainSubFolder."/main.html";
		$templateFile =  $this->getTemplateMainSubFolderFullPath($templateMainSubFolder."/".$fileName);
		return $templateFile;
	}//protected function getCoreTemplateFullPath($fileName)
	
	protected function requirePagePart($type)
	{
		
		$fileToReturn  = $type;//."LoggedIn";
		$returnFile =$this->getTemplateMainSubFolderFullPath('default')."/pageParts/".$fileToReturn.".html";		
		
		if($type == "footer")
		{
			$this->doFooterReplaces($returnFile);
		}
		else
		{
			require_once($returnFile);
		}//if($type == "footer")
	}//protected function requirePagePart($type)

	protected function doFooterReplaces($returnFile){
		$linkTags = "";
		
		$footerReplaceBlocks =  array(
								"__OSOLMVC_CSS_LINK_TAGS__" => "",
								"__OSOLMVC_CSS_CODES__" => "",
								"__OSOLMVC_SCRIPT_TAGS__" => "",
								"__OSOLMVC_SCRIPT_CODES__" => "",
								"__OSOLMVC_ON_LOAD_SCRIPT_CODES__" => ""
								);
		if(count($this->cssLinkTags) > 0)
		{
			$footerReplaceBlocks["__OSOLMVC_CSS_LINK_TAGS__"] = "<link href=\"" . join("\" rel=\"stylesheet\">\r\n<link href=\"", $this->cssLinkTags)."\" rel=\"stylesheet\">\r\n";
		}//if(count($this->cssLinkTags) > 0)
		
		if(count($this->cssCodes) > 0)
		{
			$footerReplaceBlocks["__OSOLMVC_CSS_LINK_TAGS__"] = "<style>".join("\r\n",$this->cssCodes)."</style>";
		}//if(count($this->cssCodes) > 0)
			
		if(count($this->scriptTags) > 0)
		{
			$footerReplaceBlocks["__OSOLMVC_SCRIPT_TAGS__"] = "<script src=\"" . join("\" ></script>\r\n<script src=\"", $this->scriptTags)."\"></script>\r\n";
		}//if(count($this->cssLinkTags) > 0)
			
		if(count($this->scriptCodes) > 0)
		{
			$footerReplaceBlocks["__OSOLMVC_SCRIPT_CODES__"] = "<script>".join("\r\n",$this->scriptCodes)."</script>";
		}//if(count($this->scriptCodes) > 0)
		$footerReplaceBlocksRegExps = array_map(function($val){return "@".$val."@";},array_keys($footerReplaceBlocks));	
	
		if(count($this->onLoadScriptCodes) > 0)
		{
			$footerReplaceBlocks["__OSOLMVC_ON_LOAD_SCRIPT_CODES__"] = join("\r\n",$this->onLoadScriptCodes);
		}//if(count($this->scriptCodes) > 0)
		$footerReplaceBlocksRegExps = array_map(function($val){return "@".$val."@";},array_keys($footerReplaceBlocks));
		
		$footerCode = file_get_contents($returnFile);
		$footerCode =preg_replace($footerReplaceBlocksRegExps,array_values($footerReplaceBlocks),$footerCode);
		echo $footerCode;
		//exec( $footerCode);
	}//private function doFooterReplaces(){
	protected function getPreloaderHTML()
	{
		$preloaderHTML = <<<EOT
		
            <!-- Modal Structure -->
            <div id="preloaderModal" class="modal">
              <div id="modalContent" class="modal-content">
                <h4 id="preloaderModalHeader">Modal Header</h4>
                <div id="preloaderModalContent">A bunch of text</div>

              </div>
              <div id="preloaderModalFooter" class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
              </div>
          </div>
EOT;
		return $preloaderHTML;
	}//protected function getPreloaderHTML()
	public function showView()
	{
		$isAdmin = $this->getCurrentViewIsAdmin();
		if(!$isAdmin && !(OSOL_MVC_CURRENT_CONTROLLER == "General" && in_array(OSOL_MVC_CURRENT_RENDER_METHOD ,[ "allowCookie", "privacyPolicy"])))
		{
			$templateFileURL = $this->getTemplateFileURL("js/cookieConsent.js");
			$this->addJSScriptTag($templateFileURL);
		}//if(!$isAdmin && (OSOL_MVC_CURRENT_RENDER_METHOD != "allowCookie"))
        //echo "This is showView<br />";
		
		/* $templateMainSubFolder = $this->getTemplateMainSubFolder();
		//require_once(__DIR__."/../templates/default/".$templateMainSubFolder."/main.html");
		$templateFile =  $this->getTemplateMainSubFolderFullPath($templateMainSubFolder."/main.html"); */
		//echo "page2Show is ".$this->page2Show;
		//if no message set by controller, check if message send via url param
		/* if($this->message2Template == array("message" => "","message_type" => ""))
		{
			$requestVarHelper = $this->getCoreHelper("RequestVar");
			//die("<pre>".print_r($_GET,true)."</pre>");
			//die("message is ".$requestVarHelper->getGetVar("message"));
			$this->setMessage(array(
								"message" => $requestVarHelper->getGetVar("message"),
								"message_type" => $requestVarHelper->getGetVar("message_type")
			));

		}//if($this->message2Template == array("message" => "","message_type" => "")) */
		$templateFile = $this->getCoreTemplateFullPath($this->page2Show);//"main.html"
		$this->deliverPage($templateFile);
	}
	protected function deliverPage($templateFile, $ajax = false)
	{
		if(file_exists($templateFile))
		{
			$this->requirePagePart('header');
			$this->requirePagePart('breadCrumbs');
			require_once($templateFile);
			

			$langJSFullPath = $this->getLangJsFullPath();
			$this->addJSScriptTag($langJSFullPath);
			
			$scriptCode2Add = "osolMVCSelectedLang = " . $this->getLangJsClass().";\r\n";
			$onLoad = true;
			$this->addJSScriptCode($scriptCode2Add, $onLoad);
			
			$templateFileURL = $this->getTemplateFileURL("js/commonUtils.js");
			$this->addJSScriptTag($templateFileURL);
			
			$templateFileURL = $this->getTemplateFileURL("css/main.css");
			$this->addCSSLinkTag($templateFileURL);
			$this->requirePagePart('footer');
		}
		else
		{
			echo "line :". __LINE__. " in " . __FILE__."<br />".
				"Template file does not exist : ".$templateFile."<br /><br />";
		}
			
	}//private function deliverPage($templateFile)
	protected function getAllModuleLinks():String
	{
		$addonRoutes = $this->getSiteConfig()
							->getRecordedAddons();
		$moduleLinksHTML = "";
		foreach($addonRoutes as $addonRoute)
		{
			$addonHelper = $this->getAddonHelper($addonRoute);
			if(!is_null($addonHelper))
			{
				$moduleLinks2Add = $addonHelper->getModuleLinks();
				$this->moduleLinks = array_merge($this->moduleLinks, $moduleLinks2Add);
				foreach($moduleLinks2Add as $moduleLink2Add)
				{
					$moduleLinksHTML .= '<li><a class="waves-effect" href="'.$moduleLink2Add['link'].'">'.$moduleLink2Add['link_text'].'</a></li>';
				}//foreach($moduleLinks2Add as $moduleLink2Add)
			}//if(!is_null($addonHelper))
			
		}//foreach($addonRoutes as $addonRoute)
		return $moduleLinksHTML;
		
	}//protected function collectAllModuleLinks($moduleLinks = array())
	

	
	protected function getMessage()
	{
		return $this->message2Template;
	}//protected function getMessage()
	public function setMessage($message2Template = array())//array("message" => "","message_type" => "")
	{
		$this->message2Template = $message2Template;
	}//protected function setMessage($message2Template = array("message" => "","message_type" => ""))

	public function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}//protected function setPageTitle($pageTitle)
	protected function getPageTitle():String
	{
		$addonName =  $this->getController()
							->getAddonNameFromController();
		
		
		
		
		$siteTitle = $this->getSiteConfig()
							->getSiteTitle();
		/* $pageTitle = $this->getSelectedLangClass($addonName)
						->getLangText( $this->pageTitle);
		$siteTitle = $this->getSelectedLangClass($addonName)
						->getLangText( $siteTitle ); */
		return  $this->_Text($siteTitle) . " : " .
				$this->_Text($this->pageTitle);
	}//protected function getPageTitle():String
	
	protected function _Text($textConst)
	{
		$addonName = "";
		return $this->getSelectedLangClass($addonName)
						->getLangText($textConst);
	}//protected function __Text()
}

?>