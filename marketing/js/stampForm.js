function onStampSave( ){
	var ownerId = $("#ownerId").val( );
	var stampId = $("#stampId").val( );
	var stampName = $("#stampName").val( );
	var cntRequired = $("#cntRequired").val( );
	var cntFree = $("#cntFree").val( );
	var validYn = $("#validYn").val( );
	
	if( stampName == "" ){ alert("Please input Stamp name."); return; }
	if( cntRequired == "" ){ alert("Please input Required count."); return; }
	if( cntFree == "" ){ alert("Please input Free count."); return; }
	
	$.ajax({
        url: "async-saveStamp.php",
        dataType : "json",
        type : "POST",
        data : { ownerId : ownerId, stampId : stampId, stampName : stampName, cntRequired : cntRequired, cntFree : cntFree, validYn : validYn },
        success : function(data){
            if(data.result == "success"){
                alert("Stamp saved successfully.");
                window.location.href = "stampList.php";
            }
        }
    });	
		
}