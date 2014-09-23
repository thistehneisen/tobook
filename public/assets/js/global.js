/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, unescape*/
'use strict';

var VARAA = {};

// modify String prototype to have more helpers for JS string
if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/\{(\d+)\}/g, function (match, number) {
            return args[number] !== undefined ? args[number] : match;
        });
    };
}
if (!String.prototype.routeformat) {
    String.prototype.routeformat = function () {
        var args = arguments;
        return this.replace(/\{(\w+)\}/g, function (match, key) {
            return args[0][key] !== undefined ? args[0][key] : match;
        });
    };
}

// routes
VARAA.routes = {};
VARAA.addRoute = function (routeName, url) {
    VARAA.routes[routeName] = unescape(url);
};
VARAA.getRoute = function (routeName, params) {
    var route = VARAA.routes[routeName];
    if (route !== undefined) {
        if (params === undefined) {
            return route;
        }
        return route.routeformat(params);
    }
    console.log('Error getting route "{0}"'.format(routeName));
};

// langs
VARAA.currentLocale = 'en'; // default fallback locale
VARAA._i18n = {};
// I hate global var but this is faster to type and also consistent with Laravel
var trans = function (key) {
    if (VARAA._i18n.hasOwnProperty(key)) {
        return VARAA._i18n[key];
    }
    return key;
};
