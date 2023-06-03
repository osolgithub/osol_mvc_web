<?php
/*
$logMessage = "this message will be logged";
\upkar\php\helpers\ClassLogHelper::doLog($logMessage);
*/
namespace OsolMVC\Core\Helper;
class LogHelper  extends \OsolMVC\Core\CoreParent{
    public  function doLog($logMessage, $prependFileAndLineAndLoggedTime = true){
        $clsSiteConfig = $this->getSiteConfig();//\upkar\php\ClassSiteConfig::getInstance();
        $doLog = $clsSiteConfig->getDoLog();
        //die("LOG IS {$doLog}");
        $currentUserId = ((!isset($_SESSION['user_id'])?0:$_SESSION['user_id']) + 0);//using ClassDBSessionHandler causes complication   ::getInstance()->getCurrentUserId();
        if(!$doLog)// || $currentUserId != 1/*outsourceol@gmail.com */)
        {
			//echo  "Not Logging <br />";echo __FILE__. " , line # : ". __LINE__."<br />";
            //die("LOG IS {$doLog}");
            return;
        } 
        else //if(!$clsSiteConfig->getDoLog())
        {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            
            $logFile =  $clsSiteConfig->getAllLogFile();
            $fp =  fopen($logFile ,"a");
            if($prependFileAndLineAndLoggedTime)
            {
                $logMessage = "\r\n user id is ".$currentUserId . " : " .$caller['file'] . " : " . $caller['line'] . " ".$logMessage ." logged at ". date("d-m-Y H:i:s")."\r\n"."\r\n";
            }
            fwrite($fp,$logMessage."\r\n");
            fclose($fp);
        }//if(!$clsSiteConfig->getDoLog())
    }//public static function doLog($logMessage){
}//class ClassLogHelper{
?>