function onConsumerSave() {
    var ownerId = $("#ownerId").val();
    var consumerId = $("#consumerId").val();
    var firstName = $("#firstName").val();
    var lastName = $("#lastName").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var address1 = $("#address1").val();
    var city = $("#city").val();

    if (firstName + lastName == "") {
        alert("Please input consumer name.");
        return;
    }
    if (email == "") {
        alert("Please input the Email Address.");
        return;
    }
    if (!validateEmail(email)) {
        alert("Please input the Email Address correctly.");
        return;
    }

    $.ajax({
        url : "async-saveConsumer.php",
        dataType : "json",
        type : "POST",
        data : {
            ownerId : ownerId,
            consumerId : consumerId,
            firstName : firstName,
            lastName : lastName,
            email : email,
            phone : phone,
            address1 : address1,
            city : city
        },
        success : function(data) {
            if (data.result == "success") {
                alert("Consumer saved successfully.");
                window.location.href = "consumerList.php";
            }
        }
    });

}