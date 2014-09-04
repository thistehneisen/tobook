/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';

function fnCheckGroupSelected () {
    var obj_chk_list = $("input#chkGroupId:checked");
    if (obj_chk_list.size() === 0) {
        alert("Please select groups!");
        return false;
    }
    return true;
}

$(document).ready(function () {
    $("button#btn-show-campaign-list").click(function () {
        if (!fnCheckGroupSelected()) {
            return;
        }
        $("#campaignId").val("");
        $("#campaignsModal").modal('show');
    });
    
    $("button#btn-show-sms-list").click(function () {
        if (!fnCheckGroupSelected()) {
            return;
        }
        $("#smsId").val("");
        $("#smsModal").modal('show');
    });

    $("button#btn-send-campaign").click(function () {
        var obj_chk_list = $("input#chkGroupId:checked")
          , group_ids = []
          , i
          , campaign_id = $("#campaignId").val();
      
        if (campaign_id === '') {
            alert("Please select Campaign!");
            return;
        }
  
        for (i = 0; i < obj_chk_list.size(); i++) {
            group_ids[i] = obj_chk_list.eq(i).val();
        }
      
        $.ajax({
            url : "/mt/campaigns/send_group",
            dataType : "json",
            type : "POST",
            data : {
                group_ids : group_ids,
                campaign_id : campaign_id
            },
            success : function(data) {
                if (data.result == "success") {
                    alert("Campaign sent successfully.");
                    $("input#chkGroupId").prop("checked", false);
                    $("#campaignId").val("");
                    $("#campaignModal").modal('hide');
                } else {
                    alert("Request failed.");
                }
            }
        });        
    });
    
    $("button#btn-send-sms").click(function () {
        var obj_chk_list = $("input#chkGroupId:checked")
          , group_ids = []
          , i
          , sms_id = $("#smsId").val();
        
        if (sms_id === '') {
            alert("Please select SMS!");
            return;
        }
    
        for (i = 0; i < obj_chk_list.size(); i++) {
            group_ids[i] = obj_chk_list.eq(i).val();
        }
        
        $.ajax({
            url : "/mt/sms/send_group",
            dataType : "json",
            type : "POST",
            data : {
                group_ids : group_ids,
                sms_id : sms_id
            },
            success : function(data) {
                if (data.result == "success") {
                    alert("SMS sent created successfully.");
                    $("input#chkGroupId").prop("checked", false);
                    $("#smsId").val("");
                    $("#smsModal").modal('hide');
                } else {
                    alert("Request failed.");
                }
            }
        });
    });
});