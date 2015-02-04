@if ($layout)
    @extends ($layout)
@endif

@section ('scripts')
    @parent

    @if (!empty($scripts))
        @include($scripts)
    @endif
@stop

@section ('content')
    @if ($showTab === true)
        @include($tabsView, ['routes' => $routes, 'langPrefix' => $langPrefix])
    @endif

<div id="form-olut-upsert" class="modal-form">
    @include ('el.messages')

    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

    {{ $lomake }}
</div>
@stop
