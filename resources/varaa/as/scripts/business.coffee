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
    @dataStore = m.prop { id: id, type: type, keyword: '', search_type: '', city: '', show_discount: false, page: 1, count: 0, min_price: 0, max_price: 500}
    
    @data        = m.prop {}
    @businesses  = m.prop []
    @environment = m.prop ''
    @assetPath = m.prop ''
    @categories = m.prop []
    @discountBusiness = m.prop []
    @count = m.prop 0
    @page = m.prop 1
    @cache = []
    @append = true
    @lat = m.prop 0
    @lng = m.prop 0

    @environment(app.initData.environment)
    @assetPath(app.initData.assetPath)
    @categories(app.initData.categories)
    @discountBusiness(app.initData.discountBusiness)

    @setGeolocation = (position) =>
      lat = position.coords.latitude
      lng = position.coords.longitude
      @lat(lat)
      @lng(lng)
      @search()      
      return 

    @showError = (error) ->
      console.log(error) 

    @bigbang = () =>
      $body = $ 'body'
      lat = $body.data 'lat'
      lng = $body.data 'lng'

      if (lat? and lng? and lat != '' and lng != '')
        @search()
      else
        # Ask for location
        navigator.geolocation.getCurrentPosition @setGeolocation, @showError, timeout: 10000

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
          lat: @lat(),
          lng: @lng()
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
      # window.location.hash = 'show-more'
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

      setTimeout( =>
        if (el.value == init)
          @search()
      , 300)

    @setKeyword = (e) ->
      el = e.target
      init = el.value
      @dataStore().keyword = el.value
      @dataStore().search_type = 'keyword'
      @dataStore().page = 1
      @append = false

      setTimeout( =>
        if (el.value == init)
          @search()
      , 300)

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

    @init = () =>
      $("#slider-range").slider
        range: true,
        min: 0,
        max: 500,
        values: [@dataStore().min_price, @dataStore().max_price],
        slide: (event, ui) ->
          $("#amount").val(ui.values[0] + "€" + " - " + ui.values[1] + "€");
        stop: (event, ui) =>
          @dataStore().min_price = ui.values[0]
          @dataStore().max_price = ui.values[1]
          @append = false
          @search()

      $('.categories-list ul').hide()

      $('.category-item').click((e) -> 
        console.log(e.originalEvent.detail)
        if(e.originalEvent.detail > 1) 
          return
        $(this).next('ul').slideToggle("slow");
      )

      $("ul").find("[data-id='" + app.initData.mcId + "']").slideToggle("slow");

      $('.category-item').dblclick((e) ->
        window.location.href = $(this).data('url');
      )

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

      $("#amount").val($("#slider-range" ).slider( "values", 0 ) + "€ - " + $( "#slider-range" ).slider( "values", 1 ) + "€");
      businesses = @businesses()
      markers    = @makeMarkers(businesses)
      @renderMap('topmap', businesses[0].lat, businesses[0].lng, markers)

    # Kickstart
    @bigbang()

    return

  BusinessList.view = (ctrl) ->
    m('.container.business-container', [
      if (ctrl.environment() == 'tobook' and ctrl.count() >= 1)
        m('.row.hidden-xs', [
          m('.col-sm-12', [
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
                      class : 'form-control', 
                      placeholder: __('query_placeholder'),
                      onkeyup: ctrl.setKeyword.bind(ctrl)
                    })
                  ])
                ]),
                m('.form-group', [
                  m('.col-sm-12', [
                    m('input#city[type=text]', { 
                      class : 'form-control', 
                      placeholder: __('location_placeholder'),
                      onkeyup: ctrl.setCity.bind(ctrl)
                    })
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
