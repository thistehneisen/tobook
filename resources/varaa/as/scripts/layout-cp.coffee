# global m, app
do ->
  'use strict'

  # ----------------------------------------------------------------------------
  # Panel Service
  # ----------------------------------------------------------------------------
  Service = {}
  Service.controller = (args) ->
    @categories = args.data().categories

    @selectService = (service, e) ->
      e.preventDefault()
      args.layout.selectService service

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

    # Get employees from server
    @employees = m.prop []
    # Selected employee
    @selectedEmployee = m.prop 0
    @getSelectedEmployee = -> @employees()[@selectedEmployee()]
    @fetchEmployees = ->
      m.request
        background: true
        method: 'GET'
        url: app.routes['business.booking.employees']
        data:
          serviceId: @layout.dataStore().service.id
          hash: @layout.dataStore().hash
      .then @employees

    # Get timetable from server
    @calendar = m.prop []
    @selectedDate = m.prop @layout.dataStore().date
    @fetchCalendar = ->
      return m.request
        method: 'GET'
        background: true
        url: app.routes['business.booking.timetable']
        data:
          serviceId: @layout.dataStore().service.id
          hash: @layout.dataStore().hash
          employeeId: @getSelectedEmployee().id
          date: @selectedDate()
      .then @calendar
      .then =>
        @selectedDate @calendar().selectedDate
        m.redraw()

    @selectDate = (date, e) ->
      e.preventDefault()
      @selectedDate date
      @fetchCalendar()
      return

    @selectTime = (opts, e) ->
      e.preventDefault()
      @layout.selectEmployee opts.employee
      @layout.selectDate opts.date
      @layout.selectTime opts.time
      return

    @selectEmployee = (employee, index, e) ->
      @selectedEmployee index
      @fetchCalendar()

    # Kickstart
    @fetchEmployees()
      .then =>
        @employees().unshift {id: -1, name: 'The first available employee'}
        @fetchCalendar()

    return

  Time.view = (ctrl) ->
    return m('.cbf-loading', m('i.fa.fa-spin.fa-2x.fa-spinner')) unless ctrl.getSelectedEmployee()?

    m('div', [
      m('.panel-group[id=js-booking-form-employee][role=tablist]', [
        m('.panel.panel-default', [
          m('.panel-heading[role=tab]', [
            m('h4.panel-title', [
              m('a[data-parent=#js-booking-form-employee][data-toggle=collapse][href=#js-booking-form-employees][role=button]', ctrl.getSelectedEmployee().name)
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
          m('.col-sm-1', m('a[href=#].date-selector-link', {onclick: ctrl.selectDate.bind(ctrl, ctrl.calendar().prevWeek)}, m('i.fa.fa-chevron-left'))),
          m('.col-sm-10', [
            m('ul.date-selector-dates', ctrl.calendar().dates.map((item) ->
              m('li', {
                class: if ctrl.selectedDate() is item.date then 'date-selector-dates-active' else ''
                onclick: ctrl.selectDate.bind(ctrl, item.date)
              }, [m('span', item.dayOfWeek), m('em', item.niceDate)])
            ))
          ]),
          m('.col-sm-1', m('a[href=#].date-selector-link', {onclick: ctrl.selectDate.bind(ctrl, ctrl.calendar().nextWeek)}, m('i.fa.fa-chevron-right')))
        ])
      ]),
      m('.row', [
        m('.col-sm-offset-1.col-sm-10', [
          m('ul.time-options', ctrl.calendar().calendar.map((opt) ->
            m('li', {onclick: ctrl.selectTime.bind(ctrl, opt)}, [
              m.trust("#{opt.time} &ndash; #{ctrl.layout.dataStore().service.price}&euro;"),
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

    @paymentOptions = m.prop []
    @fetchPaymentOptions = (amount) ->
      m.request
        background: true
        url: app.routes['business.booking.payments']
        method: 'GET'
        data:
          amount: amount
      .then @paymentOptions
      .then -> m.redraw()

    @validateCustomer = ->
      customer = @layout.dataStore().customer
      return ['first_name', 'last_name', 'phone', 'email']
        .map (field) -> customer[field]
        .every (value) -> value? and value.length

    # Kickstart
    @fetchPaymentOptions @layout.dataStore().service.price
    return

  Payment.view = (ctrl) ->
    dataStore = ctrl.layout.dataStore()
    paymentOptionView = if ctrl.paymentOptions().length > 0
      m('ul.row.list-inline.payment-option-list', [
        ctrl.paymentOptions().map((option) ->
          return m('li.col-sm-4', m('.payment-option', [
            m('img', {src: option.logo}),
            m('p', option.title)
          ]))
        )
      ])
    else
      m('.cbf-loading', m('i.fa.fa-spin.fa-2x.fa-spinner'))

    m('.payment', [
      m('.payment-section', [
        m('h4', 'Your booking is almost done'),
        m('form.row[action=]', [
          m('.form-group.col-sm-3', [
            m('label[for=]', 'First Name*'),
            m('input.form-control[type=text]', {value: dataStore.customer.first_name or '', onblur: ctrl.setCustomerInfo.bind(ctrl, 'first_name')})
          ]),
          m('.form-group.col-sm-3', [
            m('label[for=]', 'Last Name*'),
            m('input.form-control[type=text]', {value: dataStore.customer.last_name or '', onblur: ctrl.setCustomerInfo.bind(ctrl, 'last_name')})
          ]),
          m('.form-group.col-sm-3', [
            m('label[for=]', 'Email*'),
            m('input.form-control[type=email]', {value: dataStore.customer.email or '', onblur: ctrl.setCustomerInfo.bind(ctrl, 'email')})
          ]),
          m('.form-group.col-sm-3', [
            m('label[for=]', 'Phone*'),
            m('input.form-control[type=text]', {value: dataStore.customer.phone or '', onblur: ctrl.setCustomerInfo.bind(ctrl, 'phone')})
          ])
        ])
      ]),
      m('.payment-section', [
        m('h4', 'Booking details'),
        m('.row', [
          m('.col-sm-2', [m('p', m('strong', 'Salon')), dataStore.business.name]),
          m('.col-sm-3', [m('p', m('strong', 'Service')), dataStore.service.name]),
          m('.col-sm-2', [m('p', m('strong', 'Employee')), dataStore.employee.name]),
          m('.col-sm-3', [m('p', m('strong', 'Time')), "#{dataStore.date} #{dataStore.time}"]),
          m('.col-sm-2', [m('p', m('strong', 'Price')), m.trust("#{dataStore.service.price}&euro;")])
        ])
      ]),
      m('.payment-section', [
        m('h4', 'How do you want to pay for your booking?'),
        m('.row',
          m('.col-sm-12', [
            m('.locked', {class: if ctrl.validateCustomer() then 'hidden' else ''}),
            paymentOptionView
          ])
        )
      ])
    ])

  # ----------------------------------------------------------------------------
  # The main layout
  # ----------------------------------------------------------------------------

  LayoutCP = {}
  LayoutCP.controller = ->
    @dataStore = m.prop {hash: app.hash, customer: {}}

    # Fetch services JSON data
    @data = m.prop {}

    m.request
      method: 'GET'
      url: app.routes['business.booking.services']
    .then @data
    .then =>
      @dataStore().business = @data().business

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

    @selectService = (service) ->
      @dataStore().service = service
      @moveNext()
      return

    @selectEmployee = (employee) ->
      @dataStore().employee = employee
      return

    @selectDate = (date) ->
      @dataStore().date = date
      return

    @selectTime = (time) ->
      @dataStore().time = time
      @addBookingService()
        .then => @moveNext()

    @setCustomerInfo = (field, value) ->
      customer = @dataStore().customer

      customer = {} unless customer?
      customer[field] = value

      @dataStore().customer = customer
      return

    @addBookingService = ->
      ds = @dataStore()
      return m.request
        method: 'POST'
        url: app.routes['business.booking.book_service']
        data:
          service_id: ds.service.id
          employee_id: ds.employee.id
          inhouse: true
          hash: ds.hash
          booking_date: ds.date
          start_time: ds.time
      .then (data) ->
        ['uuid', 'price', 'cart_id', 'booking_service_id'].map (field) ->
          ds[field] = data[field]
          return
        return ds

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
