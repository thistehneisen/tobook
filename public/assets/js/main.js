/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, Bloodhound*/
'use strict';

$(document).ready(function () {
    var initTypeahead = function (selector, name) {
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
    initTypeahead('#js-queryInput', 'services');
    initTypeahead('#js-locationInput', 'locations');

    // click handler for result
    $('.result-row').click(function () {
        var _this = $(this);
        if (!_this.hasClass('selected')) {
            // add class selected
            $('.result-row').removeClass('selected');
            _this.addClass('selected');

            // do ajax
            $.ajax({
                url: _this.data('url'),
                dataType: 'html',
                type: 'GET',
                success: function (data) {
                    $('.search-right').html(data);
                },
            });
        }
    });
});
