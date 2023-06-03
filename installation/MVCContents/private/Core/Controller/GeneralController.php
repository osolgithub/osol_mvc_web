<?php 

namespace OsolMVC\Core\Controller;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class GeneralController extends DefaultController
{
    
	protected function __construct()
	{
		
		
	}
	public function allowCookie()
	{
		$this->getView()->allowCookie();
	}//public function allowCookie()
	
	public function privacyPolicy()
	{
		$this->getView()->privacyPolicy();
	}//public function privacyPolicy()
	
	public function termsOfService()
	{
		$this->getView()->termsOfService();
	}//public function termsOfService()	
	public function notAuthorized()
	{
		$this->getView()->notAuthorized();
	}//public function notAuthorized()
	public function emailSharingDisbled()
	{
		$this->getView()->emailSharingDisbled();
	}//public function notAuthorized()

    
}//class ContactController

?>