<?php
namespace OsolMVC\Addons\Basic\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class Controller extends \OsolMVC\Addons\Basic\Controller //\OsolMVC\Core\Controller\Admin\DefaultController
{
    protected $addonName = 'Basic';
    protected $addonConfig = null;
    private function getAddonFolders()
    {
		$addonFrontendFolders = parent::getAddonFolders();
		
        $addonNameSmall = strtolower($this->addonName);
        $addonAdminTemplateFolderRelativePath = "public/templates/default/addons/" . $addonNameSmall."/admin";
		
        $addonAdminFolders =  array(
            'addonAdminPrivateFolderRelativePath' => 'private/Addons/'.$this->addonName.'/Admin',
            'addonAdminPrivateFolder' => OSOLMVC_PRIVATE_FOLDER_ABSOLUTE.'/Addons/'.$this->addonName.'/Admin',
            'addonAdminUploadsFolder' => OSOLMVC_PRIVATE_FOLDER_ABSOLUTE.'/uploads/Addons/'.$this->addonName.'/Admin',
            'addonAdminTemplateFolderRelativePath' => $addonAdminTemplateFolderRelativePath,
            'addonAdminTemplateFolder' => OSOLMVC_HOME_FOLDER_PATH."/". $addonAdminTemplateFolderRelativePath,            
        );
        return array_merge($addonFrontendFolders, $addonAdminFolders);
    }//private function getAddonFolders()    
}
?>