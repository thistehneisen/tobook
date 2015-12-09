# global m, app
app.VaraaBusiness = (dom, id, type, keyword, location) ->
  'use strict'
  # Translation helper
  __ = (key) -> if app.i18n[key]? then app.i18n[key] else ''

  # ----------------------------------------------------------------------------
  # The main layout
  # ----------------------------------------------------------------------------

  BusinessList = {}
  BusinessList.controller = ->
    @dataStore = m.prop {id: id, type: type, keyword: keyword, search_type: '', location: location, page: 1, count: 1, priceRange: {}}
    
    @data       = m.prop {}
    @businesses = m.prop []
    @cache = []
    @append = true

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
          location: @dataStore().location
          page: @dataStore().page
      .then (data) =>
        if (@append)
          for business in data.businesses
            @cache.push business
          @businesses = @cache
        else
          @businesses = data.businesses

        @dataStore().page = data.current
        @dataStore().count = data.count
      .then () =>
        m.redraw()
        $('#show-more-spin').hide()
        window.location.hash = 'show-more'
        @init()

    @showMore = () ->
      window.location.hash = 'show-more'
      if (@dataStore().page < @dataStore().count)
        @dataStore().page += 1
        @search()
        if(@dataStore().page == @dataStore().count)
          $('.show-more').hide()

    @setLocation = (e) ->
      el = e.target
      @dataStore().location = el.value
      @dataStore().search_type = 'district'
      @append = false
      @search()

    @setKeyword = (e) ->
      el = e.target
      @dataStore().keyword = el.value
      @dataStore().search_type = 'keyword'
      @append = false
      @search()

    @init = ->
      $("#slider-range").slider
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: ( event, ui ) ->
          $("#amount").val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

       $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $( "#slider-range" ).slider("values", 1));

    # Kickstart
    @search()

    return

  BusinessList.view = (ctrl) ->
    m('.container', [
      m('.row',[
        m('.col-sm-3.search-panel', [
          m('.row', [
            m('i.fa.fa-map-marker.fa-2x.orange'),
            m.trust('&nbsp;'),
            m.trust(__('view_on_map'))
          ]),
          m('.row', [
            m('hr'),
            m('label[for=show_discount]',[
              m('input[type=checkbox][name=show_discount]'),
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
                  m('input#location[type=text]', { 
                    class : 'form-control', 
                    placeholder: __('location_placeholder'),
                    onkeyup: ctrl.setLocation.bind(ctrl)
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
            ])
          ])
        ]),
        m('.col-sm-9.business-list', [
          if (ctrl.businesses.length > 0)
            ctrl.businesses.map((business, index) ->
              m('.business-item',[
                m('h3.venue-title', [ 
                  m('a', { href: business.businessUrl }, [business.name]) 
                ]),
                m('span.venue-desc', [
                  m.trust(business.address),
                  m.trust(',&nbsp;'),
                  m.trust(business.city),
                  m.trust('&nbsp;'),
                  m('a', [
                    __('show_map'),
                    m.trust('&nbsp;&raquo;')
                  ])
                ]),
                m('.popular-services', [
                  business.services.map((service) ->
                    m('.row popular-service', [
                      m('.col-xs-8', [
                        m('span.orange.service-name', [service.name]) 
                      ]),
                      m('.col-xs-4', [
                        m('a.btn.btn-orange.btn-square.pull-right', { 
                            href: business.businessUrl + '/?serviceId=' + service.id 
                          }, [ __('select') ])
                      ])
                    ])
                  )
                ])
              ])
            )
          else
            m('.loading', m('i.fa.fa-spin.fa-2x.fa-spinner'))
        ])
      ]),
      m('.row', [
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
