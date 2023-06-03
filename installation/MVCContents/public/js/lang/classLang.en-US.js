class classOSOLMVCLang_en_US{
    constructor() {  // Constructor
        this.NO_IMAGE_OF_THIS_FAVOURITE = "No Image was available";
        this.ADD_CHILD_NODE_TO_NODE = "Add child node to node: ";
        this.PLEASE_WAIT_WHILE_SUBMITTING_LINK = "Please wait while the link is being submitted...";
        this.INCORRECT_CAPTCHA = "The captcha string you entered was incorrect";
      }

}//class classUpkarLang
clsOSOLMVC_en_US = {
    inst:null,
    getInstance:function()
    {
        if(this.inst == null)
        {
            this.inst =  new classOSOLMVCLang_en_US();
        }//if(this.inst == null)
        return this.inst;
    }//getInstance:function()
}//clsUpkarLang
OSOLMVCls_en_US = clsOSOLMVC_en_US.getInstance();