/*jslint browser: true, nomen: true, unparam: true*/
/*global jQuery*/

(function ($) {
    'use strict';

    $(function () {
        var $fdModal = $('#fd-modal');
        $fdModal.modal({show: false});

        $fdModal.changeContent = function(content) {
            $fdModal.find('div.modal-content').html(content);
            return this;
        };

        $fdModal.loading = function(content) {
            var div = $('<div/>').addClass('text-center').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
            $fdModal.find('div.modal-body').html(div);
            return this;
        };

        $('a.btn-fd').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            $fdModal.modal('show').loading();
            $.ajax({
                url: $this.data('url'),
            }).done(function(content) {
                $fdModal.changeContent(content);
            });
        });
    });
}(jQuery));
