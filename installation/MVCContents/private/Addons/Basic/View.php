<?php 

namespace OsolMVC\Addons\Basic;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;
/*
### Workflow
@note : methods getAddonTemplateFullPath($fileName) and getTemplateFileURLOfAddon($type) are extended in Admin/View to use 'Admin/' prefix 
Comes from controller->render

showView():returns void
	$templateFile = $this->getAddonTemplateFullPath($this->page2Show);
	parent::deliverPage($templateFile);// inherited from \OsolMVC\Core\View\DefaultView


### Addon Template Structure	

main page area(default 'mail.html') will be in
private/Addons/<Addon name>templates/<chosen template eg: default>	
And
private/Addons/<Addon name>templates/<chosen template eg: default>/Admin


public assets(js,css,images etc) are in 

public/templates/<chosen template eg: default>/addons/addonName
use addonView->getTemplateFileURLOfAddon(filepath) to get the above path

you may use subfolders in the above folder for each addon
*/

class View extends \OsolMVC\Core\View\DefaultView
{
    
	protected function getTemplateFileURLOfAddon($filePath,$targetAddon = "")
	{
		$addonName = ($targetAddon == "")? 
						strtolower($this->getAddonName($this)) : 
						strtolower($targetAddon);
		///public/templates/default/addons/basic
		$addonFilePath = "addons/" . $addonName. "/".$filePath;
		return $this->getTemplateFileURL($addonFilePath);
	}//protected function getTemplateFileURLOfAddon($filePath)
	protected function getAddonTemplateFullPath($fileName)
	{ 
		$addonName = $this->getAddonName($this);
		$templateFile =  $this->getTemplateSubFolderFullPathOfAddon($addonName,$fileName);
		return $templateFile;
	}//protected function getCoreTemplateFullPath($fileName)
	

    public function translate($text2Tranlate)
	{
		
		return $this->getAddonHelper($this->getAddonName($this))
						->translate2Lang($text2Tranlate);
	}//public function translate($text2Tranlate)
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