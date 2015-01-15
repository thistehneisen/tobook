@extends ('modules.as.crud.index')

@section ('scripts')
{{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    <script>
$(function() {
    $('table.table-crud').find('a.btn-danger').on('click', function(e) {
        e.preventDefault();
        var $this = $(this),
            url = $this.attr('href');
        // var reason = prompt($this.data('delete-reason'), $this.data('delete-reason-default'));

        alertify.prompt("Prompt", $this.data('delete-reason'), $this.data('delete-reason-default'),
          function(evt, value ){
            alertify.success('OK: ' + value);
            if (value !== null) {
                url += '?reason='+encodeURI(value);
                window.location = url;
            }
          },
          function(){
            alertify.error('Cancel');
        });
    });
});
    </script>
@stop
