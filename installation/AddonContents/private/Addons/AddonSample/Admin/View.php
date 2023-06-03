<?php 
namespace OsolMVC\Addons\__ADDON_NAME__\Admin;

class View extends \OsolMVC\Addons\Basic\Admin\View
{
    public function showView()
	{       
		$this->addJSScriptTag($this->getTemplateFileURLOfAddon("Admin/js/main.js"));
		$this->addJSScriptTag($this->getTemplateFileURLOfAddon("js/main.js"));
		parent::showView();
	}
}
?>