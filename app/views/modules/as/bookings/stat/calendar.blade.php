<div class="row">
    <div class="col-md-1">
        <a href="#" class="btn btn-link"><i class="fa fa-arrow-left"></i> {{ trans('common.prev') }}</a>
    </div>
    <div class="col-md-7 text-center">
        <h4>{{ trans('common.'.strtolower(date('M'))), ' ', date('Y') }}</h4>
    </div>
    <div class="col-md-1 text-right">
        <a href="#" class="btn btn-link">{{ trans('common.next') }} <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="col-md-3">
        {{ Form::select('employee', $employeeSelect, null, ['class' => 'form-control input-sm']) }}
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
                <p>{{ trans('as.bookings.stat.revenue') }} <span class="pull-right">&euro;{{ $data['revenue'] }}</span></p>
                <p>{{ trans('as.bookings.stat.bookings') }} <span class="pull-right">{{ $data['bookings'] }}</span></p>
                <p>{{ trans('as.bookings.stat.working_time') }} <span class="pull-right">{{ $data['working_time'] }}</span></p>
                <p>{{ trans('as.bookings.stat.booked_time') }} <span class="pull-right">{{ $data['booked_time'] }}</span></p>
                <p>{{ trans('as.bookings.stat.occupation') }} <span class="pull-right">{{ $data['occupation_percent'] }}%</span></p>
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
