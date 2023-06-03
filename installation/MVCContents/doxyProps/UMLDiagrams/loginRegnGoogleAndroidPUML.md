## Login and registration With Google via Android
1. User is presented with google login link Account/login (AccountController::login())
2. User clicks the link which calls android activity for google login
3. Upon login **Id Token** is extracted from google which is passed to server AccountController::verifyGoogleIdToken()
3. id token is verified in OsolMVC::Core::Helper::GoogleLoginHelper::verifyIdToken($idToken)
4. GoogleLoginHelper::verifyIdToken()in turn calls GoogleLoginHelper::checkUserLoginStatus($userDetailsFromGoogle) 
	1. it in turn calls GoogleLoginHelper::getUserRecord($userDetails)
		- getUserRecord checks it user Exists with given gmail.  if yes get userRecord, if not inserts new user record
	2. sets $_SESSION['user_id']
	3. calls SessionHandlerHelper::updateUserIdForSession($userId)
5. redirects to AccountController::profile()

```
@startuml
start
:User is presented with 
google login link 
Account/login 
(**AccountController::login()**);
:User clicks the link 
which calls **android activity for google login**
ie
**OSOLMVCAndroidJSInterface.osolmvcGoogleLogin()**
in android_login.html;
:Upon login **Id Token** is extracted from google which is passed 
to 
**AccountController::verifyGoogleIdToken()**
with call **verifyGoogleIdToken(googleIdToken)**
in android_login.html
which is called from 
**com.example.osolmvcandroid.mvc.view.components.webview.CustWebViewClient::onPageFinished()**;
:id token is verified in 
**GoogleLoginHelper::verifyIdToken($idToken)**;
if (id token verification success?) then (no)
	#pink:show message that login failed;
	stop
endif
:Get **user profile data from google**
 $payload = $client->verifyIdToken($idToken)
 (user details is part of $payload above);
:sets $userDetails array from $payload in previous step;
:Calls getUserRecord($userDetails);
#palegreen:if (User with given email Exists?) then (yes)
  :return user record;
else (no)
  :Insert new record and
  return user record;
endif
:sets $_SESSION['user_id'];
:calls **SessionHandlerHelper::updateUserIdForSession($userId)**;
end
@enduml
```

### Diagram with [PUML SERVER](http://www.plantuml.com/plantuml/uml/SyfFKj2rKt3CoKnELR1Io4ZDoSa70000)

![Alt text](file://googleLoginAndroidActivityDiagram.png "Text on mouseover")