@extends('modules.mt.layout')

@section('top-buttons')
<a href="{{ URL::route('mt.settings.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<!-- <button class="btn btn-default btn-danger js-deleteSetting"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button> -->
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.setting.label') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th><input type="checkbox" id="checkAll" /></th> -->
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.module_type') }}</th>
                <th>{{ trans('mt.setting.counts_prev_booking') }}</th>
                <th>{{ trans('mt.setting.days_prev_booking') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($settings as $key => $value)
            <tr>
                <!-- <td>
                    <input type="checkbox" id="chkSettingId" value="{{ $value->id }}" />
                </td> -->
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('mt.settings.edit', ['id' => $value->id]) }}">
                        <?php
                        $modules = [''   => trans('common.select_module'),
                                    'AS' => trans('common.appointment_scheduler'),
                                    'RB' => trans('common.restaurant_booking'),
                                    'TS' => trans('common.timeslot_booking'),
                                   ];
                        ?>
                        {{ $modules[$value->module_type] }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.settings.edit', ['id' => $value->id]) }}">
                        {{ $value->counts_prev_booking }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.settings.edit', ['id' => $value->id]) }}">
                        {{ $value->days_prev_booking }}
                    </a>
                </td>
                <td>
                    {{ $value->created_at }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
