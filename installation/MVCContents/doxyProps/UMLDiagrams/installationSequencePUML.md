## ClassMVCMaker::createProject()

### Revised phases as on 18-05-2023

1. show html interface for required vars
2. save user entered vars in xml. as installConfigs/xmlConfig.xml
3. create MVC with entered vars xml

xmlConfig.xml will be like.

```
<?xml version="1.0" encoding="utf-8"?>
<osol_mvc_config>
	<file_paths>
		<projectRoot>
			<!CDATA[projectBase/PR11]>
		</projectRoot>
		<PRIVATE_FOLDER_ROOT>
			<!CDATA[private]>
		</PRIVATE_FOLDER_ROOT>
	</file_paths>
	<file_urls>
	</file_urls>
	<database>
	</database>
	<email>
	</email>	
</osol_mvc_config>
```


### Original Sequence 

The call is triggered with  the following call in `installation.php` .

```
$clsCreatePjt->initiate()
					->createProject();
```					


1. Replace vars are set in `ClassMVCMaker::initiate` method

```
$this->PROJECT_ROOT_URI = "/" . $this->get_absolute_path(dirname($_SERVER['REQUEST_URI'])."/../")."/".$this->projectRoot."/";
$this->replaceVars = array(
								"__PRIVATE_FOLDER_ROOT__" => $this->PRIVATE_FOLDER_ROOT,
								"__PROJECT_ROOT_URI__" => $this->PROJECT_ROOT_URI,
								"__PROJECT_ROOT_URI_WITH_SERVER__" => $this->getFullURL2Root()
								);
```

Variables previously set are

```
public $projectRoot = "projectBase/PR11";///< root folder of new MVC instance to be created
											///< Should be <b>relative path</b> w.r.t \b MVCMaker @n
											///< ie parent folder of installation.php
private $PRIVATE_FOLDER_ROOT = "private";
```					
2. get all files and folders 
```
$MVCSourceRoot = realpath( __DIR__."/MVCContents");	
$this->MVCFoldersAndFiles = $this->getAllFilesListSorted($MVCSourceRoot,true);
```

3. Search and Replace vars

```
$searchVars =  array_keys($this->replaceVars);
$replaceVars =  array_values($this->replaceVars);
```

