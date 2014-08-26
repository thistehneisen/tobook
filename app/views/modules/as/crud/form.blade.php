@extends ('modules.as.layout')

@section ('content')
    @include ('modules.as.services.category.tabs')

<div id="form-add-category" class="modal-form">
    @include ('el.messages')
    @if (isset($item))
        <h4 class="comfortaa">{{ trans('as.services.edit_category') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans('as.services.add_category') }}</h4>
    @endif

    {{ Lomake::make($item, [
        'route' => ['as.services.categories.upsert', isset($item) ? $item->id : null],
        'trans' => 'as.services'
    ]) }}
</div>
@stop
