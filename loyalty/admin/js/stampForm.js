/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';
function onStampSave() {
    var ownerId = $("#ownerId").val(), stampId = $("#stampId").val(), stampName = $("#stampName").val(), cntRequired = $("#cntRequired").val(), cntFree = $("#cntFree").val(), autoAddYn = $("#autoAddYn").val(), validYn = $("#validYn").val();
    if (stampName === "") { alert("Please input Stamp name."); return; }
    if (cntRequired === "") { alert("Please input Required count."); return; }
    if (cntFree === "") { alert("Please input Free count."); return; }
    $.ajax({
        url: "async-saveStamp.php",
        dataType : "json",
        type : "POST",
        data : { ownerId : ownerId, stampId : stampId, stampName : stampName, cntRequired : cntRequired, cntFree : cntFree, autoAddYn : autoAddYn, validYn : validYn },
        success : function (data) {
            if (data.result === "success") {
                alert("Stamp saved successfully.");
                window.location.href = "stampList.php";
            }
        }
    });
}