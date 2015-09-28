# jslint browser: true, nomen: true, unparam: true
# global $, jQuery, external, VARAA, confirm
do ($ = jQuery) ->
  'use strict'

  # global function to be accessed from the desktop app
  window.showConsumerInfo = (consumerId, coreConsumerId = 0) ->
    $.ajax
      url: VARAA.getRoute 'consumers', {id: consumerId, coreid: coreConsumerId}
      dataType: 'html'
      type: 'GET'
    .done (data) ->
      $ '#consumer-info'
        .html data

  $ ->
    showMessage = (title, body) ->
      $modal = $ '#js-messageModal'
      $modal.find '.modal-title'
        .text title
      $modal.find '.modal-body'
        .html body
      $modal.modal 'show'

    consumerTr = $ '#consumer-table tbody tr'
    $form = $ '#js-createConsumerForm'

    # Fetch consumer info
    consumerTr.on 'click', ->
      $me = $ @
      unless $me.hasClass 'selected'
        consumerTr.removeClass 'selected'
        $me.addClass 'selected'

        consumerId = $me.data('consumerid')
        coreConsumerId = $me.data('coreconsumerid')
        showConsumerInfo consumerId, coreConsumerId

    # Create consumer
    $form.bootstrapValidator
      message: 'This value is not valid'
      feedbackIcons:
        valid: 'glyphicon glyphicon-ok'
        invalid: 'glyphicon glyphicon-remove'
        validating: 'glyphicon glyphicon-refresh'
      fields:
        first_name:
          validators:
            notEmpty:
              message: 'First name is required'
        last_name:
          validators:
            notEmpty:
              message: 'Last name is required'
        email:
          validators:
            regexp:
              regexp: VARAA.regex_email_validation,
              message: 'Not valid email address'
        phone:
          validators:
            notEmpty:
              message: 'Phone number is required'
            numeric:
              message: 'Phone number must contain only numbers'

    # Reset form when cancelling
    $ '#js-createConsumerModal'
      .on 'click', '#js-cancelCreateConsumer', ->
        $form.trigger 'reset'
        $form.bootstrapValidator 'resetForm', true
        $ '#js-alert'
          .addClass 'hidden'

    # Trigger form submit when click confirm
    $form.on 'success.form.bv', (e) ->
      e.preventDefault()
      $me = $ @

      $.ajax
        url: $me.prop 'action'
        dataType: 'JSON'
        type: 'post'
        data: $me.serialize()
      .done (data) ->
        if data.success is true
          window.location.reload()

    # Hide consumer info
    $consumerInfo = $ '#consumer-info'
    $consumerInfo.on 'click', '#js-back', ->
      $consumerInfo.html ''
      $consumerInfo.find 'tr'
        .removeClass 'selected'

    # Add stamp
    $consumerInfo.on 'click', '#js-addStamp', ->
      $me = $ @
      offerID = $me.data 'offerid'

      $.ajax
        url: $me.data 'url'
        dataType: 'JSON'
        type: 'PUT'
        data:
          action: 'addStamp'
          offerID: offerID
      .done (data) ->
        showMessage 'Add Stamp', data.message
        $ "#js-currentStamp#{offerID}"
          .text data.stamps

    # Use offer
    $consumerInfo.on 'click', '#js-useOffer', ->
      $me = $ @
      offerID = $me.data 'offerid'

      $.ajax
        url: $me.data 'url'
        dataType: 'JSON'
        type:  'PUT'
        data:
          action: 'useOffer'
          offerID: offerID
      .done (data) ->
        showMessage 'Use Offer', data.message
        $ "#js-currentStamp#{offerID}"
          .text data.stamps

    # Add point
    $givePointModal = $ '#js-givePointModal'
    $givePointModal.on 'show.bs.modal', (e) ->
      $ @
        .find '.modal-footer #js-confirmGivePoint'
        .data 'url', $(e.relatedTarget).data 'url'

    $givePointModal.on 'click', '#js-confirmGivePoint', (e) ->
      e.preventDefault()
      $me = $ @

      $.ajax
        url: $me.data 'url'
        type: 'PUT',
        data:
          action: 'addPoint'
          points: $('#points').val()
      .done (data) ->
        unless data.success
          msg = ''
          $.each data.errors, (i, err) ->
            msg += '- ' + err + '\n'
          showMessage 'Give Points', msg
        else
          $givePointModal.modal 'hide'
          $('#js-currentPoint').text data.points
          showMessage 'Give Points', data.message

      $('#js-givePointForm').trigger 'reset'

    $givePointModal.on 'click', '#js-cancelGivePoint', ->
      $('#js-givePointForm').trigger 'reset'

    # Use point
    $consumerInfo.on 'click', '#js-useVoucher', ->
      $me = $ @
      $currentPoint = $ '#js-currentPoint'

      voucherId = $me.data 'voucherid'
      required = parseInt $me.data('required'), 10
      currentPoint = parseInt $currentPoint.text(), 10

      if currentPoint >= required
        $.ajax
          url: $me.data 'url'
          dataType: 'JSON'
          type: 'PUT'
          data:
            action: 'usePoint'
            voucherID: voucherId
        .done (data) ->
          $givePointModal.modal 'hide'
          $currentPoint.text data.points

          showMessage 'Use Points', data.message
      else
        showMessage 'Use Points', 'Not enough point'

    # Write card
    $consumerInfo.on 'click', '#js-writeCard', ->
      $me = $ @
      consumerId = $me.data 'consumerid'
      external.SetCardWriteMode true

      unless confirm 'Put the card near the NFC card reader and press OK'
        external.SetCardWriteMode false
        return false

      $me.prop 'disabled', true

      if external.WriteCard(consumerId) is true
        showMessage "Write to card", "Successful!"
      else
        showMessage "Write to card", "Error writing to card!"

      $me.prop "disabled", false
      return false
