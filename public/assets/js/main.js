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
    if ($('#js-queryInput').length && $('#js-locationInput').length) {
        initTypeahead('#js-queryInput', 'services');
        initTypeahead('#js-locationInput', 'locations');
    }
});
