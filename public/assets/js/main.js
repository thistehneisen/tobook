/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, Bloodhound*/
'use strict';

$(function () {
    var $doc = $(document),
        initTypeahead,
        cart;

    initTypeahead = function (selector, name) {
        // init bloodhound collection
        var collection = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            limit: 10,
            prefetch: {
                url: '/search/' + name + '.json',
                filter: function (list) {
                    if (typeof (list[0]) === 'string') {
                        return $.map(list, function (item) {
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

        // init typeahead
        $(selector).typeahead({
            highlight: true,
            hint: true,
        }, {
            name: name,
            displayKey: 'name',
            source: collection.ttAdapter()
        });
    };

    // add 2 typeahead
    if ($('#js-queryInput').length && $('#js-locationInput').length) {
        initTypeahead('#js-queryInput', 'services');
        initTypeahead('#js-locationInput', 'locations');
    }

    cart = $('#header-cart');
    cart.popover({
        placement: 'bottom',
        trigger: 'click',
        html: true
    });

    $doc.on('click', function (e) {
        var $target = $(e.target);

        if ($target.data('toggle') !== 'popover'
                && $target.parents('#header-cart').length === 0
                && $target.parents('.popover.in').length === 0) {
            cart.popover('hide');
        }
    });

    $doc.on('cart.reload', function (e, showAfterFinish) {
        $.ajax({
            url: cart.data('cart-url'),
            dataType: 'JSON'
        }).done(function (e) {
            cart.find('.content').html(e.totalItems);
            cart.attr('data-content', e.content);

            if (showAfterFinish) {
                cart.popover('show');
                $(document).scrollTop(0);
            }
        });
    });


    // When remove an item from cart
    $doc.on('click', 'a.js-btn-cart-remove', function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.find('i.fa').removeClass('fa-close').addClass('fa-spinner fa-spin');
        $.ajax({
            url: $this.attr('href')
        }).done(function (e) {
            $('tr.cart-detail-' + $this.data('detail-id')).fadeOut();
            $doc.trigger('cart.reload', true);
        });
    });

    // Load cart content when page load
    $doc.trigger('cart.reload', false);
});
