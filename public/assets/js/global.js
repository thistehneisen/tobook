/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, unescape*/
'use strict';

var GL_VARAA = {};

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
GL_VARAA.routes = {};
GL_VARAA.addRoute = function (routeName, url) {
    GL_VARAA.routes[routeName] = unescape(url);
};
GL_VARAA.getRoute = function (routeName, params) {
    var route = GL_VARAA.routes[routeName];
    if (route !== undefined) {
        if (params === undefined) {
            return route;
        }
        return route.routeformat(params);
    }
    console.log('Error getting route [');
};

