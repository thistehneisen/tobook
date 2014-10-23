/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, VARAA*/

(function ($) {
    'use strict';

    $(function () {
        var $fdModal = $('#fd-modal');
        $fdModal.modal({show: false});

        $fdModal.changeContent = function (content) {
            $fdModal.find('div.modal-content').html(content);
            return this;
        };

        $fdModal.loading = function (content) {
            var div = $('<div/>').addClass('text-center').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
            $fdModal.find('div.modal-body').html(div);
            return this;
        };

        // When user clicks to view detail of a deal
        $('a.btn-fd').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            $fdModal.modal('show').loading();
            $.ajax({
                url: $this.data('url'),
            }).done(function (content) {
                $fdModal.changeContent(content);
            });
        });

        // When user clicks to add a deal into cart
        $fdModal.on('click', 'button.btn-fd-cart', function (e) {
            e.preventDefault();
            var $this = $(this);

            $.ajax({
                url: $this.data('url'),
                type: 'POST',
                dataType: 'JSON',
                data: {
                    business_id: $this.data('business-id'),
                    deal_id: $this.data('deal-id')
                }
            }).done(function () {
                $(document).trigger('cart.reload', true);
                $fdModal.modal('hide');
            }).fail(function (e) {

                if (e.responseJSON.hasOwnProperty('message')) {
                    var div = $('<div/>')
                        .addClass('alert alert-danger')
                        .html(e.responseJSON.message);
                    $fdModal.find('div.modal-body').html(div);
                }
            });

        });

        // init countdown
        VARAA.applyCountdown($('a.countdown'));

        // change business category
        $('#business_category').change(function (e) {
            window.location = $(this).val();
        });

        // make boxes equal height
        VARAA.equalize('.available-slot .info');
        VARAA.equalize('.list-group-item');
    });
}(jQuery));
