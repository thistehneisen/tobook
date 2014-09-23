@extends ($layout)

@section ('content')
    @include('modules.as.crud.tabs', ['routes' => $routes, 'langPrefix' => $langPrefix])

<div id="form-add-category" class="modal-form">
    @include ('el.messages')
    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

    {{ Lomake::make($item, [
        'route'      => [$routes['upsert'], isset($item) ? $item->id : null],
        'langPrefix' => $langPrefix
    ]) }}
</div>
@stop
