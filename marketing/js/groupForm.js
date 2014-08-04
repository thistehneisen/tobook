$(document).ready( function(){
	$('#memberList').footable();	
});

function onCheckAllMember( obj ){
	var status = obj.checked;
	$(obj).parents("table").eq(0).find("input:checkbox").prop("checked", status);
}

function onSaveGroup( ){
	var ownerId = $("#ownerId").val( );
	var groupId = $("#groupId").val( );
	var groupName = $("#groupName").val( );
	
	$.ajax({
        url: "async-saveGroup.php",
        dataType : "json",
        type : "POST",
        data : { ownerId : ownerId, groupId : groupId, groupName : groupName },
        success : function(data){
            if(data.result == "success"){
            	alert("Group saved successfully.");
            	window.location.href="groupList.php";
            }
        }
    });
}

function onDeleteMember( ){
	var objChkList = $("#memberList").find("input#chkMember:checked");
	var strMemberIds = "";
	for( var i = 0; i < objChkList.size(); i ++ ){
		var memberId = objChkList.eq(i).parents("td").eq(0).find("#memberId").val();
		strMemberIds += memberId + ",";
	}
	if( strMemberIds != "" ){
		strMemberIds = strMemberIds.substr( 0, strMemberIds.length - 1 );
	}else{
		alert("Please Select Member to delete.");
		return;
	}
	if( !confirm("Are you sure!") ) return;
	$.ajax({
        url: "async-deleteMember.php",
        dataType : "json",
        type : "POST",
        data : { memberIds : strMemberIds },
        success : function(data){
            if(data.result == "success"){
            	alert("Member deleted successfully.");
            	window.location.reload();
            }
        }
    });
}