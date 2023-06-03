<?php 

namespace OsolMVC\Core\View\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class AccountView extends DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function showView()
	{
		
		
		
		//$this->page2Show = "main.html";	
		$this->addPreloader = true;
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL("Admin/".$templateMainSubFolder."/js/account.js");
		$this->addJSScriptTag($templateFileURL);
		
		parent::showView();
	}//public function showView()
	public function accountsList()
	{
		$this->page2Show = "accountsList.html";
	}//public function accountDashBoard()
	
	
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>