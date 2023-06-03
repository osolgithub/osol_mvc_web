<?php
//http://localhost/pjt/legallive/public_html/mvcMaker.php?config=PYTRC_July22


/* Variables to be set


Porting to a different server
Variables in files

1. file path of project : eg: projectBase/PR11
.htaccess RewriteBase
2. private/Core/Config/DBConfig.php : DB settings
	$this->dbSettings = array(
                                        'DB_USER'  =>   "root",
                                        'DB_PASS'  =>   "",
                                        'DB_SERVER'  =>   "localhost",
                                        'DB_NAME'  =>   "osol_mvc",
                                        'table_prefix' => "osol_mvc_",
                                        'log_queries' => true,
                                        'query_log_type' => 'file',//'echo'
                                        );
3. index.php 						: $OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH  and constnt OSOLMVC_SESSION_VAR_PREPEND
4. private/Core/bootstrap.php		: OSOLMVC_URL_ROOT,OSOLMVC_PRIVATE_FOLDER_RELATIVE_PATH

Variables in DB
1. email settings
2. google app
3. fb app
4. General
	1. Site title
	2. Lang
5. Session Config
6. Routes
		1. Core
		2. Addons
*/		
/*
Phase 1
========
1.	Create HTML File
2.  Add Form Fields (first test only with file configs)
3.	add js code to ajax post
4.  add backend code to collect and save

Phase 2
=======
1. add code to change files to set file based config files
2. add code to set db based config vals
*/




//Back end
//Backend functions
//Back end
//Backend functions
function showJSONReply($status, $message)
{
	die('{"status": "' . $status . '","message":"' . $message . '"}');
}//function showJSONReply($status, $message)

function getFormFields($mvcArguments)
{
	$formFields = array();
	foreach($mvcArguments as $label => $level1Field)
	{
		$level1FieldIsArray = is_array($level1Field);
		if(!$level1FieldIsArray)
		{
			$formFields[$label] = 1;
		}
		else
		{
			$formFields[$label] = array();
			
			foreach($level1Field as $level2Label => $level2Field)
			{
				
				$formFields[$label][$level2Label] = 1;
			}//foreach($level1Field as $$level2Label => $level2Field)
		}//if(!$level1FieldIsArray)
		
	}//foreach($mvcArguments as $label => $level1Field)
	return $formFields;
}//function getFormFields($mvcArguments)
function getFormFields2mvcArguments()
{
	global $mvcArguments;
	$rdArgumentsVar = '$mvcArguments = array();'."\r\n";
	$defaultConfig = "default";
	$configFullPath = getConfigFullPath($defaultConfig);
	require_once($configFullPath);
	
	foreach($mvcArguments as $label => $level1Field)
	{
		$level1FieldIsArray = is_array($level1Field);
		if(!$level1FieldIsArray)
		{
			
			$rdArgumentsVar .= '$mvcArguments["'.$label.'"] = '.formatVarForConfig($label).";\r\n";
		}
		else
		{
			
			$rdArgumentsVar .= '$mvcArguments["'.$label.'"] = array('."\r\n";
			foreach($level1Field as $level2Label => $level2Field)
			{
				$postFieldName =  $label."_" .$level2Label; 
				$rdArgumentsVar .= str_repeat("\t",6).'"'.$level2Label.'" => '.formatVarForConfig($postFieldName).",\r\n";
			}//foreach($level1Field as $$level2Label => $level2Field)
			$rdArgumentsVar .= str_repeat("\t",5).');'."\r\n";
		}//if(!$level1FieldIsArray)
		
	}//foreach($mvcArguments as $label => $level1Field)
	return $rdArgumentsVar;
}//function getFormFields2mvcArguments()

$booleanFields = ["DB_SETTINGS_log_queries"];
function formatVarForConfig($var)
{
	global $booleanFields;
	if(in_array($var,$booleanFields))
	{
		return $_POST[$var];
	}
	else //if(in_array($var,$booleanFields))
	{
		
	return '"'.addslashes($_POST[$var]).'"';
	}//if(in_array($var,$booleanFields))
}//function formatVarForConfig($var)
function formatVarValForForm($var,$val)
{
	global $booleanFields;
	if(in_array($var,$booleanFields))
	{
		return ($val?"true":"false");
	}
	else //if(in_array($var,$booleanFields))
	{
		return $val;
	}//if(in_array($var,$booleanFields))
}//function formatVarValForForm($var)

if(isset($_GET['action']) && $_GET['action'] == "CreateMVC")
{
	$configFileName = $_GET['config'];
	$configFullPath = getConfigFullPath($configFileName);
	// Code to create MVC instance
	 require_once("installation/classMVCMaker.php");
	
	$clsCreatePjt =  new \OSOLMVCMaker\classMVCMaker();///< instance of OSOLMVCMaker::classMVCMaker@n
										 ///< methods \b createProject OR \b createAddon are called on this depending on the \a $_GET['action'] value
										 
	
	$clsCreatePjt->createProjectFromConfig($configFullPath);  
					
	exit;				
	// Code to create MVC instance ends here.
}//if(isset($_POST['action']) && $_POST['action'] == "CreateMVC")
if(isset($_POST['action']) && $_POST['action'] == "submitMVCMakerVars")
{
	//validate backend
		//check if config already exists
		$configFileName = $_POST['CONFIG_NAME'];
		$configFullPath = getConfigFullPath($configFileName);
		if($_POST['existingOrNew'] == 'new' && file_exists($configFullPath))showJSONReply("Error", "Config filename `{$configFileName}` already exists. Change Config name");
		//if(file_exists($configFullPath))showJSONReply("Error", "Encountered errors, see console log");
	//processing steps
	// create $rdArguments from form
	
	$new_mvcArguments = getFormFields2mvcArguments();
	
	
	//create/ update config file
	
	$configFileContents = '<?php' . "\r\n//" . $configFileName . "\r\n" . $new_mvcArguments . "\r\n" . '?>';
	//die("<pre>".$new_rdArguments ."</pre>");	
	file_put_contents($configFullPath,$configFileContents);
	
	
	
	showJSONReply("Success", "Saved Config!!!. Please whait while the document is downloaded.");
	
	// reture JSON
	//examples
	//die('{"status": "Success","message":"Rent Agreement Generated Successfully"}');
	//die('{"status": "Error","message":"Encountered errors, see console log"}');
	//showJSONReply($status, $message)
}//if(isset($_POST['action']) && $_POST['action'] == "submit")




//frontend
$defaultConfig = "default";
$configFile = "";

$configSet = false;
$existingConfig = false;

function getConfigFullPath($config = "")
{
	
	$instType = "MVC";
	$rentConfigFolder = __DIR__."/instVars/{$instType}";
	$configFullPath = $rentConfigFolder."/". $config . (($config!="")?".php":"");
	return $configFullPath;
}//function getConfigFullPath($config)

function getFormLabelFromArrayKey($label)
{
	//$fieldNameFromLabel = preg_replace("/\s\.\,\;/","",$label)
	return $label;
}//function getFormLabelFromArrayKey($label)
function showFormFieldFromLabelAndValue($label,$text)
{
	?>
				<div class="input-field">
					<!--<i class="material-icons prefix">email</i>-->
					<input type="text" name="<?php echo $label;?>" id="<?php echo $label;?>" value="<?php echo formatVarValForForm($label,$text);?>" required>
					<label for="<?php echo $label;?>"><?php echo getFormLabelFromArrayKey($label);?></label>
				  </div>
		<?php
}//function showFormFieldFromLabelAndValue($label,$text)


if(isset($_GET['config']) && trim($_GET['config']) != "")
{
	//if passed, check if config file exists eg $varsOfDocFile = "PYTRC_July22.php";
	$config = $_GET['config'];
	$configFullPath = getConfigFullPath($config);
	//echo "configFullPath is $configFullPath<br />";
	if(file_exists($configFullPath))
	{
		require_once($configFullPath);
		// if exists load config files
		$existingConfig = $configSet = true;
		
	}//if(file_exists($configFile))	
	
}//if(isset($_GET['config']) && trim($_POST['config']) != "")
if(!$configSet)$config = $defaultConfig;
$configFullPath = getConfigFullPath($config);
//echo "configFullPath is $configFullPath<br />";
require_once($configFullPath);
//echo "<pre>".print_r(getFormFields($rdArguments),true)."</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create OSOL MVC Instance</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 
    <!--  Material I cons from Google Fonts. -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  
<div class="container">
  <h1>Create OSOL MVC Instance</h1>
  <p>
	<a href="?action=CreateMVC&config=<?php echo $config;?>">
		Create Instace with <?php echo $config;?>
	</a>
  </p>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
   <div class="row">
	  <div  class="col s12 l5">
		  <form name="mvcMakerForm" id="mvcMakerForm" action="mvcMaker.php" method="post" enctype="multipart/form-data">
		  <?php
			if(!$existingConfig)
			{
				showFormFieldFromLabelAndValue("CONFIG_NAME",$config);
			}
			else
			{
				echo "<div style=\"width:100%;text-align:left;font-weight:bold;\">CONFIG_NAME : ".$config . "</div>";
				?>
				<input type="hidden" name="CONFIG_NAME" id="CONFIG_NAME" value="<?php echo $config;?>" />
				<?php
			}
			// create form based on config texts
			foreach($mvcArguments as $label => $level1Field)
			{
				$level1FieldIsArray = is_array($level1Field);
				if(!$level1FieldIsArray)
				{
					showFormFieldFromLabelAndValue($label,$level1Field);
				}
				else
				{
					?>		
					<b><?php echo $label;?></b> <br />
					<?php
					foreach($level1Field as $level2Label => $level2Field)
					{
						
						showFormFieldFromLabelAndValue($label . "_" . $level2Label,$level2Field);
					}//foreach($level1Field as $$level2Label => $level2Field)
				}//if(!$level1FieldIsArray)
				
			}//foreach($mvcArguments as $label => $level1Field)
			//add js ajax with preloaded
			
			
			?>
						<div class="input-field center">
							<input type="hidden" name="action" value="submitMVCMakerVars" />
							<input type="hidden" name="existingOrNew" value="<?php echo ($existingConfig)?"existing":"new";?>" />
							<button type="button" id="mvcMakerSubmit"  class="btn waves-effect waves-light">Submit</button>
						  </div> 
		  </form>
	</div><!-- class="col s12 l5 "-->
		
	  <div class="col s12 l5 offset-l2 "  style="text-align:left">
		<h3 class="indigo-text text-darken-4">Existing Configs</h3>
		<?php
		$path = getConfigFullPath();
		$files = array_diff(scandir($path), array('.', '..'));
		?>
		<ol>
		<?php
		foreach($files as $file)
		{
			$config2Display =  str_replace(".php","",$file);
		?>
		<li><a href="?config=<?php echo $config2Display;?>"><?php echo $config2Display;?></a></li>
		<?php
		}//foreach($files as $file)
		?>
		</ol>
		
	  </div><!-- class="col s12 l5 offset-l2  "-->
	</div><!-- class="row"  -->	
</div><!--class="container" ->
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
  var formValidationMessages = "";
  function validateMVCMakerFields()
  {
	  let formValidationResult = true;
	  formValidationMessages = "";	  
	  //replace below line with validation block	
	  let configNameRegex = /^([A-Z,a-z,0-9]+)$/;
	  let configName = document.getElementById("CONFIG_NAME").value;
      let OK = configNameRegex.exec(configName) && (configName != "default");
	  if( !OK)
	  {
		  formValidationResult = false;
		  formValidationMessages = "Please enter a valid config name, (only alphabets and numerals allowed, name 'default' is also not allowed)";
		  
	  }//if(document.getElementById("CONFIG_NAME").value == "")
	  
	  return formValidationResult;
	   
  }//function validateMVCMakerFields()
  function generateMVCMaker()
  {
	  if(validateMVCMakerFields())
	  {
		  postMVCMaker();
	  }
	  else //if(validateMVCMakerFields())
	  {
		  alert(formValidationMessages);
	  }//if(validateMVCMakerFields())
  }//function generateMVCMaker()
  function postMVCMaker()
  {
	 let stringVariable = window.location.href;
     let postURL = stringVariable.substring(0, stringVariable.lastIndexOf('/')) + "/mvcMaker.php?rer=sd"; 
	 const getFormData = () => {
          const form = document.getElementById("mvcMakerForm");
          return new FormData(form);
        }
	let formData = getFormData();
        /* const file = document.querySelector('#fileToUpload').files[0];
        formData.append('fileToUpload', file); */
        
        //console.log(formData);
        formData.forEach((value, key) => {
          console.log(key,value);
        });
        fetch(postURL, {
          method: 'POST',
          credentials: "include",
          body: formData
          //,headers: new Headers({ "content-type": "application/multipart/form-data; charset=UTF-8" })
        })
        .then(responseObj => {
          //this block is not redundant
          // this is required to get past a chrome bug
          //https://stackoverflow.com/questions/47177053/strange-behaviour-of-php-session-start-when-javascript-fetch
          console.log("response.status is "+ responseObj.status)
          if (responseObj.status !== 200) {
            
           preloaderInst.hide();
		   
            M.toast({
              html:
                "There was an error, please try again later (" +
                responseObj.status +
                ")",
              displayLength: 2000,
              classes:"red"
            });
            return null;
          }//if (responseObj.status !== 200) 
          return responseObj.json();
        })
        .then(response => {
          //console.log('Success');
          console.log('Success:', ((response)));
          if(!response)return null;// if status is not 200
          
          //preloaderInst.hide();
          if (response.status === "Error") {
            M.toast({ html: response.message, displayLength: 2000,classes:"red" });
            return;
          }
            
          M.toast({ html: response.message, displayLength: 2000,classes:"green" });
            
            
        })
        //.then(response => response.json())
        .catch(error => {
          console.error('Error:', (error));
        });
  }//function postMVCMaker()
  window.onload = function(){
	  var submit_btn = document.getElementById("mvcMakerSubmit");
	  <?php
	  if($existingConfig){/* 
	  ?>
	  document.getElementById("CONFIG_NAME").disabled =  true;
	  <?php
	  }
	  else //if($existingConfig){
	  {?>
		document.getElementById("CONFIG_NAME").value = "";
	  <?php
	   */}//if($existingConfig){
	  ?>
	  submit_btn.addEventListener("click", function(e) {
		e.preventDefault();
		generateMVCMaker();
		});
  }//window.onload = function()
  </script>
</body>
</html> 