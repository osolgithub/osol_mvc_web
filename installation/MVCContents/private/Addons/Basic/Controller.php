<?php 

namespace OsolMVC\Addons\Basic;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class Controller extends \OsolMVC\Core\Controller\DefaultController
{
    protected $addonName = 'Basic';
    protected $addonConfig = null;
    public function setConfig()
    {
        /* $addonFolder = OSOLMVC_ADDON_FOLDER_ABSOLUTE . "/" . $this->addonName;
        require_once($addonFolder);*/
		$addonConfig = OSOLMVC_ADDON_FOLDER_ABSOLUTE . "/" . $this->addonName . "/Config.php";
        require_once($addonConfig);
    }//public function setConfig()
    /* public function getAddonName()
    {
        return $this->addonName;
    }//public function getAddonName() */
    private function getAddonFolders()
    {
        $addonNameSmall = strtolower($this->addonName);
        $addonTemplateFolderRelativePath = "public/templates/default/addons/" . $addonNameSmall;
        return array(
            'addonPrivateFolderRelativePath' => 'private/Addons/'.$this->addonName,
            'addonPrivateFolder' => OSOLMVC_PRIVATE_FOLDER_ABSOLUTE.'/Addons/'.$this->addonName,
            'addonUploadsFolder' => OSOLMVC_PRIVATE_FOLDER_ABSOLUTE.'/uploads/Addons/'.$this->addonName,
            'addonTemplateFolderRelativePath' => $addonTemplateFolderRelativePath,
            'addonTemplateFolder' => OSOLMVC_HOME_FOLDER_PATH."/". $addonTemplateFolderRelativePath,
            
        );
        
    }//private function getAddonFolders()
    public function getBackup()
    {
        
        
        //$addonNameSmall = strtolower($this->addonName);
        
        // public/templates/default/addons/$addonNameSmall
        $addonFolders = $this->getAddonFolders(); 
        $addonPrivateFolderRelativePath = $addonFolders['addonPrivateFolderRelativePath'];
        $addonPrivateFolder = $addonFolders['addonPrivateFolder'];
        $addonTemplateFolderRelativePath = $addonFolders['addonTemplateFolderRelativePath'];
        //"public/templates/default/addons/" . $addonNameSmall;
        $addonTemplateFolder = $addonFolders['addonTemplateFolder'];
        //OSOLMVC_HOME_FOLDER_PATH."/".$addonTemplateFolder;
        //die($actualAddonTemplatePath);
        $backupFileName = $this->addonName .".". 
                            date("dmY-His").
                            "archive.zip";
        $archive = new \PclZip(OSOLMVC_PRIVATE_FOLDER_ABSOLUTE."/backups/".
                                    $backupFileName
                                    ); 
        $v_list = $archive->add($addonPrivateFolder,  
                                PCLZIP_OPT_REMOVE_PATH, __DIR__,
                                PCLZIP_OPT_ADD_PATH, $addonPrivateFolderRelativePath
                            );
        
        $v_list = $archive->add($addonTemplateFolder,  
                                PCLZIP_OPT_REMOVE_PATH, $addonTemplateFolder,
                                PCLZIP_OPT_ADD_PATH, $addonTemplateFolderRelativePath
                            );  
        if ($v_list == 0) { 
            die("Error : ".$archive->errorInfo(true)); 
        } 
        	
        $doneBackupMessage = $this->getGeneralAddonHelper()
                                    ->getSelectedLangClass($this->addonName)
                                    ->getLangText('DONE_BACKUP') .
                                    "<a href=\"Drafting/downloadBackup?backupFileName={$backupFileName}\">{$backupFileName}</a>";
        $message2Template = array("message" => $doneBackupMessage . "({$this->addonName})","message_type" => "Info");
        $this->setMessage($message2Template);
        $this->render();
        
    }
    public function downloadBackup()
    {
        $backupFileName = $this->getRequestVarHelper()
                                ->getGetVar('backupFileName');
        $zipFilePath = OSOLMVC_PRIVATE_FOLDER_ABSOLUTE."/backups/".
                                    $backupFileName;
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . basename($zipFilePath));
        header('Content-Length: ' . filesize($zipFilePath));
        readfile($zipFilePath);
    }//public function downloadBackup()
    public function deleteAddon()
    {   	
        $doneDeleteMessage = $this->getGeneralAddonHelper()
                                    ->getSelectedLangClass($this->addonName)
                                    ->getLangText('DONE_DELETE');
        $message2Template = array("message" => $doneDeleteMessage . "({$this->addonName})","message_type" => "Info");
        
        $addonFolders = $this->getAddonFolders(); 
        $addonPrivateFolder = $addonFolders['addonPrivateFolder'];
        $addonUploadsFolder = $addonFolders['addonUploadsFolder'];
        //"public/templates/default/addons/" . $addonNameSmall;
        $addonTemplateFolder = $addonFolders['addonTemplateFolder'];
        $filesHelper = $this->getFilesHelper();
        $filesHelper->removeDirectory($addonPrivateFolder);
        $filesHelper->removeDirectory($addonUploadsFolder);
        $filesHelper->removeDirectory($addonTemplateFolder);

        //$this->render($message2Template);
        $this->redirect2Page("?message={$doneDeleteMessage}&message_type=Info");
    }//public function DeleteAddon()
    /* public function render()
    {

		if(!$myView = $this->getView())
		{
			echo "view does not exist<br />";
			$defaultViewClass = '\OsolMVC\Core\View\DefaultView';
			$myView = $defaultViewClass::getInstance();
		}//if(!$myView = $this->getModel())
        $myView->setMessage($this->message2Template);
		$myView->showView();
    } *///public function getViewAndShow($message2Template = array("message" => "", $message_type = ""))
}
?>