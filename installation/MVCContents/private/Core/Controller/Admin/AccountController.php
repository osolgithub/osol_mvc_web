<?php 

namespace OsolMVC\Core\Controller\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class AccountController extends DefaultController
{
    
	protected function __construct()
	{
		parent::__construct();
		
	}
	public function render()//$message2Template = array("message" => "", "message_type" => "")
	{
		//die(  "This is Admin Account Controller<br />");
		//parent::render();
		$permissionRequired = ["admin.*"];
		$adminPage = true;
		$this->redirectIfNotLoggedIn($adminPage);
		//$this->redirectIfNotAuthorised($permissionRequired);
		
		
		
	}//public function render()
	
	
    
}//class ContactController

?>