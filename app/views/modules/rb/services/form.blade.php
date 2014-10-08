@if ($layout)
@extends ($layout)
@endif

@section ('styles')
@parent
{{ HTML::style(asset('packages/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')) }}
@stop

@section ('scripts')
@parent
{{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
{{ HTML::script(asset('packages/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')) }}
@stop

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

    {{ Form::open(['route' => [$routes['upsert'], !empty($item->id) ? $item->id : ''], 'class' => 'form-horizontal', 'role' => 'form']) }}
    @foreach ($lomake->fields as $field)
    @if ($field->name === 'start_at' || $field->name === 'end_at')
    <div class="form-group {{ Form::errorCSS($field->name, $errors) }}">
        <label for="{{ $field->name }}" class="col-sm-2 col-sm-offset-1 control-label">{{ $field->label }}</label>
        <div class="col-sm-6">
            <div class='input-group date timepicker'>
                {{ Form::text($field->name, !empty($item->id) ? Input::get($field->name, $field->name === 'start_at' ? $item->start_at : $item->end_at) : null, ['class' => 'form-control', 'data-date-format' => 'HH:mm:ss']) }}
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
            {{ Form::errorText($field->name, $errors) }}
        </div>
    </div>
    @else
    <div class="form-group {{ Form::errorCSS($field->name, $errors) }}">
        <label for="{{ $field->name }}" class="col-sm-2 col-sm-offset-1 control-label">{{ $field->label }}</label>
        <div class="col-sm-6">
            {{ $field->render() }}
            {{ Form::errorText($field->name, $errors) }}
        </div>
    </div>
    @endif
    @endforeach

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
</div>
@stop
