class BasicFormOps{
    validateForm(formId){

    }//validateForm(){
      
}//class BasicFormOps{

class ContactUs extends BasicFormOps {
    postURL = "includes/postContactUs.php";
    modalInst = M.Modal.init(document.getElementById("preloaderModal"), {dismissible: false});
    constructor(urt2Post) {
      super();
      this.postURL = urt2Post;
    }
    prefillForm()
    {
        document.getElementById("email").value = "legal@modestoffers.com";
        document.getElementById("message").value = "Testing OSOL MVC  mail";
        document.getElementById("osolmvc_keystring").value = "";
    }
    validateEmail(email) {
        var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var OK = emailRegex.exec(email);
        if (!OK) {
          return false;
        } else {
          return true;
        }
      }
    validateForm(){
		
        var data = {
            email: document.getElementById("email").value.trim(),
            message: document.getElementById("message").value.trim(),
            keystring: document.getElementById("osolmvc_keystring").value.trim()
          };
        let validForm =  true;
          // Input length validation
        if (data.email.length == 0 || !this.validateEmail(data.email)) {
            M.toast({ html: "Please enter a valid email", displayLength: 2000 });
            document.getElementById("email").focus();
            validForm =  false;
        } else if (data.message.length == 0) {
            M.toast({ html: "Please enter a message", displayLength: 2000 });
            validForm =  false;
        } else if (data.keystring.length == 0) {
            M.toast({ html: "Please enter security text", displayLength: 2000 });
            validForm =  false;
        }
          return validForm;
          
    }//validateForm
    sendEmail(){
        if(this.validateForm())
        {
			this.verifyCaptcha();
			//alert("Verify Captcha Complete!!!!");
            //this.post2backend();
        }//if(this.validateForm())
    }//sendEmail(){
	verifyCaptcha()
	{
		/* let postToURL = postAttributesJSON.postToURL;
        let formData = JSON.stringify(postAttributesJSON.formData);
        let redirect2OnSuccess = postAttributesJSON.redirect2OnSuccess;
        let onSuccessDo = postAttributesJSON.onSuccessDo;
        let onSuccessDoOnClass = postAttributesJSON.onSuccessDoOnClass;
        let classOfPreloaderInstance = postAttributesJSON.classOfPreloaderInstance;// to call .modalInst.close */
		var postAttributesJSON ={};
		postAttributesJSON.postToURL = "Contact/verifyCaptcha";
		let formData = {'osolmvc_keystring':document.getElementById('osolmvc_keystring').value};
		postAttributesJSON.formData = formData;
		//postAttributesJSON.redirect2OnSuccess = '';
		postAttributesJSON.onSuccessDo = 'post2backend';
		postAttributesJSON.onSuccessDoOnClass = this;
		postAttributesJSON.OSOLMVCClsCommonUtils = this;
		postAttributesJSON.classOfPreloaderInstance = OSOLMVCClsCommonUtils;
		
		OSOLMVCClsCommonUtils.postJSON(postAttributesJSON);
	}//verifyCaptcha()
    showPreloader()
    {
        //M.toast({ html: "showPreloader called", displayLength: 2000 });
        //show title #preloaderModalHeader
        $("#preloaderModalHeader").html("Sending your message...")
        // show content #preloaderModalContent
        $("#preloaderModalContent").html("Please wait while your message is being sent....");
            //add preloader
        $("#preloaderModalContent").prepend("<div class=\"customPreLoader\"></div>");
        $("#preloaderModalFooter").css("display","none");
        this.modalInst.open();
    }//showPreloader()
    getFormDataAsJSON(){

        const getFormData = () => {
          const form = document.getElementById("contactForm");
          return new FormData(form);
        }

        const toJson = function() {
          const formData = getFormData();
          let object = {};
          formData.forEach((value, key) => {
            if (!Reflect.has(object, key)) {
              object[key] = value;
              return;
            }
            if (!Array.isArray(object[key])) {
              object[key] = [object[key]];
            }
            object[key].push(value);
          });
          let json = JSON.stringify(object);
          //console.log(json);
          return json;
        };
        return toJson();
    }
    post2backend(){
		
        this.showPreloader();
        let stringifiedFormData = this.getFormDataAsJSON();
        //console.log(stringifiedFormData);
        let stringVariable = window.location.href;
        //let postURL = stringVariable.substring(0, stringVariable.lastIndexOf('/')) + "/contactUs.php?rer=sd";
        let postURL = "Contact/submit";
        //console.log(postURL);
        let serializedFormData = $("#contactForm").serialize();
        console.log(serializedFormData);
        let contactUs = this;


        const getFormData = () => {
          const form = document.getElementById("contactForm");
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
            
           contactUs.modalInst.close();
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
          
          contactUs.modalInst.close();
          if (response.status === "error") {
            M.toast({ html: response.message, displayLength: 2000,classes:"red" });
            if(response.message == "Invalid Captcha(Security Text)")refreshCaptcha();
            return;
          }
          refreshCaptcha();  
          M.toast({ html: "Mail sent!!!<br /> Our representative will get back to you within 24 hours", displayLength: 2000,classes:"green" });
            
            
        })
        //.then(response => response.json())
        .catch(error => {
          console.error('Error:', (error));
        });
        return false;
        fetch(postURL, {
          method: "POST",
          credentials: "include",
          body: serializedFormData,//stringifiedFormData,
          cache: "no-cache",
          //headers: new Headers({ "content-type": "application/json; charset=UTF-8" })
          headers: new Headers({ "content-type": "application/x-www-form-urlencoded; charset=UTF-8" })
        })
          .then(function(response) {
            //console.log(JSON.stringify(response));
            console.log(response);
            if (response.status !== 200) {
              M.toast({
                html:
                  "There was an error, please try again later (" +
                  response.status +
                  ")",
                displayLength: 2000
              });
              return;
            }
            /* response.json().then(function(data) {
              console.log(JSON.stringify(data));
              
              contactUs.modalInst.close();
              if (data.response == "mail_sent") {
                M.toast({ html: "Mail sent. Our representative will get back to you within 24 hours", displayLength: 2000 });
                
              }
              else
              {
                M.toast({ html: "It seems the message wasn't sent , please try again..", displayLength: 2000 });
              }
            }); */
          })
          .catch(function(error) {
            M.toast({
              html: "There was an error, please try again later",
              displayLength: 2000
            });
            console.log("Fetch error: " + error);
          }); // end fetch

    }//post2backend();
        

  }//class ContactUs extends BasicFormOps