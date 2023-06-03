<?php 
//\OsolMVC\Core\Controller\Admin\DefaultController
namespace OsolMVC\Core\Controller\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class DefaultController extends \OsolMVC\Core\Controller\DefaultController
{
	
    
	protected function __construct()
	{
		$isAdmin = true;
		$this->setCurrentViewIsAdmin($isAdmin);
		
		
	}//protected function __construct()
	public function render()//$message2Template = array("message" => "", "message_type" => "")
	{
		/* echo "line :". __LINE__. " in " . __FILE__."<br />".
				"This is Admin Controller <br /><br />"; */
		$permissionRequired = ["admin.core.view"];
		$adminPage = true;
		//$this->redirectIfNotLoggedIn($adminPage);
		$this->redirectIfNotAuthorised($permissionRequired);
		parent::render();		
	}//public function render()
	
}

?>