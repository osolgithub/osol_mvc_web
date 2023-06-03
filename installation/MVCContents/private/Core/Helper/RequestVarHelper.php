<?php
namespace OsolMVC\Core\Helper;
class RequestVarHelper extends \OsolMVC\Core\CoreParent{
	
    private $json_received = null;
	protected function __construct()
	{
		
		/* echo "<pre>".print_r($_GET,true)."</pre>";
		echo "<pre>".print_r($_SERVER,true)."</pre>"; */ 
	}
	public  function initiate(){
		
		
	}//public  function initiate(){
	public function __call($fname, $args) {
		$setGet =  array("set","get");
		$requestTypes = array("Session");
		
		if(method_exists($this,$fname))
		{
			call_user_func_array(array($this, $fname), $args);
		}
		else
		{
			throw new \Exception("Call to undefined function ".$fname);
		}
	}
    
	public function setGetVar($key, $val)
	{
		$_GET[$key] = $val;
	}//public function setRequestVar($key, $val)
	public function getGetVar($key)
	{
		$returnVal =  "";
		if(isset($_GET[$key]))
		{
			$returnVal =  $_GET[$key];
		}//if(isset($_SESSION[$key]))
		return  $returnVal;
	}//public getRequestVar($key)
	
	
	public function setPostVar($key, $val)
	{
		$_POST[$key] = $val;
	}//public function setRequestVar($key, $val)
	public function getPostVar($key)
	{
		$returnVal =  "";
		if(isset($_POST[$key]))
		{
			$returnVal =  $_POST[$key];
		}//if(isset($_SESSION[$key]))
		return  $returnVal;
	}//public getRequestVar($key)
	
	public function setCookieVar($key, $val)
	{
		$_COOKIE[$key] = $val;
	}//public function setRequestVar($key, $val)
	public function getCookieVar($key)
	{
		$returnVal =  "";
		if(isset($_COOKIE[$key]))
		{
			$returnVal =  $_COOKIE[$key];
		}//if(isset($_SESSION[$key]))
		return  $returnVal;
	}//public getRequestVar($key)
	
	
	public function setSessionVar($key, $val)
	{
		$_SESSION[OSOLMVC_SESSION_VAR_PREPEND . $key] = $val;
	}//public function setSessionVar($key, $val)
	public function getSessionVar($key)
	{
		$returnVal =  null;
		if(isset($_SESSION[OSOLMVC_SESSION_VAR_PREPEND . $key]))
		{
			$returnVal =  $_SESSION[OSOLMVC_SESSION_VAR_PREPEND . $key];
		}//if(isset($_SESSION[$key]))
		return  $returnVal;
	}//public function getSessionVar($key)
	
	public function getRequestVar($key)
	{
		$returnVal =  "";
		if(isset($_REQUEST[$key]))
		{
			$returnVal =  $_REQUEST[$key];
		}//if(isset($_REQUEST[$key]))
		return  $returnVal;
	}//public function getRequestVar($key)
	
	public function getAllJsonVars()
	{
		if($this->json_received == null)
		{
			$this->json_received = (file_get_contents("php://input"));
		}//if($this->json_received == null)
		return $this->json_received;
	}//public function getAllJsonVars()
	public function getJsonVar($key)
	{
		$this->json_received = $this->getAllJsonVars();
		
		
		$returnVal =  "";
		$jsonDecoded = json_decode($this->json_received);
		if(isset($jsonDecoded->$key))
		{
			
			$returnVal =  $jsonDecoded->$key;
		}//if(isset($this->json_received[$key]))
		return  $returnVal;
	}//public function getRequestVar($key)
	
}//class RequestVarHelper

?>