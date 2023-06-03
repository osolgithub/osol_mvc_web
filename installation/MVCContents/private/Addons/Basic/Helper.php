<?php 

namespace OsolMVC\Addons\Basic;
class Helper  extends \OsolMVC\Core\CoreParent{
    
    public function prependWithTestFunction($testWord)// sample helper function
    {
        return "Test function Called. ".$testWord;
    }//public function testFunction($testWord)
    public function getModuleLinks():array
    {
        return array();
    }
    /* protected function getMyContoller()
    {
        $className = get_class($this);
        $controllerClass = str_replace("Helper","Controller",$className);
        return $controllerClass::getInstance();
    }//protected function getMyContoller() */
    public function translate2Lang($text2Translate)
    {
        $addonName = $this->getAddonName($this);//getMyContoller()->
        $selectedLangClass = $this->getSelectedLangClass($addonName);
        return $selectedLangClass->getLangText($text2Translate);//$text2Translate;//
    }//public function translate2Lang($addonName = "")
    public function getSelectedLangClass($addonName = "")
    {
        $addonLangClassPrefix = "OsolMVC\Addons\\" . $addonName."\Lang";
        $selectedLang = $this->getSelectedLang();
        $langClassString =  $addonLangClassPrefix . "\LangClass" . strtoupper(str_replace("-","",$selectedLang));
        return $langClassString::getInstance();
    }//public function getSelectedLangClass()
}//class Helper  extends \OsolMVC\Core\CoreParent{
?>