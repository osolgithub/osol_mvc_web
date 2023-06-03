<?php 
namespace OsolMVC\Addons\__ADDON_NAME__;

class View extends \OsolMVC\Addons\Basic\View
{
    public function showView()
	{
        $this->addJSScriptTag($this->getTemplateFileURLOfAddon("js/main.js"));
		parent::showView();
	}
}
?>