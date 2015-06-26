(function() {
  (function($) {
    'use strict';
    return $(function() {
      var $allDeals, $forceSelection, $formContact, $formSearch, $location, $locationDropdownWrapper, $q, LocationDropdown, applyToogleList, doNotShowTooltip, engine, locations, visible, __;
      $formSearch = $('#form-search');
      $q = $formSearch.find('[name=q]');
      $location = $formSearch.find('[name=location]');
      $forceSelection = $formSearch.find('.force-selection');
      $locationDropdownWrapper = $('#location-dropdown-wrapper');
      __ = function(path) {
        var val;
        val = app.lang;
        path.split('.').forEach(function(key) {
          return val = val[key] != null ? val[key] : null;
        });
        if (val != null) {
          return val;
        } else {
          return path;
        }
      };
      locations = app.prefetch.districts.map(function(name) {
        return {
          type: 'district',
          name: name
        };
      }).concat(app.prefetch.cities.map(function(name) {
        return {
          type: 'city',
          name: name
        };
      }));
      visible = locations;
      LocationDropdown = {};
      LocationDropdown.view = function(ctrl, args) {
        return [
          m('li[role=presentation]', [m('a.form-search-city[data-current-location=1][href=#]', [m('strong', __('home.search.current_location'))])]), m('li.divider[role=presentation]'), m('li[role=presentation]', {
            "class": visible.length ? 'soft-hidden' : 'disabled'
          }, [m('a[href=#]', [m('em', 'Empty')])]), visible.map(function(location) {
            return m('li[role=presentation]', m("a.form-search-city[href=#][data-current-location=0][data-type=" + location.type + "]", location.name));
          })
        ];
      };
      m.mount(document.getElementById('big-cities-dropdown'), m.component(LocationDropdown));
      engine = new Bloodhound({
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: locations,
        datumTokenizer: function(i) {
          return Bloodhound.tokenizers.whitespace(i.name);
        }
      });
      engine.initialize();
      $location.on('keyup', function(e) {
        var keyword;
        keyword = e.target.value;
        if (keyword.length === 0) {
          visible = locations;
          m.redraw();
          return;
        }
        return engine.get(keyword, function(results) {
          visible = results;
          return m.redraw();
        });
      });
      if ($q.length > 0) {
        VARAA.initTypeahead($q, 'services');
      }
      $q.bind('typeahead:selected', function(e, selection) {
        $formSearch.data('disableSubmission', false);
        if (selection.type === 'category') {
          $formSearch.data('suggestion', $q.val());
          $formSearch.data('old-action', $formSearch.attr('action'));
          return $formSearch.attr('action', selection.url);
        } else {
          if (typeof selection.url !== 'undefined') {
            return window.location = selection.url;
          }
        }
      });
      doNotShowTooltip = function(e) {
        return $(e.target).tooltip('hide');
      };
      $q.on('focus', doNotShowTooltip).on('blur', function(e) {
        var $me, bloodhound, val;
        $me = $(this);
        val = $me.val();
        bloodhound = $q.data('bloodhound');
        return bloodhound.get(val, function(results) {
          results = results.filter(function(result) {
            return result.name === val;
          });
          if (results.length === 0) {
            $forceSelection.show();
            return $formSearch.data('disableSubmission', true);
          } else {
            $forceSelection.hide();
            return $formSearch.data('disableSubmission', false);
          }
        });
      });
      $location.on('focus', function(e) {
        doNotShowTooltip(e);
        $locationDropdownWrapper.addClass('open');
        return this.value = '';
      });
      $formSearch.on('submit', function(e) {
        var bypassAndSubmit, emptyLocation, emptyQ;
        if ($formSearch.data('disableSubmission') === true) {
          e.preventDefault();
          return;
        }
        if ($formSearch.data('bypass') === true) {
          return true;
        }
        if ($formSearch.data('old-action')) {
          if ($q.val() !== $formSearch.data('suggestion')) {
            $formSearch.attr('action', $formSearch.data('old-action'));
          }
          return true;
        }
        e.preventDefault();
        bypassAndSubmit = function() {
          $formSearch.data('bypass', true);
          return $formSearch.submit();
        };
        emptyQ = $q.val().length === 0;
        emptyLocation = $location.val().length === 0;
        if (emptyQ) {
          $q.tooltip('show');
        }
        if (emptyLocation) {
          $location.tooltip('show');
        }
        if (emptyLocation || emptyQ) {
          return;
        }
        if ($location.data('current-location' === '1')) {
          return VARAA.getLocation().then(function(lat, lng) {
            $formSearch.find('[name=lat]').val(lat);
            return $formSearch.find('[name=lng]').val(lng);
          }).always(bypassAndSubmit);
        } else {
          return bypassAndSubmit();
        }
      });
      $('#big-cities-dropdown').on('click', 'a.form-search-city', function(e) {
        var $me;
        e.preventDefault();
        $me = $(this);
        $formSearch.find('[name=type]').val($me.data('type'));
        $location.attr('data-current-location', $me.data('current-location'));
        $location.val($me.text());
        return $locationDropdownWrapper.removeClass('open');
      });
      $formContact = $('#form-contact');
      if ($formContact.length > 0) {
        $formContact.on('submit', function(e) {
          var $danger, $me, $submit, $success;
          e.preventDefault();
          $me = $(this);
          $success = $me.find('.alert-success');
          $danger = $me.find('.alert-danger');
          $submit = $me.find('[type=submit]');
          return $.ajax({
            url: $me.attr('action'),
            method: 'POST',
            dataType: 'JSON',
            data: $me.serialize()
          }).then(function() {
            $danger.hide();
            $success.show();
            return $submit.attr('disabled', true);
          }).fail(function(e) {
            var data, errors, name, _fn;
            data = e.responseJSON;
            if (e.status === 422) {
              $danger.empty();
              _fn = function(errors) {
                return $danger.append($('<p/>').html(errors.join('<br>')));
              };
              for (name in data) {
                errors = data[name];
                _fn(errors);
              }
            } else {
              $danger.html(data.message);
            }
            return $danger.show();
          });
        });
      }
      $('#js-navbar').find('a').on('click', function(e) {
        var $$, $body, failed, lat, lng, success;
        e.preventDefault();
        $$ = $(this);
        $body = $('body');
        lat = $body.data('lat');
        lng = $body.data('lng');
        if ((lat != null) && (lng != null) && lat !== '' && lng !== '') {
          return window.location = $$.prop('href');
        } else {
          success = function(pos) {
            lat = pos.coords.latitude;
            lng = pos.coords.longitude;
            return $.ajax({
              url: $body.data('geo-url'),
              type: 'POST',
              data: {
                lat: lat,
                lng: lng
              }
            }).done(function() {
              return window.location = $$.prop('href');
            });
          };
          failed = function() {
            return window.location = $$.prop('href');
          };
          return navigator.geolocation.getCurrentPosition(success, failed, {
            timeout: 10000
          });
        }
      });
      $('.datetime-link').on('click', function(e) {
        return e.preventDefault();
      }).on('focus', function(e) {
        e.preventDefault();
        return $(this).siblings('.datetime-control').show();
      }).on('blur', function(e) {
        e.preventDefault();
        return $(this).siblings('.datetime-control').hide();
      });
      $('#js-choose-category').on('click', function(e) {
        e.preventDefault();
        return $.scrollTo('#js-home-categories', {
          duration: 1000
        });
      });
      applyToogleList = function($el, limit) {
        var $head, $more, $tail, $toggle;
        $head = $el.find('li:not(.toggle):lt(' + limit + ')');
        $tail = $el.find('li:not(.toggle):gt(' + (limit - 1) + ')');
        $toggle = $el.find('.toggle');
        $more = $el.find('.more');
        $head.show();
        if ($tail.length > 0) {
          $more.show();
        }
        return $toggle.on('click', function(e) {
          var $me;
          e.preventDefault();
          $me = $(this);
          $tail.slideToggle();
          $me.hide();
          return $me.siblings('.toggle').show();
        });
      };
      $('ul.list-categories').each(function(i, item) {
        return applyToogleList($(item), 3);
      });
      applyToogleList($('#js-category-filter'), 6);
      $allDeals = $('.js-deal');
      return $('a.js-filter-link').on('click', function(e) {
        var $active, $me;
        e.preventDefault();
        $me = $(this);
        $me.toggleClass('active');
        $active = $('a.js-filter-link.active');
        if ($active.length) {
          $allDeals.hide();
          return $active.each(function(i, item) {
            var $item, deals, id;
            $item = $(item);
            id = $item.data('id');
            deals = $(".js-deal-category-" + id);
            return deals.fadeIn();
          });
        } else {
          return $allDeals.show();
        }
      });
    });
  })(jQuery);

}).call(this);
