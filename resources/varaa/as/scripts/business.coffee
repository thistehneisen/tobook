# global m, app
app.VaraaBusiness = (dom, id, type) ->
  'use strict'
  # Translation helper
  __ = (key) -> if app.i18n[key]? then app.i18n[key] else ''

  # ----------------------------------------------------------------------------
  # The main layout
  # ----------------------------------------------------------------------------

  BusinessList = {}
  BusinessList.controller = ->
    @dataStore = m.prop { id: id, type: type, city: '', show_discount: false, page: 1, count: 0, min_price: 0, max_price: 500}
    
    @data        = m.prop {}
    @businesses  = m.prop []
    @environment = m.prop ''
    @assetPath = m.prop ''
    @categories = m.prop []
    @services = m.prop []
    @suggestion = m.prop []
    @discountBusiness = m.prop []
    @engine = m.prop []
    @locations = m.prop []
    @visible = m.prop []
    @count = m.prop 0
    @page = m.prop 1
    @cache = []
    @append = true

    @environment(app.initData.environment)
    @assetPath(app.initData.assetPath)
    @categories(app.initData.categories)
    @services(app.initData.services)
    @discountBusiness(app.initData.business)

    @search = () ->
      $('#show-more-spin').show()

      m.request
        method: 'GET'
        url: app.routes['business.search']
        data:
          id: @dataStore().id
          type: @dataStore().type
          search_type: @dataStore().search_type
          keyword: @dataStore().keyword
          city: @dataStore().city
          show_discount: @dataStore().show_discount
          min_price: @dataStore().min_price
          max_price: @dataStore().max_price
          page: @dataStore().page,
      .then (data) =>
        if (@append)
          for business in data.businesses
            @businesses().push business
        else
          @businesses(data.businesses) 

        @dataStore().page = data.current
        @dataStore().count = data.count

        @page(data.current)
        @count(data.count)
      .then () =>
        m.redraw.strategy("diff")
        m.redraw()
        $('#show-more-spin').hide()
        # window.location.hash = 'show-more'
        @init()

    @showMore = (e) ->
      e.preventDefault()
      if (@dataStore().page < @dataStore().count)
        @dataStore().page += 1
        @search()
        if(@dataStore().page == @dataStore().count)
          $('.show-more').hide()

    @setCity = (e) ->
      el = e.target
      init = el.value
      @dataStore().city = el.value
      @dataStore().search_type = 'city'
      @dataStore().page = 1
      @append = false

      if (e.which == 13 || e.keyCode == 13)
        @search()
    
    @selectCity = (e) ->
      e.preventDefault()
      $('#location').val e.target.text

    @setKeyword = (e) =>
      el = e.target
      init = el.value
      similarScore = []
      if (e.which == 13 || e.keyCode == 13)
        for service in @services()
          if el.value == service.name
            if service.type is 'category' or service.type is 'treatment'
              @typeHeadSelect(service)
            else
              window.location.href = selection.url if typeof selection.url isnt 'undefined'
            return
          else
            score = (new Levenshtein(el.value, service.name.substring(0, el.value.length))).distance
            similarScore.push { score : score, name : service.name, type : service.type, id : service.id, url : service.url }


        similarScore.sort (a, b) ->
            if (a.score < b.score)
              return -1
            else if (a.score > b.score)
              return 1
            else 
              return 0

        suggestion = []
        count = 0
        for item in similarScore
          count++
          suggestion.push(item)
          if (count == 5) then break

        @suggestion(suggestion)

        m.redraw.strategy("diff")
        m.redraw()

        alertify.alert(__('keyword_not_exists'), $('#suggestion').html())
        
            
    @setShowDiscount = (e) ->
      el = e.target
      @dataStore().show_discount = el.checked
      @dataStore().page = 1
      @append = false
      @search()

    @addMarkers = (gmap, markers) ->
      for marker in markers
        do (marker) ->
          obj = gmap.addMarker marker
          google.maps.event.addListener gmap.map, 'center_changed', (e) ->
            center = gmap.map.getCenter()
            if center.equals new google.maps.LatLng marker.lat, marker.lng
              obj.infoWindow.open gmap.map, obj

    @renderMap =  (domId, lat, lng, markers) ->
      gmap = new GMaps
        div: domId
        lat: lat
        lng: lng
        zoom: 11

      if markers?
        @addMarkers gmap, markers
      return gmap

    @makeMarker =  (business) ->
      return {
        lat: business.lat
        lng: business.lng
        title: business.name
        infoWindow:
          content: "<a href='#{business.businessUrl}'><table class=\"map-marker-table\"><tr><td class=\"img-wrapper\"><img src='" + business.image_url + "' style='width: 100px' /></td><td><p><strong>#{business.name}</strong></p><p>#{business.full_address}</p><span class=\"label label-success\"><i class=\"fa fa-ticket\"></i>" + __('online_booking') + "</span></td></tr></table></a>"
      }

    @makeMarkers =  (businesses) ->
      markers = []
      for business in businesses
        marker = @makeMarker(business)    
        markers.push marker
      return markers

    @makeMapContainer = (width = '500px', height = '500px') ->
      if $('#gmap').length 
        $('#gmap').remove()
      gmap = $('<div>', { class: 'dialog', id: 'gmap', style: "height: #{height}; width: #{width}" })
      gmap.appendTo($('body'));

    @showMap = (business, e) =>
      e.preventDefault()
      markers = []
      marker = @makeMarker(business)
      markers.push marker
      @makeMapContainer()
      $(".dialog").dialog({
            height: 500,
            width: 500,
            resizable: false,
            title: ""
      });
      $('.dialog').dialog('open');
      @renderMap('gmap', business.lat, business.lng, markers)
      $('#gmap').trigger('click');

    @viewOnMap = (businesses, e) ->
      e.preventDefault()
      if (businesses.length == 0)
        return
      markers = @makeMarkers(businesses)
      @makeMapContainer('topmap', '800px', '500px');
      $(".dialog").dialog({
            height: 500,
            width: 800,
            resizable: false,
            title: ""
      });
      $('.dialog').dialog('open');

      allLat = 0
      allLng = 0

      for business in businesses
        allLat += parseFloat(business.lat, 10)
        allLng += parseFloat(business.lng, 10)

      avgLng = allLng / businesses.length
      avgLat = allLat / businesses.length
      @renderMap('gmap', avgLat, avgLng, markers)
      $('#gmap').trigger('click');

    @getShowMoreStyle = () ->
      if (@count() == 1 || @page() == @count())
        return 'display:none'

    @goTo = (business) ->
      if ($(document).width() >= 400)
        return
      window.location.href = business.businessUrl

    @showFilters = () ->
      $('#filters').toggleClass('hidden-xs');
      # $('#filters').slideToggle('slow');
      return

    @getServiceClass = (index, length, businessId) ->
      serivce_class = if (index > 1) then ('hidden'  + ' hidden-service-' +  businessId)  else 'show'
      serivce_class = if (index == 1) then serivce_class + ' service-second-' + businessId  else serivce_class
      serivce_class = if (index == 1 || index == length - 1) then serivce_class + ' popular-service-last' else serivce_class
      return { class : serivce_class }

    @showMoreServices = (businessId) ->
      $('.service-second-' + businessId).toggleClass('popular-service-last')
      $('.hidden-service-' + businessId).toggleClass('hidden')
      return

    @changeUrl = (selection) =>
      replaceUrl = selection.url.replace(app.routes['baseUrl'], '')
      window.history.pushState({}, selection.name, replaceUrl)

    @typeHeadSelect = (selection) =>
      @dataStore().type = selection.type
      @dataStore().id = selection.id
      @dataStore().keyword = selection.name
      @append = false
      @businesses([])
      @count(0)
      # Change initData to make sure UI is rendered correctly
      app.initData.mcId = selection.mcId
      app.initData.id = selection.id
      app.initData.type = selection.type
      # Rerender UI to view loading indicator
      m.redraw.strategy("diff")
      m.redraw()
      # Perform real search
      @search()
      # Change upper url
      @changeUrl(selection)

    @selectSuggestion = (suggestion, e) =>
      e.preventDefault()
      @dataStore().type = suggestion.type
      @dataStore().id = suggestion.id
      @dataStore().keyword = suggestion.name
      @append = false
      @businesses([])
      @count(0)
      # Change initData to make sure UI is rendered correctly
      app.initData.id = suggestion.id
      app.initData.mcId = suggestion.mcId
      app.initData.type = suggestion.type
      # Rerender UI to view loading indicator
      m.redraw.strategy("diff")
      m.redraw()
      # Perform real search
      @search()
      # Change upper url
      @changeUrl(suggestion)

    @init = () =>      
      priceSlider = document.getElementById('slider-range');
      if (!$('#slider-range').hasClass('noUi-target'))
        noUiSlider.create(priceSlider, {
          start: [ @dataStore().min_price, @dataStore().max_price ],
          snap: true,
          behaviour: 'drag',
          connect: true,
          range: {
            'min':  0,
            '10%': 5,
            '15%': 10,
            '20%': 15,
            '25%': 25,
            '30%': 50,
            '40%': 75,
            '45%': 100,
            '50%': 125,
            '55%': 150,
            '60%': 175,
            '80%': 200,
            '90%': 250,
            'max': 300
          }
        });

        priceSlider.noUiSlider.on 'update', ( values, handle ) -> 
          $("#amount").val(parseInt(values[0], 10) + "€" + " - " + parseInt(values[1], 10) + "€");
        
        priceSlider.noUiSlider.on 'end', ( values, handle ) =>
            @dataStore().min_price = parseInt(values[0], 10)
            @dataStore().max_price = parseInt(values[1], 10)
            @append = false
            @search() 

      $('.categories-list ul').hide()

      $('.category-item').click((e) -> 
        $(this).next('ul').slideToggle("slow");
      )

      $("ul").find("[data-id='" + app.initData.mcId + "']").slideToggle("slow");

      $('.category-item').dblclick((e) ->
        window.location.href = $(this).data('url');
      )
      
      VARAA.initTypeahead $('#query'), 'services' if $('#query').length > 0

      locations = app.initData.districts.map (name) -> type: 'district', name: name
        .concat app.initData.cities.map (name) -> type: 'city', name: name

      @locations(locations);

      $('#query').unbind('typeahead:selected')

      $('#query').bind 'typeahead:selected', (e, selection) =>
        if selection.type is 'category' or selection.type is 'treatment'
          @typeHeadSelect(selection)
        else
          window.location.href = selection.url if typeof selection.url isnt 'undefined'

      $('.raty').raty
        score: () ->
          return $(this).data('score')
        starOff: '/packages/jquery.raty/images/' + '/star-off.png'
        starOn: '/packages/jquery.raty/images/' + '/star-on.png'
        starHalf: '/packages/jquery.raty/images/' + '/star-half.png'
        readOnly: true

      checkbox = document.querySelector('.js-switch')
      if (checkbox.getAttribute('data-switchery') == null)
        init = new Switchery(checkbox);
        checkbox.onchange = () =>
          state = checkbox.checked;
          console.log(state)
          @dataStore().show_discount = state
          @dataStore().page = 1
          @append = false
          @search()

      businesses = @businesses()
      markers    = @makeMarkers(businesses)
      @renderMap('topmap', businesses[0].lat, businesses[0].lng, markers)

    # Kickstart
    @search()

    return

  BusinessList.view = (ctrl) ->
    m('.container.business-container', [
      if (ctrl.environment() == 'tobook' and ctrl.count() >= 1)
        m('.row.hidden-xs', [
          m('.col-sm-12#topmap-container', [
            m('#topmap', { style: "height: 300px; width: 100%" } )
          ])
        ])
      m('.row',[
        m('.col-sm-3.search-panel', [
          if(ctrl.environment() != 'tobook')
            m('.row', [
              m('i.fa.fa-map-marker.fa-2x.orange'),
              m.trust('&nbsp;'),
              m('a[href=#]', { onclick: ctrl.viewOnMap.bind(ctrl, ctrl.businesses()) }, [
                  __('view_on_map'),
              ])
            ])
          m('.row.visible-xs', [
            m('a.btn.btn-orange.pull-right[href=#]', { onclick: ctrl.showFilters.bind(ctrl) }, __('filters'))
          ]),
          m('#filters.hidden-xs',[
            m('.row', [
              m('hr', { class : ctrl.environment() }),
              m('input.js-switch[type=checkbox]', { value: true }),
              m('label[for=show_discount]',[
                m.trust('&nbsp;'),
                __('only_offpeak_discounts'),
              ])
            ]),
            m('.row', [
              m('hr'),
              m('h4', [m.trust(__('filter_search_results')) ]),
              m('form', { class : 'form-horizontal'}, [
                m('.form-group', [
                  m('.col-sm-12', [
                    m('input#query[type=text]', { 
                      class : 'form-control input-keyword', 
                      placeholder: __('query_placeholder'),
                      onkeydown: ctrl.setKeyword.bind(ctrl),
                      'data-data-source' : app.routes['business.services'],
                      'autocomplete' : 'off',
                      'data-trigger' : 'manual',
                      'data-placement': 'bottom',
                    }),
                    m('div.hidden#suggestion', [
                      m('p', [__('please_try')]),
                      m('ul', [
                        ctrl.suggestion().map((suggestion, index) ->
                          m('li', [
                            m('a', {
                                href : suggestion.url
                            }, [suggestion.name])
                          ])
                        )
                      ])
                    ])
                  ])
                ]),
                m('.form-group', [
                  m('.col-sm-12', [
                    m('div.dropdown#location-dropdown-wrapper', [
                      m('input#location[type=text]', { 
                        class : 'form-control input-keyword dropdown-toggle', 
                        placeholder: __('location_placeholder'),
                        onkeydown: ctrl.setCity.bind(ctrl),
                        'autocomplete' : 'off',
                        'data-toggle' : 'dropdown',
                        'data-trigger' : 'manual',
                        'data-placement': 'bottom',
                        'data-target': '#'
                      }),
                      m('ul#big-cities-dropdown.dropdown-menu.big-cities-dropdown', { role : 'menu'}, [
                        m('li[role=presentation]', {class: if ctrl.locations().length then 'soft-hidden' else 'disabled'}, [m('a[href=#]', [m('em', 'Empty')])])
                        ctrl.locations().map (location) ->
                          return m 'li[role=presentation]',
                            m("a.form-search-city[href=#][data-current-location=0][data-type=#{location.type}]", {
                              onclick : ctrl.selectCity.bind(ctrl)
                            }, location.name)
                      ])
                    ])
                  ])
                ])
              ])
            ])
          ]),
          m('.row.hidden-xs', [
            m('hr'),
            m('h4', [__('price_range')]),
            m('p', [
              m('label[for=amount]', [__('price_range')]),
              m.trust('&nbsp;'),
              m('input#amount[type=text]', { readonly: true, style: 'border:0; color:#f6931f; font-weight:bold;'} ),
              m('#slider-range')
            ]),
          ]),
          m('.row', [
            m('hr')
            if (ctrl.categories().length > 0)
              m('ul.categories-list', [
                ctrl.categories().map((category, index) ->
                  m('li', [
                    m('strong.category-item', { 'data-url': category.url }, [category.name])
                    if (category.treatments.length > 0)
                      m('ul', { 'data-id' : category.id } , [
                        category.treatments.map((treatment, index) ->
                          m('li', [
                            m('a', { href: treatment.url }, [treatment.name ])
                          ])
                        )
                      ])
                  ])
                )
              ])
          ]),
          m('.row', [
            if (Object.keys(ctrl.discountBusiness()).length)
              m('.discount-widget-containter', [
                  if (ctrl.discountBusiness().discountPercent > 0)
                    m('.ribbon-wrapper', [
                      m('.ribbon-red', [
                        m.trust('-'),
                        ctrl.discountBusiness().discountPercent,
                        m.trust('%')
                      ])
                    ])
                  m('a', { href: ctrl.discountBusiness().businessUrl }, [
                    m('img',{ 
                        style: 'height: 180px; width: 100%; display: block;',
                        alt: ctrl.discountBusiness().name,
                        src: ctrl.discountBusiness().image
                    },[])
                  ]),
                  m('.discount-info', [
                    ctrl.discountBusiness().serviceName ,
                    m.trust('&nbsp;'), 
                    m('span.price', [ 
                      ctrl.discountBusiness().discountPrice, 
                      m.trust('&euro;') 
                    ])
                  ]),
                  m('.discount-action', [
                    m('a.btn.btn-square.btn-success', { href : ctrl.discountBusiness().businessUrlWithService }, [
                      __('select')
                    ])
                  ])
              ])
          ])
        ]),
        m('.col-sm-9.businesses', [
          if (ctrl.businesses().length > 0)
            ctrl.businesses().map((business, index) ->
              m('.item',[
                m('.information', [
                  m('.row', [
                    m('.col-sm-4.hidden-xs',[
                      m('img', { src: business.image_url , style: 'width: 100%'})
                    ]),
                    m('.col-xs-12.col-sm-8.mobile-item', { onclick: ctrl.goTo.bind(ctrl, business) }, [
                      m('h4.venue-title', [ 
                        m('a', { href: business.businessUrl }, [
                          business.name,
                          m.trust('&nbsp;'), 
                          if (business.hasDiscount)
                            m('i.fa.fa-tags.orange')
                        ])
                      ]),
                      m('span.venue-description', [
                        m.trust(business.address),
                        if (business.district != '')
                          m.trust(', ' + business.district)
                        if (business.postcode != '')
                          m.trust(', ' + business.postcode)
                        m.trust(',&nbsp;'),
                        m.trust(business.city),
                        m.trust('&nbsp;'),
                        m('a.hidden-xs[href=#]', { onclick: ctrl.showMap.bind(ctrl, business) }, [
                          __('show_map'),
                          m.trust('&nbsp;&raquo;')
                        ])
                      ]),
                      if(parseInt(business.review_count, 10) > 0)
                        m('table.venue-review', [
                          m('tr',[
                            m('td', [
                              m('.raty', { 'data-score' : business.avg_total }),
                            ]),
                            m('td', [
                              m('span', [ 
                                business.review_count, 
                                m.trust('&nbsp;'),
                                if(parseInt(business.review_count, 10) > 1)
                                  __('reviews')
                                else
                                  __('review')
                              ]) 
                            ])
                          ])
                        ])
                      else
                        m('div', [ __('no_review') ])
                      m('.row.contact.hidden-xs', [
                        m('.col-xs-6', [
                          # m('div.contact-item', [
                          #    m('strong', [ __('phone')]),
                          #    m('span', [business.phone])
                          # ]),
                          m('div', [
                            m('strong', [ __('email')]),
                            m('span', [business.user_email]),
                          ])
                        ]),
                        m('.col-xs-6', [
                          m('strong', [ __('payment_methods') ]),
                          m('ul.payment_methods', [
                            business.payment_options.map((option, index) ->
                                m('li', [ __('payment.' + option) ])
                            )
                          ]),
                          m('a.pull-right', { href: business.businessUrl }, [
                             m.trust(__('learn_more'))
                          ])
                        ])
                      ])
                    ])
                  ])
                ])
                m('.popular-services.hidden-xs', [
                  business.services.map((service, index) ->
                    m('.row.popular-service', ctrl.getServiceClass(index, business.services.length, business.id) , [
                      m('.col-xs-8', [
                        m('span.orange.service-name', [service.name]) 
                      ]),
                      m('.col-xs-4', [
                        m('.select-block.pull-right', [
                          m('span.range', [ m.trust(business['price_range'][service.id]) ]),
                          m('a.btn.btn-orange.btn-square', { 
                              href: business.businessUrl + '/?serviceId=' + service.id 
                            }, [ __('select') ])
                        ])
                      ])
                    ])
                  )
                  if (business.services.length > 2)
                    m('.row.row-no-padding', [
                      m('.col-xs-12.show-more-services', [
                        m('a.btn.btn-default.pull-right', { onclick: ctrl.showMoreServices.bind(ctrl, business.id) } ,[
                            __('show_more')
                        ])
                      ])
                    ])
                ])
              ])
            )
          else if (ctrl.count() == 1)
            m('.business-item',[
              m('h3.venue-title', [
                m.trust(__('no_record_found'))
              ])
            ])
          else
            m('.loading', m('i.fa.fa-spin.fa-2x.fa-spinner'))
        ])
      ]),
      m('.row', { style : ctrl.getShowMoreStyle(ctrl) } , [
        m('.col-sm-3.search-panel',[]),
        m('.col-sm-9.show-more-panel',[
          m('nav.text-center.show-more', [
            m('a#show-more.btn.btn-default.btn-block[href=#]', {onclick: ctrl.showMore.bind(ctrl)}, [
              m('span', [__('show_more')]),
              m('i.fa.fa-2x.fa-spinner.fa-spin#show-more-spin', { style : 'display: none'})
            ])
          ])
        ]),
      ])
    ])

  # Render booking forms
  m.mount(dom, m.component(BusinessList))
