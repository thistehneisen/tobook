function onPointSave( ){
	var ownerId = $("#ownerId").val( );
	var pointId = $("#pointId").val( );
	var pointName = $("#pointName").val( );
	var scoreRequired = $("#scoreRequired").val( );
	var discount = $("#discount").val( );
	var validYn = $("#validYn").val( );
	
	if( pointName == "" ){ alert("Please input Point name."); return; }
	if( scoreRequired == "" ){ alert("Please input Required score."); return; }
	if( discount == "" ){ alert("Please input Discount percent."); return; }
	
	$.ajax({
        url: "async-savePoint.php",
        dataType : "json",
        type : "POST",
        data : { ownerId : ownerId, pointId : pointId, pointName : pointName, scoreRequired : scoreRequired, discount : discount, validYn : validYn },
        success : function(data){
            if(data.result == "success"){
                alert("Point saved successfully.");
                window.location.href = "pointList.php";
            }
        }
    });	
		
}