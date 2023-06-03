## Login and registration With Google via Browser 
1. User is presented with google login link Account/login (AccountController::login())
2. User clicks the link and goes to google login
3. access token is verified in OsolMVC::Core::Helper::GoogleLoginHelper::processGoogleResponseAfterSignin()
4. GoogleLoginHelper::processGoogleResponseAfterSignin()in turn calls GoogleLoginHelper::checkUserLoginStatus($userDetailsFromGoogle) 
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
and goes to google login;
:user comes back from google site 
to 
**AccountController::googleLoginRedirect()**;
:access token is checked in 
**GoogleLoginHelper::processGoogleResponseAfterSignin()**;
if (access token verification success?) then (no)
	#pink:show message that login failed;
	stop
endif
:Get **user profile data from google**
 $data = $google_service->userinfo->get();
:sets $userDetails array from $data in previous step;
:Calls checkUserLoginStatus($userDetails);
#palegreen:if (User with given email Exists?) then (yes)
  :return user record;
else (no)
  :Insert new record and
  return user record;
endif
:sets session vars for 'user_id'& userRecord;
:calls **SessionHandlerHelper::updateUserIdForSession($userId)**;
:redirects to **AccountController::profile()**;
end
@enduml
```

### Diagram with [PUML SERVER](http://www.plantuml.com/plantuml/uml/SyfFKj2rKt3CoKnELR1Io4ZDoSa70000)

![Alt text](file://googleLoginWebActivityDiagram.png "Text on mouseover")