@if ($layout)
    @extends ($layout)
@endif

@section ('content')
    @if ($showTab === true)
        @include('olut::tabs', ['routes' => $routes, 'langPrefix' => $langPrefix])
    @endif

<div id="form-olut-upsert" class="modal-form">
    @include ('el.messages')

    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

    {{ Lomake::make($item, [
        'route'  => [$routes['upsert'], isset($item) ? $item->id : null],
        'trans'  => $langPrefix,
        'fields' => $lomake
    ]) }}
</div>
@stop
