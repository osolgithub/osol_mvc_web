<?php 
namespace OsolMVC\Addons\__ADDON_NAME__;
class Helper  extends \OsolMVC\Addons\Basic\Helper{
    public function prependWithTestFunction($testWord)// sample helper function
    {
        return "Test function Called. ".$testWord;
    }//public function testFunction($testWord)

    public function getModuleLinks():array
    {
        return array(
			array("link"=>"__ADDON_NAME__","link_text" => $this->translate2Lang("__ADDON_NAME__")),
			array("link"=>"__ADDON_NAME__/Admin","link_text" => $this->translate2Lang("__ADDON_NAME__"). "/Admin")
			);
    }
}//class Helper  extends \OsolMVC\Core\CoreParent{
?>