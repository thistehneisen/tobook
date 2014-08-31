/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    $('#js-createConsumerModal').on('click', '.modal-footer #cancel', function () {
        $('#first_name').val('');
        $('#last_name').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#address').val('');
        $('#postcode').val('');
        $('#city').val('');
        $('#country').val('');
    });

    $('#js-createConsumerModal').on('click', '.modal-footer #confirm', function () {
        var firstName = $('#first_name').val(), lastName = $('#last_name').val(), email = $("#email").val(), phone = $("#phone").val(), address = $("#address").val(), postCode = $('#postcode').val(), city = $("#city").val(), country = $('#country').val();
        $.ajax({
            url: "/api/v1.0/lc/consumers",
            dataType: "json",
            type: "POST",
            data: {
                first_name: firstName,
                last_name: lastName,
                email: email,
                phone: phone,
                address: address,
                postcode: postCode,
                city: city,
                country: country
            },
            success: function (data) {
                if (data.message === 'Consumer created') {
                    window.alert("The consumer added successfully.");
                    window.location.reload();
                } else {
                    window.alert(data.message);
                }
            }
        });
    });
});
