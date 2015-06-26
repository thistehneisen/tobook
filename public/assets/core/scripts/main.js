(function() {
  $(function() {
    'use strict';
    var $cart, $document, $form, $languageSwitcher, $latInput, $lngInput, $locationInput, $searchInput, shouldAskGeolocation;
    $document = $(document);
    $searchInput = $('#js-queryInput');
    $locationInput = $('#js-locationInput');
    if (($searchInput != null) && ($locationInput != null) && $searchInput.length > 0 && $locationInput.length > 0) {
      VARAA.initTypeahead($searchInput, 'services');
      VARAA.initTypeahead($locationInput, 'locations');
    }
    $form = $('#main-search-form');
    if ($form.length) {
      $latInput = $form.find('[name=lat]');
      $lngInput = $form.find('[name=lng]');
      shouldAskGeolocation = !($latInput.val().length > 0 && $lngInput.val().length > 0);
      $searchInput.on('focus', function(e) {
        var $info;
        if (!(navigator.geolocation && shouldAskGeolocation)) {
          return;
        }
        $info = $('#js-geolocation-info');
        if (shouldAskGeolocation) {
          $info.show();
        }
        VARAA.getLocation().then(function(lat, lng) {
          $latInput.val(lat);
          return $lngInput.val(lng);
        });
        return shouldAskGeolocation = false;
      });
    }
    $languageSwitcher = $('#js-languageSwitcher');
    $languageSwitcher.change(function() {
      return window.location = this.value;
    });
    $cart = $('#header-cart');
    $cart.popover({
      placement: 'bottom',
      trigger: 'click',
      html: true
    });
    $document.on('click', function(e) {
      var $target;
      $target = $(e.target);
      if ($target.data('toggle') !== 'popover' && $target.parents('#header-cart').length === 0 && $target.parents('.popover.in').length === 0) {
        $cart.popover('hide');
      }
    });
    $document.on('cart.reload', function(e, showAfterFinish) {
      return $.ajax({
        url: $cart.data('cart-url'),
        dataType: 'JSON'
      }).done(function(e) {
        $cart.find('.content').html(e.totalItems);
        $cart.attr('data-content', e.content);
        if (showAfterFinish) {
          $cart.popover('show');
          return $document.scrollTop(0);
        }
      });
    });
    $document.on('click', 'a.js-btn-cart-remove', function(e) {
      var $$;
      e.preventDefault();
      $$ = $(this);
      $$.find('i.fa').removeClass('fa-close').addClass('fa-spinner fa-spin');
      return $.ajax({
        url: $$.attr('href')
      }).done(function(e) {
        $('tr.cart-detail-' + $$.data('detail-id')).fadeOut();
        return $document.trigger('cart.reload', true);
      });
    });
    return $document.trigger('cart.reload', false);
  });

}).call(this);
