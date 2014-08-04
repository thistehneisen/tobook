$(document).ready( function(){
	$('#automationList').footable();	
});
function onCheckAllAutomation( obj ){
	var status = obj.checked;
	$(obj).parents("table").eq(0).find("input:checkbox").prop("checked", status);
}
function onDeleteAutomation( ){
	var objChkList = $("#automationList").find("input#chkAutomation:checked");
	var strAutomationIds = "";
	for( var i = 0; i < objChkList.size(); i ++ ){
		var automationId = objChkList.eq(i).parents("td").eq(0).find("#automationId").val();
		strAutomationIds += automationId + ",";
	}
	if( strAutomationIds != "" ){
		strAutomationIds = strAutomationIds.substr( 0, strAutomationIds.length - 1 );
	}else{
		alert("Please select Automation to delete.");
		return;
	}
	$.ajax({
        url: "async-deleteAutomation.php",
        dataType : "json",
        type : "POST",
        data : { automationIds : strAutomationIds },
        success : function(data){
            if(data.result == "success"){
            	alert("Automation deleted successfully.");
            	window.location.reload();
            }
        }
    });
}
function onDuplicate(){
	var duplicateCampaignId = $("#duplicateCampaignId").val( );
	var newCampaignName = $("#newCampaignName").val( );
	
	$.ajax({
        url: "async-duplicateCampaign.php",
        dataType : "json",
        type : "POST",
        data : { duplicateCampaignId : duplicateCampaignId, newCampaignName : newCampaignName },
        success : function(data){
            if(data.result == "success"){
            	alert("Campaign duplicated successfully.");
            	window.location.reload();
            }
        }
    });
}
function onShowStatistics( campaignId ){
	$.ajax({
        url: "async-getCampaignStatistics.php",
        dataType : "json",
        type : "POST",
        data : { campaignId : campaignId },
        success : function(data){
            if(data.result == "success"){
            	$("#cntDelivered").text( data.statistics.delivered );
            	$("#cntUnsubscribes").text( data.statistics.unsubscribes );
            	$("#cntInvalid").text( data.statistics.invalid_email );
            	$("#cntOpens").text( data.statistics.opens );
            	$("#cntClicks").text( data.statistics.clicks );
            	$("#cntBounces").text( data.statistics.bounces );
            	$("#cntRequests").text( data.statistics.requests );
            	$("#campaignStatisticsPopup").fadeIn();
            	$("#divBlackBackground").fadeIn();
            }else{
            	alert("You have to wait for a while to get the statistics informations.");
            	return;
            }
        }
    });		
}
function onCloseStatisticsPopup( ){
	$("#campaignStatisticsPopup").fadeOut();
	$("#divBlackBackground").fadeOut();
	
}
function onSendCampaignNow( campaignId ){
	$.ajax({
        url: "async-sendScheduledEmailNow.php",
        dataType : "json",
        type : "POST",
        data : { campaignId : campaignId },
        success : function(data){
            if(data.result == "success"){
            	alert("Campaign sent successfully.");
            	window.location.reload();
            }
        }
    });	
}