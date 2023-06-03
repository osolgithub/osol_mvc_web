<?php
/**
* \class OsolMVC::Core::Helper::SessionHandlerHelper

*  \brief Handles session manangement with db
* @author
* Name: Sreekanth Dayanand, www.outsource-online.net
* Email: joomla@outsource-online.net
* Url: http://www.outsource-online.net

@date 23rd June 2022
*  \details  
 Session handling with DB is done with '[session_set_save_handler](https://www.php.net/manual/en/function.session-set-save-handler.php)' function.@n
 It also uses [register_shutdown_function](https://www.php.net/manual/en/function.register-shutdown-function.php).@n
 Should implement [SessionHandlerInterface](https://www.php.net/manual/en/class.sessionhandlerinterface.php)
 Uses `osol_mvc_php_sessions` table. \n
  this class is based on these tutorials [tut1](https://stackoverflow.com/questions/36753513/how-do-i-save-php-session-data-to-a-database-instead-of-in-the-file-system) and [tut2](https://culttt.com/2013/02/04/how-to-save-php-sessions-to-a-database/)@n
 
\par Gist
1. There should be a DB table. it should have atleast the 3 fields
 ```
 CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(32) NOT NULL,
    `access` int(10) unsigned DEFAULT NULL,
    `data` text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
id is $_COOKIE['PHPSESSID'], access is time of access,data 
<b>PS:</b> Any number of additional fields is allowed
for OSOLMVC it is
```
CREATE TABLE `osol_mvc_php_sessions` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `access` datetime NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `cookie_start_time` datetime NOT NULL,
  `device` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
ie, we added extra fields userid, cookie_start_time & device
2. 
```
session_set_save_handler(
            array($this, "_open"),  
            array($this, "_close"),  
            array($this, "_read"),  
            array($this, "_write"),  
            array($this, "_destroy"),  
            array($this, "_gc") 
        );
register_shutdown_function('session_write_close');
```
_open is to connect to db, close is to disconnect from db.\n
should <br>return boolean</b>, depending on wether able to open or close db
 if it was default handler, it would be open and close 'file'\n
 
_read is to read the session record in db\n
should <br>return 'data' field of the table</b> , which will be populated in '$_SESSION'\n

_write is to set session details\n
```
REPLACE INTO sessions VALUES (:id, :access, :data)
```
REPLACE works exactly like INSERT , except that if an old row in the table has the same value as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
<b>returns boolean</b>
_destroy : simply deletes a Session based on it’s Id.This method is called when you use the session destroy global function, like `session_destroy()`;\n
<br>return boolean</b>\n
 _gc(garbage collection) :The Garbage Collection function will be run by the server to clean up any expired Sessions that are lingering in the database. The Garbage Collection function is run depending on a couple of settings that you have on your server.
```
DELETE FROM {$this->table_prefix}php_sessions WHERE access < ?
```
<br>return boolean</b>\n
The Garbage collection is run based upon the `session.gc_probability` and `session.gc_divisor` settings on your server. Say for example the probability is set to 1000 and the divisor is 1. This would mean that for every page request, there would be a 0.01% chance the Garbage collection method would be run.\n

The method is passed a `max` variable. This relates to the maximum number of seconds before PHP recognises a Session has expired. Again this is a setting on your server that is open for you to edit.

Both of these settings can be found in your php.ini file.

 Detailed documentation on how uniqueness of session id is maintained is documented [here](http://www.outsource-online.net/blog/2021/05/05/php-session-handling-with-database-ensuring-unique-session-id-in-high-traffic-sites/)
 
\par How uniqueness of session is ensured
[refer](https://stackoverflow.com/a/67514141)
It uses a combination of session and a different cookie.

The algorithm in brief is like this

session handling will be done with custom class 'MySessionHandler' using DB

1. just prior to session_start, a cookie `cookie_start_time` is set to current time. life time of this cookie will be same as that of session. Both uses the variable $this->cookieLifeTime to set life time.

2. in session ‘_write’ we will set that value to db table field cookie_start_time same as $this->cookieStartTime

3. in session ‘_read’ we do a check

if($getRowsOfSession[0]['cookie_start_time'] != $this->cookieStartTime).

if it returns true, that means this is a duplicate session and the user is redirected to destroy the session (and also `cookie_start_time` cookie) and again redirected to start a new session.(2 redirections total)
\par instantiation 
this is a Singleton Class 

\OsolMVC\Core\Helper\SessionHandlerHelper::getInstance()

It is available with OsolMVC\Core::getSessionHandlerHelper()

in bootstrap.php it is called with $controllerInstance->getSessionHandlerHelper()

@note Duplicate session id detection

this is checked in `_read` method. if duplicate is detected the user is redirected to destroyDuplicateSessionURL == destroyDuplicateSession


* ===================================================
* @copyright (C) 2012,2013 Sreekanth Dayanand, Outsource Online (www.outsource-online.net). All rights reserved.
* @license see http://www.gnu.org/licenses/gpl-2.0.html  GNU/GPL.
* You can use, redistribute this file and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation.
* If you use this software as a part of own sofware, you must leave copyright notices intact or add OSOLMulticaptcha copyright notices to own.
*
*  
*/
/*

DROP TABLE IF EXISTS `osol_mvc_php_sessions`;
CREATE TABLE IF NOT EXISTS `osol_mvc_php_sessions` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `access` datetime NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `cookie_start_time` datetime NOT NULL,
  `device` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/
namespace OsolMVC\Core\Helper;

class SessionHandlerHelper extends \OsolMVC\Core\CoreParent{
	private $database = null;
    private $sessionSettings = null;
    private $siteSettings = null;
    /*
    array(
            'timezone' => 'Asia/Kolkata',
            'session_life_time' => 31536000,//60 * 60 * 24 *365 ->  365 day cookie lifetime
            //86400,    ->60 * 60 * 24  // 1 day cookie lifetime
            'idle_allowed' => 2592000,//60 * 60 * 24 * 30 ie 30 days

        );
    */
    private $dbSettings = array();
    private $currentSessId = "";
    private $currentUserId = 0;

    public $duplicateSessionDetected = false;


    
    /* block for ensuring unique session  starts here  ********************************************/
    private $cookieStartTime = null;
	private $cookieLifeTime = 2592000;//86400/*60 * 60 * 24*/ * 30;
	private $sessionIdName =  null;

    //http://localhost/pjt/upkar/upkar_site/public_html/deleteAllCookiesAndSesstions
    public  function deleteAllCookiesAndSesstions()
    {
        session_start(); // initialize session
		$dbSettings = $this->getSiteConfig()->getDBSettings();
			//Warning: session_id(): Cannot change session id when session is active
			/* $sessId = $testSessId;//session_id();
			if($sessId !="")
			{
				session_id($sessId);
			} */
			$sessId = session_id();
            $session_name =  session_name();

			session_destroy(); // destroy session
            setcookie($session_name/* "PHPSESSID" */,"",time()-3600,"/"); // delete session cookie
            //delete from DB too
			$database = $this->getDB();			
			//$this->_destroy($sessId);
			$deleteSQL = "DELETE FROM {$dbSettings['table_prefix']}php_sessions WHERE id = ? LIMIT 1";
			//echo $deleteSQL. " " . $sessId."<br />";
            $preparedSQl = $database->getReplacedSQLAdv($deleteSQL,
                                                        "s",
                                                        $sessId
                                                    );

            $logMessage = "delete SQL is " . $preparedSQl ."\r\n";
            $this->getLogHelper()->doLog($logMessage);  
            if ($database->executePS($deleteSQL, "s",$sessId)) 
            {
            
            }//if ($database->executePS($deleteSQL, "s",$sessId)) 
        
        foreach($_COOKIE as $varName => $varValue)
        {
            if (isset($_COOKIE[$varName])) {
                unset($_COOKIE[$varName]); 
                setcookie($varName, null, -1, '/'); 
                //return true;
            } 
           
            $logMessage = 'Cookies destroyed '.$varName."\r\n";
            SessionHandlerHelper::getLogHelper()->doLog($logMessage);
        }//foreach($_COOKIE as $varName => $varValue)
        
        //$js = "document.cookie = 'PHPSESSID=;Path=/cv;expires=Thu, 01 Jan 1970 00:00:01 GMT;';\n";// this is not working
			$jsMessage = "alert('Cookies and session destroyed ');\r\n";//".$sess_id ."
			//die ($jsMessage);
				$js = $jsMessage . "window.location.href='./'";
                //die('Cookies destroyed '.$varName);
				die ("<script>{$js}</script>");
    }//public static function deleteAllCookiesAndSesstions($dbSettings)
    public  function destroyDuplicateSession()
	{
        // COMES HERE AFTER THE ERROR "Same session id.." in _read
		//global $testSessId;
		//if(isset($_REQUEST['destroySession']) && $_REQUEST['destroySession'] == 'true')
		{
			
			$dbSettings = $this->getSiteConfig()->getDBSettings();
			//https://stackoverflow.com/questions/33517997/how-do-i-delete-phpsessid-on-client-computers
			session_start(); // initialize session

			//Warning: session_id(): Cannot change session id when session is active
			/* $sessId = $testSessId;//session_id();
			if($sessId !="")
			{
				session_id($sessId);
			} */
			$sessId = session_id();
            $session_name =  session_name();
			session_destroy(); // destroy session
			setcookie($session_name/* "PHPSESSID" */,"",time()-3600,"/"); // delete session cookie
			//delete from DB too
			 $database = $this->getDB();
			
			//$this->_destroy($sessId);
			$deleteSQL = "DELETE FROM {$dbSettings['table_prefix']}php_sessions WHERE id = ? LIMIT 1";
			//echo $deleteSQL. " " . $sessId."<br />";
            $preparedSQl = $database->getReplacedSQLAdv($deleteSQL,
                                                        "s",
                                                        $sessId
                                                    );

            $logMessage = "delete SQL is " . $preparedSQl ."\r\n";
            SessionHandlerHelper::getLogHelper()->doLog($logMessage);  
            if ($database->executePS($deleteSQL, "s",$sessId)) 
            {
            
            }//if ($database->executePS($deleteSQL, "s",$sessId))  
			
            $varName = "cookie_start_time";
			if (isset($_COOKIE[$varName])) {
                unset($_COOKIE[$varName]); 
                setcookie($varName, null, -1, '/'); 
                //return true;
            } 
			
			//$js = "document.cookie = 'PHPSESSID=;Path=/cv;expires=Thu, 01 Jan 1970 00:00:01 GMT;';\n";// this is not working
            
            //$jsMessage = "alert('Session destroyed id: ".$sessId ." ');\r\n";//".$sess_id ."
            $messageAfterDeletingDuplicateSession = $this->getLangClass()->getLangText('MESSAGE_AFTER_DELETING_DUPLICATE_SESSION');
            $jsMessage = "alert('".$messageAfterDeletingDuplicateSession."');\r\n";//".$sess_id ."
			//die ($jsMessage);
				$js = $jsMessage . "window.location.href='./'";
				die ("<script>{$js}</script>");
			//die("Session destroyed");
			
		}//if(isset($_REQUEST['destroySession']))
		
	}//public static function destroyDuplicateSession()
    /* block for ensuring unique session ends here ********************************************/
	
	//--------------------------------------------------------
   /*  protected function __construct(){
	}// protected function __construct() */
	// called in bootstrap.php after checking(& deleting) duplicate sessions
	public function initialize(){
        
        $siteConfig =  $this->getSiteConfig();
        $dbSettings = $siteConfig->getDBSettings();
        $this->sessionSettings = $siteConfig->getSessionSettings();
        $this->siteSettings = $siteConfig->getSiteSettings();

        
        

        $this->dbSettings = $dbSettings;
        $this->table_prefix = $this->dbSettings['table_prefix'];
        //$this->first_logged_in_time = time();// for ensuring unique session id
        /***
         * Just setting up my own database connection. Use yours as you need.
         ***/ 

            //require_once "mysqli_db_class.php";
            //$this->database = new DatabaseObject($sessionDBconnectionUrl);

            
            $this->database = $this->getDB();

        // Set handler to overide SESSION
        session_set_save_handler(
            array($this, "_open"),  
            array($this, "_close"),  
            array($this, "_read"),  
            array($this, "_write"),  
            array($this, "_destroy"),  
            array($this, "_gc") 
        );
        register_shutdown_function('session_write_close');

        /* block for ensuring unique session  starts here  ********************************************/
        // setting cookieStartTimein IST starts here
		$siteTimeZone =  $this->sessionSettings['site_time_zone'];
        $user_tz = new \DateTimeZone($siteTimeZone/* 'Asia/Kolkata' */);
        $user = new \DateTime('now', $user_tz);
        $this->cookieStartTime = $user->format('Y-m-d H:i:s');//date("Y-m-d H:i:s",time());
        
        $logMessage = "cookieStartTime set to  ".$this->cookieStartTime."\r\n";
        //die($logMessage);
        $this->getLogHelper()->doLog($logMessage);
        // setting cookieStartTimein IST ends here    
            
        $testSessId = '';//"d5lotp6qf5cnta54eupo7v7op6";
		$cookie_name = "cookie_start_time" ;
		$cookie_value = $this->cookieStartTime;//date('Y-m-d H:i:s',time());
		if(!isset($_COOKIE['cookie_start_time']))
		{
			setcookie($cookie_name, $cookie_value, time() + $this->cookieLifeTime /* (86400 * 30) */, "/"); // 86400 = 1 day
		}
		else
		{
			$this->cookieStartTime = $_COOKIE['cookie_start_time'];
		}//if(!isset($_COOKIE['cookie_start_time']))
        $logMessage = "cookie 'cookie_start_time' set to " .  $this->cookieStartTime;
        $this->getLogHelper()->doLog($logMessage);
		$sessId = $testSessId;//session_id();
		if($sessId !="")
		{
			session_id($sessId);
		}
        /* block for ensuring unique session  ends here  ********************************************/

        session_start([//https://www.php.net/manual/en/function.session-start.php#example-5976
            //'cookie_lifetime' => 86400,//60 * 60 * 24 * 7  // 7 day cookie lifetime
            'cookie_lifetime' => $this->sessionSettings['session_life_time'],//31536000,//60 * 60 * 24 *365 ->  365 day cookie lifetime
        ]);
        
        /* $origSessId = session_id();
        session_id(time()."-".$origSessId); */
        //die("session started");
        /*  
            //https://stackoverflow.com/questions/37789172/php-session-randomly-dies-when-read-and-close-is-active
            $SESSION = [
            'name' => 'my_session_name',
            'storage' => 'default',
                'options' => [
                    'read_and_close' => true,
                    'cookie_lifetime' => false,
                    'use_strict_mode' => true,
                    'use_only_cookies' => 1,
                    'cookie_httponly' => 1,
                    'use_trans_sid' => 0,
                    //Ensure this is true for production:
                    'cookie_secure' => false
                ],
            ]; 
            
            session_name($SESSION['name']);
            session_start($SESSION['options']);*/

            
    }// public function initialize(){
    /**
     * Open
     */
    public function _open($savepath, $id){
        // If successful
        /* $this->database->getSelect("SELECT `data` FROM {$this->table_prefix}php_sessions WHERE id = ? LIMIT 1",$id,TRUE);
        if($this->database->selectRowsFoundCounter() == 1){ */
        //die("SELECT `data` FROM {$this->table_prefix}php_sessions WHERE id = '{$id}' LIMIT 1");
        /* $getRowsOfSession = $this->database->selectPS("SELECT `data` FROM {$this->table_prefix}php_sessions WHERE id = ? LIMIT 1","s",$id);
        if(count($getRowsOfSession)== 1)
        {
            // Return True
            return true;
        }
        // Return False
        return false; */
        return !is_null($this->database);
       // return true;
    }
    /**
     * Read
     */
    public function _read($id)
    {
        $this->currentSessId = $id;
        $this->currentUserId = 0;
        // Set query
        /* $readRow = $this->database->getSelect('SELECT `data` FROM {$this->table_prefix}php_sessions WHERE id = ? LIMIT 1', $id,TRUE);
        if ($this->database->selectRowsFoundCounter() > 0) { */
         
		$stmt = "SELECT 'user_id',`data`,`cookie_start_time` FROM {$this->table_prefix}php_sessions WHERE id = ? LIMIT 1";
       
        
        
        $preparedSQl = $this->database->getReplacedSQLAdv($stmt,
                                                        "s",
                                                        $id
                                                    );

        $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);  
        $getRowsOfSession = $this->database->selectPS($stmt,"s",$id);
        
         //die("<pre>".print_r($getRowsOfSession,true)."</pre>");
        /* if(is_null($session_data))
        {
            $session_data = '';  //use empty string instead of null!
        } */
        //die("<pre>".print_r($getRowsOfSession,true)."</pre>");
        //echo "<br />currentUserId in read is ".$this->currentUserId."<br />";
        if(count($getRowsOfSession) > 0)
        {
            // check probability of getting duplicate sessions
            //echo "Checking duplicate sessions<br />";
            //https://stackoverflow.com/questions/138670/how-unique-is-the-php-session-id
            //https://bytes.com/topic/php/answers/667532-how-php-session-id-proved-unique
            //$_SERVER['HTTP_USER_AGENT']; may change with upgradation
            //echo $_SERVER['HTTP_USER_AGENT']."<br />";//
            /* 
            if(($getRowsOfSession[0]['first_logged_in_time'] !=0 ) && ($this->first_logged_in_time !=  $getRowsOfSession[0]['first_logged_in_time']))
            {
                $this->duplicateSessionDetected = true;
            }//if(isset($getRowsOfSession[0]['access_token']) && !isset($getRowsOfSession[0]['user_email']))
            if($this->duplicateSessionDetected)echo "Dulicate session detected<pre>".print_r($getRowsOfSession[0],true)."</pre>";
 */         

            if($getRowsOfSession[0]['cookie_start_time'] !=  $this->cookieStartTime)
            {
                //echo "Session name is ".$this->sessionIdName." ".__LINE__."</br>";
                //$alertText = " ". $getRowsOfSession[0]['cookie_start_time'] . " != ".$this->cookieStartTime;
                //$js = "alert('Same session id(".session_id().") exists 'cookie_start_time' of existing session :".$getRowsOfSession[0]['cookie_start_time'] . " cookieStartTime : " . $this->cookieStartTime."');\r\n";
                $messageOnDuplicateSession = $this->getLangClass()->getLangText('MESSAGE_ON_DUPLICATE_SESSION');
                $js = "alert('".$messageOnDuplicateSession."');\r\n";
                $js .= "window.location.href='" . $this->sessionSettings['destroyDuplicateSessionURL'] . "'";//?destroySession=true
                die ("<script>{$js}</script>");
                //header("location: testUniqueSessionWithCookie.php")
            }//if($getRowsOfSession[0]['cookie_start_time'] !=  $this->cookieStartTime)

            $this->currentUserId = (int)$getRowsOfSession[0]['user_id'] + 0;
            //echo "<br />currentUserId in read is ".$this->currentUserId."<br />";
            return $getRowsOfSession[0]['data'];
        } else {
            return '';
        }
    }

    /**
     * Write
     */
    public function _write($id, $data)
    {
        
        // Create time stamp
        $access = time();

        // Set query
        $dataReplace[0] = $id;
        //$dataReplace[1] = $access;
        $dataReplace[1] = $data;
        /* $dataJSON = json_decode($data);
        echo "dataJSON is <pre>".print_r($data ,true)."</pre>"; */
        $this->currentUserId =    $this->getCurrentUserId();
        //if ($this->database->noReturnQuery('REPLACE INTO {$this->table_prefix}php_sessions(id,access,`data`) VALUES (?, ?, ?)', $dataReplace)) {
        /*
        REPLACE works exactly like INSERT , except that if an old row in the table has the same value as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
        */
        //echo "REPLACE INTO {$this->table_prefix}php_sessions(id,access,`data`) VALUES ('{$id}', now(), '{$data}')";
        /* date_default_timezone_set('Asia/Kolkata'); // IST
        $max= 3600;
        $old = time() - $max;
        $dateOfOld = date('Y-m-d H:i:s',$old);
        echo "DELETE FROM {$this->table_prefix}php_sessions WHERE access < '".$dateOfOld."'<br />"; */
        //echo "<br />currentUserId in write is ".$this->currentUserId."<br />";
        /* if($this->database->executePS("REPLACE INTO {$this->table_prefix}php_sessions(`id`,`user_id`,`access`,`data`) 
                                        VALUES (?, ?,now(), ?)",
                                        "sis",
                                        $id,
                                        $this->currentUserId,
                                        $data
                                        )) */
        /* change for ensuring unique session  starts here  ********************************************/   
        //echo $_SERVER['HTTP_USER_AGENT'];
        //using get_browser() to display capabilities of the user browser
        //$mybrowser = get_browser();
        //print_r($mybrowser);
        /*
        [parent] => IE 6.0
        [platform] => WinXP
        [netclr] => 1
        [browser] => IE
        [version] => 6
        [majorver] => 6
        [minorver] => 0
        => 2
        [frames] => 1
        [iframes] => 1
        */
        $accessingDevice = $_SERVER['HTTP_USER_AGENT'];//$mybrowser['parent'] . " via " . $mybrowser['platform'];
        $stmt = "REPLACE INTO {$this->table_prefix}php_sessions(id,`user_id`,access,`data`,`cookie_start_time`,`device`) 
                     VALUES (?, ?,now(), ?,?,?)";
        $preparedSQl = $this->database->getReplacedSQLAdv($stmt,
                                                            "sisss",
                                                            $id,
                                                            $this->currentUserId,
                                                            $data,
                                                            $this->cookieStartTime,
                                                            $accessingDevice
                                                        );

        $logMessage = str_repeat("_",50)."\r\n cookie 'cookie_start_time' set to " .  $this->cookieStartTime ."  user id is " . $this->currentUserId . "\r\n";
        $this->getLogHelper()->doLog($logMessage);                                                            
        $logMessage = "writing to session table \r\n ".$preparedSQl."\r\n";
        $this->getLogHelper()->doLog($logMessage);
        if( $this->database->executePS( $stmt,
                                            "sisss",
                                            $id,
                                            $this->currentUserId,
                                            $data,
                                            $this->cookieStartTime,
                                            $accessingDevice
                                        ))
        /* change for ensuring unique session  ends here  ********************************************/   
        //if($this->database->executePS("REPLACE INTO {$this->table_prefix}php_sessions(id,access,`data`,`user_id`,`device`) VALUES (?, now(), ?,0,'')","ss",$id,$data))
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Destroy
     */
    public function _destroy($id)
    {
        // Set query
        //if ($this->database->noReturnQuery('DELETE FROM {$this->table_prefix}php_sessions WHERE id = ? LIMIT 1', $id)) {
        if ($this->database->executePS("DELETE FROM {$this->table_prefix}php_sessions WHERE id = ? LIMIT 1", "s",$id)) {
            $this->currentUserId = 0;
            return true;
        } else {

            return false;
        }
    }
    /**
     * Close
     */
    public function _close(){
        // Close the database connection
        //if($this->database->dbiLink->close){
		$explicitDisconnectionNeeded = false;
        if(!$explicitDisconnectionNeeded || $this->database->disconnect())
		{
            // Return True
            return true;
        }
        // Return False
        return false;
    }

    /**
     * Garbage Collection
     */
    public function _gc($max)
    {
        // Calculate what is to be deemed old
        //date_default_timezone_set('Asia/Kolkata'); // 
        $mysqlPHPTimeDiff = $this->database->mySQLAndPHPTimeDiff();
        $old = time()/* PHP time */ + $mysqlPHPTimeDiff - $max;
        $dateOfOld = date('Y-m-d\TH:i:s',$old);
        //echo "DELETE FROM {$this->table_prefix}php_sessions WHERE access < '".$dateOfOld."'<br />";
        //if ($this->database->noReturnQuery('DELETE FROM {$this->table_prefix}php_sessions WHERE access < ?', $old)) {
        if ($this->database->executePS("DELETE FROM {$this->table_prefix}php_sessions WHERE access < ?", "s",$dateOfOld)) {
            return true;
        } else {
            return false;
        }
    }

    public function __destruct()
    {
        $this->_close();
    }


    public function getCurrentSessionId()
    {
        return $this->currentSessId;
    }//public function getCurrentSessionId()
	//below method is called in ClassGoogleLoginController
	/*
	called from OsolMVC\Core\Helper::GoogleLoginHelper->checkUserLoginStatus($userDetails)
	*/
    public function updateUserIdForSession($userId)
    {
        $this->currentUserId = $userId;
        $stmt = "UPDATE {$this->table_prefix}php_sessions set `user_id`=? where `id`= ?";
        $preparedSQl = $this->database->getReplacedSQL($stmt,
                                                       array(
                                                        $userId, 
                                                        $this->currentSessId 
                                                       )  
                                                         );
        //if($this->dbSettings[])echo $preparedSQl."<br />";
        if ($this->database->executePS($stmt, 
                                        "is",
                                        $userId,
                                        $this->currentSessId
                                        ) 
         )
        {
            return true;
        } else {
            return false;
        }
    }//public function updateUserId($userId)
    private function getCurrentUserId()
    {
		//return ((!isset($_SESSION['user_id'])?0:$_SESSION['user_id']) + 0);
		$requestVarHelper =  RequestVarHelper::getInstance();
		$loggedInUserIdSessionValue = $requestVarHelper->getSessionVar('user_id');
		$returnValue = is_null($loggedInUserIdSessionValue)?0:((int)$loggedInUserIdSessionValue + 0);
		return $returnValue;
         
    }//private function getCurrentUserId()
    public function isLoggedIn()
    {
        return !$this->isLoggedOut();
    }//public function isLoggedIn()
    public function isLoggedOut()
    {
        $this->currentUserId =    $this->getCurrentUserId();
        //die("Current UserId is ".$this->currentUserId."<br />");
        return ($this->currentUserId === 0);
    }//public function isLoggedOut()


}//class SessionHandlerHelper extends \OsolMVC\Core\CoreParent{
?>