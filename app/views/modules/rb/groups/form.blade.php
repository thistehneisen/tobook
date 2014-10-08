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

    {{ $lomake->open() }}
    <div class="form-group {{ Form::errorCSS('name', $errors) }}">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('rb.groups.name') }}</label>
        <div class="col-sm-6">
            {{ $lomake->name }}
            {{ Form::errorText('name', $errors) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('rb.groups.tables') }}</label>
        <div class="col-sm-6">
            @foreach ($tables as $table)
            <div class="checkbox">
                <label>
                    {{ Form::checkbox('table_id[]', $table->id, in_array($table->id, $selectedTables)) }} {{ $table->name }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('rb.groups.description') }}</label>
        <div class="col-sm-6">
            {{ $lomake->description }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
</div>
@stop
