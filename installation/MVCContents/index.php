<?php
/**
@mainpage OSOS MVC Instance
This is an instance of OSOS MVC generated via [OSOL MVC Maker](https://hariyom.bitbucket.io/MVCMaker/html/md_readme.html)

@author Sreekanth Dayanand
@date 11th February 2023

@par Requirements

1. PHP version 5.1.0+, ideal is 7+,
2. Safe mode must be turned off
3. PHP GD Library should be enabled
   

@note To be fully functional common utility packages should be added inside private/Core/composer via composer

```
composer require osolutils/helpers 
```
The above command installs classes [here](https://github.com/osolgithub/OSOLHelpers/tree/main/src)
1. OSOLMySQL.php
2. OSOLPageNav.php
3. OSOLmulticaptcha.php

Additionally [composer.json](https://github.com/osolgithub/OSOLHelpers/blob/main/composer.json) requires
1. chamilo/pclzip		
2. phpmailer/phpmailer	
3. google/apiclient

@copyright {This project is released under the GNU Public License.}

@par Git Info

The following needs to be added

1. GIT of this instance
2. Documentation of this Instance
3. Documentation Repo of this instance
*/
/**
* @file index.php
* @brief Starting point of this this OSOLMVC Instance. 
* @details Starting point of the project.\n
* This file includes the file <b>private/Core/bootstrap.php</b> which in turn bootstraps the operations of this project\n
* This documentation is shown because *file* tag is used.\n
* This will appear under  Main Project >> Files >> File List >> thisFileName \n
* @par Operations done:
1. includes bootstap.php \ref bootstrap_operations[See operations in bootstrap]
2. executes the following functions
	1. call_user_func(@n
				array($controllerInstance, "beforeRenderPage"),@n
				$renderMethod@n
			);
	2. call_user_func(@n
			array($controllerInstance, $renderMethod)@n
		);
	3. call_user_func(@n
				array($controllerInstance, "afterRenderPage"),@n
				$renderMethod@n
			);	
* @warning without *file* tag, non class files are not documented\n
* Also no global variables will be documented
*
*/
/*! 
 *  \brief constant holding root file path of this OSOLMVC Instance.
 * @details this constant is defined for including /Core/bootstrap.php`
 At any time porting is required, this variable should be manually edited
 */
define('OSOLMVC_HOME_FOLDER_PATH',__DIR__);
define('OSOLMVC_SESSION_VAR_PREPEND','OSOL_MVC_');
define('OSOLMVC_COOKIE_DISAGREE_PAGE','Account/allowCookie');
/**
*  @brief sets the relative path to `private` folder.
*  @details
   This is set by [OSOL MVC Maker](https://hariyom.bitbucket.io/MVCMaker/html/md_readme.html) during installation.@n
   This is used to include <b>/Core/bootstrap.php</b>
*/
$OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH = '__PRIVATE_FOLDER_ROOT__';

require_once $OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH.'/Core/bootstrap.php';
// this is required, otherwise , when called inside constructor of \Core\Helper\RouteHelper, that instance is discarded
//$clsSiteConfig = \Core\Config\ClassSiteConfig::getInstance();


/* 
$routerHelper = \OsolMVC\Core\Helper\RouteHelper::getInstance();
$routerHelper->initiate();// for setting up \Core\Config\ClassSiteConfig

//echo "\$_REQUEST is <pre>".print_r($_REQUEST,true)."</pre>";
$controllerAndMethod2Route = $routerHelper->getControllerAndMethod2Route();
$controllerInstance = $controllerAndMethod2Route->controller;
$renderMethod = $controllerAndMethod2Route->method;//"render";

 */
//echo "post in index<pre>".print_r($_POST,true)."</pre>";


//$dc->$renderMethod();//TestController/TestMethod?testVar=123
//echo get_class($dc)."<br />"; 
//echo "<pre>".print_r($controllerAndMethod2Route, true)."<br />".$defaultController."</pre>";

if($renderMethod != "render404")
{
	call_user_func(
			array($controllerInstance, "beforeRenderPage"),
			$renderMethod
		);

}//if($renderMethod != "render404")
/* die( "line :". __LINE__. " in " . __FILE__."<br />".
				" renderMethod {$renderMethod}"); */
try{
	call_user_func(
        array($controllerInstance, $renderMethod)
    );
}
catch(\Exception $e) {
  //echo 'Message: ' .$e->getMessage();
	$exceptionMessage = $e->getMessage();
	if($exceptionMessage == "ALERT_NOT_AUTHORISED")
	{
		$controllerInstance->redirect2Page("General/notAuthorized");
	}
	else
	{
		die("Undetected Exception {$exceptionMessage} in line # ".__LINE__ . " of file ". __FILE__);
	}
}				


if($renderMethod != "render404")
{
	call_user_func(
			array($controllerInstance, "afterRenderPage"),
			$renderMethod
		);

}//if($renderMethod != "render404")
	
?>