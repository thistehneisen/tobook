$(document).ready(function() {
	$('#tblDataList').dataTable( {
		"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
		// "sPaginationType": "bootstrap",
		"aaSorting": [],
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		},
        "aoColumnDefs": [
                         { 
                             "bSortable": false, 
                             "aTargets": [ 0, 4,5,6 ]
                         } 
                     ]
	} );
} );

function onCheckAll( obj ){
	if( obj.checked ){
		$("table#tblDataList").find("input:checkbox").prop("checked", true);
	}else{
		$("table#tblDataList").find("input:checkbox").prop("checked", false);
	}
}

function onDeletePoint( ){
	var objList = $("table#tblDataList").find("input#chkPointId:checkbox:checked");
	if( objList.length == 0 ){ alert("Please select points to delete."); return; }
	var strIds = "";
	for( var i = 0 ; i < objList.length; i ++ ){
		strIds += objList.eq(i).val();
		if( i != objList.length - 1 )
			strIds += ",";
	}
	if( !confirm("Are you sure?") ){ return; }
    $.ajax({
        url: "async-deletePoint.php",
        dataType : "json",
        type : "POST",
        data : { pointIds : strIds },
        success : function(data){
            if(data.result == "success"){
            	alert("Points deleted succesfully.");
            	window.location.reload(); 
            }
        }
    });	
}

function onAddPoint( ){
	window.location.href = "pointForm.php";
}