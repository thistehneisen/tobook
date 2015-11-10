<script>
$(function () {
    var servicePrices = [],
        $doc = $(document),
        $services = $('#services'),
        $service_times = $('#service_times'),
        dataStorage = { booking_service_id : 0, service_id: 0, service_time_id : 0};

    $doc.on('click', '.btn-edit-booking-service', function (e) {
        e.preventDefault();
        var booking_service_id = $(this).data('booking-service-id'),
            category_id = $(this).data('category-id'),
            service_id = $(this).data('service-id'),
            service_time_id =$(this).data('service-time-id'),
            modify_times = $(this).data('modify_times');

        dataStorage.booking_service_id = booking_service_id;
        dataStorage.category_id = category_id;
        dataStorage.service_id = service_id;
        dataStorage.service_time_id = service_time_id;
        dataStorage.modify_times = modify_times;
        $('#service_categories').val(category_id).trigger('change');
        $('#modify_times').val(modify_times);
        $('#btn-add-service').text($(this).data('edit-text'));
        $('#btn-add-service').addClass('btn-success');
    });

    $doc.on('change', '#service_categories', function () {
        var category_id = $(this).val(),
            employee_id = $('#employee_id').val();

        $services.empty();
        $service_times.empty();

        if (category_id !== '-1' && category_id !== '') {
            $.ajax({
                url: $('#get_services_url').val(),
                data: {
                    employee_id: employee_id,
                    category_id: category_id
                },
                dataType: 'json'
            }).done(function (data) {
                $services.empty();
                $service_times.empty();

                $.each(data, function (index, value) {
                    $services.append(
                        $('<option>', {
                            value: value.id,
                            text: value.name
                        })
                    );
                });
                if(dataStorage.service_id == 0) {
                    // auto-select the 2nd option (1st option is "- Select -")
                    // then trigger change to auto select service time
                    $services.val($services.find('option:eq(1)').val()).trigger('change');
                } else {
                    $services.val(dataStorage.service_id).trigger('change');
                }
            });
        } else {
            $services.empty();
            $service_times.empty();
        }
    });

    $doc.on('change', '#services', function () {
        var service_id  = $(this).val(),
            employee_id = $('#employee_id').val();

            $service_times.empty();

        if (service_id !== '-1' && service_id !== '') {
            $.ajax({
                url: $('#get_service_times_url').val(),
                data: {
                    service_id: service_id,
                    employee_id: employee_id
                },
                dataType: 'json'
            }).done(function (data) {
                $service_times.empty();

                $.each(data, function (index, value) {
                    $service_times.append(
                        $('<option>', {
                            value: value.id,
                            text: value.name + (value.description ? ' (' + value.description + ')' : ''),
                            'data-length': value.length
                        })
                    );
                    servicePrices[value.id] = value.price;
                });

                if(dataStorage.service_time_id == 0) {
                    // auto-select the 2nd option (1st option is "- Select -")
                    $service_times.val($service_times.find('option:eq(1)').val());
                } else {
                    $service_times.val(dataStorage.service_time_id);
                }
            });
        } else {
            $service_times.empty();
        }
    });

    $doc.on('change', '#service_times', function () {
        $('#added_service_name').text($('#services :selected').text());
        $('#added_booking_modify_time').text($('#modify_times').val());
        $('#added_service_price').text(servicePrices[$('#service_times').val()]);
    });

    $doc.on('change', '#modify_times', function () {
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
                console.log(data.extras);
                for (var i = 0; i < data.extras.length; i++) {
                    // Don't add duplicate options
                    if ($("#extra_services option[value=" + data.extras[i].id +"]").length === 0){
                        $('<option>',{'value': data.extras[i].id, text: data.extras[i].name}).appendTo($('#extra_services'));
                    }
                };
                $('#extra_services').selectpicker('refresh');
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
        placeholder: "{{ trans('as.bookings.search_placeholder') }}",
        minimumInputLength: 3,
        ajax: {
            url: "{{ route('as.bookings.search-consumer') }}",
            dataType: 'json',
            quietMillis: 200,
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
                $('#consumer_data').data('customerData', $data);
                return { results: response };
            },
        },
    });
    $("#keyword").on("change", function(e) {
        var index = e.val,
            data = $('#consumer_data').data('customerData');
        $('#first_name').val(data[index].first_name);
        $('#last_name').val(data[index].last_name);
        $('#phone').val(data[index].phone);
        $('#email').val(data[index].email);
        $('#address').val(data[index].address);
        $('#show-consumer-info').show();
        $('#js-show-consumer-info').attr('data-consumerid', data[index].id);
    });
});

//boostrap spinner
// TODO: better way to load this?
(function(e){e("div.spinner").each(function(){var b=e(this),c=b.find("input"),a=+b.data("inc"),d=b.attr("data-positive"),d="undefined"===typeof d?!1:"true"===d;"number"===typeof a&&a!==a&&(a=1);b.find(".btn:first-of-type").on("click",function(){c.val(+c.val()+a)});b.find(".btn:last-of-type").on("click",function(){d&&0>+c.val()-a||c.val(+c.val()-a)})})})(jQuery);
</script>
