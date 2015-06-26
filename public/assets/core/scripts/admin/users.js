(function() {
  (function($) {
    var $commissionModal;
    $('[data-toggle="popover"]').popover();
    $('.js-show-tooltip').tooltip();
    $('body').append($('<div/>').attr('id', 'js-commission-modal'));
    $commissionModal = $('#js-commission-modal');
    $('a.js-commission').on('click', function(e) {
      var $me, notifier;
      e.preventDefault();
      $me = $(this);
      notifier = alertify.notify('<i class="fa fa-2x fa-spinner fa-spin"></i>');
      return $.ajax({
        url: $me.attr('href'),
        type: 'GET'
      }).done(function(res) {
        $commissionModal.html(res);
        $commissionModal.children('.modal').modal({
          show: true
        });
        return notifier.dismiss();
      });
    });
    return $commissionModal.on('submit', '#js-commission-form', function(e) {
      var $me;
      e.preventDefault();
      $me = $(this);
      return $.ajax({
        url: $me.attr('action'),
        type: $me.attr('method'),
        dataType: 'JSON',
        data: $me.serialize()
      }).done(function(res) {
        return alertify.success(res.message);
      }).fail(function(res) {
        return alertify.error(res.responseJSON.message);
      }).always(function() {
        return $commissionModal.children('.modal').modal('hide');
      });
    });
  })(jQuery);

}).call(this);
