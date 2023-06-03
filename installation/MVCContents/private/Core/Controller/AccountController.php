<?php 

namespace OsolMVC\Core\Controller;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class AccountController extends DefaultController
{
    
	protected function __construct()
	{
		
		
	}
	public function render()//$message2Template = array("message" => "", "message_type" => "")
	{
		
		//echo  "This is Contact Controller<br />";
		//parent::render();
		$adminPage = false;
		$this->redirectIfNotLoggedIn($adminPage);
		//$this->redirectIfNotAuthorised($adminPage);// after adding ACL, authorisation check is required beyond authentication
		$isLoggedIn =  $this->getSessionHandlerHelper()
							->isLoggedIn();
		if(!$isLoggedIn)
		{
			$clsSiteConfig = $this->getSiteConfig();
			$accessingDevice = $clsSiteConfig->getAccessingOnDevice();
			switch($accessingDevice)
			{
				case "android":
					$this->redirect2Page("Account/androidLogin");
					break;
				default:
					//$this->getView()->login();
					$this->redirect2Page("Account/login");
			}//switch($accessingDevice)
			
		}
		else
		{
			$this->redirect2Page("Account/profile");
		}//if(!$isLoggedIn)
		exit;
		//$this->redirect2Page("Account/profile");
		
	}//public function render()
	public function login()
	{
		$isLoggedIn =  $this->getSessionHandlerHelper()
							->isLoggedIn();
		if($isLoggedIn)
		{
			$this->redirect2Page("Account/profile");
		}//if($isLoggedIn)
		if(!$this->doesDependencyClassExist("\Google\Client") || !$this->doesDependencyClassExist("\Facebook\Facebook"))
		{
			die("This Feature Not Implemented. ".basename(__FILE__)." line # ". __LINE__);
			return false;
		}
		$myView = $this->getView();
		$myView->setPageTitle("SIGN_IN");
		$myView->login();
	}//public function  login()
	public function androidLogin()
	{
		if(!$this->doesDependencyClassExist("\Google\Client"))
		{
			die("This Feature Not Implemented. ".basename(__FILE__)." line # ". __LINE__);
			return false;
		}
		$myView = $this->getView();
		$myView->setPageTitle("SIGN_IN");
		$myView->androidLogin();
	}//public function androidLogin()
	public function facebookLoginRedirect()
	{
		//https://modestoffers.com/DEMOsites/OSOLMVC/Account/fbLoginRedirect?code=AQA39eLAOWv0fDRyxY_KclcBmNnGSmHz2aXJy1UwcZZr3SMU8BbsBzlgGgBPZUPId9aBPesdCdwl2nOILLT_7CrbuEIrqngo86W_PzFoYB1ryVvIYAldctHujb8Szx3GBTEJECQ84XtpoIPXVXJMWIMO_4mFTh5kRnMyf4t-CwGgBvy07TcMpXuQr7wlpAAcwAYdQU9mt-d_abFuSIvypV5zlr0XxYkKBJUeVmrv1udYVvjLzaRwq4xKrLuuepN0jAnVCNvaoW1aXyn1F82_Hju4aFgm-hN49JEWCzWjQpzqcXr5F0Vi7oNRKp_Y_p-YCkNgKj4sRUwUy9zAQTtFeh76qgA9wfka3uQy7QWk1UWcslX-8RX88ReCZW0oHlmI5AmSpdBHO8Mt9aMvx1EM9cUO84ypFizGtLieJYUdYUbLEg&state=72798b56e51f0a59f297d7b05c4cffed#_=_
		if(!$this->doesDependencyClassExist("\Facebook\Facebook"))
		{
			
			die("This Feature Not Implemented. ".basename(__FILE__)." line # ". __LINE__);
			return false;
		}
		$facebookLoginHelper = $this->getCoreHelper('FacebookLogin');
		$facebookLoginHelper->initialize();
		try{
				
				$facebookLoginHelper->processFacebookResponseAfterSignin();
			}
			catch(\Exception $e)
			{
				$exceptionMessage = $e->getMessage();
				if($exceptionMessage == "AUTO_REGISRATION_DISABLED")
				{
					$this->redirect2Page("Account/errorPage?errorMsg=AUTO_REGISRATION_DISABLED");
				}
				elseif(preg_match("@Error validating access token:@",$exceptionMessage,$matches))
				{
					//Error validating access token: The session has been invalidated because the user changed their password or Facebook has changed the session for security reasons.
					$requestHelper = $this->getCoreHelper('RequestVar');
					$requestHelper->setSessionVar('facebook_access_token',null);
					//$this->redirect2Page("Account/login");
					die("<script>alert('Some issue encountered. Please try again'); window.location.href='../Account/login'</script>");
				}
				elseif(preg_match("@fb_exchange_token parameter not specified@",$exceptionMessage,$matches))
				{
					/* $requestHelper = $this->getCoreHelper('RequestVar');
					$requestHelper->setSessionVar('facebook_access_token',null); */
					//die("<script>alert('Some issue encountered. Please try again'); window.location.href='../Account/login'</script>");
					die("Some issue encountered. Please go back to <a href=\"../Account/profile\">login</a> and try again");
				}
				else
				{
					//"fb_exchange_token parameter not specified"
					die("Undetected Exception \"{$exceptionMessage}\" in line # ".__LINE__ . " of file ". __FILE__);
				}
			}
	}//public function facebookLoginRedirect()
	public function googleLoginRedirect()
	{
		if(!$this->doesDependencyClassExist("\Google\Client"))
		{
			die("This Feature Not Implemented. ".basename(__FILE__)." line # ". __LINE__);
			return false;
		}
		//echo "Initializing<br />";
		$googleLoginHelper = $this->getGoogleLoginHelper()->initialize();
		//echo "Initialized<br />";
		//echo "done processGoogleResponseAfterSignin<br />";
			try{
				
				$googleLoginHelper->processGoogleResponseAfterSignin();
			}
			catch(\Exception $e)
			{
				$exceptionMessage = $e->getMessage();
				if($exceptionMessage == "AUTO_REGISRATION_DISABLED")
				{
					$this->redirect2Page("Account/errorPage?errorMsg=AUTO_REGISRATION_DISABLED");
				}
				else
				{
					die("Undetected Exception in line # ".__LINE__ . " of file ". __FILE__);
				}
			}
	}//public function googleLoginRedirect() 
	public function verifyGoogleIdToken()// called from android login
	{
		/* $verifyResult = "{\"status\":\"success\",\"short_message\":\"succefully_logged_in\",\"redirect_url\":\"Account/profile\"}";
		die($verifyResult); */
		$testIdToken =  "eyJhbGciOiJSUzI1NiIsImtpZCI6ImU4NzMyZGIwNjI4NzUxNTU1NjIxM2I4MGFjYmNmZDA4Y2ZiMzAyYTkiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxMDEzMzA2Mzc4NDMzLWx2aWJybXRtYmwzc2VraTVrcWE2NGoyb3FybGdubTBkLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTAxMzMwNjM3ODQzMy0yZW12ZmRvNWN2cTVrY2dxYXEyc2ZoZXQ4YzViMmpxcC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMDY1NzQ4NDM3NTE3MzYxNjc0MyIsImVtYWlsIjoib3V0c291cmNlb2xAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsIm5hbWUiOiJPdXRzb3VyY2UgT25saW5lIiwicGljdHVyZSI6Imh0dHBzOi8vbGg2Lmdvb2dsZXVzZXJjb250ZW50LmNvbS8tNjdCNTktTW1zSTQvQUFBQUFBQUFBQUkvQUFBQUFBQUFBQUEvQU1adXVjbWJITjVOTzQ5dnJ5WERpUnc1Ql9NWElhNlZSUS9zOTYtYy9waG90by5qcGciLCJnaXZlbl9uYW1lIjoiT3V0c291cmNlIiwiZmFtaWx5X25hbWUiOiJPbmxpbmUiLCJsb2NhbGUiOiJlbiIsImlhdCI6MTYxNDkyNjI0MSwiZXhwIjoxNjE0OTI5ODQxfQ.STZg3f0c4T0VanPMqlc1V4V5TTWSx9fsh1bhXPghgI-BoaGpQbMVphL5_8TxJxQi6UuZGKhN8WT--YyfMzIB0MFA7aadOXv0gUQVQUgjS7VkardOWklb00urjeqPp2xALE9Iyqpxz0TBX8ftEW3As5vHZ8TK8YRG2K5LWtRXjy6__wkruFYJAziMKFQf4rWJIYELsSsPevZSpZRVy1CIBJux84Hl1_DCxyR-4SNiLTLN0SuCYuofuh9Ose_iAsy5-ZLpUQErwEyPQFUwCEV5bDwsAj5BZKj07rLpk2VMpW2Y1rYM7mjAYKNvqeh2iAUMouSrC68vSpj9nBG4gtUUHQ";
		$json_received = (file_get_contents("php://input")); 
		if($json_received == "")
		{
			$idToken = $testIdToken;
		}
		else //if($jsonString == "")
		{
			$json_obj = json_decode($json_received);
			$idToken = $json_obj->googleIdToken;
			//echo "id TokenReceived from html page  is <br />\r\n".$idToken."<br />\r\n";
		}////if($jsonString == "")
		header("Content-type:application/json");
		$googleLoginHelper = $this->getGoogleLoginHelper()->initialize();
		//echo "Initialized<br />";
		$verifyResult = $googleLoginHelper->verifyIdToken($idToken);
		//die("{\"status\":\"error\",\"short_message\":\"not_logged_in\",\"login_url\":\"".$loginURL."\"}");//,\"message\":\"Successfully Added\"
		$this->sendJSONHeader();
		echo $verifyResult;
	}//public function verifyGoogleIdToken()
	public function profile()
	{
		
		$isLoggedIn =  $this->getSessionHandlerHelper()
							->isLoggedIn();
		if($isLoggedIn)
		{
			
			$myView = $this->getView();
			$myView->setPageTitle("PROFILE");
			$myView->getView()
						->profile();
		}
		else
		{
			$this->redirect2Page("Account");
		}//if($isLoggedIn)
		$adminPage = false;
		//$this->redirectIfNotLoggedIn($adminPage);
		/* $this->getView()
					->profile(); */
		//parent::render("profile");
	}//public function profile()
	public function logout()
	{
		//unset($_SESSION['user_id']);
		$isLoggedIn =  $this->getSessionHandlerHelper()
							->isLoggedIn();
		if($isLoggedIn)
		{
			//$this->getCoreHelper("GoogleLogin")->logout();
			$this->getCoreHelper("Account")->logout();
		}//if($isLoggedIn)
		$this->redirect2Page("Account");
	}//public function logout()
	public function deleteSelf()
	{
		$userDetails = $this->getCoreHelper("Account")
								->getUserDetailsWithSessionVar();
		$user_id = $userDetails['id'];//$this->getCoreHelper("RequestVar")->getSessionVar("user_id");
		$userEmail = $userDetails['email'];
		$this->getCoreHelper("Account")->deleteUser($user_id);
		$this->redirect2Page("Account/errorPage?errorMsg=User {$userEmail} deleted");
	}//public function deleteSelf()
	

    
}//class ContactController

?>