$(function() {
  'use strict';
  var $cart, $document, $languageSwitcher, $locationInput, $searchInput, initTypeahead;
  $document = $(document);
  $searchInput = $('#js-queryInput');
  $locationInput = $('#js-locationInput');
  $languageSwitcher = $('#js-languageSwitcher');
  $cart = $('#header-cart');
  initTypeahead = function(selector, name) {
    var collection;
    collection = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 10,
      prefetch: {
        url: '/search/' + name + '.json',
        filter: function(list) {
          if (typeof list[0] === 'string') {
            return $.map(list, function(item) {
              return {
                name: item
              };
            });
          }
          return list;
        }
      }
    });
    collection.clearPrefetchCache();
    collection.initialize();
    return selector.typeahead({
      highlight: true,
      hint: true
    }, {
      name: name,
      displayKey: 'name',
      source: collection.ttAdapter()
    });
  };
  if ($searchInput.length && $locationInput.length) {
    initTypeahead($searchInput, 'services');
    initTypeahead($locationInput, 'locations');
  }
  $languageSwitcher.change(function() {
    return window.location = this.value;
  });
  $cart.popover({
    placement: 'bottom',
    trigger: 'click',
    html: true
  });
  $document.on('click', function(e) {
    var $target;
    $target = $(e.target);
    if ($target.data('toggle' !== 'popover' && $target.parents('#header-cart').length === 0 && $target.parents('.popover.in').length === 0)) {
      return $cart.popover('hide');
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
    var self;
    e.preventDefault();
    self = $(this);
    self.find('i.fa').removeClass('fa-close').addClass('fa-spinner fa-spin');
    return $.ajax({
      url: self.attr('href')
    }).done(function(e) {
      $('tr.cart-detail-' + $this.data('detail-id')).fadeOut();
      return $document.trigger('cart.reload', true);
    });
  });
  $document.trigger('cart.reload', false);
  VARAA.applyCountdown = function(elems) {
    return elems.each(function() {
      var self;
      self = $(this);
      return self.countdown({
        until: new Date(self.data('date')),
        compact: true,
        layout: '{hnn}{sep}{mnn}{sep}{snn}'
      });
    });
  };
  return VARAA.equalize = function(elem) {
    var tallest;
    tallest = 0;
    return $(elem).each(function() {
      var h;
      h = $(this).outerHeight();
      if (h > tallest) {
        return tallest = h;
      }
    }).css('height', tallest);
  };
});
