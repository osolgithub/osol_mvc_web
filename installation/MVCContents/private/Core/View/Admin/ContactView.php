<?php 

namespace OsolMVC\Core\View\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class ContactView extends \OsolMVC\Core\View\Admin\DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function showView()
	{
		
		
		
		//$this->page2Show = "main.html";	
		$this->addPreloader = true;
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL("Admin/".$templateMainSubFolder."/js/contact.js");
		$this->addJSScriptTag($templateFileURL);
		
		parent::showView();
	}//public function showView()
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>