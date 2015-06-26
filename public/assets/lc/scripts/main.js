(function() {
  (function($) {
    'use strict';
    window.showConsumerInfo = function(consumerId, coreConsumerId) {
      if (coreConsumerId == null) {
        coreConsumerId = 0;
      }
      return $.ajax({
        url: VARAA.getRoute('consumers', {
          id: consumerId,
          coreid: coreConsumerId
        }),
        dataType: 'html',
        type: 'GET'
      }).done(function(data) {
        return $('#consumer-info').html(data);
      });
    };
    return $(function() {
      var $consumerInfo, $form, $givePointModal, consumerTr, showMessage;
      showMessage = function(title, body) {
        var $modal;
        $modal = $('#js-messageModal');
        $modal.find('.modal-title').text(title);
        $modal.find('.modal-body').html(body);
        return $modal.modal('show');
      };
      consumerTr = $('#consumer-table tbody tr');
      $form = $('#js-createConsumerForm');
      consumerTr.on('click', function() {
        var $me, consumerId, coreConsumerId;
        $me = $(this);
        if (!$me.hasClass('selected')) {
          consumerTr.removeClass('selected');
          $me.addClass('selected');
          consumerId = $me.data('consumerid');
          coreConsumerId = $me.data('coreconsumerid');
          return showConsumerInfo(consumerId, coreConsumerId);
        }
      });
      $form.bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
          first_name: {
            validators: {
              notEmpty: {
                message: 'First name is required'
              }
            }
          },
          last_name: {
            validators: {
              notEmpty: {
                message: 'Last name is required'
              }
            }
          },
          email: {
            validators: {
              regexp: {
                regexp: VARAA.regex_email_validation,
                message: 'Not valid email address'
              }
            }
          },
          phone: {
            validators: {
              notEmpty: {
                message: 'Phone number is required'
              },
              numeric: {
                message: 'Phone number must contain only numbers'
              }
            }
          }
        }
      });
      $('#js-createConsumerModal').on('click', '#js-cancelCreateConsumer', function() {
        $form.trigger('reset');
        $form.bootstrapValidator('resetForm', true);
        return $('#js-alert').addClass('hidden');
      });
      $form.on('success.form.bv', function(e) {
        var $me;
        e.preventDefault();
        $me = $(this);
        return $.ajax({
          url: $me.prop('action'),
          dataType: 'JSON',
          type: 'post',
          data: $me.serialize()
        }).done(function(data) {
          if (data.success === true) {
            return window.location.reload();
          }
        });
      });
      $consumerInfo = $('#consumer-info');
      $consumerInfo.on('click', '#js-back', function() {
        $consumerInfo.html('');
        return $consumerInfo.find('tr').removeClass('selected');
      });
      $consumerInfo.on('click', '#js-addStamp', function() {
        var $me, offerID;
        $me = $(this);
        offerID = $me.data('offerid');
        return $.ajax({
          url: $me.data('url'),
          dataType: 'JSON',
          type: 'PUT',
          data: {
            action: 'addStamp',
            offerID: offerID
          }
        }).done(function(data) {
          showMessage('Add Stamp', data.message);
          return $("#js-currentStamp" + offerID).text(data.stamps);
        });
      });
      $consumerInfo.on('click', '#js-useOffer', function() {
        var $me, offerID;
        $me = $(this);
        offerID = $me.data('offerid');
        return $.ajax({
          url: $me.data('url'),
          dataType: 'JSON',
          type: 'PUT',
          data: {
            action: 'useOffer',
            offerID: offerID
          }
        }).done(function(data) {
          showMessage('Use Offer', data.message);
          return $("#js-currentStamp" + offerID).text(data.stamps);
        });
      });
      $givePointModal = $('#js-givePointModal');
      $givePointModal.on('show.bs.modal', function(e) {
        return $(this).find('.modal-footer #js-confirmGivePoint').data('url', $(e.relatedTarget).data('url'));
      });
      $givePointModal.on('click', '#js-confirmGivePoint', function(e) {
        var $me;
        e.preventDefault();
        $me = $(this);
        $.ajax({
          url: $me.data('url'),
          type: 'PUT',
          data: {
            action: 'addPoint',
            points: $('#points').val()
          }
        }).done(function(data) {
          var msg;
          if (!data.success) {
            msg = '';
            $.each(data.errors, function(i, err) {
              return msg += '- ' + err + '\n';
            });
            return showMessage('Give Points', msg);
          } else {
            $givePointModal.modal('hide');
            $('#js-currentPoint').text(data.points);
            return showMessage('Give Points', data.message);
          }
        });
        return $('#js-givePointForm').trigger('reset');
      });
      $givePointModal.on('click', '#js-cancelGivePoint', function() {
        return $('#js-givePointForm').trigger('reset');
      });
      $consumerInfo.on('click', '#js-useVoucher', function() {
        var $currentPoint, $me, currentPoint, required, voucherId;
        $me = $(this);
        $currentPoint = $('#js-currentPoint');
        voucherId = $me.data('voucherid');
        required = parseInt($me.data('required'), 10);
        currentPoint = parseInt($currentPoint.text(), 10);
        if (currentPoint >= required) {
          return $.ajax({
            url: $me.data('url'),
            dataType: 'JSON',
            type: 'PUT',
            data: {
              action: 'usePoint',
              voucherID: voucherId
            }
          }).done(function(data) {
            $givePointModal.modal('hide');
            $currentPoint.text(data.points);
            return showMessage('Use Points', data.message);
          });
        } else {
          return showMessage('Use Points', 'Not enough point');
        }
      });
      return $consumerInfo.on('click', '#js-writeCard', function() {
        var $me, consumerId;
        $me = $(this);
        consumerId = $me.data('consumerid');
        external.SetCardWriteMode(true);
        if (!confirm('Put the card near the NFC card reader and press OK')) {
          external.SetCardWriteMode(false);
          return false;
        }
        $me.prop('disabled', true);
        if (external.WriteCard(consumerId) === true) {
          showMessage("Write to card", "Successful!");
        } else {
          showMessage("Write to card", "Error writing to card!");
        }
        $me.prop("disabled", false);
        return false;
      });
    });
  })(jQuery);

}).call(this);
