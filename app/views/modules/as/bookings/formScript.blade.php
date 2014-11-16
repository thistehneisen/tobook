<script>
$(function () {
    var servicePrices = [];
    $(document).on('change', '#service_categories', function () {
        $('#services').empty();
        $('#service_times').empty();
        var category_id = $(this).val(),
            employee_id = $('#employee_id').val();

        $.ajax({
            url: $('#get_services_url').val(),
            data: {
                employee_id : employee_id,
                category_id : category_id
            },
            dataType: 'json'
        }).done(function (data) {
            $('#services').empty();
            $('#service_times').empty();
            var i;
            for (i = 0; i < data.length; i = i + 1) {
                $('#services').append(
                    $('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    })
                );
            }
        });
    });

    $(document).on('change', '#services', function () {
        $('#service_times').empty();
        var service_id = $(this).val();
         $.ajax({
            url: $('#get_service_times_url').val(),
            data: {
                service_id : service_id
            },
            dataType: 'json'
        }).done(function (data) {
            $('#service_times').empty();
            var i;
            for (i = 0; i < data.length; i = i + 1) {
                $('#service_times').append(
                    $('<option>', {
                        value: data[i].id,
                        text: data[i].name + (data[i].description ? ' (' + data[i].description + ')' : ''),
                        'data-length': data[i].length
                    })
                );
                servicePrices[data[i].id] = data[i].price;
            }
        });
    });

    $(document).on('change', '#service_times', function () {
        $('#added_service_name').text($('#services :selected').text());
        $('#added_booking_modify_time').text($('#modify_times').val());
        $('#added_service_price').text(servicePrices[$('#service_times').val()]);
    });

    $(document).on('change', '#modify_times', function () {
        $('#added_booking_modify_time').text($('#modify_times').val());
    });

    $('a.btn-remove-extra-service').click( function (e) {
        e.preventDefault();
        var action_url      = $(this).data('remove-url'),
            booking_id      = $(this).data('booking-id'),
            extra_service   = $(this).data('extra-service-id');
        $.ajax({
            type: 'POST',
            url: action_url,
            data: {
                booking_id    : booking_id,
                extra_service : extra_service,
            },
            dataType: 'json'
        }).done(function (data) {
            if(data.success){
                $('#extra-service-'+extra_service).remove();
            } else {
                alertify.alert('Alert', data.message, function() {
                    alertify.message("OK");
                });
            }
        }).fail(function (data) {
            alertify.alert('Alert', data.responseJSON.message, function() {
                alertify.message("OK");
            });
        });
    });
    $("#keyword").select2({
        placeholder: "Search for a consumer",
        minimumInputLength: 4,
        ajax: {
            url: "{{ route('as.bookings.search-consumer') }}",
            dataType: 'json',
            data: function (term, page) {
                return {
                   keyword : term
                };
            },
            results: function (response, page) {
                var $data = {};
                $.each(response, function (i){
                    $data[response[i].id] = response[i];
                });
                $('#consumer_data').data('customerData', $data)
                return { results: response };
            },
        },
    });
    $("#keyword").on("change", function(e) {
       var index = e.val;
       var data = $('#consumer_data').data('customerData');
       $('#first_name').val(data[index].first_name);
       $('#last_name').val(data[index].last_name);
       $('#phone').val(data[index].phone);
       $('#email').val(data[index].email);
       $('#address').val(data[index].address);
    });
});
//boostrap spinner
(function(e){e("div.spinner").each(function(){var b=e(this),c=b.find("input"),a=+b.data("inc"),d=b.attr("data-positive"),d="undefined"===typeof d?!1:"true"===d;"number"===typeof a&&a!==a&&(a=1);b.find(".btn:first-of-type").on("click",function(){c.val(+c.val()+a)});b.find(".btn:last-of-type").on("click",function(){d&&0>+c.val()-a||c.val(+c.val()-a)})})})(jQuery);
</script>
