@extends ('modules.co.layout')

@section ('content')
    @include('modules.co.tabs')

    @include ('el.messages')

{{ Form::open(['route' => ['consumer-hub.bulk'], 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'form-bulk']) }}
    <div class="form-group">
        <label for="upload" class="col-sm-2 control-label">{{ trans('co.groups.consumers') }}</label>
        <div class="col-sm-5">
            <ul>
                @foreach($consumers as $consumer)
                    <li>
                        {{{ $consumer->name }}}
                        {{ Form::hidden('ids[]', $consumer->id) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="form-group {{ Form::errorCSS('group_id', $errors) }}">
        <label for="group_id" class="col-sm-2 control-label">{{ trans('co.group') }}</label>
        <div class="col-sm-5">
           {{ Form::select('group_id', $groupPairs, 0, ['id' => 'group_id', 'class' => 'form-control']) }}
           {{ Form::errorText('group_id', $errors) }}
        </div>
    </div>

    <div class="form-group {{ Form::errorCSS('new_group_name', $errors) }}">
        <label for="new_group_name" class="col-sm-2 control-label">{{ trans('co.groups.new_group') }}</label>
        <div class="col-sm-5">
           {{ Form::text('new_group_name', '', ['id' => 'new_group_name', 'class' => 'form-control']) }}
           {{ Form::errorText('new_group_name', $errors) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::hidden('action', 'group') }}
{{ Form::close() }}

@stop
