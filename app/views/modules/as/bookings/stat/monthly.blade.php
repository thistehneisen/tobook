    <div class="row">
        <div class="col-md-1">
            <a href="{{ route('as.bookings.statistics.monthly', $queries + ['date' => $prev->format('d-m-Y') ]) }}" class="btn btn-link js-btn-reload" rel="js-monthly-stat"><i class="fa fa-arrow-left"></i> {{ trans('common.prev') }}</a>
        </div>
        <div class="col-md-7 text-center">
            <h4>{{ trans('as.bookings.stat.monthly') }}</h4>
        </div>
        <div class="col-md-1 text-right">
            <a href="{{ route('as.bookings.statistics.monthly', $queries + ['date' => $next->format('d-m-Y') ]) }}" class="btn btn-link js-btn-reload" rel="js-monthly-stat">{{ trans('common.next') }} <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="col-md-3">
            <select name="employee" class="form-control input-sm" rel="js-monthly-stat">
                <option data-url="{{ route('as.bookings.statistics.monthly') }}">-- {{ trans('common.all') }} --</option>
            @foreach ($employees as $employee)
                <option data-url="{{ route('as.bookings.statistics.monthly', $queries + ['employee' => $employee->id ]) }}" {{ Input::get('employee') === $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
            @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        @foreach ($monthly as $report)
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">{{ $report['month'] }}</h3>
                </div>
                <div class="panel-body">
                    <p>{{ trans('as.bookings.stat.revenue') }} <span class="pull-right">&euro;{{ $report['revenue'] }}</span></p>
                    <p>{{ trans('as.bookings.stat.bookings') }} <span class="pull-right">{{ $report['bookings'] }}</span></p>
                    <p>{{ trans('as.bookings.stat.working_time') }} <span class="pull-right">{{ '' }}</span></p>
                    <p>{{ trans('as.bookings.stat.booked_time') }} <span class="pull-right">{{ $report['booked_time'] }}</span></p>
                    <p>{{ trans('as.bookings.stat.occupation') }} <span class="pull-right">{{ '' }}%</span></p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
