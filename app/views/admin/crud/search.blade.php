@extends ('admin.crud.index')

@section ('content')
    <h3 class="comfortaa text-danger">Search results for &quot;{{ Input::get('q') }}&quot;</h3>
    @include ('el.messages')

    @include ('admin.crud.list', $items)
@stop
