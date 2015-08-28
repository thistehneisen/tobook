    <div class="row">
        <div class="col-md-1">
            <a href="{{ route('as.reports.monthly.monthly', ['employee' => Input::get('employee'), 'date' => $prev->format('Y-m-d') ]) }}" class="btn btn-link js-btn-reload" rel="js-monthly-stat"><i class="fa fa-arrow-left"></i> {{ trans('common.prev') }}</a>
        </div>
        <div class="col-md-7 text-center">
            <h4>{{ trans('as.reports.stat.monthly') }}</h4>
        </div>
        <div class="col-md-1 text-right">
            <a href="{{ route('as.reports.monthly.monthly', ['employee' => Input::get('employee'), 'date' => $next->format('Y-m-d') ]) }}" class="btn btn-link js-btn-reload" rel="js-monthly-stat">{{ trans('common.next') }} <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="col-md-3">
            <select name="employee" class="form-control input-sm" rel="js-monthly-stat">
                <option data-url="{{ route('as.reports.monthly.monthly', ['date' => Input::get('date')]) }}">{{ trans('common.options_all') }}</option>
            @foreach ($employees as $employee)
                <option data-url="{{ route('as.reports.monthly.monthly', ['employee' => $employee->id, 'date' => Input::get('date')]) }}" {{ intval(Input::get('employee')) === $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
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
                    <p>{{ trans('as.reports.stat.revenue') }} <span class="pull-right">{{ show_money($report['revenue']) }}
                        @if (isset($report['gap']['revenue']))
                            @if ($report['gap']['revenue'] > 0)
                                <small class="text-success"><i class="fa fa-arrow-up"></i> <strong>{{ show_money($report['gap']['revenue']) }}</strong></small>
                            @elseif (($report['gap']['revenue'] < 0))
                                <small class="text-danger"><i class="fa fa-arrow-down"></i> <strong>{{ show_money($report['gap']['revenue']) }}</strong></small>
                            @endif
                        @endif
                    </span></p>

                    <p>{{ trans('as.reports.stat.bookings') }}
                    <span class="pull-right">{{ $report['bookings'] }}
                        @if (isset($report['gap']['bookings']))
                            @if ($report['gap']['bookings'] > 0)
                                <small class="text-success"><i class="fa fa-arrow-up"></i> <strong>{{ $report['gap']['bookings'] }}</strong></small>
                            @elseif ($report['gap']['bookings'] < 0)
                                <small class="text-danger"><i class="fa fa-arrow-down"></i> <strong>{{ $report['gap']['bookings'] }}</strong></small>
                            @endif
                        @endif
                    </span></p>
                    <p>{{ trans('as.reports.stat.working_time') }} <span class="pull-right">{{ $report['working_time'] }}</span></p>
                    <p>{{ trans('as.reports.stat.booked_time') }} <span class="pull-right">{{ $report['booked_time'] }}</span></p>

                    <p>{{ trans('as.reports.stat.occupation') }} <span class="pull-right">{{ $report['occupation_percent'] }}%
                        @if (isset($report['gap']['occupation_percent']))
                            @if ($report['gap']['occupation_percent'] > 0)
                                <small class="text-success"><i class="fa fa-arrow-up"></i> <strong>{{ $report['gap']['occupation_percent'] }}%</strong></small>
                            @elseif ($report['gap']['occupation_percent'] < 0)
                                <small class="text-danger"><i class="fa fa-arrow-down"></i> <strong>{{ $report['gap']['occupation_percent'] }}%</strong></small>
                            @endif
                        @endif
                    </span></p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
