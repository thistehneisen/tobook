@extends ('modules.co.layout')

@section ('content')
    @include ('el.messages')

{{ Form::open(['route' => ['consumer-hub.bulk'], 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'form-bulk']) }}
    <div class="form-group">
        <label for="upload" class="col-sm-2 control-label">{{ trans('co.consumers') }}</label>
        <div class="col-sm-5">
            @if ($sendAll)
                {{ trans('common.all') }}
                {{ Form::hidden('ids[]', 'all') }}
            @endif
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

    <div class="form-group {{ Form::errorCSS('campaign_id', $errors) }}">
        <label for="group_id" class="col-sm-2 control-label">{{ trans('co.email_templates.single') }}</label>
        <div class="col-sm-5">
           {{ Form::select('campaign_id', $campaignPairs, 0, ['id' => 'campaign_id', 'class' => 'form-control']) }}
           {{ Form::errorText('campaign_id', $errors) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::hidden('action', 'send_email') }}
{{ Form::close() }}

@stop
