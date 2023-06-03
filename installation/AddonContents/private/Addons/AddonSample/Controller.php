<?php 

namespace OsolMVC\Addons\__ADDON_NAME__;


class Controller extends \OsolMVC\Addons\Basic\Controller
{
    protected $addonName = '__ADDON_NAME__';
    public function deleteAddon()
    {
        $doneBackupMessage = $this->getGeneralAddonHelper()
                                    ->getSelectedLangClass($this->addonName)
                                    ->getLangText('DELETION_DISABLED_FOR_ADDON');
        $message2Template = array("message" =>  $doneBackupMessage . "({$this->addonName})","message_type" => "Error");
        $this->setMessage($message2Template);
        $this->render();
    }
    
}
?>