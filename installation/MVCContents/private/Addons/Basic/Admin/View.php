<?php 

namespace OsolMVC\Addons\Basic\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class View extends \OsolMVC\Addons\Basic\View //\OsolMVC\Core\View\Admin\DefaultView  
{
    
	/* protected function getTemplateFileURLOfAddon($filePath,$targetAddon = "")
	{
		$addonName = ($targetAddon == "")? 
						strtolower($this->getAddonName($this)) : 
						strtolower($targetAddon);
		///public/templates/default/addons/basic
		$addonFilePath = "addons/" . $addonName. "/Admin/".$filePath;
		return $this->getTemplateFileURL($addonFilePath);
	}//protected function getTemplateFileURLOfAddon($filePath) */
	protected function getAddonTemplateFullPath($fileName)
	{ 
		$addonName = $this->getAddonName($this);
		$isAdmin = true;
		$templateFile =  $this->getTemplateSubFolderFullPathOfAddon($addonName,$fileName,$isAdmin);
		return $templateFile;//$templateFile =  "private/Addons/$addonName/templates/default/main.html";
	}//protected function getCoreTemplateFullPath($fileName)
	

    
    public function showView()
	{
        //echo "This is showView<br />";
		
		/* $templateMainSubFolder = $this->getTemplateMainSubFolder();
		//require_once(__DIR__."/../templates/default/".$templateMainSubFolder."/main.html");
		$templateFile =  $this->getTemplateMainSubFolderFullPath($templateMainSubFolder."/main.html"); */
		//echo "page2Show is ".$this->page2Show;
		$templateFile = $this->getAddonTemplateFullPath($this->page2Show);//"main.html"
	
		//$this->addCSSLinkTag($this->getTemplateFileURLOfAddon("css/main.css")); // sample code to add JS file
		//$this->deliverPage($templateFile);
		parent::deliverPage($templateFile);
	}
}
?>