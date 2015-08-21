<div class="row">
    <div class="col-md-1">
        <a href="{{ route('as.reports.monthly.calendar', ['employee' => Input::get('employee'), 'date' => $prev->format('Y-m-d') ]) }}" class="btn btn-link js-btn-reload" rel="js-calendar-stat"><i class="fa fa-arrow-left"></i> {{ trans('common.prev') }}</a>
    </div>
    <div class="col-md-7 text-center">
        <h4>{{ trans('common.'.strtolower($date->format('M'))), ' ', $date->format('Y') }}</h4>
    </div>
    <div class="col-md-1 text-right">
        <a href="{{ route('as.reports.monthly.calendar', ['employee' => Input::get('employee'), 'date' => $next->format('Y-m-d') ]) }}" class="btn btn-link js-btn-reload" rel="js-calendar-stat">{{ trans('common.next') }} <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="col-md-3">
        <select name="employee" class="form-control input-sm" rel="js-calendar-stat">
            <option data-url="{{ route('as.reports.monthly.calendar', ['date' => Input::get('date')]) }}">{{ trans('common.options_all') }}</option>
        @foreach ($employees as $employee)
            <option data-url="{{ route('as.reports.monthly.calendar', ['employee' => $employee->id, 'date' => Input::get('date')]) }}" {{ intval(Input::get('employee')) === $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
        @endforeach
        </select>
    </div>
</div>

<table class="table table-stripped table-bordered table-statistics">
    <thead>
        <tr>
            <th>{{ trans('common.mon') }}</th>
            <th>{{ trans('common.tue') }}</th>
            <th>{{ trans('common.wed') }}</th>
            <th>{{ trans('common.thu') }}</th>
            <th>{{ trans('common.fri') }}</th>
            <th>{{ trans('common.sat') }}</th>
            <th>{{ trans('common.sun') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        @foreach ($calendar as $index => $data)
            <td>
            @if ($data !== null)
                <div class="date">{{ $data['day'] }}</div>
                <p>{{ trans('as.reports.stat.revenue') }} <span class="pull-right">{{ show_money($data['revenue']) }}</span></p>
                <p>{{ trans('as.reports.stat.bookings') }} <span class="pull-right">{{ $data['bookings'] }}</span></p>
                <p>{{ trans('as.reports.stat.working_time') }} <span class="pull-right">{{ $data['working_time'] }}</span></p>
                <p>{{ trans('as.reports.stat.booked_time') }} <span class="pull-right">{{ $data['booked_time'] }}</span></p>
                <p>{{ trans('as.reports.stat.occupation') }} <span class="pull-right">{{ $data['occupation_percent'] }}%</span></p>
            @endif
            </td>
            @if ($index % 7 === 6)
            </tr>
            <tr>
            @endif
        @endforeach
        </tr>
    </tbody>
</table>
