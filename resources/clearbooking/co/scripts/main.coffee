do ($ = jQuery) ->
  'use strict';

  $ ->
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

