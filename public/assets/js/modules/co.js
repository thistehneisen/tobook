/*jslint browser: true, nomen: true, unparam: true*/
/*global $*/
'use strict';

$(document).ready(function () {
    $(this).on('click', '#js-showHistory', function () {
        var service = $(this).data('service');
        $.ajax({
            url: '/en/co/consumers/history/',
            dataType: 'json',
            type: 'get',
            data: {
                id: $(this).data('consumerid'),
                service: service,
            },
            success: function (data) {
                if (data.success) {
                    var body = "";
                    if (data.history === null) {
                        body = "<p>There is no history available</p>";
                    } else {
                        body = "<table class=\"table table-striped\"><thead><tr>";

                        if (service === 'as') {
                            body += "<th>UUID</th>";
                            body += "<th>Date</th>";
                            body += "<th>Start At</th>";
                            body += "<th>End At</th>";
                            body += "<th>Service</th>";
                            body += "<th>Note</th>";
                            body += "<th>Created</th></tr></thead><tbody>";

                            $.each(data.history, function (index, data) {
                                body += "<tr>";
                                body += "<td>" + data.uuid + "</td>";
                                body += "<td>" + data.date + "</td>";
                                body += "<td>" + data.start_at + "</td>";
                                body += "<td>" + data.end_at + "</td>";
                                body += "<td>" + data.name + "</td>";
                                body += "<td>" + data.notes + "</td>";
                                body += "<td>" + data.created_at + "</td>";
                                body += "</tr></tbody></table>";
                            });
                        } else if (service === 'lc') {
                            body += "<th>Date</th>";
                            body += "<th>Action</th>";
                            body += "<th>Shop</th></tr></thead><tbody>";

                            $.each(data.history, function (index, data) {
                                body += "<tr>";
                                body += "</tr>";
                            });
                        }
                    }

                    $('#js-historyModal .modal-body').html(body);
                    $('#js-historyModal').modal('show');
                }
            }
        });
    });
});
