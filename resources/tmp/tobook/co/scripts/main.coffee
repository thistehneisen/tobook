do ($ = jQuery) ->
  'use strict';

  $ ->
    # show history
    $ '.js-showHistory'
      .on 'click', (e) ->
        e.preventDefault()
        $me = $ @

        $.ajax
          url: $me.prop 'href'
          type: 'GET'
          data:
            id: $me.data 'consumerid'
            service: $me.data 'service'
        .done (content) ->
          $modal = $ '#js-historyModal'
          $modal.find '.modal-body'
            .html content
          $modal.modal 'show'

    # inject ids input to bypass bulk validation
    $ '#olut-mass-action'
      .on 'change', (e) ->
        e.preventDefault()
        $me = $ @

        if $me.val() in ['send_all_email', 'send_all_sms']
          if $('#all_ids').length is 0
            $me.after '<input id="all_ids" type="hidden" name="ids[]" value="all" />'
        else
          $('#all_ids').remove()
