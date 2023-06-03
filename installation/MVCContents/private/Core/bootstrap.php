<?php
/**
* @file private/Core/bootstrap.php
*
* @brief Bootsraps operations for this OSOLMVC Instance. 
* @details 
\anchor bootstrap_operations
Does the following operations
1. Declare file paths
2. Declare url paths
3. Sets AUTOLOADER OsolMVC::Psr4AutoloaderClass
5. sets request variables,<b>controller and render method</b> based on URL, using OsolMVC::Core::Helper::RouteHelper::getControllerAndMethod2Route()
6. includes '/composer/vendor/autoload.php' (if it exists)

* @note Besides the above main operations, the following are also done.
1. checks if the instance is called via CLI(Command Line Interface) and provides space to execute CLI operations
2. deletes cookies when the session($_COOKIE['PHPSESSID']) is duplicate for the newly logged in user(very small probability , but an important one though)@n
when $_GET['redirect_to'] is `destroyDuplicateSession`, using OsolMVC::Core::Helper::SessionHandlerHelper
3. starts session with sessionHelper->initialize()

* @warning without *file* tag, non class files are not documented\n
* Also no global variables will be documented
*
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*! 
 *  \brief constant holding root file path of "private/Core" this OSOLMVC Instance.
 * @details  At any time porting is required, this variable should be manually edited
 */
define('OSOLMVC_APP_ROOT', dirname(__FILE__));

/*! 
 *  \brief constant holding root URL of this OSOLMVC Instance.
 * @details  This is set by installer during installation@n
 * At any time porting is required, this variable should be manually edited
 */
define('OSOLMVC_URL_ROOT', '__PROJECT_ROOT_URI__');
/*! 
 *  \brief constant holding root URL of 'private` folder of this OSOLMVC Instance.
 * @details  The need of this constant is that,@n
 if we want to move 'private' folder to a more secure location fearing hacking,@n
 we just need to change this value.@n
 * At any time porting is required, this variable should be manually edited
 */
define('OSOLMVC_PRIVATE_FOLDER_ABSOLUTE', realpath(__DIR__.'/../'));
/*! 
 *  \brief constant holding root URL of 'private/Addons` folder of this OSOLMVC Instance.
 * @details  The need of this constant is that,@n
 if we want to move 'private' folder to a more secure location fearing hacking,@n
 we just need to change this value.@n
 * At any time porting is required, this variable should be manually edited
 */
define('OSOLMVC_ADDON_FOLDER_ABSOLUTE', OSOLMVC_PRIVATE_FOLDER_ABSOLUTE.'/Addons');
/*! 
 *  \brief constant holding relative path of 'private' folder of this OSOLMVC Instance.
 * @details  This is set by installer during installation@n
 * At any time porting is required, this variable should be manually edited
 */
define('OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH', '__PRIVATE_FOLDER_ROOT__');
//require_once(__DIR__.'/autoload_register.php');
require_once(__DIR__.'/Psr4AutoloaderClass.php');
// instantiate the loader
/**
*  @brief initiates auto loader.
*  @details
   Path to two root name spaces are added.@n
   1. 'OsolMVC\Core'
   2. 'OsolMVC\Addons'
*/
$loader = new \OsolMVC\Psr4AutoloaderClass;

// register the autoloader
$loader->register();

// register the base directories for the namespace prefix
/* $loader->addNamespace('Core\Controller', __DIR__.'/controller');
$loader->addNamespace('Core\View', __DIR__.'/view'); */
$loader->addNamespace('OsolMVC\Core', __DIR__);
//echo "Addons path is ".realpath(__DIR__."/../Addons")."<br />";
$loader->addNamespace('OsolMVC\Addons', realpath(__DIR__."/../Addons"));
//$loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');

//---------------------------------------------------------------------------------------------------------------------------


/* SET $_GET, $_REQUEST AND GET CONTROLLER AND METHOD2ROUTE BASED ON URL STARTS HERE */
/**
*  @brief `RouteHelper` sets request variables,controller and render method based on URL
*  @details
	Sets the folloring based on url
    1. $_GET
    2. $_REQUEST 
	3. CONTROLLER
	4. METHOD2ROUTE
*/
$routerHelper = \OsolMVC\Core\Helper\RouteHelper::getInstance();
$routerHelper->initiate();// for setting up \Core\Config\ClassSiteConfig and also 'currentRedirectTo' based on url

//echo "\$_REQUEST is <pre>".print_r($_REQUEST,true)."</pre>";
$controllerAndMethod2Route = $routerHelper->getControllerAndMethod2Route();
$controllerName = $controllerAndMethod2Route->controllerName;
$controllerInstance = $controllerAndMethod2Route->controller;
$renderMethod = $controllerAndMethod2Route->method;//"render";
define("OSOL_MVC_CURRENT_CONTROLLER",$controllerName);
define("OSOL_MVC_CURRENT_RENDER_METHOD",$renderMethod);
/**  SET $_GET, $_REQUEST AND GET CONTROLLER AND METHOD2ROUTE BASED ON URL ENDS HERE **/

//---------------------------------------------------------------------------------------------------------------------------




$COMPOSER_AUTOLOAD = __DIR__.'/composer/vendor/autoload.php';
if(file_exists($COMPOSER_AUTOLOAD))require_once $COMPOSER_AUTOLOAD;


//---------------------------------------------------------------------------------------------------------------------------


/* CHECK IF CLI STARTS HERE */
if($controllerInstance->isCLI())
{
    // Do something
}
else /* CHECK IF CLI ENDS HERE */
{
    


    /* CHECK FOR DUPLICATION SESSION STARTS HERE**/

    $sessionHelper =  $controllerInstance->getSessionHandlerHelper();//\OsolMVC\Core\Helper\SessionHandlerHelper


    $requestVarHelper =  $controllerInstance->getRequestVarHelper();//\OsolMVC\Core\Helper\RequestVarHelper
    $redirect_to = $requestVarHelper->getGetVar('redirect_to');
    //die("redirect_to is {$redirect_to}");
    if($redirect_to != '')//destroySession=true
    {
        //$dbDetails = \upkar\php\ClassSiteConfig::getInstance()->getDBSettings();
        switch($redirect_to)
        {
            case "deleteAllCookiesAndSesstions": // for test purposes
				//http://localhost/pjt/upkar/upkar_site/public_html/deleteAllCookiesAndSesstions
                //\upkar\php\helpers\ClassDBSessionHandler::deleteAllCookiesAndSesstions($dbDetails);
                $sessionHelper->deleteAllCookiesAndSesstions();
                exit; 
                break;
        
            case "destroyDuplicateSession": //http://localhost/pjt/upkar/upkar_site/public_html/destroySession
                //\upkar\php\helpers\ClassDBSessionHandler::destroyDuplicateSession($dbDetails);
                $sessionHelper->destroyDuplicateSession();
                exit; 
                break;
            default:
                
        }//switch($_GET['redirect_to'])
        
        
    }//if(isset($_GET['redirect_to']))
    /** CHECK FOR DUPLICATION SESSION ENDS HERE**/

    //---------------------------------------------------------------------------------------------------------------------------

    /** START SESSION **/
    $sessionHelper->initialize();
}//if($controllerInstance->isCLI())

      
?>