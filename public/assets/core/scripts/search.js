(function() {
  var VaraaSearch, search;

  VaraaSearch = (function() {
    function VaraaSearch(input) {
      this.businesses = input.businesses;
      this.lat = input.lat;
      this.lng = input.lng;
      if (input.categoryId != null) {
        this.categoryId = input.categoryId;
      }
      if (input.serviceId != null) {
        this.serviceId = input.serviceId;
      }
      if (input.employeeId != null) {
        this.employeeId = input.employeeId;
      }
      if (input.time != null) {
        this.time = input.time;
      }
      this.title = $('title').text();
      History.Adapter.bind(window, 'statechange', function() {
        var State;
        State = History.getState();
      });
    }

    VaraaSearch.prototype.run = function() {
      if (this.categoryId && this.serviceId) {
        this.selectBookingForm();
      }
      return this.showBusinesses();
    };

    VaraaSearch.prototype.selectBookingForm = function() {
      $("input:radio[name=category_id][value=" + this.categoryId + "], input:radio[name=service_id][value=" + this.serviceId + "]").click();
      $('input[name=service_id]').on('afterSelect', function() {
        return $("input:radio[name=employee_id][value=" + this.employeeId + "]").click();
      });
      return $('#as-step-3').on('afterShow', function() {
        return $("button[data-time=" + this.time + "]").click();
      });
    };


    /**
     * Render the map
     *
     * @param  {string|jQuery} domId
     * @param  {double} lat
     * @param  {double} lng
     * @param  {array} markers Array of pairs of lat/lng
     *
     * @return {void}
     */

    VaraaSearch.prototype.renderMap = function(domId, lat, lng, markers) {
      var gmap;
      gmap = new GMaps({
        div: domId,
        lat: lat,
        lng: lng,
        zoom: 13
      });
      if (markers != null) {
        this.addMarkers(gmap, markers);
      }
      return gmap;
    };

    VaraaSearch.prototype.addMarkers = function(gmap, markers) {
      var marker, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = markers.length; _i < _len; _i++) {
        marker = markers[_i];
        _results.push((function(marker) {
          var obj;
          obj = gmap.addMarker(marker);
          return google.maps.event.addListener(gmap.map, 'center_changed', function(e) {
            var center;
            center = gmap.map.getCenter();
            if (center.equals(new google.maps.LatLng(marker.lat, marker.lng))) {
              return obj.infoWindow.open(gmap.map, obj);
            }
          });
        })(marker));
      }
      return _results;
    };


    /**
     * Display businesses on the map
     *
     * @return {void}
     */

    VaraaSearch.prototype.showBusinesses = function() {
      var $heading, $leftSidebar, $list, $loading, $map, $single, businessOnClick, businessOnMouseEnter, gmap, markers, self;
      self = this;
      $loading = $('#js-loading');
      $list = $('#js-business-list');
      $map = $('#js-map-canvas');
      $single = $('#js-business-single');
      $heading = $('#js-business-heading');
      $leftSidebar = $('#js-left-sidebar');
      $('#js-hot-offers').sticky({
        topSpacing: 10
      });
      $map.show();
      markers = this.extractMarkers(this.businesses);
      gmap = this.renderMap($map.attr('id'), this.lat, this.lng, markers);
      $heading.on('click', (function(_this) {
        return function(e) {
          e.preventDefault();
          $single.hide();
          $list.find('.panel').each(function() {
            return $(this).show();
          });
          $map.show();
          $heading.find('i').hide();
          $('title').text(_this.title);
          return History.back();
        };
      })(this));
      $leftSidebar.on('click', '#js-show-more', function(e) {
        var $$;
        e.preventDefault();
        $$ = $(this);
        $$.children('span').hide();
        $$.children('i').show();
        return $.ajax({
          url: $$.attr('href'),
          dataType: 'JSON'
        }).done(function(data) {
          $leftSidebar.find('nav.show-more').remove();
          $leftSidebar.append(data.html);
          return self.addMarkers(gmap, self.extractMarkers(data.businesses));
        });
      });

      /**
       * Click on a business will load its details and show immediately
       *
       * @param  {EventObject} e
       *
       * @return {void}
       */
      businessOnClick = function(e) {
        var $$, businessId, hidePanel;
        e.preventDefault();
        $$ = $(this);
        businessId = $$.data('id');
        hidePanel = function() {
          return $list.find('.panel').hide();
        };
        if ($(window).width() < 768) {
          window.location = $$.data('url');
          return;
        }
        History.pushState({
          businessId: businessId
        }, '', $$.data('url'));
        if ($list.data('current-business-id') === businessId) {
          hidePanel();
          $single.show();
          $heading.find('i').show();
          return;
        }
        $('div.js-business').removeClass('selected');
        $$.addClass('selected');
        $loading.show();
        $map.hide();
        return $.ajax({
          url: $$.data('url'),
          type: 'GET'
        }).done(function(html) {
          var $bmap, lat, lng, slider, swiper;
          $loading.hide();
          hidePanel();
          $single.html(html);
          $single.show();
          $heading.find('i').show();
          VARAA.initLayout3();
          $list.data('current-business-id', businessId);
          $bmap = $("#js-map-" + businessId);
          lat = $bmap.data('lat');
          lng = $bmap.data('lng');
          self.renderMap($bmap.attr('id'), lat, lng, [
            {
              lat: lat,
              lng: lng
            }
          ]);
          $('title').text($$.data('title'));
          $.scrollTo('#js-search-results', {
            duration: 300
          });
          swiper = $("#js-swiper-" + businessId);
          slider = null;
          if (swiper.length) {
            slider = new Swiper(swiper.get(), {
              autoplay: 3000,
              loop: true,
              autoplayDisableOnInteraction: false
            });
          }
          if (slider !== null) {
            return slider.update().slideNext();
          }
        });
      };

      /**
       * Hover on a business will highlight its position on the map
       *
       * @param  {EventObject} e
       *
       * @return {void}
       */
      businessOnMouseEnter = function(e) {
        var $$, lat, lng, marker, _fn, _i, _len, _ref;
        $$ = $(this);
        lat = $$.data('lat');
        lng = $$.data('lng');
        _ref = gmap.markers;
        _fn = function(marker) {
          return marker.infoWindow.close();
        };
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          marker = _ref[_i];
          _fn(marker);
        }
        return gmap.setCenter(lat, lng);
      };
      return $leftSidebar.on('click', 'div.js-business', businessOnClick).on('meouseenter', 'div.js-business', businessOnMouseEnter);
    };


    /**
     * Extract pairs of lat and lng values to be show as markers on the map
     *
     * @param  {array} businesses Array of business objects
     *
     * @return {array}
     */

    VaraaSearch.prototype.extractMarkers = function(businesses) {
      var business, markers, _i, _len;
      markers = [];
      for (_i = 0, _len = businesses.length; _i < _len; _i++) {
        business = businesses[_i];
        markers.push({
          lat: business.lat,
          lng: business.lng,
          title: business.name,
          infoWindow: {
            content: "<p><strong>" + business.name + "</strong></p><p>" + business.full_address + "</p>"
          }
        });
      }
      return markers;
    };

    return VaraaSearch;

  })();

  search = new VaraaSearch(VARAA.Search);

  search.run();

}).call(this);
