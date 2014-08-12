function subscribe() {
    var signature = window.localStorage.getItem("signature");
    var username = window.localStorage.getItem("userId");
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
    $.ajax({
        dataType : 'jsonp',
        url : 'https://klikkaajaa.recurly.com/jsonp/klikkaajaa/subscribe',
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
                alert("The payment is succesful");
                parent.onClosePayment();

            }
        }

    });

}