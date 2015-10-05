# global m, app
app.VaraaCPLayout = (dom, hash) ->
  'use strict'
  # Translation helper
  __ = (key) -> if app.i18n[key]? then app.i18n[key] else ''

  # ----------------------------------------------------------------------------
  # Panel Service
  # ----------------------------------------------------------------------------
  Service = {}
  Service.controller = (args) ->
    @categories = args.data().categories
    @priceRange = args.data().priceRange
    @hasDiscount = args.data().hasDiscount
    @servicesDiscount = args.data().servicesDiscount

    @selectService = (service, e) ->
      e.preventDefault()
      args.layout.selectService service

    @selectServiceTime = (serviceTime, service, e) ->
      e.preventDefault()
      args.layout.selectServiceTime serviceTime, service

    @showServiceCounter = (value) ->
      value = parseInt value, 10
      return "#{value} #{__('pl_service')}" if value > 1
      return "#{value} #{__('sg_service')}" if value is 1
      return ''

    @showServicePriceRange = (category, priceRange, hasDiscount) ->
      range = priceRange[category.id]['category']
      discount = hasDiscount[category.id]
      return m.trust("#{range}") if discount is false
      return [m.trust("#{range}&nbsp;"), m('i.fa.fa-tag.discount')] if discount is true

    @showServiceDiscount = (service) ->
      discount = this.servicesDiscount[service.id]
      return m.trust("#{service.name}") if discount is false
      return [m.trust("#{service.name}&nbsp;"), m('i.fa.fa-tag.discount')] if discount is true
    return

  Service.view = (ctrl) ->

    normalService = (category, priceRange, service) ->
      range = if priceRange[category.id]? then priceRange[category.id]['service'][service.id] else 0
      m('.single-service', {onclick: ctrl.selectService.bind(ctrl, service)},[
        m('.row', [
          m('.col-xs-8', [
            m('h4.panel-title', ctrl.showServiceDiscount(service)),
            m('.service-description', service.description),
            m('p', "#{service.during}min")
          ]),
          m('.col-xs-4',{class :'service-price'}, [
            m('span.range', [m.trust("#{range}")]),
            m('button.btn.btn-orange.btn-square.pull-right', __('select'))
          ])
        ])
      ])

    serviceWithCustomTimes = (category, priceRange, service) ->
      range = if priceRange[category.id]? then priceRange[category.id]['service'][service.id] else 0
      m('.panel-group.panel-custom-times[id=js-cp-booking-form-categories-1][role=tablist]', {
          id: "js-cbf-service-#{service.id}"
        }, [
        m('.panel.panel-default', [
          m('.panel-heading[role=tab]', [
            m('.row', [
              m('.col-xs-8', m('h4.panel-title',
                m('a[data-toggle=collapse][role=button]', {
                  'data-parent': "#js-cbf-service-#{service.id}",
                  href: "#js-cbf-service-#{service.id}-custom-times"
                }, ctrl.showServiceDiscount(service))
              )),
              m('.col-xs-4', {class :'service-price'}, [
                m('span.range', [m.trust("#{range}")]),
                m('a.btn.btn-orange.btn-square.pull-right[data-toggle=collapse]', {
                  'data-parent': "#js-cbf-service-#{service.id}",
                  href: "#js-cbf-service-#{service.id}-custom-times"
                }, __('select'))
              ])
            ])
          ]),
          m('.panel-collapse.collapse[id=js-service-1][role=tabpanel]', {
              id: "js-cbf-service-#{service.id}-custom-times"
            }, [
            m('.panel-body', [
              m('.text-center', [
                m('.custom-time-service', {onclick: ctrl.selectService.bind(ctrl, service)}, [
                  if service.name? then m('p', service.name) else m.trust('&nbsp;'),
                  m('.service-description', service.description),
                  m('p', "#{service.during}min")
                ]),
                service.service_times.map((item) ->
                  m('.custom-time-service', {onclick: ctrl.selectServiceTime.bind(ctrl, item, service)}, [
                    if service.name? then m('p', service.name) else m.trust('&nbsp;'),
                    m('.service-description', item.description),
                    m('p', "#{item.during}min")
                  ])
                )
              ])
            ])
          ])
        ])
      ])

    m('.panel-group[id=js-cbf-categories][role=tablist]', [
      ctrl.categories
        .filter((category) -> category.services.length > 0)
        .map((category, index) ->
          m('.panel.panel-default.panel-category', [
            m('.panel-heading[role=tab]', [
              m('h4.panel-title', [
                m('a[data-parent=#js-cbf-categories][data-toggle=collapse][role=button]', {
                    href: "#js-cbf-category-#{category.id}"
                  }, [
                  m('span.category-name', category.name),
                  m('span.pull-right', ctrl.showServicePriceRange(category, ctrl.priceRange, ctrl.hasDiscount))
                ]),
                m('.clearfix')
              ])
            ]),
            m('.panel-collapse.collapse[role=tabpanel]', {
                id: "js-cbf-category-#{category.id}"
              }, [
              m('.panel-body', [
                m('.panel-group-service', category.services.map((service) ->
                  v = if service.service_times? and service.service_times.length then serviceWithCustomTimes else normalService
                  return v(category, ctrl.priceRange, service)
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
      ds = @layout.dataStore()
      m.request
        background: true
        method: 'GET'
        url: app.routes['business.booking.employees']
        data:
          serviceTimeId: if ds.serviceTime? then ds.serviceTime.id else null
          serviceId: if ds.service? then ds.service.id else null
          hash: ds.hash
      .then @employees

    # Get timetable from server
    @calendar = m.prop []
    @selectedDate = m.prop @layout.dataStore().date
    @fetchCalendar = ->
      @showLoading true
      ds = @layout.dataStore()
      return m.request
        method: 'GET'
        background: true
        url: app.routes['business.booking.timetable']
        data:
          serviceTimeId: if ds.serviceTime? then ds.serviceTime.id else null
          serviceId: if ds.service? then ds.service.id else null
          hash: ds.hash
          employeeId: @getSelectedEmployee().id
          date: @selectedDate()
      .then @calendar
      .then =>
        @selectedDate @calendar().date
        @showLoading false
        m.redraw()

    @selectDate = (date, e) ->
      e.preventDefault()
      today = new Date()
        .setHours 0, 0, 0, 0
      dateObj = new Date date
        .setHours 0, 0, 0, 0

      return if e.currentTarget.classList.contains 'date-selector-dates-past'

      @selectedDate date
      @fetchCalendar()
      return

    @selectTime = (opts, e) ->
      e.preventDefault()
      @layout.selectEmployee opts.employee
      @layout.selectDate opts.date
      @layout.selectTime opts.time
      @layout.selectPrice opts.price, opts.discountPrice
      return

    @selectEmployee = (employee, index, e) ->
      @selectedEmployee index
      $ '#js-booking-form-employees'
        .collapse 'hide'
      @fetchCalendar()

    @getCssClass = (date) ->
      return 'date-selector-dates-past' if @calendar().unbookable.indexOf(date) isnt -1
      return 'date-selector-dates-active' if @selectedDate() is date
      return ''

    @showTime = (item) ->
      return [m('em', item.niceDate), m('span', item.dayOfWeek)] if item.hasDiscount is false
      return [m('em', item.niceDate), m('span', [m.trust("#{item.dayOfWeek}&nbsp;"), m('i.fa.fa-tag.discount')])] if item.hasDiscount is true

    # Kickstart
    @showLoading = m.prop false
    @fetchEmployees()
      .then =>
        defaultEmployee =
          id: -1
          name: __('first_employee')
          avatar: app.assets.employee_avatar

        @employees().unshift defaultEmployee
        @fetchCalendar()

    return

  Time.view = (ctrl) ->
    loading = m('.cbf-loading', m('i.fa.fa-spin.fa-2x.fa-spinner'))
    return loading unless ctrl.getSelectedEmployee()?

    if ctrl.showLoading() is true
      slots = loading
    else
      slots = m('.row', [
          m('.col-sm-12', [
            m('ul.time-options', ctrl.calendar().calendar.map((opt) ->
              ds = ctrl.layout.dataStore()
              if parseInt(opt.discountPrice, 10) <  parseInt(opt.price, 10)
                data = [
                  m.trust("#{opt.time} &ndash; "),
                  m('span.non-discount.price', [opt.price]),
                  m.trust(" &ndash; ")
                  m('span.discount.price', [opt.discountPrice]),
                  m.trust("&nbsp;"),
                  m('i.fa.fa-tag.discount'),
                  m('button.btn.btn-square.btn-success', __('select'))
                ]
              else
                data = [
                  m.trust("#{opt.time} &ndash; #{opt.price}&euro;"),
                  m('button.btn.btn-success', __('select'))
                ]
              m('li', {onclick: ctrl.selectTime.bind(ctrl, opt)}, data)
            ))
          ])
        ])

    m('div', [
      m('.panel-group.panel-group-employees[id=js-booking-form-employee][role=tablist]', [
        m('.panel.panel-default', [
          m('.panel-heading[role=tab]', [
            m('h4.panel-title', [
              m('a[data-parent=#js-booking-form-employee][data-toggle=collapse][href=#js-booking-form-employees][role=button]', [
                m('img.img-circle.employee-avatar', {src: ctrl.getSelectedEmployee().avatar}),
                m('span.employee-name', ctrl.getSelectedEmployee().name)
              ])
            ])
          ]),
          m('.panel-collapse.collapse[id=js-booking-form-employees][role=tabpanel]', [
            m('.panel-body', [
              m('.row', [
                m('.col-sm-offset-1.col-sm-10', [
                  m('ul.list-employees', ctrl.employees().map((employee, index) ->
                    m('li', {onclick: ctrl.selectEmployee.bind(ctrl, employee, index)}, [
                      m('img.img-circle.employee-avatar', {src: employee.avatar}),
                      employee.name
                    ])
                  ))
                ])
              ])
            ])
          ])
        ])
      ]),
      m('.date-selector', [
        m('a[href=#].date-selector-link', {onclick: ctrl.selectDate.bind(ctrl, ctrl.calendar().prevWeek)}, m('i.fa.fa-chevron-left')),
        m('ul.date-selector-dates', ctrl.calendar().dates.map((item) ->
          if(item.disabled == false)
            m('li', {
              class: ctrl.getCssClass.call(ctrl, item.date),
              onclick: ctrl.selectDate.bind(ctrl, item.date)
            }, ctrl.showTime(item))
          else
            m('li', {
              class: ctrl.getCssClass.call(ctrl, item.date)
            }, ctrl.showTime(item))
        )),
        m('a[href=#].date-selector-link', {onclick: ctrl.selectDate.bind(ctrl, ctrl.calendar().nextWeek)}, m('i.fa.fa-chevron-right'))
      ]),
      slots
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

    @isDisabledPayment = m.prop false
    # This URL will be submitted to if payment was disabled
    @payAtVenueUrl = m.prop null
    @paymentOptions = m.prop []
    @fetchPaymentOptions = (amount) ->
      m.request
        background: true
        url: app.routes['business.booking.payments']
        method: 'GET'
        data:
          hash: @layout.dataStore().hash
          amount: amount
          cart_id: @layout.dataStore().cart_id
      .then (data) =>
        # Should we disable payment options?
        @isDisabledPayment data.disabled_payment

        if data.disabled_payment is true
          @payAtVenueUrl data.url
        else
          @paymentOptions data.payment_methods
          @layout.setTransactionId data.transaction_id
      .then -> m.redraw()


    # --------------------------------------------------------------------------
    # Validation
    # --------------------------------------------------------------------------
    @validateCustomer = ->
      customer = @layout.dataStore().customer
      return ['first_name', 'last_name', 'phone', 'email']
        .map (field) -> customer[field]
        .every (value) -> value? and value.length

    @validationErrors = m.prop {}
    @getValidationErrorCss = (field) -> if @validationErrors()[field]? then 'has-error' else ''
    @getValidationError = (field) -> @validationErrors()[field] or ''

    #
    # Auxilary function to create FORM with provided input and submit it
    # normally
    #
    # @param string action
    # @param object inputs
    #
    createFormAndSubmit = (action, inputs) ->
      addInput = (name, value) ->
        input = document.createElement 'input'
        input.name = name
        input.value = value
        input.type = 'hidden'
        return input

      # Create a new form
      form = document.createElement 'form'
      form.method = 'POST'
      form.action = action

      for key, value of inputs
        form.appendChild addInput key, value

      # Firefox require the form to be attached to DOM tree in order to submit
      document.body.appendChild form
      # Submit it
      form.submit()

    # This property is used to prevent submitting duplicated requests
    # And show a locking curtain as well
    @lock = m.prop false

    # Error handler in case of failing validation
    whenPlacingBookingFailed = (res) =>
      if res.success is false
        @lock false
        # Reset all errors
        @validationErrors {}
        Object.keys res.message
          .map (field) =>
            @validationErrors()[field] = res.message[field].join '\n'
        # Close the payment modal
        $('#js-cbf-payment-modal').modal 'hide'
        m.redraw()

    @placeBooking = (paygate, e) ->
      return if @lock() is true
      e.preventDefault()

      # Assume the response is fine, submit payment info to paygate
      submitToPaygate = =>
        results = @paymentOptions().filter (item) -> item.key is paygate
        option = results[0]
        if option?
          createFormAndSubmit option.url, option.attr

      # Send request to place the booking
      @lock true
      m.redraw()
      @layout.placeBooking()
        .then submitToPaygate
        .then null, whenPlacingBookingFailed

    # --------------------------------------------------------------------------
    # Modal
    # --------------------------------------------------------------------------
    @shouldOpenModal = (e) ->
      return false if e.target.disabled is true
      if @isDisabledPayment() is false
        $('#js-cbf-payment-modal').modal('show')
        return

      @layout.placeBooking()
        .then => createFormAndSubmit @payAtVenueUrl(), @layout.dataStore()
        .then null, whenPlacingBookingFailed


    # Kickstart
    @fetchPaymentOptions @layout.dataStore().price
    return

  Payment.view = (ctrl) ->
    dataStore = ctrl.layout.dataStore()
    paymentOptionView = if ctrl.paymentOptions().length > 0
      m('ul.list-inline.payment-option-list', [
        ctrl.paymentOptions().map((option) ->
          return m('li.payment-option', {
            onclick: ctrl.placeBooking.bind(ctrl, option.key)
          }, [
            m('img', {src: option.logo}),
            m('br'),
            option.title
          ])
        )
      ])
    else
      m('.cbf-loading', m('i.fa.fa-spin.fa-2x.fa-spinner'))

    m('.payment', [
      m('.payment-section', [
        m('h4', __('almost_done')),
        m('form.row', [
          ['first_name', 'last_name', 'email', 'phone'].map (field) ->
            return m('.form-group.col-sm-3', {class: ctrl.getValidationErrorCss.call(ctrl, field)}, [
              m('label', __(field)+'*'),
              m('input.form-control.btn-square[type=text]', {
                value: dataStore.customer[field] or '',
                onkeyup: ctrl.setCustomerInfo.bind(ctrl, field)
              }),
              m('p.help-block', ctrl.getValidationError.call(ctrl, field))
            ])
        ])
      ]),
      m('.payment-section', [
        m('h4', __('details')),
        m('.row', [
          m('.col-sm-2', [m('p', m('strong', __('salon'))), dataStore.business.name]),
          m('.col-sm-3', [m('p', m('strong', __('service'))), dataStore.service.name]),
          m('.col-sm-2', [m('p', m('strong', __('employee'))), dataStore.employee.name]),
          m('.col-sm-3', [m('p', m('strong', __('time'))), "#{dataStore.date} #{dataStore.time}"]),
          m('.col-sm-2', [m('p', m('strong', __('price'))), m.trust("#{dataStore.price}&euro;")])
        ])
      ]),
      m('.payment-section.text-center', [
        m('button.btn.btn-lg.btn-square.btn-book.btn-success', {
          disabled: ctrl.validateCustomer() is no or ctrl.lock() is yes,
          onclick: ctrl.shouldOpenModal.bind(ctrl)
        }, __('book'))
      ]),
      m('.modal.fade[id=js-cbf-payment-modal][role=dialog][tabindex=-1]', [
        m('.modal-dialog[role=document]', [
          m('.modal-content', [
            m('.modal-header', [
              m('button.close[data-dismiss=modal][type=button]', [m('span[aria-hidden=true]', m.trust('&times;'))]),
              m('h4.modal-title', __('how_to_pay'))
            ]),
            m('.modal-body', m('.row', m('.col-sm-12', paymentOptionView))),
            m('.modal-footer', [
              m('button.btn.btn-default[data-dismiss=modal][type=button]', __('close'))
            ])
          ])
        ])
      ])
    ])

  # ----------------------------------------------------------------------------
  # The main layout
  # ----------------------------------------------------------------------------

  LayoutCP = {}
  LayoutCP.controller = ->
    @dataStore = m.prop {hash: hash, customer: {}}

    # Fetch services JSON data
    @data = m.prop {}

    m.request
      method: 'GET'
      url: app.routes['business.booking.services']
      data:
        hash: @dataStore().hash
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

    @selectServiceTime = (serviceTime, service) ->
      @dataStore().serviceTime = serviceTime
      @dataStore().service = service
      @moveNext()
      return

    @selectEmployee = (employee) ->
      @dataStore().employee = employee
      return

    @selectDate = (date) ->
      @dataStore().date = date
      return

    @selectPrice = (originalPrice, discountPrice) ->
      if discountPrice
        @dataStore().price = discountPrice
      else
        @dataStore().price = originalPrice
      @dataStore().originalPrice = originalPrice;
      return

    @selectTime = (time) ->
      @dataStore().time = time
      @addBookingService()
        .then => @moveNext()

    @setTransactionId = (id) -> @dataStore().transaction_id = id
    @setBookingId = (id) -> @dataStore().booking_id = id

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
          hash: ds.hash
          booking_date: ds.date
          start_time: ds.time
      .then (data) ->
        ['uuid', 'cart_id', 'booking_service_id'].map (field) ->
          ds[field] = data[field]
          return
        return ds

    @placeBooking = ->
      ds = @dataStore()
      return m.request
        method: 'POST'
        url: app.routes['business.booking.book']
        data:
          l: 'cp'
          uuid: ds.uuid
          hash: ds.hash
          terms: true # Auto-select the terms
          phone: ds.customer.phone
          email: ds.customer.email
          source: 'cp'
          cart_id: ds.cart_id
          last_name: ds.customer.last_name
          first_name: ds.customer.first_name
          json_messages: true

    return

  LayoutCP.view = (ctrl) ->
    m('.cp-booking-form', [
      m('.content', ctrl.getActivePanel()),
      m('.navigation', {
          class: if ctrl.activePanel() is 0 then 'hidden' else ''
        }, [
        m('a.btn.btn-orange.btn-square[href=#]', {
          onclick: ctrl.showPreviousPanel.bind(ctrl),
          class: if ctrl.shouldHideBackButton() then 'hidden' else ''
        }, __('go_back'))
      ])
    ])

  # Render booking forms
  m.mount(dom, m.component(LayoutCP))
