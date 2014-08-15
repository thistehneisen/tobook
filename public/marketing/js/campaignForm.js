$(document).ready(function() {

});
function onSelectEmailTemplate(obj) {
    $(obj).parents("#emailTemplateList").eq(0).find("div#emailTemplateItem")
            .removeClass("greyBackground");
    $(obj).addClass("greyBackground");
}
function onShowEmailTemplatePopup() {
    $("#divBlackBackground").fadeIn();
    $("#emailTemplatePopup").fadeIn();
}
function onCloseEmailTemplatePopup() {
    $("div#emailTemplateList").find("div#emailTemplateItem").removeClass(
            "greyBackground");
    $("#divBlackBackground").fadeOut();
    $("#emailTemplatePopup").fadeOut();
}
function onChooseEmailTemplate() {
    var obj = $("div#emailTemplateList").find("div.greyBackground");
    var ind = $("div#emailTemplateList").find("div#emailTemplateItem").index(
            obj);
    if (ind == -1) {
        alert("Please select the Email Template.");
    } else {
        var emailId = obj.find("#emailId").val();
        $.ajax({
            url : "async-getEmailTemplate.php",
            dataType : "json",
            type : "POST",
            data : {
                emailId : emailId
            },
            success : function(data) {
                if (data.result == "success") {
                    $('#txtEmail').data('liveEdit').putHTML(data.content);
                    onCloseEmailTemplatePopup();
                }
            }
        });
    }
}
function onSaveCampaign() {
    var replyEmail = $("#replyEmail").val();
    var replyName = $("#replyName").val();
    var subject = $("#txtSubject").val();
    var content = $('#txtEmail').data('liveEdit').getXHTML();
    if (subject == "") {
        alert("Please enter Email Subject.");
        return;
    }
    if (replyEmail == "") {
        alert("Please enter Reply Email.");
        return;
    }
    if (replyName == "") {
        alert("Please enter Email Name.");
        return;
    }
    var ownerId = $("#ownerId").val();
    var campaignId = $("#campaignId").val();
    $.ajax({
        url : "async-saveCampaign.php",
        dataType : "json",
        type : "POST",
        data : {
            subject : subject,
            content : content,
            ownerId : ownerId,
            campaignId : campaignId,
            replyEmail : replyEmail,
            replyName : replyName
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Campaign saved successfully.");
                window.location.href = "campaignList.php";
            }
        }
    });

}