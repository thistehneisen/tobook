/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, Bloodhound*/
'use strict';

$(document).ready(function () {
    var categories = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 10,
        prefetch: {
            url: '/ajax/categories.json',
            filter: function (list) {
                return $.map(list, function (category) {
                    return {
                        name: category
                    };
                });
            }
        }
    });
    categories.clearPrefetchCache();
    categories.initialize();
    $('#js-queryInput').typeahead({
        highlight: true,
        hint: true,
    }, {
        name: 'categories',
        displayKey: 'name',
        source: categories.ttAdapter()
    });
});
