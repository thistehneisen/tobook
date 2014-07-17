$(document).ready( function(){
	$('#campaignList').footable();	
});
function onCloseDuplicatePopup(){
	$("#divBlackBackground").fadeOut();
	$("#campaignDuplicatePopup").fadeOut();
}
function onShowDuplicatePopup( campaignId ){
	$("#duplicateCampaignId").val( campaignId );
	$("#divBlackBackground").fadeIn();
	$("#campaignDuplicatePopup").fadeIn();
	$("#newCampaignName").val("");
	$("#newCampaignName").focus();
}
function onCheckAllCampaign( obj ){
	var status = obj.checked;
	$(obj).parents("table").eq(0).find("input:checkbox").prop("checked", status);
}
function onDeleteCampaign( ){
	var objChkList = $("#campaignList").find("input#chkCampaign:checked");
	var strCampaignIds = "";
	for( var i = 0; i < objChkList.size(); i ++ ){
		var campaignId = objChkList.eq(i).parents("td").eq(0).find("#campaignId").val();
		strCampaignIds = campaignId + ",";
	}
	if( strCampaignIds != "" ){
		strCampaignIds = strCampaignIds.substr( 0, strCampaignIds.length - 1 );
	}else{
		alert("Please select campaign to delete.");
		return;
	}
	
	$.ajax({
        url: "async-deleteCampaign.php",
        dataType : "json",
        type : "POST",
        data : { campaignIds : strCampaignIds },
        success : function(data){
            if(data.result == "success"){
            	alert("Campaign deleted successfully.");
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