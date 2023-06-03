<?php 

namespace OsolMVC\Core\View\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class DefaultView extends \OsolMVC\Core\View\DefaultView
{
    
	/* protected function __construct()
	{
		
	} */
	protected function getTemplateFileURL($scriptFile)
	{
		$siteConfig = $this->getSiteConfig();
		return "../public/templates/".$siteConfig->getFrontendTemplate()."/".$scriptFile;
	}//protected function getTemplateFileURL($scriptFile)
	protected function getCoreTemplateFullPath($fileName)
	{
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		//$templateFile =  __DIR__."/../templates/default/".$templateMainSubFolder."/main.html";
		$templateFile =  $this->getTemplateMainSubFolderFullPath("Admin/".$templateMainSubFolder."/".$fileName);
		return $templateFile;
	}//protected function getCoreTemplateFullPath($fileName)
	protected function requirePagePart($type)
	{
		
		$fileToReturn  = $type;//."LoggedIn";
		$returnFile =$this->getTemplateMainSubFolderFullPath('Admin/default')."/pageParts/".$fileToReturn.".html";		
		
		if($type == "footer")
		{
			$this->doFooterReplaces($returnFile);
		}
		else
		{
			require_once($returnFile);
		}//if($type == "footer")
	}//protected function requirePagePart($type)
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>