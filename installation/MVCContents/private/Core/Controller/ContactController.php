<?php 

namespace OsolMVC\Core\Controller;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class ContactController extends DefaultController
{
    
	protected function __construct()
	{
		
		
	}
	public function render()//$message2Template = array("message" => "", "message_type" => "")
	{
		
		//echo  "This is Contact Controller<br />";
		//parent::render($message2Template = array("message" => "", "message_type" => ""));
		$myView = $this->getView();
		$myView->setPageTitle("CONTACT_US");
		$myView->showView();
	}//public function render()
	/* public function render()
	{
		if(!$myView = $this->getView())
		{
			
			echo "view does not exist<br />";
			$defaultViewClass = '\OsolMVC\Core\View\DefaultView';
			
		}
		else
		{
			$myView->;
		}//if(!$myView = $this->getModel())	
		
		$myView->showView();		
		
	}//protected function renderPage() */
	
	public function submit()
	{
		if(!$this->doesDependencyClassExist("\PHPMailer\PHPMailer\PHPMailer"))
		{
			return false;
		}
		$requestVarHelper = $this->getRequestVarHelper();
		///CHECK Captcha
		$verifyCatpchaResultJSON =  $this->getVerifyCaptchaResult($requestVarHelper->getPostVar("osolmvc_keystring"));
		$verifyCatpchaResult = json_decode($verifyCatpchaResultJSON);
		if($verifyCatpchaResult->status != 'success')
		{
			$prePost = print_r($_POST,true);
			die("Incorrect Captcha ".$verifyCatpchaResultJSON." post is ".$prePost);
		}//if(!verifyCaptcha())
		// validate form
		$senderEmail = $requestVarHelper->getPostVar("email");//"office@outsource-online.net";
		$messageSubject = "New Enquiry from OSOL MVC!!!";
		$messageBody = $requestVarHelper->getPostVar("message");//"Hello Mr Zamindar";
		if($senderEmail == "" || $messageBody =="")
		{
			die("{\"status\":\"error\",\"message\":\"Email and Message fields are mandatory\"}");
		}//if($senderEmail == "" || $messageBody =="")
		
		$templateMainSubFolder = $this->getTemplateMainSubFolder();
		//$templateFile =  __DIR__."/../templates/default/".$templateMainSubFolder."/main.html";
		$templateFile =  $this->getTemplateMainSubFolderFullPath($templateMainSubFolder."/email.html");
		$emailHTMLMessage = file_get_contents($templateFile);
		
		$siteConfig = $this->getSiteConfig();
		$emailResourcesFolder = $siteConfig->getAppRoot()."/public";
		//die("emailResourcesFolder is $emailResourcesFolder");
		$settings4CurrentEmail = array(
									'senderEmail' => $senderEmail,
									'messageSubject' => $messageSubject,
									'messageBody' => $messageBody,
									'emailHTMLMessage' => $emailHTMLMessage,
									'emailResourcesFolder' => $emailResourcesFolder,
									);
		$emailHelper = $this->getEmailHelper($settings4CurrentEmail);
		//$response2Send = ("{\"status\":\"error\",\"message\":\"".addslashes($mail->ErrorInfo)."\"}");
		$response2Send = $emailHelper->sendEmail($settings4CurrentEmail);
		die($response2Send);

	}//public function submit()     
}//class ContactController

?>