# global m, app
do ->
  'use strict'

  # ----------------------------------------------------------------------------
  # Panel Service
  # ----------------------------------------------------------------------------
  Service = {}
  Service.controller = (args) ->
    @categories = args.data().categories
    # @categories = [
    #   {id: 1, name: 'Category 1', services: [
    #     {id: 1, name: 'Service 1', time: 60, price: 55.00, custom: []},
    #     {id: 2, name: 'Service 2', custom: [
    #       {id: 1, time: 45, price: 60.00},
    #       {id: 2, time: 45, price: 70.00}
    #     ]}
    #   ]},
    #   {id: 2, name: 'Category 2', services: []}
    # ]

    @selectService = args.layout.selectService.bind(args.layout)

    return
  Service.view = (ctrl) ->

    normalService = (service) ->
      m('.single-service', {onclick: ctrl.selectService.bind(ctrl, service)},[
        m('.row', [
          m('.col-md-10', [
            m('h4.panel-title', service.name),
            m('.service-description', service.description),
            m('p', "#{service.during}min")
          ]),
          m('.col-md-2', [
            m('button.btn.btn-orange.pull-right', 'Select')
          ])
        ])
      ])

    serviceWithCustomTimes = (service) ->
      m('.panel-group[id=js-cp-booking-form-categories-1][role=tablist]', {
          id: "js-cbf-service-#{service.id}"
        }, [
        m('.panel.panel-default', [
          m('.panel-heading[role=tab]', [
            m('h4.panel-title', [
              m('a[data-toggle=collapse][role=button]', {
                'data-parent': "#js-cbf-service-#{service.id}",
                href: "#js-cbf-service-#{service.id}-custom-times"
              }, service.name)
            ])
          ]),
          m('.panel-collapse.collapse.in[id=js-service-1][role=tabpanel]', {
              id: "js-cbf-service-#{service.id}-custom-times"
            }, [
            m('.panel-body', [
              service.custom.map((item) ->
                m('.service', {onclick: ctrl.selectService.bind(ctrl, item)}, [
                  m('.row', [
                    m('.col-md-10', [
                      m('.service-description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima aliquid fugit beatae labore provident unde, quasi, tempore, reiciendis amet inventore, delectus recusandae et dolorem ut maxime autem obcaecati pariatur corporis.'),
                      m('p', "#{item.time}min/ #{item.price}€")
                    ]),
                    m('.col-md-2', [
                      m('button.btn.btn-orange.pull-right', 'Select')
                    ])
                  ])
                ])
              )
            ])
          ])
        ])
      ])

    m('.panel-group[id=js-cbf-categories][role=tablist]', [
      ctrl.categories.map((category, index) ->
        m('.panel.panel-default', [
          m('.panel-heading[role=tab]', [
            m('h4.panel-title', [
              m('a[data-parent=#js-cbf-categories][data-toggle=collapse][role=button]', {
                  href: "#js-cbf-category-#{category.id}"
                }, [
                category.name,
                m('span.pull-right', if category.services.length then "#{category.services.length} services" else '')
              ])
            ])
          ]),
          m('.panel-collapse.collapse[role=tabpanel]', {
              id: "js-cbf-category-#{category.id}",
              class: if index is 0 then 'in' else ''
            }, [
            m('.panel-body', [
              m('.panel-group-service', category.services.map((service) ->
                v = if service.custom? and service.custom.length then serviceWithCustomTimes else normalService
                return v(service)
              ))
            ])
          ])
        ])
      )
    ])

  # ----------------------------------------------------------------------------
  # Panel Time
  # ----------------------------------------------------------------------------
  Time = {}
  Time.controller = (args) ->
    @layout = args.layout

    @calendar = [
      {dayOfWeek: 'ma', date: '17.8'},
      {dayOfWeek: 'ti', date: '18.8'},
      {dayOfWeek: 'ke', date: '19.8'},
      {dayOfWeek: 'to', date: '20.8'},
      {dayOfWeek: 'pe', date: '21.8'},
      {dayOfWeek: 'la', date: '22.8'},
      {dayOfWeek: 'su', date: '23.8'}
    ]

    @timeOptions = [0..18].map (i) -> {time: "#{i}:00", price: 45.50}

    # Get employees from server
    @employees = m.prop []
    m.request
      method: 'GET'
      url: app.routes['business.booking.employees']
      data:
        serviceId: @layout.dataStore().service.id
        hash: @layout.dataStore().hash
    .then @employees
    .then =>
      @employees().unshift {id: -1, name: 'Any'}

    # Selected employee
    @selectedEmployee = m.prop 0
    @getSelectedEmployee = -> @employees()[@selectedEmployee()]

    @selectTime = (time, e) ->
      e.preventDefault()
      @layout.selectTime time, e

    @selectEmployee = (employee, index, e) ->
      @selectedEmployee index
      @layout.selectEmployee employee, e
    return

  Time.view = (ctrl) ->
    m('div', [
      m('.panel-group[id=js-booking-form-employee][role=tablist]', [
        m('.panel.panel-default', [
          m('.panel-heading[role=tab]', [
            m('h4.panel-title', [
              m('a[data-parent=#js-booking-form-employee][data-toggle=collapse][href=#js-booking-form-employees][role=button]', ctrl.getSelectedEmployee().name || 'Any')
            ])
          ]),
          m('.panel-collapse.collapse[id=js-booking-form-employees][role=tabpanel]', [
            m('.panel-body', [
              m('.row', [
                m('.col-sm-offset-1.col-sm-10', [
                  m('ul.list-employees', ctrl.employees().map((employee, index) ->
                    m('li', {onclick: ctrl.selectEmployee.bind(ctrl, employee, index)}, employee.name)
                  ))
                ])
              ])
            ])
          ])
        ])
      ]),
      m('.date-selector', [
        m('.row', [
          m('.col-sm-1', [m('i.fa.fa-chevron-left')]),
          m('.col-sm-10', [
            m('ul.date-selector-dates', ctrl.calendar.map((item) ->
              m('li', [m('span', item.dayOfWeek),m('em', item.date)])
            ))
          ]),
          m('.col-sm-1.text-right', [m('i.fa.fa-chevron-right')])
        ])
      ]),
      m('.row', [
        m('.col-sm-offset-1.col-sm-10', [
          m('ul.time-options', ctrl.timeOptions.map((opt) ->
            m('li', {onclick: ctrl.selectTime.bind(ctrl, opt)}, [
              m.trust("#{opt.time} &ndash; #{opt.price}&euro;"),
              m('button.btn.btn-success', 'Select')
            ])
          ))
        ])
      ])
    ])

  # ----------------------------------------------------------------------------
  # Panel Payment
  # ----------------------------------------------------------------------------
  Payment = {}
  Payment.controller = (args) ->
    @layout = args.layout

    @setCustomerInfo = (field, e) ->
      el = e.target
      @layout.setCustomerInfo field, el.value

    return
  Payment.view = (ctrl) ->
    dataStore = ctrl.layout.dataStore()
    m('.payment', [
      m('.payment-section', [
        m('h4', 'Your booking is almost done'),
        m('form.row[action=]', [
          m('.form-group.col-sm-3', [
            m('label[for=]', 'First Name'),
            m('input.form-control[type=text]', {onblur: ctrl.setCustomerInfo.bind(ctrl, 'first_name')})
          ]),
          m('.form-group.col-sm-3', [
            m('label[for=]', 'Last Name'),
            m('input.form-control[type=text]', {onblur: ctrl.setCustomerInfo.bind(ctrl, 'last_name')})
          ]),
          m('.form-group.col-sm-3', [
            m('label[for=]', 'Email'),
            m('input.form-control[type=email]', {onblur: ctrl.setCustomerInfo.bind(ctrl, 'email')})
          ]),
          m('.form-group.col-sm-3', [
            m('label[for=]', 'Phone'),
            m('input.form-control[type=text]', {onblur: ctrl.setCustomerInfo.bind(ctrl, 'phone')})
          ])
        ])
      ]),
      m('.payment-section', [
        m('h4', 'Booking details'),
        m('.row', [
          m('.col-sm-3', 'HiusAkatemi'),
          m('.col-sm-3', dataStore.service.name),
          m('.col-sm-2', 'An Cao'),
          m('.col-sm-3', '08:00 12/08/2015'),
          m('.col-sm-1', m.trust("#{dataStore.service.price}&euro;"))
        ])
      ]),
      m('.payment-section', [
        m('h4', 'How do you want to pay for your booking?'),
        m('p', [m('img[alt=][src=https://www.mokejimai.lt/new/upload/plan_payment_types/rf52cbea8580fa5/visa-mastercard-maestro.jpeg]')])
      ])
    ])

  # ----------------------------------------------------------------------------
  # The main layout
  # ----------------------------------------------------------------------------

  LayoutCP = {}
  LayoutCP.controller = ->
    @dataStore = m.prop {hash: app.hash}

    # Fetch services JSON data
    @data = m.prop {}

    m.request
      method: 'GET'
      url: app.routes['business.booking.services']
    .then @data

    # The list of all panels in layout
    args = {layout: @, data: @data}
    @panels = [
      m.component(Service, args),
      m.component(Time, args),
      m.component(Payment, args)
    ]
    # Default panel is the first one
    @activePanel = m.prop 0

    # Set active panel backward or forward
    @move = (step) ->
      index = @activePanel() + step
      @activePanel(index) if @panels[index]?
      return

    @moveNext = -> @move(1)

    @moveBack = -> @move(-1)

    # Return the active panel in panel list
    @getActivePanel = -> @panels[@activePanel()]

    @showPreviousPanel = (e) ->
      e.preventDefault()
      @moveBack()

    # Hide Back button if active panel is the first one
    @shouldHideBackButton = -> @activePanel() is 0

    @selectService = (service, e) ->
      e.preventDefault()
      @dataStore().service = service
      @moveNext()

    @selectEmployee = (employee, e) ->
      e.preventDefault()
      @dataStore().employee = employee
      console.log @dataStore()
      return

    @selectTime = (time, e) ->
      e.preventDefault()
      @dataStore().time = time
      @moveNext()

    @setCustomerInfo = (field, value) ->
      customer = @dataStore().customer

      customer = {} unless customer?
      customer[field] = value

      @dataStore().customer = customer
      return

    return

  LayoutCP.view = (ctrl) ->
    m('.cp-booking-form', [
      m('.content', ctrl.getActivePanel()),
      m('.navigation', {
          class: if ctrl.activePanel() is 0 then 'hidden' else ''
        }, [
        m('a.btn.btn-orange[href=#]', {
          onclick: ctrl.showPreviousPanel.bind(ctrl),
          class: if ctrl.shouldHideBackButton() then 'hidden' else ''
        }, 'Go back')
      ])
    ])

  # Render booking forms
  m.mount(el, m.component(LayoutCP)) for el in document.querySelectorAll 'div.js-cp-booking-form'
