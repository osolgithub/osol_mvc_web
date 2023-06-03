<?php
namespace OsolMVC\Addons\Basic\Lang;
 class LangClassENUS extends \OsolMVC\Core\CoreParent{
	protected $DONE_BACKUP = "Done Backup!!!";
	protected $DONE_DELETE = "Done Deleting Addon!!!";
	protected $DELETION_DISABLED_FOR_ADDON = "Deletion disabled for this Addon!!!<br/>To enable, comment method 'deleteAddon' in the Controller class of the addon";
    
    
    /*
    Usage example :
    $message = $this->getAddonHelper($addonName)->getSelectedLangClass($addonName)->getLangText('DONE_BACKUP');
    */

    public function getLangText($varName)
    {
        $var2Return = $varName;
        if(isset($this->$varName))
        {
            $var2Return = $this->$varName;
        }//if(isset($this->$varName))
        return $var2Return;
    }//public function getLangText(\$varName)
 }//class LangClassENUS extends extends \OsolMVC\Core\CoreParent{
?>