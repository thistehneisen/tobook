/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';
var customerToken;
function getSelectedConsumerId() {
    var objList = $("table#tblDataList").find("input#chkConsumerId:checkbox:checked");
    if (objList.length === 0) { return -1; }
    return objList.eq(0).val();
}
function writeCard(ctrl, consumer_id) {
    external.SetCardWriteMode(true);
    if (!confirm("Put the card near the NFC card reader and press OK")) {
        external.SetCardWriteMode(false);
        return false;
    }
    $(ctrl).prop("disabled", true);
    if (external.WriteCard(consumer_id) === true) { $(ctrl).val("Card written OK!"); } else { alert("Error writing card!"); }
    $(ctrl).prop("disabled", false);
    return false;
}
function onSelectConsumer(objThis, event) {
    var obj = $(objThis).get(0), name = $(obj).parents("tr").eq(0).find("td").eq(2).text(), email = $(obj).parents("tr").eq(0).find("td").eq(3).text(), phone = $(obj).parents("tr").eq(0).find("td").eq(4).text(), score = $(obj).parents("tr").eq(0).find("td").eq(5).text(), consumerId = $(obj).val();
    if (obj.checked) {
        $("#divConsumerInfo").fadeIn();
        $("table#tblDataList").find("input#chkConsumerId:checkbox").prop("checked", false);
        obj.checked = true;
        $("#consumerName").text(name);
        $("#consumerEmail").text(email);
        $("#consumerPhone").text(" / " + phone);
        $("#consumerScore").text("Points : " + score);

        $.ajax({
            url: "/loyalty/api/getConsumerInfo.php",
            dataType: "json",
            type: "POST",
            data: {
                customerToken: customerToken,
                consumerId: consumerId
            },
            success: function (data) {
                if (data.result === "success") {
                    var stampList = data.usedStampList, j = 0;
                    for (j = 0; j < stampList.length; j += 1) {
                        $("button#btnAddStamp[data-id='" + data.usedStampList[j].stampId + "']").parents("#stampItem").eq(0).find("#stampRequired").text(stampList[j].cntCurrentUsed + " / " + stampList[j].cntRequired);
                    }
                } else {
                    alert(data.msg);
                }
            }
        });
    } else {
        $("#divConsumerInfo").fadeOut();
        $("#consumerName").html("&nbsp;");
        $("#consumerEmail").html("&nbsp;");
        $("#consumerPhone").html("&nbsp;");
        $("#consumerScore").html("&nbsp;");
        $("span#stampRequired").text("");
    }
    event.stopPropagation();
}
function onClickLine(obj) {
    $(obj).find("input:checkbox").eq(0).click();
}
function onUsePoint(obj) {
    var consumerId = getSelectedConsumerId(), consumerScore = $("#consumerScore").text(), pointId = $(obj).attr("data-id"), scoreRequired = $(obj).attr("data-score");
    if (consumerId === -1) {
        alert("Please select consumer to use point.");
        return;
    }
    consumerScore = consumerScore.substring(9);
    if (Number(scoreRequired) > Number(consumerScore)) {
        alert("Your Point is not enough.");
        return;
    }
    $.ajax({
        url: "/loyalty/api/usePoint.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken,
            consumerId: consumerId,
            pointId: pointId
        },
        success: function (data) {
            if (data.result === "success") {
                alert("The Point used successfully.");
                $("#consumerScore").text("Points : " + String(Number(consumerScore) - Number(scoreRequired)));
                $("table#tblDataList").find("input#chkConsumerId:checkbox:checked").parents("tr").find("td").eq(5).text(Number(consumerScore) - Number(scoreRequired));
            } else {
                alert(data.msg);
            }
        }
    });
}
function onAddStamp(obj) {
    var consumerId = getSelectedConsumerId(), stampId = $(obj).attr("data-id");
    if (consumerId === -1) {
        alert("Please select consumer to add stamp.");
        return;
    }
    $.ajax({
        url: "/loyalty/api/addStamp.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken,
            consumerId: consumerId,
            stampId: stampId
        },
        success: function (data) {
            if (data.result === "success") {
                alert("The Stamp added successfully.");
                var strCntUsed = $("button#btnAddStamp[data-id='" + stampId + "']").parents("#stampItem").eq(0).find("#stampRequired").text(), arrCntUsed = strCntUsed.split(" / "), cntUsed = Number(arrCntUsed[0]) + 1, cntRequired = Number(arrCntUsed[1]);
                if (cntUsed % cntRequired === 0) { cntUsed = 0; }
                $("button#btnAddStamp[data-id='" + stampId + "']").parents("#stampItem").eq(0).find("#stampRequired").text(cntUsed + " / " + cntRequired);
            } else {
                alert(data.msg);
            }
        }
    });
}
function onUseStamp(obj) {
    var consumerId = getSelectedConsumerId(), stampId = $(obj).attr("data-id");
    if (consumerId === -1) {
        alert("Please select consumer to use stamp.");
        return;
    }
    $.ajax({
        url: "/loyalty/api/useStamp.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken,
            consumerId: consumerId,
            stampId: stampId
        },
        success: function (data) {
            if (data.result === "success") {
                alert("The Stamp used successfully.");
                window.location.reload();
            } else {
                alert(data.msg);
            }
        }
    });
}
function getSelectedConsumerInfo(consumerId) {
    $.ajax({
        url: "/loyalty/api/getConsumerInfo.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken,
            consumerId: consumerId
        },
        success: function (data) {
            if (data.result === "success") {
                return data;
            } else {
                alert(data.msg);
            }
        }
    });	
}
$(document).ready(function () {
    customerToken = $("#customerToken").val();
    $.ajax({
        url: "/loyalty/api/getConsumerList.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken
        },
        success: function (data) {
            if (data.result === "success") {
                var strHTML = "", consumerList = data.consumerList, i = 0;
                for (i = 0; i < consumerList.length; i += 1) {
                    strHTML += '<tr style="cursor:pointer;" onclick="onClickLine(this);">';
                    strHTML += '<td><input type="checkbox" id="chkConsumerId" onclick="onSelectConsumer(this, event)" value="' + consumerList[i].consumerId + '"/></td>';
                    strHTML += '<td>' + String(i + 1) + '</td>';
                    strHTML += '<td>' + consumerList[i].firstName + " " + consumerList[i].lastName + '</td>';
                    strHTML += '<td>' + consumerList[i].email + '</td>';
                    strHTML += '<td>' + consumerList[i].phone + '</td>';
                    strHTML += '<td>' + consumerList[i].currentScore + '</td>';
                    strHTML += '</tr>';
                }
                $("table#tblDataList").find("tbody").html(strHTML);
            } else {
                alert(data.msg);
            }
        }
    });

    $.ajax({
        url: "/loyalty/api/getPointList.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken
        },
        success: function (data) {
            if (data.result === "success") {
                var pointList = data.pointList, i = 0, objPointItem;
                $("#pointList").html("");
                for (i = 0; i < pointList.length; i += 1) {
                    objPointItem = $("#clonePointItem").clone();
                    objPointItem.show();
                    objPointItem.attr("id", "pointItem");
                    objPointItem.find("#pointName").text(pointList[i].pointName);
                    objPointItem.find("#scoreRequired").text("Points Required : " + pointList[i].scoreRequired);
                    objPointItem.find("button").attr("data-id", pointList[i].pointId);
                    objPointItem.find("button").attr("data-score", pointList[i].scoreRequired);
                    $("#pointList").append(objPointItem);
                }
            } else {
                alert(data.msg);
            }
        }
    });

    $.ajax({
        url: "/loyalty/api/getStampList.php",
        dataType: "json",
        type: "POST",
        data: {
            customerToken: customerToken
        },
        success: function (data) {
            if (data.result === "success") {
                var stampList = data.stampList, i = 0, objStampItem;
                $("#stampList").html("");
                for (i = 0; i < stampList.length; i += 1) {
                    objStampItem = $("#cloneStampItem").clone();
                    objStampItem.show();
                    objStampItem.attr("id", "stampItem");
                    objStampItem.find("#stampName").text(stampList[i].stampName);
                    objStampItem.find("button").attr("data-id", stampList[i].stampId);
                    $("#stampList").append(objStampItem);
                }
            } else {
                alert(data.msg);
            }
        }
    });
    $("#btnLogout").click(function () {
        $.removeCookie("CUSTOMER_TOKEN");
        window.location.reload();
    });

    $("#btnAddConsumer").click(function () {
        $("#firstName").val("");
        $("#lastName").val("");
        $("#email").val("");
        $("#phone").val("");
        $("#address1").val("");
        $("#city").val("");

        $('#dlgConsumerInfo').modal('show');
    });

    $("#btnDeleteConsumer").click(function () {
        var consumerId = getSelectedConsumerId();
        if (consumerId === -1) {
            alert("Please select consumer to delete consumer.");
            return;
        }
        $.ajax({
            url: "/loyalty/api/deleteConsumer.php",
            dataType: "json",
            type: "POST",
            data: {
                customerToken: customerToken,
                consumerId: consumerId
            },
            success: function (data) {
                if (data.result === "success") {
                    alert("The consumer deleted successfully.");
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    $("#btnSaveConsumer").click(function () {
        var firstName = $("#firstName").val(), lastName = $("#lastName").val(), email = $("#email").val(), phone = $("#phone").val(), address1 = $("#address1").val(), city = $("#city").val();
        $.ajax({
            url: "/loyalty/api/saveConsumer.php",
            dataType: "json",
            type: "POST",
            data: {
                customerToken: customerToken,
                consumerId: '',
                firstName: firstName,
                lastName: lastName,
                email: email,
                phone: phone,
                address1: address1,
                city: city
            },
            success: function (data) {
                if (data.result === "success") {
                    alert("The consumer added successfully.");
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });
    $("#btnOpenGiveScore").click(function () {
        var consumerId = getSelectedConsumerId();
        if (consumerId === -1) {
            alert("Please select consumer to give score.");
            return;
        }

        $("#giveScore").val("");
        $('#dlgGiveScore').modal('show');
    });

    $("#btnGiveScore").click(function () {
        var giveScore = $("#giveScore").val(), consumerId = getSelectedConsumerId();
        if (consumerId === -1) {
            alert("Please select consumer to give score.");
            return;
        }

        $.ajax({
            url: "/loyalty/api/giveScore.php",
            dataType: "json",
            type: "POST",
            data: {
                customerToken: customerToken,
                consumerId: consumerId,
                score: giveScore
            },
            success: function (data) {
                if (data.result === "success") {
                    alert("The score added successfully.");
                    var consumerScore = $("#consumerScore").text(), arrConsumerScore = consumerScore.split(" : ");
                    $("#consumerScore").text("Points : " + String(Number(arrConsumerScore[1]) + Number(giveScore)));
                    $("table#tblDataList").find("input#chkConsumerId:checkbox:checked").parents("tr").find("td").eq(5).text(Number(arrConsumerScore[1]) + Number(giveScore));
                    $("#btnCloseDlgGiveScore").click();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    $("#btnWriteCard").click(function () {
        var consumerId = getSelectedConsumerId();
        if (consumerId === -1) {
            alert("Please select consumer to use stamp.");
            return;
        }
        writeCard($(this).get(0), consumerId);
    });
});