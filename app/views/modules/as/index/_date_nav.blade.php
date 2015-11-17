<div class="container as-date-nav">
    <div class="visible-print">
        {{ str_standard_to_local($selectedDate) }}
    </div>

    <div class="col-md-2">
        <div class="input-group hidden-print">
            <input type="text" data-index-url="{{ route($routeName, ['id' => $employeeId]) }}" id="calendar_date" class="form-control" value="{{ str_standard_to_local($selectedDate) }}">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-8 hidden-print">
        <a href="{{ route($routeName, ['date'=> str_date(Carbon\Carbon::today()), 'id'=> $employeeId]) }}" class="btn btn-default">{{ trans('as.index.today') }}</a>
        <a href="{{ route($routeName, ['date'=> str_date(Carbon\Carbon::tomorrow()), 'id'=> $employeeId]) }}" class="btn btn-default">{{ trans('as.index.tomorrow') }}</a>

        <div class="btn-group">
            <a href="{{ route($routeName, ['date'=> str_date($date->copy()->subWeek()), 'id'=> $employeeId]) }}" class="btn btn-link" id="prev-week"><i class="fa fa-fast-backward"></i></a>
            <a href="{{ route($routeName, ['date'=> str_date($date->copy()->subDay()), 'id'=> $employeeId]) }}" class="btn btn-link" id="prev-day"><i class="fa fa-backward"></i></a>
            <a href="{{ route($routeName, ['date'=> str_date($date->copy()->addDay()), 'id'=> $employeeId]) }}" class="btn btn-link" id="next-day"><i class="fa fa-forward"></i></a>
            <a href="{{ route($routeName, ['date'=> str_date($date->copy()->addWeek()), 'id'=> $employeeId]) }}" class="btn btn-link" id="next-week"><i class="fa fa-fast-forward"></i></a>
        </div>

        <div class="btn-group hidden-print">
            <?php
                $startOfWeek = $date->copy()->startOfWeek();
                $endOfWeek = $date->copy()->endOfWeek();
            ?>
            <a href="{{ route($routeName, ['date'=> str_date($startOfWeek), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::MONDAY) btn-primary @endif" id="day-mon">{{ trans('common.short.mon') }}</a>
            <a href="{{ route($routeName, ['date'=> str_date($startOfWeek->addDay()), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::TUESDAY) btn-primary @endif" id="day-tue">{{ trans('common.short.tue') }}</a>
            <a href="{{ route($routeName, ['date'=> str_date($startOfWeek->addDay()), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::WEDNESDAY) btn-primary @endif" id="day-wed">{{ trans('common.short.wed') }}</a>
            <a href="{{ route($routeName, ['date'=> str_date($startOfWeek->addDay()), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::THURSDAY) btn-primary @endif" id="day-thu">{{ trans('common.short.thu') }}</a>
            <a href="{{ route($routeName, ['date'=> str_date($startOfWeek->addDay()), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::FRIDAY) btn-primary @endif" id="day-fri">{{ trans('common.short.fri') }}</a>
            <a href="{{ route($routeName, ['date'=> str_date($startOfWeek->addDay()), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SATURDAY) btn-primary @endif" id="day-sat">{{ trans('common.short.sat') }}</a>
            <a href="{{ route($routeName, ['date'=> str_date($endOfWeek), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SUNDAY) btn-primary @endif" id="day-sun">{{ trans('common.short.sun') }}</a>
        </div>
    </div>
</div>
