# global m, app
app.VaraaBusiness = (dom, keyword, location, mc, tc) ->
  'use strict'
  # Translation helper
  __ = (key) -> if app.i18n[key]? then app.i18n[key] else ''

  # ----------------------------------------------------------------------------
  # The main layout
  # ----------------------------------------------------------------------------

  BusinessList = {}
  BusinessList.controller = ->
    @dataStore = m.prop {keyword: keyword, location: location, priceRange: {}}
    
    @data = m.prop {}
    @businesses = m.prop []
    # Kickstart

    m.request
      method: 'GET'
      url: app.routes['business.search']
      data:
        hash: @dataStore().keyword
        location: @dataStore().location
    .then (data) =>
      @businesses  = data.businesses
      console.log(@businesses)
    .then () =>
      m.redraw()
      @init()
    
    @init = ->
      $("#slider-range").slider
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: ( event, ui ) ->
          $("#amount").val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

       $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $( "#slider-range" ).slider("values", 1));

    return

  BusinessList.view = (ctrl) ->
    m('div', [
      m('.col-sm-3.search-panel', [
        m('.row', [
          m('i.fa.fa-map-marker.fa-2x'),
          m.trust('View result on map')
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
                m('input#query[type=text]', { class : 'form-control', placeholder: __('query_placeholder')})
              ])
            ]),
            m('.form-group', [
              m('.col-sm-12', [
                m('input#location[type=text]', { class : 'form-control', placeholder: __('location_placeholder')})
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
              m('h3.venue-title', business.name),
              m('span.venue-desc', [
                m.trust(business.address),
                m.trust('&nbsp;'),
                m('a', [ m.trust('Show map &raquo;')])
              ]),
              m('.popular-services', [
                business.services.map((service) ->
                  m('.row popular-service', [
                    m('.col-xs-8', [
                      m('a', [ service.name ]) 
                    ]),
                    m('.col-xs-4', [
                      m('button.btn.btn-orange.btn-square.pull-right', [ __('select') ])
                    ])
                  ])
                )
              ])
            ])
          )
        else
          m('.loading', m('i.fa.fa-spin.fa-2x.fa-spinner'))
      ])
    ])

  # Render booking forms
  m.mount(dom, m.component(BusinessList))
