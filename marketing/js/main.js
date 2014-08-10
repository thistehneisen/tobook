$(document).ready(function() {
    $('#txtStartDate, #txtEndDate, #scheduleDate').datepicker({
        format : 'yyyy-mm-dd',
        showToday : true
    });
    $('#customerList').footable();
    $("#txtSMS").keyup(function() {
        $("#lengthSMSText").text($("#txtSMS").val().length);
    });
    $("#titleSMS").keyup(function() {
        $("#lengthSMSTitle").text($("#titleSMS").val().length);
    });

});
function onResetDate() {
    $("#txtStartDate").val("");
    $("#txtEndDate").val("");
}
function onAllGroup(obj) {
    var status = obj.checked;
    $(obj).parents("div").eq(0).find("input:checkbox").prop("checked", status);
}
function onCheckAllCustomer(obj) {
    var status = obj.checked;

    $(obj).parents("table").eq(0).find("input:checkbox")
            .prop("checked", status);
}
function onSearch() {
    var startDate = $("#txtStartDate").val();
    var endDate = $("#txtEndDate").val();
    if (endDate < startDate && endDate != "" && startDate != "") {
        alert("Please enter Search Date correcty.");
        return;
    }
    var tb = $("#chkTimeslot").get(0).checked ? "Y" : "N";
    var rb = $("#chkRestaurant").get(0).checked ? "Y" : "N";
    var as = $("#chkAppointment").get(0).checked ? "Y" : "N";
    var hb = $("#chkHairBeauty").get(0).checked ? "Y" : "N";
    window.location.href = "index.php?startDate=" + startDate + "&endDate="
            + endDate + "&tb=" + tb + "&rb=" + rb + "&as=" + as + "&hb=" + hb;

}
function onSendEmail() {
    var objChkList = $("#customerList").find("input#chkCustomer:checked");
    var customerList = [];
    for ( var i = 0; i < objChkList.size(); i++) {
        var customerId = objChkList.eq(i).parents("td").eq(0).find(
                "#customerId").val();
        var planGroupCode = objChkList.eq(i).parents("td").eq(0).find(
                "#planGroupCode").val();
        var destination = objChkList.eq(i).parents("tr").eq(0).find("td").eq(2)
                .text();
        var data = {
            customerId : customerId,
            planGroupCode : planGroupCode,
            destination : destination
        };
        customerList[i] = data;
    }
    if (customerList.length == 0) {
        alert("Please select customers.");
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
        url : "async-sendMail.php",
        dataType : "json",
        type : "POST",
        data : {
            campaignId : campaignId,
            customerList : customerList,
            ownerId : ownerId
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Email will sent.");
                objCampaign.remove();
                $("#cntCredits").text(data.credits);
                onCloseEmail();
            } else {
                alert("Credits is not enough for sending email.");
                return;
            }
        }
    });
}

function onEmailSchedule() {

    var objChkList = $("#customerList").find("tbody").find(
            "input#chkCustomer:checked");
    var customerList = [];
    for ( var i = 0; i < objChkList.size(); i++) {
        var customerId = objChkList.eq(i).parents("td").eq(0).find(
                "#customerId").val();
        var planGroupCode = objChkList.eq(i).parents("td").eq(0).find(
                "#planGroupCode").val();
        var destination = objChkList.eq(i).parents("tr").eq(0).find("td").eq(2)
                .text();
        var data = {
            customerId : customerId,
            planGroupCode : planGroupCode,
            destination : destination
        };
        customerList[i] = data;
    }
    if (customerList.length == 0) {
        alert("Please select customers.");
        return;
    }

    var objCampaign = $("#campaignList").find("div.campaignItemSelected");
    if (objCampaign.length == 0) {
        alert("Please select campaign.");
        return;
    }
    var campaignId = objCampaign.find("#campaignId").val();
    var ownerId = $("#ownerId").val();

    var scheduleDate = $("#scheduleDate").val();
    var scheduleHour = $("#scheduleHour").val();
    var scheduleTime = scheduleDate + " " + scheduleHour + ":00:00";

    $.ajax({
        url : "async-sendScheduleMail.php",
        dataType : "json",
        type : "POST",
        data : {
            campaignId : campaignId,
            customerList : customerList,
            ownerId : ownerId,
            scheduleTime : scheduleTime
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Campaign will sent on Scheduled Time.");
                objCampaign.remove();
                onCloseEmail();
            } else {
                alert("Credits is not enough for sending email.");
                return;
            }
        }
    });

}

function onSendSMS() {
    var objChkList = $("#customerList").find("input#chkCustomer:checked");
    var customerList = [];
    for ( var i = 0; i < objChkList.size(); i++) {
        var customerId = objChkList.eq(i).parents("td").eq(0).find(
                "#customerId").val();
        var planGroupCode = objChkList.eq(i).parents("td").eq(0).find(
                "#planGroupCode").val();
        var destination = objChkList.eq(i).parents("tr").eq(0).find("td").eq(3)
                .text();
        var data = {
            customerId : customerId,
            planGroupCode : planGroupCode,
            destination : destination
        };
        customerList[i] = data;
    }
    var ownerId = $("#ownerId").val();
    var title = $("#titleSMS").val();
    var content = $("#txtSMS").val();

    $.ajax({
        url : "async-sendSMS.php",
        dataType : "json",
        type : "POST",
        data : {
            ownerId : ownerId,
            title : title,
            content : content,
            customerList : customerList
        },
        success : function(data) {
            if (data.result == "success") {
                alert("SMS will sent.");
                $("#cntCredits").text(data.credits);
                onCloseSMS();
            } else {
                alert("Credits is not enough for sending SMS.");
                return;
            }
        }
    });

}
function onCloseEmail() {
    $("#divEmailArea").fadeOut();
    $("#divBlackBackground").fadeOut();
}
function onCloseSMS() {
    $("#divSMSArea").fadeOut();
    $("#divBlackBackground").fadeOut();
}

function onOpenEmail() {
    if ($("#alreadyPaidYn").val() == "N") {
        parent.onOpenPayment();
        return;
    }
    $("#divEmailArea").fadeIn();
    $("#divBlackBackground").fadeIn();
    $("#campaignList").find("div#campaignItem").attr("class", "");
}
function onOpenSMS() {
    if ($("#alreadyPaidYn").val() == "N") {
        parent.onOpenPayment();
        return;
    }
    $("#titleSMS").val("");
    $("#txtSMS").val("");
    $("#titleSMS").keyup();
    $("#txtSMS").keyup();
    $("#divSMSArea").fadeIn();
    $("#divBlackBackground").fadeIn();
    $("#titleSMS").focus();
}
function refreshCredits(amount) {
    var cntCredits = $("#cntCredits").text() * 1 + amount
            * $("#CREDITS_PRICE").val() * 1;
    $("#cntCredits").text(cntCredits);
}
function onClickCampaignItem(obj) {
    $("#campaignList").find("div#campaignItem").attr("class", "");
    $(obj).addClass("campaignItemSelected");
}
function onEditCustomer(planGroupCode, cId) {
    var ownerId = $("#ownerId").val();
    $.ajax({
        url : "async-getCustomerInfo.php",
        dataType : "json",
        type : "POST",
        data : {
            planGroupCode : planGroupCode,
            cId : cId,
            ownerId : ownerId
        },
        success : function(data) {
            if (data.result == "success") {
                $("#cId").val(cId);
                $("#pGroupCode").val(planGroupCode);

                $("#txtName").val(data.name);
                $("#txtEmail").val(data.email);
                $("#txtPhone").val(data.phoneNo);
                $("#txtNote").val(data.note);
                $("#txtBookedCnt").val(data.cnt);
                $("#myModal").modal();
                $("#bookingList").html("");
                for ( var i = 0; i < data.bookingList.length; i++) {
                    $("#bookingList").append(
                            $("<div>" + String(i + 1) + ". "
                                    + data.bookingList[i] + "</div>"));
                }
            }
        }
    });
}
function onSaveCustomer() {
    var ownerId = $("#ownerId").val();
    var planGroupCode = $("#pGroupCode").val();
    var cId = $("#cId").val();
    var txtName = $("#txtName").val();
    var txtEmail = $("#txtEmail").val();
    var txtPhone = $("#txtPhone").val();
    var txtNote = $("#txtNote").val();

    $.ajax({
        url : "async-saveCustomerInfo.php",
        dataType : "json",
        type : "POST",
        data : {
            planGroupCode : planGroupCode,
            cId : cId,
            ownerId : ownerId,
            txtName : txtName,
            txtEmail : txtEmail,
            txtPhone : txtPhone,
            txtNote : txtNote
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Customer info saved successfully.");
                $("#btnClose").click();
            }
        }
    });
}
function onShowGroup() {
    var objChkList = $("#customerList").find("tbody").find(
            "input#chkCustomer:checked");
    if (objChkList.length == 0) {
        alert("Please Select Customers to Join Group.");
        return;
    }

    $("#groupList").find("div#groupItem").removeClass("groupItemSelected");
    $("#myModalGroupList").modal();
}
function onClickGroupItem(obj) {
    $("#groupList").find("div#groupItem").removeClass("groupItemSelected");
    $(obj).addClass("groupItemSelected");
}
function onJoinGroup() {
    var objChkList = $("#customerList").find("input#chkCustomer:checked");
    var customerList = [];
    for ( var i = 0; i < objChkList.size(); i++) {
        var customerId = objChkList.eq(i).parents("td").eq(0).find(
                "#customerId").val();
        var planGroupCode = objChkList.eq(i).parents("td").eq(0).find(
                "#planGroupCode").val();
        var email = objChkList.eq(i).parents("tr").eq(0).find("td").eq(2)
                .text();
        var phone = objChkList.eq(i).parents("tr").eq(0).find("td").eq(3)
                .text();
        var data = {
            customerId : customerId,
            planGroupCode : planGroupCode,
            email : email,
            phone : phone
        };
        customerList[i] = data;
    }
    var objGroup = $("#groupList").find("div.groupItemSelected");
    if (objGroup.length == 0) {
        alert("Please select group.");
        return;
    }
    var groupId = $(objGroup).attr("data");

    $.ajax({
        url : "async-joinCustomerGroup.php",
        dataType : "json",
        type : "POST",
        data : {
            groupId : groupId,
            customerList : customerList
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Customer joined successfully.");
                $("#btnClose1").click();
            }
        }
    });
}