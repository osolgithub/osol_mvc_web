## Page rendering flow chart

1. index.php includes  private/Core/bootstrap.php
2. inside bootstrap.php
```
$routerHelper = \OsolMVC\Core\Helper\RouteHelper::getInstance();
$controllerAndMethod2Route = $routerHelper->getControllerAndMethod2Route();
$controllerInstance = $controllerAndMethod2Route->controller;
$renderMethod = $controllerAndMethod2Route->method;//"render";
define("OSOL_MVC_CURRENT_RENDER_METHOD",$renderMethod);
```
3. back in index.php

```
if($renderMethod != "render404") $controllerInstance->beforeRenderPage()
$controllerInstance->$renderMethod
if($renderMethod != "render404") $controllerInstance->afterRenderPage()
```

### Activity flow
```
@startuml
start
:index.php
 includes 
 private/Core/bootstrap.php;
 
:in bootstrap
$routerHelper = \OsolMVC\Core\Helper\RouteHelper::getInstance()
$controllerAndMethod2Route = $routerHelper->getControllerAndMethod2Route()
$controllerInstance = $controllerAndMethod2Route->controller
$renderMethod = $controllerAndMethod2Route->method;//"render"
define("OSOL_MVC_CURRENT_RENDER_METHOD",$renderMethod);

:back in index
if($renderMethod != "render404") $controllerInstance->beforeRenderPage()
$controllerInstance->$renderMethod
if($renderMethod != "render404") $controllerInstance->afterRenderPage();


stop
@enduml
```
### Diagram with [PUML SERVER](http://www.plantuml.com/plantuml/uml/SyfFKj2rKt3CoKnELR1Io4ZDoSa70000)

![Alt text](file://pageRendering.png "Text on mouseover")

## DefaultView->render() default method of controller


```
	if(!$myView = $this->getView())
	$defaultViewClass = '\OsolMVC\Core\View\DefaultView';
	$myView = $defaultViewClass::getInstance();
```

## when some other method of controller is called $controllerInstance->$renderMethod	


Take as example `AccountController::profile()`.
1. $this->getView()->$renderMethod();
2. AccountView->profile()
```
		$templateMainSubFolder = $this->getTemplateMainSubFolder(get_called_class());
		$templateFileURL = $this->getTemplateFileURL($templateMainSubFolder."/css/profile.css");
		$this->addCSSLinkTag($templateFileURL);
		//die("HI in line # " . __LINE__ . " of " .__FILE__);		
		$this->variables4Template['userDetails'] = $this->getCoreHelper("Account")
															->getUserDetailsWithSessionVar();
		$this->page2Show = "profile.html";
		$this->showView();
```		
