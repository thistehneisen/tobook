var customerToken;
$(document).ready( function(){
	customerToken = $("#customerToken").val( );
	$.ajax({
        url: "/loyalty/api/getConsumerList.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken },
        success : function(data){
            if(data.result == "success"){
            	var strHTML = "";
            	var consumerList = data.consumerList;
            	for( var i = 0 ; i < consumerList.length; i ++ ){
            		strHTML+= '<tr>';
            		strHTML+= '<td><input type="checkbox" id="chkConsumerId" onclick="onSelectConsumer(this)" value="' + consumerList[i].consumerId + '"/></td>';
            		strHTML+= '<td>' + String( i + 1 ) + '</td>';
            		strHTML+= '<td>' + consumerList[i].firstName + " " + consumerList[i].lastName + '</td>';
            		strHTML+= '<td>' + consumerList[i].email + '</td>';
            		strHTML+= '<td>' + consumerList[i].phone + '</td>';
            		strHTML+= '<td>' + consumerList[i].currentScore + '</td>';
            		strHTML+= '<td>' + consumerList[i].createdTime + '</td>';
            		strHTML+= '</tr>';
            	}					
            	$("table#tblDataList").find("tbody").html( strHTML );
            }else{
            	alert( data.msg );
            }
        }
    });
	
	$.ajax({
        url: "/loyalty/api/getPointList.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken },
        success : function(data){
            if(data.result == "success"){
            	var strHTML = "";
            	var pointList = data.pointList;
            	$("#pointList").html("");
            	for( var i = 0 ; i < pointList.length; i ++ ){
            		var objPointItem = $("#clonePointItem").clone();
            		objPointItem.show();
            		objPointItem.attr("id", "pointItem");
            		objPointItem.find("#pointName").text( pointList[i].pointName );
            		objPointItem.find("#scoreRequired").text( "Points Required : " + pointList[i].scoreRequired );
            		objPointItem.find("button").attr( "data-id", pointList[i].pointId );
            		objPointItem.find("button").attr( "data-score", pointList[i].scoreRequired );
            		$("#pointList").append( objPointItem );
            	}
            }else{
            	alert( data.msg );
            }
        }
    });
	
	$.ajax({
        url: "/loyalty/api/getStampList.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken },
        success : function(data){
            if(data.result == "success"){
            	var stampList = data.stampList;
            	$("#stampList").html("");
            	for( var i = 0 ; i < stampList.length; i ++ ){
            		var objStampItem = $("#cloneStampItem").clone();
            		objStampItem.show();
            		objStampItem.attr("id", "stampItem");
            		objStampItem.find("#stampName").text( stampList[i].stampName );
            		objStampItem.find("#stampRequired").text( stampList[i].cntFree + " / " + stampList[i].cntRequired );
            		objStampItem.find("button").attr( "data-id", stampList[i].stampId );
            		$("#stampList").append( objStampItem );
            	}
            }else{
            	alert( data.msg );
            }
        }
    });		
});
function onCheckAll( obj ){
	if( obj.checked ){
		$("table#tblDataList").find("input:checkbox").prop("checked", true);
	}else{
		$("table#tblDataList").find("input:checkbox").prop("checked", false);
	}
}
function onSelectConsumer( obj ){
	if( obj.checked ){
		$("table#tblDataList").find("input#chkConsumerId:checkbox").prop("checked", false);
		obj.checked = true;
		
		var name = $(obj).parents("tr").eq(0).find("td").eq(2).text();
		var email = $(obj).parents("tr").eq(0).find("td").eq(3).text();
		var phone = $(obj).parents("tr").eq(0).find("td").eq(4).text();
		var score = $(obj).parents("tr").eq(0).find("td").eq(5).text();
		$("#consumerName").text(name);
		$("#consumerEmail").text(email);
		$("#consumerPhone").text(" / " + phone);
		$("#consumerScore").text( "Points : " + score );
		
	}else{
		$("#consumerName").html("&nbsp;");
		$("#consumerEmail").html("&nbsp;");
		$("#consumerPhone").html("&nbsp;");
		$("#consumerScore").html("&nbsp;");
	}
}
function onAddConsumer( ){
	$("#firstName").val("");
	$("#lastName").val("");
	$("#email").val("");
	$("#phone").val("");
	$("#address1").val("");
	$("#city").val("");	
	
	$('#dlgConsumerInfo').modal('show');
}
function onDeleteConsumer( ){
	var consumerId = getSelectedConsumerId();
	if( consumerId == -1 ) { alert("Please select consumer to delete."); return; }
	$.ajax({
        url: "/loyalty/api/deleteConsumer.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken, consumerId : consumerId },
        success : function(data){
            if(data.result == "success"){
            	alert("The consumer deleted successfully.");
            	window.location.reload();
            }else{
            	alert( data.msg );
            }
        }
    });	
}
function getSelectedConsumerId( ){
	var objList = $("table#tblDataList").find("input#chkConsumerId:checkbox:checked");
	if( objList.length == 0 )
		return -1;
	else
		return objList.eq(0).val();
}
function onSaveConsumer( ){
	var firstName = $("#firstName").val();
	var lastName = $("#lastName").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var address1 = $("#address1").val();
	var city = $("#city").val();
	$.ajax({
        url: "/loyalty/api/saveConsumer.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken, consumerId : '', firstName : firstName, lastName : lastName, email : email, phone : phone, address1 : address1, city : city },
        success : function(data){
            if(data.result == "success"){
            	alert("The consumer added successfully.");
            	window.location.reload();
            }else{
            	alert( data.msg );
            }
        }
    });		
}
function onClickGiveScore( ){
	var consumerId = getSelectedConsumerId();
	if( consumerId == -1 ) { alert("Please select consumer to delete."); return; }
	
	$("#giveScore").val("");	
	$('#dlgGiveScore').modal('show');
}
function onGiveScore( ){
	var giveScore = $("#giveScore").val( );
	var consumerId = getSelectedConsumerId();
	if( consumerId == -1 ) { alert("Please select consumer to delete."); return; }
	
	$.ajax({
        url: "/loyalty/api/giveScore.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken, consumerId : consumerId, score : giveScore},
        success : function(data){
            if(data.result == "success"){
            	alert("The score added successfully.");
            	window.location.reload();
            }else{
            	alert( data.msg );
            }
        }
    });
}
function onUsePoint( obj ){
	var consumerId = getSelectedConsumerId();
	if( consumerId == -1 ) { alert("Please select consumer to delete."); return; }
	var consumerScore = $("#consumerScore").text( );
	consumerScore = consumerScore.substring( 9 );
	var scoreRequired = $(obj).attr("data-score");
	if( Number(scoreRequired) > Number(consumerScore) ){ alert("Your Point is not enough."); return; }
	
	var pointId = $(obj).attr("data-id");

	$.ajax({
        url: "/loyalty/api/usePoint.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken, consumerId : consumerId, pointId : pointId},
        success : function(data){
            if(data.result == "success"){
            	alert("The Point used successfully.");
            	window.location.reload();
            }else{
            	alert( data.msg );
            }
        }
    });	
}
function onLogout( ){
	$.removeCookie("CUSTOMER_TOKEN");
	window.location.reload();
}
function onAddStamp( obj ){
	var consumerId = getSelectedConsumerId();
	if( consumerId == -1 ) { alert("Please select consumer to delete."); return; }
	var stampId = $(obj).attr("data-id");
	
	$.ajax({
        url: "/loyalty/api/addStamp.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken, consumerId : consumerId, stampId : stampId},
        success : function(data){
            if(data.result == "success"){
            	alert("The Stamp added successfully.");
            }else{
            	alert( data.msg );
            }
        }
    });
}

function onUseStamp( obj ){
	var consumerId = getSelectedConsumerId();
	if( consumerId == -1 ) { alert("Please select consumer to delete."); return; }
	var stampId = $(obj).attr("data-id");
	
	$.ajax({
        url: "/loyalty/api/useStamp.php",
        dataType : "json",
        type : "POST",
        data : { customerToken : customerToken, consumerId : consumerId, stampId : stampId},
        success : function(data){
            if(data.result == "success"){
            	alert("The Stamp used successfully.");
            	window.location.reload();
            }else{
            	alert( data.msg );
            }
        }
    });
}