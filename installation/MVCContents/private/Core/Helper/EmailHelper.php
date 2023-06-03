<?php
namespace OsolMVC\Core\Helper;
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class EmailHelper extends \OsolMVC\Core\CoreParent{
	protected function __construct()
	{
		
		/* echo "<pre>".print_r($_GET,true)."</pre>";
		echo "<pre>".print_r($_SERVER,true)."</pre>"; */ 
	}
	public  function initiate(){
		
		
	}//public  function initiate(){
	
	public function sendEmail($settings4CurrentEmail)
	{
		$siteConfig = $this->getSiteConfig();
		$smtpSettings = $siteConfig->getSMTPSettings();
		//Create a new PHPMailer instance
		$mail = new PHPMailer;

		//Tell PHPMailer to use SMTP
		$mail->isSMTP();

		//Enable SMTP debugging
		// SMTP::DEBUG_OFF = off (for production use)
		// SMTP::DEBUG_CLIENT = client messages
		// SMTP::DEBUG_SERVER = client and server messages
		$mail->SMTPDebug = SMTP::DEBUG_OFF;//SMTP::DEBUG_SERVER;

		//Set the hostname of the mail server
		$mail->Host = $smtpSettings["smtpHost"];
		
		
		
		
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = $smtpSettings["port"];

		//Set the encryption mechanism to use - STARTTLS or SMTPS
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mtpSecureConstant =  'ENCRYPTION_START' . $smtpSettings["SMTPSecure"];
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $smtpSettings["smtpLogin"];

		//Password to use for SMTP authentication
		$mail->Password = $smtpSettings["smtpPassword"];//app password after enabling 2 step authentication

		//Set who the message is to be sent from
		$mail->setFrom($smtpSettings["mailSendFrom"], $smtpSettings["mailSendFromName"]);

		//Set an alternative reply-to address
		$mail->addReplyTo($settings4CurrentEmail['senderEmail'] );//, 'First Last');

		//Set who the message is to be sent to
		//$mail->addAddress('hariyom.com@gmail.com', 'Hariyom Solutions');
		$mail->addAddress($smtpSettings['sendToEmail'] , $smtpSettings['sendToName']);
		//$mail->addBCC('advdsreekanth@gmail.com', 'Adv D Sreekanth');

		//Set the subject line
		$mail->Subject = $settings4CurrentEmail['messageSubject'];//'Hariyom Solutions, New Enquiry';// (PHPMailer GMail SMTP)';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		/* $htmlContentDir = __DIR__."/includes/externalUtils/phpMailer/examples";
		$mail->msgHTML(str_replace("__MAIL_CONTENT__",$messageBody,file_get_contents($htmlContentDir.'/contents.html')),$htmlContentDir ); */
		
		$fullURl2Root = $siteConfig->getFullURL2Root();
		//echo "fullURl2Root is $fullURl2Root<br />";
		$mail->msgHTML(str_replace(array("__MAIL_CONTENT__","__OSOLMVC_URL_ROOT_FULL_URL__"),
									array($settings4CurrentEmail['messageBody'],$fullURl2Root),
									$settings4CurrentEmail['emailHTMLMessage']), 
						$settings4CurrentEmail['emailResourcesFolder']
						);

		//Replace the plain text body with one created manually
		$mail->AltBody = ($settings4CurrentEmail['messageBody'].'This is a plain-text message body'). str_replace("<br />","\r\n",$settings4CurrentEmail['messageBody']);

		//Attach an image file
		//$mail->addAttachment('C:/Users/Dell/Desktop/bird.jpg');
		/* Array
		(
			[fileToUpload] => Array
				(
					[name] => bird.jpg
					[type] => image/jpeg
					[tmp_name] => C:\wamp64\tmp\php3C89.tmp
					[error] => 0
					[size] => 26766
				)

		) */
		if(isset($_FILES['fileToUpload']) && isset($_FILES['fileToUpload']['tmp_name']))
		{
			$mail->addAttachment($_FILES['fileToUpload']['tmp_name'],$_FILES['fileToUpload']['name']);
		}//if(isset($_FILES['fileToUpload']) && isset($_FILES['fileToUpload']['tmp_name']))
		
		$response2Send = "";
		//send the message, check for errors
		if (!$mail->send()) {
			//echo 'Mailer Error: '. $mail->ErrorInfo;
			$response2Send = ("{\"status\":\"error\",\"message\":\"".addslashes($mail->ErrorInfo)."\"}");
		} else {
			//echo 'Message sent!';
			//Section 2: IMAP
			//Uncomment these to save your message in the 'Sent Mail' folder.
			#if (save_mail($mail)) {
			#    echo "Message saved!";
			#}
			$response2Send = ("{\"status\":\"success\",\"message\":\"Message Sent!!!\"}");
		}
		
		
		return $response2Send;
	}//public function sendEmail($settings4CurrentEmail)
    
}//class EmailHelper

?>