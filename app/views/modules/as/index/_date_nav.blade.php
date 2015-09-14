<div class="container as-date-nav">
    <div class="col-md-2">
        <div class="input-group hidden-print">
            <input type="text" data-index-url="{{ route($routeName, ['id' => $employeeId]) }}" id="calendar_date" class="form-control" value="{{ $selectedDate }}">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-8 hidden-print as-home-navigator">
        <a href="{{ route($routeName, ['date'=> Carbon\Carbon::today()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default btn-as-day">{{ trans('as.index.today') }}</a>
        <a href="{{ route($routeName, ['date'=> Carbon\Carbon::tomorrow()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default btn-as-day">{{ trans('as.index.tomorrow') }}</a>

        <div class="btn-group as-days-navigator">
            <a href="{{ route($routeName, ['date'=> with(clone $date)->subWeek()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link" id="prev-week"><i class="fa fa-fast-backward"></i></a>
            <a href="{{ route($routeName, ['date'=> with(clone $date)->subDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link" id="prev-day"><i class="fa fa-backward"></i></a>
            <a href="{{ route($routeName, ['date'=> with(clone $date)->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link" id="next-day"><i class="fa fa-forward"></i></a>
            <a href="{{ route($routeName, ['date'=> with(clone $date)->addWeek()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link" id="next-week"><i class="fa fa-fast-forward"></i></a>
        </div>

        <div class="btn-group as-days-of-week hidden-print">
            <?php
                $startOfWeek = with(clone $date)->startOfWeek();
                $endOfWeek = with(clone $date)->endOfWeek();
            ?>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::MONDAY) btn-primary @endif" id="day-mon">{{ trans('common.short.mon') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::TUESDAY) btn-primary @endif" id="day-tue">{{ trans('common.short.tue') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::WEDNESDAY) btn-primary @endif" id="day-wed">{{ trans('common.short.wed') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::THURSDAY) btn-primary @endif" id="day-thu">{{ trans('common.short.thu') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::FRIDAY) btn-primary @endif" id="day-fri">{{ trans('common.short.fri') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SATURDAY) btn-primary @endif" id="day-sat">{{ trans('common.short.sat') }}</a>
            <a href="{{ route($routeName, ['date'=> $endOfWeek->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SUNDAY) btn-primary @endif" id="day-sun">{{ trans('common.short.sun') }}</a>
        </div>
    </div>
</div>
