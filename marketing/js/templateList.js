$(document).ready(function() {
    $('#templateList').footable();
});
function onCheckAllTemplate(obj) {
    var status = obj.checked;
    $(obj).parents("table").eq(0).find("input:checkbox")
            .prop("checked", status);
}
function onDeleteTemplate() {
    var objChkList = $("#templateList").find("input#chkTemplate:checked");
    var strTemplateIds = "";
    for ( var i = 0; i < objChkList.size(); i++) {
        var templateId = objChkList.eq(i).parents("td").eq(0).find(
                "#templateId").val();
        strTemplateIds = templateId + ",";
    }
    if (strTemplateIds != "") {
        strTemplateIds = strTemplateIds.substr(0, strTemplateIds.length - 1);
    } else {
        alert("Please select template to delete.");
        return;
    }

    $.ajax({
        url : "async-deleteTemplate.php",
        dataType : "json",
        type : "POST",
        data : {
            templateIds : strTemplateIds
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Template deleted successfully.");
                window.location.reload();
            }
        }
    });
}