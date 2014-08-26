@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.setting.label') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'mt.settings.store']) }}
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label('campaign_id', trans('mt.campaign.label')) }}
                        {{ Form::select('campaign_id'
                                       , array('' => trans('mt.setting.select_campaign')) + $campaigns
                                       , null
                                       , array('class' => 'form-control')) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label('sms_id', trans('mt.sms.label')) }}
                        {{ Form::select('sms_id'
                                       , array('' => trans('mt.setting.select_sms')) + $sms
                                       , null
                                       , array('class' => 'form-control')) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label('module_type', trans('common.module_type')) }}
                        {{ Form::select('module_type', [''   => trans('common.select_module'),
                                                        'AS' => trans('common.appointment_scheduler'),
                                                        'RB' => trans('common.restaurant_booking'),
                                                        'TS' => trans('common.timeslot_booking'),
                                                       ]
                                                     , null
                                                     , array('class' => 'form-control')) }}
                    </div>
                </td>
            </tr>
            @foreach ([
                'counts_prev_booking' => trans('mt.setting.counts_prev_booking'),
                'days_prev_booking'   => trans('mt.setting.days_prev_booking'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        {{ Form::text($key, null, ['class' => 'form-control']) }}
                        {{ $errors->first($key) }}
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                {{ Form::submit(trans('mt.setting.create'), ['class' => 'btn btn-primary', ]) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@section('scripts')
    <script src="{{ asset('assets/js/mt/common.js') }}" type="text/javascript"></script>
@stop

@stop
