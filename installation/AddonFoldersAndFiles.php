<?php
//die("TemporarilyDisabled");
$addonName = (isset($_GET['addonName']) && ctype_alnum($_GET['addonName'])) ? $_GET['addonName'] : "";//"Benorm";

//replace __ADDON_NAME__ inside files with $addonName
//replace __ADDON_NAME_LOWERCASE__ inside files with $addonNameSmall ie strtolower($addonName)
//replace AddonSample in pathnames with $addonName
//replace addonsample in pathnames with $addonNameSmall ie strtolower($addonName)
if($addonName == "")
{
	die("Addon name must contain only alphabets or numbers");
}//if($addonName == "")
$addonBaseFolder = "projectBase/AddonMade/".$addonName;
$addonNameSmall = strtolower($addonName);
$addonPrivateFolder = "private/Addons/{$addonName}";
$addonAdminPrivateFolder = "private/Addons/{$addonName}/Admin";
$addonPublicFolder = "public/templates/default/addons/{$addonNameSmall}";
$AddonFoldersAndFiles = array(
                            /* $addonPrivateFolder => null,
                            $addonPublicFolder => null,
                            $addonAdminPrivateFolder => null, */
                            );

/**

	- create folders 
	 1. private/Addons/{$addonName}, templates/default
	 2. private/uploads/Addons/{$addonName}
	 3. public/templates/default/addons/{$addonNameSmall}, css,js,images
*/
/**ADDONS/BASIC */
//---------------/private/Addons/$addonName---------------
$folderAndFiles = array();
$readmeContent = <<<TEXT
# Project Title
### Author

### Contributors

## Synopsis
In few lines

## Description
One or two paragraph of project Description goes here

## Prerequisites
1. **List of all dependensies required**
2. **What all should be installed as prerequisites**
3. **How to prerequisites**

## Installation
Step By step installation guide
1. Create db
2. run dbSchema.sql
3. run installation.php
5. Edit Config files(DB, APIs etc)
6. Run `composer require osolutils/helpers` inside private/Core/composer

## Extending / Installing Addons

## Contributing
Issue Tracker: github.com/project/issues

## License / Copyright Info
Licence Information

## Citation
1. How this software can be cited
2. DOI(Digital Object Identifier) link/image

## Contact
Email addesses or Contact us links
TEXT;
$folderAndFiles["readme.md"] = $readmeContent;
$addonSQLDumpFileName = $addonName.".mysql.sql";
$folderAndFiles[$addonSQLDumpFileName] = "";
$folderAndFiles["Config.php"] = <<<TEXT
<?php
\$this->addonConfig = array();
?>
TEXT;
$contollerContent = <<<TEXT
<?php 

namespace OsolMVC\Addons\__ADD_ON_NAME__;


class Controller extends \OsolMVC\Addons\Basic\Controller
{
    protected \$addonName = '$addonName';
    public function deleteAddon()
    {
        \$doneBackupMessage = \$this->getGeneralAddonHelper()
                                    ->getSelectedLangClass(\$this->addonName)
                                    ->getLangText('DELETION_DISABLED_FOR_ADDON');
        \$message2Template = array("message" =>  \$doneBackupMessage . "({\$this->addonName})","message_type" => "Error");
        \$this->setMessage(\$message2Template);
        \$this->render();
    }
    
}
?>
TEXT;

$folderAndFiles["Controller.php"] = $contollerContent;
$folderAndFiles["Model.php"] = "";
$viewContent = <<<TEXT
<?php 
namespace OsolMVC\Addons\__ADD_ON_NAME__;

class View extends \OsolMVC\Addons\Basic\View
{
    public function showView()
	{
        
		parent::showView();
	}
}
?>
TEXT;
$folderAndFiles["View.php"] = $viewContent;
$helperContent = <<<TEXT
<?php 
namespace OsolMVC\Addons\__ADD_ON_NAME__;
class Helper  extends \OsolMVC\Addons\Basic\Helper{
    public function prependWithTestFunction(\$testWord)// sample helper function
    {
        return "Test function Called. ".\$testWord;
    }//public function testFunction(\$testWord)

    public function getModuleLinks():array
    {
        return array(array("link"=>"$addonName","link_text" => \$this->translate2Lang("$addonName")));
    }
}//class Helper  extends \OsolMVC\Core\CoreParent{
?>
TEXT;
$folderAndFiles["Helper.php"] = $helperContent;
$AddonFoldersAndFiles[$addonPrivateFolder] = $folderAndFiles;



$langClassContent = <<<TEXT
<?php
namespace OsolMVC\Addons\__ADD_ON_NAME__\Lang;
 class LangClassENUS extends \OsolMVC\Addons\Basic\Lang\LangClassENUS{
	protected \$ADDON_INFO = "This is $addonName";   
	protected \$$addonName = "$addonName"."TTT";   
    
    /*
    Usage example :
    \$message = \$this->getAddonHelper()->getSelectedLangClass(\$addonName)->getLangText('DONE_BACKUP');
    */
 }//class LangClassENUS extends extends \OsolMVC\Core\CoreParent{
?>
TEXT;
$folderAndFiles = array();
$folderAndFiles["LangClassENUS.php"] = $langClassContent;
$AddonFoldersAndFiles[$addonPrivateFolder."/Lang"] = $folderAndFiles;

//---------------/private/Addons/Basic/templates---------------
$folderAndFiles = array();
$AddonFoldersAndFiles[$addonPrivateFolder."/templates"] = $folderAndFiles;

//---------------/private/Addons/Basic/templates/default---------------
$folderAndFiles = array();
$mainhtmlContent = <<<TEXT
<?php
\$this->addCSSLinkTag(\$this->getTemplateFileURLOfAddon("css/main.css")); // sample code to add JS file

?>

<img src="<?php echo \$this->getTemplateFileURLOfAddon("images/sample.png","Basic");?>" /><br />
This is main page of <br />
<span class="osolMVCBasicAddonBold">$addonName Addon</span><br />
<?php echo \$this->getAddonHelper()->prependWithTestFunction("Helper Test Success");?>
TEXT;

$folderAndFiles["main.html"] = $mainhtmlContent;
$AddonFoldersAndFiles[$addonPrivateFolder."/templates/default"] = $folderAndFiles;

//---------------private/uploads/Addons/{$addonName}---------------
$folderAndFiles = array();
$AddonFoldersAndFiles["private/uploads/Addons/{$addonName}"] = $folderAndFiles;


//---------------/public/templates/default/addons/{$addonNameSmall}---------------
$folderAndFiles = array();
$AddonFoldersAndFiles[$addonPublicFolder] = $folderAndFiles;

//---------------/public/templates/default/addons/{$addonNameSmall}/css---------------
$folderAndFiles = array();
$mainCSSContent = <<<TEXT
.osolMVCBasicAddonBold{
    font-weight: bold;
    font-size:  22px;
}
TEXT;
$folderAndFiles["main.css"] = $mainCSSContent;
$AddonFoldersAndFiles[$addonPublicFolder.'/css'] = $folderAndFiles;

//---------------/public/templates/default/addons/{$addonNameSmall}/images---------------
$folderAndFiles = array();
//$folderAndFiles[] = "sample.png";
$AddonFoldersAndFiles[$addonPublicFolder.'/images'] = $folderAndFiles;

//---------------/public/templates/default/addons/basic/js---------------
$folderAndFiles = array();
$folderAndFiles["main.js"] = "";
$AddonFoldersAndFiles[$addonPublicFolder . '/js'] = $folderAndFiles;



///----------------------admin folders and files



//admin private folders, **lang file is common**


//lang folder is common 
//---------------/private/Addons/$addonName/Admin---------------
$adminfolderAndFiles = array();
$contollerContent = <<<TEXT
<?php 

namespace OsolMVC\Addons\__ADD_ON_NAME__;


class Controller extends \OsolMVC\Addons\Basic\Admin\Controller
{
    protected \$addonName = '$addonName';
    public function deleteAddon()
    {
        \$doneBackupMessage = \$this->getGeneralAddonHelper()
                                    ->getSelectedLangClass(\$this->addonName)
                                    ->getLangText('DELETION_DISABLED_FOR_ADDON');
        \$message2Template = array("message" =>  \$doneBackupMessage . "({\$this->addonName})","message_type" => "Error");
        \$this->setMessage(\$message2Template);
        \$this->render();
    }
	public function rendor()
	{
		echo "Hello this is {$addonName} admin page";
	}
    
}
?>
TEXT;

$adminfolderAndFiles["Controller.php"] = $contollerContent;
$AddonFoldersAndFiles[$addonAdminPrivateFolder] = $adminfolderAndFiles;
//---------------/private/Addons/{$addonName}/templates/default/Admin---------------
//---------------/private/uploads/Addons/{$addonName}/Admin---------------


// admin public folders


//---------------/public/templates/default/addons/{$addonNameSmall}/admin---------------
/* $folderAndFiles = array();
$AddonFoldersAndFiles[$addonPublicFolder] = $folderAndFiles; */

//---------------/public/templates/default/addons/{$addonNameSmall}/admin/css---------------
/* $folderAndFiles = array();
$mainCSSContent = <<<TEXT
.osolMVCBasicAddonBold{
    font-weight: bold;
    font-size:  22px;
}
TEXT;
$folderAndFiles["main.css"] = $mainCSSContent;
$AddonFoldersAndFiles[$addonPublicFolder.'/css'] = $folderAndFiles; */

//---------------/public/templates/default/addons/{$addonNameSmall}/admin/images---------------
//---------------/public/templates/default/addons/{$addonNameSmall}/admin/js---------------


?>