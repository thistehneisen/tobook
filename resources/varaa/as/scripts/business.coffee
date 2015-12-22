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
    @dataStore = m.prop { id: id, type: type, keyword: '', search_type: '', city: '', show_discount: false, page: 1, count: 1, min_price: 0, max_price: 500}
    
    @data        = m.prop {}
    @businesses  = m.prop []
    @environment = m.prop ''
    @count = m.prop 1
    @page = m.prop 1
    @cache = []
    @append = true

    @environment(app.initData.environment)

    @search = ->
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
          page: @dataStore().page
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
        zoom: 13

      if markers?
        @addMarkers gmap, markers
      return gmap

    @makeMarker =  (business) ->
      return {
        lat: business.lat
        lng: business.lng
        title: business.name
        infoWindow:
          content: "<table><tr><td><img src='" + business.image_url + "' style='width: 100px' /></td><td><p><strong>#{business.name}</strong></p><p>#{business.full_address}</p></td></tr></table>"
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
      @makeMapContainer('800px', '500px');
      $(".dialog").dialog({
            height: 500,
            width: 800,
            resizable: false,
            title: ""
      });
      $('.dialog').dialog('open');
      @renderMap('gmap', businesses[0].lat, businesses[0].lng, markers)
      $('#gmap').trigger('click');

    @getShowMoreStyle = () ->
      if (@count() == 1 || @page() == @count())
        return 'display:none'

    @init = () =>
      $("#slider-range").slider
        range: true,
        min: 0,
        max: 500,
        values: [@dataStore().min_price, @dataStore().max_price],
        slide: (event, ui) ->
          $("#amount").val( "$" + ui.values[0] + " - $" + ui.values[1]);
        stop: (event, ui) =>
          @dataStore().min_price = ui.values[0]
          @dataStore().max_price = ui.values[1]
          @append = false
          @search()

      $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $( "#slider-range" ).slider("values", 1));
      businesses = @businesses()
      markers    = @makeMarkers(businesses)
      @renderMap('gmap', businesses[0].lat, businesses[0].lng, markers)

    # Kickstart
    @search()

    return

  BusinessList.view = (ctrl) ->
    m('.container.business-container', [
      if (ctrl.environment() == 'tobook')
        m('.row', [
          m('.col-sm-12', [
            m('#topmap', { class: 'dialog', id: 'gmap', style: "height: 300px; width: 100%" } )
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
          m('.row', [
            m('hr'),
            m('label[for=show_discount]',[
              m('input[type=checkbox][name=show_discount]', { onclick: ctrl.setShowDiscount.bind(ctrl), value: true }),
              m.trust('&nbsp;'),
              m.trust('Only off-peak discounts'),
            ])
          ]),
          m('.row', [
            m('hr'),
            m('h4', [m.trust('Filter search result')]),
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
          ]),
          m('.row', [
            m('hr'),
            m('h4', [__('price_range')]),
            m('p', [
              m('label[for=amount]', [__('price_range')]),
              m.trust('&nbsp;'),
              m('input#amount[type=text]', { readonly: true, style: 'border:0; color:#f6931f; font-weight:bold;'} ),
              m('#slider-range')
            ]),
          ])
        ]),
        m('.col-sm-9.businesses', [
          if (ctrl.businesses().length > 0)
            ctrl.businesses().map((business, index) ->
              m('.item',[
                m('.information', [
                  m('.row', [
                    m('.col-xs-4',[
                      m('img', { src: business.image_url , style: 'width: 100%'})
                    ]),
                    m('.col-xs-8',[
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
                        m.trust(',&nbsp;'),
                        m.trust(business.city),
                        m.trust('&nbsp;'),
                        m('a[href=#]', { onclick: ctrl.showMap.bind(ctrl, business) }, [
                          __('show_map'),
                          m.trust('&nbsp;&raquo;')
                        ])
                      ]),
                      m('.row.contact', [
                        m('.col-xs-8', [
                          m('div', [
                             m('strong', [ __('phone')]),
                             m('span', [business.phone])
                          ]),
                          m('div', [
                             m('strong', [ __('email')]),
                             m('span', [business.user_email])
                          ])
                        ]),
                        m('.col-xs-4', [
                           m('strong', [ __('payment_methods') ]),
                           m('ul', [
                            business.payment_options.map((option, index) ->
                                m('li', [ __('payment.' + option) ])
                            )
                          ])
                        ])
                      ])
                    ])
                  ])
                ])
                m('.popular-services', [
                  business.services.map((service) ->
                    m('.row popular-service',  [
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
