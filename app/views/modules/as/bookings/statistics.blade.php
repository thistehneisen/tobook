@extends ('modules.as.layout')

@section ('scripts')
    @parent
    <script>
$(function() {
    $('#js-stat').on('click', 'a.js-btn-reload', function(e) {
        e.preventDefault();
        var $this = $(this),
            target = $('#'+$this.prop('rel')),
            loading = target.siblings('div.js-loading');

        loading.show();
        $.ajax({
            url: $this.prop('href'),
            type: 'GET'
        }).done(function(data) {
            target.html(data);
            loading.hide();
        });

    });
})
    </script>
@stop

@section ('content')
<div id="js-stat">
    <div class="relative" id="js-calendar-stat">{{ $calendar }}</div>

    <div class="relative">
        <div class="js-loading"><i class="fa fa-refresh fa-spin fa-5x"></i></div>
        <div id="js-monthly-stat">{{ $monthly }}</div>
    </div>
</div>
@stop
