(($) ->
    $('document').on "focus", ".datepicker", ->
        $(this).datepicker ->
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            language: $('body').data 'locale'

    $('.workshift-editable').click ->
        $this = $(this)
        custom_time = CUSTOM_TIME
        current_custom_time_id = parseInt $this.data 'custom-time-id', 10
        if ($this.data 'editable')  == true

            $this.data 'editable', false

            dropdown = $('<select/>',
                class: 'form-control',
                style: 'max-width:70%; display:inline'
            )

            for key, val of custom_time
                $('<option />',
                    value: key.replace('@',''),
                    text: val
                ).appendTo dropdown

            btnOk = $('<input/>',
                value: 'OK',
                type: 'button',
                class: 'btn btn-primary btn-change-workshift'
            )

            if current_custom_time_id
                dropdown.val current_custom_time_id

            $this.empty()
            dropdown.appendTo $this
            btnOk.appendTo $this


    $(document).on 'click', '.btn-change-workshift', (e) ->
        e.preventDefault()
        $this = $(this)
        parentSpan = $this.closest 'div'
        workshiftSelect  = $this.prev 'select'
        custom_time_id   = workshiftSelect.val()
        custom_time_text = workshiftSelect.find("option:selected").text()
        url = $('#update_workshift_url').val()

        $.post(url,
            'custom_time_id': custom_time_id,
            'employee_id'   : parentSpan.data('employee-id'),
            'date'          : parentSpan.data('date'),
        ).done (data) ->
            parentSpan.data 'editable', true
            parentSpan.empty
            text = if (parseInt custom_time_id) == 0  then '--' else custom_time_text
            parentSpan.text(text)

) jQuery
