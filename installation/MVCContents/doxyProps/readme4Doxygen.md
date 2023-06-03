# README
<!-- Replace ^[^#]([\r\n]*) with blank to make a template. In note pad you can also use `negative lookahead` ^(?!") -->


## Author

Sreekanth Dayanand

### Contributors

## Synopsis

This is an instance of OSOS MVC generated via [OSOL MVC Maker](https://hariyom.bitbucket.io/MVCMaker/html/md_readme.html)

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
7. For doxygen comments edit 'Doxyfile' and replace `D:\projects\PMUtilities\projectBase\PR11` & `D:\projects\PMUtilities\projectBase\doxyDocumentation` with appropriate paths

\warning 

1. Google signin Error : <b>Warning</b>:  count(): Parameter must be an array or an object that implements Countable in <b>vendor/guzzlehttp/guzzle/src/Handler/CurlFactory.php</b> on line <b>67</b><br />
[Solution](https://stackoverflow.com/a/55238965)
change `count($this->handles)` to `count((is_countable($this->handles)?$this->handles:[]))`

## Extending / Installing Addons
1. Copy private and public folders
2. Edit private/Core/Config/AddonRoutes.php
	add Addon Name, so that link appear in left nav
3. run sql.dump of the addon
4. If additional links are to be added, add in Helper.php


## Porting to a different server

1. edit `RewriteBase` in .htaccess
2. edit `<base href=` in 
	1. private/Core/templates/default/default/pageParts/header.html and
	2. private/Core/templates/default/Admin/default/pageParts/header.html
3. Edit private/Core/Config/DBConfig.php. edit database credentials
4. Edit index.php, 
	1. edit `$OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH`(if private folder is to be moved to a safe location)
	2. edit constant `OSOLMVC_SESSION_VAR_PREPEND` appropriately(if there are multiple instancees of OSOLMVC in different subfolders for same ip/domain name)
5. Edit private/Core/bootstrap.php. Edit
	1. OSOLMVC_URL_ROOT
	2. OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH(if private folder is to be moved to a safe location) 
6. For Google Login 
	1. Redirect URL is $this->googleAppSettings['redirectURL'] in ClassSiteConfig, which is `$this->getFullURL2Root() . "Account/googleLoginRedirect"`
	2. That Redirect URL must be set in <https://console.developers.google.com/apis/credentials?authuser=1> 

## Contributing
Issue Tracker: github.com/project/issues

## License / Copyright Info
Licence Information

## Citation
1. How this software can be cited
2. DOI(Digital Object Identifier) link/image

## Contact
Email addesses or Contact us links

## Refernces

[Quick file Explorer excluding `composer` folder](http://localhost/pjt/MyContributions/osolcontributions/quickfileexplorer/master/?folder_to_select=D%3A%5Cprojects%5CPMUtilities%5CprojectBase%5CPR11&datepickerFrom=&datepickerTo=&exclude_paths=cache%2Clog%2CThumbs.db%2Csvn%2Czip%2Ccomposer&search_only_if_matches=)\n
[Quick file Explorer for uploading in testing server](http://localhost/pjt/MyContributions/osolcontributions/quickfileexplorer/master/?folder_to_select=D%3A%5Cprojects%5CPMUtilities%5CprojectBase%5CPR11&datepickerFrom=&datepickerTo=&exclude_paths=cache%2Clog%2CThumbs.db%2Csvn%2Czip%2Ccomposer%2CClassSiteConfig.php%2C.htaccess%2CDBConfig.php%2Cbootstrap.php&search_only_if_matches=)\n
[Direct link to download changed files with "&downloadFile=Download Modified Files"](http://localhost/pjt/MyContributions/osolcontributions/quickfileexplorer/master/?folder_to_select=D%3A\projects\PMUtilities\projectBase\PR11&datepickerFrom=&datepickerTo=&exclude_paths=cache%2Clog%2CThumbs.db%2Csvn%2Czip%2Ccomposer%2CClassSiteConfig.php%2C.htaccess%2CDBConfig.php%2Cbootstrap.php&search_only_if_matches=&downloadFile=Download%20Modified%20Files)
