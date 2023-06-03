<?php
/**
* \class OsolMVC::Core::Helper::GoogleLoginHelper

*  \brief Helps to handle Google Login.
* @author
* Name: Sreekanth Dayanand, www.outsource-online.net
* Email: joomla@outsource-online.net
* Url: http://www.outsource-online.net

@date 23rd June 2022
*  \details  
Works as an intermediary between OsolMVC\Core\Helper::SessionHandlerHelper and [Google API Client Classes](https://github.com/googleapis/google-api-php-client).\n
Mainly 
1. \Google\Client
2. \Google_Service_Oauth2

For setting up Google Login, visit this [tutorial](http://www.outsource-online.net/blog/2021/12/31/setting-up-google-login-for-website/)

\par Profile Scopes

see <https://stackoverflow.com/a/43253352> for how different types of data could be extracted.\n
Visit [scopes](https://developers.google.com/workspace/guides/configure-oauth-consent#profile-scopes), for how to extract data.\n
visit [People API](https://developers.google.com/people/) for more detail, eg: [gender](https://developers.google.com/people/api/rest/v1/people#gender), [birthday](https://developers.google.com/people/api/rest/v1/people#birthday) .\n
Visit [Getting people and profile information in Android App](https://developers.google.com/+/mobile/android/people)

\par Google Login Process
1. <b>Getting Google Login Button</b>
The following method in `OsolMVC\Core\View::AccountView` creates it\n.
```
	public function login()
	{
		
		$googleLoginHelper = $this->getGoogleLoginHelper();
		$googleLoginHelper->initialize();
		$googleClient4Login = $googleLoginHelper->getGoogleClientForLogin();		
		$googleLoginURL = $googleClient4Login->createAuthUrl();		
		$loginButtonURL = $this->getTemplateFileURL("images/sign-in-with-google.png");
		$this->variables4Template['login_button'] = '<a href="'.$googleLoginURL.'"><img src="'.$loginButtonURL.'" /></a>';
		$this->page2Show = "login.html";
		$this->showView();
	}
```	
2. <b>Redirect back to site after login</b>
Once the user clicks the button , he will be taken to google login page. and returned back to the site based on the `redirect url`.
For OSOL MVC it is `Account/googleLoginRedirect`
```
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
		$googleLoginHelper->processGoogleResponseAfterSignin();
		//echo "done processGoogleResponseAfterSignin<br />";

	}//public function googleLoginRedirect() 
```	
3. <b>Confirming Google Login</b>\n
$_GET["code"] variable is received after user has login into their Google Account and redirect to PHP script 
this is verified with 
```
$token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);
```
$token is an array, if it contains a key $token['error'], the login failed.\n
Otherwise, set access token with googleClient. This is required for getting user details
```
$this->googleClient->setAccessToken($token);
```
Create Object of Google Service OAuth 2 class
```
$google_service = new \Google_Service_Oauth2($this->googleClient);
```
Get user profile data from google
```
$data = $google_service->userinfo->get();
```

\par Access & Refresh tokens
The $token array mentioned above will have 2 keys
1. access_token : required to get user details
2. refresh_token : The refresh_token is only provided on the first authorization from the user. Subsequent authorizations, such as the kind you make while testing an OAuth2 integration, will not return the refresh_token again.\n
If you want to get it again, remove access to the app going to <https://myaccount.google.com/u/0/permissions>.

For more details check <https://stackoverflow.com/a/10857806>


* @copyright (C) 2012,2013 Sreekanth Dayanand, Outsource Online (www.outsource-online.net). All rights reserved.
* @license see http://www.gnu.org/licenses/gpl-2.0.html  GNU/GPL.
* You can use, redistribute this file and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation.
* If you use this software as a part of own sofware, you must leave copyright notices intact or add OSOLMulticaptcha copyright notices to own.
*
\par Common Errors in Oauth
	<b>1. Error 400: redirect_uri_mismatch</b>
	
	Redirect URL(s) must be set in https://console.developers.google.com/apis/credentials?authuser=1
	One of them should match with $this->googleAppSettings['redirectURL'], in ClassSiteConfig
	
 * <b>2. wamp Fatal error: Uncaught GuzzleHttp\Exception\RequestException: cURL error 60: SSL certificate problem: unable to get local issuer certificate</b>
 * https://stackoverflow.com/questions/35638497/curl-error-60-ssl-certificate-prblm-unable-to-get-local-issuer-certificate
 * Google shows you this question first.

    Download and extract for cacert.pem here (a clean file format/data)

        https://curl.haxx.se/docs/caextract.html

    Put it in :

        C:\xampp\php\extras\ssl\cacert.pem

    Add this line to your php.ini

        curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"

    restart your webserver/Apache
	

 */
namespace OsolMVC\Core\Helper; 
class GoogleLoginHelper extends \OsolMVC\Core\CoreParent{
    private $database = null;
    private $table_prefix = null;
    private $currentUserRecord;
    private $googleClient = null;
    private $googleAppSettings;
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
			$this->googleAppSettings = $siteConfig->getGoogleAppSettings();
			//echo "<pre>".print_r($this->googleAppSettings,true)."</pre>";
			//$this->accountHelper = \OsolMVC\Core\Helper\AccountHelper::getInstance();
			$this->accountHelper = $this->getCoreHelper("Account");
        }
        return $this;
    }//private function initialize()
    public function getGoogleClient()
    {

        if(is_null($this->googleClient))
		{
			#require_once __DIR__."/../../vendor/autoload.php";
			//Make object of Google API Client for call Google API
			$this->googleClient = new \Google\Client();
			$this->googleClient->setApplicationName($this->googleAppSettings['appName2Display']);// not working

			//Set the OAuth 2.0 Client ID
			$this->googleClient->setClientId($this->googleAppSettings['clientId']);

			//Set the OAuth 2.0 Client Secret key
			$this->googleClient->setClientSecret($this->googleAppSettings['clientSecret']);
			//$this->googleClient->setPrompt('select_account consent');
			$this->googleClient->setAccessType("offline");// needed to get refresh token https://stackoverflow.com/questions/10827920/not-receiving-google-oauth-refresh-token			
		}//if(is_null($this->googleClient))




        return $this->googleClient;

    }//private function getGoogleClient()
	public function getGoogleClientForLogin()
	{
		$this->getGoogleClient();
		
		$this->googleClient->setPrompt('select_account consent');
        $this->googleClient->setApprovalPrompt("consent");// to get refresh token
        $this->googleClient->setIncludeGrantedScopes(true);   // incremental auth


        //Set the OAuth 2.0 Redirect URI
        //$this->googleClient->setRedirectUri('http://localhost:3000/htmlSamples/jsTree/upkarTopicTree/loginSamples/google/php/phpLoginWithoutCommandline.php');
        $this->googleClient->setRedirectUri($this->googleAppSettings['redirectURL']);

        //
        $this->googleClient->addScope('email');

        $this->googleClient->addScope('profile');
		return $this->googleClient;
	}//public function prepareGoogleClientForLogin()
    
    public function getLoginURLOld()//showLoginForm
    {
        
        //start session on web page
        //session_start();// already called in bootstrap

        $login_button = '';                            

        //This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
        //if(!isset($_SESSION['access_token']))
        if(is_null(RequestVarHelper::getInstance()->getSessionVar('access_token')))
        {   //Create a URL to obtain user authorization
            
            //$this->googleClient = $this->getGoogleClient();
			$this->getGoogleClientForLogin();
            //$loginButtonURL = \upkar\php\ClassSiteConfig::getInstance()->getTemplateURLPath()."/images/sign-in-with-google.png";
            
            $login_button = '<a href="'.$this->googleClient->createAuthUrl().'"><img src="'.$loginButtonURL.'" /></a>';
        }
        else
        {
            
            $userId = RequestVarHelper::getInstance()->getSessionVar('user_id');//$_SESSION['user_id'];
            /* $userRecords  = $this->getUserDetailsWithId($userId);
            $userDetails = $userRecords[0]; */
			$userDetails = $this->accountHelper()->getUserRecordWithId($userId);
            $this->updateLastVisitedTime($userDetails['id']);
        }

        //require_once($templatePath);
    }//public function getLoginURL()
    
    
    public function processGoogleResponseAfterSignin()//$templatePath)
    {
        /*
		Sequence
		1. gets access_token with $_GET["code"];
		2. gets user info with access token
		3. setup data to insert in to db, in case it is a new used 
			fields are first_name, last_name, email, picture, refresh_token
		4. calls checkUserLoginStatus , which in  turn calls getUserRecord where
			1. user added if new
			2. session vars set.
			
		5. redirects to "Account/profile"
		*/
        
        //start session on web page
        //session_start();// already called in bootstrap
        //require_once __DIR__."/../../vendor/autoload.php";
        //Make object of Google API Client for call Google API
		
        /* $this->googleClient = new \this->googleClient();
        $this->googleClient->setApplicationName($this->googleAppSettings['appName2Display']);// not working

        //Set the OAuth 2.0 Client ID
        $this->googleClient->setClientId($this->googleAppSettings['clientId']);

        //Set the OAuth 2.0 Client Secret key
        $this->googleClient->setClientSecret($this->googleAppSettings['clientSecret']);
        $this->googleClient->setAccessType("offline");// needed to get refresh token  */https://stackoverflow.com/questions/10827920/not-receiving-google-oauth-refresh-token
		
		$this->getGoogleClient();
        //Set the OAuth 2.0 Redirect URI
        //$this->googleClient->setRedirectUri('http://localhost:3000/htmlSamples/jsTree/upkarTopicTree/loginSamples/google/php/phpLoginWithoutCommandline.php');
        $this->googleClient->setRedirectUri($this->googleAppSettings['redirectURL']);
        //This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
        //echo "<pre>".print_r($_GET,true)."</pre>";
        if(isset($_GET["code"]))
        {
            //echo "<pre>".print_r($_GET,true)."</pre>";
            //It will Attempt to exchange a code for an valid authentication token.
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);
            //echo "Token is <pre>".print_r($token,true)."</pre>";
            //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
            if(!isset($token['error']))
            {
                //$_SESSION['token'] = serialize($token);
				 RequestVarHelper::getInstance()->setSessionVar('token', serialize($token));
				
                //Set the access token used for requests
                //$this->googleClient->setAccessToken($token['access_token']);
                //the above code gives error Notice: Undefined index: expires_in in vendor\google\apiclient\src\Google\Client.php on line 470
                //so replaced it with below code
                //When calling $client->setAccessToken(), make sure you are passing the entire json string, not only the token part. The json string contains the expires_in timestamp which is evaluated in isAccessTokenExpired
                $this->googleClient->setAccessToken($token);//['access_token']);

                //Create Object of Google Service OAuth 2 class
                $google_service = new \Google_Service_Oauth2($this->googleClient);

                //Get user profile data from google
                $data = $google_service->userinfo->get();
                //(`first_name`,`last_name`,`email`,`gender`,`picture`,`date_joined`,`refresh_token`)
                $userDetails = array(
                                         'first_name' => $data['given_name'],
                                         'last_name' => $data['family_name'],
                                         'email' => $data['email'],
                                         //'gender' => $data['gender'],
                                         'picture' => $data['picture'],


                                         'refresh_token' => ''// to be got below
                                         );

                $emailSharingDisbled = ($userDetails['email']==""?"email sharing was disabled. please login again allowing email sharing":"email is shared");
				//die($emailSharingDisbled . ", email is ". $userDetails['email']. " Line no : ". __LINE__. "  file : " . __FILE__);
				if($userDetails['email']=="")
				{					
					$this->getDefaultController()->redirect2Page("General/emailSharingDisbled");
					exit;
				}//if($userDetails['email']=="")
				//Store "access_token" value in $_SESSION variable for future use.
                //$_SESSION['access_token'] = $token['access_token'];
				RequestVarHelper::getInstance()->setSessionVar('access_token', serialize($token['access_token']));
                if(isset($token["refresh_token" ]))
                {
                    //https://stackoverflow.com/questions/10827920/not-receiving-google-oauth-refresh-token
                    //$_SESSION["refresh_token" ] = $token["refresh_token" ]; 
					RequestVarHelper::getInstance()->setSessionVar('refresh_token', serialize($token['refresh_token']));
                    $userDetails['refresh_token'] = $token["refresh_token" ];
                }//if(isset($token["refresh_token" ]))
                $this->checkUserLoginStatus($userDetails);
                /* $clsSiteConfig = \upkar\php\ClassSiteConfig::getInstance();
                $clsSiteConfig->redirect2Page("profile"); */
                $this->getDefaultController()->redirect2Page("Account/profile");
            }//if(!isset($token['error']))
            else
            {
				echo "<h1>Login Failed </h1>";
                echo "<pre>".print_r($token,true)."</pre>";
                /* Array
                (
                    [error] => invalid_request
                    [error_description] => Could not determine client ID from request.

                    [error_description] => Bad Request

                    [error_description] => Missing parameter: redirect_uri


                ) */

            }//if(!isset($token['error']))
        }//if(isset($_GET["code"]))

                
    }//public function processGoogleResponseAfterSignin($templatePath)
	
    
    public function verifyIdToken($idToken)// called from android login
    {
        //https://stackoverflow.com/questions/52415960/how-can-i-get-this-google-login-id-token-from-this-android-app-to-verify-server
        //https://developers.google.com/identity/sign-in/web/backend-auth
		/*
		Sequence of processGoogleResponseAfterSignin (Web sign in)
		1. gets access_token with $_GET["code"];
		2. gets user info with access token
		3. setup data to insert in to db, in case it is a new used 
			fields are first_name, last_name, email, picture, refresh_token
		4. calls checkUserLoginStatus , which in  turn calls getUserRecord where
			1. user added if new
			2. session vars set.
			
		5. redirects to "Account/profile"
		*/
		
		/*
		Sequence of verifyIdToken
		1. $payload = $client->verifyIdToken($idToken);, payload contains user details 
		2. setup data to insert in to db, in case it is a new used 
			fields are first_name, last_name, email, picture, refresh_token
		4. calls checkUserLoginStatus , which in  turn calls getUserRecord where
			1. user added if new
			2. session vars set.
			
		5. redirects to "Account/profile"
		*/
        $verifyResult = "{\"status\":\"error\",\"short_message\":\"Invalid ID Token\"}";
		$client = $this->getGoogleClient();
        try {
            $payload = $client->verifyIdToken($idToken);
        } catch (\Exception $e){
            throw new BadRequestHttpException($e->getMessage());
        }
        if($payload){
            /*
            {
                // These six fields are included in all Google ID Tokens.
                "iss": "https://accounts.google.com",
                "sub": "110169484474386276334",
                "azp": "1008719970978-hb24n2dstb40o45d4feuo2ukqmcc6381.apps.googleusercontent.com",
                "aud": "1008719970978-hb24n2dstb40o45d4feuo2ukqmcc6381.apps.googleusercontent.com",
                "iat": "1433978353",
                "exp": "1433981953",

                // These seven fields are only included when the user has granted the "profile" and
                // "email" OAuth scopes to the application.
                "email": "testuser@gmail.com",
                "email_verified": "true",
                "name" : "Test User",
                "picture": "https://lh4.googleusercontent.com/-kYgzyAWpZzJ/ABCDEFGHI/AAAJKLMNOP/tIXL9Ir44LE/s99-c/photo.jpg",
                "given_name": "Test",
                "family_name": "User",
                "locale": "en"
            }

            REAL Result
            Array
            (
                [iss] => https://accounts.google.com
                [azp] => 1013306378433-lvibrmtmbl3seki5kqa64j2oqrlgnm0d.apps.googleusercontent.com
                [aud] => 1013306378433-2emvfdo5cvq5kcgqaq2sfhet8c5b2jqp.apps.googleusercontent.com
                [sub] => 110657484375173616743
                [email] => outsourceol@gmail.com
                [email_verified] => 1
                [name] => Outsource Online
                [picture] => https://lh6.googleusercontent.com/-67B59-MmsI4/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucmbHN5NO49vryXDiRw5B_MXIa6VRQ/s96-c/photo.jpg
                [given_name] => Outsource
                [family_name] => Online
                [locale] => en
                [iat] => 1613561238
                [exp] => 1613564838
            )
            */
            $userid = $payload['sub'];
            //echo "<pre>".print_r($payload,true)."</pre>";

			//first_name, last_name, email, picture, refresh_token
            $userDetails = array(
                'first_name' => $payload['given_name'],
                'last_name' => $payload['family_name'],
                'email' => $payload['email'],
                //'gender' => $data['gender'],
                'picture' => $payload['picture'],
                'refresh_token' => '',
                //'android_id_token' => ''
                );

                //Store "access_token" value in $_SESSION variable for future use.
                $_SESSION['android_id_token'] = $idToken;
               
                $this->checkUserLoginStatus($userDetails);
                //die("{\"status\":\"success\",\"short_message\":\"succefully_logged_in\",\"redirect_url\":\"viewSavedResources\"}");//,\"message\":\"Successfully Added\"
                $verifyResult = "{\"status\":\"success\",\"short_message\":\"succefully_logged_in\",\"redirect_url\":\"Account/profile\"}";
				//,\"errorJSON\":\"".$this->errorJSON."\"
				//,\"message\":\"Successfully Added\"
        } else {
            //throw new AccessDeniedHttpException("Invalid ID Token");
            //echo "Invalid ID Token";
            //die("{\"status\":\"error\",\"short_message\":\"Invalid ID Token\"}");//,\"message\":\"Successfully Added\"
            $verifyResult = "{\"status\":\"error\",\"short_message\":\"Invalid ID Token\"}";//,\"message\":\"Successfully Added\"
        }
		return $verifyResult;
    }//public function verifyIdToken($idToken)
	
	
	
    /*****************************METHODS TO BE MOVED TO AccountHelper***************************************************/
	/* public function getUserDetailsWithId($userId)
    {
	
        //$this->database = $this->initiateDB();
        $table_prefix = $this->database->getTablePrefix();
        $selectUserSQL = "select * from `{$table_prefix}user` where id = ?";
        $userRecords  = $this->database->selectPS($selectUserSQL,"i",$userId);
        return $userRecords ;
    }//public function getUserDetailsWithId($userId) */
	private function getUserRecordOld($data)
    {
		/*
		Sequence
		1. check if user is not registered with email.
			1. if not  insert user
			2. get user id
		2. if yes get user id. 
		3. sets session vars user_id and user_email
		3. return user details with user_id
		*/
        
                //echo "Data is <pre>".print_r($data,true)."</pre>";
                //$this->database = $this->initiateDB();
                //$this->database->connectdb();
                //search with email to see if user exists
                $table_prefix = $this->database->getTablePrefix();
                $selectUserSQL = "select * from `{$table_prefix}user` where email = ?";
                $userRecords  = $this->database->selectPS($selectUserSQL,"s",$data['email']);
                //die( "<pre>".print_r($userRecords,true)."</pre>");
                if(count($userRecords) == 0)
                {
                    //if user doesnt exist, insert record
                    /* 
                    Fields available from google
                    2.given_name : first_name
                    3.family_name:last_name
                    4.email, (unique, for replace)
                    5.gender,
                    6.picture
                    */
                    /* 

                    truncate `upkar_user`;
                    truncate `upkar_user_sessions`;
                    truncate `upkar_php_sessions`;
                     */
                    $insertUserSQL = "insert into `{$table_prefix}user` 
                                        (`first_name`,`last_name`,`email`,`picture`,`date_joined`,`refresh_token`)
                                        values (?,?,?,?,NOW(),?)";//`gender`, gender is to be retrieved from people api
                    /* $replacedSQL =  vsprintf(preg_replace("/\?/","'%s'",$insertUserSQL),
                                                array(
                                                    $data['given_name'],
                                                    $data['family_name'],
                                                    $data['email'],
                                                    $data['gender'],
                                                    $data['picture'],
                                                )                    
                                             ); */
                   //(`given_name`,`family_name`,`email`,`gender`,`picture`,`date_joined`,`refresh_token`)
                    $insertResult = $this->database->executePS($insertUserSQL,"sssss",
                                                $data['first_name'],
                                                $data['last_name'],
                                                $data['email'],
                                                /* $data['gender'], */
                                                $data['picture'],
                                                $data['refresh_token']
                                                );

                   /*  $replacedSQL = $this->database->getReplacedSQL($insertUserSQL,
                                                                    array(
                                                                        $data['given_name'],
                                                                        $data['family_name'],
                                                                        $data['email'],
                                                                        $data['gender'],
                                                                        $data['picture'],
                                                                        ) ); */
                    //die ($replacedSQL."<hr />"."<pre>".print_r($insertResult,true)."</pre>");//."<pre>".print_r($data,true)."</pre>");
					$userId = $this->database->lastInsertId();
					$this->errorJSON = str_repeat("*",50).
										$replacedSQL."\\n\\n".
										addslashes(print_r($insertResult,true))."\\n\\n".
										"userId is " . $userId."\\n\\n".
										str_repeat("*",50);//."<pre>".print_r($data,true)."</pre>"
                    
                    
                    
                    //$_SESSION['user_id'] = $userId;
                    //$_SESSION['user_email'] = $data['email'];
					
					//RequestVarHelper::getInstance()->setSessionVar('user_id', $userId);
					//RequestVarHelper::getInstance()->setSessionVar('user_email', $data['email']);
					
                    $selectUserSQL = "select * from `{$table_prefix}user` where id = ?";
                    $userRecords  = $this->database->selectPS($selectUserSQL,"i",$userId);
                    
                }
                else //if(count($userRecords) == 0)
                {
                    $userId = $userRecords[0]['id'];
                }//if(count($userRecords) == 0)
                $this->currentUserRecord = $userRecords[0];
                return $this->currentUserRecord;
    }//private function getUserRecord($data)
    public function getUserDetailsOld()//replaced with AccountHelper::getUserDetailsWithSessionVar();
	{
		  
            $requestVarHelper =  RequestVarHelper::getInstance();
			$loggedInUserIdSessionValue = $requestVarHelper->getSessionVar('user_id');
			$userId = $loggedInUserIdSessionValue;//$_SESSION['user_id'];
            /* $userRecords  = $this->getUserDetailsWithId($userId);
            $userDetails = $userRecords[0]; */
			$userDetails = $this->accountHelper()->getUserRecordWithId($userId);
            $this->updateLastVisitedTime($userDetails['id']);
			return $userDetails;
	}//public function userDetails()
    public function showUserProfileOld($templatePath)// not required it seems. if required, move to `AccountHelper` class
    {
		$requestVarHelper =  RequestVarHelper::getInstance();
		$loggedInUserIdSessionValue = $requestVarHelper->getSessionVar('user_id');
        $userId = $loggedInUserIdSessionValue;//$_SESSION['user_id'];
        /* $userRecords  = $this->getUserDetailsWithId($userId);
		$userDetails = $userRecords[0]; */
		$userDetails = $this->accountHelper()->getUserRecordWithId($userId);
        $this->updateLastVisitedTime($userDetails['id']);
        require_once($templatePath);
    }//public function showLoginForm($templatePath)
    private function updateLastVisitedTime($userId)
    {
        /* $stmt = "update `{$this->table_prefix}user`  set `last_visited`=NOW() where id = ?";
        $userRecords  = $this->database->executePS($stmt,"i",$userId); */
		$this->accountHelper->updateLastVisitedTime($userId);
    }//private function updateLastVisitedTime()
	/* public function logout()
    {
       unset($_SESSION['user_id']);
    }//public function logout() */
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
    //public function checkUserLoginStatus($userDetails)
    public function checkUserLoginStatusOld($userDetails)
    {
                            
        



        //Add user(if new)(users table) and session(user_sessions table) to db 
        // get user record
        $userRecord = $this->getUserRecord($userDetails);
        //$_SESSION['user_id'] = $userRecord['id'];
		RequestVarHelper::getInstance()->setSessionVar('user_id', $userRecord['id']);
        //insert session(user_sessions table) to db            
        //$this->insert2UserSessionTable($userRecord);
        //\upkar\php\helpers\ClassDBSessionHandler::getInstance()->updateUserIdForSession($userRecord['id']);
        $this->getSessionHandlerHelper()->updateUserIdForSession($userRecord['id']);
        $userDetails = $userRecord;
        //require_once($templatePath);
       
        //Below you can find Get profile data and store into $_SESSION variable
        /* if(!empty($data['given_name']))
        {
        $_SESSION['user_first_name'] = $data['given_name'];
        }

        if(!empty($data['family_name']))
        {
        $_SESSION['user_last_name'] = $data['family_name'];
        }

        if(!empty($data['email']))
        {
        $_SESSION['user_email_address'] = $data['email'];
        }

        if(!empty($data['gender']))
        {
        $_SESSION['user_gender'] = $data['gender'];
        }

        if(!empty($data['picture']))
        {
        $_SESSION['user_image'] = $data['picture'];
        } */
        //echo "<pre>".print_r($data,true)."</pre>";
        /*
        Google_Service_Oauth2_Userinfo Object
            (
                [internal_gapi_mappings:protected] => Array
                    (
                        [familyName] => family_name
                        [givenName] => given_name
                        [verifiedEmail] => verified_email
                    )

                [email] => capsuleasan@gmail.com
                [familyName] => Asan
                [gender] => 
                [givenName] => Capsule
                [hd] => 
                [id] => 113618073370784522515
                [link] => 
                [locale] => en
                [name] => Capsule Asan
                [picture] => https://lh5.googleusercontent.com/-1GlGhXIsFt4/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuclMy7lmfFb0V2M5Ir89-KaYf8H-9Q/s96-c/photo.jpg
                [verifiedEmail] => 1
                [modelData:protected] => Array
                    (
                        [verified_email] => 1
                        [given_name] => Capsule
                        [family_name] => Asan
                    )

                [processed:protected] => Array
                    (
                    )

            )
        */
            
    }//public function checkUserLoginStatus($userDetails)
    /*** METHODS TO BE MOVED TO AccountHelper ends here**/
	

}//class GoogleLoginHelper extends \OsolMVC\Core\CoreParent{
?>