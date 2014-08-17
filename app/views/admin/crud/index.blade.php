@extends ('layouts.admin')

@section ('scripts')
    @parent
    <script>
$(function () {
    $('a.link-delete').on('click', function (e) {
        var confirmed = confirm('Are you sure?');
        if (confirmed === false) {
            e.preventDefault();
        }
    });
});
    </script>
@stop

@section ('content')
    <h3 class="comfortaa text-danger">List of {{ Request::segment(2) }}</h3>
    @include ('el.messages')

    @include ('admin.crud.list', $items)
@stop
