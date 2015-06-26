(function() {
  "use strict";
  var $body;

  if (!String.prototype.format) {
    String.prototype.format = function() {
      var args;
      args = arguments;
      return this.replace(/\{(\d+)\}/g, function(match, number) {
        if (args[number] !== undefined) {
          return args[number];
        } else {
          return match;
        }
      });
    };
  }

  if (!String.prototype.routeformat) {
    String.prototype.routeformat = function() {
      var args;
      args = arguments;
      return this.replace(/\{(\w+)\}/g, function(match, key) {
        if (args[0][key] !== undefined) {
          return args[0][key];
        } else {
          return match;
        }
      });
    };
  }

  VARAA.routes = VARAA.routes || {};

  VARAA.addRoute = function(routeName, url) {
    VARAA.routes[routeName] = unescape(url);
  };

  VARAA.getRoute = function(routeName, params) {
    var route;
    route = VARAA.routes[routeName];
    if (route !== undefined) {
      if (params === undefined) {
        return route;
      }
      return route.routeformat(params);
    }
    console.log("Error getting route \"{0}\"".format(routeName));
  };

  $body = $('body');

  VARAA.currentLocale = $body.data('locale' !== undefined) ? $body.data('locale') : 'en';

  VARAA._i18n = {};

  $.getJSON($body.data('js-locale'), function(data) {
    VARAA._i18n = data;
  });

  VARAA.trans = function(key) {
    if (VARAA._i18n.hasOwnProperty(key)) {
      return VARAA._i18n[key];
    }
    return key;
  };

  VARAA.regex_email_validation = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  VARAA.getLocation = function() {
    var error, q, success;
    q = $.Deferred();
    success = function(position) {
      var lat, lng;
      lat = position.coords.latitude;
      lng = position.coords.longitude;
      return $.ajax({
        url: VARAA.routes.updateLocation,
        type: 'POST',
        data: {
          lat: lat,
          lng: lng
        }
      }).done(function() {
        return q.resolve(lat, lng);
      });
    };
    error = function(err) {
      return q.reject(err);
    };
    navigator.geolocation.getCurrentPosition(success, error, {
      timeout: 10000
    });
    return q.promise();
  };

  VARAA.initTypeahead = function(selector, name) {
    var collection;
    collection = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 10,
      prefetch: {
        url: selector.data('data-source')
      }
    });
    collection.clearPrefetchCache();
    collection.initialize();
    selector.data('bloodhound', collection);
    return selector.typeahead({
      highlight: true,
      hint: true
    }, {
      name: name,
      displayKey: 'name',
      source: collection.ttAdapter(),
      templates: {
        suggestion: function(item) {
          return "<div data-url=" + item.url + ">" + item.name + "</div>";
        }
      }
    });
  };

}).call(this);
