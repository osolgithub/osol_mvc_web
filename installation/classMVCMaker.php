<?php
/** 
 *  \namespace OSOLMVCMaker
 *  \brief     Sole namespace of this project.
 *  \details   This namespace is the root namespace.\n
 * Holds all classes &amp; subclasses for OSOL-MVC Maker.\n
 * This documentation is written in OSOLMVCMaker::classMVCMaker under *namespace* tag\n
 * And will be shown in Main Project &gt;&gt; Namespaces &gt;&gt; Namespaces List &gt;&gt; thisNamespacename 
 *  \author    Sreekanth Dayanand
 *  \author    [Outsource Online Internet Solutions](https://www.outsource-online.net)
 *  \copyright GNU Public License. 
 */
 
/*!
* \class OSOLMVCMaker::classMVCMaker

*  \brief called in installation.php(bootstrap file), to create a new instance of OSOL-MVC
*  \details  
 methods \b createProject OR \b createAddon are called on this depending on the \a $_GET['action'] value@n
 \par Note
 This class was initially working differently.@n
 After introduction of MVCFoldersAndFiles.php, the only <b> non redundant</b> methods are
 1. initiate()
 2. createAddon()
 3. createProject()
 4. makeDir() : private method

* @author
* Name: Sreekanth Dayanand, www.outsource-online.net
* Email: osolmvc@outsource-online.net
* Url: http://www.outsource-online.net
* ===================================================
* @copyright (C) 2023 Sreekanth Dayanand, Outsource Online (www.outsource-online.net). All rights reserved.
* @license see http://www.gnu.org/licenses/gpl-2.0.html  GNU/GPL.
* You can use, redistribute this file and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation.
* If you use this software as a part of own sofware, you must leave copyright notices intact or add OSOLMulticaptcha copyright notices to own.
*
*  @date 27th January 2023
*/

namespace OSOLMVCMaker;
class classMVCMaker{
	public $projectRoot = "projectBase/PR11";///< root folder of new MVC instance to be created
											///< Should be <b>relative path</b> w.r.t \b MVCMaker @n
											///< ie parent folder of installation.php
	
	public $createdAddonBaseFolder = "projectBase/AddonMade/";
	public $createdCoreFeatureBaseFolder = "projectBase/CoreComponentMade";
	//$PROJECT_ROOT_URI = "/pjt/PMUtilities/createProject/".$projectRoot."/";
	private $PROJECT_ROOT_URI = "";//dirname($_SERVER['REQUEST_URI'])."/".$projectRoot."/";
	//echo "PROJECT_ROOT_URI is $PROJECT_ROOT_URI <br />";
	private $PRIVATE_FOLDER_ROOT = "private";
	private $replaceVars = null;
	
	//variables for ListFolder() method
	private $childId =1;
	//$tree;
	private $totalFilesChanged =0;
	private $allFilesChanged = array();
	private $excludePaths = array("composer\\","index.html","..");
	private $search_only_if_matches = array();
	private $datepickerFrom = "10 Jun 2011 11:00:00";
	private $datepickerTo = "";
	private $datesUpdated = array();
	public $allFiles = array();
	public $allFilesSorted = array();
	private $allFilesFound = array();
	private $rootPath2Traverse = "";
	
	private $MVCFoldersAndFiles =  null;
	
	public $projectFiles =  null;///< \b redundant after \b $MVCFoldersAndFiles is loaded from MVCFoldersAndFiles.php.@n
								///<  was used in previous version where template folders and files were auto detected and created
	private $fullFolderPaths =  array();
	//refer https://www.giuseppemaccario.com/how-to-build-a-simple-php-mvc-framework/					
	private $projectFileContents =  array(
									//"f2" => 
									);
	public function __construct()
	{
		
		
	}//public function __construct()
	/**
	 *  @brief should be called before createProject Method.
	 *  
     *  @param [in] no input parameters
	 *  @return Return description
	 *  
	 *  @details 
		Sets all prerequisite variables to be rplaced in template files@n
		ie, derives 
		1. \b $this->PROJECT_ROOT_URI using `$_SERVER['REQUEST_URI']`
		2.  $this->replaceVars is first set here
		3. loads `MVCFoldersAndFiles.php` where all files and folders for new MVC instace are stored
		4. sets `$this->MVCFoldersAndFiles =  $MVCFoldersAndFiles`;
	 */
	public function initiate()
	{
		
		$this->PROJECT_ROOT_URI = "/" . $this->get_absolute_path(dirname($_SERVER['REQUEST_URI'])."/../")."/".$this->projectRoot."/";
		
		//echo $this->get_absolute_path(__DIR__.'/../../etc/passwd') . PHP_EOL;
		echo "<b>PROJECT_ROOT_URI</b> is ". $this->PROJECT_ROOT_URI."</br>\r\n";
		$this->replaceVars = array(
								"__PRIVATE_FOLDER_ROOT__" => $this->PRIVATE_FOLDER_ROOT,
								"__PROJECT_ROOT_URI__" => $this->PROJECT_ROOT_URI,
								"__PROJECT_ROOT_URI_WITH_SERVER__" => $this->getFullURL2Root()
								);
		/* require_once(__DIR__ . "/MVCFoldersAndFiles.php");
		$this->MVCFoldersAndFiles =  $MVCFoldersAndFiles; */
		return $this;
	}//public function initiate()
	/*
	Function equivalent to realpath().
	 for realpath(), The running script must have executable permissions on all directories in the hierarchy, otherwise realpath() will return FALSE.
	https://www.php.net/manual/en/function.realpath.php#84012
	*/
	function get_absolute_path($path, $dirSeperatorOf = 'LINUX') {
		$dirSeperator2Apply = DIRECTORY_SEPARATOR;
		switch($dirSeperatorOf)
		{
			case 'LINUX':
				$dirSeperator2Apply = '/';
				break;
			case 'WINDOWS':
				$dirSeperator2Apply = '\\';
				break;
			
		}//switch($dirSeperatorOf)
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        //return implode(DIRECTORY_SEPARATOR, $absolutes);
        return implode($dirSeperator2Apply, $absolutes);
    }
	/**
     *  @brief gets root url of new MVC instance $this->PROJECT_ROOT_URI
     *
     *  @param [in] no input parameters
     *  @return String full url to root of new MVC instance 
     *  @author Sreekanth Dayanand
     *  @date 23rd June 2022
     *  @details 
		uses $_SERVER['HTTPS'],$_SERVER['HTTP_HOST'] &amp; $this->PROJECT_ROOT_URI which in turn uses $_SERVER['REQUEST_URI']
        @n      
        \par Called in      
        1. $this->initiate()
     */
	public function getFullURL2Root()
	{
		// Program to display URL of current page.
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
			$link = "https";
		else $link = "http";
		  
		// Here append the common URL characters.
		$link .= "://";
		  
		// Append the host(domain name, ip) to the URL.
		$link .= $_SERVER['HTTP_HOST'];
		  
		// Append the requested resource location to the URL
		$link .= $this->PROJECT_ROOT_URI;//$_SERVER['REQUEST_URI'];
		  
		// Print the link
		return $link;
	}//public function getFullURL2Root()
	private function getAllFilesList($path,$parentID=0, $relativePath = false, $foldersOnly = false)
	{
		
		$DS =  DIRECTORY_SEPARATOR ;
		if($parentID == 0)
		{
			$this->allFilesFound = array();
			$this->rootPath2Traverse = $path;
		}//if($parentID == 0)
		$childId = 0;
		$dir_handle = @opendir($path) or die("Unable to open $path");
		$filenames = array();
		$foldernames = array();
		while (false !== ($file = readdir($dir_handle)))
		{ 
			$fullFilePath = $path.$DS.$file;
			if (is_dir($fullFilePath))
			{
				$foldernames[] = $file; 
				sort($foldernames); 
				$childId++;
				if($file!="." && $file!="..")
				{
					$this->getAllFilesList($fullFilePath, $childId, $relativePath, $foldersOnly );
				}//if($file!="." && $file!="..")
			}
			else
			{
				$filenames[] = $file; 
				sort($filenames); 
			}
		} 
		$fileArrayKey = $path;
		if($relativePath)
		{
			$substrStart =  strlen($this->rootPath2Traverse);
			$fileArrayKey = substr($fileArrayKey,$substrStart);
		}//if($relativePath)
		if($foldersOnly)
		{
			$this->allFilesFound[$fileArrayKey] =   array();
		}
		else
		{
			$this->allFilesFound[$fileArrayKey] =   $filenames;
		}//if($foldersOnly)
		
		//$sortedfilenames = array_merge($foldernames,$filenames);
		closedir($dir_handle);
	}//private function getAllFilesList($path,$parentID=0)
	public function getAllFilesListSorted($path , $relativePath = false, $foldersOnly = false)
	{
		
		$this->getAllFilesList($path, 0 , $relativePath, $foldersOnly);
		ksort($this->allFilesFound);
		
		
		return $this->allFilesFound;
		
	}//public function getAllFilesListSorted()
	
	/**
     *  @brief Method to create new addon with the name sent via $_GET['coreFeatureName']
     *
     *  @param [in] no input parameters
     *  @return void 
     *  @author Sreekanth Dayanand
     *  @date 23rd June 2022
     *  @details
		New addon dummy is created in \b $addonBaseFolder folder set in `AddonFoldersAndFiles.php`
        @n      
        \par Called in      
        1. installation.php when $_GET['action'] =="createCoreFeature"  
        \par Uses Methods
        calls 
		1. $this->makeDir
     */
	public function createCoreFeature()
	{
		//replace __COREFEATURE_NAME__ inside files with $addonName
		//replace __COREFEATURE_NAME_LOWERCASE__ inside files with $addonNameSmall ie strtolower($addonName)
		//replace AddonSample in pathnames with $addonName
		//replace addonsample in pathnames with $addonNameSmall ie strtolower($addonName)
		
		$coreFeatureName = (isset($_GET['coreFeatureName']) && ctype_alnum($_GET['coreFeatureName'])) ? $_GET['coreFeatureName'] : "";
		if($coreFeatureName == "")
		{
			die("CoreFeature Name name must contain only alphabets or numbers");
		}//if($coreFeatureName == "")
			$this->createSpecifiedCoreFeature($coreFeatureName);
	}
	public function createCoreFeatureFromConfig($configFullPath)
	{
		require_once($configFullPath);
		$coreFeatureName = $coreFeatureArguments['CORE_FEATURE_NAME'];
		$this->createSpecifiedCoreFeature($coreFeatureName);
	}//public function createCoreFeatureFromConfig($configFullPath)
	public function createSpecifiedCoreFeature($coreFeatureName)
	{
		$coreFeatureBaseFolder = $this->createdCoreFeatureBaseFolder ."/". $coreFeatureName;
		//$coreFeatureBaseFolder = $this->projectRoot;
		$coreFeatureNameSmall = strtolower($coreFeatureName);
		
		//require_once(__DIR__ . "/AddonFoldersAndFiles.php");
		
		$coreFeatureSourceRoot = realpath( __DIR__."/CoreFeatureContents");
		$coreFeatureBaseFullPath = realpath( __DIR__."/../../");
		$CoreFeatureFoldersAndFiles = $this->getAllFilesListSorted($coreFeatureSourceRoot,true);
		foreach($CoreFeatureFoldersAndFiles as $CoreFeatureFolder => $CoreFeatureFiles)
		{
			$CoreFeatureFolderFullPath = $coreFeatureBaseFullPath. "/". $coreFeatureBaseFolder;
			if($CoreFeatureFolder !="")
			{
			
				 $CoreFeatureFolderFullPath .= $CoreFeatureFolder;// starts with '/'
				 $CoreFeatureFolderFullPath = str_replace("CoreFeature", $coreFeatureName,$CoreFeatureFolderFullPath);
				 $CoreFeatureFolderFullPath = str_replace("corefeature", $coreFeatureNameSmall,$CoreFeatureFolderFullPath);
				 echo  "Creating Folder {$CoreFeatureFolderFullPath}<br />";
				 
				 $this->makeDir($CoreFeatureFolderFullPath);
			}//if($CoreFeatureFolder !="")
			//foreach($CoreFeatureFiles as $CoreFeatureFile => $CoreFeatureFileContent)
			foreach($CoreFeatureFiles as  $CoreFeatureFile)
			{
				$TargetCoreFeatureFile = str_replace("CoreFeature", $coreFeatureName,$CoreFeatureFile);
				$TargetCoreFeatureFile = str_replace("corefeature", $coreFeatureNameSmall,$TargetCoreFeatureFile);
				$CoreFeatureFileSourceContent = $coreFeatureSourceRoot ."/" . $CoreFeatureFolder. "/" . $CoreFeatureFile;
				$CoreFeatureFileContent = file_get_contents($CoreFeatureFileSourceContent);
				$CoreFeatureFileFullPath = $CoreFeatureFolderFullPath . "/" . $TargetCoreFeatureFile;

				echo  "Creating File {$CoreFeatureFileFullPath}<br />";
				$fileContent = str_replace("__COREFEATURE_NAME__", $coreFeatureName, $CoreFeatureFileContent);
				$fileContent = str_replace("__COREFEATURE_NAME_LOWERCASE__", $coreFeatureNameSmall, $fileContent);
				file_put_contents($CoreFeatureFileFullPath,$fileContent);
			}//foreach($CoreFeatureFiles as $CoreFeatureFile => $CoreFeatureFileContent)
		}//foreach($CoreFeatureFoldersAndFiles as $MVCFolder => $MVCFiles)
		echo "Done !!!. Created CoreFeature {$coreFeatureName}<br />";
	}//public function createCoreFeature()
	
	/**
     *  @brief Method to create new addon with the name sent via $_GET['addonName']
     *
     *  @param [in] no input parameters
     *  @return void 
     *  @author Sreekanth Dayanand
     *  @date 23rd June 2022
     *  @details
		New addon dummy is created in \b $addonBaseFolder folder set in `AddonFoldersAndFiles.php`
        @n      
        \par Called in      
        1. installation.php when $_GET['action'] =="createAddon"  
        \par Uses Methods
        calls 
		1. $this->makeDir
     */
	public function createAddon()
	{
		//replace __ADDON_NAME__ inside files with $addonName
		//replace __ADDON_NAME_LOWERCASE__ inside files with $addonNameSmall ie strtolower($addonName)
		//replace AddonSample in pathnames with $addonName
		//replace addonsample in pathnames with $addonNameSmall ie strtolower($addonName)
		
		$addonName = (isset($_GET['addonName']) && ctype_alnum($_GET['addonName'])) ? $_GET['addonName'] : "";
		if($addonName == "")
		{
			die("Addon name must contain only alphabets or numbers");
		}//if($addonName == "");
		$this->createSpecifiedAddon($addonName);
	}//public function createAddon()
	public function createAddonFromConfig($configFullPath)
	{
		require_once($configFullPath);
		$addonName = $addonArguments['ADDON_NAME'];
		//die("addon name is ". $addonName);
		$this->createSpecifiedAddon($addonName);
	}//public function createAddonFromConfig()
	public function createSpecifiedAddon($addonName)
	{
		$addonBaseFolder = $this->createdAddonBaseFolder ."/". $addonName;
		//$addonBaseFolder = $this->projectRoot;
		$addonNameSmall = strtolower($addonName);
		
		//require_once(__DIR__ . "/AddonFoldersAndFiles.php");
		
		$addonSourceRoot = realpath( __DIR__."/AddonContents");
		$addonBaseFullPath = realpath( __DIR__."/../../");
		$AddonFoldersAndFiles = $this->getAllFilesListSorted($addonSourceRoot,true);
		foreach($AddonFoldersAndFiles as $AddonFolder => $AddonFiles)
		{
			$AddonFolderFullPath = $addonBaseFullPath. "/". $addonBaseFolder;
			if($AddonFolder !="")
			{
			
				 $AddonFolderFullPath .= $AddonFolder;// starts with '/'
				 $AddonFolderFullPath = str_replace("AddonSample", $addonName,$AddonFolderFullPath);
				 $AddonFolderFullPath = str_replace("addonsample", $addonNameSmall,$AddonFolderFullPath);
				 echo  "Creating Folder {$AddonFolderFullPath}<br />";
				 
				 $this->makeDir($AddonFolderFullPath);
			}//if($AddonFolder !="")
			//foreach($AddonFiles as $AddonFile => $AddonFileContent)
			foreach($AddonFiles as  $AddonFile)
			{
				
				$AddonFileSourceContent = $addonSourceRoot ."/" . $AddonFolder. "/" . $AddonFile;
				$AddonFileContent = file_get_contents($AddonFileSourceContent);
				$AddonFileFullPath = $AddonFolderFullPath . "/" . $AddonFile;

				echo  "Creating File {$AddonFileFullPath}<br />";
				$fileContent = str_replace("__ADDON_NAME__", $addonName, $AddonFileContent);
				$fileContent = str_replace("__ADDON_NAME_LOWERCASE__", $addonNameSmall, $fileContent);
				file_put_contents($AddonFileFullPath,$fileContent);
			}//foreach($AddonFiles as $AddonFile => $AddonFileContent)
		}//foreach($AddonFoldersAndFiles as $MVCFolder => $MVCFiles)
		echo "Done !!!. Created Addon {$addonName}<br />";
	}//public function createSpecifiedAddon($addonName)
	/**
     *  @brief Method to create new MVC instance 
     *
     *  @param [in] no input parameters
     *  @return void 
     *  @author Sreekanth Dayanand
     *  @date 23rd June 2022
     *  @details
		New new MVC instance is created in \b $this->projectRoot folder 
        @n      
        \par Called in      
        1. installation.php when $_GET['action'] =="createMVC"  
        \par Uses Methods
        calls 
		1. $this->makeDir
     */
	public function createProject()
	{
		
		$MVCSourceRoot = realpath( __DIR__."/MVCContents");		
		$this->MVCFoldersAndFiles = $this->getAllFilesListSorted($MVCSourceRoot,true);
		
		
		$searchVars =  array_keys($this->replaceVars);
		$replaceVars =  array_values($this->replaceVars);
		foreach($this->MVCFoldersAndFiles as $folderPath => $files)
		{
			$MVCFolderFullPath = classMVCMaker::getProjectRootPath($this->projectRoot). $folderPath; 
			$installationFolderFullPath = __DIR__."/MVCContents". $folderPath; 
			echo  "Creating Folder {$MVCFolderFullPath}<br />";
			$this->makeDir($MVCFolderFullPath);
			foreach($files as $file)
			{
				$MVCFile = $MVCFolderFullPath."/".$file;
				$installationFile = $installationFolderFullPath."/".$file;
				echo  "Adding File {$installationFile}<br />to <br />{$MVCFile}<br />";
				$fileContent = str_replace($searchVars, $replaceVars, file_get_contents($installationFile));
				file_put_contents($MVCFile,$fileContent);
			}//foreach($files as $file)
		}//foreach($MVCFoldersAndFiles as $folderPath = > $files)
		
	}//public function createProject()
	public static function getProjectRootPath($projectRoot)
	{
		return __DIR__."/../../".$projectRoot;
	}//public static function getProjectRootPath($projectRoot)
	
	public function createProjectFromConfig($configFullPath)
	{
		require_once($configFullPath);
		$this->projectRoot = $mvcArguments['PROJECT_PATH'];
		$this->initiate();
		$this->replaceVars["__PRIVATE_FOLDER_ROOT__"] = $mvcArguments['PRIVATE_FOLDER_ROOT'];
		$this->createProject();
		// edit DB Settings
		$dbConfigFileFullPath = classMVCMaker::getProjectRootPath($this->projectRoot)."/private/Core/Config/DBConfig.php";
		$dbConfig =  $mvcArguments['DB_SETTINGS'];
		$configArrayAsString =  array_map(function($key, $val){
												$booleanFields = ["log_queries"];
												return "\"{$key}\" => ".\OSOLMVCMaker\classMVCMaker::formatVarForConfig($key,$val,$booleanFields).",\r\n".str_repeat("\t",5);
											},
											 array_keys($dbConfig),$dbConfig);
		$dbSettingsConfigAsString = "<?php\r\n\$this->dbSettings = array(%s);\r\n?>";
		$dbSettingsConfigAsString2Replace = sprintf($dbSettingsConfigAsString,join("",$configArrayAsString));
		echo "DB Settings is <pre>". print_r($dbSettingsConfigAsString2Replace,true)."</pre><hr />";
		file_put_contents($dbConfigFileFullPath,$dbSettingsConfigAsString2Replace);
		// edit siteSettings
		$siteConfigFileFullPath = classMVCMaker::getProjectRootPath($this->projectRoot)."/private/Core/Config/SiteConfig.php";
		$siteConfig =  $mvcArguments['siteSettings'];
		$configArrayAsString =  array_map(function($key, $val){
												$booleanFields = ["'autoRegistrationEnabled'"];
												return "\"{$key}\" => ".\OSOLMVCMaker\classMVCMaker::formatVarForConfig($key,$val,$booleanFields).",\r\n".str_repeat("\t",5);
											},
											 array_keys($siteConfig),$siteConfig);
		$siteSettingsConfigAsString = "<?php\r\n\$this->siteSettings = array(%s);\r\n?>";
		$siteSettingsConfigAsString2Replace = sprintf($siteSettingsConfigAsString,join("",$configArrayAsString));
		echo "Site Settings is <pre>". print_r($siteSettingsConfigAsString2Replace,true)."</pre><hr />";
		file_put_contents($siteConfigFileFullPath,$siteSettingsConfigAsString2Replace);
		// edit sessionSettings
		$sessionConfigFileFullPath = classMVCMaker::getProjectRootPath($this->projectRoot)."/private/Core/Config/SessionConfig.php";
		$sessionConfig =  $mvcArguments['sessionSettings'];
		$configArrayAsString =  array_map(function($key, $val){
												$booleanFields = [];
												return "\"{$key}\" => ".\OSOLMVCMaker\classMVCMaker::formatVarForConfig($key,$val,$booleanFields).",\r\n".str_repeat("\t",5);
											},
											 array_keys($sessionConfig),$sessionConfig);
		$sessionSettingsConfigAsString = "<?php\r\n\$this->sessionSettings = array(%s);\r\n?>";
		$sessionSettingsConfigAsString2Replace = sprintf($sessionSettingsConfigAsString,join("",$configArrayAsString));
		echo "Session Settings is <pre>". print_r($sessionSettingsConfigAsString2Replace,true)."</pre><hr />";
		file_put_contents($sessionConfigFileFullPath,$sessionSettingsConfigAsString2Replace);
		// edit smtpSettings
		$emailConfigFileFullPath = classMVCMaker::getProjectRootPath($this->projectRoot)."/private/Core/Config/EmailConfig.php";
		$emailConfig =  $mvcArguments['smtpSettings'];
		$configArrayAsString =  array_map(function($key, $val){
												$booleanFields = [];
												return "\"{$key}\" => ".\OSOLMVCMaker\classMVCMaker::formatVarForConfig($key,$val,$booleanFields).",\r\n".str_repeat("\t",5);
											},
											 array_keys($emailConfig),$emailConfig);
		$emailSettingsConfigAsString = "<?php\r\n\$this->smtpSettings = array(%s);\r\n?>";
		$emailSettingsConfigAsString2Replace = sprintf($emailSettingsConfigAsString,join("",$configArrayAsString));
		echo "Email Settings is <pre>". print_r($emailSettingsConfigAsString2Replace,true)."</pre><hr />";
		file_put_contents($emailConfigFileFullPath,$emailSettingsConfigAsString2Replace);
		// edit googleAppSettings
		$googleAppConfigFileFullPath = classMVCMaker::getProjectRootPath($this->projectRoot)."/private/Core/Config/GoogleAppConfig.php";
		$googleAppConfig =  $mvcArguments['googleAppSettings'];
		$configArrayAsString =  array_map(function($key, $val){
												$booleanFields = [];
												$returnVal = "\"{$key}\" => ".\OSOLMVCMaker\classMVCMaker::formatVarForConfig($key,$val,$booleanFields).",\r\n".str_repeat("\t",5);
												return $returnVal;
											},
											 array_keys($googleAppConfig),$googleAppConfig);
		$googleAppSettingsConfigAsString = "<?php\r\n\$this->googleAppSettings = array(%s);\r\n?>";
		$googleAppSettingsConfigAsString2Replace = sprintf($googleAppSettingsConfigAsString,join("",$configArrayAsString));
		$googleAppSettingsConfigAsString2Replace = str_replace("\"redirectURL\" => ","\"redirectURL\" => \$this->getFullURL2Root() .", $googleAppSettingsConfigAsString2Replace );
		echo "GoogleApp Settings is <pre>". print_r($googleAppSettingsConfigAsString2Replace,true)."</pre><hr />";		
		file_put_contents($googleAppConfigFileFullPath,$googleAppSettingsConfigAsString2Replace);
		// edit facebookAppSettings
		$facebookAppConfigFileFullPath = classMVCMaker::getProjectRootPath($this->projectRoot)."/private/Core/Config/FacebookAppConfig.php";
		$facebookAppConfig =  $mvcArguments['facebookAppSettings'];
		$configArrayAsString =  array_map(function($key, $val){
												$booleanFields = [];
												$returnVal = "\"{$key}\" => ".\OSOLMVCMaker\classMVCMaker::formatVarForConfig($key,$val,$booleanFields).",\r\n".str_repeat("\t",5);
												return $returnVal;
											},
											 array_keys($facebookAppConfig),$facebookAppConfig);
		$facebookAppSettingsConfigAsString = "<?php\r\n\$this->facebookAppSettings = array(%s);\r\n?>";
		$facebookAppSettingsConfigAsString2Replace = sprintf($facebookAppSettingsConfigAsString,join("",$configArrayAsString));
		$facebookAppSettingsConfigAsString2Replace = str_replace("\"OAuthRedirectURI\" => ","\"OAuthRedirectURI\" => \$this->getFullURL2Root() .", $facebookAppSettingsConfigAsString2Replace );
		echo "FacebookApp Settings is <pre>". print_r($facebookAppSettingsConfigAsString2Replace,true)."</pre><hr />";		
		file_put_contents($facebookAppConfigFileFullPath,$facebookAppSettingsConfigAsString2Replace);
		
	}//public function createProjectFromConfig($configFullPath)
	public static function formatVarForConfig($var,$val,$booleanFields = [])
	{
		if(in_array($var, $booleanFields))
		{
			return ($val?"true":"false");
		}
		else //if(in_array($var,$booleanFields))
		{
			
			return '"'.addslashes($val).'"';
		}//if(in_array($var,$booleanFields))
	}//public static function formatVarForConfig($label)
	/**TEMPORATY METHODS FOR SWITHCHING TO NEW FORMAT STARTS HERE**/
	public static function strContainsArrayVal($str, array $arr)
	{
		foreach($arr as $a) {
			//echo $str." , ". $a . " is ".(stripos($str,$a) !== false)."<br />";
			if (stripos($str,$a) !== false) return true;
		}
		return false;
	}//public static function contains($str, array $arr)
	private function getFolderTrees()
	{
		
		//foreach($this->fullFolderPaths as $fullFolderPath)
		$requiredFolders = array_filter($this->fullFolderPaths,function($val){
			$getFolderTeesOf = array("private/Core/","public/templates/");
			return classCreateProject::strContainsArrayVal($val, $getFolderTeesOf);
		});
		//echo "<pre>".print_r($requiredFolders,true)."</pre>";
		return $requiredFolders;
		
	}//private function getFolderTrees()
	
	private function saveInstallFilesInAppropriatePath($folderFullPath,$folderFullPathKey)
	{
		$files2Add = array_filter($this->projectFiles, function($fileDetails)use($folderFullPathKey){
										return ($fileDetails['parent'] == $folderFullPathKey);
										});
										
		array_walk($files2Add,function($projectFileDetails, $key, $fpath){
			$file2Create = $fpath."/".$projectFileDetails['name'];
			$contentPath = "projectContents/".$projectFileDetails['content'];
			echo "adding contents from ".$contentPath."<br />";
			$fileContents = file_get_contents($contentPath);
			file_put_contents($file2Create,$fileContents);
		},$folderFullPath);
	}//private function makeDir2($folderFullPath,$folderFullPathKey)
	
	/**TEMPORATY METHODS FOR SWITHCHING TO NEW FORMAT ENDS HERE**/
	/**
     *  @brief creates folder if does not already exist
     *
     *  @param [in] String $folderFullPath
     *  @return void 
     *  @author Sreekanth Dayanand
     *  @date 23rd June 2022
     *  @details
        used to create new folders for createProject &amp createAddon @n      
        \par Called in      
        1. createProject
		2. createAddon
        \par Uses Methods
        no other custom methods/function used
     */
	private function makeDir($folderFullPath){/* }
	private function makeDir2($folderFullPath,$folderFullPathKey)
	{ */
		//return ;//
		if (!file_exists($folderFullPath)) 
		{
			
			mkdir($folderFullPath, 0755, true);
			file_put_contents($folderFullPath."/index.html","");
			
			
			//$this->saveInstallFilesInAppropriatePath($folderFullPath,$folderFullPathKey);
			
		}
	}//private function makeDir($fullPath)
	
	private function getPathRelative2ProjectRoot($folderPath)
	{
		$projectRootLength = strlen($this->projectRoot);
		return substr($folderPath,($projectRootLength));
	}//private function getPathRelative2ProjectRoot($folderPath)
	private function createFolders()
	{
		echo "<h2>Creating Folders</h2>";
		//Create projectBase first
		//$this->makeDir('projectBase');
		
		//Create projectRoot 
		$this->makeDir($this->projectRoot);
		
		$createdFolders = array();
		$this->fullFolderPaths[""] =  $this->projectRoot;
		$createdFolderNo = 0;
		foreach($this->projectFolders as $projectFolderDetails)
		{
			$folderID = $projectFolderDetails['id'];
			$createdFolders[$folderID] = $projectFolderDetails;
			$folderParentId = $projectFolderDetails['parent'];
			$folderName = $projectFolderDetails['name'];
			$folderFullPath = $folderName;
				while($folderParentId != "")
				{
					
					$folderFullPath = $createdFolders[$folderParentId]['name']."/".$folderFullPath;
					
					$folderParentId = $createdFolders[$folderParentId]['parent'];
				}//while(true)
				$folderFullPath =  $this->projectRoot."/".$folderFullPath;
			$createdFolderNo++;
			echo $createdFolderNo . ". Creating ". $folderFullPath. "<br />";
			$this->fullFolderPaths[$folderID] = $folderFullPath;
			$this->makeDir($folderFullPath);
		}//foreach($projectFolders as $projectFolder)
		
	}//private function createFolders()
	
		
	

	function ListFolder($path,$parentID=0)
	{
		//global $tree,$childId;
		//global $totalFilesChanged,$allFilesChanged ,$excludePaths,$search_only_if_matches,$datepickerFrom,$datepickerTo,$datesUpdated;
		//using the opendir function
		
	/*	private $childId =1;
	//$tree;
	private $totalFilesChanged =0;
	private $allFilesChanged = array();
	private $excludePaths = array();
	private $search_only_if_matches = array();
	private $datepickerFrom = "10 Jun 2011 11:00:00";
	private $datepickerTo = "";
	private $datesUpdated = array(); */
	
	
		$dir_handle = @opendir($path) or die("Unable to open $path");
	   // $childId++;
		//Leave only the lastest folder name
		$dirArray = explode(DIRECTORY_SEPARATOR, $path);
		$dirname = end($dirArray);
		if($parentID==0)
		{
			//$tree->addToArray($childId,$dirname,$parentID,"","frmMain");
			$parentID = $this->childId;
		}
		//display the target folder.
	   // echo ("<li>$dirname\n");
		//echo "<ul>\n";
		$filenames = array();
		$foldernames = array();
		while (false !== ($file = readdir($dir_handle)))
		{ 
			$fullFilePath = $path.DIRECTORY_SEPARATOR.$file;
			if(!$this->strContainsArrayVal($fullFilePath, $this->excludePaths) && substr($fullFilePath,-1) !=".")
			{
				$this->allFiles[] = $fullFilePath;				
			}//if(!$this->strContainsArrayVal($fullFilePath, $this->excludePaths))
			if (is_dir($fullFilePath))
			{
				$foldernames[] = $file; 
				sort($foldernames); 
			}
			else
			{
				$filenames[] = $file; 
				sort($filenames); 
			}
		} 
		$sortedfilenames = array_merge($foldernames,$filenames);
		//$this->allFiles = array_merge($this->allFiles,$sortedfilenames);
		
		foreach($sortedfilenames as $file) 
		//while (false !== ($file = readdir($dir_handle)))
		{
			$fullFilePath = $path.DIRECTORY_SEPARATOR.$file;
			$this->childId++;
			if($file!="." && $file!="..")
			{
				if(!is_dir($fullFilePath))$fileModifiedTime = filemtime($fullFilePath);
				if (is_dir($fullFilePath))
				{
					$excludeFile =  false;
					foreach($this->excludePaths as $excludePath)
					{
						if(strstr($fullFilePath,$excludePath))$excludeFile = true;
					}
					
					
					if(!$excludeFile)
					{
						//Display a list of sub folders.
						//$tree->addToArray($childId,$file,$parentID,"","frmMain");
						//$this->ListFolder($fullFilePath, $this->childId );
						$sortedfilenames2 = $this->ListFolder($fullFilePath, $this->childId );
						$sortedfilenames = array_merge($sortedfilenames,$sortedfilenames2);
					}//if(!$excludeFile)
					
					
				}//if (is_dir($fullFilePath))
				elseif(($this->datepickerFrom == '' || ($this->datepickerFrom !='' && $fileModifiedTime > strtotime($this->datepickerFrom))) &&
						($this->datepickerTo =='' || ($this->datepickerTo !='' && $fileModifiedTime < strtotime($this->datepickerTo)))
						)
				{
					
					//Display a list of files.
				   // echo "<li>$file</li>";
				   //$tree->addToArray($childId,$file,$parentID,"","frmMain","images/dhtmlgoodies_sheet.gif");
						$excludeFile =  false;
						foreach($this->excludePaths as $excludePath)
						{
							if(strstr($fullFilePath,$excludePath))$excludeFile = true;
						}
						if(!$excludeFile && count($this->search_only_if_matches) )
						{
							foreach($this->search_only_if_matches as $search_only_if_match)
							{
								
								if(!strstr(strtolower($fullFilePath),strtolower($this->search_only_if_match)))$excludeFile = true;
							}//foreach($search_only_if_matches as $search_only_if_match)
							
						}//if(!$excludeFile)
						if(!$excludeFile)
						{
							$this->allFilesChanged[] = $fullFilePath."(Changed at ".date("d-m-Y h:i:s",filemtime($fullFilePath)).")";
							//$tree->addToArray($childId,$file,$parentID,$fullFilePath,"","images/dhtmlgoodies_sheet.gif");
							$this->totalFilesChanged++;
							//$fileModifiedDate = date("D M d Y",$fileModifiedTime);
							date_default_timezone_set('Asia/Kolkata');
							$fileModifiedDate = strtotime(date("D M d Y",$fileModifiedTime));
							if(!isset($this->datesUpdated[$fileModifiedDate]))
							{
								$this->datesUpdated[$fileModifiedDate] = array($fullFilePath);
							}
							else
							{
								$this->datesUpdated[$fileModifiedDate][] = $fullFilePath ;
							}
							$this->allFiles[] = $fullFilePath;
						}
				   
				}//if (is_dir($fullFilePath))
			}
		}
	   // echo "</ul>\n";
		//echo "</li>\n";
	   
		//closing the directory
		closedir($dir_handle);
		/* $this->allFiles = array_unique($this->allFiles);
		sort($this->allFiles); */
		$this->allFiles = $this->getUniqueAndOrderedArray($this->allFiles);
		$sortedfilenames = $this->getUniqueAndOrderedArray($sortedfilenames);
		return $sortedfilenames;
	}//function ListFolder($path,$parentID=0)
	public function getUniqueAndOrderedArray($array)
	{
		$array = array_unique($array);
		sort($array);
		return $array;
	}//public function getUniqyeAndOrderedArray($array)
	public function getAllFilesSorted($allFiles)
	{
		$allFilesSorted = array();
		$relativeFile ="";
		$dir2Fetch  = __DIR__.DIRECTORY_SEPARATOR.$this->projectRoot;
		foreach($allFiles as $file)
		{
			
			//$relativePath = str_replace($dir2Fetch,"",$file);
			if(is_dir($file))
			{
				$relativeFile = str_replace(DIRECTORY_SEPARATOR,"/",str_replace($dir2Fetch,"",$file));
				//echo $file.is_dir($file)."<br />";
				$allFilesSorted[$relativeFile] = array();
			}
			else{
				$dirpath = dirname($file);
				$filename = basename($file);
				$relativeFilePath = str_replace(DIRECTORY_SEPARATOR,"/",str_replace($dir2Fetch,"",$dirpath));
				$allFilesSorted[$relativeFilePath][] =$filename;
			}//if(is_dir)
		}//foreach($this->allFiles as $file)
		
		return $allFilesSorted;
	}//public function getAllFilesSorted()
	public function getArrayAsString($array)
	{
		$arrayString =  "\$MVCFoldersAndFiles =  array();<br />";
		foreach($array as $key => $filesArray)
		{
			$relativeFolder = $key !=""?$key:"root folder";
			$arrayString .= "<br />//".str_repeat("-",15).$relativeFolder.str_repeat("-",15)."<br />\$folderAndFiles =  array();<br />";
			foreach($filesArray as $file)
			{
				
				$arrayString .= "\$folderAndFiles[] =  '{$file}';<br />";
			}//foreach($filesArray as $file)
			$arrayString .= "\$MVCFoldersAndFiles['{$key}'] =  \$folderAndFiles;<br />";
		}//foreach($array as $key => $filesArray)
		return $arrayString;
	}//public function getArrayAsString($array)
	
}//class classCreateProject{
?>