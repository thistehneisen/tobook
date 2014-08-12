var couponCode, price, currencyNow, signature;
function couponSelect() {
    couponCode = $("#coupon_code").val();
    var price = window.localStorage.getItem("price");
    var discount;
    var accountCode = window.localStorage.getItem("accountCode");
    var plancode = window.localStorage.getItem("plancode");
    currencyNow = window.localStorage.getItem("currencyNow");

    $.ajax({
        url : "ajax-coupons.php",
        dataType : "json",
        type : "POST",
        data : {
            couponCode : couponCode,
            plancode : plancode,
            accountCode : accountCode

        },
        success : function(data) {
            if (data.result == "success") {
                $("#coupon_discount").css("display", "block");
                $("#subscription_subtotal").show();
                if (data.couponType == currencyNow) {
                    $("#coupon_discount").find(".description").text(
                            "Discount");
                    $("#coupon_discount").find(".price").text(
                            "-" + (data.coupontAmount / 100) + " "
                                    + currencyNow);
                    discount = data.coupontAmount / 100;
                } else {
                    $("#coupon_discount").find(".description").text(
                            "Discount");
                    $("#coupon_discount").find(".price").text(
                            "-" + (data.coupontAmount) + "%");
                    discount = price * (data.coupontAmount / 100);
                }
                $("#subscription_subtotal").find(".price")
                        .text(
                                "€ " + (price - discount) + " "
                                        + currencyNow);
                $("#order_total").find(".price")
                        .text(
                                "€ " + (price - discount) + " "
                                        + currencyNow);
                $("#invalidCoupon").hide();
                $("#validCoupon").val("Y");
            } else {
                $("#coupon_discount").css("display", "none");
                $("#subscription_subtotal").hide();
                $("#invalidCoupon").show();
                $("#validCoupon").val("N");
                $("#order_total").find(".price").text(
                        "€ " + price + " " + currencyNow);
            }
        }
    });
}

function subscribe() {

    var isCouponValid = $("#validCoupon").val();
    couponCode = $("#coupon_code").val();
    if (couponCode == "") {

    } else if (isCouponValid == "N") {
        alert("Please check the coupon code.");
        return;
    }

    var signature;
    var username_temp = window.localStorage.getItem("userId");
    var subdomain = window.localStorage.getItem("subdomain");
    var accountCode = window.localStorage.getItem("accountCode");
    var signature = window.localStorage.getItem("signature");

    var plancode = window.localStorage.getItem("plancode");
    username = username_temp + ":" + plancode;
    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();
    var email = $("#email").val();
    var card_number = $("#card_number").val();

    var address1 = $("#address1").val();
    var address2 = $("#address2").val();
    var city = $("#city").val();
    var state_zip = $("#state_zip").val();
    var zip = $("#zip").val();
    var vat_number = $("#vat_number").val();
    var cvv = $("#cvv").val();
    var country = $(".country").find("select").val();
    var month = $(".month").find("select").val();
    var year = $(".year").find("select").val();
    var company = $("#company").val();
    var phone_number = $("#phone_number").val();
    var url = "https://" + subdomain + ".recurly.com/jsonp/" + subdomain
            + "/subscribe";

    if (couponCode == "") {
        $.ajax({
            dataType : 'jsonp',
            url : url,
            data : {
                signature : signature,
                billing_info : {
                    first_name : first_name,
                    last_name : last_name,
                    address1 : address1,
                    state : state_zip,
                    username : username,
                    city : city,
                    zip : zip,
                    country : country,
                    number : card_number,
                    year : year,
                    month : month,
                    verification_value : cvv,
                },
                account : {
                    first_name : first_name,
                    last_name : last_name,
                    company_name : company,
                    email : email,
                    username : username

                },
            },
            success : function(data) {
                if (!(data.errors) == "") {
                    var error = "";
                    for (i = 0; i < data.errors.base.length; i++) {
                        error = error + data.errors.base[i];
                        alert(error);
                    }
                } else {
                    var token = data.success.token;
                    alert("Payment is successful");
                    parent.onAfterPayment();
                }
            }

        });

    } else {

        $.ajax({
            url : "ajax-redemCoupon.php",
            dataType : "json",
            type : "POST",
            data : {
                accountCode : accountCode,
                first_name : first_name,
                last_name : last_name,
                email : email,
                username : username,
                couponCode : couponCode,
                plancode : plancode
            },
            success : function(data) {
                signature = data.signature;
                $.ajax({
                    dataType : 'jsonp',
                    url : url,
                    data : {
                        signature : signature,
                        billing_info : {

                            first_name : first_name,
                            last_name : last_name,
                            address1 : address1,
                            state : state_zip,
                            username : username,
                            city : city,
                            zip : zip,
                            country : country,
                            number : card_number,
                            year : year,
                            month : month,
                            phone : phone_number,
                            verification_value : cvv,
                        },

                    },
                    success : function(data) {
                        if (!(data.errors) == "") {
                            var error = "";
                            for (i = 0; i < data.errors.base.length; i++) {
                                error = error + data.errors.base[i];
                                alert(error);
                            }
                        } else {

                            alert("The payment is successful");
                            parent.onClosePayment();

                        }
                    },
                });
            }
        });
    }
}
function onKeyUpCouponCode() {
    $("#validCoupon").val("N");
    $("#coupon_discount").hide();
    $("#subscription_subtotal").hide();
    $("#invalidCoupon").hide();
    price = window.localStorage.getItem("price");
    currencyNow = window.localStorage.getItem("currencyNow");
    $("#order_total").find(".price").text("€ " + price + " " + currencyNow);
}
