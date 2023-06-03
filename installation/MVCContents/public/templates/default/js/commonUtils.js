//regexp to search all functions is ^\s{4}([^\{\}\(\s\.]+)\(
  // for php ^[^\}\\\n]+function
    //^[^\}\\\n]+function\s+([^\{\}\(\s\.]+)\(
//On Windows Shift + Alt + F  for formatting
class classOSOLMVCommonUtils{
    constructor() { 
        this.modalInst = null;
        this.preloaderModalInst = null;
        this.consoleLogs = "";
    }
    consoleLog(msg)
    {
      console.log(msg);
      this.consoleLogs += "<p>" + msg + "</p>";
      
    }//consoleLog(msg)
    redirect2Page(redirect2)
    {
      window.location.href = redirect2;
    }//redirect2Page(redirect2)
    showConsoleMessages()
    {
      this.initModalDynamic(this);
      this.showModal(this.consoleLogs);
    }//showConsoleMessages()
    showPreloader()
    {
        this.initPreloaderModalDynamic(null);
        this.consoleLog("modalInst opened in classOSOLMVCommonUtils");
        //M.toast({ html: "showPreloader called", displayLength: 2000 });
        //show title #preloaderModalHeader
        /* $("#preloaderModalHeader").html("Sending your message...")
        // show content #preloaderModalContent
        $("#preloaderModalContent").html("Please wait while your message is being sent....");
            //add preloader

        $("#dynamicPreloaderModalContent").prepend("<div class=\"dynamicPreLoader\"></div>");
        $("#preloaderModalFooter").css("display","none"); */
        this.preloaderModalInst.open();
        
    }//showPreloader()
    initPreloaderModalDynamic(initingClass)
      {
          if(this.preloaderModalInst != null) return;
        
          //add modal to html
          
          let preloaderHTML = '<!-- Modal Structure -->';
              preloaderHTML += ' <div id="dynamicPreloaderModal" class="modal">';
              preloaderHTML += '   <div id="modalContent" class="modal-content">';
              preloaderHTML += '       <h4 id="preloaderModalHeader">Modal Header</h4>';
              preloaderHTML += '       <div id="preloaderModalContent"></div>';
              preloaderHTML += '       <div id="dynamicPreloaderModalContent"></div>';
              preloaderHTML += '   </div>';
              preloaderHTML += '   <div id="preloaderModalFooter" class="modal-footer">';
              preloaderHTML += '       <button type="button" id="upkarTopicChoseParent" onclick="upkarTopicController.chooseAmongParentAndChild(\'parent\')"  class="btn waves-effect waves-light">Parent</button>';
              preloaderHTML += '       <button type="button" id="upkarTopicChoseChild" onclick="upkarTopicController.chooseAmongParentAndChild(\'child\')"  class="btn waves-effect waves-light">Child</button>';
              preloaderHTML += '       <a href="javascript:void(0)" id="modalCloseButton" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>';
              preloaderHTML += '   </div>';
              preloaderHTML += ' </div>';
          let preloaderContainer = document.createElement('div');
          preloaderContainer.id = "preloader_container";
          preloaderContainer.innerHTML = preloaderHTML;
          (document.body  || document.documentElement).appendChild(preloaderContainer);
  
          this.consoleLog('document.getElementById("dynamicPreloaderModal") is ' + document.getElementById("dynamicPreloaderModal"));
          var onModalClose = function () {
              //alert("Modal closed!");
              if(initingClass !=null)initingClass.callClosePreloader();
          };
  
          this.preloaderModalInst = M.Modal.init(document.getElementById("dynamicPreloaderModal"), { dismissible: false, onCloseEnd: onModalClose });
  
          //add css
         
        var style = document.createElement('style');
          style.innerHTML = `
                              .dynamicPreLoader {
                                  border-top: 16px solid #00e676;
                                  border-bottom: 16px solid #40c4ff;      
                                  
                                  border-radius: 50%;
                                  width: 120px;
                                  height: 120px;
                                  animation: dynamic-preloader-spin 2s linear infinite;
                                  margin:auto;
                              }
                              #dynamicPreloaderModalContent{
                                  text-align:center;
                              }
                          
                              @keyframes dynamic-preloader-spin {
                                  0% { transform: rotate(0deg); }
                                  100% { transform: rotate(360deg); }
                              }
                          `;
          document.head.appendChild(style);
          
          if (typeof hideCloseButton != "undefined" && hideCloseButton) {
              $("#upkarTopicChoseParent").css("display", "inline-block");
              $("#upkarTopicChoseChild").css("display", "inline-block");
              $("#modalCloseButton").css("display", "none");
          }
          else {
              //hide parent and child button

              $("#upkarTopicChoseParent").css("display", "none");
              $("#upkarTopicChoseChild").css("display", "none");
              $("#modalCloseButton").css("display", "block");
          }//if(typeof hideCloseButton != "undefined" && hideCloseButton)
          $("#preloaderModalFooter").css("display","inline-block");

          

          $("#preloaderModalHeader").html("Sending your message...")
          // show content #preloaderModalContent
          $("#preloaderModalContent").html("Please wait while your message is being sent....");
              //add preloader
          $("#dynamicPreloaderModalContent").prepend("<div class=\"dynamicPreLoader\"></div>");
          $("#preloaderModalFooter").css("display","none");
          this.consoleLog("initPreloader() complete");
          
      }//initPreloaderModalDynamic() 
    
    initModal(){
        if(this.modalInst ==  null)
        {
            var onModalClose = function () {
                //alert("Modal closed!");
                //upkarClsCommonUtils.callCloseDialog();
            };
            this.modalInst = M.Modal.init(document.getElementById("preloaderModal"), { dismissible: false, onCloseEnd: onModalClose });
        }//if(this.modalInst ==  null)
        
    }//showModal(){
        
    initModalDynamic(initingClass)
    {
        if(this.modalInst != null) return;
      
        //add modal to html
        
        let preloaderHTML = '<!-- Modal Structure -->';
            preloaderHTML += ' <div id="preloaderModal" class="modal">';
            preloaderHTML += '   <div id="modalContent" class="modal-content">';
            preloaderHTML += '       <h4 id="preloaderModalHeader">Modal Header</h4>';
            preloaderHTML += '       <div id="preloaderModalContent"></div>';
            preloaderHTML += '       <div id="dynamicPreloaderModalContent"></div>';
            preloaderHTML += '   </div>';
            preloaderHTML += '   <div id="preloaderModalFooter" class="modal-footer">';
            preloaderHTML += '       <button type="button" id="upkarTopicChoseParent" onclick="upkarTopicController.chooseAmongParentAndChild(\'parent\')"  class="btn waves-effect waves-light">Parent</button>';
            preloaderHTML += '       <button type="button" id="upkarTopicChoseChild" onclick="upkarTopicController.chooseAmongParentAndChild(\'child\')"  class="btn waves-effect waves-light">Child</button>';
            preloaderHTML += '       <a href="javascript:void(0)" id="modalCloseButton" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>';
            preloaderHTML += '   </div>';
            preloaderHTML += ' </div>';
        let preloaderContainer = document.createElement('div');
        preloaderContainer.id = "preloader_container";
        preloaderContainer.innerHTML = preloaderHTML;
        (document.body  || document.documentElement).appendChild(preloaderContainer);

        this.consoleLog('document.getElementById("preloaderModal") is ' + document.getElementById("preloaderModal"));
        var onModalClose = function () {
            //alert("Modal closed!");
            if(initingClass !=null)initingClass.callCloseDialog();
        };

        this.modalInst = M.Modal.init(document.getElementById("preloaderModal"), { dismissible: false, onCloseEnd: onModalClose });

        //add css
       
        this.consoleLog("initModal() complete");
        
    }//initModal()
    getSiteBaseURL(savedLink)
    {
      //new element link with your link
      var a = document.createElement("a");
      a.href=savedLink;//"http://www.sitename.com/article/2009/09/14/this-is-an-article/";
      
      //hide it from view when it is added
      a.style.display="none";
      
      //add it
      document.body.appendChild(a);
      
      //read the links "features"
      /* alert(a.protocol);
      alert(a.hostname)
      alert(a.pathname)
      alert(a.search);//query string
      alert(a.port);
      alert(a.hash); */
      let siteBase = a.protocol + "//"+a.hostname + "/" +  a.port;
      let withPath = arguments[1];
      if(typeof withPath != "undefined" && withPath == true)
      {
        siteBase += "/" + a.pathname;
        let withQueryString = arguments[2];
        if(typeof withQueryString != "undefined" && withQueryString == true)
        {
            siteBase +=  a.search;
        }//if(typeof withPath != "undefined" && withPath == true)
      }//if(typeof withPath != "undefined" && withPath == true)
      
      
      //remove it
      document.body.removeChild(a);
      return siteBase;
    }//getSiteBaseURL()
    ajaxPost(url2Post, data2Post, /*optional 3rd argument 'json'*/)
    {
      //https://stackoverflow.com/questions/13333378/how-can-javascript-upload-a-blob
      OSOLMVCClsCommonUtils.showPreloader();
      let data4FetchPost = {
        method:"POST",
        credentials: "include"
      };
      //if 3rd argument explicily says json, post as json
      //in that case 'data2Post' should be simple object, not of type 'FormData', eg: {elementHTML:elementHTML}
      if(typeof arguments[2] != 'undefined' && arguments[2].toLowerCase() == "json")
      {
        var headers2Post = {
                          //'Accept': 'application/json',
                          "Content-Type": "application/json"/* ,                                                                                                
                          "Access-Control-Origin": "*" */
                      };
          //this.consoleLog("formData is " + data2Post.elementHTML);
          data4FetchPost.body = JSON.stringify(formData);
          //this.consoleLog("post body is " + data4FetchPost.body);
          data4FetchPost.headers = headers2Post;
      }
      else // otherwise do simple post
      {
        
        data4FetchPost.body = data2Post
      }//if(arguments[2] != 'undefined' && arguments[2].toLowerCase() == "json")
      
     
      fetch(url2Post, 
            data4FetchPost
            )
      .then(response => {
          if (response.ok) return response.json();
          else throw Error(`Server returned ${response.status}: ${response.statusText}`)
      })
      .then(response => {
        //console.log(JSON.stringify(response))
        let toastClass = (response.status=="error"?"red":"green");
        OSOLMVCClsCommonUtils.preloaderModalInst.close();
        M.toast({ html: response.message, displayLength: 2000,classes:toastClass });
      })
      .catch(err => {
          //alert(err);
          OSOLMVCClsCommonUtils.preloaderModalInst.close();
          M.toast({ html: err, displayLength: 2000,classes:"red" });
      });
    }//ajaxPost(blob)
    postJSON(postAttributesJSON)
    {
        let postToURL = postAttributesJSON.postToURL;
        let formData = JSON.stringify(postAttributesJSON.formData);
		this.consoleLog("formData is "+ formData);
        let redirect2OnSuccess = postAttributesJSON.redirect2OnSuccess;
        let onSuccessDo = postAttributesJSON.onSuccessDo;
        let onSuccessDoOnClass = postAttributesJSON.onSuccessDoOnClass;
        let classOfPreloaderInstance = postAttributesJSON.classOfPreloaderInstance;// to call .modalInst.close
        //return jSON should have 2 vars , response.status and response.message

        classOfPreloaderInstance.showPreloader();
        fetch(postToURL, {
            method: 'POST',
            credentials: "include",
            body: formData,
            //,headers: new Headers({ "content-type": "application/multipart/form-data; charset=UTF-8" })
            headers:new Headers({ "Content-Type" : "application/json" })// Set the request header i.e. which type of content you are sending
          })
          .then(responseObj => {
            
            //this block is not redundant
            let response = responseObj;
            const contentType = response.headers.get("content-type");
    
            //CODE BLOCK TO SEE CONTENT OF RESPONSE FROM SERVER
            // SHOULD BE COMMENTED AFTER TESTEING OTHERWISE WON'T WORK IN LAST 'then' BLOCK
            /* 
            if (contentType && contentType.indexOf("application/json") !== -1) {
              return response.json().then(data => {
                // process your JSON data further
                this.consoleLog("JSON returned is " + data);
              });
            } else {
              return response.text().then(text => {
                // this is text, do something with it
                this.consoleLog("text returned is " + text);
              });
            }
            */
            //this block is not redundant
            // this is required to get past a chrome bug
            //https://stackoverflow.com/questions/47177053/strange-behaviour-of-php-session-start-when-javascript-fetch
            //this.consoleLog("response.status is "+ responseObj.status)
            //this.consoleLog("response.json is "+ responseObj.json())
           
            if (responseObj.status !== 200) {
              
             let errorMessage = `There was an error, please try again later ($(responseObj.status) `;
              this.consoleLog(errorMessage);
              M.toast({ html: errorMessage, displayLength: 2000,classes:"red" });
              return null;
            }//if (responseObj.status !== 200) 
            return responseObj.json();
          })
          .then(response => {
            //this.consoleLog('Success');
            //this.consoleLog('POST Success:', JSON.stringify(response));
            this.consoleLog('POST Success:' + JSON.stringify(response));
            //return jSON shoul have 2 vars , response.status and response.message
			if(typeof classOfPreloaderInstance != undefined)
			{
				classOfPreloaderInstance.preloaderModalInst.close();
			}//if(typeof classOfPreloaderInstance != undefined)
			
            if (response.status === "success") {
              
             let succesMessage = response.message;
             this.consoleLog( succesMessage);
              M.toast({ html: succesMessage, displayLength: 2000,classes:"green" });
              if(typeof onSuccessDo != "undefined")
              {
                onSuccessDoOnClass[onSuccessDo](response);
              }//if(typeof onSuccessDo != "undefined")
              if(typeof redirect2OnSuccess != "undefined")
              {
                setTimeout( function(){window.location.href = redirect2OnSuccess;},2000);
              }//if(typeof postAttributesJSON.redirect2OnSuccess != "undefined")
              
            }//if (response.status === "success") {
            
            if (response.status === "error") {
              let errorMessage = response.message;
              this.consoleLog( errorMessage);
              M.toast({ html: errorMessage, displayLength: 2000,classes:"red" });
              return;
              
            }
			
             
          })
          //.then(response => response.json())
          .catch(error => {
            classOfPreloaderInstance.preloaderModalInst.close();
            console.error('Error:', (error));
            M.toast({ html: error, displayLength: 2000,classes:"red" });
            //return Promise.reject();
          });
    }//postJSON()
    showModal(message) {
        let hideCloseButton = arguments[1];
        //this.consoleLog("topiController.showModal called, hideCloseButton IS " + hideCloseButton)
        //M.toast({ html: "showPreloader called", displayLength: 2000 });
        //show title #preloaderModalHeader
        this.initModal();
        $("#preloaderModalHeader").html("Attention, user")
        // show content #preloaderModalContent
        //"Please wait while your message is being sent...."
        $("#preloaderModalContent").html(message);
        //add preloader
        //$("#preloaderModalContent").prepend("<div class=\"customPreLoader\"></div>");
        //$("#preloaderModalFooter").css("display","none");
        if (typeof hideCloseButton != "undefined" && hideCloseButton) {
            $("#upkarTopicChoseParent").css("display", "inline-block");
            $("#upkarTopicChoseChild").css("display", "inline-block");
            $("#modalCloseButton").css("display", "none");
        }
        else {
            //hide parent and child button

            $("#upkarTopicChoseParent").css("display", "none");
            $("#upkarTopicChoseChild").css("display", "none");
            $("#modalCloseButton").css("display", "block");
        }//if(typeof hideCloseButton != "undefined" && hideCloseButton)
        $("#preloaderModalFooter").css("display","inline-block");
        this.modalInst.open();
    }//showModal(message)
    showEditTopicModal()
    {
        $("#editTopicModalHeader").html("Attention, user")
        // show content #preloaderModalContent
        //"Please wait while your message is being sent...."
        let clsLangInst = clsUpkarLang.getInstance();
        let message2Display = this.getSelectedNodeText();
        $("#editTopicModalMessage").html(clsLangInst.ADD_CHILD_NODE_TO_NODE + message2Display);//"Edit Topi form will come here"
        this.editTopicModalInst.open();
    }//showEditTopicModal()
    cleanJSONStr(JSONStr)
    {
        var br2nl = JSONStr;//
        var replace_br2nl = arguments[1];
        if(typeof replace_br2nl != "undefined" && replace_br2nl == true )
        {
            br2nl = br2nl.replace(/<br\s*[\/]?>/gi, "\\n").replace(/(<([^>]+)>)/gi, "");
        }//if(typeof replace_nl2br != "undefined" && replace_nl2br == true )
        br2nl = br2nl.replace(/\\n/g, "\\n")  
                .replace(/\\\\/g, "\\")
                .replace(/\\'/g, "\\'")
                .replace(/\\"/g, '\\"')
                .replace(/\\&/g, "\\&")
                .replace(/\\r/g, "\\r")
                .replace(/\\t/g, "\\t")
                .replace(/\\b/g, "\\b")
                .replace(/\\f/g, "\\f");
        // remove non-printable and other non-valid JSON chars
        br2nl = br2nl.replace(/[\u0000-\u0019]+/g,""); 
        return br2nl;
    }//
    /* Touchpunch (https://github.com/yeco/jquery-ui-touch-punch) code starts here */
    //This code is required for drag and drop sortable to work in mobile https://stackoverflow.com/questions/23595081/jquery-ui-drag-and-drop-not-work-on-mobile
    touchHandler(event) {
        // Iterate through the touch points that were activiated
          // for this element.
          let e =  event;
          // Register a touchmove listener for the 'source' element
        /*var src = document.getElementById("saveNewOrderingButton");
        
        src.addEventListener('touchstart', function(e) {
          // Iterate through the touch points that were activiated
          // for this element.
          for (var i=0; i < e.targetTouches.length; i++) {
            console.log("touchpoint[" + i + "].target = " + e.targetTouches[i].target);
            //touchpoint[0].target = javascript:favouritesConroller.submitNewOrdering()
          }
        }, false);*/
        //Cannot read property 'target' of undefined
          if(typeof e.targetTouches != "undefined" && typeof e.targetTouches[0] != "undefined" && e.targetTouches[0].target == "javascript:favouritesConroller.submitNewOrdering()")
          {
              console.log("submitNewOrdering detected, not proceeding..");
              return;
          }/**/
          
          
        var touch = event.changedTouches[0];

        var simulatedEvent = document.createEvent("MouseEvent");
            simulatedEvent.initMouseEvent({
            touchstart: "mousedown",
            touchmove: "mousemove",
            touchend: "mouseup"
        }[event.type], true, true, window, 1,
            touch.screenX, touch.screenY,
            touch.clientX, touch.clientY, false,
            false, false, false, 0, null);

        touch.target.dispatchEvent(simulatedEvent);
        if (e.changedTouches.length > 1) e.preventDefault();
        
    }//touchHandler(event) {

    initTouchPunch() {
        document.addEventListener("touchstart", this.touchHandler, true);
        document.addEventListener("touchmove", this.touchHandler, true);
        document.addEventListener("touchend", this.touchHandler, true);
        document.addEventListener("touchcancel", this.touchHandler, true);
    }
    /* Touchpunch code ends here */
	
	validateEmail(email) {
        var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var OK = emailRegex.exec(email);
        if (!OK) {
          return false;
        } else {
          return true;
        }
      }
}//class classOSOLMVCommonUtils{
var OSOLMVCClsCommonUtils;
var clsOSOLMVCommonUtils = {
    inst:null,
    getInstance:function()
    {
        if(this.inst == null)
        {
            this.inst = new classOSOLMVCommonUtils();
            this.inst.initModal();
        }//if(this.inst == null)
        return this.inst ;
    }//getInstance:funciion()
}//var clsCommonUtils = {	
OSOLMVCClsCommonUtils = clsOSOLMVCommonUtils.getInstance();
