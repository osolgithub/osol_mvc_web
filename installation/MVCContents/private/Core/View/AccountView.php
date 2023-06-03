<?php 

namespace OsolMVC\Core\View;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class AccountView extends \OsolMVC\Core\View\DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function login()
	{
		$page2Show = "login.html";
		/* $clsSiteConfig = $this->getSiteConfig();
		$accessingDevice = $clsSiteConfig->getAccessingOnDevice();
		switch($accessingDevice)
		{
			case "android":
				$page2Show = "android_login.html"; 
				break;
			default:
				$googleLoginHelper = $this->getGoogleLoginHelper();
				$googleLoginHelper->initialize();
				$googleClient4Login = $googleLoginHelper->getGoogleClientForLogin();		
				$googleLoginURL = $googleClient4Login->createAuthUrl();		
				$loginButtonURL = $this->getTemplateFileURL("images/sign-in-with-google.png");
				$this->variables4Template['login_button'] = '<a href="'.$googleLoginURL.'"><img src="'.$loginButtonURL.'" /></a>';


		}//switch($accessingDevice) */
		
		$googleLoginHelper = $this->getGoogleLoginHelper();
		$googleLoginHelper->initialize();
		$googleClient4Login = $googleLoginHelper->getGoogleClientForLogin();		
		$googleLoginURL = $googleClient4Login->createAuthUrl();		
		$loginButtonURL = $this->getTemplateFileURL("images/sign-in-with-google.png");
		$this->variables4Template['login_button'] = '<a href="'.$googleLoginURL.'"><img src="'.$loginButtonURL.'" /></a>';
		
		$faceBookHelper = $this->getCoreHelper("FacebookLogin");
		$faceBookHelper->initialize();		
		$faceBookLoginURL = $faceBookHelper->getFacebookOAuthURL();
		$facebookLoginButtonURL = $this->getTemplateFileURL("images/signinWithFB.png");
		$this->variables4Template['facebook_login_button'] = '<a href="'.$faceBookLoginURL.'"><img src="'.$facebookLoginButtonURL.'" /></a>';
		
		$this->page2Show = $page2Show;
		$this->showView();
	}//public function login()
	public function androidLogin()
	{
		
		$faceBookHelper = $this->getCoreHelper("FacebookLogin");
		$faceBookHelper->initialize();		
		$faceBookLoginURL = $faceBookHelper->getFacebookOAuthURL();
		$facebookLoginButtonURL = $this->getTemplateFileURL("images/signinWithFB.png");
		$this->variables4Template['facebook_login_button'] = '<a href="'.$faceBookLoginURL.'"><img src="'.$facebookLoginButtonURL.'" /></a>';
		
		
		
		$page2Show = "android_login.html";
		$this->page2Show = $page2Show;
		$this->showView();
	}//public function androidLogin()
	public function profile()
	{
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL($templateMainSubFolder."/css/profile.css");
		$this->addCSSLinkTag($templateFileURL);
		//die("HI in line # " . __LINE__ . " of " .__FILE__);
		/* $this->variables4Template['userDetails'] = $this->getGoogleLoginHelper()
															->initialize()
															->getUserDetails(); */
		$this->variables4Template['userDetails'] = $this->getCoreHelper("Account")
															->getUserDetailsWithSessionVar();
		$this->page2Show = "profile.html";
		$this->showView();
	}//public function profile()
	
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>