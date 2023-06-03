<?php 

namespace OsolMVC\Core\Controller\Admin;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class ContactController extends \OsolMVC\Core\Controller\Admin\DefaultController
{
	
    
	protected function __construct()
	{
		parent::__construct();		
		
	}//protected function __construct()
	public function render()
	{
		//echo "This is Admin Contact Controller<br />";
		parent::render();
	}//public function render()
	public function contacts()
	{
		$permissionRequired = ["admin.*"];
		$adminPage = true;
		$this->redirectIfNotLoggedIn($adminPage);
		//$this->redirectIfNotAuthorised($permissionRequired);
		
	
		echo "This is Admin Contact Controller. public function contacts()";
	}
}

?>