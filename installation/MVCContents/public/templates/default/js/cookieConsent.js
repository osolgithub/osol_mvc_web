/*
//localStorage.removeItem("cookiesAccepted"); // this line is for testing only. always comment after testing
// Check if the user has already accepted cookies
if (!localStorage.getItem('cookiesAccepted')) {
	
	//alert("cookiesAccepted is " + localStorage.getItem('cookiesAccepted'));
  // Display the cookie consent modal
   const modal = document.createElement('div');
          
  modal.innerHTML = `
    <div class="cookie-modal">
      <p>This website uses cookies to ensure you get the best experience. By using our site, you acknowledge that you have read and understand our Cookie Policy.</p>
      <button id="accept-cookies">Accept Cookies</button>
    </div>
  `;

  document.body.appendChild(modal);

  // Handle the "Accept Cookies" button click event
  const acceptButton = document.getElementById('accept-cookies');
  acceptButton.addEventListener('click', () => {
    // Set a flag in local storage to remember the user's choice
    localStorage.setItem('cookiesAccepted', true);

    // Remove the modal from the DOM
    modal.remove();
  }); 
 
}*/
//localStorage.removeItem("cookiesAccepted"); // this line is for testing only. always comment after testing
const modal = document.createElement('div');
  modal.innerHTML = `
  <!-- Modal Trigger
  <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a> -->

  <!-- Modal Structure -->
  <div id="cookieConsentModal" class="modal">
    <div class="modal-content">
      <h4>Cookie Alert</h4>
      <p>This website uses cookies to ensure you get the best experience. By using our site, you acknowledge that you have read and understand our <a href="./General/privacyPolicy#cookiePolicy">Cookie Policy</a>.</p>
    </div>
    <div class="modal-footer">
      <a href="javascript:cookieConsented(true)" class="modal-close waves-effect waves-green btn-flat">Agree</a>
	  <a href="javascript:cookieConsented(false)" class="modal-close waves-effect waves-green btn-flat">Disagree</a>
    </div>
  </div>`;
var cookieConsentedVal = false;  
document.body.appendChild(modal);
document.addEventListener('DOMContentLoaded', function() {
	if (!localStorage.getItem('cookiesAccepted')) {
		var Modalelem = document.getElementById("cookieConsentModal");//document.querySelector('.modal');
		var options = {dismissible: false, onCloseEnd:function(){
											console.log("closed, cookieConsentedVal is " + cookieConsentedVal);
										}};
		var instance = M.Modal.init(Modalelem, options);
		//var instances = M.Modal.getInstance(elems);
		instance.open();
	}//if (!localStorage.getItem('cookiesAccepted')) {
  });
function cookieConsented(val)
{
	cookieConsentedVal = val;
	if(val)
	{
		 localStorage.setItem('cookiesAccepted', true);
	}
	else
	{
		// redirect to a non cookie page
		window.location.replace("General/allowCookie");
	}
}
