@extends ('layouts.admin')

@section ('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
@stop

@section ('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.{{ App::getLocale() }}.min.js"  charset="UTF-8"></script>
    <script>
$(function() {
    $('div.input-daterange').datepicker({
        language: '{{ App::getLocale() }}',
        format: 'yyyy-mm-dd'
    });
});
    </script>
@stop

@section ('content')
    <h3 class="comfortaa">Associated modules</h3>


    {{ Form::open(['route' => ['admin.users.modules', $user->id]]) }}
    <h3 class="comfortaa">Enable new module</h3>
    @include ('el.messages')

    @foreach ($modules as $module)
    <div class="radio">
        <label>
            {{ Form::radio('module_id', $module->id) }}
            {{ trans('dashboard.'.$module->name) }}
        </label>
    </div>
    @endforeach

    <div class="form-group">
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm form-control" name="start">
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm form-control" name="end">
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-primary">{{ trans('common.save') }}</button>
    </div>
    {{ Form::close() }}
@stop