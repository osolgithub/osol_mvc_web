<?php 

/*
### Workflow
render()
	checks if view is available(method in CoreParent)
			if not available 
			gets '\OsolMVC\Core\View\DefaultView'
	view->showView()

*/

namespace OsolMVC\Core\Controller;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class DefaultController extends \OsolMVC\Core\CoreParent
{
	protected $message2Template = array();//"message" => "", "message_type" => ""
    
	protected function __construct()
	{
		
		$isAdmin = false;
		$this->setCurrentViewIsAdmin($isAdmin);
		
	}//protected function __construct()
	
	public function getAddonNameFromController()
	{
		$addonName2Return = "";
		if(isset($this->addonName))
		{

			$addonName2Return = $this->addonName;
		}
		return $addonName2Return;
	}//public function getAddonNameFromController()
	public function beforeRenderPage($renderMethod)// call set message if any, in extending class here
	{
		//echo  "this is beforeRenderPage of  $renderMethod<br />";
		/* $this->setMessage(array("message" => "Hello", "message_type" => "Info"));
		$this->setMessage(array("message" => "Hello", "message_type" => "Error")); */
	}//public function beforeRenderPage()
	
	public function afterRenderPage($renderMethod)
	{
		//echo  "this is afterRenderPage of  $renderMethod<br />";
	}//public function afterRenderPage()
	public function render()//$message2Template = array("message" => "", "message_type" => "")
	{
		if(!$myView = $this->getView())
		{
			$viewClass = "";//$this->getModelViewController($this,"View");
			echo "view does not exist<br /> 
			This message is in  line : " . __LINE__. " of " .__FILE__."<br /><br />";
			echo "Following classes/files are essential for a Core Feature or Addon<br />
			<ol>
				<li>Controller</li>
				<li>View : Core\View\FeatureView or Core\View\Admin\FeatureView or Addon\AddonName\View\View or Addon\AddonName\View\Admin\View</li>
				<li>Model</li>
				<li>Helper(Optional)</li>
				<li>Private template files : private\Core\templates\default\Admin\<core feature> or \private\Addons\AddonName\templates\default\Admin</li>				
				<li>public template files : or public\templates\default\addons\drafting</li>				
			</ol>
			";
			$defaultViewClass = '\OsolMVC\Core\View\DefaultView';
			$myView = $defaultViewClass::getInstance();
		}//if(!$myView = $this->getModel())	
		$myView->setPageTitle("HOME_PAGE");
		$myView->setMessage($this->message2Template);
		$myView->showView();		
		
	}//protected function renderPage()
	public function setMessage($message2Template )//= array("message" => "", "message_type" => "")
	{
		$this->message2Template[] = $message2Template;
	}//public function setMEssage($message2Template = array("message" => "", "message_type" => ""))
	
	public function render404()
	{
		 header("HTTP/1.0 404 Not Found");
		 echo "<h1>404 Not Found</h1>";
		 echo "The page that you have requested could not be found.";
		
	}//public function render404()
	public function errorPage()
	{
		 $errorMsg = $this->getCoreHelper("RequestVar")->getGetVar("errorMsg");
		 
		 $errorMsgHeadingInLang =  $this->getSiteConfig()
									->getSelectedLangClass()
									->getLangText("ERROR_MESSAGE_HEADING");
		 $errorMsgInLang =  $this->getSiteConfig()
									->getSelectedLangClass()
									->getLangText($errorMsg);
		 echo "<h1>{$errorMsgHeadingInLang}</h1>";
		 echo "<span style=\"color:red;\">{$errorMsgInLang}</span>";
		
	}//public function errorPage()
	public function test()
	{
		// show xml
		/* $newsXML = new \SimpleXMLElement("<news></news>");
		$newsXML->addAttribute('newsPagePrefix', 'value goes here');
		$newsIntro = $newsXML->addChild('content');
		$newsIntro->addAttribute('type', 'latest');
		Header('Content-type: text/xml');
		echo $newsXML->asXML(); */
		
		//https://stackoverflow.com/a/143260
		/* $domDoc = new \DOMDocument;
		$rootElt = $domDoc->createElement('root');
		$rootNode = $domDoc->appendChild($rootElt);

		$subElt = $domDoc->createElement('foo');
		$attr = $domDoc->createAttribute('ah');
		$attrVal = $domDoc->createTextNode('OK');
		$attr->appendChild($attrVal);
		$subElt->appendChild($attr);
		$subNode = $rootNode->appendChild($subElt);

		$textNode = $domDoc->createCDataNode('Wow, it works!');
		$subNode->appendChild($textNode);

		//echo htmlentities($domDoc->saveXML());
		
		Header('Content-type: text/xml');
		echo $domDoc->saveXML(); */
		
		/*
		Output of above code is 
		<osol_mvc_config>
			<file_paths ah="OK">Wow, it works!</file_paths>
		</osol_mvc_config>
		*/
		//https://stackoverflow.com/a/31242415
		/* $document = new \DOMDocument();
		$root = $document->appendChild(
		  $document->createElement('element-name')
		);
		$root->appendChild(
		  $document->createCDATASection('one')
		);
		$root->appendChild(
		  $document->createComment('two')
		);
		$root->appendChild(
		  $document->createTextNode('three')
		);
		Header('Content-type: text/xml');
		echo $document->saveXml(); */
		/*
		<osol_mvc_config>
			<file_paths>
				<projectRoot>
					<!CDATA[projectBase/PR11]>
				</projectRoot>
				<PRIVATE_FOLDER_ROOT>
					<!CDATA[private]>
				</PRIVATE_FOLDER_ROOT>
			</file_paths>
			<file_urls>
			</file_urls>
			<database>
			</database>
			<email>
			</email>	
		</osol_mvc_config>
		*/
		
		
		
		/* $string = '<?xml version="1.0" encoding="UTF-8"?>
					<books>
					   <book>
						  <name>PHP - An Introduction</name>
						  <price>$5.95</price>
						  <id>1</id>
					   </book>
					   <book>
						  <name>PHP - Advanced</name>
						  <price>$25.00</price>
						  <id>2</id>
					   </book>
					</books>';
		$doc = new \DOMDocument();
		$doc->loadXML($string);
		$books = $doc->getElementsByTagName('book');
		foreach ($books as $book) {
			$title = $book->getElementsByTagName('name')->item(0)->nodeValue;
			$price = $book->getElementsByTagName('price')->item(0)->nodeValue;
			$id = $book->getElementsByTagName('id')->item(0)->nodeValue;
			print_r ("The title of the book $id is $title and it costs $price." . "\n");
		} */
		
	}//public function test()
	public function captcha()
	{
		$captchaClass =  "\OSOLUtils\Helpers\OSOLmulticaptcha";
		if($this->doesDependencyClassExist($captchaClass))
		{
			$captcha = new $captchaClass();
			$captcha->displayCaptcha();
			$sessionHelper = \OsolMVC\Core\Helper\RequestVarHelper::getInstance();
			$sessionHelper->setSessionVar("OSOLmulticaptcha_keystring", $captcha->keystring);			
		}//if($this->doesDependencyClassExist($captchaClass))
		
	}//public function captcha()
	protected function getVerifyCaptchaResult($captchaVal = "")
	{
		$captchaClass =  "\OSOLUtils\Helpers\OSOLmulticaptcha";
		if(!$this->doesDependencyClassExist($captchaClass))return false;
		$requestHelper = \OsolMVC\Core\Helper\RequestVarHelper::getInstance();
		$sessionCaptchaVal = $requestHelper->getSessionVar("OSOLmulticaptcha_keystring");
		if($captchaVal == "")
		{
			$captchaVal2Verify = $requestHelper->getJsonVar("osolmvc_keystring");
		}
		else
		{
			$captchaVal2Verify = $captchaVal;
		}//if($captchaVal == "")
		
		//echo "$sessionCaptchaVal == $captchaVal2Verify<br />";
		$status = "error";
		$verificationResult = "INCORRECT_CAPTCHA";
		if($sessionCaptchaVal ==  $captchaVal2Verify)
		{
			$status = "success";
			$verificationResult = "correct captcha";
		}//if($captchaVal ==  $captchaVal2Verify)
		//$post = print_r($_POST,true);
		//echo "<pre>".print_r($_REQUEST,true)."</pre>";
		//echo "<pre>".print_r($requestHelper->getAllJsonVars(),true)."</pre>";
		//header("Content-type:application/json");
		return "{\"status\":\"".$status."\",\"message\":\"".$verificationResult."\",\"addnlInfo\":\"captcha sent was ".$captchaVal2Verify."\"}";
	}//public function verifyCaptcha()
	protected function sendJSONHeader()
	{
		header("Content-type:application/json");
	}//protected function sendJSONHeader()
	public function verifyCaptcha()
	{
		$verifyResult = $this->getVerifyCaptchaResult();		
		$this->sendJSONHeader();
		echo $verifyResult;
	}//public function verifyCaptcha()
	
	public function setup()
	{
		$setup = \OsolMVC\Core\Setup\Setup::getInstance();
		$setup->runMysqlQueries();
	}//public function setup()
	
	public function redirect2Page($page2go)
    {
       
        header("location:".$this->getSiteConfig()->getSiteUrl($page2go));
    }//public function redirect2Page($page2go)
	protected function redirectIfNotLoggedIn($adminPage = false)
	{
		
		$sessionHandlerHelper = $this->getSessionHandlerHelper();
		$isLoggedIn =  $sessionHandlerHelper->isLoggedIn();
		if(!$isLoggedIn)
		{
			$clsSiteConfig = $this->getSiteConfig();
			$accessingDevice = $clsSiteConfig->getAccessingOnDevice();
			
			$adminPath2Concat = $adminPage? "/Admin":"";
			switch($accessingDevice)
			{
				case "android":
					$this->redirect2Page("Account" . $adminPath2Concat . "/androidLogin");
					break;
				default:
					//$this->getView()->login();
					$this->redirect2Page("Account" . $adminPath2Concat . "/login");
			}//switch($accessingDevice)
			
			
		}
	}//protected function redirectIfNotLoggedIn($adminPage = false)
	protected function redirectIfNotAuthorised($permissionsRequired = array())
	{
		$requestHelper = $this->getCoreHelper("RequestVar");
		$user_id = $requestHelper->getSessionVar("user_id");
		if($user_id == 0)
		{
			throw new \Exception('ALERT_NOT_AUTHORISED');
		}//if($user_id == 0)
		$ACLHelper = $this->getCoreHelper("ACL");
		$requiredPermissions = $permissionsRequired;//["admin.core.view"];
		$hasPermission = $ACLHelper->hasPermission($requiredPermissions);
		if(!$hasPermission) 
		{
			throw new \Exception('ALERT_NOT_AUTHORISED');
		}//if(!$hasPermission) 
		
		
	}//redirectIfNotAuthorised($adminPage = false, $permissionsRequired = array())
	

}//class DefaultController extends \OsolMVC\Core\CoreParent

?>