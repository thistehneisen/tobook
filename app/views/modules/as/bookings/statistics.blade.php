@extends ('modules.as.layout')

@section ('content')
<div class="row">
    <div class="col-md-1">
        <a href="#" class="btn btn-primary btn-sm">Previous</a>
    </div>
    <div class="col-md-7 text-center">
        <h4>{{ date('F Y') }}</h4>
    </div>
    <div class="col-md-1 text-right">
        <a href="#" class="btn btn-primary btn-sm">Next</a>
    </div>
    <div class="col-md-3">
        <select name="employee" id="" class="form-control input-sm">
            <option value="">Employee 1</option>
            <option value="">Employee 2</option>
            <option value="">Employee 3</option>
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
        @foreach ($calendar as $index => $date)
            <td>
            @if ($date !== null)
                <div class="date">{{ $date }}</div>
                <p>Revenue <span class="pull-right">&euro;178</span></p>
                <p>Num. book. <span class="pull-right">&euro;178</span></p>
                <p>Work time <span class="pull-right">&euro;178</span></p>
                <p>Booked time <span class="pull-right">&euro;178</span></p>
                <p>Occupation % <span class="pull-right">&euro;178</span></p>
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
@stop
