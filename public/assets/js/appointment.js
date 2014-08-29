(function ($) {
  $(function () {
    'use strict';

    $('.customer-tooltip').tooltip({
      'selector': '',
      'placement': 'top',
      'container': 'body'
    });
    $('.toggle-check-all-boxes').click(function () {
      var checkboxClass = ($(this).data('checkbox-class')) || 'checkbox';
      $('.' + checkboxClass).prop('checked', this.checked);
    });

    $('#form-bulk').on('submit', function (e) {
      e.preventDefault();
      var $this = $(this);
      alertify.confirm($this.data('confirm'), function (e) {
        if (e) {
          // user clicked "ok"
          $.ajax({
            type: 'POST',
            url: $this.attr('action'),
            data: $this.serialize(),
            dataType: 'json'
          }).done(function () {
            alertify.alert('OK');
            if ($('#mass-action').val() === 'destroy') {
              $("#form-bulk [type=checkbox]:checked").each(function () {
                $('#row-' + $(this).val()).remove();
              });
            }
          }).fail(function () {
            alertify.alert('Something went wrong');
          });
        }
      });
    });

    // Allow to click on TR to select checkbox
    $('table.table-crud tr').on('click', function (event) {
      var target = $(event.target);
      if (target.is('td')) {//fix bug cannot click to the actual checkbox
        var $this = $(this),
          checkbox = $this.find('td:first input:checkbox'),
          checked = checkbox.prop('checked');

        checkbox.prop('checked', !checked);
      }
    });

    // Date picker
    $('.date-picker').datepicker({
      format: 'yyyy-mm-dd'
    });

    // Backend Calendar
    $('.active').click(function (){
      var employee_id = $(this).data('employee-id');
      var time = $(this).data('time');
      $('.fancybox').fancybox({
        padding: 5,
        width: 350,
        title: '',
        autoSize: false,
        autoWidth: false,
        autoHeight: true
      });
    });
    $('#btn-continute-action').click(function (e){
      e.preventDefault();
      var selected_action = $('input[name="action_type"]:checked').val();
      if (selected_action === 'book'){
        $.fancybox.open({
          padding: 5,
          width: 850,
          title: '',
          autoSize: false,
          autoScale: true,
          autoWidth: false,
          autoHeight: true,
          fitToView : false,
          href: '#book-form'
        });
      } else if(selected_action === 'freetime'){

      }
    });
  });
}(jQuery));
