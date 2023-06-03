<?php
/**
@mainpage OSOL-MVC Maker
Creates an instance of OSOL-MVC
@par Why OSOL-MVC Maker
This project was developed as part of [Outsource Online Interenet Solutions's](https://www.outsource-online.net/) with the following objectives.
 
1. To develop an MVC frameword of its own
2. Simple, light weight and well documented and 
3. Free of some one else's copy right, so that in case some properietary solutions are required, this could be used as its base 

@par Requirements
PHP GD Library must be available in the server
safe mode must be turned off
The above requirements are default settings in most PHP hosts.however if the captcha isnt showing up you need to check those settings   
@date 24th January 2023
@copyright {This project is released under the GNU Public License.}
@author Sreekanth Dayanand
@note This project basically creates 
@par Git Info
[Git](https://bitbucket.org/hariyom/mvcmakerupgraded/src/master/)@n
[altasian bitbucket documentation site tips](https://support.atlassian.com/bitbucket-cloud/docs/publishing-a-website-on-bitbucket-cloud/)@n
[documentation](https://hariyom.bitbucket.io/MVCMaker/html/)@n
[documentation repo](https://bitbucket.org/hariyom/hariyom.bitbucket.io/src/main/)@n
*/

//http://localhost/pjt/PMUtilities/MVCMaker/installation.php
//http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createAddon
//http://localhost/pjt/PMUtilities/projectBase/PR11/
//https://modestoffers.com/DEMOsites/OSOLMVC/
//https://bitbucket.org/hariyom/mvcmakerupgraded.git
/**
 *  @file installation.php
 *  @brief This is the starting script to create a new MVC instance
 */
//http://sreelp/pjt/MyContributions/osolcontributions/quickfileexplorer/master/?folder_to_select=E%3A%5Cprojects%5CPMUtilities%5CMVCMaker%5C&datepickerFrom=&datepickerTo=&exclude_paths=cache%2Clog%2CThumbs.db%2Csvn%2Czip%2CprojectBase%2C.git%2Cindex.html&search_only_if_matches=

// cue from https://www.giuseppemaccario.com/how-to-build-a-simple-php-mvc-framework/
// Define Paths
// create folders
// create files
// load contents in files
// device algorithm to create new feature/component


require_once("installation/classMVCMaker.php");
//Step 2 : create folders
// Step 3 : Create files
$clsCreatePjt =  new \OSOLMVCMaker\classMVCMaker();///< instance of OSOLMVCMaker::classMVCMaker@n
									 ///< methods \b createProject OR \b createAddon are called on this depending on the \a $_GET['action'] value
$actionRequested = "";///< could be \b createProject OR \b createAddon \n
					  ///<  extracted from \a $_GET['action']
if(isset($_GET['action']) && $_GET['action'] != "")
{
	$actionRequested = $_GET['action'];
	
}//if(isset($_GET['action']) && $_GET['action'] =="createAddon")
switch($actionRequested)
{
	case "createAddon":
		$clsCreatePjt->createAddon();
		break;
	case "createMVC":		
		$clsCreatePjt->initiate()
					->createProject();
		break;
	case "createCoreFeature":
	// should be called as http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createCoreFeature&coreFeatureName=ACL
		/* echo "Feature yet to implement.<br />\n
		Following classes/files are essential for a Core Feature or Addon<br />
			<ol>
				<li>Controller</li>
				<li>View : Core\View\FeatureView and Core\View\Admin\FeatureView </li>
				<li>Model</li>
				<li>Helper(Optional)</li>
				<li>Private template files : private\Core\templates\default\<core feature> and private\Core\templates\default\Admin\<core feature></li>				
				<li>public template files : or public\templates\default\<core feature> and public\templates\default\Admin\<core feature></li>				
			</ol>
			"; */
			$clsCreatePjt->createCoreFeature();
		break;
	case "getAllFilesListSorted":	
		//?action=getAllFilesListSorted&path=D:\projects\PMUtilities\MVCMaker\installation\AddonContents
		$path = $_REQUEST['path'];
		$relativePath =  true;
		$foldersOnly =  true;
		$allFilesListSorted = $clsCreatePjt->getAllFilesListSorted($path, $relativePath, $foldersOnly);
		//echo "<pre>".print_r($allFilesListSorted, true ) ."</pre>";
		$fileNo = 0;
		foreach($allFilesListSorted as $folderName => $fileArray)
		{
			echo "<b>".$folderName."</b><br />\r\n";
			if(count($fileArray) > 0)
			{
				//echo "<b>".$folderName."</b><br />\r\n";
				foreach($fileArray as $fileIndex => $fileName)
				{
					$fileNo++;
					echo $fileNo. " , " . ($fileIndex +1) . " , " . $fileName . "<br />\r\n";
				}//foreach($fileArray as $fileIndex => $fileName)
			}//if(count($fileArray) > 0)
			
		}//foreach($allFilesListSorted as $folderName = > $fileArray)
		break;
	default:
		die("No proper 'action' requested");
}
/* $dir2Fetch  = __DIR__.DIRECTORY_SEPARATOR.$clsCreatePjt->projectRoot;
$sortedfilenames = $clsCreatePjt->ListFolder($dir2Fetch);
$allFilesSorted = $clsCreatePjt->getAllFilesSorted($clsCreatePjt->allFiles);
array_walk($clsCreatePjt->allFiles, function(&$val) use ($dir2Fetch){
							$val = str_replace($dir2Fetch,"",$val);
						});
//echo "<pre>".print_r($allFilesSorted, true)."</pre>";
//echo "<pre>".print_r($clsCreatePjt->allFiles, true)."</pre>";
//echo "<pre>".print_r($sortedfilenames, true)."</pre>";

echo $clsCreatePjt->getArrayAsString($allFilesSorted) */
?>