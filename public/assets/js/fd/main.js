(function ($) {
    'use strict';
    $(function () {
        var datePickerOptions = {
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            language: $('body').data('locale')
        };

        var guid = function() {
            return Math.floor((1 + Math.random()) * 0x10000)
                       .toString(16)
                       .substring(1);
        };

        $('.date-picker').datepicker(datePickerOptions);

        $('#js-fd-add-date').on('click', function (e) {
            e.preventDefault();
            var tpl = $('#js-fd-date-template'),
                clone = $(tpl.clone()),
                uuid = guid();

            clone.find('.date-picker').datepicker(datePickerOptions);
            clone.find('input:text').attr('name', 'date['+uuid+']');
            clone.find('input:radio').attr('name', 'time['+uuid+']');

            $('div.js-fd-date:last').after(clone);
        });

        $('a.js-fd-delete-date').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            if (confirm($this.data('confirm')) === false) {
                return;
            }

            $this.find('i').removeClass('fa-close').addClass('fa-refresh fa-spin');

            $.ajax({
                url: $this.attr('href'),
                type: 'GET'
            }).done(function () {
                $('#js-fd-date-'+$this.data('id')).fadeOut();
            });
        });
    });
}(jQuery));
