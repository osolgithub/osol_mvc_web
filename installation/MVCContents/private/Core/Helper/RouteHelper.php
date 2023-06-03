<?php
namespace OsolMVC\Core\Helper;
class RouteHelper extends \OsolMVC\Core\CoreParent{
	private  $currentRedirectTo =  null;
	private  $clsSiteConfig =  null;
    
	protected function __construct()
	{
		
		/* echo "<pre>".print_r($_GET,true)."</pre>";
		echo "<pre>".print_r($_SERVER,true)."</pre>"; */ 
	}
	public  function initiate(){
		
		$this->clsSiteConfig = $this->getSiteConfig();//\OsolMVC\Core\Config\ClassSiteConfig::getInstance();
		/* echo "<pre>".print_r($_GET,true)."</pre>";
		echo "<pre>".print_r($_SERVER,true)."</pre>"; */
		if( !$this->isCLI()) $this->setCurrentRedirectTo();
	}//public  function initiate(){
	/**
     *  @brief sets $_GET and required $_SERVER variables
     * 
     *  @author Sreekanth Dayanand
     *  @date 14th Feb 2023
     *  @param [in] no input parameters
     *  @return void
     *  @details
		
        in .htaccess RewriteRule ^(.*)$ index.php?redirect_to=$1 [L,NC]@n
		for http://www.example.com/some-dir/yourpage.php?q=bogus&n=10,$_SERVER['REQUEST_URI'] will be /some-dir/yourpage.php?q=bogus&n=10@n
		Sets the following Variables based on the above example
        1. $_SERVER["SCRIPT_URI"] will be /some-dir/yourpage.php
		2. $_SERVER['QUERY_STRING'] will be q=bogus&n=10
		3. $_GET, $_REQUEST
		4. $this->currentRedirectTo will be some-dir/yourpage.php?q=bogus&n=10
		@par Called from
		$this->initiate() which in turn is called from bootstrap.php
     */
	private  function setCurrentRedirectTo(){
        $redirect_to = "";
/* 
        echo "\$_SERVER['REQUEST_URI'] is ".$_SERVER['REQUEST_URI']."<br />";        
        echo "\$_GET is <pre>".print_r($_GET,true)."</pre>";
         */
		//in .htaccess RewriteRule ^(.*)$ index.php?redirect_to=$1 [L,NC]
        if(isset($_GET['redirect_to']) && $_GET['redirect_to'] != "")
        {
            $redirect_to = $_GET['redirect_to'];
        }//if(isset($_GET['redirect_to']) && $_GET['redirect_to'] != "")


        
        
        $uri = $_SERVER['REQUEST_URI'];//for http://www.example.com/some-dir/yourpage.php?q=bogus&n=10 it will be /some-dir/yourpage.php?q=bogus&n=10
           
        $this->baseUrl = $this->clsSiteConfig->getSiteBase();// is OSOLMVC_URL_ROOT in bootstrap.php 
        $uri = str_replace($this->baseUrl, '', $uri);// if the OSOL MVC instance is in a sub folder, remove the sub folder
		//for http://www.example.com/some-dir/yourpage.php?q=bogus&n=10 SCRIPT_URI is set to  /some-dir/yourpage.php
        $_SERVER["SCRIPT_URI"] = preg_replace("/([^\?]+)(\?(.*))/","$1",$uri);
        if(strpos($uri,"?") !== false)
        {
			//echo "FFF<br />";
			//preg_match("/\?([^\?]+)/",$uri,$match);
			//echo "<pre>".print_r($match,true)."</pre>";
            $getVars = preg_replace("/([^\?]*)\?/","",$uri);
            //$getVars = str_replace('?', '', preg_replace("/([^\?]+)\?/","",$uri));
        }
        else
        {
            $getVars = "";
        }

        
        
        //echo " \$_SERVER['QUERY_STRING'] is ". $_SERVER['QUERY_STRING']."<br />";
        $_SERVER['QUERY_STRING'] = $getVars;//preg_replace("/([^\?]+)\?(.*)/","$1",$getVars);;
        //echo " \$_SERVER['QUERY_STRING'] is ". $_SERVER['QUERY_STRING']."<br />";
        parse_str($getVars, $_GET);// normal $_GET is lost due to htaccess,so Parse a query string into variables and store in $_GET
		$_GET = array_merge(array('redirect_to' => $redirect_to),$_GET);//
		$_REQUEST = array_merge($_REQUEST,$_GET,$_POST);//
        /* echo "Get Vars is ".print_r($getVars,true)."<br />"; 
        echo "\$_GET is <pre>".print_r($_GET,true)."</pre>"; */
		//echo "\$_GET is <pre>".print_r($_REQUEST,true)."</pre>";
        //exit;

        $this->currentRedirectTo = $redirect_to;//for http://www.example.com/some-dir/yourpage.php?q=bogus&n=10 it will be some-dir/yourpage.php?q=bogus&n=10
        //return $this->currentRedirectTo;
    }//private  function setCurrentRedirectTo(){
    public  function getCurrentRedirectTo(){
        return $this->currentRedirectTo;
    }//public  function getCurrentRedirectTo(){
	/**
     *  @brief returns controller and render method for current request
     *  @author Sreekanth Dayanand
     *  @date 14th Feb 2023
	 *
     *  @param [in] no input parameters
     *  @return stdClass with properties controller & method
     *  @details
        Uses $this->currentRedirectTo , which is set in  setCurrentRedirectTo via initiate from bootstrap.php@n
		Splits setCurrentRedirectTo with '/' as seperator to say "splitArray".@
		Does the following checks
		1. checks if call is for admin controller, is if splitArray[0] == admin@n
		in that case Controller is splitArray[1] and render method is  splitArray[2]
		2. else checks if call is for 'Core' Controller, Core controllers are set in OsolMVC::Core::Config::coreRoutes@n
		could be edited in "private\Core\Config\CoreRoutes.php"
		3. otherwise it is assumed that the controller required is of an "Addon" Controller
		could be edited in "private\Core\Config\AddonRoutes.php"
		
		in the 2nd and 3rd cases Controller is splitArray[0] and render method is  splitArray[1]
     */
	public function getControllerAndMethod2Route()
	{
		
		$defaultController = $contoller2Route = '\OsolMVC\Core\Controller\DefaultController';
		$renderMethod = "render";
		//set default controller and render method
		
		
		//echo "\$_SERVER['REQUEST_URI'] is ".$_SERVER['REQUEST_URI']."<br />";     
		/* if(isset($_GET['redirect_to']))
		{
			echo '$_GET[redirect_to] is '.$_GET['redirect_to']."<br />";
		}//if(isset($_GET['redirect_to']))  */  
		$formattedController = "";
		if($this->currentRedirectTo != "")// set in setCurrentRedirectTo
		{
			//echo 'currentRedirectTo is '.$this->currentRedirectTo."<br />";//TestController/TestMethod
			$splitRedirectTo = preg_split("@\/@",$this->currentRedirectTo);
			//echo "splitRedirectTo is <pre>".print_r($splitRedirectTo,true)."</pre>";
			$formattedSplitRedirectTo = $splitRedirectTo;
			//$formattedSplitRedirectTo = array_map(function($value){return ucwords(strtolower($value));},$splitRedirectTo);
			//echo "formattedSplitRedirectTo is <pre>".print_r($formattedSplitRedirectTo,true)."</pre>";
			$formattedController = $formattedSplitRedirectTo[0];//ucwords(strtolower($formattedSplitRedirectTo[0]));
			$renderMethod = (isset($formattedSplitRedirectTo[1]) && $formattedSplitRedirectTo[1] !="")?$formattedSplitRedirectTo[1]:"render";
			//if( isset(($formattedSplitRedirectTo[0])) && strtolower($formattedSplitRedirectTo[0]) == 'admin')//substr($formattedController,0,5)
			if($this->isAdminController($formattedSplitRedirectTo, "Core"))
			{
				$adminController = (isset($formattedSplitRedirectTo[1]) && $formattedSplitRedirectTo[1] !="") ?
									$formattedSplitRedirectTo[1]/* ucwords(strtolower($formattedSplitRedirectTo[1]) )*/ :
									"Default";//"DefaultAdmin";
				$formattedController = 'Admin'.'\\'.$adminController;
				$renderMethod = (isset($formattedSplitRedirectTo[2]) && $formattedSplitRedirectTo[2] !="")?$formattedSplitRedirectTo[2]:"render";
			}//if( $formattedController == 'Admin')//substr($formattedController,0,5)
			/* die( "This formattedController is {$formattedController}<br /> 
			This message is in ".__FILE__."<br /><br />"); */
			//echo " formattedController is $formattedController<br />";
			$isCore = $this->clsSiteConfig->isCoreController($formattedController);
			/* $namespacePrefix =  '\OsolMVC\\'.($isCore? 'Core':'Addons');
			$contoller2Route = $namespacePrefix.'\Controller\\' .$formattedController.'Controller'; */
			if($isCore)
			{
				$namespacePrefix =  '\OsolMVC\\Core';
				$contoller2Route = $namespacePrefix.'\Controller\\' .$formattedController.'Controller';
			}
			else //if($isCore)
			{
				$namespacePrefix =  '\OsolMVC\\Addons';
				$adminControllerPrefix = "";
				if($this->isAdminController($formattedSplitRedirectTo, "Addon"))
				{
					$adminControllerPrefix = '\Admin';
					$renderMethod = (isset($formattedSplitRedirectTo[2]) && $formattedSplitRedirectTo[2] !="")?$formattedSplitRedirectTo[2]:"render";
				}
				$contoller2Route = $namespacePrefix.'\\' . $formattedController . $adminControllerPrefix . '\Controller';
			}//if($isCore)
			//die("contoller2Route is {$contoller2Route}");
			
			
		}//if(isset($_GET['redirect_to']))
		$controllerAndMethod2Route = new \stdClass();
		$controllerAndMethod2Route->controller = $contoller2Route;//$controllerInstance;
		$controllerAndMethod2Route->method = $renderMethod;
		/* die( "This contoller2Route is {$contoller2Route}<br /> 
			This message is in ".__FILE__."<br /><br />"); */
		/* die( "line :". __LINE__. " in " . __FILE__.
				"<pre>".print_r($formattedSplitRedirectTo , true)."</pre>"); */
		/* die( "line :". __LINE__. " in " . __FILE__.
				"<pre>".print_r($controllerAndMethod2Route, true)."<br />".$defaultController."</pre>"); */
		/* die( "line :". __LINE__. " in " . __FILE__."<br />".
				"Class {$contoller2Route}, ". (class_exists($contoller2Route)?" exists" : "doesn't exist")); */
		$controllerInstance =  null;
		if (class_exists($contoller2Route)) {
			$controllerInstance = $contoller2Route::getInstance();
			//echo $contoller2Route. "<br />".get_class($controllerInstance)."<br />"; 
			/* die( "line :". __LINE__. " in " . __FILE__.
				"<pre>".print_r($controllerAndMethod2Route, true)."<br />".$defaultController."</pre>"); */
			if (!(method_exists($controllerInstance , $renderMethod)
			&& is_callable(array($controllerInstance , $renderMethod))))
			{
				
				$renderMethod = "render404";
			}
		}
		else
		{
			$controllerInstance = $defaultController::getInstance();
			$renderMethod = "render404";
		}
		
		$controllerAndMethod2Route->controllerName = $formattedController;
		$controllerAndMethod2Route->controller = $controllerInstance;
		$controllerAndMethod2Route->method = $renderMethod;
		
		return $controllerAndMethod2Route;
	}//private function getControllerAndMethod2Route()
	private function isAdminController($formattedSplitRedirectTo, $coreOrAddon = "Core")
	{
		$checkIndex = ($coreOrAddon == "Core")?0:1;
		 return (isset($formattedSplitRedirectTo[$checkIndex]) && (strtolower($formattedSplitRedirectTo[$checkIndex]) == 'admin'));
	}//private function isAdminController()
	
}//class RouteHelper

?>