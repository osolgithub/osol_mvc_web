<?php 

namespace OsolMVC\Core\View\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class ACLView extends \OsolMVC\Core\View\Admin\DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function showView()
	{
		
		/* $isadmin = $this->getCurrentViewIsAdmin();
		echo "Current View is ". ($isadmin?"":" not ") . " admin."; */
		
		//$this->page2Show = "main.html";	
		
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL("Admin/".$templateMainSubFolder."/js/main.js");
		$this->addJSScriptTag($templateFileURL);
		
		parent::showView();
	}//public function showView()
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>