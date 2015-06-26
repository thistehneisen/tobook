(function() {
  (function($) {
    'use strict';
    return $(function() {
      var datePickerOptions, guid;
      datePickerOptions = {
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
        startDate: new Date,
        language: $('body').data('locale')
      };
      $('.date-picker').datepicker(datePickerOptions);
      guid = function() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
      };
      $('#js-fd-add-date').on('click', function(e) {
        var clone, tpl, uuid;
        e.preventDefault();
        tpl = $('#js-fd-date-template');
        clone = $(tpl.clone());
        uuid = guid();
        clone.find('.date-picker').datepicker(datePickerOptions);
        clone.find('input:text').attr('name', 'date[' + uuid + ']');
        clone.find('input:radio').attr('name', 'time[' + uuid + ']');
        $('div.js-fd-date:last').after(clone);
      });
      return $('a.js-fd-delete-date').on('click', function(e) {
        var $me;
        e.preventDefault();
        $me = $(this);
        if (confirm($me.data('confirm')) === false) {
          return;
        }
        $me.find('i').removeClass('fa-close').addClass('fa-refresh fa-spin');
        return $.ajax({
          url: $me.attr('href'),
          type: 'GET'
        }).done(function() {
          return $('#js-fd-date-' + $me.data('id')).fadeOut();
        });
      });
    });
  })(jQuery);

}).call(this);
