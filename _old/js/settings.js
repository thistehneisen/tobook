$(document).ready(function () { 
    var activeIndex=parseInt($('#hidIndex').val());
    
    $( "#accordion" ).accordion({
        collapsible: true,
        active: activeIndex
    });
    
    $('.jDisplayIcon').click(function(){
        var selectedheader    =   $(this).attr('val');
        var imgtype           =   $('#img_'+selectedheader).attr('type');
        if(imgtype=='close'){
            var typename  ='open';
        }
        else{
            var typename  ='close';
        }
        $('.jimage').attr('src','../style/images/accordian-arrow-close.png');
        $('.jimage').attr('type','close');
        $('#img_'+selectedheader).attr('src','../style/images/accordian-arrow-'+typename+'.png');
        $('#img_'+selectedheader).attr('type',typename);
    });
    
    $('#jSocialShare').click(function(){
        if($(this).is(':checked')){
            $('#jDisplaySocialShare').show();
        }
        else{
            $('#jDisplaySocialShare').hide();
        }
       
    //       if($.('#jSocialShare'). )
    //       $('#jDisplaySocialShare').show();
    });

    $('#paymentsupport').click(function(){
        $('#priceTr').slideDown('slow');
        $('#paymentTab').slideDown('slow');
    });

    $('#paymentsupport1').click(function(){
        $('#priceTr').slideUp('slow');
        $('#paymentTab').slideUp('slow');
    });


    $('.jqToggle').click(function(){ 
        showHideDiv(this);
    });
    hideDivOnLoad();
   
});


function checkMail(email)
{
    var str1=email;
    var arr=str1.split('@');
    var eFlag=true;
    if(arr.length != 2)
    {
        eFlag = false;
    }
    else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
    {
        eFlag = false;
    }
    else
    {
        var dot=arr[1].split('.');
        if(dot.length < 2)
        {
            eFlag = false;
        }
        else
        {
            if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
            {
                eFlag = false;
            }

            for(i=1;i < dot.length;i++)
            {
                if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1 || dot[i].length > 4)
                {
                    eFlag = false;
                }
            }
        }
    }
    return eFlag;
}

function validate(){
    var frm = document.settingsForm;

    var error = false;
    var message = "";

    if(document.settingsForm.day_maintain_temp.value==""){
        alert(VALMSG_TEMP_DAYS_EMP);
        document.settingsForm.day_maintain_temp.focus();
        return false;

    }else if(isNumeric(document.settingsForm.day_maintain_temp.value)==false){
        alert(VALMSG_ENTER_NUM_DIGITS);
        document.settingsForm.day_maintain_temp.focus();
        return false;
        
    }else if(document.settingsForm.site_name.value==""){
        alert(VALMSG_SITE_NAME_EMP);
        document.settingsForm.site_name.focus();
        return false;

    }else if(document.settingsForm.admin_mail.value==""){
        alert(VALMSG_ADMIN_MAIL_EMP);
        document.settingsForm.admin_mail.focus();
        return false;

    }
    else if(document.settingsForm.theme.value==""){
        alert(VALMSG_THEME_EMP);
        document.settingsForm.theme.focus();
        return false;
    }
    else if(checkMail(document.settingsForm.admin_mail.value)==false){
        alert(VALMSG_EMAIL_INVALID);
        document.settingsForm.admin_mail.focus();
        return false;

    }else if(document.settingsForm.root_directory.value==""){
        alert(VALMSG_ROOT_DIR);
        document.settingsForm.root_directory.focus();
        return false;

    }else{
        if (document.getElementById("paymentsupport").checked) {
            if(document.settingsForm.site_price.value=="" ){
                error = true;
                message += "* "+VALMSG_PRICE_EMP+"!" + "\n";
            }
            /*
            if(isNumeric(document.settingsForm.site_price.value)==false){
                error = true;
                message += "* "+VALMSG_ENTER_NUM_DIGITS+" !" + "\n";
            } */

            if (! (/^\d*(?:\.\d{0,2})?$/.test(document.settingsForm.site_price.value))) {
                error = true;
                message += "* "+VALMSG_ENTER_NUM_DIGITS+" !" + "\n";
            }
        }

    }
    if(error){
        message = VALMSG_ERROR_FOUND+" !" + "\n" + message;
        alert(message);
        return false;
    } else {
        document.settingsForm.submit();
    }
}



function validateCreatedSite(){
    
    var frm = document.createdSiteSettingsForm;

    var error = false;
    var message = "";  

    var bannerVal = '';
    if(frm.created_site_banner_value.value!='') bannerVal = frm.created_site_banner_value.value;
    if(frm.created_site_banner_name.value!='')  bannerVal = frm.created_site_banner_name.value;

    if(frm.enable_created_site_banner.checked && bannerVal=='' ){
        error = true;
        message += "* "+VALMSG_SEL_BANNER_UPLOAD+ " !" + "\n";
    }
    if(frm.enable_created_site_banner.checked && frm.created_site_banner_link.value=='' ){
        error = true;
        message += "* "+VALMSG_ENTER_BANNER_LINK+" !" + "\n";
    }
        
    if(error){
        message = VALMSG_ERROR_FOUND+" !" + "\n" + message;
        alert(message);
        return false;
    } else {
        document.createdSiteSettingsForm.submit();
    }
}


function paymentValidate(){
    var frm = document.paymentSettingsForm;

    var error = false;
    var message = "";
    var paymentSupport = '<?php echo $paymentsupport; ?>';

    if (paymentSupport=='yes' && !frm.enable_paypal.checked
        && !frm.enable_google.checked && frm.enable_gateways.options[frm.enable_gateways.options.selectedIndex].value == "NO") {
        error = true;
        message += "* "+VALMSG_SEL_PAYMNT_METHOD+" !" + "\n";
    } else {
        if (paymentSupport=='yes') {
            if(document.paymentSettingsForm.secureserver.value==""){
                error = true;
                message += "* "+VALMSG_SERVER_URL_EMP+" !" + "\n";
            }
        }
        if(frm.enable_paypal.checked  && paymentSupport=='yes'){//if paypal, check paypal email id
            if(frm.paypal_email.value.length==0){
                error = true;
                message += "* "+VALMSG_PP_EMAIL_ADDRES+" !" + "\n";
            }else if(!checkMail(frm.paypal_email.value)){
                error = true;
                message += "* "+VALMSG_PP_EMAIL_INVALID+" !" + "\n";
            }
            if (frm.paypal_token.value.length==0) {
                error = true;
                message += "* "+VALMSG_PP_TOKEN_EMP+" !" + "\n";
            }
        }
        if(frm.enable_google.checked  && paymentSupport=='yes'){//if google checkout, check google id & key
            if(frm.google_id.value.length==0){
                error = true;
                message += "* "+VALMSG_GC_MERCHNT_ID+" !" + "\n";
            }
            if (frm.google_key.value.length==0) {
                error = true;
                message += "* "+VALMSG_GC_MERCHNT_KEY+" !" + "\n";
            }
        }
        if(frm.enable_gateways.options[frm.enable_gateways.options.selectedIndex].value == "AN"  && paymentSupport=='yes'){
            if(frm.auth_loginid.value.length==0){
                error = true;
                message += "* "+VALMSG_AUTH_LOGIN_ID+" !" + "\n";
            }
            if(frm.auth_txnkey.value.length==0){
                error = true;
                message += "* "+VALMSG_AUTH_TRANS_KEY+" !" + "\n";
            }
            if(frm.auth_pass.value.length==0){
                error = true;
                message += "* "+VALMSG_AUTH_PSWD+" !" + "\n";
            }
            if(frm.auth_email.value.length==0){
                error = true;
                message += "* "+VALMSG_AUTH_EMAIL_ADDR+" !" + "\n";
            }
        }else if(frm.enable_gateways.options[frm.enable_gateways.options.selectedIndex].value == "CO"  && paymentSupport=='yes'){
            if(frm.checkout_key.value.length==0){
                error = true;
                message += "* "+VALMSG_TC_TRANS_KEY+" !" + "\n";
            }
            if(frm.checkout_productid.value.length==0){
                error = true;
                message += "* "+VALMSG_TC_PRODUCT_ID+" !" + "\n";
            }
        }else if(frm.enable_gateways.options[frm.enable_gateways.options.selectedIndex].value == "LP"  && paymentSupport=='yes'){
            if(frm.linkpay_store.value.length==0){
                error = true;
                message += "* "+VALMSG_LP_STORE_NUM+" !" + "\n";
            }
        }
    }
    if(error){
        message = VALMSG_ERROR_FOUND+" !" + "\n" + message;
        alert(message);
        return false;
    } else {
        document.paymentSettingsForm.submit();
    }
}

function isNumeric(sText){

    var IsNumber=true;
    var Char;
    for (i = 0; i < sText.length; i++)
    {
        Char = sText.charAt(i);
        if (Char != "1" && Char != "2" && Char != "3" && Char != "4" && Char != "5" && Char != "6" && Char != "7" && Char != "8" && Char != "9" && Char != "0")
        {
            IsNumber = false;
        }
    }
    return IsNumber;
}

function isNumber(val){
    if((val.indexOf("e") != -1 ) || (val.indexOf("E") != -1 )){
        return false;
    }
    if(isNaN(val)){
        return false;
    }
    return true;
}

function checkNumber(t) {
    if(t.value.length == 0 ||  isNaN(t.value) || t.value.substr(0,1) == " " || parseInt(t.value) < 0) {
        t.value="";
    }
}

function changePass(){

    if($("#password").val()==""){
        alert(VALMSG_ENTER_NEW_PSWD);
        $("#password").focus();
        return false;
    }else if($("#confirmpassword").val()==""){
        alert(VALMSG_ENTER_CONF_PSWD);
        $("#confirmpassword").focus();
        return false;
    }else if($("#password").val() != $("#confirmpassword").val()){
        alert(VALMSG_PSWD_MATCH);
        $("#confirmpassword").focus();
        return false;
    }else{
        document.getElementById('passwordForm').submit()
    //document.passwordForm.submit();
    }
}

function cleanUp(){

    conf=confirm(VALMSG_DELETE_OLD_FILES);
    if(conf){
        document.cleanupForm.submit();
    }
}
function checkpayment1(){
    document.getElementById("site_price").disabled=true;
    document.getElementById("enable_paypal").disabled=true;
    document.getElementById("paypal_sandbox").disabled=true;
    document.getElementById("paypal_email").disabled=true;
    //  document.getElementById("paypal_token").disabled=true;
    document.getElementById("enable_gateways").disabled=true;
    //document.getElementById("enable_gateways").value="NO";
    document.getElementById("secureserver").disabled=true;
    document.getElementById("enable_google").disabled=true;
    document.getElementById("google_demo").disabled=true;
    document.getElementById("google_id").disabled=true;
    document.getElementById("google_key").disabled=true;
    document.getElementById("enable_gateways_backup").value=document.getElementById("enable_gateways").value;
    document.getElementById("enable_gateways").value = 'NO';
    displayDetails(document.getElementById("enable_gateways").value);

}
function checkpayment(){

    document.getElementById("site_price").disabled=false;
    document.getElementById("enable_paypal").disabled=false;
    document.getElementById("paypal_sandbox").disabled=false;
    document.getElementById("paypal_email").disabled=false;
    // document.getElementById("paypal_token").disabled=false;
    document.getElementById("enable_gateways").disabled=false;
    document.getElementById("secureserver").disabled=false;
    document.getElementById("enable_google").disabled=false;
    document.getElementById("google_demo").disabled=false;
    document.getElementById("google_id").disabled=false;
    document.getElementById("google_key").disabled=false;
    document.getElementById("enable_gateways").value=document.getElementById("enable_gateways_backup").value;
    displayDetails(document.getElementById("enable_gateways").value);

}
function displayDetails(val){
    var frm = document.paymentSettingsForm;
    for(i=0;i<frm.enable_gateways.options.length;i++){
        if(frm.enable_gateways.options[i].value == val){
            document.getElementById(frm.enable_gateways.options[i].value).style.display='';
        }else{
            document.getElementById(frm.enable_gateways.options[i].value).style.display='none';
        }
    }
}

function checkFieldStatus(field){ 
    
    var fieldNamecheck   = field+'_check';  
    var fieldNameMandat   = field+'_mandatory'; 
    if(document.getElementById(fieldNamecheck).checked)
        return true;
    
    else{
        if(document.getElementById(fieldNameMandat).checked == false){
            
        }else{
            document.getElementById(fieldNameMandat).checked = false;
            alert(VALMSG_ENABLE_FIELD);
            return false;
        }
    }
}



function showHideDiv(elem)
{
    var divCls;
    if($(elem).attr('checked')){
        divCls = $(elem).attr('id');
        $('.'+divCls).fadeIn('slow');
    }
    else{
        divCls = $(elem).attr('id');
        $('.'+divCls).fadeOut('slow');
    }
}

function hideDivOnLoad()
{
    var divCls;
    $('.jqToggle').each(function(i, j){
        if(!$(j).attr('checked')){
            divCls = $(j).attr('id');
            $('.'+divCls).fadeOut('fast');
        }
    });
}


