<?php 

namespace OsolMVC\Core\View;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class ACLView extends \OsolMVC\Core\View\DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function showView()
	{
		/* $isadmin = $this->getCurrentViewIsAdmin();
		echo "Current View is ". ($isadmin?"":" not ") . " admin."; */
		
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL($templateMainSubFolder."/js/main.js");
		$this->addJSScriptTag($templateFileURL);
		
		parent::showView();
	}//public function showView()
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>