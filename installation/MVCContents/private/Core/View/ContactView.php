<?php 

namespace OsolMVC\Core\View;

#use App\Models\Product;
#use Symfony\Component\Routing\RouteCollection;

class ContactView extends \OsolMVC\Core\View\DefaultView
{
    
	protected function __construct()
	{
		
	}
	public function showView()
	{
		
		
		$scriptCode2Add = "
		function refreshCaptcha(){
			var time =  new Date().getTime();
			var captchaSrc = document.getElementById('osolCaptchaContactus').src 
			var captchaSrc = /([^\?]*)\??/.exec(captchaSrc)[1];
			//console.log('captchaSrc is ' + captchaSrc);
			var captchaSrc = captchaSrc+'?'+time;
			//console.log('loading ' + captchaSrc);
			document.getElementById('osolCaptchaContactus').src = captchaSrc;
			
		}//function refreshCaptcha()
		";
		$this->addJSScriptCode($scriptCode2Add);
		
		
		
		$scriptCode2Add = <<<EOT
		
			var contactus = new ContactUs();
			contactus.prefillForm();
			var submit_btn = document.getElementById("contactSubmit");

			  submit_btn.addEventListener("click", function(e) {
				e.preventDefault();
				contactus.sendEmail();
				});


            

            // dragover and dragenter events need to have 'preventDefault' called
            // in order for the 'drop' event to register. 
            // See: https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Drag_operations#droptargets
            let dropContainer = document.getElementById("dropContainer");
            let fileInput = document.getElementById("fileToUpload");

            dropContainer.ondragover = dropContainer.ondragenter = function(evt) {
              evt.preventDefault();
            };

            dropContainer.ondrop = function(evt) {
              // pretty simple -- but not for IE :(
              fileInput.files = evt.dataTransfer.files;

              /* // If you want to use some of the dropped files
              const dT = new DataTransfer();
              dT.items.add(evt.dataTransfer.files[0]);
              dT.items.add(evt.dataTransfer.files[3]);
              fileInput.files = dT.files; */

              evt.preventDefault();
            };



            var isMobile = {
                              Android: function() {
                                  return navigator.userAgent.match(/Android/i);
                              },
                              BlackBerry: function() {
                                  return navigator.userAgent.match(/BlackBerry/i);
                              },
                              iOS: function() {
                                  return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                              },
                              Opera: function() {
                                  return navigator.userAgent.match(/Opera Mini/i);
                              },
                              Windows: function() {
                                  return navigator.userAgent.match(/IEMobile/i);
                              },
                              any: function() {
                                  return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                              }
                          };//var isMobile = {
                          if( isMobile.any() )
                          {
                            console.log('Accessed via Mobile , hiding dropContainer');
                            dropContainer.style.display='none';
                          } //if( isMobile.any() )

             

            

         

        
EOT;
 //});//$(document).ready(function(){
		$this->addJSScriptCode($scriptCode2Add,true);
		$templateFileURL = $this->getTemplateFileURL("js/contactUs.js");
		$this->addJSScriptTag($templateFileURL);
		
		$this->addCSSLinkTag($this->getTemplateFileURL("css/preloader.css"));
		//$this->page2Show = "main.html";	
		$this->addPreloader = true;
		
		
		
		parent::showView();
	}//public function showView()
}//class ContactView extends \OsolMVC\Core\View\DefaultView

?>