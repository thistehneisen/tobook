@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.setting.label') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::model($setting, array('route' => array('mt.settings.update', $setting->id), 'method' => 'PUT', 'class' => 'form-horizontal')) }}
        @foreach ([
            'campaign_id'         => trans('mt.campaign.label'),
            'sms_id'              => trans('mt.sms.label'),
            'module_type'         => trans('common.module_type'),
            'counts_prev_booking' => trans('mt.setting.counts_prev_booking'),
            'days_prev_booking'   => trans('mt.setting.days_prev_booking'),
        ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-6">
                @if ($key === 'campaign_id')
                    {{ Form::select('campaign_id'
                       , array('' => trans('mt.setting.select_campaign')) + $campaigns->lists('subject', 'id')
                       , null
                       , array('class' => 'form-control')) }}
                @elseif ($key === 'sms_id')
                    {{ Form::select('sms_id'
                       , array('' => trans('mt.setting.select_sms')) + $sms->lists('title', 'id')
                       , null
                       , array('class' => 'form-control')) }}                
                @elseif ($key === 'module_type')
                    {{ Form::select('module_type', $modules
                       , null
                       , array('class' => 'form-control')) }}                
                @else
                    {{ Form::text($key, null, ['class' => 'form-control']) }}
                @endif
                </div>
                <div class="col-sm-3">{{ $errors->first($key) }}</div>
            </div>
        @endforeach
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-10">
                {{ Form::submit(trans('mt.setting.edit'), ['class' => 'btn btn-primary', ]) }}
            </div>
        </div>
        {{ Form::close() }}    
    </div>
</div>
@section('scripts')
    <script src="{{ asset('assets/js/mt/common.js') }}" type="text/javascript"></script>
@stop

@stop
