<?php 

namespace OsolMVC\Core\View;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class GeneralView extends \OsolMVC\Core\View\DefaultView
{
    
	protected function __construct()
	{
		
	}
	
	public function allowCookie()
	{
		$this->page2Show = "allowCookie.html";
		$this->showView();
	}//public function allowCookie()
	public function privacyPolicy()
	{
		$this->page2Show = "privacyPolicy.html";
		$this->showView();
	}//public function privacyPolicy()
	
	
	public function termsOfService()
	{
		$this->page2Show = "termsOfService.html";
		$this->showView();
	}//public function termsOfService()	
	public function notAuthorized()
	{
		$this->page2Show = "notAuthorized.html";
		$this->showView();
	}//public function notAuthorized()
	public function emailSharingDisbled()
	{
		$this->page2Show = "emailSharingDisbled.html";
		$this->showView();
	}//public function emailSharingDisbled()
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>