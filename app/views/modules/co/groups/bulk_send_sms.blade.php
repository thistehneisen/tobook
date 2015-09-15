@extends ('modules.co.layout')

@section ('content')
    @include ('el.messages')

{{ Form::open(['route' => ['consumer-hub.groups.bulk'], 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'form-bulk']) }}
    <div class="form-group">
        <label for="upload" class="col-sm-2 control-label">{{ trans('co.groups.groups') }}</label>
        <div class="col-sm-5">
            <ul>
                @foreach($targets as $group)
                    <li>
                        {{{ $group->name }}}
                        ({{ Lang::choice('co.x_consumers', $group->consumers()->count(), ['count' => $group->consumers()->count()]) }})
                        {{ Form::hidden('ids[]', $group->id) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="form-group {{ Form::errorCSS('sms_id', $errors) }}">
        <label for="group_id" class="col-sm-2 control-label">{{ trans('co.send_sms') }}</label>
        <div class="col-sm-5">
           {{ Form::select('sms_id', $smsPairs, 0, ['id' => 'sms_id', 'class' => 'form-control']) }}
           {{ Form::errorText('sms_id', $errors) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">{{ trans('common.send') }}</button>
        </div>
    </div>

    {{ Form::hidden('action', 'send_sms') }}
{{ Form::close() }}

@stop
