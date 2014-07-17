$(document).ready(function(){
	$("input#imageUpload").change( function(){
		$(this).parents("form").ajaxForm({
			target: '#' + $(this).parents("form").find("#imagePrevDiv").val()
		}).submit();
	});
});

function onSaveTemplate( ){
	var name = $("#txtName").val( );
	var content = $('#txtEmail').data('liveEdit').getXHTML();
	var thumb = $("#previewImage").find("img").attr("src");
	var templateId = $("#templateId").val( );
	$.ajax({
        url: "async-saveTemplate.php",
        dataType : "json",
        type : "POST",
        data : { name : name, content : content, thumb : thumb, templateId : templateId },
        success : function(data){
            if(data.result == "success"){
            	alert("Template saved successfully.");
            	window.location.href="templateList.php";
            }
        }
    });
}