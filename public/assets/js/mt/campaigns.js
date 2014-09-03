/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';

$(document).ready(function () {
    $("button#btn-templates").click(function () {
        $("div#template-item").removeClass("selected");
        $("#templatesModal").modal('show');
    });
    
    $("div#template-item").click(function () {
        $("div#template-item").removeClass("selected");
        $(this).addClass("selected");
    });
    
    $("button#btn-select-template").click(function () {
        var obj_item, template_id;
        obj_item = $("div#template-list").find("#template-item.selected");
        if (obj_item.length === 0) {
            alert("Please select one of template.");
            return;
        } else {
            template_id = $(obj_item).find("#template-id").val();
        }
        
        $.ajax({
            url : "/mt/templates/load",
            dataType : "json",
            type : "post",
            data : {template_id : template_id},
            success : function(data) {
                if (data.result === "success") {
                    $('#content').data('liveEdit').putHTML(data.content);
                    $("#templatesModal").modal('hide');
                } else {
                    alert("Request Timeout!");
                }
            }
        });
    });
    
    $("button#btn-duplicate-campaign").click(function () {
        var campaign_id = $("#duplicate_campaign_id").val();
        var subject = $("#subject").val();
        $.ajax({
            url : "/mt/campaigns/duplication",
            dataType : "json",
            type : "post",
            data : {campaign_id : campaign_id, subject : subject},
            success : function(data) {
                if (data.result === "success") {
                    window.location.reload();
                } else {
                    alert("Request Timeout!");
                }
            }
        });         
    });
    
    $("button#btn-duplication").click(function () {
        var campaign_id = $(this).parents("td").eq(0).find("#campaign_id").val();
        $("#subject").val("");
        $("#duplicate_campaign_id").val(campaign_id);
        $("#campaignsModal").modal('show');
    });
    
});