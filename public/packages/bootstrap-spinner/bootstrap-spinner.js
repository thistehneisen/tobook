(function ($) {
  'use strict';
  $('div.spinner').each(function() {
    var $this = $(this),
        input = $this.find('input'),
        inc = +$this.data('inc'),
        positive = $this.attr('data-positive');

    // If only positive numbers are allowed
    positive = (typeof positive === 'undefined')
      ? false
      : positive === 'true';

    // If inc is NaN
    if (typeof inc === 'number' && inc !== inc) {
      inc = 1;
    }

    $this.find('.btn:first-of-type').on('click', function() {
      input.val(+input.val() + inc);
    });

    $this.find('.btn:last-of-type').on('click', function() {
      if (positive && +input.val() - inc < 0) {
        return;
      }
      input.val(+input.val() - inc);
    });
  });
})(jQuery);
