$(document).ready(function() {
    $('#scheduleDate').datepicker({
        format : 'yyyy-mm-dd',
        showToday : true
    });
    $('#tblGroupList').footable();

    $("#txtSMS").keyup(function() {
        $("#lengthSMSText").text($("#txtSMS").val().length);
    });
    $("#titleSMS").keyup(function() {
        $("#lengthSMSTitle").text($("#titleSMS").val().length);
    });
});
function onCheckAllGroup(obj) {
    var status = obj.checked;
    $(obj).parents("table").eq(0).find("input:checkbox")
            .prop("checked", status);
}
function onDeleteGroup() {
    var objChkList = $("#groupList").find("input#chkGroup:checked");
    var strGroupIds = "";
    for ( var i = 0; i < objChkList.size(); i++) {
        var groupId = objChkList.eq(i).parents("td").eq(0).find("#groupId")
                .val();
        strGroupIds += groupId + ",";
    }
    if (strGroupIds != "") {
        strGroupIds = strGroupIds.substr(0, strGroupIds.length - 1);
    } else {
        alert("Please Select Group to delete.");
        return;
    }
    if (!confirm("Are you sure!"))
        return;
    $.ajax({
        url : "async-deleteGroup.php",
        dataType : "json",
        type : "POST",
        data : {
            groupIds : strGroupIds
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Group deleted successfully.");
                window.location.reload();
            }
        }
    });
}

function onOpenEmail() {
    $("#divEmailArea").fadeIn();
    $("#divBlackBackground").fadeIn();
    $("#campaignList").find("div#campaignItem").attr("class", "");
}
function onOpenSMS() {
    $("#titleSMS").val("");
    $("#txtSMS").val("");
    $("#titleSMS").keyup();
    $("#txtSMS").keyup();
    $("#divSMSArea").fadeIn();
    $("#divBlackBackground").fadeIn();
    $("#titleSMS").focus();
}

function onCloseEmail() {
    $("#divEmailArea").fadeOut();
    $("#divBlackBackground").fadeOut();
}
function onCloseSMS() {
    $("#divSMSArea").fadeOut();
    $("#divBlackBackground").fadeOut();
}

function onClickCampaignItem(obj) {
    $("#campaignList").find("div#campaignItem").attr("class", "");
    $(obj).addClass("campaignItemSelected");
}

function onSendSMS() {
    var objChkList = $("#groupList").find("input#chkGroup:checked");

    var strGroupIds = "";
    for ( var i = 0; i < objChkList.size(); i++) {
        var groupId = objChkList.eq(i).parents("td").eq(0).find("#groupId")
                .val();
        strGroupIds += groupId + ",";
    }
    if (strGroupIds != "") {
        strGroupIds = strGroupIds.substr(0, strGroupIds.length - 1);
    } else {
        alert("Please Select Group to delete.");
        return;
    }

    var ownerId = $("#ownerId").val();
    var title = $("#titleSMS").val();
    var content = $("#txtSMS").val();

    $.ajax({
        url : "async-sendGroupSMS.php",
        dataType : "json",
        type : "POST",
        data : {
            ownerId : ownerId,
            title : title,
            content : content,
            groupIds : strGroupIds
        },
        success : function(data) {
            if (data.result == "success") {
                alert("SMS will sent.");
                onCloseSMS();
            } else {
                alert("Credits is not enough for sending SMS.");
                return;
            }
        }
    });
}

function onSendEmail() {
    var objChkList = $("#groupList").find("input#chkGroup:checked");

    var strGroupIds = "";
    for ( var i = 0; i < objChkList.size(); i++) {
        var groupId = objChkList.eq(i).parents("td").eq(0).find("#groupId")
                .val();
        strGroupIds += groupId + ",";
    }
    if (strGroupIds != "") {
        strGroupIds = strGroupIds.substr(0, strGroupIds.length - 1);
    } else {
        alert("Please Select Group to delete.");
        return;
    }

    var objCampaign = $("#campaignList").find("div.campaignItemSelected");
    if (objCampaign.length == 0) {
        alert("Please select campaign.");
        return;
    }
    var campaignId = objCampaign.find("#campaignId").val();
    var ownerId = $("#ownerId").val();

    $.ajax({
        url : "async-sendGroupMail.php",
        dataType : "json",
        type : "POST",
        data : {
            campaignId : campaignId,
            groupIds : strGroupIds,
            ownerId : ownerId
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Email will sent.");
                objCampaign.remove();
                onCloseEmail();
            } else {
                alert("Credits is not enough for sending email.");
                return;
            }
        }
    });
}