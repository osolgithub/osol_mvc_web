<?php
namespace OsolMVC\Core\Lang;
 class LangClassENUS extends \OsolMVC\Core\CoreParent{
	private $OSOL_MVC_SITE_TITLE = "OSOL MVC Instance";
	private $HOME_PAGE = "Home";
	private $ABOUT_US = "About Us";
    private $TOPICS = "Topics";
    private $ADD_TOPIC = "Add Topic";
    private $SIGN_IN = "Login/Sign up";
    private $SIGN_OUT = "Sign Out";
    private $PROFILE = "User Profile";
    private $CONTACT_US = "Contact Us";
    private $EDIT_LINK = "Edit Link";
    private $SAVED_RESOURCES = "Saved Resources";
    private $MANAGE_TOPICS = "Manage Topics";
    private $ASSIGN_PARENTS = "Assign Parent(s)";
    private $INVALID_ID = "The id of record you sought is invalid";
    private $SAVED_LINKS = "Saved Links";
    private $MESSAGE_ON_DUPLICATE_SESSION = "There is some problem in proceeding, please wait while you are redirected.. ";
    private $MESSAGE_AFTER_DELETING_DUPLICATE_SESSION = "Please click Ok again. ";
    private $MESSAGE_AFTER_LINK_UPDATE_SUCCESS = "Link Updated Successfully";
	
	
    private $ERROR_MESSAGE_HEADING = "Error!!!";
    private $AUTO_REGISRATION_DISABLED = "You are not a registered user. To register,please contact admin";
    
    /*
    Usage example :
    $message = \ClassSiteConfig::getInstance()->getSelectedLangClass()->getLangText('MESSAGE_ON_DUPLICATE_SESSION');
    */
    public function getLangText($varName)
    {
        $var2Return = $varName;
        if(isset($this->$varName))
        {
            $var2Return = $this->$varName;
        }//if(isset($this->$varName))
        return $var2Return;
    }//public function getLangText($varName)
 }//class LangClassENUS extends extends \OsolMVC\Core\CoreParent{
?>