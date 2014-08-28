$(document).ready(function() {
    $('#tblDataList').dataTable({
        "sDom" : "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        // "sPaginationType": "bootstrap",
        "aaSorting" : [],
        "oLanguage" : {
            "sLengthMenu" : "_MENU_ records per page"
        },
        "aoColumnDefs" : [ {
            "bSortable" : false,
            "aTargets" : [ 0, 3, 4 ]
        } ]
    });
});

function onCheckAll(obj) {
    if (obj.checked) {
        $("table#tblDataList").find("input:checkbox").prop("checked", true);
    } else {
        $("table#tblDataList").find("input:checkbox").prop("checked", false);
    }
}

function onDeleteConsumer() {
    var objList = $("table#tblDataList").find(
            ".js-consumerIdCheckbox:checkbox:checked");
    if (objList.length == 0) {
        alert("Please select consumers to delete.");
        return;
    }
    var strIds = "";
    for ( var i = 0; i < objList.length; i++) {
        strIds += objList.eq(i).val();
        if (i != objList.length - 1)
            strIds += ",";
    }
    if (!confirm("Are you sure?")) {
        return;
    }
    $.ajax({
        url : "async-deleteConsumer.php",
        dataType : "json",
        type : "POST",
        data : {
            consumerIds : strIds
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Consumers deleted succesfully.");
                window.location.reload();
            }
        }
    });
}

function onAddConsumer() {
    window.location.href = "consumerForm.php";
}
