<?php
$MVCFoldersAndFiles = array();

//---------------root folder---------------
$folderAndFiles = array();
$folderAndFiles[] = '.htaccess';
$folderAndFiles[] = 'Doxyfile';
$folderAndFiles[] = 'index.php';
$MVCFoldersAndFiles[''] = $folderAndFiles;

//---------------/doxyProps---------------
$folderAndFiles = array(
						"changeLog.md",
						"ClassDiagrams.md",
						"DBStructure.md",
						"Designs.md",
						"readme4Doxygen.md",
						"UseCaseDiagrams.md",
						"WireFrames.md",
						);
$MVCFoldersAndFiles['/doxyProps'] = $folderAndFiles;

//---------------/doxyProps/assets4OSOLMVC---------------
$folderAndFiles = array(
						"index.html",
						);
$MVCFoldersAndFiles['/doxyProps/assets4OSOLMVC'] = $folderAndFiles;

//---------------/doxyProps/UMLDiagrams---------------
$folderAndFiles = array(
						"index.html",
						);
$MVCFoldersAndFiles['/doxyProps/UMLDiagrams'] = $folderAndFiles;

//---------------/private---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private'] = $folderAndFiles;

//---------------/private/backups---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/backups'] = $folderAndFiles;

//---------------/private/Core---------------
$folderAndFiles = array();
$folderAndFiles[] = 'CoreParent.php';
$folderAndFiles[] = 'Psr4AutoloaderClass.php';
$folderAndFiles[] = 'bootstrap.php';
$MVCFoldersAndFiles['/private/Core'] = $folderAndFiles;

//---------------/private/Core/Config---------------
$folderAndFiles = array();
$folderAndFiles[] = 'ClassSiteConfig.php';
$folderAndFiles[] = 'CoreRoutes.php';
$folderAndFiles[] = 'AddonRoutes.php';
$folderAndFiles[] = 'DBConfig.php';
$folderAndFiles[] = 'EmailConfig.php';
$folderAndFiles[] = 'GoogleAppConfig.php';
$folderAndFiles[] = 'SessionConfig.php';
$folderAndFiles[] = 'SiteConfig.php';
$MVCFoldersAndFiles['/private/Core/Config'] = $folderAndFiles;

//---------------/private/Core/Controller---------------
$folderAndFiles = array();
$folderAndFiles[] = 'AccountController.php';
$folderAndFiles[] = 'ContactController.php';
$folderAndFiles[] = 'DefaultController.php';
$MVCFoldersAndFiles['/private/Core/Controller'] = $folderAndFiles;

//---------------/private/Core/Controller/Admin---------------
$folderAndFiles = array();
$folderAndFiles[] = 'ContactController.php';
$folderAndFiles[] = 'DefaultController.php';
$MVCFoldersAndFiles['/private/Core/Controller/Admin'] = $folderAndFiles;

//---------------/private/Core/Helper---------------
$folderAndFiles = array();
$folderAndFiles[] = 'ACLHelper.php';
$folderAndFiles[] = 'AddonHelper.php';
$folderAndFiles[] = 'EmailHelper.php';
$folderAndFiles[] = 'FilesHelper.php';
$folderAndFiles[] = 'GoogleLoginHelper.php';
$folderAndFiles[] = 'LogHelper.php';
$folderAndFiles[] = 'RequestVarHelper.php';
$folderAndFiles[] = 'RouteHelper.php';
$folderAndFiles[] = 'SessionHandlerHelper.php';
$MVCFoldersAndFiles['/private/Core/Helper'] = $folderAndFiles;

//---------------/private/Core/Lang---------------
$folderAndFiles = array();
$folderAndFiles[] = 'LangClassENUS.php';
$MVCFoldersAndFiles['/private/Core/Lang'] = $folderAndFiles;

//---------------/private/Core/Library---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Core/Library'] = $folderAndFiles;

//---------------/private/Core/Model---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Core/Model'] = $folderAndFiles;

//---------------/private/Core/Setup---------------
$folderAndFiles = array();
$folderAndFiles[] = 'Mysql.php';
$folderAndFiles[] = 'Setup.php';
$MVCFoldersAndFiles['/private/Core/Setup'] = $folderAndFiles;

//---------------/private/Core/View---------------
$folderAndFiles = array();
$folderAndFiles[] = 'AccountView.php';
$folderAndFiles[] = 'ContactView.php';
$folderAndFiles[] = 'DefaultView.php';
$MVCFoldersAndFiles['/private/Core/View'] = $folderAndFiles;
//---------------/private/Core/View/Admin---------------
$folderAndFiles = array();
$folderAndFiles[] = 'DefaultView.php';
$MVCFoldersAndFiles['/private/Core/View/Admin'] = $folderAndFiles;

//---------------/private/Core/composer---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Core/composer'] = $folderAndFiles;

//---------------/private/Core/templates---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Core/templates'] = $folderAndFiles;

//---------------/private/Core/templates/default---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Core/templates/default'] = $folderAndFiles;

//---------------/private/Core/templates/default/contact---------------
$folderAndFiles = array();
$folderAndFiles[] = 'email.html';
$folderAndFiles[] = 'main.html';
$MVCFoldersAndFiles['/private/Core/templates/default/contact'] = $folderAndFiles;

//---------------/private/Core/templates/default/default---------------
$folderAndFiles = array();
$folderAndFiles[] = 'main.html';
$MVCFoldersAndFiles['/private/Core/templates/default/default'] = $folderAndFiles;

//---------------/private/Core/templates/default/account---------------
$folderAndFiles = array();
$folderAndFiles[] = 'login.html';
$folderAndFiles[] = 'profile.html';
$MVCFoldersAndFiles['/private/Core/templates/default/account'] = $folderAndFiles;

//---------------/private/Core/templates/default/default/pageParts---------------
$folderAndFiles = array();
$folderAndFiles[] = 'breadCrumbs.html';
$folderAndFiles[] = 'footer.html';
$folderAndFiles[] = 'header.html';
$MVCFoldersAndFiles['/private/Core/templates/default/default/pageParts'] = $folderAndFiles;

//---------------/private/Core/templates/default/Admin/default---------------
$folderAndFiles = array();
$folderAndFiles[] = 'main.html';
$MVCFoldersAndFiles['/private/Core/templates/default/Admin/default'] = $folderAndFiles;

//---------------/private/Core/templates/default/Admin/default/pageParts---------------
$folderAndFiles = array();
$folderAndFiles[] = 'breadCrumbs.html';
$folderAndFiles[] = 'footer.html';
$folderAndFiles[] = 'header.html';
$MVCFoldersAndFiles['/private/Core/templates/default/Admin/default/pageParts'] = $folderAndFiles;

//---------------/private/Crons---------------
$folderAndFiles = array();
$folderAndFiles[] = 'cron_bootstrap.php';
$MVCFoldersAndFiles['/private/Crons'] = $folderAndFiles;

//---------------/private/backups---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/backups'] = $folderAndFiles;

//---------------/private/cache---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/cache'] = $folderAndFiles;

//---------------/private/lang---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/lang'] = $folderAndFiles;

//---------------/private/logs---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/logs'] = $folderAndFiles;

//---------------/private/temp---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/temp'] = $folderAndFiles;

//---------------/private/testing---------------
$folderAndFiles = array();
$folderAndFiles[] = 'testing_bootstrap.php';
$folderAndFiles[] = 'phpunit.xml';
$folderAndFiles[] = 'testing.md';
$MVCFoldersAndFiles['/private/testing'] = $folderAndFiles;

//---------------/private/testing/tests---------------
$folderAndFiles = array();
$folderAndFiles[] = 'SampleTest.php';
$MVCFoldersAndFiles['/private/testing/tests'] = $folderAndFiles;

//---------------/private/uploads---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/uploads'] = $folderAndFiles;

//---------------/private/uploads/Addons---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/uploads/Addons'] = $folderAndFiles;






/***PUBLIC FILES***/

//---------------/public---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public'] = $folderAndFiles;

//---------------/public/css---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/css'] = $folderAndFiles;

//---------------/public/commonAssets---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/commonAssets'] = $folderAndFiles;

//---------------/public/commonAssets/jsFramewoks---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/commonAssets/jsFramewoks'] = $folderAndFiles;

//---------------/public/commonAssets/jsFramewoks---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/commonAssets/jsLibraries'] = $folderAndFiles;

//---------------/public/images---------------
$folderAndFiles = array();
$folderAndFiles[] = 'logoMain.png';
$folderAndFiles[] = 'logoMini.png';
$MVCFoldersAndFiles['/public/images'] = $folderAndFiles;

//---------------/public/js---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/js'] = $folderAndFiles;

//---------------/public/js/lang---------------
$folderAndFiles = array();
$folderAndFiles[] = 'classLang.en-US.js';
$MVCFoldersAndFiles['/public/js/lang'] = $folderAndFiles;

//---------------/public/media---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/media'] = $folderAndFiles;

//---------------/public/templates---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/templates'] = $folderAndFiles;

//---------------/public/templates/default---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/templates/default'] = $folderAndFiles;

//---------------/public/templates/default/css---------------
$folderAndFiles = array();
$folderAndFiles[] = 'main.css';
$folderAndFiles[] = 'preloader.css';
$MVCFoldersAndFiles['/public/templates/default/css'] = $folderAndFiles;

//---------------/public/templates/default/images---------------
$folderAndFiles = array();
$folderAndFiles[] = 'sign-in-with-google.png';
$folderAndFiles[] = 'office.jpg';
$folderAndFiles[] = 'yuna.jpg';
$MVCFoldersAndFiles['/public/templates/default/images'] = $folderAndFiles;

//---------------/public/templates/default/js---------------
$folderAndFiles = array();
$folderAndFiles[] = 'contactUs.js';
$folderAndFiles[] = 'commonUtils.js';
$MVCFoldersAndFiles['/public/templates/default/js'] = $folderAndFiles;

//---------------/public/templates/default/media---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/public/templates/default/media'] = $folderAndFiles;






/***ADDONS***/

//---------------/private/Addons---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons'] = $folderAndFiles;


/**ADDONS/BASIC */
//---------------/private/Addons/Basic---------------
$folderAndFiles = array();
$folderAndFiles[] = "Controller.php";
$folderAndFiles[] = "Model.php";
$folderAndFiles[] = "View.php";
$folderAndFiles[] = "Helper.php";
$folderAndFiles[] = "Config.php";
$MVCFoldersAndFiles['/private/Addons/Basic'] = $folderAndFiles;

//---------------/private/Addons/Basic/Admin---------------
$folderAndFiles = array();
$folderAndFiles[] = "Controller.php";
/* $folderAndFiles[] = "Model.php";
$folderAndFiles[] = "View.php";
$folderAndFiles[] = "Helper.php";
$folderAndFiles[] = "Config.php"; */
$MVCFoldersAndFiles['/private/Addons/Basic/Admin'] = $folderAndFiles;

//---------------/private/Addons/Basic/Lang---------------
$folderAndFiles = array();
$folderAndFiles[] = "LangClassENUS.php";
$MVCFoldersAndFiles['/private/Addons/Basic/Lang'] = $folderAndFiles;

//---------------/private/Addons/Basic/templates---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/Basic/templates'] = $folderAndFiles;

//---------------/private/Addons/Basic/templates/default---------------
$folderAndFiles = array();
$folderAndFiles[] = "main.html";
$MVCFoldersAndFiles['/private/Addons/Basic/templates/default'] = $folderAndFiles;

//---------------/public/templates/default/addons/basic---------------
$folderAndFiles = array();
//$folderAndFiles[] = "main.html";
$MVCFoldersAndFiles['/public/templates/default/addons/basic'] = $folderAndFiles;

//---------------/public/templates/default/addons/basic/css---------------
$folderAndFiles = array();
$folderAndFiles[] = "main.css";
$MVCFoldersAndFiles['/public/templates/default/addons/basic/css'] = $folderAndFiles;

//---------------/public/templates/default/addons/basic/images---------------
$folderAndFiles = array();
$folderAndFiles[] = "sample.png";
$MVCFoldersAndFiles['/public/templates/default/addons/basic/images'] = $folderAndFiles;

//---------------/public/templates/default/addons/basic/js---------------
$folderAndFiles = array();
$folderAndFiles[] = "main.js";
$MVCFoldersAndFiles['/public/templates/default/addons/basic/js'] = $folderAndFiles;

/* 
//---------------/private/Addons/Config---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/Config'] = $folderAndFiles;

//---------------/private/Addons/Controller---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/Controller'] = $folderAndFiles;

//---------------/private/Addons/Helper---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/Helper'] = $folderAndFiles;

//---------------/private/Addons/Library---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/Library'] = $folderAndFiles;

//---------------/private/Addons/Model---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/Model'] = $folderAndFiles;

//---------------/private/Addons/View---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/View'] = $folderAndFiles;

//---------------/private/Addons/templates---------------
$folderAndFiles = array();
$MVCFoldersAndFiles['/private/Addons/templates'] = $folderAndFiles;

 */

?>