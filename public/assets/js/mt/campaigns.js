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
            url : $("#link-templates-load").val(),
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
            url : $("#link-campaigns-duplication").val(),
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
   
    $("button#btn-statistics").click(function () {
        var campaign_id = $(this).parents("td").eq(0).find("#campaign_id").val();
        
        $.ajax({
            url : $("#link-campaigns-statistics").val(),
            dataType : "json",
            type : "post",
            data : {campaign_id : campaign_id},
            success : function(data) {
                if (data.result === "success") {
                    var statistics = data.data.last_30_days;
                    $("#tblStatistics").find("tbody").find("tr").find("td").eq(0).text(statistics.clicks);
                    $("#tblStatistics").find("tbody").find("tr").find("td").eq(1).text(statistics.opens);
                    $("#tblStatistics").find("tbody").find("tr").find("td").eq(2).text(statistics.rejects);
                    $("#tblStatistics").find("tbody").find("tr").find("td").eq(3).text(statistics.sent);
                    $("#tblStatistics").find("tbody").find("tr").find("td").eq(4).text(statistics.unique_clicks);
                    $("#tblStatistics").find("tbody").find("tr").find("td").eq(5).text(statistics.unique_opens);
                    $("#statisticsModal").modal('show');
                } else {
                    alert("Request Timeout!");
                }
            }
        });        
    });
});