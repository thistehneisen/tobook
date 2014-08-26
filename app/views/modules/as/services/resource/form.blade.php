@extends ('modules.as.layout')

@section ('content')
    @include ('modules.as.services.resource.tabs')
    @include ('el.messages')
    @if (isset($item))
        <h4 class="comfortaa">{{ trans('as.services.add_resource') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans('as.services.edit_resource') }}</h4>
    @endif

    {{ Lomake::make($item, [
        'route' => ['as.services.resources.upsert', isset($item) ? $item->id : null],
        'trans' => 'as.resources'
    ]) }}

@stop
