# Readme

## Author

Sreekanth Dayanand <br />
[Outsource Online Internet Solutions](http://www.outsource-online.net/)

## Contributors

This is a solo project.
	
### Date 

5th January 2023

## Synopsis

Creates Light weight MVC framework.

Also helps to create addons

Detailed documentation is available at <https://hariyom.bitbucket.io/MVCMaker/html/>

## Description

Will be added soon. Please keep Checking

### UML Diagrams

Will be added soon. Please keep Checking


## Prerequisites

1. PHP version 5.1.0+, ideal is 7+, 
2. Safe mode must be turned off
3. PHP GD Library should be enabled

The above requirements are default settings in most PHP hosts.


## Installation

### Create a new MVC instance:

Step By step installation guide
1. Create db
2. run dbSchema.sql

4. set the value in class \ref classCreateProject
```
public $projectRoot = "projectBase/PR11";
```
5. Run <http://localhost/pjt/PMUtilities/MVCMaker/MVCMaker.php>, previously<http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createMVC> 
6. Run `composer require osolutils/helpers` inside private/Core/composer\n
7. <b>Doxygen :</b> For doxygen comments edit 'Doxyfile' and replace `D:\projects\PMUtilities\projectBase\PR11` & `D:\projects\PMUtilities\projectBase\doxyDocumentation` with appropriate paths
8. Edit Config files(DB, APIs etc)
for google login,edit private\Core\Config\GoogleAppConfig.php
```
$this->googleAppSettings
```
use the following regexp in in private\Core\Config\ClassSiteConfig.php
```
^([^\}\r\n\/\$]+)function([^\(\r\n]+)\(
```
in private\Core\Config\DBConfig.php
edit
```
$this->dbSettings = array(
                                        'DB_USER'  =>   "root",
                                        'DB_PASS'  =>   "",
                                        'DB_SERVER'  =>   "localhost",
                                        'DB_NAME'  =>   "osol_mvc",
                                        'table_prefix' => "osol_mvc_",
                                        'log_queries' => true,
                                        'query_log_type' => 'file',//'echo'
                                        );
```
in private\Core\Config\SiteConfig.php
```
$this->siteSettings = array(
                                    'site_title' => 'OSOL_MVC_SITE_TITLE',
                                    // sample time zones 'America/Los_Angeles'  , 'America/New_York' etc
                                    'system_time_zone' => 'UTC', //timezone in php.ini
                                    "mysql_time_zone" => "Asia/Kolkata",//UTC",
                                    'site_time_zone' => 'Asia/Kolkata',

                                    );
```
in private\Core\Config\EmailConfig.php
```
$this->smtpSettings = array(
```

### To create Addon. 
<http://localhost/pjt/PMUtilities/MVCMaker/addonMaker.php> previously <http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createAddon&addonName=addonName>


### To create Core Feature Component. 
<http://localhost/pjt/PMUtilities/MVCMaker/coreFeatureMaker.php> previously <http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createCoreFeature&coreFeatureName=CoreFeatureName>



## Use Case

### Create MVC

1. user calls <http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createMVC>\n
2. 
```
$clsCreatePjt->initiate()
					->createProject();
```

### Create Addon 				

1. user calls <http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createAddon&addonName=addonName>\n
2. 
```
$clsCreatePjt->createAddon();
```
### Core Feature Component

1. user calls <http://localhost/pjt/PMUtilities/MVCMaker/installation.php?action=createCoreFeature&coreFeatureName=CoreFeatureName>\n
2. 
```
$clsCreatePjt->createCoreFeature();
```
## Extending / Installing Addons

Any suggetions are welcome


## Porting to a different server

Steps are in "OSOL MVC Instance" Documentation.

## Contributing

Will come soon in github

## License / Copyright Info

This project is released under the GNU Public License.

## Citation
the following will shortly come 
1. How this software can be cited
2. DOI(Digital Object Identifier) link/image

## Contact

[Contact Us](https://outsource-online.net/contact-us.html)

## References

[Work flow](http://www.outsource-online.net/blog/demos/doxygenComments/)@n
[Documentation from scratch](http://www.outsource-online.net/blog/2022/10/17/documentation-from-scratch/)@n
[Documentation steps](http://www.outsource-online.net/blog/2022/07/13/doxygen-basics/)@n
[Bitbucket HTML Pages](http://www.outsource-online.net/blog/2022/06/13/git-command-line-tutorials/#bitbucket_html_pages)"# osol_mvc_web" 
