<?php
namespace OsolMVC\Core\Helper; 
class FacebookLoginHelper extends \OsolMVC\Core\CoreParent{
	private $database = null;
    private $table_prefix = null;
    private $currentUserRecord;
    private $facebookSDKMainClassInst = null;
    private $facebookSDKHelper = null;
    private $facebookClient = null;
    private $facebookAppSettings;
	private $errorJSON= "";
	
	private $accountHelper = null;
    
    public function initialize()
    {
		$siteConfig = $this->getSiteConfig();
        if($this->database == null)
        {
            $dbSettings = $siteConfig->getDBSettings();
            $this->database = $this->getDB();
            $this->table_prefix = $dbSettings['table_prefix'];
			$this->facebookAppSettings = $siteConfig->getFacebookAppSettings();
			$this->accountHelper = $this->getCoreHelper("Account");
			$this->setFacebookSDKClassInstances();
        }
        return $this;
    }//private function initialize()
	public function setFacebookSDKClassInstances()
    {

        if(is_null($this->facebookSDKMainClassInst))
		{
			$this->facebookSDKMainClassInst = new \Facebook\Facebook([
									 'app_id' => $this->facebookAppSettings['app_id'],
									 'app_secret' => $this->facebookAppSettings['app_secret'],
									 'default_graph_version' => 'v2.5',
									]);	
			$this->facebookSDKHelper = $this->facebookSDKMainClassInst->getRedirectLoginHelper();						
		}//if(is_null($this->googleClient))




        

    }//private function setFacebookSDKClassInstances()
	public function getFacebookSDKMainClass()
	{
		return $this->facebookSDKMainClassInst;
	}//public function getFacebookSDKMainClass()
	public function getFacebookSDKHelper()
	{
		return $this->facebookSDKHelper;
	}//public function getFacebookSDKHelper()
    public  function getFacebookOAuthURL()
	{
		$permissions = ["email"];
		$OAuthUrl = $this->facebookSDKHelper->getLoginUrl($this->facebookAppSettings['OAuthRedirectURI'], $permissions);
		return $OAuthUrl;
		
	}//public  function getFacebookOAuthURL()
	
	public function processFacebookResponseAfterSignin()
	{

		$requestHelper = $this->getCoreHelper('RequestVar');
		$code =  $requestHelper->getGetVar('code');
		if ($code == "" /* is_null($code) */) {
			//after login the user will be redirected to url something like https://modestoffers.com/DEMOsites/OSOLMVC/Account/facebookLoginRedirect?code=AQA39eLAOWv0fDRyxY_KclcBmNnGSmHz2aXJy1UwcZZr3SMU8BbsBzlgGgBPZUPId9aBPesdCdwl2nOILLT_7CrbuEIrqngo86W_PzFoYB1ryVvIYAldctHujb8Szx3GBTEJECQ84XtpoIPXVXJMWIMO_4mFTh5kRnMyf4t-CwGgBvy07TcMpXuQr7wlpAAcwAYdQU9mt-d_abFuSIvypV5zlr0XxYkKBJUeVmrv1udYVvjLzaRwq4xKrLuuepN0jAnVCNvaoW1aXyn1F82_Hju4aFgm-hN49JEWCzWjQpzqcXr5F0Vi7oNRKp_Y_p-YCkNgKj4sRUwUy9zAQTtFeh76qgA9wfka3uQy7QWk1UWcslX-8RX88ReCZW0oHlmI5AmSpdBHO8Mt9aMvx1EM9cUO84ypFizGtLieJYUdYUbLEg&state=72798b56e51f0a59f297d7b05c4cffed#_=_
			die("Some issue with facebook login. Undetected issue in line # ".__LINE__ . " of file ". __FILE__);
		}
		$facebook_access_token = $requestHelper->getSessionVar('facebook_access_token');
		try {
				if (!is_null($facebook_access_token)) {
					$accessToken = $facebook_access_token;
				} 
				else 
				{
				  $accessToken = $this->facebookSDKHelper->getAccessToken();
				}
		} catch(Facebook\Exceptions\facebookResponseException $e) {
				// When Graph returns an error
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
		}
		if (isset($accessToken)) {
			if (!is_null($facebook_access_token)) {
				$this->facebookSDKMainClassInst->setDefaultAccessToken($facebook_access_token);
			} 
			else 
			{
				// getting short-lived access token
				$requestHelper->setSessionVar('facebook_access_token',(string) $accessToken);
				  // OAuth 2.0 client handler
				$oAuth2Client = $this->facebookSDKMainClassInst->getOAuth2Client();
				// Exchanges a short-lived access token for a long-lived one
				$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($facebook_access_token);
				$requestHelper->setSessionVar('facebook_access_token',(string) $longLivedAccessToken);
				// setting default access token to be used in script
				$this->facebookSDKMainClassInst->setDefaultAccessToken($longLivedAccessToken);
			}
			
			// getting basic info about user
			try {
				$profile_request = $this->facebookSDKMainClassInst->get('/me?fields=name,first_name,last_name,email');
				$requestPicture = $this->facebookSDKMainClassInst->get('/me/picture?redirect=false&height=200'); //getting user picture
				$picture = $requestPicture->getGraphUser();
				$profile = $profile_request->getGraphUser();
				$fbid = $profile->getProperty('id');           // To Get Facebook ID
				$fbfullname = $profile->getProperty('name');   // To Get Facebook full name
				$fbemail = $profile->getProperty('email');    //  To Get Facebook email
				$fbpic = "<img src='".$picture['url']."' class='img-rounded'/>";
				# save the user nformation in session variable
				
				/* $requestHelper->setSessionVar('fb_id') = $fbid;
				$requestHelper->setSessionVar('fb_name') = $fbfullname;
				$requestHelper->setSessionVar('fb_email') = $fbemail;
				$requestHelper->setSessionVar('fb_pic') = $fbpic; */
				//echo "Done setting SESSION Vars!! go to <a href=\"profile.php\">Profile page</a> or <a href=\"fbAlbums.php\">Albums page</a>";
				
				$userDetails = array(
                                         'first_name' => $profile->getProperty('first_name'),
                                         'last_name' => $profile->getProperty('last_name'),
                                         'email' => $profile->getProperty('email'),
                                         //'gender' => $data['gender'],
                                         'picture' => $picture['url'],


                                         'refresh_token' => ''// to be got below
                                         );
				$emailSharingDisbled = ($userDetails['email']==""?"email sharing was disabled. please login again allowing email sharing":"email is shared");
				//die($emailSharingDisbled . ", email is ". $userDetails['email']. " Line no : ". __LINE__. "  file : " . __FILE__);
				if($userDetails['email']=="")
				{					
					$this->getDefaultController()->redirect2Page("General/emailSharingDisbled");
					exit;
				}//if($userDetails['email']=="")
				$this->checkUserLoginStatus($userDetails);
                /* $clsSiteConfig = \upkar\php\ClassSiteConfig::getInstance();
                $clsSiteConfig->redirect2Page("profile"); */
                $this->getDefaultController()->redirect2Page("Account/profile");
			} 
			catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				echo 'Graph returned an error: ' . $e->getMessage();
				session_destroy();
				// redirecting user back to app login page
				header("Location: ./");
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			// redirect the user to the profile page if it has "code" GET variable
			/* $code =  $requestHelper->getGetVar('code');
			if (!is_null($code)) {
				//die('Location: profile.php');
				header('Location: profile.php');
			} */
		} 
		else //if (isset($accessToken))
		{
			die("Facebook Login failed!!!<br />
				Undetected issue in line # ".__LINE__ . " of file ". __FILE__);
		}
		
	}//public function processFacebookResponseAfterSignin()
	public function checkUserLoginStatus($userDetails)
	{
		//Add user(if new)(users table) and session(user_sessions table) to db 
        // get user record
        
		/* $userDetails = array(
                'first_name' => $payload['given_name'],
                'last_name' => $payload['family_name'],
                'email' => $payload['email'],
                //'gender' => $data['gender'],
                'picture' => $payload['picture'],
                'refresh_token' => '',
                //'android_id_token' => ''
                ); */
		//$userRecord = $this->getUserRecord($userDetails);
		$userNotRegistered = false;
		try{
			$userRecord = $this->accountHelper->getUserRecordWithEmail($userDetails['email']);
		}
		catch(\Exception $e) {
		  //echo 'Message: ' .$e->getMessage();
			$exceptionMessage = $e->getMessage();
			if($exceptionMessage == "USER_EMAIL_NOT_REGISTERED")
			{
				$userNotRegistered = true;
			}
			else
			{
				die("Undetected Exception in line # ".__LINE__ . " of file ". __FILE__);
			}
		}
		if($userNotRegistered)
		{
			$userRecord = $this->accountHelper->addNewGoogleUser($userDetails);
			
		}//if($userNotRegistered)
        //$_SESSION['user_id'] = $userRecord['id'];
		$this->accountHelper->setUserSessionsOnLogin($userRecord);
        //insert session(user_sessions table) to db            
        //$this->insert2UserSessionTable($userRecord);
        //\upkar\php\helpers\ClassDBSessionHandler::getInstance()->updateUserIdForSession($userRecord['id']);
        $this->getSessionHandlerHelper()->updateUserIdForSession($userRecord['id']);
        $userDetails = $userRecord;
	}
	
}//class FacebookLoginHelper extends \OsolMVC\Core\CoreParent
?>	