/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery*/
/* author hung@varaa.com */
'use strict';
(function ($) {
    var tableTop = -1,
        originalWidth = [];
    $.fn.fixedHeader = function () {
        var self = $(this),
            defaultHeight = self.find('thead > tr > th').height(),
            defaultWidth  = self.find('thead > tr').width();

        if ($.isEmptyObject(originalWidth)) {
            self.find('thead > tr > th').each(function (key, item) {
                originalWidth.push(parseInt($(item).outerWidth(), 10));
            });
        }

        $(window).scroll(function () {
            if (self.length === 0) {
                return;
            }
            if (tableTop === -1) {
                tableTop = self.offset().top;
            }
            if ($(window).scrollTop() > tableTop) {
                self.find('thead > tr').css('position', 'fixed');
                self.find('thead > tr').css('background-color', '#FFFFFF');
                self.find('thead > tr > th').css('background-color', '#FFFFFF');
                self.find('thead > tr').css('height', defaultHeight);
                self.find('thead > tr').css('width', defaultWidth);
                self.find('thead > tr > th').each(function (key, item) {
                    $(item).css('width', parseInt(originalWidth[key], 10));
                });
                self.find('tbody > tr > td').each(function (key, item) {
                    $(item).css('width', parseInt(originalWidth[key], 10));
                });
                self.find('thead > tr').css('top', 0);
            }
            if ($(window).scrollTop() <= tableTop) {
                self.find('thead > tr').css('position', 'relative');
            }
        });
    };
}(jQuery));
