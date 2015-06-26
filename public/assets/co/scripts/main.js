(function() {
  (function($) {
    'use strict';
    return $(function() {
      $('.js-showHistory').on('click', function(e) {
        var $me;
        e.preventDefault();
        $me = $(this);
        return $.ajax({
          url: $me.prop('href'),
          type: 'GET',
          data: {
            id: $me.data('consumerid'),
            service: $me.data('service')
          }
        }).done(function(content) {
          var $modal;
          $modal = $('#js-historyModal');
          $modal.find('.modal-body').html(content);
          return $modal.modal('show');
        });
      });
      return $('#olut-mass-action').on('change', function(e) {
        var $me, _ref;
        e.preventDefault();
        $me = $(this);
        if ((_ref = $me.val()) === 'send_all_email' || _ref === 'send_all_sms') {
          if ($('#all_ids').length === 0) {
            return $me.after('<input id="all_ids" type="hidden" name="ids[]" value="all" />');
          }
        } else {
          return $('#all_ids').remove();
        }
      });
    });
  })(jQuery);

}).call(this);
