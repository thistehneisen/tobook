@extends ('modules.as.layout')

@section ('scripts')
    @parent
    <script>
$(function() {
    var showContent = function(url, target) {
        var target =  $(target),
            loading = target.siblings('div.js-loading');

        loading.show();
        $.ajax({
            url: url,
            type: 'GET'
        }).done(function(data) {
            target.html(data);
            loading.hide();
        });
    };

    $('div.relative').on('click', 'a.js-btn-reload', function(e) {
        e.preventDefault();
        var $this = $(this);
        showContent($this.attr('href'), '#'+$this.attr('rel'));
    }).on('change', 'select[name=employee]', function(e) {
        e.preventDefault();
        var $this = $(this),
            selected = $this.find('option:selected').first();
        showContent(selected.data('url'), '#'+$this.attr('rel'));
    });
})
    </script>
@stop

@section ('content')
<div id="js-stat">
    <div class="relative">
        <div class="js-loading"><i class="fa fa-refresh fa-spin fa-5x"></i></div>
        <div id="js-calendar-stat">{{ $calendar }}</div>
    </div>

    <div class="relative">
        <div class="js-loading"><i class="fa fa-refresh fa-spin fa-5x"></i></div>
        <div id="js-monthly-stat">{{ $monthly }}</div>
    </div>
</div>
@stop
