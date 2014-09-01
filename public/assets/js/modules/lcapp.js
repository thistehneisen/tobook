/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    $('#consumers').find('tr').click(function () {
        var consumerID = $(this).index() + 1;
        $.ajax({
            url: "/loyalty-card/consumers/" + consumerID,
            dataType: "html",
            type: "GET",
            success: function (data) {
                console.log(data);
            }
        });
    });

    $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');

        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #confirm').data('form', form);
    });

    // Form confirm (yes/ok) handler, submits form
    $('#js-confirmDeleteModal').on('click', '.modal-footer #confirm', function () {
        // $(this).data('form').submit();
        window.location.reload();
    });

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
                    window.alert("The customer added successfully.");
                    window.location.reload();
                }
            }
        });
    });
});
