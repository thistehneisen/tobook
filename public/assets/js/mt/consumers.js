/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';

function fnCheckConsumerSelected () {
    var obj_chk_list = $("input#js-chkConsumerId:checked");
    if (obj_chk_list.size() === 0) {
        alert("Please select consumers!");
        return false;
    }
    return true;
}

$(document).ready(function () {
    $("button#btn-show-group-name").click(function () {
        if (!fnCheckConsumerSelected())
            return;
        $("#group_name").val("");
        $("#groupsModal").modal('show');
    });
    
    $("button#btn-create-group").click(function () {
        var obj_chk_list = $("input#js-chkConsumerId:checked")
          , consumer_ids = []
          , i
          , group_name = $("#group_name").val();
        
        if (group_name === '') {
            alert("Please enter Group Name!");
            return;
        }

        for (i = 0; i < obj_chk_list.size(); i++) {
            consumer_ids[i] = obj_chk_list.eq(i).val();
        }

        $.ajax({
            url : $("#link-groups-create").val(),
            dataType : "json",
            type : "POST",
            data : {
                consumer_ids : consumer_ids,
                group_name : group_name
            },
            success : function(data) {
                if (data.result == "success") {
                    alert("Group created successfully.");
                    $("input#js-chkConsumerId").prop("checked", false);
                    $("#groupsModal").modal('hide');
                } else {
                    alert("Request failed.");
                }
            }
        });        
    });
    
    $("button#btn-show-campaign-list").click(function () {
        if (!fnCheckConsumerSelected()) {
            return;
        }
        $("#campaignId").val("");
        $("#campaignsModal").modal('show');
    });
    
    $("button#btn-show-sms-list").click(function () {
        if (!fnCheckConsumerSelected()) {
            return;
        }
        $("#smsId").val("");
        $("#smsModal").modal('show');
    });

    $("button#btn-send-campaign").click(function () {
        var obj_chk_list = $("input#js-chkConsumerId:checked")
          , consumer_ids = []
          , i
          , campaign_id = $("#campaignId").val();
      
        if (campaign_id === '') {
            alert("Please select Campaign!");
            return;
        }

        for (i = 0; i < obj_chk_list.size(); i++) {
            consumer_ids[i] = obj_chk_list.eq(i).val();
        }
        
        $.ajax({
            url : $("#link-campaigns-individual").val(),
            dataType : "json",
            type : "POST",
            data : {
                consumer_ids : consumer_ids,
                campaign_id : campaign_id
            },
            success : function(data) {
                if (data.result == "success") {
                    alert("Campaign sent successfully.");
                    $("input#js-chkConsumerId").prop("checked", false);
                    $("#campaignId").val("");
                    $("#campaignsModal").modal('hide');
                } else {
                    alert("Request failed.");
                }
            }
        });        
    });
    
    $("button#btn-send-sms").click(function () {
        var obj_chk_list = $("input#js-chkConsumerId:checked")
          , consumer_ids = []
          , i
          , sms_id = $("#smsId").val();
        
        if (sms_id === '') {
            alert("Please select SMS!");
            return;
        }
    
        for (i = 0; i < obj_chk_list.size(); i++) {
            consumer_ids[i] = obj_chk_list.eq(i).val();
        }
        
        $.ajax({
            url : $("#link-sms-individual").val(),
            dataType : "json",
            type : "POST",
            data : {
                consumer_ids : consumer_ids,
                sms_id : sms_id
            },
            success : function(data) {
                if (data.result == "success") {
                    alert("SMS sent successfully.");
                    $("input#js-chkConsumerId").prop("checked", false);
                    $("#smsId").val("");
                    $("#smsModal").modal('hide');
                } else {
                    alert("Request failed.");
                }
            }
        });
    });
});