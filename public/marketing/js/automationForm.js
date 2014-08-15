function onSaveAutomation() {
    var ownerId = $("#ownerId").val();
    var title = $("#title").val();
    var type = $("#type").val();
    var campaignId = $("#campaign").val();
    var smsText = $("#smsText").val();
    var pluginType = $("#pluginType").val();
    var cntPreviousBooking = $("#cntPreviousBooking").val();
    var daysPreviousBooking = $("#daysPreviousBooking").val();
    if (type == "email" && campaignId == "") {
        alert("Please select Campaign.");
        return;
    }
    if (type == "sms" && smsText == "") {
        alert("Please enter SMS Text.");
        return;
    }

    if (pluginType == "") {
        alert("Please select Plugin Type.");
        return;
    }
    if (cntPreviousBooking == "" && daysPreviousBooking == "") {
        alert("Please enter Number of Previous Booking or Days of Previous Booking.");
        return;
    }

    var automationId = $("#automationId").val();
    $.ajax({
        url : "async-saveAutomation.php",
        dataType : "json",
        type : "POST",
        data : {
            automationId : automationId,
            ownerId : ownerId,
            title : title,
            type : type,
            campaignId : campaignId,
            smsText : smsText,
            pluginType : pluginType,
            cntPreviousBooking : cntPreviousBooking,
            daysPreviousBooking : daysPreviousBooking
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Automation saved successfully.");
                window.location.href = "automationList.php";
            }
        }
    });
}
function onChangeMarketingType(obj) {
    var type = $(obj).val();
    if (type == "") {
        $("#divEmail").hide();
        $("#divSms").hide();
    } else if (type == "email") {
        $("#divEmail").show();
        $("#divSms").hide();
    } else if (type == "sms") {
        $("#divEmail").hide();
        $("#divSms").show();
    }
}