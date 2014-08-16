/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert*/
'use strict';
$(document).ready(function () {
    $("#username").keyup(function (event) {
        if (event.keyCode === 13) {
            $("#password").focus();
        }
    });
    $("#password").keyup(function (event) {
        if (event.keyCode === 13) {
            $("#btnLogin").click();
        }
    });
    $("#username").focus();

    $("#btnLogin").click(function () {
        var username = $("#username").val(), password = $("#password").val();

        $.ajax({
            url: "/loyalty/api/customerAuthentication.php",
            dataType: "json",
            type: "POST",
            data: {
                username: username,
                password: password
            },
            success: function (data) {
                if (data.result === "success") {
                    $.cookie("CUSTOMER_TOKEN", data.customerToken, {
                        expires: 7
                    });
                    window.location.href = "index.php";
                } else {
                    alert(data.msg);
                }
            }
        });
    });
});