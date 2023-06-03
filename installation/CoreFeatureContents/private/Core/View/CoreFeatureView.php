<?php 

namespace OsolMVC\Core\View;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class __COREFEATURE_NAME__View extends \OsolMVC\Core\View\DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function showView()
	{
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL($templateMainSubFolder."/js/main.js");
		$this->addJSScriptTag($templateFileURL);
		
		parent::showView();
	}//public function showView()
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>